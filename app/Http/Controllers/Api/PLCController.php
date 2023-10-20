<?php

namespace App\Http\Controllers\Api;

use App\Enums\Location;
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameLog;
use App\Models\Station;
use App\Models\Task;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PLCController extends Controller
{
    public function tryOpenDoor(Request $request): Response
    {
        $game = Game::query()
            ->where('chip', $request->get('chip'))
            ->first();

        if (!$game instanceof Game) {
            return response()->json([
                'open' => false,
                'typeOfPlayer' => null,
            ]);
        }

        if (intval($request->get('doorId')) === 1) {
            GameLog::query()->create([
                'game_id' => $game->id,
                'chip' => $game->chip,
                'type' => 'info',
                'action' => 'Začátek hry',
            ]);

            $game->update([
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (intval($request->get('doorId')) === 2) {
            GameLog::query()->create([
                'game_id' => $game->id,
                'chip' => $game->chip,
                'type' => 'info',
                'action' => 'Konec hry',
            ]);

            $game->update([
                'ended_at' => now(),
            ]);
        }

        return response()->json([
            'open' => true,
            'typeOfPlayer' => $game->type,
        ]);
    }

    public function labyrint(Request $request): Response
    {
        $chip = $request->json('chip');

        if (!$chip) {
            return response()->json([
                'success' => false,
                'error' => 'Missing chip',
            ], Response::HTTP_BAD_REQUEST);
        }

        $game = Game::query()
            ->whereNull('time')
            ->where('chip', $chip)
            ->first();

        if (!$game instanceof Game) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid chip',
            ], Response::HTTP_BAD_REQUEST);
        }

        $points = $request->json('points', 0);
        $time = $request->json('time', '00:00');
        $note = $request->json('note', '');

        $game->update([
            'points' => $game->points + $points,
            'time' => $time,
        ]);

        GameLog::query()
            ->create([
                'game_id' => $game->id,
                'chip' => $game->chip,
                'type' => 'task_labyrint',
                'location' => 'labyrint',
                'action' => $note,
            ]);

        return response();
    }

    public function task(Request $request): Response
    {
        $chip = $request->get('chip');

        if (!$chip) {
            return response()->json([
                'success' => false,
                'error' => 'Missing chip',
            ], Response::HTTP_BAD_REQUEST);
        }

        $game = Game::query()->where('chip', $chip)->first();

        if (!$game instanceof Game) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid chip',
            ], Response::HTTP_BAD_REQUEST);
        }

        $task = $this->chooseTask($game);

        if (!$task instanceof Task) {
            return response()->json([
                'success' => false,
                'error' => 'All the tasks are done',
            ], Response::HTTP_BAD_REQUEST);
        }

        $game->update([
            'current_task_id' => $task->id,
        ]);

        GameLog::query()
            ->create([
                'game_id' => $game->id,
                'chip' => $game->chip,
                'type' => 'task_start',
                'task_id' => $task->id,
                'location' => $task->station->location,
                'action' => 'Začátek úkolu: '.$task->name,
            ]);

        return response()->json([
            'sound' => $task->{'sound'.ucfirst($game->language->value)}?->number,
        ]);
    }

    public function answer(Request $request): Response
    {
        $chip = $request->json('chip');

        if (!$chip) {
            return response()->json([
                'success' => false,
                'error' => 'Missing chip',
            ], Response::HTTP_BAD_REQUEST);
        }

        $game = Game::query()->where('chip', $chip)->first();

        if (!$game instanceof Game) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid chip',
            ], Response::HTTP_BAD_REQUEST);
        }

        $task = $game->currentTask;
        if (!$task instanceof Task) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid task',
            ], Response::HTTP_BAD_REQUEST);
        }

        $answer = $request->json('answer');

        $correctAnswer = array_map('intval', $answer) === array_map('intval', $task->getAnswerClean('response_correct'));
        if ($correctAnswer) {
            $game->update([
                'current_task_id' => null,
                'streak' => $game->streak + 1,
                'points' => $game->points + $task->points_correct,
            ]);

            GameLog::query()
                ->create([
                    'game_id' => $game->id,
                    'chip' => $game->chip,
                    'type' => 'task_end_correct',
                    'task_id' => $task->id,
                    'location' => $task->station->location,
                    'action' => 'Správná odpověď na úkol: '.$task->name,
                ]);

            return response()->json([
                'eval' => 'ok',
            ]);
        }

        $partialAnswer = array_map('intval', $answer) === array_map('intval', $task->getAnswerClean('response_partial'));
        if ($partialAnswer) {
            $game->update([
                'current_task_id' => null,
                'points' => $game->points + $task->points_partial,
            ]);

            GameLog::query()
                ->create([
                    'game_id' => $game->id,
                    'chip' => $game->chip,
                    'type' => 'task_end_partial',
                    'task_id' => $task->id,
                    'location' => $task->station->location,
                    'action' => 'Částečná odpověď na úkol: '.$task->name,
                ]);

            return response()->json([
                'eval' => 'ok',
            ]);
        }

        $game->update([
            'current_task_id' => null,
            'streak' => $game->streak - 1,
            'points' => $game->points + $task->points_incorrect,
        ]);

        GameLog::query()
            ->create([
                'game_id' => $game->id,
                'chip' => $game->chip,
                'type' => 'task_end_incorrect',
                'task_id' => $task->id,
                'location' => $task->station->location,
                'action' => 'Špatná odpověď na úkol: '.$task->name,
            ]);

        return response()->json([
            'eval' => 'fail',
        ]);
    }

    private function chooseTask(Game $game): ?Task
    {
        $isInProgress = $this->isInProgress($game);

        if ($isInProgress) {
            return $game->currentTask;
        }

        $difficulty = $this->clamp($game->level->value + $game->streak, 1, 5);

        foreach ($game->level->rules() as $location => $number) {
            $prev = GameLog::query()
                ->where('game_id', $game->id)
                ->where('location', $location)
                ->get();

            $locationDone = count($prev) >= $number;

            if ($locationDone) {
                continue;
            }

            $stations = Station::query()
                ->where('location', $location)
                ->pluck('id');

            $task = Task::query()
                ->whereNotIn('id', $prev->pluck('task_id'))
                ->whereIn('station_id', $stations)
                ->where('difficulty', $difficulty)
                ->inRandomOrder()
                ->first();

            if ($task instanceof Task) {
                return $task;
            }

            GameLog::query()
                ->create([
                    'game_id' => $game->id,
                    'chip' => $game->chip,
                    'type' => 'task_end_incorrect',
                    'task_id' => $task->id,
                    'location' => $task->station->location,
                    'action' => sprintf('Úkol s obtížnosti %d v lokaci %s nebyl nalezen', $difficulty, Location::from($location)->toString()),
                ]);
        }

        return null;
    }

    private function isInProgress(Game $game): bool
    {
        return $game->currentTask !== null;
    }

    private function clamp(int $value, int $min, int $max): int
    {
        return min(max($value, $min), $max);
    }
}

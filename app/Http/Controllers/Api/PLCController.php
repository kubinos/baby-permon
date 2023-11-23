<?php

namespace App\Http\Controllers\Api;

use App\Enums\GameType;
use App\Enums\Level;
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
            ->where(function ($q) {
                $q
                    ->whereNull('ended_at')
                    ->orWhereIn('type', ['operator', 'accompaniment']);
            })
            ->first();

        if (!$game instanceof Game) {
            return response()->json([
                'open' => false,
                'typeOfPlayer' => null,
            ]);
        }

        if (intval($request->get('doorId')) === 1) {
            $hasEntered = GameLog::query()
                ->where('game_id', $game->id)
                ->where('action', 'Začátek hry')
                ->exists();

            GameLog::query()->create([
                'game_id' => $game->id,
                'chip' => $game->chip,
                'type' => 'info',
                'action' => 'Začátek hry (hráč prošel dveřmi)',
            ]);

            if (!$hasEntered) {
                $game->update([
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        if (intval($request->get('doorId')) === 2) {
            GameLog::query()->create([
                'game_id' => $game->id,
                'chip' => $game->chip,
                'type' => 'info',
                'action' => 'Konec hry (hráč prošel dveřmi)',
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

        $isBetterTime = $points > $game->labyrint_points;

        if ($isBetterTime) {
            $gamePoints = $game->points - $game->labyrint_points;

            $game->update([
                'points' => $gamePoints + $points,
                'labyrint_points' => $points,
                'labyring_time' => $time,
            ]);
        }

        GameLog::query()
            ->create([
                'game_id' => $game->id,
                'chip' => $game->chip,
                'type' => 'task_labyrint',
                'action' => sprintf('Průchod labyrintem (body: %d; čas: %s) %s', $points, $time, $note),
            ]);

        return response()->json([
            'success' => true,
        ]);
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

        $game = Game::query()
            ->where('type', 'player')
            ->where('chip', $chip)
            ->first();

        if (!$game instanceof Game) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid chip',
            ], Response::HTTP_BAD_REQUEST);
        }

        $task = $this->chooseTask($game);

        if (!$task instanceof Task) {
            GameLog::query()
                ->create([
                    'game_id' => $game->id,
                    'chip' => $game->chip,
                    'type' => 'game_done',
                    'action' => 'Pokus o načtení úkolu po dokončení hry',
                ]);

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
                'action' => 'Načtení úkolu: '.$task->name,
            ]);

        return response()->json([
            'task' => $task->name,
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
                    'action' => sprintf("Správná odpověď na úkol: %s - počet bodů: %d (player: %s)", $task->name, $task->points_correct, implode(',', $answer)),
                ]);

            return response()->json([
                'eval' => 'ok',
                'labyrint' => $this->didYouJustFinishUnderWorld($game, $task),
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
                    'action' => sprintf("Částečná odpověď na úkol: %s - počet bodů: %d (player: %s, ok: %s)", $task->name, $task->points_partial, implode(',', $answer), implode(',', $task->getAnswerClean('response_correct'))),
                ]);

            return response()->json([
                'eval' => 'partial',
                'labyrint' => $this->didYouJustFinishUnderWorld($game, $task),
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
                'action' => sprintf("Špatná odpověď na úkol: %s - počet bodů: %d (player: %s, ok: %s)", $task->name, $task->points_incorrect, implode(',', $answer), implode(',', $task->getAnswerClean('response_correct'))),
            ]);

        return response()->json([
            'eval' => 'fail',
            'labyrint' => $this->didYouJustFinishUnderWorld($game, $task),
        ]);
    }

    private function chooseTask(Game $game): ?Task
    {
        $isInProgress = $this->isInProgress($game);

        if ($isInProgress) {
            return $game->currentTask;
        }

        $difficulty = $this->clamp($game->level->value + $game->streak, 1, 5);

        /** @var Level $level */
        $level = $game->level;

        foreach ($level->rules() as $location => $number) {
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
                    'action' => sprintf('Úkol s obtížnosti %d v lokaci %s nebyl nalezen', $difficulty, Location::from($location)->toString()),
                ]);
        }

        return null;
    }

    private function didYouJustFinishUnderWorld(Game $game, Task $task): bool
    {
        if ($task->station->location !== Location::UnderWorld) {
            return false;
        }

        if ($game->type !== GameType::Player) {
            return false;
        }

        $gameLogCount = GameLog::query()
            ->where('game_id', $game->id)
            ->where('type', 'like', 'task_end%')
            ->where('location', Location::UnderWorld->value)
            ->count();


        /** @var Level $level */
        $level = $game->level;

        return $level->rules()[Location::UnderWorld->value] === $gameLogCount;
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

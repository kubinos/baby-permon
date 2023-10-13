<?php

namespace App\Http\Controllers\Api;

use App\Enums\Color;
use App\Enums\Difficulty;
use App\Enums\Number;
use App\Enums\Shape;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\Rules\Enum;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    public function index(): JsonResource
    {
        return TaskResource::collection(
            Task::with(['station', 'soundCs', 'soundEn', 'soundDe'])
                ->get()
        );
    }

    public function show(int $taskId): JsonResource
    {
        return new TaskResource(
            Task::with(['station', 'soundCs', 'soundEn', 'soundDe'])
                ->findOrFail($taskId)
        );
    }

    public function store(Request $request): Response
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'difficulty' => ['required', new Enum(Difficulty::class)],
            'stationId' => ['required', 'integer', 'exists:station,id'],
            'soundCsId' => ['required', 'integer', 'exists:sound,id'],
            'soundEnId' => ['required', 'integer', 'exists:sound,id'],
            'soundDeId' => ['required', 'integer', 'exists:sound,id'],
            'responseNumber' => ['required', 'string', new Enum(Number::class)],
            'responseColor' => ['required', 'string', new Enum(Color::class)],
            'responseShape' => ['required', 'string', new Enum(Shape::class)],
            'pointsCorrect' => ['required', 'integer'],
            'pointsPartial' => ['required', 'integer'],
            'pointsIncorrect' => ['required', 'integer'],
        ]);

        $task = Task::query()
            ->create([
                'name' => $data['name'],
                'difficulty' => $data['difficulty'],
                'station_id' => $data['stationId'],
                'sound_cs_id' => $data['soundCsId'],
                'sound_en_id' => $data['soundEnId'],
                'sound_de_id' => $data['soundDeId'],
                'response_number' => $data['responseNumber'],
                'response_color' => $data['responseColor'],
                'response_shape' => $data['responseShape'],
                'points_correct' => $data['pointsCorrect'],
                'points_partial' => $data['pointsPartial'],
                'points_incorrect' => $data['pointsIncorrect'],
            ]);

        return response()->json(
            [
                'data' => new TaskResource($task),
            ],
            Response::HTTP_CREATED
        );
    }

    public function update(Request $request, int $taskId): Response
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'difficulty' => ['required', new Enum(Difficulty::class)],
            'stationId' => ['required', 'integer', 'exists:station,id'],
            'soundCsId' => ['required', 'integer', 'exists:sound,id'],
            'soundEnId' => ['required', 'integer', 'exists:sound,id'],
            'soundDeId' => ['required', 'integer', 'exists:sound,id'],
            'responseNumber' => ['required', 'string', new Enum(Number::class)],
            'responseColor' => ['required', 'string', new Enum(Color::class)],
            'responseShape' => ['required', 'string', new Enum(Shape::class)],
            'pointsCorrect' => ['required', 'integer'],
            'pointsPartial' => ['required', 'integer'],
            'pointsIncorrect' => ['required', 'integer'],
        ]);

        $task = Task::query()
            ->findOrFail($taskId);

        $task->update([
            'name' => $data['name'],
            'difficulty' => $data['difficulty'],
            'station_id' => $data['stationId'],
            'sound_cs_id' => $data['soundCsId'],
            'sound_en_id' => $data['soundEnId'],
            'sound_de_id' => $data['soundDeId'],
            'response_number' => $data['responseNumber'],
            'response_color' => $data['responseColor'],
            'response_shape' => $data['responseShape'],
            'points_correct' => $data['pointsCorrect'],
            'points_partial' => $data['pointsPartial'],
            'points_incorrect' => $data['pointsIncorrect'],
        ]);

        return response()->noContent();
    }

    public function destroy(int $taskId): Response
    {
        Task::destroy($taskId);

        return response()->noContent();
    }
}

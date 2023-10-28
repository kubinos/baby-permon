<?php

namespace App\Http\Controllers\Api;

use App\Enums\Difficulty;
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
            'correct1' => ['required', 'string'],
            'correct2' => ['required', 'string'],
            'correct3' => ['required', 'string'],
            'correct4' => ['required', 'string'],
            'pointsCorrect' => ['required', 'integer'],
            'partial1' => ['required', 'string'],
            'partial2' => ['required', 'string'],
            'partial3' => ['required', 'string'],
            'partial4' => ['required', 'string'],
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
                'response_correct' => $this->decideColumns('correct', $data),
                'points_correct' => $data['pointsCorrect'],
                'response_partial' => $this->decideColumns('partial', $data),
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
            'correct1' => ['required', 'string'],
            'correct2' => ['required', 'string'],
            'correct3' => ['required', 'string'],
            'correct4' => ['required', 'string'],
            'pointsCorrect' => ['required', 'integer'],
            'partial1' => ['required', 'string'],
            'partial2' => ['required', 'string'],
            'partial3' => ['required', 'string'],
            'partial4' => ['required', 'string'],
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
            'response_correct' => $this->decideColumns('correct', $data),
            'points_correct' => $data['pointsCorrect'],
            'response_partial' => $this->decideColumns('partial', $data),
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

    private function decideColumns(string $type, array $data): array
    {
        $gameColumns = [
            'o' => 1,
            'b' => 1,
            '1' => 1,
            't' => 2,
            'g' => 2,
            '2' => 2,
            'c' => 3,
            'r' => 3,
            '3' => 3,
            's' => 4,
            'y' => 4,
            '4' => 4,
        ];

        $response = ['0', '0', '0', '0'];
        $admin = ['0', '0', '0', '0'];

        for ($i = 1; $i <= 4; $i++) {
            $item = $data[sprintf('%s%s', $type, $i)];
            if ($item === '0') {
                continue;
            }

            $column = $gameColumns[$item];

            $response[$column - 1] = (string) $i;
            $admin[$column - 1] = $item;
        }

        $arr = [];
        for ($i = 0; $i < 4; $i++) {
            $arr[$i] = $response[$i] . $admin[$i];
        }

        return $arr;
    }
}

<?php

namespace App\Http\Resources;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'difficulty' => $this->difficulty,
            'station' => $this->station,
            'soundCs' => $this->soundCs,
            'soundEn' => $this->soundEn,
            'soundDe' => $this->soundDe,
            'correct1' => Task::parseResponse($this->response_correct)[0],
            'correct2' => Task::parseResponse($this->response_correct)[1],
            'correct3' => Task::parseResponse($this->response_correct)[2],
            'correct4' => Task::parseResponse($this->response_correct)[3],
            'pointsCorrect' => $this->points_correct,
            'partial1' => Task::parseResponse($this->response_partial)[0],
            'partial2' => Task::parseResponse($this->response_partial)[1],
            'partial3' => Task::parseResponse($this->response_partial)[2],
            'partial4' => Task::parseResponse($this->response_partial)[3],
            'pointsPartial' => $this->points_partial,
            'pointsIncorrect' => $this->points_incorrect,
        ];
    }
}

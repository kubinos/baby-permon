<?php

namespace App\Http\Resources;

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
            'responseNumber' => $this->response_number,
            'responseColor' => $this->response_color,
            'responseShape' => $this->response_shape,
            'pointsCorrect' => $this->points_correct,
            'pointsPartial' => $this->points_partial,
            'pointsIncorrect' => $this->points_incorrect,
        ];
    }
}

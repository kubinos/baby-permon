<?php

namespace App\Http\Controllers\Api;

use App\Enums\Difficulty;
use App\Enums\Emotion;
use App\Enums\GameType;
use App\Enums\Level;
use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfigLevelController extends Controller
{
    public function index(): Response
    {
        $config = Config::query()
            ->first();

        return response()->json([
            'data' => [
                'level_1' => $config->threshold_level_1,
                'level_2' => $config->threshold_level_2,
                'level_3' => $config->threshold_level_3,
            ]
        ]);
    }

    public function enums(): Response
    {
        return response()->json([
            'game_types' => array_map(fn (GameType $enum) => ['key' => $enum->value, 'value' => $enum->toString()], GameType::cases()),
            'emotions' => array_map(fn (Emotion $enum) => ['key' => $enum->value, 'value' => $enum->toString()], Emotion::cases()),
            'difficulties' => array_map(fn (Difficulty $enum) => ['key' => $enum->value, 'value' => $enum->toString()], Difficulty::cases()),
            'levels' => array_map(fn (Level $enum) => ['key' => $enum->value, 'value' => $enum->toString()], Level::cases()),
        ]);
    }

    public function update(Request $request): Response
    {
        $data = $request->validate([
            'level_1' => ['required', 'integer'],
            'level_2' => ['required', 'integer'],
            'level_3' => ['required', 'integer'],
        ]);

        $config = Config::query()
            ->first();

        $config->update([
            'threshold_level_1' => $data['level_1'],
            'threshold_level_2' => $data['level_2'],
            'threshold_level_3' => $data['level_3'],
        ]);

        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers\Api;

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

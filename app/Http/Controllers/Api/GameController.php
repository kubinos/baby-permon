<?php

namespace App\Http\Controllers\Api;

use App\Enums\Emotion;
use App\Enums\Language;
use App\Enums\Level;
use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Symfony\Component\HttpFoundation\Response;

class GameController extends Controller
{
    public function index(): Response
    {
        return response()->json([
            'data' => Game::active()->get(),
        ]);
    }

    public function store(Request $request): Response
    {
        $data = $request->validate([
            'chip' => ['required', 'string'],
            'salutation' => ['required', 'string'],
            'level' => ['required', new Enum(Level::class)],
            'emotion' => ['required', 'string', new Enum(Emotion::class)],
            'language' => ['required', 'string', new Enum(Language::class)],
        ]);

        $game = Game::query()
            ->create($data);

        return response()->json(
            [
                'data' => $game,
            ],
            Response::HTTP_CREATED
        );
    }

    public function destroy(string $chip): Response
    {
        $game = Game::query()->firstWhere(['chip' => $chip]);
        if ($game instanceof Game) {
            $game->delete();
        }

        return response()->json(
            [
                'data' => $game,
            ]
        );
    }
}

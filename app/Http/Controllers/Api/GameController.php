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
    public function show(int $gameId): Response
    {
        return response()->json([
            'data' => Game::query()->findOrFail($gameId),
        ]);
    }

    public function store(Request $request): Response
    {
        $data = $request->validate([
            'chip' => ['required', 'string'],
            'salutation' => ['required', 'string'],
            'level' => ['required', 'string', new Enum(Level::class)],
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

    public function destroy(int $gameId): Response
    {
        Game::destroy($gameId);

        return response()->noContent();
    }
}

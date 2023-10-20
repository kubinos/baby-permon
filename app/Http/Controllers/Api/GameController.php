<?php

namespace App\Http\Controllers\Api;

use App\Enums\Emotion;
use App\Enums\GameType;
use App\Enums\Language;
use App\Enums\Level;
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Symfony\Component\HttpFoundation\Response;

class GameController extends Controller
{
    public function index(): Response
    {
        return response()->json([
            'data' => Game::all(),
        ]);
    }

    public function store(Request $request): Response
    {
        $isPlayer = $request->get('type') === GameType::Player->value;

        $rules = [
            'chip' => ['required', 'string'],
            'salutation' => ['required', 'string'],
            'type' => ['required', new Enum(GameType::class)],
        ];

        if ($isPlayer) {
            $rules += [
                'level' => ['required_if:type,player', new Enum(Level::class)],
                'emotion' => ['required_if:type,player', 'string', new Enum(Emotion::class)],
                'language' => ['required_if:type,player', 'string', new Enum(Language::class)],
            ];
        }

        $data = $request->validate($rules);

        $game = Game::query()
            ->create([
                'chip' => $data['chip'],
                'salutation' => $data['salutation'],
                'type' => $data['type'],
                'level' => $data['level'] ?? Level::One,
                'emotion' => $data['emotion'] ?? Emotion::Angry,
                'language' => $data['language'] ?? Language::Czech,
            ]);

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

    public function getLogs(int $gameId): Response
    {
        return response()->json([
            'data' => GameLog::query()->where('game_id', $gameId)->get(),
        ]);
    }
}

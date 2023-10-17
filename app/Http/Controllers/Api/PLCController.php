<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PLCController extends Controller
{
    public function tryOpenDoor(Request $request): Response
    {
        $game = Game::query()
            ->where('chip', $request->get('chip'))
            ->first();

        if (! $game instanceof Game) {
            return response()->json([
                'open' => false,
                'typeOfPlayer' => null,
            ]);
        }

        if (intval($request->get('doorId')) === 1) {
            $game->update([
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (intval($request->get('doorId')) === 2) {
            $game->update([
                'ended_at' => now(),
            ]);
        }

        return response()->json([
            'open' => true,
            'typeOfPlayer' => $game->type,
        ]);
    }

    public function panelInfo(Request $request): Response
    {
//        $panelId = $request->get('panelIdNumber');
//        $chip = $request->get('chip');
//
//        if (! $panelId || ! $chip) {
//            return response()->json([
//                'success' => false,
//                'error' => 'Missing panelIdNumber or chip',
//            ], Response::HTTP_BAD_REQUEST);
//        }
//
//        // TOOD: check if chip is valid
//        // TODO: get panel info from database
//
//        return response()->json([
//            'name' => 'Mine',
//            'sound' => '55.wav',
//            'responseNumber' => 2,
//            'responseColor' => 'red',
//            'responseShape' => 'rectangle',
//            'pointsCorrect' => 9,
//            'pointsPartial' => 6,
//            'pointsIncorrect' => 1,
//        ]);
    }

    public function points(Request $request): Response
    {
//        $chip = $request->get('chip');
//
//        if (! $chip) {
//            return response()->json([
//                'success' => false,
//                'error' => 'Missing chip',
//            ], Response::HTTP_BAD_REQUEST);
//        }
//
//        // TODO: check if chip is valid
//
//        return \response()->json([
//            'success' => false,
//        ]);
    }
}

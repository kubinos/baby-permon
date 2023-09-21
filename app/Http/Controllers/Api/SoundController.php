<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sound;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SoundController extends Controller
{
    public function index(): Response
    {
        return response()->json([
            'data' => Sound::all(),
        ]);
    }

    public function show(int $soundId): Response
    {
        return response()->json([
            'data' => Sound::query()->findOrFail($soundId),
        ]);
    }

    public function store(Request $request): Response
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'number' => ['required', 'integer']
        ]);

        $sound = Sound::query()
            ->create($data);

        return response()->json(
            [
                'data' => $sound,
            ],
            Response::HTTP_CREATED
        );
    }

    public function update(Request $request, int $soundId): Response
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'number' => ['required', 'integer']
        ]);

        $sound = Sound::query()
            ->findOrFail($soundId);

        $sound->update($data);

        return response()->noContent();
    }

    public function destroy(int $soundId): Response
    {
        Sound::destroy($soundId);

        return response()->noContent();
    }
}

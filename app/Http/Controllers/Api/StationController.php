<?php

namespace App\Http\Controllers\Api;

use App\Enums\Location;
use App\Http\Controllers\Controller;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Symfony\Component\HttpFoundation\Response;

class StationController extends Controller
{
    public function index(): Response
    {
        return response()->json([
            'data' => Station::all(),
        ]);
    }

    public function show(int $stationId): Response
    {
        return response()->json([
            'data' => Station::query()->findOrFail($stationId),
        ]);
    }

    public function store(Request $request): Response
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'location' => ['required', 'string', new Enum(Location::class)]
        ]);

        $station = Station::query()
            ->create($data);

        return response()->json(
            [
                'data' => $station,
            ],
            Response::HTTP_CREATED
        );
    }

    public function update(Request $request, int $stationId): Response
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'location' => ['required', 'string', new Enum(Location::class)]
        ]);

        $station = Station::query()
            ->findOrFail($stationId);

        $station->update($data);

        return response()->noContent();
    }

    public function destroy(int $stationId): Response
    {
        Station::destroy($stationId);

        return response()->noContent();
    }
}

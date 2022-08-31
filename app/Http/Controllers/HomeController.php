<?php

namespace App\Http\Controllers;

use Aerni\Spotify\Spotify;
use App\Http\Requests\SearchRequest;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Home',);
    }

    public function search(SearchRequest $request): JsonResponse
    {
        try {
            $keyword = $request->get('track');
            $offset = $request->get('offset');

            $config = config('spotify.default_config');
            $spotify = new Spotify($config);
            $result = $spotify->searchTracks($keyword)->offset($offset)->get();

            return response()->json([
                'data' => $result,
                'message' => 'Showing result for: '.$keyword,
                'status' => true
            ]);
        }
        catch (\Exception $exception){

            return response()->json([
                'data' => null,
                'message' => 'An error occurred, '.$exception->getMessage(),
                'status' => false
            ], 500);
        }
    }
}

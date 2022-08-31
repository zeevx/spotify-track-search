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
        if (request()->has('search')) {
            $config = config('spotify.default_config');
            $spotify = new Spotify($config);
            $result = $spotify->searchTracks(request()->get('search'))->get();
        }
        return Inertia::render('Home', [
            'tracks' => $result['tracks']['items'] ?? null
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function fetchArticles(Request $request)
    {
        $filter = $request->input('filter');
        $searchTerm = $request->input('searchTerm');

        $apiKey = config('services.newsapi.key');
        $url = config('services.newsapi.url');

        if($filter === 'sources') {
            $searchTerm = Str::slug($searchTerm);
        }

        if ($filter) {
            $url .= "?$filter=" . urlencode($searchTerm);
        } else {
            $url .= "?country=us";
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey
        ])->get($url);

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Unable to fetch articles'], 500);
    }
}

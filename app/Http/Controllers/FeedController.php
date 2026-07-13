<?php

namespace App\Http\Controllers;

use App\Models\WikiArticle;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index()
    {
        $articles = WikiArticle::inRandomOrder()->limit(10)->get();

        return view('feed.show', compact('articles'));
    }

    public function loadMore(Request $request)
    {
        $excludeIds = $request->input('exclude', []);

        $articles = WikiArticle::inRandomOrder()
            ->whereNotIn('id', $excludeIds)
            ->limit(10)
            ->get();

        return response()->json($articles);
    }
}
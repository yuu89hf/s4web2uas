<?php

namespace App\Http\Controllers;

use App\Models\WikiArticle;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    /**
     * Menampilkan halaman feed utama dengan 10 artikel acak.
     */
    public function index()
    {
        $articles = WikiArticle::inRandomOrder()->limit(10)->get();

        return view('feed.show', compact('articles'));
    }

    /**
     * Mengambil 10 artikel acak tambahan untuk infinite scroll atau tombol refresh.
     */
    public function loadMore(Request $request)
    {
        // Pastikan $excludeIds selalu berupa array
        $excludeIds = $request->input('exclude', []);

        // Kita gunakan when() agar query whereNotIn hanya berjalan jika ada ID yang diexclude.
        // Ini mencegah bug dan membuat query lebih bersih saat tombol refresh ditekan!
        $articles = WikiArticle::inRandomOrder()
            ->when(!empty($excludeIds), function ($query) use ($excludeIds) {
                return $query->whereNotIn('id', $excludeIds);
            })
            ->limit(10)
            ->get();

        return response()->json($articles);
    }
}
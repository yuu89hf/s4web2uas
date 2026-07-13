<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WikipediaService
{
    // URL ini sudah benar, tidak perlu diubah!
    protected string $baseUrl = 'https://en.wikipedia.org/w/api.php';

    public function getCategoryMembers(string $category, int $limit = 50, ?string $continue = null): array
    {
        // FIX 1: Memastikan nama kategori otomatis dibungkus "Category:" jika kamu lupa
        if (!str_starts_with($category, 'Category:')) {
            $category = 'Category:' . $category;
        }

        $params = [
            'action' => 'query',
            'list' => 'categorymembers',
            'cmtitle' => $category,
            'cmlimit' => $limit,
            'cmtype' => 'page',
            'format' => 'json',
        ];

        if ($continue) {
            $params['cmcontinue'] = $continue;
        }

        // FIX 2: Wajib menambahkan denganHeaders() berisi User-Agent agar tidak diblokir Wikipedia
        $response = Http::withHeaders([
            'User-Agent' => 'LaravelAstronomyBot/1.0 (contact@example.com)'
        ])->get($this->baseUrl, $params);

        return [
            'items' => $response->json('query.categorymembers', []),
            'continue' => $response->json('continue.cmcontinue', null),
        ];
    }

    public function getArticleSummary(string $title): ?array
    {
        // FIX 3: Tambahkan User-Agent juga di fungsi ini!
        $response = Http::withHeaders([
            'User-Agent' => 'LaravelAstronomyBot/1.0 (contact@example.com)'
        ])->get($this->baseUrl, [
            'action' => 'query',
            'titles' => $title,
            'prop' => 'extracts|pageimages|info',
            'exintro' => true,
            'explaintext' => true,
            'exchars' => 400,
            'piprop' => 'thumbnail',
            'pithumbsize' => 500,
            'inprop' => 'url',
            'format' => 'json',
        ]);

        $pages = $response->json('query.pages', []);
        $page = reset($pages);

        if (!$page || isset($page['missing'])) {
            return null;
        }

        return [
            'title' => $page['title'] ?? $title,
            'extract' => $page['extract'] ?? '',
            'thumbnail_url' => $page['thumbnail']['source'] ?? null,
            'page_url' => $page['fullurl'] ?? '',
        ];
    }
}
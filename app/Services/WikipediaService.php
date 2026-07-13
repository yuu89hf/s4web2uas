<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WikipediaService
{
    protected string $baseUrl = 'https://id.wikipedia.org/w/api.php';

    public function getCategoryMembers(string $category, int $limit = 50, ?string $continue = null): array
    {
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

        $response = Http::get($this->baseUrl, $params);

        return [
            'items' => $response->json('query.categorymembers', []),
            'continue' => $response->json('continue.cmcontinue', null),
        ];
    }

    public function getArticleSummary(string $title): ?array
    {
        $response = Http::get($this->baseUrl, [
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
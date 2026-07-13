<?php

namespace App\Console\Commands;

use App\Models\WikiArticle;
use App\Services\WikipediaService;
use Illuminate\Console\Command;

class SyncAstronomyArticles extends Command
{
    protected $signature = 'wiki:sync-astronomy';
    protected $description = 'Sinkronisasi artikel astronomi dari Wikipedia';

    protected string $category = 'Kategori:Astronomi';

    public function handle(WikipediaService $wiki)
    {
        $this->info('Mulai sync artikel astronomi...');
        $continue = null;
        $totalNew = 0;

        do {
            $result = $wiki->getCategoryMembers($this->category, 50, $continue);
            $members = $result['items'];
            $continue = $result['continue'];

            foreach ($members as $member) {
                if (WikiArticle::where('title', $member['title'])->exists()) {
                    continue;
                }

                $summary = $wiki->getArticleSummary($member['title']);

                if (!$summary || empty($summary['extract'])) {
                    continue;
                }

                WikiArticle::create([
                    'title' => $summary['title'],
                    'extract' => $summary['extract'],
                    'thumbnail_url' => $summary['thumbnail_url'],
                    'page_url' => $summary['page_url'],
                    'fetched_at' => now(),
                ]);

                $totalNew++;
                usleep(200000);
            }

            $this->info("Batch selesai, total baru sejauh ini: {$totalNew}");
        } while ($continue !== null);

        $this->info("Selesai! Total artikel baru: {$totalNew}");
    }
}
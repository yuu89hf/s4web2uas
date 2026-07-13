<?php

namespace App\Console\Commands;

use App\Models\WikiArticle;
use App\Services\WikipediaService;
use Illuminate\Console\Command;

class SyncAstronomyArticles extends Command
{
    protected $signature = 'wiki:sync-astronomy';
    protected $description = 'Sinkronisasi massal artikel astronomi dan antariksa dari Wikipedia';

    // FIX 1: Kita ganti jadi array kategori besar antariksa agar artikelnya variatif dan banyak!
    protected array $categories = [
        'Category:Astronomy',
        'Category:Galaxies',
        'Category:Stars',
        'Category:Planets of the Solar System',
        'Category:Spaceflight',
        'Category:Nebulae',
        'Category:Exoplanets',
        'Category:Astronomical objects'
    ];

    public function handle(WikipediaService $wiki)
    {
        $this->info('Mulai sync massal artikel astronomi...');
        $totalNew = 0;

        // Loop setiap kategori yang sudah kita daftarkan
        foreach ($this->categories as $currentCategory) {
            $this->info("--------------------------------------------------");
            $this->info("Mengambil dari tema: {$currentCategory}");
            
            $continue = null;
            $batchCount = 0;

            do {
                // Mengambil data per batch (50 artikel)
                $result = $wiki->getCategoryMembers($currentCategory, 50, $continue);
                $members = $result['items'] ?? [];
                $continue = $result['continue'] ?? null;

                if (empty($members)) {
                    break;
                }

                foreach ($members as $member) {
                    // Cek apakah judul artikel sudah ada di database agar tidak duplikat
                    if (WikiArticle::where('title', $member['title'])->exists()) {
                        continue;
                    }

                    $summary = $wiki->getArticleSummary($member['title']);

                    // Lewati jika summary kosong atau artikel tidak valid
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
                    
                    // Output ke terminal agar kamu bisa melihat progress real-time
                    $this->line("   [BERHASIL] Berhasil menyimpan: {$summary['title']}");

                    // Delay 200ms (0.2 detik) agar tidak diblokir oleh rate limiter Wikipedia
                    usleep(200000); 
                }

                $batchCount++;
                $this->info("=> Batch {$batchCount} selesai untuk {$currentCategory}. Total baru sementara: {$totalNew}");

                // FIX 2: Batasi maksimal 3 kali hit (150 data) per kategori agar proses sync tidak memakan waktu berjam-jam
                if ($batchCount >= 3) {
                    $this->comment("   Mencapai batas batch maksimum untuk kategori ini. Lanjut ke kategori berikutnya...");
                    break;
                }

            } while ($continue !== null);
        }

        $this->info("==================================================");
        $this->info("Selesai! Total seluruh artikel baru yang masuk: {$totalNew}");
    }
}
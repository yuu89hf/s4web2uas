<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🔭 Astronomy Feed
        </h2>
    </x-slot>

    <div class="py-8" x-data="{
        articles: {{ $articles->toJson() }}.slice(0, 10),
        loading: false,
        async refreshFeed() {
            if (this.loading) return;
            this.loading = true;
            
            const ids = this.articles.map(a => a.id);
            const params = new URLSearchParams();
            ids.forEach(id => params.append('exclude[]', id));
            
            try {
                const res = await fetch('{{ route('feed.load-more') }}?' + params.toString());
                const freshArticles = await res.json();
                
                if (freshArticles.length > 0) {
                    this.articles = freshArticles.slice(0, 10);
                }
            } catch (error) {
                console.error('Gagal memuat artikel:', error);
            } finally {
                this.loading = false;
            }
        }
    }">
        <div style="max-w-6xl; margin: 0 auto; padding: 0 1.5rem;">
            
            <div style="display: flex; justify-content: flex-end; margin-bottom: 1.5rem;">
                <button 
                    @click="refreshFeed()"
                    :disabled="loading"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow text-sm transition duration-200 disabled:opacity-50">
                    <span x-show="!loading">🔄 Acak Artikel Baru</span>
                    <span x-show="loading">🔄 Memuat...</span>
                </button>
            </div>

            <template x-if="articles.length === 0 && !loading">
                <div class="bg-white p-6 rounded-lg shadow text-center text-gray-500">
                    Belum ada artikel. Jalankan <code>php artisan wiki:sync-astronomy</code> dulu ya.
                </div>
            </template>

            <div style="display: flex; flex-wrap: wrap; gap: 1.5rem;">
                <template x-for="article in articles" :key="article.id">
                    
                    <div class="bg-white shadow-sm sm:rounded-lg" 
                         style="flex: 0 0 calc(50% - 0.75rem); display: flex; flex-direction: column; justify-content: space-between; overflow: hidden; border: 1px solid #f3f4f6; box-sizing: border-box;">
                        
                        <div>
                            <div x-show="article.thumbnail_url" style="width: 100%; height: 200px; overflow: hidden; bg-color: #f3f4f6;">
                                <img :src="article.thumbnail_url" style="width: 100%; height: 100%; object-fit: cover;" alt="">
                            </div>
                            
                            <div class="p-6">
                                <h2 class="text-xl font-bold mb-2 text-gray-900" style="display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;" x-text="article.title"></h2>
                                <p class="text-gray-600 text-sm" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;" x-text="article.extract"></p>
                            </div>
                        </div>
                        
                        <div class="px-6 pb-6">
                            <a :href="article.page_url" target="_blank" rel="noopener" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm inline-block">
                                Baca selengkapnya di Wikipedia →
                            </a>
                        </div>
                    </div>

                </template>
            </div>
        </div>
    </div>
</x-app-layout>
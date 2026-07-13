<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🔭 Astronomy Feed
        </h2>
    </x-slot>

    <div class="py-8" x-data="{
        articles: {{ $articles->toJson() }},
        loading: false,
        async loadMore() {
            if (this.loading) return;
            this.loading = true;
            const ids = this.articles.map(a => a.id);
            const params = new URLSearchParams();
            ids.forEach(id => params.append('exclude[]', id));
            const res = await fetch('{{ route('feed.load-more') }}?' + params.toString());
            const newArticles = await res.json();
            this.articles.push(...newArticles);
            this.loading = false;
        }
    }" x-init="window.addEventListener('scroll', () => {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 300) {
            loadMore();
        }
    })">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <template x-if="articles.length === 0">
                <div class="bg-white p-6 rounded-lg shadow text-center text-gray-500">
                    Belum ada artikel. Jalankan <code>php artisan wiki:sync-astronomy</code> dulu ya.
                </div>
            </template>

            <template x-for="article in articles" :key="article.id">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <img :src="article.thumbnail_url" x-show="article.thumbnail_url" class="w-full h-64 object-cover" alt="">
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-2" x-text="article.title"></h2>
                        <p class="text-gray-600 mb-4" x-text="article.extract"></p>
                        <a :href="article.page_url" target="_blank" rel="noopener" class="text-indigo-600 hover:underline">
                            Baca selengkapnya di Wikipedia →
                        </a>
                    </div>
                </div>
            </template>

            <p x-show="loading" class="text-center text-gray-400">Memuat artikel...</p>
        </div>
    </div>
</x-app-layout>
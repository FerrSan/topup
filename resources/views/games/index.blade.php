@extends('layouts.app')

@section('title', 'All Games - Top Up Online')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">All Games</h1>
        <p class="text-gray-400">Choose your favorite game and top up instantly</p>
    </div>

    <!-- Filters -->
    <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-gray-300 text-sm mb-2">Search Game</label>
                <input type="text" id="searchGame" placeholder="Type game name..."
                    class="w-full px-4 py-2 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary">
            </div>

            <!-- Category -->
            <div>
                <label class="block text-gray-300 text-sm mb-2">Category</label>
                <select id="categoryFilter"
                    class="w-full px-4 py-2 bg-zinc-800/50 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-accent-primary">
                    <option value="">All Categories</option>
                    <option value="mobile">Mobile</option>
                    <option value="pc">PC</option>
                    <option value="console">Console</option>
                </select>
            </div>

            <!-- Sort -->
            <div>
                <label class="block text-gray-300 text-sm mb-2">Sort By</label>
                <select id="sortBy"
                    class="w-full px-4 py-2 bg-zinc-800/50 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-accent-primary">
                    <option value="popular">Most Popular</option>
                    <option value="name">Name (A-Z)</option>
                    <option value="newest">Newest</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Games Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4" id="gamesGrid">
        @foreach($games as $game)
        <a href="{{ route('games.show', $game->slug) }}" class="group game-item" data-category="{{ $game->category }}" data-name="{{ strtolower($game->name) }}">
            <div class="bg-zinc-900/60 rounded-2xl p-4 hover:bg-zinc-900 transition-all hover:scale-[1.02] hover:ring-2 hover:ring-accent-primary/50">
                <div class="relative mb-3">
                    <img src="{{ $game->icon_url }}" alt="{{ $game->name }}" 
                        class="w-full aspect-square rounded-xl group-hover:brightness-110 transition">
                    @if($game->is_featured)
                    <span class="absolute top-2 right-2 bg-accent-primary text-zinc-900 text-xs px-2 py-1 rounded font-semibold">
                        FEATURED
                    </span>
                    @endif
                </div>
                <h3 class="text-white font-semibold text-sm">{{ $game->name }}</h3>
                <p class="text-gray-400 text-xs mt-1">{{ $game->publisher }}</p>
                <div class="flex items-center mt-2">
                    <svg class="w-3 h-3 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span class="text-gray-500 text-xs ml-1">{{ number_format($game->orders_count ?? 0) }} orders</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $games->links() }}
    </div>
</div>

@push('scripts')
<script>
    // Search functionality
    document.getElementById('searchGame').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        filterGames();
    });

    // Category filter
    document.getElementById('categoryFilter').addEventListener('change', function() {
        filterGames();
    });

    // Sort functionality
    document.getElementById('sortBy').addEventListener('change', function() {
        sortGames(this.value);
    });

    function filterGames() {
        const searchTerm = document.getElementById('searchGame').value.toLowerCase();
        const category = document.getElementById('categoryFilter').value;
        const games = document.querySelectorAll('.game-item');

        games.forEach(game => {
            const name = game.dataset.name;
            const gameCategory = game.dataset.category;
            
            const matchesSearch = !searchTerm || name.includes(searchTerm);
            const matchesCategory = !category || gameCategory === category;
            
            if (matchesSearch && matchesCategory) {
                game.style.display = 'block';
            } else {
                game.style.display = 'none';
            }
        });
    }

    function sortGames(sortBy) {
        const grid = document.getElementById('gamesGrid');
        const games = Array.from(grid.querySelectorAll('.game-item'));
        
        games.sort((a, b) => {
            if (sortBy === 'name') {
                return a.dataset.name.localeCompare(b.dataset.name);
            }
            // Add more sort options as needed
            return 0;
        });
        
        games.forEach(game => grid.appendChild(game));
    }
</script>
@endpush
@endsection
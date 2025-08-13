<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class UpdateGameStatistics implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        // Invalidate cache statistik game terkait order yang baru dibuat
        Cache::forget("game_stats_{$order->game_id}");

        // TODO (opsional): update tabel statistik khusus
        // GameStatistic::updateOrCreate([...]);
    }
}

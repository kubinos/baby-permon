<?php

namespace App\Listeners;

use App\Events\GameUpdated;
use App\Models\GameLog;

class LogGameUpdate
{
    public function handle(object $event): void
    {
        if (! $event instanceof GameUpdated) {
            return;
        }

        GameLog::query()
            ->create([
                'chip' => $event->game->chip,
                'salutation' => $event->game->salutation,
                'action' => $event->action,
            ]);
    }
}

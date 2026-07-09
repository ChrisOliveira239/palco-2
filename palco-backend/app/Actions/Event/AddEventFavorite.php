<?php

namespace App\Actions\Event;

use App\Models\Event;
use App\Models\User;

class AddEventFavorite
{
    public function handle(Event $event, User $user): void
    {
        $event->favoritedBy()->syncWithoutDetaching([$user->id]);
    }
}
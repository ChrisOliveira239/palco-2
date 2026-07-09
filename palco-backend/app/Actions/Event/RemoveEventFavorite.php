<?php

namespace App\Actions\Event;

use App\Models\Event;
use App\Models\User;

class RemoveEventFavorite
{
    public function handle(Event $event, User $user): void
    {
        $event->favoritedBy()->detach($user->id);
    }
}
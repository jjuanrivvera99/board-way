<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;

class AssignUserRole
{
   /**
     * Handle the event.
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event): void
    {
        $event->user->assignRole('user');
    }
}

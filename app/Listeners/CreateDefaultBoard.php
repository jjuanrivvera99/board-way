<?php

namespace App\Listeners;

use App\Models\Row;
use App\Models\Board;
use Illuminate\Auth\Events\Registered;

class CreateDefaultBoard
{
   /**
     * Handle the event.
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event): void
    {
        if ($event->user->boards()->count() === 0) {
            // Create the default board
            $board = Board::create([
                'user_id' => $event->user->id,
                'title' => 'My Default Board',
                'description' => 'This is your first board!'
            ]);

            // Define the default columns
            $defaultColumns = ['To Do', 'In Progress', 'Done'];

            // Create the default columns
            foreach ($defaultColumns as $index => $title) {
                Row::create([
                    'board_id' => $board->id,
                    'title' => $title,
                    'position' => $index + 1
                ]);
            }
        }
    }
}

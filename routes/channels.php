<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('board.{boardId}', function ($user, $boardId) {
    return $user->boards->contains($boardId);
});

Broadcast::channel('row.{rowId}', function ($user, $rowId) {
    return true;
});

Broadcast::channel('card.{cardId}', function ($user, $cardId) {
    return true;
});

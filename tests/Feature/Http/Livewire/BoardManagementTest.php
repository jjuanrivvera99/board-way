<?php

use App\Models\User;
use Livewire\Livewire;
use Livewire\Volt\Volt;

it('can create a board', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('board-management')
        ->call('addBoard');

    $this->assertDatabaseHas('boards', [
        'title' => 'New Board',
        'user_id' => $user->id,
    ]);
});

it('can delete a board', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $board = $user->boards()->create([
        'title' => 'Test Board',
    ]);

    Livewire::test('board-management')
        ->call('deleteBoard', $board->id);

    $this->assertSoftDeleted('boards', [
        'id' => $board->id,
    ]);
});

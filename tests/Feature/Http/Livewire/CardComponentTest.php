<?php

use Livewire\Livewire;
use App\Services\CardService;
use App\Livewire\CardComponent;

beforeEach(function () {
    // Mock the CardService
    $this->cardServiceMock = mock(CardService::class);

    // Bind the mock to the service container
    app()->instance(CardService::class, $this->cardServiceMock);
});

it('validates input before saving a card', function () {
    Livewire::test(CardComponent::class)
        ->set('title', '') // Assuming title is required
        ->call('save')
        ->assertHasErrors(['title' => 'required']);
});

it('dispatches event on save success', function () {

    $this->cardServiceMock->shouldReceive('update')->once()->withArgs([
        1,
        [
            'title' => 'Valid Title',
            'description' => 'Valid Description'
        ]
    ]);

    Livewire::test(CardComponent::class)
        ->set('id', 1)
        ->set('title', 'Valid Title')
        ->set('description', 'Valid Description')
        ->call('save')
        ->assertDispatched('card-saved');

    $this->cardServiceMock->shouldHaveReceived('update')->once();
});

it('dispatches validation-failed event on validation error', function () {
    Livewire::test(CardComponent::class)
        ->set('title', '')
        ->call('save')
        ->assertDispatched('validation-failed');
});

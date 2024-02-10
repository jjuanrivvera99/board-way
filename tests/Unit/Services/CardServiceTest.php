<?php

use App\Models\Row;
use App\Models\Card;
use App\Events\RowUpdated;
use App\Events\CardUpdated;
use App\Services\CardService;
use Illuminate\Support\Facades\Event;
use App\Contracts\Repositories\CardRepositoryInterface;

beforeEach(function () {
    // Fake events to ensure they are not actually dispatched
    Event::fake();

    // Create a mock for the CardRepositoryInterface
    $this->cardRepository = mock(CardRepositoryInterface::class);

    // Create a new instance of the CardService, injecting the mock repository
    $this->cardService = new CardService($this->cardRepository);
});

it('can retrieve all cards', function () {
    $cardsMockData = Card::factory()->count(3)->make();

    $this->cardRepository->shouldReceive('all')->once()->andReturn($cardsMockData);

    $cards = $this->cardService->all();

    expect($cards)->toBe($cardsMockData);
});

it('can find a card by id', function () {
    $card = Card::factory()->create();

    $this->cardRepository->shouldReceive('find')
        ->with($card->id)
        ->once()
        ->andReturn($card);

    $foundCard = $this->cardService->find($card->id);

    expect($foundCard)->toBe($card);
});

it('can create a new card', function () {
    $cardData = Card::factory()->make()->toArray();

    $this->cardRepository->shouldReceive('create')
        ->with($cardData)
        ->once()
        ->andReturn(new Card($cardData));

    $createdCard = $this->cardService->create($cardData);

    expect($createdCard->title)->toEqual($cardData['title']);
});

it('can update a card and dispatches CardUpdated event', function () {
    $card = Card::factory()->create();
    $updateData = ['title' => 'Updated Card Title'];

    $this->cardRepository->shouldReceive('find')->with($card->id)->andReturn($card);
    $this->cardRepository->shouldReceive('update')->with($card->id, $updateData)->once()->andReturn(true);

    $this->cardService->update($card->id, $updateData);

    Event::assertDispatched(CardUpdated::class);
});

it('can delete a card and dispatches RowUpdated event', function () {
    $cardId = Card::factory()->create()->id;

    $this->cardRepository->shouldReceive('find')->with($cardId)->andReturn(Card::find($cardId));
    $this->cardRepository->shouldReceive('delete')->with($cardId)->once()->andReturn(true);

    $this->cardService->delete($cardId);

    Event::assertDispatched(RowUpdated::class);
});

it('can move a card to a rows position and dispatches events', function () {
    $card = Card::factory()->create();
    $newRowId = Row::factory()->create()->id;
    $position = 1;

    $this->cardRepository->shouldReceive('find')->with($card->id)->andReturn($card);
    $this->cardRepository->shouldReceive('move')->with($card->id, $newRowId, $position)->once()->andReturn(true);

    $this->cardService->move($card->id, $newRowId, $position);

    Event::assertDispatched(CardUpdated::class);
    Event::assertDispatched(RowUpdated::class);
});

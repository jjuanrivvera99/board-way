<?php

use App\Models\Row;
use Livewire\Livewire;
use Livewire\Volt\Volt;
use App\Services\RowService;
use App\Livewire\RowComponent;

beforeEach(function () {
    // Mock the RowService
    $this->rowServiceMock = mock(RowService::class);

    // Bind the mock to the service container
    app()->instance(RowService::class, $this->rowServiceMock);
});

it('can add cards', function () {
    $row = Row::factory()->create();

    $this->rowServiceMock->shouldReceive('addCard')->once()->withArgs([$row->id]);
    $this->rowServiceMock->shouldReceive('findWithOrderedCards')->with($row->id);

    Livewire::test(RowComponent::class, ['row' => $row])
        ->call('addCard');

    $this->rowServiceMock->shouldHaveReceived('addCard')->once();
    $this->rowServiceMock->shouldHaveReceived('findWithOrderedCards')->twice();
});

it('can update row title', function () {
    $row = Row::factory()->create();

    $this->rowServiceMock->shouldReceive('update')->once()->withArgs([$row->id, ['title' => 'Updated Row Title']]);
    $this->rowServiceMock->shouldReceive('find')->with($row->id);
    $this->rowServiceMock->shouldReceive('findWithOrderedCards')->with($row->id);

    Livewire::test('row-component', ['row' => $row])
        ->call('updateRowTitle', 'Updated Row Title');

    $this->rowServiceMock->shouldHaveReceived('update')->once();
    $this->rowServiceMock->shouldHaveReceived('find')->once();
    $this->rowServiceMock->shouldHaveReceived('findWithOrderedCards')->twice();
});

it("can empty row", function () {
    $row = Row::factory()->create();

    $row->cards()->create([
        'title' => 'Test Card',
        'description' => 'Test Description',
    ]);

    $this->rowServiceMock->shouldReceive('deleteCards')->once()->withArgs([$row->id]);
    $this->rowServiceMock->shouldReceive('findWithOrderedCards')->with($row->id);

    Livewire::test('row-component', ['row' => $row])
        ->call('deleteCards');

    $this->rowServiceMock->shouldHaveReceived('deleteCards')->once();
    $this->rowServiceMock->shouldHaveReceived('findWithOrderedCards')->twice();
});

it('can delete row', function () {
    $row = Row::factory()->create();

    $this->rowServiceMock->shouldReceive('delete')->once()->withArgs([$row->id]);
    $this->rowServiceMock->shouldReceive('findWithOrderedCards')->with($row->id);

    Livewire::test('row-component', ['row' => $row])
        ->call('deleteRow');

    $this->rowServiceMock->shouldHaveReceived('delete')->once();
    $this->rowServiceMock->shouldHaveReceived('findWithOrderedCards')->twice();
});

it('can move to another position', function () {
    $row = Row::factory()->create([
        'position' => 2,
    ]);

    $this->rowServiceMock->shouldReceive('move')->once()->withArgs([$row->id, 1]);
    $this->rowServiceMock->shouldReceive('findWithOrderedCards')->with($row->id);

    Livewire::test('row-component', ['row' => $row])
        ->call('move', $row->id, 1);

    $this->rowServiceMock->shouldHaveReceived('move')->once();
    $this->rowServiceMock->shouldHaveReceived('findWithOrderedCards')->twice();
});

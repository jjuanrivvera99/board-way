<?php

use App\Models\Row;
use App\Events\RowUpdated;
use App\Events\BoardUpdated;
use App\Services\RowService;
use Illuminate\Support\Facades\Event;
use App\Contracts\Repositories\RowRepositoryInterface;

beforeEach(function () {
    // Fake events to ensure they are not actually dispatched
    Event::fake();

    // Create a mock for the RowRepositoryInterface
    $this->rowRepository = mock(RowRepositoryInterface::class);

    // Create a new instance of the RowService, injecting the mock repository
    $this->rowService = new RowService($this->rowRepository);
});

it('can retrieve all rows', function () {
    $rowsMockData = Row::factory()->count(3)->make();

    $this->rowRepository->shouldReceive('all')->once()->andReturn($rowsMockData);

    $rows = $this->rowService->all();

    expect($rows)->toBe($rowsMockData);
});

it('can find a row by id', function () {
    $row = Row::factory()->create();

    $this->rowRepository->shouldReceive('find')->with($row->id)->once()->andReturn($row);

    $foundRow = $this->rowService->find($row->id);

    expect($foundRow)->toBe($row);
    expect($foundRow->id)->toEqual($row->id);
});

it('can create a new row', function () {
    $rowData = Row::factory()->make()->toArray();

    $this->rowRepository->shouldReceive('create')
        ->with($rowData)
        ->once()
        ->andReturn(new Row($rowData));

    $createdRow = $this->rowService->create($rowData);

    expect($createdRow->title)->toEqual($rowData['title']);
});

it('can update a row and dispatch events', function () {
    $rowMock = Row::factory()->create(); // Persist a row to get a real ID
    $updateData = ['title' => 'Updated Row Title'];

    $this->rowRepository->shouldReceive('findWithOrderedCards')->with($rowMock->id)->andReturn($rowMock);
    $this->rowRepository->shouldReceive('update')->with($rowMock->id, $updateData)->once()->andReturn(true);

    $updated = $this->rowService->update($rowMock->id, $updateData);

    expect($updated)->toBeTrue();
    Event::assertDispatched(RowUpdated::class);
});

it('can delete a row and dispatch BoardUpdated event', function () {
    $rowId = Row::factory()->create()->id;

    $this->rowRepository->shouldReceive('find')->with($rowId)->andReturn(Row::find($rowId));
    $this->rowRepository->shouldReceive('delete')->with($rowId)->once()->andReturn(true);

    $deleted = $this->rowService->delete($rowId);

    expect($deleted)->toBeTrue();
    Event::assertDispatched(BoardUpdated::class);
});

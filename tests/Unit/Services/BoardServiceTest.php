<?php

use App\Models\Board;
use App\Services\BoardService;
use App\Contracts\Repositories\BoardRepositoryInterface;

beforeEach(function () {
    // Mock the BoardRepositoryInterface
    $this->boardRepository = mock(BoardRepositoryInterface::class);

    // Instantiate the BoardService with the mocked repository
    $this->boardService = new BoardService($this->boardRepository);
});

it('can retrieve all boards', function () {
    $boardsMockData = Board::factory()->count(3)->make();

    $this->boardRepository->shouldReceive('all')->once()->andReturn($boardsMockData);

    $boards = $this->boardService->all();

    expect($boards)->toBe($boardsMockData);
});

it('can find a board by id', function () {
    $board = Board::factory()->create();

    $this->boardRepository->shouldReceive('find')->with($board->id)->once()->andReturn($board);

    $board = $this->boardService->find($board->id);

    expect($board)->toBe($board);
    expect($board->id)->toEqual($board->id);
});

it('can create a new board', function () {
    $board = Board::factory()->create()->toArray();

    $this->boardRepository->shouldReceive('create')->with($board)->once()->andReturn(new Board($board));

    $createdBoard = $this->boardService->create($board);

    expect($createdBoard->title)->toEqual($board['title']);
    expect($createdBoard->description)->toEqual($board['description']);
});

it('can update a board', function () {
    $boardMock = Board::factory()->create(); // Persist a board to get a real ID
    $updateData = ['title' => 'Updated Board Title'];

    $this->boardRepository->shouldReceive('find')->with($boardMock->id)->andReturn($boardMock);
    $this->boardRepository->shouldReceive('update')->with($boardMock->id, $updateData)->once()->andReturn(true);

    $updated = $this->boardService->update($boardMock->id, $updateData);

    expect($updated)->toBeTrue();
});

it('can delete a board', function () {
    $boardId = Board::factory()->create()->id;

    $this->boardRepository->shouldReceive('delete')->with($boardId)->once()->andReturn(true);

    $deleted = $this->boardService->delete($boardId);

    expect($deleted)->toBeTrue();
});

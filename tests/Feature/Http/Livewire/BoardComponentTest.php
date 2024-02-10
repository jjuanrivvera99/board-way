<?php

use App\Models\User;
use App\Services\BoardService;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->boardService = mock(BoardService::class);
});

// it('can add rows', function () {
//     $board = $this->user->boards()->create(['title' => 'Test Board']);

//     $this->boardService->shouldReceive('addRow')->once()->withArgs([$board->id]);
//     $this->boardService->shouldReceive('findWithOrderedRows')->with($board->id);

//     Livewire::test('board-component', ['board' => $board])
//         ->call('addRow');

//     $this->boardService->shouldHaveReceived('addRow')->once();
//     $this->boardService->shouldHaveReceived('findWithOrderedRows')->twice();
// });

// it('can update board title', function () {
//     $board = $this->user->boards()->create(['title' => 'Test Board']);

//     $this->boardService->shouldReceive('update')->once()->withArgs([$board->id, ['title' => 'Updated Board Title']]);
//     $this->boardService->shouldReceive('find')->with($board->id);
//     $this->boardService->shouldReceive('findWithOrderedRows')->with($board->id);

//     Livewire::test('board-component', ['board' => $board])
//         ->call('updateBoardTitle', 'Updated Board Title');

//     $this->boardService->shouldHaveReceived('update')->once();
//     $this->boardService->shouldHaveReceived('find')->once();
//     $this->boardService->shouldHaveReceived('findWithOrderedRows')->twice();
// });

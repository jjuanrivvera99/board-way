<?php

namespace App\Livewire;

use App\Services\BoardService;
use Livewire\Component;

class BoardManagement extends Component
{
    /**
     * Board service instance.
     * @var BoardService
     */
    protected BoardService $boardService;

    /**
     * The boards.
     */
    public $boards;

    /**
     * Create a new component instance.
     * @return void
     */
    public function __construct()
    {
        $this->boardService = app(BoardService::class);
    }

    /**
     * Mount the component.
     * @return void
     */
    public function mount()
    {
        $this->boards = $this->boardService->currentUserBoards();
    }

    /**
     * Render the component.
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $this->boards = $this->boardService->currentUserBoards();
        return view('livewire.board-management');
    }

    /**
     * Delete a board.
     * @param int $id
     * @return void
     */
    public function deleteBoard($id)
    {
        $this->boardService->delete($id);
    }

    /**
     * Add a new board.
     * @return void
     */
    public function addBoard()
    {
        $this->boardService->create([
            'title' => 'New Board',
            'user_id' => auth()->id(),
        ]);
    }
}

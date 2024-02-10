<?php

namespace App\Livewire;

use App\Models\Board;
use App\Services\BoardService;
use Livewire\Component;

class BoardComponent extends Component
{
    /**
     * The board's ID.
     * @var int
     */
    public int $id;

    /**
     * The board's title.
     * @var string
     */
    public string $title;

    /**
     * The board's description.
     * @var string
     */
    public ?string $description;

    /**
     * The board's rows.
     * @var array
     */
    public $rows = [];

    /**
     * Board service instance.
     * @var BoardService
     */
    protected BoardService $boardService;

    /**
     * The component's listeners.
     * @var array<string, string>
     */
    protected $listeners = [
        'card-updated' => 'updateCard',
        'refresh-board' => 'refreshBoard',
    ];

    public function __construct()
    {
        $this->boardService = app(BoardService::class);
    }

    /**
     * Mount the component.
     * @param Board $board
     * @return void
     */
    public function mount(Board $board)
    {
        $this->fill($board->toArray());
    }

    /**
     * Render the component.
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $board = $this->boardService->findWithOrderedRowsAndCards($this->id);

        if ($board) {
            $this->fill($board->toArray());
        }

        return view('livewire.board');
    }

    /**
     * Update board's title.
     * @param string $title
     * @return void
     */
    public function updateBoardTitle(string $title)
    {
        if (empty($title)) {
            $board = $this->boardService->find($this->id);
            $title = $board->title;
        }

        $this->boardService->update($this->id, ['title' => $title]);
    }

    /**
     * Add a new row to the board.
     * @return void
     */
    public function addRow()
    {
        $this->boardService->addRow($this->id);
    }

    /**
     * Refresh the board.
     * @param int|null $id
     * @return void
     */
    public function refreshBoard(int $id = null)
    {
        if ($id && $id != $this->id) {
            return;
        }

        $this->fill(
            $this->boardService->findWithOrderedRowsAndCards($this->id)->toArray()
        );
    }
}

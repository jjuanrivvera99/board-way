<?php

namespace App\Livewire;

use App\Models\Row;
use App\Models\Card;
use Livewire\Component;
use App\Services\RowService;

class RowComponent extends Component
{
    /**
     * The row's ID.
     * @var int
     */
    public int $id;

    /**
     * The row's title.
     * @var string
     */
    public string $title;

    /**
     * Board ID.
     * @var array
     */
    public int $boardId;

    /**
     * The row's position
     * @var array
     */
    public int $position;

    /**
     * The row's cards.
     * @var Card[]
     */
    public $cards = [];

    /**
     * Row service instance.
     * @var RowService
     */
    protected RowService $rowService;

    /**
     * The component's listeners.
     * @var array<string, string>
     */
    protected $listeners = [
        'refresh-row' => 'refreshRow',
        'move-row' => 'move',
    ];

    /**
     * Create a new component instance.
     * @return void
     */
    public function __construct()
    {
        $this->rowService = app(RowService::class);
    }

    /**
     * Mount the component.
     * @param Row $rowId
     * @return void
     */
    public function mount(Row $row)
    {
        $this->fill($row->toArray());
    }

    /**
     * Render the component.
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $row = $this->rowService->findWithOrderedCards($this->id);

        if ($row) {
            $this->fill($row->toArray());
        }

        return view('livewire.row');
    }

    /**
     * Add a new card to the row.
     * @return void
     */
    public function addCard()
    {
        $this->rowService->addCard($this->id);
    }

    /**
     * Empty the row.
     * @return void
     */
    public function deleteCards()
    {
        $this->rowService->deleteCards($this->id);
    }

    /**
     * Update the row's title.
     * @param string $title
     * @return void
     */
    public function updateRowTitle(string $title)
    {
        $row = $this->rowService->find($this->id);

        if (empty($title)) {
            $title = $row->title;
        }

        $this->rowService->update($this->id, ['title' => $title]);
    }

    /**
     * Delete the row.
     * @return void
     */
    public function deleteRow()
    {
        $this->rowService->delete($this->id);
    }

    /**
     * Refresh the row.
     * @param int $id
     * @return void
     */
    public function refreshRow(int $id = null)
    {
        if ($id && $id != $this->id) {
            return;
        }

        $row = $this->rowService->findWithOrderedCards($this->id);

        if (!$row) {
            return;
        }

        $this->fill($row->toArray());
    }

    /**
     * Move the row to a new position.
     * @param int $rowId
     * @param int $position
     * @return void
     */
    public function move(int $rowId, int $position = 0)
    {
        if ($rowId != $this->id) {
            return;
        }

        $this->rowService->move($this->id, $position);
    }
}

<?php

namespace App\Livewire;

use App\Models\Row;
use App\Models\Card;
use Livewire\Component;
use App\Services\CardService;

class CardComponent extends Component
{
    /**
     * The card's ID.
     * @var int
     */
    public int $id;

    /**
     * The card's title.
     * @var string
     */
    public string $title;

    /**
     * The card's description.
     * @var string
     */
    public string $description;

    /**
     * The card's row ID.
     * @var int
     */
    public int $row_id;

    /**
     * The card's position.
     * @var int
     */
    public int $position;

    /**
     * The card's service.
     * @var CardService
     */
    protected CardService $cardService;

    /**
     * Rules for the card.
     * @var array<string, string>
     */
    public $rules = [
        'title' => 'required|string',
        'description' => 'required|string',
    ];

    /**
     * The card's listeners.
     * @var array<string, string>
     */
    protected $listeners = [
        'refresh-card' => 'refreshCard',
        'move-card' => 'move',
    ];

    /**
     * Create a new component instance.
     * @return void
     */
    public function __construct()
    {
        $this->cardService = app(CardService::class);
    }

    /**
     * Mount the component.
     * @param Card $card
     * @return void
     */
    public function mount(Card $card)
    {
        $this->fill($card->toArray());
    }

    /**
     * Render the component.
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.card');
    }

    /**
     * Save the card.
     * @return void
     */
    public function save()
    {
        try {
            $this->validate();
        } catch (\Throwable $th) {
            $this->dispatch('validation-failed');
            throw $th;
        }

        $this->cardService->update($this->id, [
            'title' => $this->title,
            'description' => $this->description,
        ]);

        $this->dispatch('refresh-card');
        $this->dispatch('card-saved');
    }

    /**
     * Refresh the card.
     * @return void
     */
    public function refreshCard(int $id = null)
    {
        if ($id && $id != $this->id) {
            return;
        }

        $card = $this->cardService->find($this->id);

        if (!$card) {
            return;
        }

        $this->fill($card->toArray());
    }

    /**
     * Remove the card.
     * @return void
     */
    public function removeCard()
    {
        $this->cardService->delete($this->id);
    }

    /**
     * Move the card to a new row.
     * @param int $cardId
     * @param int $rowId
     * @param int $position
     * @return void
     */
    public function move(int $cardId, int $rowId, int $position = 0)
    {
        if ($cardId != $this->id) {
            return;
        }

        $card = $this->cardService->find($cardId);
        $newRow = Row::find($rowId);

        if (!$newRow || !$card) {
            return;
        }

        $this->cardService->move($this->id, $rowId, $position);
    }
}

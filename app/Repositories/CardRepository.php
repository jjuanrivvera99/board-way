<?php

namespace App\Repositories;

use App\Models\Row;
use App\Models\Card;
use App\Contracts\Repositories\CardRepositoryInterface;

class CardRepository implements CardRepositoryInterface
{
    /**
     * Get all cards.
     * @return mixed
     */
    public function all()
    {
        return Card::all();
    }

    /**
     * Get a card by its ID.
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        return Card::find($id);
    }

    /**
     * Get a card by its ID with cards ordered by position.
     * @param int $id
     * @return mixed
     */
    public function findWithOrderedCards(int $id)
    {
        return Card::with(['cards' => function ($query) {
            $query->orderBy('position');
        }])->find($id);
    }

    /**
     * Create a new card.
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Card::create($data);
    }

    /**
     * Update a card.
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        return Card::find($id)->update($data);
    }

    /**
     * Delete a card.
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        $card = Card::find($id);
        // Re adjust the position of the cards in the current row
        Card::where('row_id', $card->row_id)
            ->where('position', '>', $card->position)
            ->decrement('position');

        return $card->delete();
    }

    /**
     * Move a card to a row's position.
     * @param int $id
     * @param int $rowId
     * @param int $position
     * @return void
     */
    public function move(int $id, int $rowId, int $position = 0)
    {
        $card = Card::with('row')->find($id);
        if (!$card) {
            return;
        }

        $newRow = Row::with('cards')->find($rowId);
        if (!$newRow) {
            return;
        }

        // Re adjust the position of the cards in the old row
        Card::where('position', '>', $card->position)
            ->where('row_id', $card->row->id)
            ->decrement('position'); // This action will not trigger the updated event

        $position = $position ?: $newRow->cards->max('position') + 1;

        // Adjust the position of the cards in the new row
        if ($newRow->cards->where('position', $position)->count()) {
            Card::where('row_id', $newRow->id)
                ->where('position', '>=', $position)
                ->increment('position'); // This action will not trigger the updated event
        }

        // Move the card to the new row
        $card->row_id = $newRow->id;
        $card->position = $position;
        $card->save();
    }
}

<?php

namespace App\Repositories;

use App\Models\Row;
use App\Contracts\Repositories\RowRepositoryInterface;

class RowRepository implements RowRepositoryInterface
{
    /**
     * Get all rows.
     * @return mixed
     */
    public function all()
    {
        return Row::all();
    }

    /**
     * Get a row by its ID.
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        return Row::find($id);
    }

    /**
     * Get a row by its ID with cards ordered by position.
     * @param int $id
     * @return mixed
     */
    public function findWithOrderedCards(int $id)
    {
        return Row::with(['cards' => function ($query) {
            $query->orderBy('position');
        }])->find($id);
    }

    /**
     * Create a new row.
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Row::create($data);
    }

    /**
     * Update a row.
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        return Row::find($id)->update($data);
    }

    /**
     * Delete a row.
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        $row = Row::find($id);
        $row->cards()->delete();

        // Re adjust the position of the rows in the board
        Row::where('position', '>', $row->position)
            ->where('board_id', $row->board_id)
            ->decrement('position');

        return $row->delete();
    }

    /**
     * Move a row to a new position.
     * @param int $id
     * @param int $position
     * @return void
     */
    public function move(int $id, int $position)
    {
        $row = Row::find($id);
        $oldPosition = $row->position;
        $newPosition = $position;

        if ($oldPosition == $newPosition) {
            return;
        }

        if ($newPosition < $oldPosition) {
            Row::where('position', '>=', $newPosition)
                ->where('position', '<', $oldPosition)
                ->where('board_id', $row->board_id)
                ->increment('position');
        } else {
            Row::where('position', '>', $oldPosition)
                ->where('position', '<=', $newPosition)
                ->where('board_id', $row->board_id)
                ->decrement('position');
        }

        $row->position = $newPosition;
        $row->save();
    }

    /**
     * Add a card to the row.
     * @param int $id
     * @return void
     */
    public function addCard(int $id)
    {
        $row = Row::with('cards')->find($id);
        if (!$row) {
            return;
        }

        $position = $row->cards->count() + 1;

        $row->cards()->create([
            'title' => 'New card',
            'description' => 'New card description',
            'position' => $position,
        ]);
    }

    /**
     * Empty the row.
     * @param int $id
     * @return void
     */
    public function deleteCards(int $id)
    {
        $row = Row::with('cards')->find($id);
        if (!$row) {
            return;
        }

        $row->cards()->delete();
    }
}

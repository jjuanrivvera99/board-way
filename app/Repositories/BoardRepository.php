<?php

namespace App\Repositories;

use App\Models\Board;
use App\Contracts\Repositories\BoardRepositoryInterface;

class BoardRepository implements BoardRepositoryInterface
{
    /**
     * Get all boards.
     * @return mixed
     */
    public function all()
    {
        return Board::all();
    }

    /**
     * Get all boards for the current user.
     * @return mixed
     */
    public function currentUserBoards()
    {
        return Board::where('user_id', auth()->id())->get();
    }

    /**
     * Get a board by its ID.
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        return Board::find($id);
    }

    /**
     * Get a board by its ID with rows ordered by position.
     * @param int $id
     * @return mixed
     */
    public function findWithOrderedRows(int $id)
    {
        return Board::with(['rows' => function ($query) {
            $query->orderBy('position');
        }])->find($id);
    }

    /**
     * Get a board by its ID with rows and cards ordered by position.
     * @param int $id
     * @return mixed
     */
    public function findWithOrderedRowsAndCards(int $id)
    {
        return Board::with([
            'rows' => function ($query) {
                $query->orderBy('position');
            },
            'rows.cards' => function ($query) {
                $query->orderBy('position');
            }
        ])->find($id);
    }

    /**
     * Create a new board.
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Board::create($data);
    }

    /**
     * Update a board.
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        return Board::find($id)->update($data);
    }

    /**
     * Delete a board.
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        return Board::find($id)->delete();
    }

    /**
     * Add a row to a board.
     * @param int $id
     * @return mixed
     */
    public function addRow(int $id)
    {
        $board = Board::find($id);

        $maxPosition = $board->rows->max('position') ?? 0;

        return $board->rows()->create([
            'title' => 'New row',
            'position' => $maxPosition + 1,
        ]);
    }
}

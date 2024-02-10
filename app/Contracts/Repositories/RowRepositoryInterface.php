<?php

namespace App\Contracts\Repositories;

use App\Contracts\BaseRepositoryInterface;

interface RowRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get a board by its ID with cards ordered by position.
     * @param int $id
     * @return mixed
     */
    public function findWithOrderedCards(int $id);

    /**
     * Move a row to a new position.
     * @param int $id
     * @param int $position
     * @return void
     */
    public function move(int $id, int $position);

    /**
     * Add a card to the row.
     * @param int $id
     * @return void
     */
    public function addCard(int $id);

    /**
     * Empty the row.
     * @param int $id
     * @return void
     */
    public function deleteCards(int $id);
}

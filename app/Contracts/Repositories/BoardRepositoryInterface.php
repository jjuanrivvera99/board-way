<?php

namespace App\Contracts\Repositories;

use App\Contracts\BaseRepositoryInterface;

interface BoardRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get a board by its ID with rows ordered by position.
     * @param int $id
     * @return mixed
     */
    public function findWithOrderedRows(int $id);

    /**
     * Get a board by its ID with rows and cards ordered by position.
     * @param int $id
     * @return mixed
     */
    public function findWithOrderedRowsAndCards(int $id);

    /**
     * Add a row to a board.
     * @param int $id
     * @return mixed
     */
    public function addRow(int $id);

    /**
     * Get all boards for the current user.
     * @return mixed
     */
    public function currentUserBoards();
}

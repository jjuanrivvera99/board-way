<?php

namespace App\Contracts\Repositories;

use App\Contracts\BaseRepositoryInterface;

interface CardRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Move a card to a row's position.
     * @param int $id
     * @param int $rowId
     * @param int $position
     * @return void
     */
    public function move(int $id, int $rowId, int $position = 0);
}

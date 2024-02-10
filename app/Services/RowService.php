<?php

namespace App\Services;

use App\Events\RowUpdated;
use App\Events\CardUpdated;
use App\Events\BoardUpdated;
use App\Contracts\Repositories\RowRepositoryInterface;

class RowService
{
    /**
     * The row repository instance.
     * @var RowRepositoryInterface
     */
    protected RowRepositoryInterface $rowRepository;

    /**
     * Create a new service instance.
     * @param RowRepositoryInterface $rowRepository
     * @return void
     */
    public function __construct(RowRepositoryInterface $rowRepository)
    {
        $this->rowRepository = $rowRepository;
    }

    /**
     * Get all rows.
     * @return mixed
     */
    public function all()
    {
        return $this->rowRepository->all();
    }

    /**
     * Get a row by its ID.
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        return $this->rowRepository->find($id);
    }

    /**
     * Get a row by its ID with cards ordered by position.
     * @param int $id
     * @return mixed
     */
    public function findWithOrderedCards(int $id)
    {
        return $this->rowRepository->findWithOrderedCards($id);
    }

    /**
     * Create a new row.
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->rowRepository->create($data);
    }

    /**
     * Update a row.
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        $row = $this->rowRepository->findWithOrderedCards($id);

        // Dispatch the CardUpdated event for each card in the row.
        foreach ($row->cards as $card) {
            CardUpdated::dispatch($card);
        }

        RowUpdated::dispatch($row);
        return $this->rowRepository->update($id, $data);
    }

    /**
     * Delete a row.
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        BoardUpdated::dispatch($this->rowRepository->find($id)->board);
        return $this->rowRepository->delete($id);
    }

    /**
     * Move a row to a new position.
     * @param int $id
     * @param int $position
     * @return void
     */
    public function move(int $id, int $position)
    {
        BoardUpdated::dispatch($this->rowRepository->find($id)->board);
        $this->rowRepository->move($id, $position);
    }

    /**
     * Add a card to the row.
     * @param int $id
     * @return void
     */
    public function addCard(int $id)
    {
        RowUpdated::dispatch($this->rowRepository->find($id));
        $this->rowRepository->addCard($id);
    }

    /**
     * Empty a row.
     * @param int $id
     * @return mixed
     */
    public function deleteCards(int $id)
    {
        $row = $this->rowRepository->find($id);
        RowUpdated::dispatch($row);
        return $this->rowRepository->deleteCards($id);
    }
}

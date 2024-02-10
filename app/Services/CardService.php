<?php

namespace App\Services;

use App\Events\RowUpdated;
use App\Events\CardUpdated;
use App\Contracts\Repositories\CardRepositoryInterface;

class CardService
{
    /**
     * The card repository instance.
     * @var CardRepositoryInterface
     */
    protected CardRepositoryInterface $cardRepository;

    /**
     * Create a new service instance.
     * @param CardRepositoryInterface $cardRepository
     * @return void
     */
    public function __construct(CardRepositoryInterface $cardRepository)
    {
        $this->cardRepository = $cardRepository;
    }

    /**
     * Get all cards.
     * @return mixed
     */
    public function all()
    {
        return $this->cardRepository->all();
    }

    /**
     * Get a card by its ID.
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        return $this->cardRepository->find($id);
    }

    /**
     * Create a new card.
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->cardRepository->create($data);
    }

    /**
     * Update a card.
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        CardUpdated::dispatch($this->cardRepository->find($id));
        return $this->cardRepository->update($id, $data);
    }

    /**
     * Delete a card.
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        RowUpdated::dispatch($this->cardRepository->find($id)->row);
        return $this->cardRepository->delete($id);
    }

    /**
     * Move a card to a row's position.
     * @param int $id
     * @param int $rowId
     * @param int $position
     * @return void
     */
    public function move(int $id, int $rowId, int $position)
    {
        CardUpdated::dispatch($this->cardRepository->find($id));
        RowUpdated::dispatch($this->cardRepository->find($id)->row);
        return $this->cardRepository->move($id, $rowId, $position);
    }
}

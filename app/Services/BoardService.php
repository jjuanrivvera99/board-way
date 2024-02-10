<?php

namespace App\Services;

use App\Events\BoardUpdated;
use App\Contracts\Repositories\BoardRepositoryInterface;

class BoardService
{
    /**
     * The board repository instance.
     * @var BoardRepositoryInterface
     */
    protected BoardRepositoryInterface $boardRepository;

    /**
     * Create a new service instance.
     * @param BoardRepositoryInterface $boardRepository
     * @return void
     */
    public function __construct(BoardRepositoryInterface $boardRepository)
    {
        $this->boardRepository = $boardRepository;
    }

    /**
     * Get all boards.
     * @return mixed
     */
    public function all()
    {
        return $this->boardRepository->all();
    }

    /**
     * Get a board by its ID.
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        return $this->boardRepository->find($id);
    }

    /**
     * Get a board by its ID with rows ordered by position.
     * @param int $id
     * @return mixed
     */
    public function findWithOrderedRows(int $id)
    {
        return $this->boardRepository->findWithOrderedRows($id);
    }

    /**
     * Get a board by its ID with rows and cards ordered by position.
     * @param int $id
     * @return mixed
     */
    public function findWithOrderedRowsAndCards(int $id)
    {
        return $this->boardRepository->findWithOrderedRowsAndCards($id);
    }

    /**
     * Create a new board.
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->boardRepository->create($data);
    }

    /**
     * Update a board.
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        BoardUpdated::dispatch($this->boardRepository->find($id));
        return $this->boardRepository->update($id, $data);
    }

    /**
     * Delete a board.
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        return $this->boardRepository->delete($id);
    }

    /**
     * Add a row to a board.
     * @param int $id
     * @return void
     */
    public function addRow(int $id)
    {
        BoardUpdated::dispatch($this->boardRepository->find($id));
        $this->boardRepository->addRow($id);
    }

    /**
     * Get all boards for the current user.
     * @return mixed
     */
    public function currentUserBoards()
    {
        return $this->boardRepository->currentUserBoards();
    }
}

<?php

namespace App\Contracts;

interface BaseRepositoryInterface
{
    /**
     * Get all resources.
     * @return mixed
     */
    public function all();

    /**
     * Get a resource by its ID.
     * @param int $id
     * @return mixed
     */
    public function find(int $id);

    /**
     * Create a new resource.
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update a resource.
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data);

    /**
     * Delete a resource.
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);
}

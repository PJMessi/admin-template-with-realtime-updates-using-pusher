<?php

namespace App\Repositories;

use App\Driver;

interface DriverRepositoryInterface
{
    /**
     * Returns User Model.
     *
     * @return User
     */
    public function model(): Driver;

    /**
     * Creates new Driver from the given attributes and returns that driver.
     *
     * @param array $attributes
     * @return Driver
     */
    public function store(array $attributes): Driver;

    /**
     * Deletes the given Driver.
     *
     * @param Driver $driver
     * @return void
     */
    public function destroy(Driver $driver): void;

    /**
     * Fetches the driver with the given id and returns that Driver.
     *
     * @param int $id
     * @return Driver
     * @throws ModelNotFoundException
     */
    public function show(int $id): Driver;

    /**
     * Updates the given driver with given attributes and returns that Driver.
     *
     * @param Driver $driver
     * @param array $attributes
     * @return Driver
     */
    public function update(Driver $driver, array $attributes): Driver;
}

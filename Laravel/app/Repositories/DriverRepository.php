<?php

namespace App\Repositories;

use App\Driver;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DriverRepository implements DriverRepositoryInterface
{
    private $model;

    /**
     * UserRepository constructor.
     *
     * @param Admin $model
     */
    public function __construct(Driver $model)
    {
        $this->model = $model;
    }

    /**
     * Returns User Model.
     *
     * @return User
     */
    public function model(): Driver
    {
        return $this->model;
    }

    /**
     * Creates new Driver from the given attributes and returns that driver.
     *
     * @param array $attributes
     * @return Driver
     */
    public function store(array $attributes): Driver
    {
        return $this->model->create($attributes);
    }

    /**
     * Deletes the given Driver.
     *
     * @param Driver $driver
     * @return void
     */
    public function destroy(Driver $driver): void
    {
        $driver->delete();
    }

    /**
     * Fetches the driver with the given id and returns that Driver.
     *
     * @param int $id
     * @return Driver
     * @throws ModelNotFoundException
     */
    public function show(int $id): Driver
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Updates the given driver with given attributes and returns that Driver.
     *
     * @param Driver $driver
     * @param array $attributes
     * @return Driver
     */
    public function update(Driver $driver, array $attributes): Driver
    {
        $driver->update($attributes);

        return $driver;
    }
}

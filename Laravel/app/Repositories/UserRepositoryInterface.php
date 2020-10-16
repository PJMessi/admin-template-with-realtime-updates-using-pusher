<?php

namespace App\Repositories;

use App\User;

interface UserRepositoryInterface
{
    /**
     * Returns User Model.
     *
     * @return User
     */
    public function getModel(): User;

    /**
     * Fetches and returns the User with given id.
     *
     * @return User
     * @throws ModelNotFoundException
     */
    public function get(int $id): User;

    /**
     * Creates new User from the given attributes and returns that User.
     *
     * @param array $attributes
     * @return User
     */
    public function store(array $attributes): User;

    /**
     * Updates the given user with given attributes and returns the user.
     *
     * @param User $user
     * @param array $attributes
     * @return User
     */
    public function update(User $user, array $attributes): User;

    /**
     * Deletes the given User.
     *
     * @param User $user
     * @return bool|null
     * @throws Exception
     */
    public function destory(User $user);
}

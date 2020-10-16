<?php

namespace App\Repositories;

use App\Repositories\UserRepositoryInterface;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    private $model;

    /**
     * UserRepository constructor.
     *
     * @param Admin $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Returns User Model.
     *
     * @return User
     */
    public function getModel(): User
    {
        return $this->model;
    }

    /**
     * Fetches and returns the User with given id.
     *
     * @return User
     * @throws ModelNotFoundException
     */
    public function get(int $id): User
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Creates new User from the given attributes and returns that User.
     *
     * @param array $attributes
     * @return User
     */
    public function store(array $attributes): User
    {
        if (array_key_exists("password", $attributes)) {
            $attributes["password"] = Hash::make($attributes["password"]);
        }

        return $this->model->create($attributes);
    }

    /**
     * Updates the given user with given attributes and returns the user.
     *
     * @param User $user
     * @param array $attributes
     * @return User
     */
    public function update(User $user, array $attributes): User
    {
        if (array_key_exists("password", $attributes) && $attributes["password"] != null && $attributes["password"] != "") {
            $attributes["password"] = Hash::make($attributes["password"]);
        } else {
            unset($attributes['password']);
        }

        $user->update($attributes);

        return $user;
    }

    /**
     * Deletes the given User.
     *
     * @param User $user
     * @return bool|null
     * @throws Exception
     */
    public function destory(User $user)
    {
        return $user->delete();
    }
}

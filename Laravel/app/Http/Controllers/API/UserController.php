<?php

namespace App\Http\Controllers\API;

use App\Events\UserEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    private $userRepository;

    /**
     * UserController Constructor.
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Creates new User.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $attributes = $this->validate($request, [
                "name" => "required|string|min:3|max:255",
                "email" => "required|email|unique:users,email",
                "password" => "required|string|confirmed|min:5|max:255"
            ]);

            $user = $this->userRepository->store($attributes);

            DB::commit();

            broadcast(new UserEvent('user-added', Auth::user(), $user))->toOthers();

            return response()->json([
                "status" => 200,
                "message" => "New user added successfully."
            ]);

        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
    }

    /**
     * Filters and fetches the list of users.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $userModel = $this->userRepository->getModel();

        try {
            $this->validate($request, [
                "limit" => "nullable|integer",
                "sort_by" => ["nullable", Rule::in($userModel::$sortable)],
                "sort_order" => "nullable|in:asc,desc",
                "page" => "nullable|integer",
                "q" => "nullable|string"
            ]);

            $users = $userModel->orderBy(
                $request->sort_by ? $request->sort_by : "id",
                $request->sort_order ? $request->sort_order : "desc"
            );

            if ($request->q) {
                $users = $users->whereLike($userModel::$searchable, $request->q);
            }

            $users = $users->paginate($request->limit);

            return response()->json([
                "status" => 200,
                "message" => "Users fetched successfully.",
                "data" => UserResource::collection($users)->resource
            ]);

        } catch (ValidationException $exception) {
            return response()->json([
                "status" => 422,
                "message" => $exception->getMessage(),
                "errors" => $exception->errors()
            ], 422);
        }
    }

    /**
     * Updates the given User.
     *
     * @param Request $request
     * @param $user_id
     * @return JsonResponse
     * @throws Exception
     */
    public function update(Request $request, $user_id)
    {
        try {
            DB::beginTransaction();

            $attributes = $this->validate($request, [
                "name" => "sometimes|string|min:3|max:255",
                "email" => "sometimes|email|unique:users,email,{$user_id}",
                "password" => "nullable|string|confirmed",
            ]);

            $user = $this->userRepository->get($user_id);

            $user = $this->userRepository->update($user, $attributes);

            DB::commit();

            broadcast(new UserEvent('user-updated', Auth::user(), $user))->toOthers();

            return response()->json([
                "status" => 200,
                "message" => "User updated successfully.",
                "data" => new UserResource($user)
            ]);

        } catch(ValidationException $exception) {
            DB::rollback();
            return response()->json([
                "status" => 422,
                "message" => $exception->getMessage(),
                "errors" => $exception->errors()
            ], 422);

        } catch(ModelNotFoundException $exception) {
            DB::rollback();
            return response()->json([
                "status" => 404,
                "message" => $exception->getMessage()
            ], 404);

        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
    }

    /**
     * Deletes the user with given id.
     *
     * @param $user_id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy($user_id)
    {
        try {
            DB::beginTransaction();

            $user = $this->userRepository->get($user_id);

            $this->userRepository->destory($user);

            DB::commit();

            broadcast(new UserEvent('user-deleted', Auth::user(), $user))->toOthers();

            return response()->json([
                "status" => 200,
                "message" => "User deleted successfully."
            ]);

        } catch (ModelNotFoundException $exception) {
            DB::rollback();
            return response()->json([
                "status" => 404,
                "message" => $exception->getMessage()
            ], 404);

        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
    }

    /**
     * Fetches user with given id.
     *
     * @param $user_id
     * @return JsonResponse
     */
    public function show($user_id)
    {
        try {
            $user = $this->userRepository->get($user_id);

            return response()->json([
                "status" => 200,
                "message" => "User fetched successfully.",
                "data" => new UserResource($user)
            ]);

        } catch (ModelNotFoundException $exception) {
            return response()->json([
                "status" => 404,
                "message" => $exception->getMessage()
            ], 404);
        }
    }
}

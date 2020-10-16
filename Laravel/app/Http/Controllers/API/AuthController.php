<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Creates a Personal Access Token for the User.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function login(Request $request)
    {
        try {
            DB::beginTransaction();

            $credentials = $this->validate($request, [
                "email" => "required|email",
                "password" => "required|string"
            ]);

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    "status" => 401,
                    "message" => "Invalid Credentials."
                ], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('Personal Access Token')->accessToken;

            DB::commit();

            return response()->json([
                "status" => 200,
                "message" => "User logged in successfully.",
                "data" => [
                    "token" => $token,
                    "user" => $user
                ]
            ]);

        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
    }

    /**
     * Revokes the current token of the User.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function logout(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = $request->user();

            $token = $user->token();

            $token->revoke();

            DB::commit();

            return response()->json([
                "status" => 200,
                "message" => "User logged out successfully."
            ]);

        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
    }
}

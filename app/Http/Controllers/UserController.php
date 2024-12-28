<?php

namespace App\Http\Controllers;

use App\Constants\UserRole;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class UserController extends Controller
{
    /**
     * ユーザー情報を登録する
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        $input = $request->validated();
        switch ($input['role']) {
            case UserRole::STAFF:
                $input['tel'] = null;
                break;
            case UserRole::ADMIN:
                $input['nickname'] = null;
                break;
            default:
                throw new \InvalidArgumentException('Invalid role.');
        }
        $user = User::create($input);

        return response()->json([
            'id' => $user->id,
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     * ログイン
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        if (!auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return response()->json(status: JsonResponse::HTTP_NO_CONTENT);
    }


    /**
     * ログインユーザー情報を取得する
     */
    public function showMe(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}

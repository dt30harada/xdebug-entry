<?php

namespace App\Http\Requests;

use App\Constants\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * ユーザー情報登録のリクエスト
 */
final class UserStoreRequest extends FormRequest
{
    /**
     * ユーザー情報を登録する権限があるか判定する
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    /**
     * バリデーションルール
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in([UserRole::STAFF, UserRole::ADMIN])],
            'nickname' => ['nullable', 'required_if:role,'.UserRole::STAFF, 'string', 'max:50'],
            'tel' => ['nullable', 'required_if:role,'.UserRole::ADMIN, 'string', 'max:20'],
        ];
    }
}

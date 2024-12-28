<?php

namespace Tests\Feature;

use App\Constants\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * ユーザー情報登録APIのテスト
 */
class UserStoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider data_管理者がユーザー情報を正常に登録できる
     */
    public function 管理者がユーザー情報を正常に登録できる(array $request): void
    {
        $admin = User::factory()->admin()->create();
        Carbon::setTestNow($now = '2024-12-28 14:10:20');
        $request = array_merge($request, [
            'name' => 'test-user',
            'email' => 'test@sample.org',
            'password' => 'password!',
        ]);

        $response = $this->actingAs($admin)->postJson('/api/users', $request);

        $response->assertStatus(201);
        $this->assertIsInt($response->json('id'));
        $this->assertDatabaseHas('users', [
            'name' => $request['name'],
            'email' => $request['email'],
            'role' => $request['role'],
            'nickname' => $request['nickname'],
            'tel' => $request['tel'],
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public static function data_管理者がユーザー情報を正常に登録できる(): array
    {
        return [
            'スタッフ権限のユーザー情報を登録' => [
                'request' => [
                    'role' => UserRole::STAFF,
                    'nickname' => 'test-nickname',
                    'tel' => null,
                ],
            ],
            '管理者権限のユーザー情報を登録' => [
                'request' => [
                    'role' => UserRole::ADMIN,
                    'nickname' => null,
                    'tel' => '050-1234-6789',
                ],
            ],
        ];
    }

    /**
     * @test
     */
    public function 入力値が不正な場合はバリデーションエラーレスポンスを返す(): void
    {
        $admin = User::factory()->admin()->create();
        $request = [
            'name' => 'test-user',
            'email' => 'test@sample.org',
            'password' => 'password!',
            'role' => true,
        ];

        $response = $this->actingAs($admin)->postJson('/api/users', $request);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'role',
        ]);
    }
}

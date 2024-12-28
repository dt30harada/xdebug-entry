<?php

namespace Tests\Feature;

use App\Constants\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * ログインユーザー情報取得APIのテスト
 */
class UserShowMeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function ログインユーザー情報を正常に取得できる(): void
    {
        Carbon::setTestNow('2024-12-28 14:00:30');
        $user = User::factory()->staff()->create();

        $response = $this->actingAs($user)->get('/api/users/me');

        // TODO: debug
        $response->assertStatus(200);
        $response->assertExactJson([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => UserRole::STAFF,
            'nickname' => $user->nickname,
            'tel' => null,
            'created_at' => '2024-12-28T14:00:30.000000Z',
            'updated_at' => '2024-12-28T14:00:30.000000Z',
        ]);
    }
}

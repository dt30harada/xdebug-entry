<?php

namespace Database\Factories;

use App\Constants\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
        ];
    }

    /**
     * スタッフユーザーとして定義する
     */
    public function staff()
    {
        return $this->state(function () {
            return [
                'role' => UserRole::STAFF,
                'nickname' => $this->faker->name(),
            ];
        });
    }

    /**
     * 管理者ユーザーとして定義する
     */
    public function admin()
    {
        return $this->state(function () {
            return [
                'role' => UserRole::ADMIN,
                'tel' => $this->faker->phoneNumber(),
            ];
        });
    }
}

<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class UserFactory extends Factory
{

    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            // 'type' => new Sequence('user', 'admin'),
            'type' => Arr::random(['admin', 'user']),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function user()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'user',
            ];
        });
    }

    /** @noinspection PhpUnusedParameterInspection */
    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'admin',
            ];
        });
    }

}

<?php

namespace Database\Factories;

use Cr4sec\AtomVPN\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    protected $model = Account::class;

    public function definition(): array
    {
        return [
            'uuid' => $this->faker->unique()->uuid,
            'vpn_username' => $this->faker->userName,
            'vpn_password' => $this->faker->password,
            'multi_login' => config('atom_vpn.default_count_of_vpn_user_sessions'),
            'session_limit' => 300,
            'enabled' => true,
            'expires_at' => now()->addMonth(),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    /**
     * Indicate that the user is suspended.
     *
     * @return Factory
     */
    public function expired(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'expires_at' => now()->subDays(5),
            ];
        });
    }
}

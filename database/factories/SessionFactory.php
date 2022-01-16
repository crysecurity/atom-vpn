<?php

namespace Database\Factories;

use Cr4sec\AtomVPN\Models\Session;
use Illuminate\Database\Eloquent\Factories\Factory;

class SessionFactory extends Factory
{
    protected $model = Session::class;

    public function definition(): array
    {
        return [
            'started_at' => null,
            'closed_at' => null,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    /**
     * Indicate that the user is suspended.
     *
     * @return Factory
     */
    public function open(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'started_at' => now(),
                'closed_at' => null
            ];
        });
    }

    /**
     * Indicate that the user is suspended.
     *
     * @return Factory
     */
    public function closed(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'started_at' => now()->subMinutes(10),
                'closed_at' => now(),
                'created_at' => now()->subMinutes(10)
            ];
        });
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
                'started_at' => now()->subDay()->subMinute(),
                'closed_at' => null,
                'created_at' => now()->subDay()->subMinute()
            ];
        });
    }
}

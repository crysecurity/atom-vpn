<?php

namespace Database\Factories;

use Cr4sec\AtomVPN\Models\Server;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServerFactory extends Factory
{
    protected $model = Server::class;

    public function definition(): array
    {
        return [
            'free' => false,
            'country' => $this->faker->country,
            'icon' => $this->faker->imageUrl(),
            'hostname' => $this->faker->ipv4,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    /**
     * Indicate that the user is suspended.
     *
     * @return Factory
     */
    public function free(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'free' => true,
            ];
        });
    }
}

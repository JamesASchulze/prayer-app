<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PrayerRequest>
 */
class PrayerRequestFactory extends Factory
{
    public function definition(): array
    {
        $isPraise = $this->faker->boolean(20); // 20% chance of being a praise

        return [
            'title' => $isPraise ? 'Praise Report: ' . $this->faker->sentence() : 'Prayer Request: ' . $this->faker->sentence(),
            'request' => $this->faker->paragraph(),
            'is_praise' => $isPraise,
        ];
    }
} 
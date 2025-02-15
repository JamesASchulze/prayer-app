<?php

namespace Database\Seeders;

use App\Models\PrayerRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class PrayerRequestSeeder extends Seeder
{
    public function run(): void
    {
        $requests = [
            [
                'request' => 'Please pray for my mother who is undergoing surgery next week.',
                'is_praise' => false,
                'follow_up_email' => 'john@example.com',
                'user_id' => User::where('email', 'john@example.com')->first()?->id,
            ],
            [
                'request' => 'Praise God! My son got accepted into medical school!',
                'is_praise' => true,
                'follow_up_email' => 'sarah@example.com',
                'user_id' => User::where('email', 'sarah@example.com')->first()?->id,
            ],
            [
                'request' => 'Need prayer for wisdom in making a career decision.',
                'is_praise' => false,
                'follow_up_email' => null,
                'user_id' => null,
            ],
            [
                'request' => 'Celebrating one year of sobriety - thank you for your prayers!',
                'is_praise' => true,
                'follow_up_email' => 'mike@example.com',
                'user_id' => User::where('email', 'mike@example.com')->first()?->id,
            ],
            [
                'request' => 'Please pray for our marriage counseling journey.',
                'is_praise' => false,
                'follow_up_email' => null,
                'user_id' => null,
            ],
        ];

        foreach ($requests as $request) {
            PrayerRequest::create($request);
        }
    }
}
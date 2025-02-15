<?php

namespace Database\Seeders;

use App\Models\PrayerCount;
use App\Models\PrayerRequest;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrayerCountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all prayer requests and users
        $prayerRequests = PrayerRequest::all();
        $users = User::all();

        // Create some sample prayer counts
        foreach ($prayerRequests as $request) {
            // Randomly select 1-3 users who prayed for each request
            $randomUsers = $users->random(rand(1, 3));
            
            foreach ($randomUsers as $user) {
                PrayerCount::create([
                    'prayer_request_id' => $request->id,
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}

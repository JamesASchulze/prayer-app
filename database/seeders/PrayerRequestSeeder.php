<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\PrayerRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class PrayerRequestSeeder extends Seeder
{
    public function run(): void
    {
        $organizations = Organization::all();
        
        // Create users for each organization
        foreach ($organizations as $organization) {
            // Create 3 users per organization
            $users = User::factory(3)->create([
                'organization_id' => $organization->id,
            ]);

            // Create prayer requests for each user
            $users->each(function ($user) {
                PrayerRequest::factory(5)->create([
                    'user_id' => $user->id,
                    'organization_id' => $user->organization_id,
                ]);
            });
        }
    }
}
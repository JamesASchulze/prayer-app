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
        $organizations = User::select('organization_id')->distinct()->get();

        foreach ($organizations as $org) {
            $orgUsers = User::where('organization_id', $org->organization_id)->get();
            $orgPrayerRequests = PrayerRequest::where('organization_id', $org->organization_id)->get();

            // For each prayer request in the organization, create some random prayer counts
            $orgPrayerRequests->each(function ($prayerRequest) use ($orgUsers) {
                // Create 1-5 random prayer counts for each request
                $randomUsers = $orgUsers->random(rand(1, min(5, $orgUsers->count())));
                
                foreach ($randomUsers as $user) {
                    PrayerCount::create([
                        'user_id' => $user->id,
                        'prayer_request_id' => $prayerRequest->id,
                        'organization_id' => $user->organization_id,
                    ]);
                }
            });
        }
    }
}

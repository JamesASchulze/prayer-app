<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create organizations first
        $this->call(OrganizationSeeder::class);

        // Create admin users for each organization
        $organizations = Organization::all();
        
        foreach ($organizations as $index => $organization) {
            User::create([
                'name' => "Admin {$organization->name}",
                'email' => "admin{$index}@example.com",
                'password' => bcrypt('password'),
                'is_admin' => true,
                'organization_id' => $organization->id,
            ]);
        }

        // Call other seeders
        $this->call([
            // UserSeeder::class,
            PrayerRequestSeeder::class,
            PrayerCountSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $organizations = [
            [
                'name' => 'First Baptist Church',
                'slug' => 'first-baptist',
            ],
            [
                'name' => 'Grace Community Church',
                'slug' => 'grace-community',
            ],
        ];

        foreach ($organizations as $org) {
            Organization::create($org);
        }
    }
} 
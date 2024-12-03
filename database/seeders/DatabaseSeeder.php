<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\BusinessEntity;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        User::create([
            'name' => 'Test User',
            'username' => 'admin',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name' => 'HR',
            'username' => 'hr',
            'password' => bcrypt('hr'),
        ]);

        $entities = [
            ['name' => 'PT Maju Kendaraan Listrik Indonesia', 'code' => 'MKLI'],
            ['name' => 'PT Retail Indonesia Selalu Maju', 'code' => 'RISM'],
            ['name' => 'CV Maju Tecnologi', 'code' => 'MAJU'],
            ['name' => 'CV Top Selular', 'code' => 'CVTOP'],
            ['name' => 'PT Media Selular Indonesia', 'code' => 'MSI'],
            ['name' => 'AAN/CS', 'code' => 'AANCS'],
            ['name' => 'Complete Mulia', 'code' => 'CM'],
        ];

        foreach ($entities as $entity) {
            BusinessEntity::create($entity);
        }

        Artisan::call('shield:super-admin', ['--user' => 1]);
        Artisan::call('shield:generate --all --panel=admin');
    }
}

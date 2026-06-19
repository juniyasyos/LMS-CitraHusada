<?php

namespace Database\Seeders;

// use App\Models\Role;
// use App\Models\JenisTenaga;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed master data
        $this->call(RoleSeeder::class);
        $this->call(JenisTenagaSeeder::class);
        $this->call(UnitKerjasSeeder::class); // Menggunakan UnitKerjasSeeder hasil export DB
        $this->call(KategoriSeeder::class);
        $this->call(UserSeeder::class);
        
        $this->call(BackupSettingSeeder::class);
        $this->call(MotivationQuoteSeeder::class);
    }
}
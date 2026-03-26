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
        // Seed roles
        $this->call(RoleSeeder::class);

        // Call seeder untuk jenis tenaga
        $this->call(JenisTenagaSeeder::class);

        // Call seeder untuk unit kerja
        $this->call(UnitKerjaSeeder::class);

        // create default admin user
        $this->call(UserSeeder::class);

        $this->call([NotificationSeeder::class,]);
    }
}

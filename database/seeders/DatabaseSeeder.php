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
        $this->call(BackupSettingSeeder::class);

        // create default admin user
        $this->call(UserSeeder::class);

        // Seed data pelatihan & sub-data dari database asli
        $this->call([
            MaterisSeeder::class,
            SubMaterisSeeder::class,
            PostTestsSeeder::class,
            SoalsSeeder::class,
            MateriJenisTenagasSeeder::class,
            MateriUnitKerjasSeeder::class,
            SkorUsersSeeder::class,
        ]);

        // Optional default data
        $this->call([
            NotificationSeeder::class,
            MotivationQuoteSeeder::class,
        ]);
    }
}
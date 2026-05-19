<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BackupSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\BackupSetting::updateOrCreate(
            ['id' => 1],
            [
                'is_active' => false,
                'frequency' => 'weekly',
                'execution_time' => '02:00:00'
            ]
        );
    }
}

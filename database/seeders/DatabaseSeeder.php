<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermittedAppSeeder::class,
            SettingSeeder::class,
            UserSeeder::class,
        ]);

        if (App::isLocal()) {
            $this->call([
                CentreSeeder::class,
                CourseSeeder::class,
                EventSeeder::class
            ]);
        }
    }
}

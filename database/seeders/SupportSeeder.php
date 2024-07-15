<?php

namespace Database\Seeders;

use App\Models\Common\Support;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Support::factory(30)
            ->forUser()
            ->create();
    }
}

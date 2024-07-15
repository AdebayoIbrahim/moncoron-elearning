<?php

namespace Database\Seeders;

use App\Models\PermittedApp;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermittedAppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appName = 'Moncoran Mobile App';
        $key = Str::random(16);
        PermittedApp::create([
            'name'      => $appName,
            'type'      => 'mobile',
            'url'       => null,
            'app_key'   => sha1(Str::random()),
            'status'    => true
        ]);
    }
}

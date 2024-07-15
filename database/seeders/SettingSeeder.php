<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    protected $settings = [
        'locales'       => 'en',
        'countries'     => 'Nigeria,Gambia,Guinea',
        'max_subscriptions.below_eighten' => 5,
        'max_subscriptions.eighteen_plus' => 3,
        'legal.privacy-policy' => '',
        'legal.tos' => ''
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->settings as $key => $setting){
            setting()->set($key, $setting);
        }

        setting()->save();
    }
}

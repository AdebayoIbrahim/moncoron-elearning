<?php

namespace Database\Seeders;

use App\Models\User;
use App\Traits\Auth\HasPermissions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    use HasPermissions;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'ref'           => Str::random(),
            'name'          => 'John Doe',
            'email'         => 'johndoe@gmail.com',
            'locale'        => 'en',
            'phone'         => '2348033333333',
            'country'       => 'Gambia',
            'status'        => true,
            'password'      => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'dob'           => now()->subYears(24),
            'role'          => 'admin'
        ]);
        $admin->markEmailAsVerified();
        // TODO - Wallet

        if(! app()->environment('production')){
            $users = User::factory(50)->create();
            // TODO -- Wallet
        }
    }
}

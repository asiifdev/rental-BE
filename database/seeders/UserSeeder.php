<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@mail.com',
            'photo' => 'https://ui-avatars.com/api/?name=Super Admin&background=random',
            'password' => bcrypt('password')
        ]);
        $superadmin->assignRole('Super Admin');
    }
}

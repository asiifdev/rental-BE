<?php

namespace Database\Seeders;

use App\Models\CompanySetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanySetting::create([
            'name' => 'Asiifdev Digital Agency',
            'email' => 'admin@asiifdev.com',
            'phone' => '+6282134462498',
            'meta_description' => 'Jasa Pembuatan Website Professional',
            'meta_keyword' => 'Jasa Pembuatan Website Professional',
            'about' => '.',
            'logo' => 'assets/logo.png',
            'icon' => 'assets/icon.png',
            'address' => 'Kestalan 005/001, Nepen, Teras, Boyolali, Jawa Tengah 57372'
        ]);
    }
}

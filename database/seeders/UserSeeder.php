<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //SEED FULBERTODEV ADMIN
        User::create([
            'name' => 'Fulberto Dev',
            'email_verified_at' => now(),
            'email' => 'fulbertodev@gmail.com',
            'password' => Hash::make('fulbertodev'),
            'isAdmin' => true,
        ]);
    }
}

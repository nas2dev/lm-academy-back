<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $albanianUsers = [
            [
                'first_name' => 'Arben',
                'last_name' => 'Hoxha',
                'gender' => 'male',
                'email' => 'arben.hoxha@example.com',
                'date_of_birth' => '1995-03-15',
                'academic_year' => 3,
                'password' => Hash::make('password123'),
                'profile_completed' => true,
            ],
            [
                'first_name' => 'Enkeleda',
                'last_name' => 'Shehu',
                'gender' => 'female',
                'email' => 'enkeleda.shehu@example.com',
                'date_of_birth' => '1998-07-22',
                'academic_year' => 2,
                'password' => Hash::make('password123'),
                'profile_completed' => true,
            ],
            [
                'first_name' => 'Dritan',
                'last_name' => 'Prifti',
                'gender' => 'male',
                'email' => 'dritan.prifti@example.com',
                'date_of_birth' => '1997-11-30',
                'academic_year' => 4,
                'password' => Hash::make('password123'),
                'profile_completed' => true,
            ],
            [
                'first_name' => 'Blerina',
                'last_name' => 'Kola',
                'gender' => 'female',
                'email' => 'blerina.kola@example.com',
                'date_of_birth' => '1999-05-18',
                'academic_year' => 1,
                'password' => Hash::make('password123'),
                'profile_completed' => true,
            ],
            [
                'first_name' => 'Genti',
                'last_name' => 'Brahimi',
                'gender' => 'male',
                'email' => 'genti.brahimi@example.com',
                'date_of_birth' => '1996-09-25',
                'academic_year' => 3,
                'password' => Hash::make('password123'),
                'profile_completed' => false,
            ],
        ];

        foreach ($albanianUsers as $user) {
            User::create($user);
        }
    }
}

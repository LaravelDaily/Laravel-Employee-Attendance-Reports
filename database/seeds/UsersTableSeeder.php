<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'name'           => 'Admin',
                'email'          => 'admin@admin.com',
                'password'       => '$2y$10$Vr/mYRcIkhXVoBryt538Au/B/X64uDT91KtDJVrbIQc5IRp2IdPVS',
                'remember_token' => null,
            ]
        ];

        for($i = 1; $i <= 10; $i++)
        {
            array_push($users, [
                'id'             => $i+1,
                'name'           => 'Employee ' . $i,
                'email'          => 'employee' . $i . '@employee' . $i . '.com',
                'password'       => '$2y$10$Vr/mYRcIkhXVoBryt538Au/B/X64uDT91KtDJVrbIQc5IRp2IdPVS',
                'remember_token' => null,
            ]);
        }

        User::insert($users);
    }
}

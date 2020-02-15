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
                'password'       => '$2y$10$cfAgZGvh.6ORlXLIg0vfV.0tfGgS8gs3YIn.H.Ppmn9dPiVywpWPy',
                'remember_token' => null,
            ],
        ];

        User::insert($users);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
                                       [
                                           'email'      => 'admin@example.com',
                                           'password'   => Hash::make('password'),
                                           'name'       => 'Admin User',
                                           'role'       => 'admin',
                                           'status'     => 'active',
                                           'created_at' => now(),
                                           'updated_at' => now(),
                                           'delete_flg' => 0,
                                       ],
                                       [
                                           'email'      => 'user1@example.com',
                                           'password'   => Hash::make('password'),
                                           'name'       => 'User One',
                                           'role'       => 'user',
                                           'status'     => 'inactive',
                                           'created_at' => now(),
                                           'updated_at' => now(),
                                           'delete_flg' => 0,
                                       ],
                                       [
                                           'email'      => 'user2@example.com',
                                           'password'   => Hash::make('password'),
                                           'name'       => 'User Two',
                                           'role'       => 'user',
                                           'status'     => 'inactive',
                                           'created_at' => now(),
                                           'updated_at' => now(),
                                           'delete_flg' => 0,
                                       ],
                                   ]);
    }
}


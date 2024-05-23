<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stores')->insert([
                                        [
                                            'user_id'     => 1,
                                            'name'        => 'Store One',
                                            'description' => 'Description for Store One',
                                            'address'     => '123 Main St, City, Country',
                                            'status'      => 'active',
                                            'created_at'  => now(),
                                            'updated_at'  => now(),
                                            'delete_flg'  => 0,
                                        ],
                                        [
                                            'user_id'     => 2,
                                            'name'        => 'Store Two',
                                            'description' => 'Description for Store Two',
                                            'address'     => '456 Elm St, City, Country',
                                            'status'      => 'inactive',
                                            'created_at'  => now(),
                                            'updated_at'  => now(),
                                            'delete_flg'  => 0,
                                        ],
                                        [
                                            'user_id'     => 3,
                                            'name'        => 'Store Three',
                                            'description' => 'Description for Store Three',
                                            'address'     => '789 Oak St, City, Country',
                                            'status'      => 'active',
                                            'created_at'  => now(),
                                            'updated_at'  => now(),
                                            'delete_flg'  => 0,
                                        ],
                                    ]);
    }
}

<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker  = Faker::create();
        $stores = DB::table('stores')->pluck('id')->toArray();

        foreach (range(1, 50) as $index) {
            DB::table('products')->insert([
                                              'store_id'    => $faker->randomElement($stores),
                                              'name'        => $faker->word,
                                              'description' => $faker->paragraph,
                                              'price'       => $faker->numberBetween(),
                                              'quantity'    => $faker->numberBetween(1, 100),
                                              'status'      => $faker->randomElement(['active', 'inactive']),
                                              'created_at'  => now(),
                                              'updated_at'  => now(),
                                              'delete_flg'  => 0,
                                          ]);
        }
    }
}


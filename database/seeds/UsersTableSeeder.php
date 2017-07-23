<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		DB::table('users')->insert([
		  'name' => 'Paluj',
		  'email' => 'paluj@yandex.ru',
		  'password' => bcrypt(123456),
		  'admin' => 1,
		]);		
    }
}

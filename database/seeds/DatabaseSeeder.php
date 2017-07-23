<?php
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
		$this->call(CategorySeeder::class);
		$this->call(GoodsSeeder::class);
		$this->call(PropCatSeeder::class);
		$this->call(PropertiesSeeder::class);
		$this->call(PropertiesValuesSeeder::class);
    }
}

<?php

use Illuminate\Database\Seeder;

class GoodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		DB::table('goods')->insert([
											[
												'id' => 1,
												'name_goods' => 'Samsung',
												'id_category' => 3
											],											
											[
												'id' => 2,
												'name_goods' => 'Nokia',
												'id_category' => 3
											],											
											[
												'id' => 3,
												'name_goods' => 'iphone',
												'id_category' => 4
											],											
											[
												'id' => 4,
												'name_goods' => 'Sony',
												'id_category' => 5
											],											
											[
												'id' => 5,
												'name_goods' => 'БМВ',
												'id_category' => 7
											],											
											[
												'id' => 6,
												'name_goods' => 'Мото Восход',
												'id_category' => 8
											],											
											[
												'id' => 7,
												'name_goods' => 'Suzuki',
												'id_category' => 8
											]
											
									]);		
    }
}


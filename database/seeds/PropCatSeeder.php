<?php

use Illuminate\Database\Seeder;

class PropCatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		DB::table('prop_cat')->insert([
											[
												'id_category' => 1,
												'id_property' => 3  
											],
											[
												'id_category' => 2,
												'id_property' => 4  
											],
											[
												'id_category' => 2,
												'id_property' => 5  
											],
											[
												'id_category' => 2,
												'id_property' => 6  
											],
											[
												'id_category' => 3,
												'id_property' => 7  
											],
											[
												'id_category' => 4,
												'id_property' => 8  
											],
											[
												'id_category' => 5,
												'id_property' => 9  
											],
											[
												'id_category' => 6,
												'id_property' => 10  
											],
											[
												'id_category' => 6,
												'id_property' => 11  
											],
											[
												'id_category' => 7,
												'id_property' => 12  
											],
											[
												'id_category' => 8,
												'id_property' => 13  
											]
										]);
    }
}

<?php

use Illuminate\Database\Seeder;

class PropertiesValuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		DB::table('properties_values')->insert([
											[
												'id_property' => 5,
												'id_goods' => 1,
												'value' => 'Samsung A3231'
											],
											[
												'id_property' => 6,
												'id_goods' => 1,
												'value' => '2Гб'
											],
											[
												'id_property' => 7,
												'id_goods' => 1,
												'value' => '4.1'
											],
											[
												'id_property' => 1,
												'id_goods' => 1,
												'value' => 'img_url:img_good_1_0.jpg'
											],
											[
												'id_property' => 1,
												'id_goods' => 1,
												'value' => 'img_url:img_good_1_1.jpg'
											],
											[
												'id_property' => 1,
												'id_goods' => 1,
												'value' => 'img_url:img_good_1_2.jpg'
											],
											[
												'id_property' => 2,
												'id_goods' => 1,
												'value' => 'Samsung Galaxy S5'
											],
											[
												'id_property' => 3,
												'id_goods' => 2,
												'value' => '2000'
											],
											[
												'id_property' => 5,
												'id_goods' => 2,
												'value' => 'NA'
											],
											[
												'id_property' => 6,
												'id_goods' => 2,
												'value' => '1Гб'
											],
											[
												'id_property' => 7,
												'id_goods' => 2,
												'value' => '5.0'
											],
											[
												'id_property' => 1,
												'id_goods' => 2,
												'value' => 'img_url:img_good_2_0.jpg'
											],
											[
												'id_property' => 1,
												'id_goods' => 2,
												'value' => 'img_url:img_good_2_1.jpg'
											],
											[
												'id_property' => 2,
												'id_goods' => 3,
												'value' => 'Nokia 5'
											],
											[
												'id_property' => 15,
												'id_goods' => 3,
												'value' => 'Реплика China'
											],
											[
												'id_property' => 3,
												'id_goods' => 3,
												'value' => '999999'
											],
											[
												'id_property' => 4,
												'id_goods' => 3,
												'value' => 'НЕТ'
											],
											[
												'id_property' => 5,
												'id_goods' => 3,
												'value' => 'Intel Core)'
											],
											[
												'id_property' => 6,
												'id_goods' => 3,
												'value' => '256Мб'
											],
											[
												'id_property' => 1,
												'id_goods' => 3,
												'value' => 'img_url:img_good_3_0.jpg'
											],
											[
												'id_property' => 1,
												'id_goods' => 3,
												'value' => 'img_url:img_good_3_1.jpg'
											],
											[
												'id_property' => 1,
												'id_goods' => 3,
												'value' => 'img_url:img_good_3_2.jpg'
											],
											[
												'id_property' => 2,
												'id_goods' => 3,
												'value' => 'iphone 4'
											],
											[
												'id_property' => 16,
												'id_goods' => 4,
												'value' => '21"'
											],
											[
												'id_property' => 19,
												'id_goods' => 4,
												'value' => '5кг'
											],
											[
												'id_property' => 20,
												'id_goods' => 4,
												'value' => 'Да'
											],
											[
												'id_property' => 3,
												'id_goods' => 4,
												'value' => '15000'
											],
											[
												'id_property' => 8,
												'id_goods' => 4,
												'value' => 'LCD'
											],
											[
												'id_property' => 1,
												'id_goods' => 4,
												'value' => 'img_url:img_good_4_0.jpg'
											],
											[
												'id_property' => 1,
												'id_goods' => 4,
												'value' => 'img_url:img_good_4_0.jpg'
											],
											[
												'id_property' => 2,
												'id_goods' => 4,
												'value' => 'Sony Tr543'
											],
											[
												'id_property' => 9,
												'id_goods' => 5,
												'value' => '200лс'
											],
											[
												'id_property' => 10,
												'id_goods' => 5,
												'value' => 'BMW'
											],
											[
												'id_property' => 11,
												'id_goods' => 5,
												'value' => 'Дизель'
											],
											[
												'id_property' => 1,
												'id_goods' => 5,
												'value' => 'img_url:img_good_5_0.jpg'
											],
											[
												'id_property' => 1,
												'id_goods' => 5,
												'value' => 'img_url:img_good_5_1.jpg'
											],
											[
												'id_property' => 1,
												'id_goods' => 5,
												'value' => 'img_url:img_good_5_2.jpg'
											],
											[
												'id_property' => 1,
												'id_goods' => 5,
												'value' => 'img_url:img_good_5_3.jpg'
											],
											[
												'id_property' => 1,
												'id_goods' => 5,
												'value' => 'img_url:img_good_5_4.jpg'
											],
											[
												'id_property' => 2,
												'id_goods' => 5,
												'value' => 'BMW X5'
											],
											[
												'id_property' => 9,
												'id_goods' => 6,
												'value' => '120лс'
											],
											[
												'id_property' => 12,
												'id_goods' => 6,
												'value' => 'есть'
											],
											[
												'id_property' => 1,
												'id_goods' => 6,
												'value' => 'img_url:img_good_6_0.jpg'
											],
											[
												'id_property' => 2,
												'id_goods' => 6,
												'value' => 'Восход Д4'
											],
											[
												'id_property' => 9,
												'id_goods' => 7,
												'value' => '350лс'
											],
											[
												'id_property' => 12,
												'id_goods' => 7,
												'value' => 'Нет'
											],
											[
												'id_property' => 1,
												'id_goods' => 7,
												'value' => 'img_url:img_good_7_0.jpg'
											],
											[
												'id_property' => 2,
												'id_goods' => 7,
												'value' => 'Suzuki S800'
											]
									]);
    }
}
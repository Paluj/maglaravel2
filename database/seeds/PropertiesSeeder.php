<?php

use Illuminate\Database\Seeder;

class PropertiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		DB::table('properties')->insert([
											[
												'id' => 1,
												'name_property' => 'фото'  // обязательные свойства, есть у всех товаров
											],
											[
												'id' => 2,
												'name_property' => 'описание' // обязательные свойства, есть у всех товаров
											],
											[
												'id' => 3,
												'name_property' => 'Цена'
											],
											[
												'id' => 4,
												'name_property' => 'Сертификация'
											],
											[
												'id' => 5,
												'name_property' => 'Процессор'
											],
											[
												'id' => 6,
												'name_property' => 'ОЗУ'
											],
											[
												'id' => 7,
												'name_property' => 'версия ОС'
											],
											[
												'id' => 8,
												'name_property' => 'Тип матрицы'
											],
											[
												'id' => 9,
												'name_property' => 'Мощность двигателя'
											],
											[
												'id' => 10,
												'name_property' => 'Марка'
											],
											[	
												'id' => 11,
												'name_property' => 'Тип двигателя'
											],
											[
												'id' => 12,
												'name_property' => 'Наличие коляски'
											],
											[
												'id' => 13,
												'name_property' => 'Пол'
											],
											[
												'id' => 14,
												'name_property' => 'Состояние'
											],
											[
												'id' => 15,
												'name_property' => 'Дополнительно'
											],
											[
												'id' => 16,
												'name_property' => 'Размер экрана'
											],
											[
												'id' => 17,
												'name_property' => 'Вес'
											],
											[
												'id' => 18,
												'name_property' => 'Крепление к стене'
											]
										]);	
    }
}

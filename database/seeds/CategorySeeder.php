<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		DB::table('categorys')->insert([
											[
												'id' => 1,
												'name_category' => 'Электроника',
												'description_category' => 'Товары работающие от электричества',
												'path' => '1'
											],
											[
												'id' => 2,
												'name_category' => 'Смартфоны',
												'description_category' => 'Телефоны и планшеты',
												'path' => '1.1'
											],
											[
												'id' => 3,
												'name_category' => 'Android',
												'description_category' => 'Смартфоны на ОС Android',
												'path' => '1.1.1'
											],
											[
												'id' => 4,
												'name_category' => 'Apple',
												'description_category' => 'Техника Appleg',
												'path' => '1.1.2'
											],
											[
												'id' => 5,
												'name_category' => 'Телевизоры',
												'description_category' => 'Телевизор (телевизио́нный приёмник) (новолат. televisorium — дальновидец, от др.-греч. τῆλε — далеко и лат. video — видеть) — приёмник телевизионных сигналов изображения и звукового сопровождения, отображающий их на экране и с помощью громкоговорителей. Современный телевизор способен принимать телевизионные программы как с антенны, так и непосредственно от устройств их воспроизведения — например, видеомагнитофона, DVD-проигрывателя или медиаплеера.',
												'path' => '1.2'
											],
											[
												'id' => 6,
												'name_category' => 'Авто и Мото',
												'description_category' => 'Автомобили, мотоциклы и другая техника на колесах',
												'path' => '2'
											],
											[
												'id' => 7,
												'name_category' => 'Автомобили',
												'description_category' => 'Автомобили разных марок',
												'path' => '2.1'
											],
											[
												'id' => 8,
												'name_category' => 'Мото',
												'description_category' => 'Мототехника',
												'path' => '2.2'
											],
											[
												'id' => 9,
												'name_category' => 'Одежда',
												'description_category' => 'Одежда',
												'path' => '3'
											]										
										]);
		
/* 		Category::create([
							[
								'name_category' => 'категория 1',
								'description_category' => 'описание категории 1',
								'path' => '1'
							],
							[
								'name_category' => 'категория 2',
								'description_category' => 'описание категории 2',
								'path' => '2'
							],
							[
								'name_category' => 'категория 3',
								'description_category' => 'описание категории 3',
								'path' => '3'
							]							
						]);	 */	
    }
}

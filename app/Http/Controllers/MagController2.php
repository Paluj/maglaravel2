<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Category;
use App\PropCat;
use App\PropertyValues;
use App\Property;
use App\Good;

use DB;

class MagController2 extends Controller
{
    // Формируем объект для fancytree
	public function route_tree(Request $request) {
		$categorys = Category::orderBy('path','ASC')->get();
		$str_category_list = ""; // строка объекта fancytree
		$tmp_level = 0;	// уровень вложенной категории которая сейчас обрабатывается	
		foreach($categorys as $category) {
			$arr_path = explode(".", $category->path);
			$level = count($arr_path); // уровень вложения категории 
			$str_title = '{title: "'.$category->name_category.'", id_category: "'.$category->id.'", description_category: "'.$category->description_category.'",folder: true';
			if ($level == 1) {
				for ($i=0; $i<($tmp_level-$level); $i++) {
					$str_category_list .='}]';
				}		
				$str_category_list .= '},'.$str_title;
			} elseif ($level>$tmp_level) {
				$str_category_list .= ', children: ['.$str_title;
			} elseif ($tmp_level == $level) {
				$str_category_list .= '},'.$str_title;
			} else {
				for ($i=0; $i<($tmp_level-$level); $i++) {
					$str_category_list .='}]';
				}
				$str_category_list .= '},'.$str_title;
			}	
			$tmp_level = $level;				
		}
		$str_category_list .='}';
		for ($i=1; $i<$tmp_level; $i++)	$str_category_list .=']}';
		$str_category_list = substr($str_category_list , 2); // удаляем '},' из начала строки

		$array_to_view = [
							'str_category_list' => $str_category_list
						];

		return view('mag',$array_to_view);
	}
	
	// Запрос на список товаров в категориях
	public function route_post(Request $request) {
		$id_category = intval($request->input('id_category'));
		if ( $id_category > 0 ) {
			$goods = PropertyValues::select('goods.id', 'goods.name_goods', 'properties_values.value')
							->crossJoin('categorys as cat_2')
							->leftJoin('goods', 'properties_values.id_goods', 'goods.id')
							->leftJoin('categorys as cat_1', 'cat_1.id', 'goods.id_category')
							->where('cat_2.id',$id_category)
							->whereRaw('cat_1.path like CONCAT( cat_2.path, "%")')
							->where('properties_values.id_property', 1) // Картинки товара
							->groupBy('goods.id')
							->get()
							->toJson();
			return $goods;
		}
	}

	// Описание товара
	public function route_patch(Request $request) {	
		$id_goods = intval($request->input('id_goods'));
		if ( $id_goods > 0 ) {
			$arr_good = Property::join('properties_values','properties_values.id_property', '=', 'properties.id')
									->where('properties_values.id_goods',$id_goods)
									->get(['properties_values.value', 'properties.name_property'])
									->toJson();
			return $arr_good;
		}
	}
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Category;
use App\PropCat;
use App\PropertyValues;
use App\Property;
use App\Good;

use DB;
use File;

class MagControllerAdmin extends Controller
{
	public function route_tree(Request $request) {
		$categorys = Category::orderBy('path','ASC')->get();
		$str_category_list = ""; // строка объекта fancytree
		$tmp_level = 0;		
		$adm_add_catalog =  '{title: "Новая категория", id_category: "new_category"';
		foreach($categorys as $category) {
			$arr_path = explode(".", $category->path);
			$level = count($arr_path); // уровень вложения категории 
			$str_title = '{title: "'.$category->name_category.'", id_category: "'.$category->id.'", description_category: "'.$category->description_category.'",folder: true';
			if ($level == 1) {
				if ($str_category_list!="") $str_category_list .= ', children: ['.$adm_add_catalog.'}]';
					for ($i=0; $i<($tmp_level-$level); $i++) {
						$str_category_list .='}]';
					}		
					$str_category_list .= '},'.$str_title;
				} elseif ($level>$tmp_level) {
					$str_category_list .= ', children: ['.$adm_add_catalog."},".$str_title;
				} elseif ($tmp_level == $level) {
					$str_category_list .= ', children: ['.$adm_add_catalog.'}]},'.$str_title;
				} else {
					$str_category_list .= ', children: ['.$adm_add_catalog.'}]';
					for ($i=0; $i<($tmp_level-$level); $i++) {
						$str_category_list .='}]';
					}
					$str_category_list .= '},'.$str_title;
				}	
			$tmp_level = $level;	
		}
		$str_category_list .=', children: ['.$adm_add_catalog.'}]}';
		for ($i=1; $i<$tmp_level; $i++)	$str_category_list .=']}';
		$str_category_list = substr($str_category_list , 2).",".$adm_add_catalog."}";
		

		if ($tmp_level == 0) $str_category_list = '{title: "Новая категория", id_category: "new_category"}'; // если нет ни одной категории

		$array_to_view = [
							'str_category_list' => $str_category_list
						];

		return view('mag',$array_to_view);
	}
	
	// Описание товара + список внешних свойств
	public function route_patch(Request $request) {	
		if ( intval($request->input('id_goods')) > 0) { // описание товара
			$arr_good = Property::join('properties_values','properties_values.id_property', '=', 'properties.id')
									->where('properties_values.id_goods',intval($request->input('id_goods')))
									->get(['properties_values.value', 'properties.name_property'])->toJson();
			return $arr_good;
		} elseif($request->input('get_arr_property')=='ok') { // свойства внешних каталогов
			$properties = Property::select('properties.id','name_property')
									->join('prop_cat','prop_cat.id_property', '=', 'properties.id')
									->whereIn('id_category',$request->input('arr_id_category'))
									->get()->toJson();
			return $properties;	
		} 		
	}	

	// Добавить каталог/товар
	public function route_put(Request $request) {
		if ($request->input('add_new_category')=="ok" && $arr_input = $this->get_input_add_category($request)) $res = $this->add_category($request, $arr_input);
		elseif ($request->input('add_new_good')=="ok" && $arr_input = $this->get_input_add_good($request)) $res = $this->add_goods($request, $arr_input);
		if ($res) return $res;
		else return redirect()->route('MagControllerAdminGet')->with('status', 'Error PUT');
	}

	private function get_input_add_category($request){ // проверка переданных обязательных значений
		if (intval($request->input('id_parent_category'))>=0 && $request->input('new_name_category')!='' && $request->input('new_description_category')!='') 
			return $array = [
						"id_parent_category" => intval($request->input('id_parent_category')),
						"new_name_category" => $request->input('new_name_category'),
						"new_description_category" => $request->input('new_description_category'),
					];
		else return false;
	}
	
	private function get_input_add_good($request){ // проверка переданных обязательных значений
		if (intval($request->input('id_parent_category'))>=0 && $request->input('new_name_good')!='' && $request->input('new_description_good')!='') 
			return $array = [
						"id_parent_category" => intval($request->input('id_parent_category')),
						"new_name_good" => $request->input('new_name_good'),
						"new_description_good" => $request->input('new_description_good'),
					];
		else return false;
	}	
	
	private function add_category($request, $arr_input){
 		$str_path_parent = ""; // Путь нового каталога
		if ($arr_input['id_parent_category'] == 0) {
			$get_parent_path = Category::where('path', 'REGEXP', '^[0-9]*[^\.]$')->pluck('path')->toArray();
		} elseif ($arr_input['id_parent_category'] > 0) {
			$str_path_parent = Category::where('id', $arr_input['id_parent_category'])->value('path');
			$get_parent_path = Category::where('path', 'REGEXP', '^'.$str_path_parent.'.[0-9]*[^\.]$')->pluck('path')->toArray();
		}	
		$arr_path=[];
		foreach($get_parent_path as $new_path) {
			$arr = explode(".", $new_path);
			$arr_path[] = end($arr); // получение последнего номера каталога
		}
		asort($arr_path);
		$new_path = 1; // новый номер каталога.
		foreach ($arr_path as $value) { // Проверка на пропущенные номера. что бы новый номер был 1.3, а не 1.6 (в путях 1.1 - 1.2 - 1.5)
			if ($value != $new_path) break;
			$new_path++;			
		}		
		if ($str_path_parent != "") $new_path = $str_path_parent.".".$new_path;
  		$id_category = Category::insertGetId([
										'name_category' => $arr_input['new_name_category'],
										'description_category' => $arr_input['new_description_category'],
										'path' => $new_path
										]); 
		$arr_new_properties = [];
		$arr_list_propertys = array_diff($request->input('list_property_.*'), array('')); // Убираем из новых свойств, те, что без названия.
		foreach ($arr_list_propertys as $key=>$value) {
			$arr_new_properties[] = ['name_property' => $request->input("list_property_.{$key}")];
		}
		if (count($arr_new_properties) > 0) { 
			Property::insert($arr_new_properties);
			$new_id_properties = Property::whereIn('name_property', $arr_new_properties)->pluck('id')->toArray();
			$arr_new_propcat = [];
			foreach ($new_id_properties as $new_id_prop) {
				$arr_new_propcat[] = ['id_category'=>$id_category, 'id_property'=>$new_id_prop];
			}
			PropCat::insert($arr_new_propcat);
		}	  
		return redirect()->route('MagControllerAdminGet')->with('status', 'add_category ok');
	}

	private function add_goods($request, $arr_input){
  		$new_id_good = Good::insertGetId([
										'name_goods' => $arr_input['new_name_good'],
										'id_category' => $arr_input['id_parent_category']
										]);
		$arr_new_properties = []; 
		$arr_new_properties_value = [];
		if ($request->has('list_property_name_value_.*')) { // Личные свойства товара
			foreach ($request->input('list_property_name_value_.*') as $value) {
				if ($value[0] != '') {
					$arr_new_properties[] = ['name_property' => $value[0]];
					$arr_new_properties_value[] = [
													'id_property' => '',
													'id_goods' => $new_id_good,
													'value' => $value[1]
												];				
				}
			}
		}	
 	 	Property::insert($arr_new_properties);
		$new_id_properties = Property::whereIn('name_property', $arr_new_properties)->orderBy('id')->pluck('id')->toArray();
		for ($i=0;$i<count($new_id_properties); $i++) {
			$arr_new_properties_value[$i]['id_property'] = $new_id_properties[$i];
		}
		foreach($request->all() as $key=>$value) { // Наследуемые свойства
			$id_parent_property = substr($key,30);
			if ( (substr($key,0,30) == "list_parent_property_value_id_") &&  ($request->input('list_parent_property_value_id_'.$id_parent_property) != "") ) {
				$arr_new_properties_value[] = [
												'id_property' => $id_parent_property,
												'id_goods' => $new_id_good,
												'value' => $request->input('list_parent_property_value_id_'.$id_parent_property)
												];							
			} 
		}
 		if ($request->hasFile('new_img_good')) {
			$files = $request->file('new_img_good');  
			$n = 0;
			foreach($files as $file) {
				$name_file = "img_good_".$new_id_good."_".$n++.".jpg";
				$file->move("img", $name_file);
				$arr_new_properties_value[] = [
												'id_property' => 1, // ссылка на картинки
												'id_goods' => $new_id_good,
												'value' => "img_url:".$name_file
											];									
			}
		}  else {
			$arr_new_properties_value[] = [
											'id_property' => 1, // ссылка на картинки
											'id_goods' => $new_id_good,
											'value' => "img_url:no_image.jpg"
										];									
		} 	
		$arr_new_properties_value[] = [
										'id_property' => 2, // описание товара/категории
										'id_goods' => $new_id_good,
										'value' => $arr_input['new_description_good']
									];							
		PropertyValues::insert($arr_new_properties_value); 	 	
		
		return redirect()->route('MagControllerAdminGet')->with('status', 'add_goods ok'); 
	}	
	
	// Удалить товар/категорию
	public function route_delete(Request $request ) {
		if ($request->input('del_id_category') > 0) $res = $this->del_category($request);
		elseif ($request->input('del_id_good') > 0) $res = $this->del_goods($request);
		
		if ($res) return $res;
		else return redirect()->route('MagControllerAdminGet')->with('status', 'Error Delete');;		
	}
	
	private function del_category($request) {
		if (intval($request->input('del_id_category')) >= 0 ) {
			$id_category = intval($request->input('del_id_category'));
			// Все дочерние категории, включая $id_category
			$id_categories = Category::from('categorys as cat_1')
								->crossJoin('categorys as cat_2')
								->whereRaw('cat_1.path like CONCAT( cat_2.path, "%")')
								->where('cat_2.id', $id_category)
								->pluck('cat_1.id');

			$id_goods = Good::whereIn('id_category', $id_categories)
							->pluck('id');

			$arr_id_prop = PropCat::whereIn('id_category',$id_categories)
								->distinct()
								->pluck('id_property')
								->toArray();							
			
			$arr_id_prop = array_merge($arr_id_prop, $this->get_del_properties($id_goods));

 			Category::whereIn('id', $id_categories)->delete();
			Property::whereIn('id', $arr_id_prop)->delete();
			PropertyValues::whereIn('id_goods', $id_goods)->delete();
			PropCat::whereIn('id_property', $arr_id_prop)->delete();
			Good::whereIn('id_category', $id_categories)->delete();
			
			return redirect()->route('MagControllerAdminGet')->with('status', 'del_category ok'); 
		} else return false;
	}

	private function del_goods($request) {
		if (intval($request->input('del_id_good')) >= 0 ) {
			$id_good = intval($request->input('del_id_good'));
			$arr_id_prop = $this->get_del_properties($id_good);
			
			Good::where('id', $id_good)->delete();	
			Property::whereIn('id', $arr_id_prop)->delete();
			PropertyValues::where('id_goods', $id_good)->delete();	
			
			return redirect()->route('MagControllerAdminGet')->with('status', 'del_goods ok');
		} else return false;
	}

	// id свойств и значения товара, исключая те, свойства, что принадлежат категориям. + Удаление картинок.
	private function get_del_properties($id_goods) {
		if (is_int($id_goods)) $id_goods = [$id_goods];
		$id_properties = PropertyValues::select('properties_values.id_property', 'properties_values.value')
											->leftJoin('prop_cat', 'prop_cat.id_property', 'properties_values.id_property')
											->whereNull('prop_cat.id_property')
											->whereIn('properties_values.id_goods',$id_goods)
											->where('properties_values.id_property', '!=', '2') // 2 - описание товара/категории общее свойство
											->get();
		$arr_id_prop = [];											
 		foreach($id_properties as $id_property) {
			if ($id_property->id_property == 1) { // свойство отвечающие за картинки
				$file_name = substr($id_property->value,8);
				if ($file_name != 'no_image.jpg') File::delete('img/'.$file_name);
			} else $arr_id_prop[] = $id_property->id_property;
		}
		return $arr_id_prop;
	}
}

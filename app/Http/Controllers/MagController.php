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

class MagController extends Controller
{
    //
	public function show(Request $request) {
					if ($request->input('id_category')>0) {
						$id_category = $request->input('id_category')/1;
						$goods = DB::select("SELECT goods.id, goods.name_goods, properties_values.value from categorys t1, categorys t2, goods, properties_values WHERE t1.path like CONCAT( t2.path, '%') AND t2.id = {$id_category} AND t1.id=goods.id_category AND properties_values.id_property=1 AND properties_values.id_goods=goods.id group by goods.id");
						return response()->json($goods);
					} elseif ($request->input('id_goods')>0) {
					 	$id_goods = $request->input('id_goods')/1;
			
						$arr_good = DB::table('properties')->select('properties_values.value', 'properties.name_property')
									->join('properties_values','properties_values.id_property', '=', 'properties.id')
									->where('properties_values.id_goods',$id_goods)
									->get();
						return response()->json($arr_good);
					}
		if ($request->user()) {
			if ($request->user()['admin']) {
				if ($request->isMethod('post')) {
					if($request->input('get_arr_property')=='ok') {
						$properties = Property::select('properties.id','name_property')->join('prop_cat','prop_cat.id_property', '=', 'properties.id')->whereIn('id_category',$request->input('arr_id_category'))->get();
						return response()->json($properties);				
					} elseif ($request->input('add_new_category')=="ok") {
						$str_path_parent = "";
						if ($request->input('id_parent_category')==0) {
							$get_parent_path = Category::where('path', 'REGEXP', '^[0-9]*[^\.]$')->get();
						} else {
							$str_path_parent = Category::where('id', $request->input('id_parent_category'))->value('path');
							$get_parent_path = Category::where('path', 'REGEXP', '^'.$str_path_parent.'.[0-9]*[^\.]$')->get();
						}	
						$arr_path=[];
						foreach($get_parent_path as $new_path) {
							$arr=explode(".", $new_path->path);
							$arr_path[]=end($arr);
						}
						asort($arr_path);
						$new_path = 1;
						foreach ($arr_path as $value) {
							if ($value != $new_path) break;
							$new_path++;			
						}		
						if ($str_path_parent!="") {
							$new_path = $str_path_parent.".".$new_path;
						}
						
						$id_category = Category::insertGetId([
												'name_category' => $request->input('new_name_category'),
												'description_category' => $request->input('new_description_category'),
												'path' => $new_path
											]);
						$num_property=0;
						$n=0;
						$arr_new_properties = [];
						while ( $request->input('list_property_'.$num_property) ) {
							if ($request->input('list_property_'.$num_property)!='') {
									$arr_new_properties[] = ['name_property' => $request->input('list_property_'.$num_property)];
									$n++;
							}
							$num_property++;
						}
						if (count($arr_new_properties)>0) { 
							//$id_property = Property::insertGetId($arr_new_properties);
							Property::insert($arr_new_properties);
							$first_id_new = Property::max('id')-$n+1;
							$arr_new_propcat=[];
							for ($i=$first_id_new; $i<$first_id_new+$n; $i++) {
								$arr_new_propcat[] = ['id_category'=>$id_category, 'id_property'=>$i];
							}
							PropCat::insert($arr_new_propcat);
						}			 		
					} elseif ($request->input('add_new_good')=="ok") {
						$request->input('id_parent_category');
						$new_id_good = Good::insertGetId([
														'name_goods' => $request->input('new_name_good'),
														'id_category' => $request->input('id_parent_category')
														]);
						$num_property=0;
						$n=0;
						$arr_new_properties = [];
						$arr_new_properties_value = [];
						while ( $request->input('list_property_name_'.$num_property) ) {
							if ($request->input('list_property_name_'.$num_property)!='') {
								$arr_new_properties[] = ['name_property' => $request->input('list_property_name_'.$num_property)];
								$arr_new_properties_value[] = [
													'id_property' => '',
													'id_goods' => $new_id_good,
													'value' => $request->input('list_property_value_'.$num_property)
															];
								$n++;
							}
							$num_property++;  
						}
						Property::insert($arr_new_properties);
						$first_id_new = Property::max('id')-$n+1;
						for ($i=$first_id_new, $k=0; $i<$first_id_new+$n; $i++, $k++) {
								$arr_new_properties_value[$k]['id_property'] = $i;
						}
						
						foreach($request->all() as $key=>$value) {
							if ( (substr($key,0,30) == "list_parent_property_value_id_") &&  
								($request->input('list_parent_property_value_id_'.substr($key,30)) != "") ) {
								$arr_new_properties_value[] = [
													'id_property' => substr($key,30),
													'id_goods' => $new_id_good,
													'value' => $request->input('list_parent_property_value_id_'.substr($key,30))
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
														'id_property' => 1,
														'id_goods' => $new_id_good,
														'value' => "img_url:".$name_file
																];									
							}
							
						}  else {
							$arr_new_properties_value[] = [
													'id_property' => 1,
													'id_goods' => $new_id_good,
													'value' => "img_url:no_image.jpg"
															];									
						} 	
						$arr_new_properties_value[] = [
													'id_property' => 2,
													'id_goods' => $new_id_good,
													'value' => $request->input('new_description_good')
															];							
						PropertyValues::insert($arr_new_properties_value);
					} elseif ($request->input('del_id_category')>0) {
						
						$id_category = $request->input('del_id_category')/1;
						$id_categories = DB::select("SELECT t1.id from categorys t1, categorys t2 WHERE t1.path like CONCAT( t2.path, '%') AND t2.id = ".$id_category);
						
						$id_categories = json_decode(json_encode($id_categories), true);
						$arr_id_prop = [];
						$arr_id_cat = [];
						foreach($id_categories as $id_category) {
							$arr_id_cat[] = $id_category['id'];
						}	
						
						$id_properties = PropCat::select('id_property')->whereIn('id_category',$arr_id_cat)->distinct()->get();
						foreach($id_properties as $id_property) {
							$arr_id_prop[] = $id_property->id_property;
						}
						
						$id_goods = Good::select('id')
											->whereIn('id_category', $arr_id_cat)
											->get();
						$arr_id_good = [];
						foreach($id_goods as $id_good) {
							$arr_id_good[] = $id_good->id;
						}

						$id_properties=PropertyValues::select('id_property', 'value')
												->whereIn('id_goods',$arr_id_good)
												->get();
						foreach($id_properties as $id_property) {
							
							if (substr($id_property->value,0,8)=='img_url:') {
								$file_name = substr($id_property->value,8);
								if ($file_name != 'no_image.jpg') File::delete('img/'.$file_name);
							}
							if ($id_property->id_property > 2) $arr_id_prop[] = $id_property->id_property;
						}
						Category::whereIn('id', $arr_id_cat)->delete();
						Property::whereIn('id', $arr_id_prop)->delete();
						PropertyValues::whereIn('id_goods', $arr_id_good)->delete();
						PropCat::whereIn('id_property', $arr_id_prop)->delete();
						Good::whereIn('id_category', $arr_id_cat)->delete();
					} elseif ($request->input('del_id_good')>0) {
						$id_good = $request->input('del_id_good')/1;
						$id_properties=PropertyValues::select('id_property', 'value')
												->where('id_goods',$id_good)
												->get();
						$arr_id_prop = [];
						foreach($id_properties as $id_property) {
							if (substr($id_property->value,0,8)=='img_url:') {
								$file_name = substr($id_property->value,8);
								if ($file_name != 'no_image.jpg') File::delete('img/'.$file_name);
							}
							if ($id_property->id_property > 2) $arr_id_prop[] = $id_property->id_property;
						}
						$id_in_cats = PropCat::select('id_property')->whereIn('id_property',$arr_id_prop)->get();
						foreach($id_in_cats as $id_in_cat) {
							$k = array_search($id_in_cat->id_property, $arr_id_prop);
							unset($arr_id_prop[$k]);
						}
						Good::where('id', $id_good)->delete();	
						Property::whereIn('id', $arr_id_prop)->delete();
						PropertyValues::where('id_goods', $id_good)->delete();		
					}	
				}
				$categorys = Category::orderBy('path','ASC')->get();
				$str_category_list = "";
				$n = 0;				
				$adm_add_catalog =  '{title: "Новая категория", id_category: "new_category"';
				foreach($categorys as $category) {
					$arr_path = explode(".", $category->path);
					$level = count($arr_path);
					$str_title = '{title: "'.$category->name_category.'", id_category: "'.$category->id.'", description_category: "'.$category->description_category.'",folder: true';
					if ($level == 1) {
						if ($str_category_list!="") $str_category_list .= ', children: ['.$adm_add_catalog.'}]';
						for ($i=0; $i<($n-$level); $i++) {
							$str_category_list .='}]';
						}		
						$str_category_list .= '},'.$str_title;
					} elseif ($level>$n) {
						$str_category_list .= ', children: ['.$adm_add_catalog."},".$str_title;
					} elseif ($n == $level) {
						$str_category_list .= ', children: ['.$adm_add_catalog.'}]},'.$str_title;
					} else {
						$str_category_list .= ', children: ['.$adm_add_catalog.'}]';
						for ($i=0; $i<($n-$level); $i++) {
							$str_category_list .='}]';
						}
						$str_category_list .= '},'.$str_title;
					}	
					$n = $level;	
				}
				$str_category_list .=', children: ['.$adm_add_catalog.'}]}';
				for ($i=1; $i<$n; $i++)	$str_category_list .=']}';
				$str_category_list = substr($str_category_list , 2).",".$adm_add_catalog."}";
				
				$is_get_goods = 'adm_get_goods(node);';
			}
		}	
		else { 
			$categorys = Category::orderBy('path','ASC')->get();
			$str_category_list = "";
			$n = 0;				
			foreach($categorys as $category) {
				$arr_path = explode(".", $category->path);
				$level = count($arr_path);
				$str_title = '{title: "'.$category->name_category.'", id_category: "'.$category->id.'", description_category: "'.$category->description_category.'",folder: true';
				if ($level == 1) {
					for ($i=0; $i<($n-$level); $i++) {
						$str_category_list .='}]';
					}		
					$str_category_list .= '},'.$str_title;
				} elseif ($level>$n) {
					$str_category_list .= ', children: ['.$str_title;
				} elseif ($n == $level) {
					$str_category_list .= '},'.$str_title;
				} else {
					for ($i=0; $i<($n-$level); $i++) {
						$str_category_list .='}]';
					}
					$str_category_list .= '},'.$str_title;
				}	
				$n = $level;				
			}
			
			$str_category_list .='}';
			for ($i=1; $i<$n; $i++)	$str_category_list .=']}';
			$str_category_list = substr($str_category_list , 2);	 
			
			$is_get_goods = 'get_goods(node.data);';
		
			//echo("admin non");
		}
		//print_r($request->all());
		$array_to_view = [
							'str_category_list' => $str_category_list,
							'is_get_goods'		=> $is_get_goods
						];
		//echo($request->user()['admin']);
		return view('home',$array_to_view);
		
	}
}

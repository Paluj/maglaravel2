<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Good extends Model
{
    //
	public $timestamps = FALSE;	
	
	public function scopeAllGoodsJpg($query, $id_category)
	{
		return DB::select("SELECT goods.id, goods.name_goods, properties_values.value 
							from categorys t1, categorys t2, goods, properties_values 
							WHERE t1.path like CONCAT( t2.path, '%') 
									AND t2.id = {$id_category} 
									AND t1.id=goods.id_category 
									AND properties_values.id_property=1 
									AND properties_values.id_goods=goods.id 
							group by goods.id");
	}	
}

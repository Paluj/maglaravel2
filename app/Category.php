<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Category extends Model
{
    //
	protected $table = 'categorys';
	public $timestamps = FALSE;

	public function scopeArrChildCat($query, $id_category)
	{
		//return DB::select("SELECT t1.id from categorys t1, categorys t2 WHERE t1.path like CONCAT( t2.path, '%') AND t2.id =".$id_category);
		
		return DB::table('categorys as t1')
					->select('t1.id')
					->crossJoin('categorys as t2')
					->whereRaw('t1.path like CONCAT( t2.path, "%")')
					->where('t2.id', $id_category)
					->get();
					
	}		
	
}

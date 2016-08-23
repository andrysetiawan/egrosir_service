<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
* 
*/
class Pasar extends Model
{
	
	protected $table = 'tb_pasar';
	public function admins()
	{
		return $this->hasMany('App\Models\Admin');
	}

}
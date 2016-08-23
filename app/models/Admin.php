<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
* 
*/
class Admin extends Model
{
	
	protected $table = 'tb_admin';
	public function pasar()
    {
    	return $this->belongsTo('App\Models\Pasar','id_pasar');
    }

}
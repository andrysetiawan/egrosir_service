<?php

namespace App\Controllers;


use App\Models\User;
/**
* 
*/
class user_controller
{
	public function insert($request, $response)
	{

	}

	public function get_all($request, $response)
	{
		$tb_user=User::get();
		$tamp = '{"users" :'.$tb_user.'}';
		$body = $response->getBody();
		    $body->write($tamp);
        	return $response->withHeader(
		        'Content-Type',
		        'application/json'
		    )->withStatus(404)->withBody($body);        	

	}
	public function get_by_id($request, $response)
	{

	}
	public function update($request, $response)
	{

	}
	public function delete($request, $response)
	{

	}
	

}
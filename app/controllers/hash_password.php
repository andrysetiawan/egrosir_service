<?php 
namespace App\Controllers;

/**
* 
*/
class hash_password
{
	public function pass_hash($password)
	{
		$options = [
		    'salt' => md5('egrosir_service_app'), //write your own code to generate a suitable salt
		    'cost' => 12 // the default cost is 10
		];
		$hash = password_hash($password, PASSWORD_DEFAULT, $options);

	}

}
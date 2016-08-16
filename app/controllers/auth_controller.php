<?php
namespace App\Controllers;


use App\Models\User;
use App\Controllers\controller;
use Respect\Validation\Validator as v;

class auth_controller extends controller
{

	public function sign_in($request,$response)
	{
		$validation = $this->validator->validate($request,[
	        'email' => v::noWhitespace()->notEmpty(),
	        'password' => v::noWhitespace()->notEmpty(),
		]);
		if($validation->failed())
		{
			$body = $response->getBody();
		    $body->write('{"status": "error","message": "validation failed","error":'.$validation->msg().'}');
        	return $response->withHeader(
		        'Content-Type',
		        'application/json'
		    )->withStatus(422)->withBody($body);
		}
		else
		{

		}



	}
	public function sign_up($request,$response)
	{
		$validation = $this->validator->validate($request,[
			'username' => v::noWhitespace()->notEmpty(),
	        'nama' => v::notEmpty()->alpha(),
	        'email' => v::noWhitespace()->notEmpty(),
	        'nik' => v::noWhitespace()->notEmpty(),
	        'alamat' => v::notEmpty(),
	        'kelurahan' => v::notEmpty()->alpha(),
	        'kecamatan' => v::notEmpty()->alpha(),
	        'kabupaten_kota' => v::notEmpty()->alpha(),
	        'propinsi' => v::notEmpty()->alpha(),
	        'kelamin' => v::noWhitespace()->notEmpty(),
	        'hp' => v::noWhitespace()->notEmpty(),
	        'password' => v::noWhitespace()->notEmpty(),
		]);
		

		if($validation->failed())
		{
			$body = $response->getBody();
		    $body->write('{"status": "error","message": "validation failed","error":'.$validation->msg().'}');
        	return $response->withHeader(
		        'Content-Type',
		        'application/json'
		    )->withStatus(422)->withBody($body);
		}
		else
		{
			$user_username=User::select('username')
                   	->where('username', $request->getParam('username'))
                   	->first();
        
	        if(!empty($user_username))
	        {
	        	$body = $response->getBody();
			    $body->write('{"status": "error","message": "username is already taken"}');
	        	return $response->withHeader(
			        'Content-Type',
			        'application/json'
			    )->withStatus(404)->withBody($body);
	        }
	        else
	        {
	        	$user_email=User::select('email')
	                   	->where('email', $request->getParam('email'))
	                   	->first();

	        	if(!empty($user_email))
	        	{
	            	$body = $response->getBody();
				    $body->write('{"status": "error","message": "email is already taken"}');
		        	return $response->withHeader(
				        'Content-Type',
				        'application/json'
				    )->withStatus(404)->withBody($body);


	        	}
	        	else
	        	{
	        		$user = new User;
			        $user->username = $request->getParam('username');
			        $user->nama = $request->getParam('nama');
			        $user->email = $request->getParam('email');
			        $user->nik = $request->getParam('nik');
			        $user->alamat = $request->getParam('alamat');
			        $user->kelurahan = $request->getParam('kelurahan');
			        $user->kecamatan = $request->getParam('kecamatan');
			        $user->kabupaten_kota = $request->getParam('kabupaten_kota');
			        $user->propinsi = $request->getParam('propinsi');
			        $user->kelamin = $request->getParam('kelamin');
			        $user->hp = $request->getParam('hp');
			        $user->password = $request->getParam('password');
			        
			        if($user->save()) {
			        	$body = $response->getBody();
					    $body->write('{"status": "success","message":"you are successfully registered"}');
			        	return $response->withHeader(
					        'Content-Type',
					        'application/json'
					    )->withStatus(202)->withBody($body);
			            
			        } else {
			        	$body = $response->getBody();
					    $body->write('{"status": "error","message":"failed to store data"}');
			        	return $response->withHeader(
					        'Content-Type',
					        'application/json'
					    )->withStatus(422)->withBody($body);
			            
					}
		        	

	        	}

	        }

		}
		

		
	}
	public function sign_out($request,$response)
	{

	}

}
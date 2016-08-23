<?php

namespace App\Controllers;

use App\Models\User;
use App\Controllers\controller;
use Respect\Validation\Validator as v;
/**
* 
*/
class user_controller extends controller
{
	public function insert($request, $response)
	{
		if (false === $this->token->hasScope(["admin"])) {
			$tamp = '{"status": "error","message":"token not allowed to insert data"}';			
			$body = $response->getBody();
		    $body->write($tamp);
        	return $response->withHeader(
		        'Content-Type',
		        'application/json'
		    )->withStatus(403)->withBody($body);

		}
		else
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
		        'verified' => v::noWhitespace()->notEmpty()->alpha(),
		        
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
	        
		        if(count($user_username)>0)
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

		        	if(count($user_email)>0)
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
		        		$pass = $this->hash_password->hash($request->getParam('password'));
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
				        $user->password = $pass;
				        $user->verified = $request->getParam('verified');
				        $user->foto = $request->getParam('foto');
				        
				        if($user->save()) {
				        	$body = $response->getBody();
						    $body->write('{"status": "success","message":"Successfully store data"}');
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
		
	}

	public function get_all($request, $response)
	{	
		
		if (false === $this->token->hasScope(["admin"])) {
			$tamp = '{"status": "error","message":"token not allowed to get data"}';			
			$body = $response->getBody();
		    $body->write($tamp);
        	return $response->withHeader(
		        'Content-Type',
		        'application/json'
		    )->withStatus(403)->withBody($body);
		}
		else
		{
			//with pagination
			$tb_user=User::select('username','nama','email','nik','alamat','kelurahan','kecamatan','kabupaten_kota','propinsi','kelamin','hp','foto','verified')
				->paginate(10);
			$user_json=$tb_user->toJson();
			//without pagination
			//$tb_user=User::get();

			if(count($tb_user)>0)
			{
				$tamp = '{"status": "success","message":"successfully get data","output":'.$user_json.'}';
				$body = $response->getBody();
			    $body->write($tamp);
	        	return $response->withHeader(
			        'Content-Type',
			        'application/json'
			    )->withStatus(202)->withBody($body);

			}
			else
			{
				$tamp = '{"status": "error","message":"data not found"}';			
				$body = $response->getBody();
			    $body->write($tamp);
	        	return $response->withHeader(
			        'Content-Type',
			        'application/json'
			    )->withStatus(404)->withBody($body);

			}        	

		}
		
	}
	public function get_by_id($request, $response, $args)
	{
		if (false === $this->token->hasScope(["user", "admin"])) {
			$tamp = '{"status": "error","message":"token not allowed to get data"}';			
			$body = $response->getBody();
		    $body->write($tamp);
        	return $response->withHeader(
		        'Content-Type',
		        'application/json'
		    )->withStatus(403)->withBody($body);
		}
		else
		{
			$tb_user=User::where('id', $args['id'])
				->select('username','nama','email','nik','alamat','kelurahan','kecamatan','kabupaten_kota','propinsi','kelamin','hp','foto','verified')
              	->first();

			if(count($tb_user)>0)
			{
				$tamp = '{"status": "success","message":"successfully get data","output":'.$tb_user.'}';
				$body = $response->getBody();
			    $body->write($tamp);
		    	return $response->withHeader(
			        'Content-Type',
			        'application/json'
			    )->withStatus(202)->withBody($body);

			}
			else
			{
				$tamp = '{"status": "error","message":"data not found"}';
				$body = $response->getBody();
			    $body->write($tamp);
		    	return $response->withHeader(
			        'Content-Type',
			        'application/json'
			    )->withStatus(404)->withBody($body);

			}
			
		}
	}
	public function update($request, $response, $args)
	{
		if (false === $this->token->hasScope(["user", "admin"])) {
			$tamp = '{"status": "error","message":"token not allowed to update data"}';			
			$body = $response->getBody();
		    $body->write($tamp);
        	return $response->withHeader(
		        'Content-Type',
		        'application/json'
		    )->withStatus(403)->withBody($body);
		}
		else
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
				$user=User::where('id', $args['id'])
	        		->first();
		        if(count($user)>0)
		        {
		        	$pass = $this->hash_password->hash($request->getParam('password'));
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
			        $user->password = $pass;
			        $user->verified = $request->getParam('verified');
			        $user->foto = $request->getParam('foto');
			        if($user->save()) {
			        	$body = $response->getBody();
					    $body->write('{"status": "success","message":"successfully update data"}');
			        	return $response->withHeader(
					        'Content-Type',
					        'application/json'
					    )->withStatus(202)->withBody($body);
			            
			        } else {
			        	$body = $response->getBody();
					    $body->write('{"status": "error","message":"failed to update data"}');
			        	return $response->withHeader(
					        'Content-Type',
					        'application/json'
					    )->withStatus(422)->withBody($body);
			            
					}

		        }
		        else
				{
					$tamp = '{"status": "error","message":"data not found"}';
					$body = $response->getBody();
				    $body->write($tamp);
		        	return $response->withHeader(
				        'Content-Type',
				        'application/json'
				    )->withStatus(404)->withBody($body);

				}
			}

		}
		
	}
	public function delete($request, $response,$args)
	{
		if (false === $this->token->hasScope(["admin"])) {
			$tamp = '{"status": "error","message":"token not allowed to delete data"}';			
			$body = $response->getBody();
		    $body->write($tamp);
        	return $response->withHeader(
		        'Content-Type',
		        'application/json'
		    )->withStatus(403)->withBody($body);

		}
		else
		{
			$user=User::where('id', $args['id'])
	            ->first();
	        if(count($user)>0)
	        {
	        	if($user->delete()) {
		        	$body = $response->getBody();
				    $body->write('{"status": "success","message":"successfully delete data"}');
		        	return $response->withHeader(
				        'Content-Type',
				        'application/json'
				    )->withStatus(202)->withBody($body);
		            
		        } else {
		        	$body = $response->getBody();
				    $body->write('{"status": "error","message":"failed to delete data"}');
		        	return $response->withHeader(
				        'Content-Type',
				        'application/json'
				    )->withStatus(422)->withBody($body);
		            
				}

	        }
	        else
			{
				$tamp = '{"status": "error","message":"data not found"}';
				$body = $response->getBody();
			    $body->write($tamp);
	        	return $response->withHeader(
			        'Content-Type',
			        'application/json'
			    )->withStatus(404)->withBody($body);

			}

		}
	}
	

}
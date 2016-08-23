<?php

namespace App\Controllers;

use App\Models\Admin;
use App\Controllers\controller;
use Respect\Validation\Validator as v;
/**
* 
*/
class admin_controller extends controller
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
				'id_pasar' => v::noWhitespace()->notEmpty(),
				'username' => v::noWhitespace()->notEmpty(),
		        'nama' => v::notEmpty()->alpha(),
		        'email' => v::noWhitespace()->notEmpty(),
		        'nik' => v::noWhitespace()->notEmpty(),
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
				
				$user_username=Admin::select('username')
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
		        	$user_email=Admin::select('email')
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
		        		$admin = new Admin;
				        $admin->id_pasar = $request->getParam('id_pasar');
		        		$admin->username = $request->getParam('username');
				        $admin->nama = $request->getParam('nama');
				        $admin->email = $request->getParam('email');
				        $admin->password = $pass;
				        $admin->foto = $request->getParam('foto');
				        $admin->nik = $request->getParam('nik');
				        
				        if($admin->save()) {
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
			//with join table and pagination
			$tb_admin=Admin::select('username','nama','email','nik','foto','id_pasar')
				->with(array('pasar'=>function($query)
					{
	        			$query->select('id','nama_pasar','alamat','gambar');
	    			}))
				->paginate(10);
			$admin_json = $tb_admin->toJSON();
			//without pagination
			//$tb_admin=Admin::with('pasar')->get();
			//print_r($tb_admin);

			if(count($tb_admin)>0)
			{
				$tamp = '{"status": "success","message":"successfully get data","output":'.$admin_json.'}';
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
			$tb_admin=Admin::select('username','nama','email','nik','foto','id_pasar')
				->where('id', $args['id'])
				->with(array('pasar'=>function($query)
					{
	        			$query->select('id','nama_pasar','alamat','gambar');
	    			}))
              	->first();
			if(count($tb_admin)>0)
			{
				$tamp = '{"status": "success","message":"successfully get data","output":'.$tb_admin.'}';
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
		if (false === $this->token->hasScope(["admin"])) {
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
				'id_pasar' => v::noWhitespace()->notEmpty(),
				'username' => v::noWhitespace()->notEmpty(),
		        'nama' => v::notEmpty()->alpha(),
		        'email' => v::noWhitespace()->notEmpty(),
		        'nik' => v::noWhitespace()->notEmpty(),
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

				$admin=Admin::where('id', $args['id'])
	        	->first();
		        if(count($admin)>0)
		        {
		        	$pass = $this->hash_password->hash($request->getParam('password'));
		        	$admin->id_pasar = $request->getParam('id_pasar');
	        		$admin->username = $request->getParam('username');
			        $admin->nama = $request->getParam('nama');
			        $admin->email = $request->getParam('email');
			        $admin->password = $pass;
			        $admin->foto = $request->getParam('foto');
			        $admin->nik = $request->getParam('nik');
			        if($admin->save()) {
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
			$admin=Admin::where('id', $args['id'])
	            ->first();
	        if(count($admin)>0)
	        {
	        	if($admin->delete()) {
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
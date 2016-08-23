<?php
namespace App\Controllers;

use App\Models\Admin;
use App\Controllers\controller;
use Respect\Validation\Validator as v;
use Firebase\JWT\JWT;
use Tuupola\Base62;

class admin_auth_controller extends controller
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
			$requested_scopes = $request->getParam('scope_api');
			$req_api[] = $requested_scopes;
			$valid_scopes = [
		        "admin",
		        "user",
		        "public",
		        "manager"
		    ];

			$scopes = array_filter($req_api, function ($needle) use ($valid_scopes) {
				return in_array($needle, $valid_scopes);
			});
			// $now = new DateTime();
			// $future = new DateTime("now +2 hours");
			$server = $request->getServerParams();
            $jti = Base62::encode(random_bytes(16));
            $payload = [
                // "iat" => $now->getTimeStamp(),
                // "exp" => $future->getTimeStamp(),
                "jti" => $jti,
                "sub" => $server["PHP_AUTH_USER"],
                "scope" => $scopes
            ];
            $secret = "hatepnganuikihihamburadul";
            $token = JWT::encode($payload, $secret, "HS256");
			$admin=Admin::select('username','password','nama','email','nik','foto','id_pasar')
				->where('email', $request->getParam('email'))
				->with(array('pasar'=>function($query)
					{
	        			$query->select('id','nama_pasar','alamat','gambar');
	    			}))
                ->first();
            if($this->hash_password->check_password($admin->password,$request->getParam('password')))
            {
            	$body = $response->getBody();
			    $body->write('{"status": "success","message": "Login success","token":"'.$token.'","data":'.$admin.'}');
		    	return $response->withHeader(
			        'Content-Type',
			        'application/json'
			    )->withStatus(202)->withBody($body);


            }
            else
            {
            	$body = $response->getBody();
			    $body->write('{"status": "error","message": "Login failed. Incorrect credentials"}');
		    	return $response->withHeader(
			        'Content-Type',
			        'application/json'
			    )->withStatus(401)->withBody($body);

            }


		}



	}
	public function sign_up($request,$response)
	{
		$validation = $this->validator->validate($request,[
			'id_pasar' => v::noWhitespace()->notEmpty(),
			'username' => v::noWhitespace()->notEmpty(),
	        'nama' => v::notEmpty()->alpha(),
	        'email' => v::noWhitespace()->notEmpty(),
	        'password' => v::noWhitespace()->notEmpty(),
	        'nik' => v::noWhitespace()->notEmpty(),
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


}
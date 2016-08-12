<?php
namespace App\Controllers;


use App\Models\User;

class auth_controller
{

	public function sign_in($request,$response)
	{

	}
	public function sign_up($request,$response)
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
			    $body->write('{"status": "error","message": "username is already taken"}');
	        	return $response->withHeader(
			        'Content-Type',
			        'application/json'
			    )->withStatus(404)->withBody($body);


        	}
        	else
        	{
        		$user = new User;
		        $user->username = $request->getParam();
		        $user->nama = $request->getParam();
		        $user->email = $request->getParam();
		        $user->nik = $request->getParam();
		        $user->alamat = $request->getParam();
		        $user->kelurahan = $request->getParam();
		        $user->kecamatan = $request->getParam();
		        $user->kabupaten_kota = $request->getParam();
		        $user->propinsi = $request->getParam();
		        $user->kelamin = $request->getParam();
		        $user->hp = $request->getParam();
		        $user->password = $request->getParam();
		        
		        if($user->save()) {
		        	$body = $response->getBody();
				    $body->write('{"status": "success","message":"data had been stored"}');
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
				    )->withStatus(404)->withBody($body);
		            
				}
	        	

        	}

        }

		
	}

}
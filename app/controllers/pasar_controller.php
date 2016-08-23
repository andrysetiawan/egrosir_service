<?php
namespace App\Controllers;

use App\Models\Pasar;
use App\Controllers\controller;
use Respect\Validation\Validator as v;
/**
* 
*/
class pasar_controller extends controller
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
				'nama_pasar' => v::notEmpty(),
		        'alamat' => v::notEmpty(),
		        'gambar' => v::notEmpty(),
		        
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
				
	       		$pasar = new Pasar;
		        $pasar->nama_pasar = $request->getParam('nama_pasar');
		        $pasar->alamat = $request->getParam('alamat');
		        $pasar->gambar = $request->getParam('gambar');
		        
		        if($pasar->save()) {
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

	public function get_all($request, $response)
	{	
		//with pagination
		$tb_pasar=Pasar::select('nama_pasar','alamat','gambar')
				->paginate(10);
		$pasar_json = $tb_pasar->toJson();
		//without pagination
		//$tb_pasar=Pasar::get();

		if(count($tb_pasar)>0)
		{
			$tamp = '{"status": "success","message":"successfully get data","output":'.$pasar_json.'}';
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
	public function get_by_id($request, $response, $args)
	{
		$tb_pasar=Pasar::where('id', $args['id'])
              	->first();

		if(count($tb_pasar)>0)
		{
			$tamp = '{"status": "success","message":"successfully get data","data":'.$tb_pasar.'}';
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
				'nama_pasar' => v::notEmpty(),
		        'alamat' => v::notEmpty(),
		        'gambar' => v::notEmpty(),
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
				$pasar=Pasar::where('id', $args['id'])
	        		->first();
		        if(count($pasar)>0)
		        {
		        	$pasar->nama_pasar = $request->getParam('nama_pasar');
			        $pasar->alamat = $request->getParam('alamat');
			        $pasar->gambar = $request->getParam('gambar');
			        if($pasar->save()) {
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
			$pasar=Pasar::where('id', $args['id'])
	            ->first();
	        if(count($pasar)>0)
	        {
	        	if($pasar->delete()) {
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
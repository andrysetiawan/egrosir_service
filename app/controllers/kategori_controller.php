<?php
namespace App\Controllers;

use App\Models\Kategori;
use App\Controllers\controller;
use Respect\Validation\Validator as v;
/**
* 
*/
class kategori_controller extends controller
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
				'nama_kategori' => v::notEmpty(),
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
				
	       		$kategori = new Kategori;
		        $kategori->nama_kategori = $request->getParam('nama_kategori');
		        $kategori->gambar = $request->getParam('gambar');
		        
		        if($kategori->save()) {
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
		$tb_kategori=Kategori::select('nama_kategori','gambar')
				->paginate(10);
		$kategori_json = $tb_kategori->toJSON();
		//without pagination
		//$tb_kategori=Kategori::get();

		if(count($tb_kategori)>0)
		{
			$tamp = '{"status": "success","message":"successfully get data","output":'.$kategori_json.'}';
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
		$tb_kategori=Kategori::where('id', $args['id'])
              	->first();

		if(count($tb_kategori)>0)
		{
			$tamp = '{"status": "success","message":"successfully get data","data":'.$tb_kategori.'}';
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
				'nama_kategori' => v::notEmpty(),
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
				$kategori=Kategori::where('id', $args['id'])
	        		->first();
		        if(count($kategori)>0)
		        {
		        	$kategori->nama_kategori = $request->getParam('nama_kategori');
			        $kategori->gambar = $request->getParam('gambar');
			        if($kategori->save()) {
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
			$kategori=Kategori::where('id', $args['id'])
	            ->first();
	        if(count($kategori)>0)
	        {
	        	if($kategori->delete()) {
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
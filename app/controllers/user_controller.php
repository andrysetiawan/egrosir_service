<?php

namespace App\Controllers;

use App\Models\User;
use App\Controllers\controller;
use Respect\Validation\Validator as v;

/**
 * 
 */
class user_controller extends controller {

    public function insert($request, $response) {
        $validation = $this->validator->validate($request, [
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

        if ($validation->failed()) {
            $body = $response->getBody();
            $body->write('{"status": "error","message": "validation failed","error":' . $validation->msg() . '}');
            return $response->withHeader(
                            'Content-Type', 'application/json'
                    )->withStatus(422)->withBody($body);
        } else {
            $user_username = User::select('username')
                    ->where('username', $request->getParam('username'))
                    ->first();

            if (count($user_username) > 0) {
                $body = $response->getBody();
                $body->write('{"status": "error","message": "username is already taken"}');
                return $response->withHeader(
                                'Content-Type', 'application/json'
                        )->withStatus(404)->withBody($body);
            } else {
                $user_email = User::select('email')
                        ->where('email', $request->getParam('email'))
                        ->first();

                if (count($user_email) > 0) {
                    $body = $response->getBody();
                    $body->write('{"status": "error","message": "email is already taken"}');
                    return $response->withHeader(
                                    'Content-Type', 'application/json'
                            )->withStatus(404)->withBody($body);
                } else {
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

                    if ($user->save()) {
                        $body = $response->getBody();
                        $body->write('{"status": "success","message":"Successfully store data"}');
                        return $response->withHeader(
                                        'Content-Type', 'application/json'
                                )->withStatus(202)->withBody($body);
                    } else {
                        $body = $response->getBody();
                        $body->write('{"status": "error","message":"failed to store data"}');
                        return $response->withHeader(
                                        'Content-Type', 'application/json'
                                )->withStatus(422)->withBody($body);
                    }
                }
            }
        }
    }

    public function get_all($request, $response) {

        $tb_user = User::get();
        if (!empty($tb_user)) {
            $tamp = '{"status": "success","data":' . $tb_user . ',"message":"successfully get dataxx"}';
            $body = $response->getBody();
            $body->write($tamp);
            return $response->withHeader(
                            'Content-Type', 'application/json'
                    )->withStatus(202)->withBody($body);
        } else {
            $tamp = '{"status": "error","message":"data not found"}';
            $body = $response->getBody();
            $body->write($tamp);
            return $response->withHeader(
                            'Content-Type', 'application/json'
                    )->withStatus(404)->withBody($body);
        }
    }

    public function get_by_id($request, $response, $args) {
        $tb_user = User::where('id', $args['id'])
                ->first();

        if (!empty($tb_user)) {
            $tamp = '{"status": "success","data":' . $tb_user . ',"message":"successfully get data"}';
            $body = $response->getBody();
            $body->write($tamp);
            return $response->withHeader(
                            'Content-Type', 'application/json'
                    )->withStatus(202)->withBody($body);
        } else {
            $tamp = '{"status": "error","message":"data not found"}';
            $body = $response->getBody();
            $body->write($tamp);
            return $response->withHeader(
                            'Content-Type', 'application/json'
                    )->withStatus(404)->withBody($body);
        }
    }

    public function update($request, $response, $args) {
        $validation = $this->validator->validate($request, [
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

        if ($validation->failed()) {
            $body = $response->getBody();
            $body->write('{"status": "error","message": "validation failed","error":' . $validation->msg() . '}');
            return $response->withHeader(
                            'Content-Type', 'application/json'
                    )->withStatus(422)->withBody($body);
        } else {
            $user = User::where('id', $args['id'])
                    ->first();
            if (!empty($user)) {
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
                if ($user->save()) {
                    $body = $response->getBody();
                    $body->write('{"status": "success","message":"successfully update data"}');
                    return $response->withHeader(
                                    'Content-Type', 'application/json'
                            )->withStatus(202)->withBody($body);
                } else {
                    $body = $response->getBody();
                    $body->write('{"status": "error","message":"failed to update data"}');
                    return $response->withHeader(
                                    'Content-Type', 'application/json'
                            )->withStatus(422)->withBody($body);
                }
            } else {
                $tamp = '{"status": "error","message":"data not found"}';
                $body = $response->getBody();
                $body->write($tamp);
                return $response->withHeader(
                                'Content-Type', 'application/json'
                        )->withStatus(404)->withBody($body);
            }
        }
    }

    public function delete($request, $response, $args) {
        $user = User::where('id', $args['id'])
                ->first();
        if (!empty($user)) {
            if ($user->delete()) {
                $body = $response->getBody();
                $body->write('{"status": "success","message":"successfully delete data"}');
                return $response->withHeader(
                                'Content-Type', 'application/json'
                        )->withStatus(202)->withBody($body);
            } else {
                $body = $response->getBody();
                $body->write('{"status": "error","message":"failed to delete data"}');
                return $response->withHeader(
                                'Content-Type', 'application/json'
                        )->withStatus(422)->withBody($body);
            }
        } else {
            $tamp = '{"status": "error","message":"data not found"}';
            $body = $response->getBody();
            $body->write($tamp);
            return $response->withHeader(
                            'Content-Type', 'application/json'
                    )->withStatus(404)->withBody($body);
        }
    }

}

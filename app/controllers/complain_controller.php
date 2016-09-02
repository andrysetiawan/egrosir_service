<?php

namespace App\Controllers;

use App\Controllers\controller;
use App\Models\Complain;
use Respect\Validation\Validator as v;

/**
 * Description of complain_controller
 *
 * @author Ervan
 */
class complain_controller extends controller {

    public function insert($req, $res) {
        if (false === $this->token->hasScope(['admin', 'user'])) {
            $tamp = '{"status": "error","message":"token not allowed to insert data"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(403)->withBody($body);
        }
        $validator = $this->validator->validate($req, array(
            'id_transaksi' => v::noWhiteSpace()->notEmpty(),
            'id_user' => v::noWhitespace()->notEmpty(),
            'pesan' => v::notEmpty(),
        ));

        if ($validator->failed()) {
            $body = $res->getBody();
            $body->write('{"is_error":"true", "status": "error","message": "validation failed","error":' . $validator->msg() . '}');
            return $res->withHeader('Content-Type', 'application/json')->withStatus(422)->withBody($body);
        } else {

            $complain = new Complain();
            $complain->id_transaksi = $req->getParam('id_transaksi');
            $complain->id_user = $req->getParam('id_user');
            $complain->pesan = $req->getParam('pesan');

            if ($complain->save()) {
                $body = $res->getBody();
                $body->write('{"status": "success","message":"Successfully store data", "is_error": "false"}');
                return $res->withHeader('Content-Type', 'application/json')->withStatus(202)->withBody($body);
            } else {
                $body = $res->getBody();
                $body->write('{"status": "error","message":"failed to store data", "is_error": "true"}');
                return $res->withHeader('Content-Type', 'application/json')->withStatus(422)->withBody($body);
            }
        }
    }
    
    public function get_all($req, $res){
        if (false === $this->token->hasScope(['admin'])) {
            $tamp = '{"status": "error","message":"token not allowed to insert data"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(403)->withBody($body);
        }
        $complain = Complain::join('tb_user AS tu', 'tb_komplain.id_user', '=', 'tu.id')
                ->join('tb_transaksi AS tt', 'tb_komplain.id_transaksi', '=', 'tt.id')
                ->select('tb_komplain.*', 'tu.nama AS nama_user', 'tu.email AS email_user', 'tu.nik AS nik_user', 'tu.alamat AS alamat_user', 'tu.hp AS hp_user', "tt.id_cart")
                ->paginate(10);
        $json_complain = $complain->toJson();

        if (count($complain) > 0) {
            $tamp = '{"status": "success","data":' . $json_complain . ',"message":"successfully get data", "is_error":"false"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(202)->withBody($body);
        } else {
            $tamp = '{"status": "error","message":"data not found", "is_error":"true"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(404)->withBody($body);
        }
    }
    
    public function get_by_id($req, $res, $args){
        if (false === $this->token->hasScope(['admin', 'user'])) {
            $tamp = '{"status": "error","message":"token not allowed to insert data"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(403)->withBody($body);
        }
        $complain = Complain::join('tb_user AS tu', 'tb_komplain.id_user', '=', 'tu.id')
                ->join('tb_transaksi AS tt', 'tb_komplain.id_transaksi', '=', 'tt.id')
                ->select('tb_komplain.*', 'tu.nama AS nama_user', 'tu.email AS email_user', 'tu.nik AS nik_user', 'tu.alamat AS alamat_user', 'tu.hp AS hp_user', "tt.id_cart")
                ->where("tb_komplain.id", $args['id'])
                ->first();

        if (count($complain) > 0) {
            $tamp = '{"status": "success","data":' . $complain . ',"message":"successfully get data", "is_error":"false"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(202)->withBody($body);
        } else {
            $tamp = '{"status": "error","message":"data not found", "is_error":"true"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(404)->withBody($body);
        }
    }
    
    public function get_by_user($req, $res, $args){
        if (false === $this->token->hasScope(['admin', 'user'])) {
            $tamp = '{"status": "error","message":"token not allowed to insert data"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(403)->withBody($body);
        }
        $complain = Complain::join('tb_user AS tu', 'tb_komplain.id_user', '=', 'tu.id')
                ->join('tb_transaksi AS tt', 'tb_komplain.id_transaksi', '=', 'tt.id')
                ->select('tb_komplain.*', 'tu.nama AS nama_user', 'tu.email AS email_user', 'tu.nik AS nik_user', 'tu.alamat AS alamat_user', 'tu.hp AS hp_user', "tt.id_cart")
                ->where('tb_komplain.id_user', $args['id'])
                ->paginate(10);
        $json_complain = $complain->toJson();

        if (count($complain) > 0) {
            $tamp = '{"status": "success","data":' . $json_complain . ',"message":"successfully get data", "is_error":"false"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(202)->withBody($body);
        } else {
            $tamp = '{"status": "error","message":"data not found", "is_error":"true"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(404)->withBody($body);
        }
    }
    
    public function update($req, $res, $args){
        if (false === $this->token->hasScope(['admin', 'user'])) {
            $tamp = '{"status": "error","message":"token not allowed to insert data"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(403)->withBody($body);
        }
        $validator = $this->validator->validate($req, array(
            'id_transaksi' => v::noWhiteSpace()->notEmpty(),
            'id_user' => v::noWhitespace()->notEmpty(),
            'pesan' => v::notEmpty(),
        ));

        if ($validator->failed()) {
            $body = $res->getBody();
            $body->write('{"is_error":"true", "status": "error","message": "validation failed","error":' . $validator->msg() . '}');
            return $res->withHeader('Content-Type', 'application/json')->withStatus(422)->withBody($body);
        } else {

            $complain = Complain::where('id', $args['id'])->first();

            if (count($complain) > 0) {
                $complain->id_transaksi = $req->getParam('id_transaksi');
                $complain->id_user = $req->getParam('id_user');
                $complain->pesan = $req->getParam('pesan');

                if ($complain->save()) {
                    $body = $res->getBody();
                    $body->write('{"status": "success","message":"Successfully store data", "is_error": "false"}');
                    return $res->withHeader('Content-Type', 'application/json')->withStatus(202)->withBody($body);
                } else {
                    $body = $res->getBody();
                    $body->write('{"status": "error","message":"failed to store data", "is_error": "true"}');
                    return $res->withHeader('Content-Type', 'application/json')->withStatus(422)->withBody($body);
                }
            } else {
                $tamp = '{"status": "error","message":"failed to update data, data not found", "is_error": "true"}';
                $body = $res->getBody();
                $body->write($tamp);
                return $res->withHeader('Content-Type', 'application/json')->withStatus(404)->withBody($body);
            }
        }
    }
    
    public function delete($req, $res, $args){
        if (false === $this->token->hasScope(['admin', 'user'])) {
            $tamp = '{"status": "error","message":"token not allowed to delete data"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(403)->withBody($body);
        }

        $complain = Complain::where('id', $args['id'])->first();
        if (count($complain) > 0) {
            if ($complain->delete()) {
                $body = $res->getBody();
                $body->write('{"status": "success","message":"Successfully delete data", "is_error": "false"}');
                return $res->withHeader('Content-Type', 'application/json')->withStatus(202)->withBody($body);
            } else {
                $body = $res->getBody();
                $body->write('{"status": "error","message":"failed to delete data", "is_error": "true"}');
                return $res->withHeader('Content-Type', 'application/json')->withStatus(422)->withBody($body);
            }
        } else {
            $body = $res->getBody();
            $body->write('{"status": "error","message":"Data not found", "is_error": "true"}');
            return $res->withHeader('Content-Type', 'application/json')->withStatus(404)->withBody($body);
        }
    }

}

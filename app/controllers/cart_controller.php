<?php

namespace App\Controllers;

use App\Controllers\controller;
use App\Models\Cart;
use Respect\Validation\Validator as v;

/**
 * Description of cart_controller
 *
 * @author Ervan
 */
class cart_controller extends controller {

    public function insert($req, $res) {
        if (false === $this->token->hasScope(['admin', 'user'])) {
            $tamp = '{"status": "error","message":"token not allowed to insert data"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(403)->withBody($body);
        }
        $validator = $this->validator->validate($req, array(
            'id_barang' => v::noWhiteSpace()->notEmpty(),
            'id_user' => v::noWhitespace()->notEmpty(),
            'tgl_pesan' => v::notEmpty(),
            'ukuran' => v::notEmpty(),
            'jumlah_barang' => v::notEmpty(),
            'alamat_pengiriman' => v::noWhitespace()->notEmpty(),
            'status_tansaksi' => v::noWhitespace()->notEmpty()->alpha(),
            'metode_pembayaran' => v::notEmpty()
        ));

        if ($validator->failed()) {
            $body = $res->getBody();
            $body->write('{"is_error":"true", "status": "error","message": "validation failed","error":' . $validator->msg() . '}');
            return $res->withHeader('Content-Type', 'application/json')->withStatus(422)->withBody($body);
        } else {

            $cart = new Cart();
            $cart->id_barang = $req->getParam('id_barang');
            $cart->id_user = $req->getParam('id_user');
            $cart->tgl_pesan = $req->getParam('tgl_pesan');
            $cart->ukuran = $req->getParam('ukuran');
            $cart->jumlah_barang = $req->getParam('jumlah_barang');
            $cart->alamat_pengiriman = $req->getParam('alamat_pengiriman');
            $cart->status_tansaksi = $req->getParam('status_tansaksi');
            $cart->metode_pembayaran = $req->getParam('metode_pembayaran');

            if ($cart->save()) {
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

    public function get_all($req, $res) {
        if (false === $this->token->hasScope(['admin'])) {
            $tamp = '{"status": "error","message":"token not allowed to insert data"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(403)->withBody($body);
        }
        $cart = Cart::join('tb_user AS tu', 'tb_cart.id_user', '=', 'tu.id')
                ->join('tb_barang AS tb', 'tb_cart.id_barang', '=', 'tb.id')
                ->select('tb_cart.*', 'tu.nama AS nama_user', 'tu.email AS email_user', 'tu.nik AS nik_user', 'tu.alamat AS alamat_user', 'tu.hp AS hp_user', 'tb.nama AS nama_barang', 'tb.harga AS harga_barang', 'tb.gambar AS gambar_barang')
                ->paginate(10);
        $json_cart = $cart->toJson();

        if (count($cart) > 0) {
            $tamp = '{"status": "success","data":' . $json_cart . ',"message":"successfully get data", "is_error":"false"}';
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

    public function get_by_id($req, $res, $args) {
        if (false === $this->token->hasScope(['admin', 'user'])) {
            $tamp = '{"status": "error","message":"token not allowed to insert data"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(403)->withBody($body);
        }
        $cart = Cart::join('tb_user AS tu', 'tb_cart.id_user', '=', 'tu.id')
                ->join('tb_barang AS tb', 'tb_cart.id_barang', '=', 'tb.id')
                ->select('tb_cart.*', 'tu.nama AS nama_user', 'tu.email AS email_user', 'tu.nik AS nik_user', 'tu.alamat AS alamat_user', 'tu.hp AS hp_user', 'tb.nama AS nama_barang', 'tb.harga AS harga_barang', 'tb.gambar AS gambar_barang')
                ->where('tb_cart.id', $args['id'])
                ->first();
        if (count($cart) > 0) {
            $tamp = '{"status": "success","data":' . $cart . ',"message":"successfully get data", "is_error":"false"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(200)->withBody($body);
        } else {
            $tamp = '{"status": "error","message":"data not found", "is_error":"true"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(404)->withBody($body);
        }
    }

    public function get_by_user($req, $res, $args) {
        if (false === $this->token->hasScope(['admin', 'user'])) {
            $tamp = '{"status": "error","message":"token not allowed to insert data"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(403)->withBody($body);
        }
        $cart = Cart::join('tb_user AS tu', 'tb_cart.id_user', '=', 'tu.id')
                ->join('tb_barang AS tb', 'tb_cart.id_barang', '=', 'tb.id')
                ->select('tb_cart.*', 'tu.nama AS nama_user', 'tu.email AS email_user', 'tu.nik AS nik_user', 'tu.alamat AS alamat_user', 'tu.hp AS hp_user', 'tb.nama AS nama_barang', 'tb.harga AS harga_barang', 'tb.gambar AS gambar_barang')
                ->where('tb_cart.id_user', $args['id'])
                ->paginate(10);
        $json_cart = $cart->toJson();
        if (count($cart) > 0) {
            $tamp = '{"status": "success","data":' . $json_cart . ',"message":"successfully get data", "is_error":"false"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(200)->withBody($body);
        } else {
            $tamp = '{"status": "error","message":"data not found", "is_error":"true"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(404)->withBody($body);
        }
    }

    public function update($req, $res, $args) {
        if (false === $this->token->hasScope(['admin', 'user'])) {
            $tamp = '{"status": "error","message":"token not allowed to insert data"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(403)->withBody($body);
        }
        $validator = $this->validator->validate($req, array(
            'id_barang' => v::noWhiteSpace()->notEmpty(),
            'id_user' => v::noWhitespace()->notEmpty(),
            'tgl_pesan' => v::notEmpty(),
            'ukuran' => v::notEmpty(),
            'jumlah_cart' => v::notEmpty(),
            'alamat_pengiriman' => v::noWhitespace()->notEmpty(),
            'status_tansaksi' => v::noWhitespace()->notEmpty()->alpha(),
            'metode_pembayaran' => v::notEmpty()
        ));

        if ($validator->failed()) {
            $body = $res->getBody();
            $body->write('{"is_error":"true", "status": "error","message": "validation failed","error":' . $validator->msg() . '}');
            return $res->withHeader('Content-Type', 'application/json')->withStatus(422)->withBody($body);
        } else {

            $cart = Cart::where('id', $args['id'])->first();

            if (count($cart) > 0) {
                $cart->id_barang = $req->getParam('id_barang');
                $cart->id_user = $req->getParam('id_user');
                $cart->tgl_pesan = $req->getParam('tgl_pesan');
                $cart->ukuran = $req->getParam('ukuran');
                $cart->jumlah_cart = $req->getParam('jumlah_cart');
                $cart->alamat_pengiriman = $req->getParam('alamat_pengiriman');
                $cart->status_tansaksi = $req->getParam('status_tansaksi');
                $cart->metode_pembayaran = $req->getParam('metode_pembayaran');

                if ($cart->save()) {
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

    public function delete($req, $res, $args) {
        if (false === $this->token->hasScope(['admin', 'user'])) {
            $tamp = '{"status": "error","message":"token not allowed to delete data"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(403)->withBody($body);
        }

        $cart = Cart::where('id', $args['id'])->first();
        if (count($cart) > 0) {
            if ($cart->delete()) {
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

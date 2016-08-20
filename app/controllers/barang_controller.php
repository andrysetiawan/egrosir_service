<?php

namespace App\Controllers;

use App\Controllers\controller;
use App\Models\Barang;
use Respect\Validation\Validator as v;

/**
 * Description of barang_controller
 *
 * @author Ervan
 */
class barang_controller extends controller {

    public function insert($req, $res) {
        $validator = $this->validator->validate($req, array(
            'nama' => v::notEmpty(),
            'harga' => v::noWhitespace()->notEmpty(),
            'harga_diskon' => v::noWhitespace(),
            'stock' => v::noWhitespace()->notEmpty(),
            'deskripsi' => v::notEmpty(),
            'status_nego' => v::noWhitespace()->notEmpty()->alpha(),
            'status_diskon' => v::noWhitespace()->notEmpty()->alpha(),
            'status_cuci_gudang' => v::noWhitespace()->notEmpty()->alpha(),
            'jasa_pengiriman' => v::notEmpty(),
            'berat' => v::noWhitespace()->notEmpty(),
            'gambar' => v::notEmpty(),
            'ukuran_tersedia' => v::notEmpty(),
            'id_kategori' => v::noWhitespace()->notEmpty(),
            'id_pasar' => v::noWhitespace()->notEmpty(),
            'status_iklan' => v::noWhitespace()->notEmpty()->alpha()
        ));

        if ($validator->failed()) {
            $body = $res->getBody();
            $body->write('{"is_error":"true", "status": "error","message": "validation failed","error":' . $validator->msg() . '}');
            return $res->withHeader('Content-Type', 'application/json')->withStatus(422)->withBody($body);
        } else {

            $barang = new Barang();
            $barang->nama = $req->getParam('nama');
            $barang->harga = $req->getParam('harga');
            $barang->harga_diskon = $req->getParam('harga_diskon');
            $barang->stock = $req->getParam('stock');
            $barang->deskripsi = $req->getParam('deskripsi');
            $barang->status_nego = $req->getParam('status_nego');
            $barang->status_diskon = $req->getParam('status_diskon');
            $barang->status_cuci_gudang = $req->getParam('status_cuci_gudang');
            $barang->jasa_pengiriman = $req->getParam('jasa_pengiriman');
            $barang->berat = $req->getParam('berat');
            $barang->gambar = $req->getParam('gambar');
            $barang->ukuran_tersedia = $req->getParam('ukuran_tersedia');
            $barang->id_kategori = $req->getParam('id_kategori');
            $barang->id_pasar = $req->getParam('id_pasar');
            $barang->status_iklan = $req->getParam('status_iklan');

            if ($barang->save()) {
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

        $tb_barang = Barang::get();

        if (count($tb_barang) > 0) {
            $tamp = '{"status": "success","data":' . $tb_barang . ',"message":"successfully get data", "is_error":"false"}';
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

}

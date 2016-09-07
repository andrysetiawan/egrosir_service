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
        if (false === $this->token->hasScope(['admin'])) {
            $tamp = '{"status": "error","message":"token not allowed to insert data"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(403)->withBody($body);
        }
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

            $kode_barang = function($req) {
                $nama = strtoupper(substr($req->getParam('nama'), 0, 3));
                $tgl = date('d');
                $kategori = $req->getParam('id_kategori');
                return $nama . "-" . $tgl . "-" . $kategori;
            };

            $barang = new Barang();
            $barang->nama = $req->getParam('nama');
            $barang->harga = $req->getParam('harga');
            $barang->harga_diskon = $req->getParam('harga_diskon');
            $barang->stock = $req->getParam('stock');
            $barang->deskripsi = $req->getParam('deskripsi');
            $barang->status_nego = $req->getParam('status_nego');
            $barang->status_diskon = $req->getParam('status_diskon');
            $barang->status_cuci_gudang = $req->getParam('status_cuci_gudang');
            $barang->kode_barang = $kode_barang($req);
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
        $page = $req->getParam("page");
        if ($page < 1 OR $page == NULL) {
            $page = 1;
        }        
        $barang = Barang::join('tb_kategori AS tk', 'tb_barang.id_kategori', '=', 'tk.id')
                ->join('tb_pasar AS tp', 'tb_barang.id_pasar', '=', 'tp.id')
                ->select('tb_barang.*', 'tk.nama_kategori', 'tk.gambar AS gambar_kategori', 'tp.nama_pasar', 'tp.alamat AS alamat_pasar', 'tp.gambar AS gambar_pasar');

        $tb_barang = $this->helper_class->paginate($barang, 10, $page);
        $tb_barang->setPath('http://' . $_SERVER['HTTP_HOST'] . '/egrosir_service/public/barang');
        $json_barang = $tb_barang->toJson();

        if (count($tb_barang) > 0) {
            $tamp = '{"status": "success","data":' . $json_barang . ',"message":"successfully get data", "is_error":"false"}';
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

    public function update($req, $res, $args) {
        if (false === $this->token->hasScope(["admin"])) {
            $tamp = '{"status": "error","message":"token not allowed to update data"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(403)->withBody($body);
        } else {
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
                $barang = Barang::where('id', $args['id'])->first();

                if (count($barang) > 0) {
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
                        $body->write('{"status": "success","message":"Successfully update data", "is_error": "false"}');
                        return $res->withHeader('Content-Type', 'application/json')->withStatus(202)->withBody($body);
                    } else {
                        $body = $res->getBody();
                        $body->write('{"status": "error","message":"failed to update data", "is_error": "true"}');
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
    }

    public function delete($req, $res, $args) {
        if (false === $this->token->hasScope(['admin'])) {
            $tamp = '{"status": "error","message":"token not allowed to delete data"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(403)->withBody($body);
        }

        $barang = Barang::where('id', $args['id'])->first();
        if (count($barang) > 0) {
            if ($barang->delete()) {
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

    public function get_by_id($req, $res, $args) {
        $barang = Barang::join('tb_kategori AS tk', 'tb_barang.id_kategori', '=', 'tk.id')
                ->join('tb_pasar AS tp', 'tb_barang.id_pasar', '=', 'tp.id')
                ->select('tb_barang.*', 'tk.nama_kategori', 'tk.gambar AS gambar_kategori', 'tp.nama_pasar', 'tp.alamat AS alamat_pasar', 'tp.gambar AS gambar_pasar')
                ->where('tb_barang.id', $args['id'])
                ->first();
        if (count($barang) > 0) {
            $tamp = '{"status": "success","data":' . $barang . ',"message":"successfully get data", "is_error":"false"}';
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

    public function get_by_category($req, $res, $args) {
        $page = $req->getParam("page");
        if ($page < 1 OR $page == NULL) {
            $page = 1;
        } 
        $barang = Barang::join('tb_kategori AS tk', 'tb_barang.id_kategori', '=', 'tk.id')
                ->join('tb_pasar AS tp', 'tb_barang.id_pasar', '=', 'tp.id')
                ->select('tb_barang.*', 'tk.nama_kategori', 'tk.gambar AS gambar_kategori', 'tp.nama_pasar', 'tp.alamat AS alamat_pasar', 'tp.gambar AS gambar_pasar')
                ->where('tb_barang.id_kategori', $args['id']);
        $barang = $this->helper_class->paginate($barang, 10, $page);        
        $json_barang = $barang->toJson();

        if (count($barang) > 0) {
            $tamp = '{"status": "success","data":' . $json_barang . ',"message":"successfully get data", "is_error":"false"}';
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

    public function get_by_pasar($req, $res, $args) {
        $page = $req->getParam("page");
        if ($page < 1 OR $page == NULL) {
            $page = 1;
        } 
        $barang = Barang::join('tb_kategori AS tk', 'tb_barang.id_kategori', '=', 'tk.id')
                ->join('tb_pasar AS tp', 'tb_barang.id_pasar', '=', 'tp.id')
                ->select('tb_barang.*', 'tk.nama_kategori', 'tk.gambar AS gambar_kategori', 'tp.nama_pasar', 'tp.alamat AS alamat_pasar', 'tp.gambar AS gambar_pasar')
                ->where('tb_barang.id_pasar', $args['id']);
        $barang = $this->helper_class->paginate($barang, 10, $page);
        $json_barang = $barang->toJson();
        if (count($barang) > 0) {
            $tamp = '{"status": "success","data":' . $json_barang . ',"message":"successfully get data", "is_error":"false"}';
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

    public function get_by_status($req, $res, $args) {
        $status = "";
        if (strtolower($args['status']) == "nego") {
            $status = "status_nego";
        } elseif (strtolower($args['status']) == "diskon") {
            $status = "status_diskon";
        } elseif (strtolower($args['status']) == "cuci_gudang") {
            $status = "status_cuci_gudang";
        } else {
            $tamp = '{"status": "error","message":"Parameter not valid", "is_error":"true"}';
            $body = $res->getBody();
            $body->write($tamp);
            return $res->withHeader('Content-Type', 'application/json')->withStatus(404)->withBody($body);
        }
        
        $page = $req->getParam("page");
        if ($page < 1 OR $page == NULL) {
            $page = 1;
        }         
        $barang = Barang::join('tb_kategori AS tk', 'tb_barang.id_kategori', '=', 'tk.id')
                ->join('tb_pasar AS tp', 'tb_barang.id_pasar', '=', 'tp.id')
                ->select('tb_barang.*', 'tk.nama_kategori', 'tk.gambar AS gambar_kategori', 'tp.nama_pasar', 'tp.alamat AS alamat_pasar', 'tp.gambar AS gambar_pasar')
                ->where($status, 'Y');
        $barang = $this->helper_class->paginate($barang, 10, $page);
        $json_barang = $barang->toJson();

        if (count($barang) > 0) {
            $tamp = '{"status": "success","data":' . $json_barang . ',"message":"successfully get data", "is_error":"false"}';
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

    public function get_by_price($req, $res, $args) {
        $page = $req->getParam("page");
        if ($page < 1 OR $page == NULL) {
            $page = 1;
        } 
        $harga = explode('-', $args['harga']);
        $barang = Barang::join('tb_kategori AS tk', 'tb_barang.id_kategori', '=', 'tk.id')
                ->join('tb_pasar AS tp', 'tb_barang.id_pasar', '=', 'tp.id')
                ->select('tb_barang.*', 'tk.nama_kategori', 'tk.gambar AS gambar_kategori', 'tp.nama_pasar', 'tp.alamat AS alamat_pasar', 'tp.gambar AS gambar_pasar')
                ->whereBetween('harga', [$harga[0], $harga[1]]);
        $barang = $this->helper_class->paginate($barang, 10, $page);
        $json_barang = $barang->toJson();

        if (count($barang) > 0) {
            $tamp = '{"status": "success","data":' . $json_barang . ',"message":"successfully get data", "is_error":"false"}';
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

}

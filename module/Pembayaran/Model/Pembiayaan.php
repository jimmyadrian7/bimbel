<?php
namespace Bimbel\Pembayaran\Model;

use Bimbel\Core\Model\BaseModel;
use Illuminate\Database\Capsule\Manager as DB;

class Pembiayaan extends BaseModel
{
    protected $fillable = ['kategori_pembiayaan', 'kode', 'nama', 'harga', 'stok', 'jumlah_stok', 'jenis_komisi', 'nominal'];
    protected $table = 'pembiayaan';

    protected $kategori_pembiayaan_enum = [
        ["value" => "a", "label" => "Aksesoris"],
        ["value" => "s", "label" => "SPP"],
        ["value" => "p", "label" => "Pendaftaran"],
        ["value" => "d", "label" => "Deposit"],
        ["value" => "l", "label" => "Lain-lain"]
    ];

    protected $jenis_komisi_enum = [
        ["value" => "s", "label" => "Siswa"],
        ["value" => "p", "label" => "Persen"],
        ["value" => "n", "label" => "Nominal"]
    ];


    public function fetchDetail($id, $obj)
    {
        $data = parent::fetchDetail($id, $obj);
        
        $query = "
            SELECT COUNT(id) AS total FROM
            (
                SELECT id FROM iuran_detail
                WHERE pembiayaan_id = %u
                UNION
                SELECT id FROM tagihan_detail
                WHERE pembiayaan_id = %u
            ) AS x
            GROUP BY id
        ";
        $query = sprintf($query, $id, $id);

        $total = DB::select(DB::raw($query));

        if (count($total) > 0)
        {
            $data->editable = false;
            $data->deleteable = false;
        }

        return $data;
    }
}
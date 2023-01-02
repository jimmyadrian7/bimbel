<?php
namespace Bimbel\Pembayaran\Model;

use Bimbel\Core\Model\BaseModel;

class Diskon extends BaseModel
{
    protected $fillable = ['diskon', 'tipe_diskon'];
    protected $table = 'diskon';

    protected $tipe_diskon_enum = [
      ["value" => "p", "label" => "Persen"],
      ["value" => "n", "label" => "Nominal"]
    ];
}
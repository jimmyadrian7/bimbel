<?php
namespace Bimbel\Master\Model;

use Bimbel\Core\Model\BaseModel;

class Sequance extends BaseModel
{
    protected $fillable = ['kode', 'nama', 'nomor'];
    protected $table = 'sequance';

    public function getnextCode($code_name)
	{
		$seq = $this->where('nama', $code_name)->first();
		$code = join("", array_map(function($string) {
			return ucfirst(substr($string, 0, 1));
		},
		explode("_", $code_name)));

		if (empty($seq))
		{
			$seq = $this->create(['nama' => $code_name, 'kode' => $code]);
		}
		
		$seq = $seq->refresh();
		$kode = $seq->kode.sprintf('%03d', $seq->nomor);
		$seq->update(['nomor' => $seq->nomor+1]);

		return $kode;
	}
}

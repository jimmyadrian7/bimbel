<?php
namespace Bimbel\Pembayaran\Controller;

use \Bimbel\Master\Controller\Controller;
use \Slim\Exception\HttpNotFoundException;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Facades\Schema;

class TagihanController extends Controller
{
    public function gantiGuru($request, $args, &$response)
    {
        $result = ["data" => "success"];

        $tagihan = new \Bimbel\Pembayaran\Model\Tagihan();
        $tagihan_detail = new \Bimbel\Pembayaran\Model\TagihanDetail();
        $postData = $request->getParsedBody();

        $tagihan = $tagihan->find($postData['tagihan_id']);

        $tagihan->updateGuru($postData['guru_id'], $postData['komisi']);

        return $result;
    }
}

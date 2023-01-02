<?php
namespace Bimbel\Message\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\Siswa\Model\Siswa;

class Message extends BaseModel
{
    protected $fillable = ['siswa_id', 'message'];
    protected $table = 'message';
    protected $appends = ['siswa_data'];

    public function getSiswaDataAttribute() {
		$data = [
            "id" => $this->siswa->id,
            "nama" => $this->siswa->orang->nama
        ];

		return $data;
	}

    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'id', 'siswa_id');
    }


    public function sendMessage($attributes)
    {
        $siswa = new Siswa();
        $siswa = $siswa->find($attributes['siswa_id']);

        $url = "https://graph.facebook.com/v15.0/110496218589871/messages";
        $data = [
            "messaging_product" => "whatsapp",
            "to" => "628997290679",
            "type" => "template",
            "template" => [
                "name" => "hello_world",
                "language" => [
                    "code" => "en_US"
                ]
            ]
        ];
        $data = json_encode($data);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Content-type: application/json",
            "Authorization: Bearer EABUBDufda7oBAP6Jh9VXb9JxFQ4Fh6ZCeSl8iSy0RxRfkD04WdmXcYyvz7C7NoZB65Ab6mlhPBqvn0q56unoZBI55MNk6wp0NZCZAFQAqPORVSZCg2POBuxNRZBzWxDmvRY4ZA9ZCoWjlHp6dAYj7kkn9wqo9GGsnd7AuOrZB2etono3bXPwEyec02vMtlLJyH4HqFuGO8Cd6UjKwnuJSvJnNl"
        ));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);


        if ( $status != 200 ) {
            throw new \Error("Error sending message");
        }
        curl_close($curl);

        $response = json_decode($json_response, true);        
    }

    public function create(array $attributes = [])
    {
		$this->sendMessage($attributes);
        return parent::create($attributes);
    }
}

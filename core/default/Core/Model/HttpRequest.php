<?php

namespace Bimbel\Core\Model;


class HttpRequest
{
    private $header = array("Content-type: application/json");

    public function getHeader()
    {
        return $this->header;
    }

    public function setHeader($header)
    {
        $this->header = $header;
    }


    public function get($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'GET' );

        $result = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $data = [
            "data" => $result,
            "status" => $status
        ];

        curl_close($curl);

        return $data;
    }

    public function post($url, $data)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $result = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $data = [
            "data" => $result,
            "status" => $status
        ];

        curl_close($curl);

        return $data;
    }
}
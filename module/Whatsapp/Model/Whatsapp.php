<?php
namespace Bimbel\Whatsapp\Model;

class Whatsapp
{
    private $account;
    private $base_url;
    private $version;

    function __construct()
    {
        $account = new \Bimbel\Master\Model\AccountConfiguration();
        $this->account = $account->first();
        $this->base_url = $_ENV['wa_base_url'];
        $this->version = $_ENV['wa_version'];
    }

    public function buildUrl($endpoint, $phone_id = false)
    {
        $account_id = $this->account->wa_business_account_id;

        if ($phone_id)
        {
            $account_id = $this->account->wa_phone_number_id;
        }

        $url = "%s/%s/%s/%s";
        $url = sprintf($url, $this->base_url, $this->version, $account_id, $endpoint);

        return $url;
    }


    public function getTemplate($template_name = "")
    {
        $endpoint = "message_templates";
        $url = $this->buildUrl($endpoint) . "?access_token=%s&name=%s&status=APPROVED";
        $url = sprintf($url, $this->account->wa_access_token, $template_name);

        $req = new \Bimbel\Core\Model\HttpRequest();
        $result = $req->get($url);

        return $result;
    }

    public function sendMessageTemplate($template, $language, $to, $nama = "")
    {
        $url = $this->buildUrl("messages", true);
        $data = [
            "messaging_product" => "whatsapp",
            "to" => $to,
            "type" => "template",
            "template" => [
                "name" => $template,
                "language" => [
                    "code" => $language
                ]
            ]
        ];

        if (!empty($nama))
        {
            $data['template']['components'] = [
                [
                    "type" => "body",
                    "parameters" => [
                        [
                            "type" => "text", 
                            "text" => $nama
                        ]
                    ]
                ]
            ];
        }

        $data = json_encode($data);

        $req = new \Bimbel\Core\Model\HttpRequest();
        $req->setHeader(array(
            "Content-type: application/json",
            "Authorization: Bearer " . $this->account->wa_access_token
        ));
        $result = $req->post($url, $data);

        return $result;
    }
}

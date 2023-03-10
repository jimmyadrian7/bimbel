<?php
namespace Bimbel\Whatsapp\Controller;

use \Bimbel\Master\Controller\Controller;

class FetchController extends Controller
{
    public function getTemplate($request, $args)
    {        
        $account_id = $_ENV['wa_business_account_id'];
        $version = $_ENV['wa_version'];
        $base_url = $_ENV['wa_base_url'];
        $access_token = $_ENV['wa_access_token'];
        $url = "%s/%s/%s/message_templates?limit=3&access_token=%s";
        $url = sprintf($url, $base_url, $version, $account_id, $access_token);
        

        $req = $this->container->get('httpReq');
        $result = $req->get($url);
        $data = json_decode($result['data']);

        if ($result['status'] !== 200 && $result['status'] !== 201)
        {
            $data = json_decode($result['data']);
            throw new \Error($data->error->message);
        }

        return $data;
    }

    public function getTemplateAutocomplete($request, $args)
    {
        $getData = $request->getQueryParams();
        $wa = new \Bimbel\Whatsapp\Model\Whatsapp();

        $result = $wa->getTemplate($getData['query']);
        $data = json_decode($result['data']);

        if ($result['status'] !== 200 && $result['status'] !== 201)
        {
            $data = json_decode($result['data']);
            throw new \Error($data->error->message);
        }

        $data = array_map(function($v) {
            $t_name = $v->name . "(". $v->language .")";
            $t_content = "";

            foreach ($v->components as $content)
            {
                if ($content->type == "BODY")
                {
                    $t_content = $content->text;
                }
            }

            $newData = ["id" => $t_name, 'text' => $t_name, 'content' => $t_content];
            return $newData;
        }, $data->data);

        return $data;
    }
}

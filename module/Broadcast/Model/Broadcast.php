<?php
namespace Bimbel\Broadcast\Model;

use Bimbel\Core\Model\BaseModel;

class Broadcast extends BaseModel
{
    protected $fillable = ['template_name', 'content', 'language', 'status'];
    protected $table = 'broadcast';

    protected $status_enum = [
        ["value" => "n", "label" => "Baru"],
        ["value" => "s", "label" => "Terkirim"]
    ];


    public function getSiswa()
    {
        $siswa = new \Bimbel\Siswa\Model\Siswa();
        $siswa = $siswa->where('status', 'a')->get();

        return $siswa;
    }

    public function handleStatus($attributes)
    {
        $status = self::getValue($attributes, 'status', false);

        if (empty($status) || $status != 's')
        {
            return;
        }

        $siswas = $this->getSiswa();
        $wa = new \Bimbel\Whatsapp\Model\Whatsapp();

        $template_data = $this->template_name;
        $template_data = explode("(", $template_data);
        $template = $template_data[0];
        $language = substr($template_data[1], 0, -1);
        
        foreach ($siswas as $siswa)
        {
            if (empty($siswa->orang->no_hp))
            {
                continue;
            }

            $result = $wa->sendMessageTemplate($template, $language, $siswa->orang->no_hp);

            if ($result['status'] != '200' && $result['status'] != '201')
            {
                $errMsg = $result['data'];
                $errMsg = json_decode($errMsg);
                throw new \Error($errMsg->error->message);
            }
        }
        
    }

    public function update(array $attributes = [], array $options = [])
    {
        $this->handleStatus($attributes);

        $result = parent::update($attributes, $options);
        return $result;
    }


    public function fetchDetail($id, $obj)
    {
        $data = parent::fetchDetail($id, $obj);

        $data->editable = false;        
        if ($data->status == 's')
        {
            $data->deleteable = false;
        }

        return $data;
    }
}

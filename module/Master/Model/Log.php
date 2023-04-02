<?php
namespace Bimbel\Master\Model;

use Bimbel\Core\Model\BaseModel;
use Bimbel\User\Model\User;

class Log extends BaseModel
{
    protected $fillable = ['target_id', 'target_table', 'operation', 'data', 'user_id'];
    protected $table = 'log';
    public $searchField = "target_table";


    public function user()
	{
		return $this->hasOne(User::class, 'id', 'user_id');
	}


    public function log($id, $table, $operation, $data, $user_id = false)
    {
        $this->removeBase64($data);

        if (!$user_id)
        {
            $session = new \Bimbel\Master\Model\Session();
            $user_id = $session->getCurrentUser()->id;
        }

        $log = $this->create([
            "target_id" => $id,
            "target_table" => $table,
            "operation" => $operation,
            "data" => json_encode($data),
            "user_id" => $user_id
        ]);

        return $log;
    }

    public function fetchAllData($condition, $obj, $pagination = false, $page = 1, $sort = [])
    {
        $obj = $this->with('user', 'user.orang');
        return parent::fetchAllData($condition, $obj, $pagination, $page, $sort);
    }

	public function fetchDetail($id, $obj)
    {
		$obj = $obj->with('user', 'user.orang');
        $data = parent::fetchDetail($id, $obj);
        return $data;
    }


    function removeBase64(&$data)
    {
        if (gettype($data) == "array")
        {
            foreach($data as $key => &$value)
            {
                if (gettype($value) == "array")
                {
                    removeBase64($value);
                }
                else
                {
                    if ($key == "base64")
                    {
                        unset($data[$key]);
                    }
                }
            }
        }
    }
}

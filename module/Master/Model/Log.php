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


    public function fetchAllData($condition, $obj, $pagination = false, $page = 1)
    {
        $obj = $this->with('user', 'user.orang');
        return parent::fetchAllData($condition, $obj, $pagination, $page);
    }

	public function fetchDetail($id, $obj)
    {
		$obj = $obj->with('user', 'user.orang');
        $data = parent::fetchDetail($id, $obj);
        return $data;
    }
}

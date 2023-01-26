<?php

namespace Bimbel\Core\Model;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public $timestamps = false;
    public $searchField = "nama";
    
    public function getFillable()
    {
        return $this->fillable;
    }

    public static function getValue(&$attributes, $name, $isUnset = true)
    {
        $output = [];
        if (array_key_exists($name, $attributes))
        {
            $output = $attributes[$name];
            if ($isUnset)
            {
                unset($attributes[$name]);
            }
        }

        return $output;
    }

    public function getLabel($name)
    {
        $result = "";

        foreach ($this->{$name. "_enum"} as $val) {
            if ($val['value'] == $this->{$name})
            {
                return $val['label'];
            }
        }

        return $result;
    }

    public function fetchAllData($condition, $obj, $pagination = false, $page = 1)
    {
        if (!empty($condition))
        {
            foreach($condition as $key => $cond)
            {
                if (in_array("in", $cond))
                {
                    $obj = $obj->whereIn($cond[0], $cond[2]);
                    array_splice($condition, $key, 1);
                }
            }

            if (!empty($condition))
            {
                $obj = $obj->where($condition);
            }
        }

        if ($pagination)
        {
            $data = $obj->paginate($_ENV['row_per_page'], ['*'], 'page', $page);
        }
        else
        {
            $data = $obj->get();
        }
        
        return $data;
    }

    public function fetchDetail($id, $obj)
    {
        $data = $obj->find($id);
        $data->editable = true;
        $data->deleteable = true;

        return $data;
    }
}

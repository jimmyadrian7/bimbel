<?php

namespace Bimbel\Core\Model;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public $timestamps = false;
    public $searchField = "nama";
    public $required_field = [];
    
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

    public function fetchAllData($condition, $obj, $pagination = false, $page = 1, $sort = [])
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
                else
                {
                    $checkField = $cond[0];
                    $checkField = explode(".", $checkField);

                    if (count($checkField) > 1)
                    {
                        $obj = $this->deepWhere($obj, $cond);
                    }
                    else
                    {
                        $obj = $obj->where([$cond]);
                    }
                }
            }
        }

        if (!empty($sort))
        {
            foreach($sort as $key => $s)
            {
                // $checkField = $cond[0];
                // $checkField = explode(".", $checkField);

                // if (count($checkField) > 1)
                // {
                //     $obj = $this->deepSort($obj, $s);
                // }
                // else
                // {
                //     $obj = $obj->orderBy($s[0], $s[1]);
                // }

                $obj = $obj->orderBy($s[0], $s[1]);
            }
        }

        if ($pagination)
        {
            $data = $obj->orderBy('id', 'DESC')->paginate($_ENV['row_per_page'], ['*'], 'page', $page);
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

    public function deepWhere($obj, $cond)
    {
        $field = $cond[0];
        $field = explode(".", $field);
        $fieldOne = $field[0];

        array_shift($field);

        $cond[0] = join(".", $field);

        $obj = $obj->whereHas($fieldOne, function($q) use ($cond) {
            $field = $cond[0];
            $field = explode(".", $field);
            $fieldOne = $field[0];

            if (count($field) == 1)
            {
                return $q->where($fieldOne, $cond[1], $cond[2]);
            }

            return $this->deepWhere($q, $cond);
        });

        return $obj;
    }

    // public function deepSort($obj, $s)
    // {

    // }
}

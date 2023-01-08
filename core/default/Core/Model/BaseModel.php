<?php

namespace Bimbel\Core\Model;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public $timestamps = false;
    
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
}

<?php
namespace Bimbel\Master\Controller;

use \Bimbel\Master\Controller\Controller;
use Illuminate\Database\Capsule\Manager as DB;

class UpdateController extends Controller
{
    public function updateData($request, $args)
    {
        $result = [];

        try 
        {
            DB::beginTransaction();
            
            $data = $request->getParsedBody();
            $model = $this->getModel($args["model"]);
            $fields = $model->getFillable();
            $model_values = [];

            if(!array_key_exists("id", $data))
            {
                throw new \Error("Id not found");
            }

            foreach($fields as $field)
            {
                if(array_key_exists($field, $data))
                {
                    $model_values[$field] = $data[$field];
                }
            }

            if (count($model_values) === 0)
            {
                throw new \Error("Data is empty");
            }

            $model_id = $data["id"];
            $model = $model->find($model_id);

            if(!$model)
            {
                throw new \Error("Data not found");
            }

            $model->update($model_values);
            $result = ["id" => $model->id];


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

            removeBase64($model_values);

            $log = $this->getModel('log');
            $log = $log->create([
                "target_id" => $model->id,
                "target_table" => $args['model'],
                "operation" => "Update",
                "data" => json_encode($model_values)
            ]);

            DB::commit();
        }
        catch(\Error $e) 
        {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

        return $result;
    }
}

<?php
namespace Bimbel\Master\Controller;

use \Bimbel\Master\Controller\Controller;
use Illuminate\Database\Capsule\Manager as DB;

class InsertController extends Controller
{
    public function insertData($request, $args)
    {
        $result = [];

        try 
        {
            DB::beginTransaction();
            
            $data = $request->getParsedBody();
            $model = $this->getModel($args["model"]);
            $fields = $model->getFillable();
            $model_values = [];

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

            $model = $model->create($model_values);
            $result = ["id" => $model->id];

            $log = $this->getModel('log');
            $log = $log->create([
                "target_id" => $model->id,
                "target_table" => $args['model'],
                "operation" => "Create",
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

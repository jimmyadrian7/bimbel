<?php
namespace Bimbel\Master\Controller;

use \Bimbel\Master\Controller\Controller;
use Illuminate\Database\Capsule\Manager as DB;

class UpdateController extends Controller
{
    public function updateData($request, $args, &$response)
    {
        $result = [];

        try 
        {
            DB::beginTransaction();
            
            $data = $request->getParsedBody();
            $model = $this->getModel($args["model"]);
            $fields = $model->getFillable();
            $required_fields = $model->required_field;
            $model_values = [];

            if(!array_key_exists("id", $data))
            {
                throw new \Error("Id not found");
            }

            foreach($fields as $field)
            {
                foreach ($required_fields as $required_field)
                {
                    if ($field === $required_field['name'])
                    {
                        if (!array_key_exists($field, $data) || empty($data[$field]))
                        {
                            throw new \Error($required_field['label'] . " wajib diisi.");
                        }
                    }
                }

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

            $session = new \Bimbel\Master\Model\Session();
            $log = $this->getModel('log');
            $log = $log->create([
                "target_id" => $model->id,
                "target_table" => $args['model'],
                "operation" => "Update",
                "data" => json_encode($model_values),
                "user_id" => $session->getCurrentUser()->id
            ]);

            DB::commit();
        }
        catch(\Error $e) 
        {
            DB::rollBack();
            $result = $this->container->get('error')($e, $response);
        }

        return $result;
    }
}

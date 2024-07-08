<?php
namespace Bimbel\Master\Controller;

use \Bimbel\Master\Controller\Controller;
use Illuminate\Database\Capsule\Manager as DB;

class DeleteController extends Controller
{
    public function deleteData($request, $args, &$response)
    {
        $result = [];

        try 
        {
            DB::beginTransaction();
            
            $data = $request->getParsedBody();
            $model = $this->getModel($args["model"]);

            if(!array_key_exists("id", $data))
            {
                throw new \Error("Id not found");
            }
            
            $model_id = $data["id"];
            $model = $model->find($model_id);

            if(!$model)
            {
                throw new \Error("Data not found");
            }

            $model_values = $model->toArray();

            $model->delete();
            $result = ["id" => $model_id];

            $log = $this->getModel('log');
            $log = $log->log($model_id, $args['model'], "Delete", $model_values);

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

<?php
namespace Bimbel\Master\Controller;

use \Bimbel\Master\Controller\Controller;
use Illuminate\Database\Capsule\Manager as DB;

class DeleteController extends Controller
{
    public function deleteData($request, $args)
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

            $model->delete();
            $result = ["id" => $model_id];

            $session = new \Bimbel\Master\Model\Session();
            $log = $this->getModel('log');
            $log = $log->create([
                "target_id" => $model_id,
                "target_table" => $args['model'],
                "operation" => "Delete",
                "user_id" => $session->getCurrentUser()->id
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

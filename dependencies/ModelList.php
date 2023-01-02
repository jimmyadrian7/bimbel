<?php

class ModelList {
    public $listModel = array();

    public function __construct()
    {
        $modules_path = __DIR__ . "/../module";
        $modules = scandir($modules_path);

        foreach ($modules as $key => $module) {
            if ('.' == $module) continue;
            if ('..' == $module) continue;
        
            $models = sprintf("%s/%s/Model", $modules_path, $module);
        
            if (is_dir($models)) {
                $models = scandir($models);

                foreach ($models as $model) {
                    if ('.' == $model) continue;
                    if ('..' == $model) continue;

                    $model_name = explode(".php", $model);
                    $model_name = array_shift($model_name);
                    $this->listModel[$model_name] = sprintf("\\Bimbel\\%s\\Model\\%s", $module, $model_name);
                }
            }
        }
    }

    public function getModel($name)
    {
        if(array_key_exists($name, $this->listModel))
        {
            return new $this->listModel[$name]();
        }
    }
}

return [
    'Model' => function () {
        return new ModelList();
    },
];
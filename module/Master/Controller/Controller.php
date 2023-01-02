<?php
namespace Bimbel\Master\Controller;

use Psr\Container\ContainerInterface;
use \Bimbel\Core\Controller\Controller as CoreController;
use \Illuminate\Database\Capsule\Manager;
use \Monolog\Logger;

abstract class Controller extends CoreController
{
    protected $container;
    protected $database;

    public function __construct(
        Manager $database,
        Logger $logger,
        ContainerInterface $container
    ) {
        $this->database = $database;
        $this->container = $container;
    }

    public function getModel($model_name)
    {
        $modelList = $this->container->get("Model");
        $model_name = join("", array_map("ucfirst", explode("_", $model_name)));
        $model = $modelList->getModel($model_name);

        return $model;
    }
}

<?php
namespace Bimbel\Template\Controller;

use \Bimbel\Master\Controller\Controller;

class ViewController extends Controller
{
    public function homeView()
    {
        $twig = $this->container->get("twig");
        $data = [
            "base_url" => "/bimbel"
        ];
        $rendered = $twig->fetch("Template/View/home.twig", $data);

        return $rendered;
    }

    public function templateView($args)
    {
        $twig = $this->container->get("twig");
        $rendered = $twig->fetch(sprintf("Template/View/static/%s.twig", $args['template']));

        return $rendered;
    }
}

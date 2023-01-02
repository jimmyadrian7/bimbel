<?php

$app->get("/admin", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Template\Controller\ViewController");
    $result = $controller->homeView();

    $response = $response->withHeader("Content-Type", "text/html");
    $response->getBody()->write($result);
    return $response;
});


$app->get("/admin/static/template/{template}", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Template\Controller\ViewController");
    $result = $controller->templateView($args);

    $response = $response->withHeader("Content-Type", "text/html");
    $response->getBody()->write($result);
    return $response;
});

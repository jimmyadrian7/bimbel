<?php

$app->post("/api/{model}", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Master\Controller\InsertController");
    $result = $controller->insertData($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

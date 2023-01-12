<?php

$app->post("/api/delete/{model}", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Master\Controller\DeleteController");
    $result = $controller->deleteData($request, $args);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

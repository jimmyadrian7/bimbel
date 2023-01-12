<?php

$app->post("/api/update/{model}", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Master\Controller\UpdateController");
    $result = $controller->updateData($request, $args);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

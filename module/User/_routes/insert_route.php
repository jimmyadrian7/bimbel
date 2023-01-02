<?php

$app->post("/api/user/role", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\User\Controller\InsertController");
    $result = $controller->insertRole($request, $args);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/role/menu", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\User\Controller\InsertController");
    $result = $controller->insertMenu($request, $args);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

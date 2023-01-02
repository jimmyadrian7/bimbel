<?php

$app->post("/api/user/authenticate", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\User\Controller\UserController");
    $result = $controller->authenticateUser($request, $args);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->get("/api/user/current/login", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\User\Controller\UserController");
    $result = $controller->getCurrentUser($request, $args);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/user/current/logout", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\User\Controller\UserController");
    $result = $controller->logoutCurrentUser($request, $args);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

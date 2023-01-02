<?php

$app->get("/", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Web\Controller\ViewController");
    $result = $controller->homeView($response);

    $response = $response->withHeader("Content-Type", "text/html");
    $response->getBody()->write($result);
    return $response;
});

$app->post("/submit/question", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Web\Controller\QuestionController");
    $result = $controller->submitQuestion($request);

    $response = $response->withHeader("Content-Type", "text/html");
    $response->getBody()->write($result);
    return $response;
});
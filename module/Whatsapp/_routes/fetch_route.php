<?php

$app->get("/api/get/WA/template", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Whatsapp\Controller\FetchController");
    $result = $controller->getTemplate($request, $args);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->get("/api/get/WA/autocomplete", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Whatsapp\Controller\FetchController");
    $result = $controller->getTemplateAutocomplete($request, $args);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});
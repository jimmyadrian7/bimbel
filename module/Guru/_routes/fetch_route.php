<?php

$app->get("/api/guru/search/autocomplete", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Guru\Controller\FetchController");
    $result = $controller->getGuru($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->get("/api/guru/data/siswa/{guru_id}", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Guru\Controller\FetchController");
    $result = $controller->getSiswaGuru($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->get("/api/guru/available", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Guru\Controller\FetchController");
    $result = $controller->getGuruAvailable($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->get("/api/asisten_guru/search/autocomplete", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Guru\Controller\FetchController");
    $result = $controller->getAsistenGuru($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

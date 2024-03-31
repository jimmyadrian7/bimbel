<?php

$app->get("/api/generate/report/siswa", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\SiswaController");
    $result = $controller->getSiswa($response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});

$app->get("/api/generate/report/jadwal", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\SiswaController");
    $result = $controller->getJadwal($response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});

$app->post("/api/generate/report/siswa/utang", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\SiswaController");
    $result = $controller->getSiswaUtang($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});
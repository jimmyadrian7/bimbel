<?php

$app->get("/api/generate/report/siswa", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\SiswaController");
    $result = $controller->getSiswa();

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});

$app->get("/api/generate/report/jadwal", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\SiswaController");
    $result = $controller->getJadwal();

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});
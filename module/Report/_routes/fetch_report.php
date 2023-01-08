<?php

$app->post("/api/generate/report/labarugi", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\ReportController");
    $result = $controller->getLabaRugi($request, $args);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});

$app->post("/api/generate/report/gaji", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\ReportController");
    $result = $controller->getGaji($request, $args);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});

$app->post("/api/generate/report/pendapatan", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\ReportController");
    $result = $controller->getPendapatan($request, $args);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});

$app->post("/api/generate/report/pengeluaran", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\ReportController");
    $result = $controller->getPengeluaran($request, $args);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});

$app->post("/api/generate/report/deposit", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\ReportController");
    $result = $controller->getDeposit($request, $args);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});
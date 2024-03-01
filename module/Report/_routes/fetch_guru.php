<?php


$app->post("/api/generate/report/pendapatan/guru", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\ReportGuruController");
    $result = $controller->getPendapatan($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});

$app->post("/api/generate/report/iuran", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\ReportGuruController");
    $result = $controller->getIuran($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});
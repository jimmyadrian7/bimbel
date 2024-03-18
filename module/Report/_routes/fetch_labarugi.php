<?php

$app->post("/api/generate/report/labarugi/tahunan", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\ReportLabaRugiController");
    $result = $controller->getTahunan($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});
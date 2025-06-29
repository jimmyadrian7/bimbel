<?php

$app->post("/api/asisten_guru/generate/slip/gaji", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\ReportAsistenGuruController");
    $result = $controller->getSlipGaji($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});
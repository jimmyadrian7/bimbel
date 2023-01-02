<?php

$app->post("/api/guru/generate/gaji", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Pengeluaran\Controller\GajiController");
    $result = $controller->generateGaji($request, $args);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

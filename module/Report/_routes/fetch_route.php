<?php

$app->post("/api/generate/report/pendapatan", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\PendapatanController");
    $result = $controller->getReport($request, $args);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});

$app->post("/api/generate/report/gaji", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\GajiController");
    $result = $controller->getReport($request, $args);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});

$app->post("/api/generate/report/pengeluaran", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\PengeluaranController");
    $result = $controller->getReport($request, $args);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});

$app->post("/api/generate/report/deposit", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\DepositController");
    $result = $controller->getReport($request, $args);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});

$app->get("/api/generate/report/invoice/{tagihan_id}", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\InvoiceController");
    $result = $controller->getReport($request, $args);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});

$app->get("/api/generate/report/tabungan_aset/invoice/{tabungan_aset_id}", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\TabunganAsetController");
    $result = $controller->getReport($request, $args);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});
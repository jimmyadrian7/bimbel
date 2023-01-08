<?php

$app->get("/api/generate/report/invoice/{tagihan_id}", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\InvoiceController");
    $result = $controller->getTagihan($request, $args);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});

$app->get("/api/generate/report/tabungan_aset/invoice/{tabungan_aset_id}", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\InvoiceController");
    $result = $controller->getTabunganAset($request, $args);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});
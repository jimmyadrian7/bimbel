<?php

$app->get("/api/notify/tagihan/wa/{tagihan_id}", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Pembayaran\Controller\NotifyController");
    $result = $controller->notifyInvoice($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->get("/api/fix/data/old/tagihan", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Pembayaran\Controller\NotifyController");
    $result = $controller->fixData($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->get("/api/fix/data/old/guru", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Pembayaran\Controller\NotifyController");
    $result = $controller->fixDataGuru($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

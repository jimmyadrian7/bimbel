<?php

$app->post("/api/tagihan/ganti/guru", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Pembayaran\Controller\TagihanController");
    $result = $controller->gantiGuru($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});
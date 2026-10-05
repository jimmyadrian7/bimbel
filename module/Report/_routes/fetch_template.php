<?php

// Editable kwitansi template (Konfigurasi > Template Kwitansi). Super Admin only.

$app->get("/api/report/template/kwitansi", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\KwitansiTemplateController");
    $result = $controller->getState($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});

$app->post("/api/report/template/kwitansi/preview", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\KwitansiTemplateController");
    $result = $controller->preview($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});

$app->post("/api/report/template/kwitansi/html", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\KwitansiTemplateController");
    $result = $controller->html($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});

$app->post("/api/report/template/kwitansi/save", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\KwitansiTemplateController");
    $result = $controller->save($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});

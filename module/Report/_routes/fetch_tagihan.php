<?php

$app->post("/api/generate/report/tagihan_list", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\TagihanExportController");
    $result = $controller->exportPdf($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});

$app->post("/api/generate/report/tagihan_list/excel", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\TagihanExportController");
    $result = $controller->exportExcel($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});

$app->post("/api/generate/report/tagihan_preview", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\TagihanExportController");
    $result = $controller->exportPreviewPdf($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});

$app->post("/api/generate/report/tagihan_preview/excel", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Report\Controller\TagihanExportController");
    $result = $controller->exportPreviewExcel($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write($result);
    return $response;
});

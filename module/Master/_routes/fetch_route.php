<?php

$app->get("/api/{models}", function ($request, $response, $args) {
    $controller = $this->get("Bimbel\Master\Controller\FetchController");
    $datas = $controller->fetchDatas($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($datas));
    return $response;
});

$app->get("/api/{model}/{model_id}", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Master\Controller\FetchController");
    $agama = $controller->fetchData($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($agama));
    return $response;
});

$app->post("/api/{models}/custom", function ($request, $response, $args) {
    $controller = $this->get("Bimbel\Master\Controller\FetchController");
    $datas = $controller->fetchCustomDatas($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($datas));
    return $response;
});
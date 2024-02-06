<?php

$app->get("/api/siswa/search/autocomplete", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Siswa\Controller\FetchController");
    $result = $controller->getSiswa($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->get("/api/kursus/search/autocomplete", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Siswa\Controller\FetchController");
    $result = $controller->getKursus($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
}); 

$app->post("/api/siswa/generate/tagihan", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Siswa\Controller\FetchController");
    $result = $controller->generateTagihan($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/siswa/mass/generate/tagihan", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Siswa\Controller\FetchController");
    $result = $controller->massGenerateTagihan($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

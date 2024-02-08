<?php

$app->get("/api/notify/tagihan/wa/{tagihan_id}", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Pembayaran\Controller\NotifyController");
    $result = $controller->notifyInvoice($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

// $app->get("/api/hapus/gaji/guru", function ($request, $response, $args) {

//     $controller = $this->get("Bimbel\Pembayaran\Controller\NotifyController");
//     $result = $controller->hapusGaji($request, $args, $response);

//     $response = $response->withHeader("Content-Type", "application/json");
//     $response->getBody()->write(json_encode($result));
//     return $response;
// });

// $app->get("/api/fix/data/old/tagihan", function ($request, $response, $args) {

//     $controller = $this->get("Bimbel\Pembayaran\Controller\NotifyController");
//     $result = $controller->fixData($request, $args, $response);

//     $response = $response->withHeader("Content-Type", "application/json");
//     $response->getBody()->write(json_encode($result));
//     return $response;
// });

// $app->get("/api/fix/data/old/guru", function ($request, $response, $args) {

//     $controller = $this->get("Bimbel\Pembayaran\Controller\NotifyController");
//     $result = $controller->fixDataGuru($request, $args, $response);

//     $response = $response->withHeader("Content-Type", "application/json");
//     $response->getBody()->write(json_encode($result));
//     return $response;
// });

// $app->get("/api/reset/data/siswa", function ($request, $response, $args) {

//     $controller = $this->get("Bimbel\Pembayaran\Controller\NotifyController");
//     $result = $controller->resetDataSiswa($request, $args, $response);

//     $response = $response->withHeader("Content-Type", "application/json");
//     $response->getBody()->write(json_encode($result));
//     return $response;
// });

// $app->get("/api/delete/data/pembayaran", function ($request, $response, $args) {

//     $controller = $this->get("Bimbel\Pembayaran\Controller\NotifyController");
//     $result = $controller->deleteDataPembayaran($request, $args, $response);

//     $response = $response->withHeader("Content-Type", "application/json");
//     $response->getBody()->write(json_encode($result));
//     return $response;
// });

// $app->get("/api/fix/tanggal/iuran", function ($request, $response, $args) {

//     $controller = $this->get("Bimbel\Pembayaran\Controller\NotifyController");
//     $result = $controller->fixTanggalIuran($request, $args, $response);

//     $response = $response->withHeader("Content-Type", "application/json");
//     $response->getBody()->write(json_encode($result));
//     return $response;
// });


$app->post("/api/reset/sequance/tagihan", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Pembayaran\Controller\NotifyController");
    $result = $controller->resetSequanceTagihan($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/fix/data/tagihan", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Pembayaran\Controller\NotifyController");
    $result = $controller->fixDataTagihan($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/add/field/keluar/deposit", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Pembayaran\Controller\NotifyController");
    $result = $controller->addFieldKeluarDeposit($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/add/field/sequance-pendaftaran/kursus", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Pembayaran\Controller\NotifyController");
    $result = $controller->addFieldSequancePendaftaranKursus($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/update/no/formulir/siswa", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Pembayaran\Controller\NotifyController");
    $result = $controller->updateNoFormulirSiswa($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/add/field/youtube/web", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Pembayaran\Controller\NotifyController");
    $result = $controller->addFieldYouTubeWeb($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/add/field/phone/web", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\Pembayaran\Controller\NotifyController");
    $result = $controller->addFieldPhoneWeb($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});
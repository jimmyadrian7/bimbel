<?php

// $app->get("/api/hapus/gaji/guru", function ($request, $response, $args) {

//     $controller = $this->get("Bimbel\FixData\Controller\FixDataController");
//     $result = $controller->hapusGaji($request, $args, $response);

//     $response = $response->withHeader("Content-Type", "application/json");
//     $response->getBody()->write(json_encode($result));
//     return $response;
// });

// $app->get("/api/fix/data/old/tagihan", function ($request, $response, $args) {

//     $controller = $this->get("Bimbel\FixData\Controller\FixDataController");
//     $result = $controller->fixData($request, $args, $response);

//     $response = $response->withHeader("Content-Type", "application/json");
//     $response->getBody()->write(json_encode($result));
//     return $response;
// });

// $app->get("/api/fix/data/old/guru", function ($request, $response, $args) {

//     $controller = $this->get("Bimbel\FixData\Controller\FixDataController");
//     $result = $controller->fixDataGuru($request, $args, $response);

//     $response = $response->withHeader("Content-Type", "application/json");
//     $response->getBody()->write(json_encode($result));
//     return $response;
// });

// $app->get("/api/reset/data/siswa", function ($request, $response, $args) {

//     $controller = $this->get("Bimbel\FixData\Controller\FixDataController");
//     $result = $controller->resetDataSiswa($request, $args, $response);

//     $response = $response->withHeader("Content-Type", "application/json");
//     $response->getBody()->write(json_encode($result));
//     return $response;
// });

// $app->get("/api/delete/data/pembayaran", function ($request, $response, $args) {

//     $controller = $this->get("Bimbel\FixData\Controller\FixDataController");
//     $result = $controller->deleteDataFixData($request, $args, $response);

//     $response = $response->withHeader("Content-Type", "application/json");
//     $response->getBody()->write(json_encode($result));
//     return $response;
// });

// $app->get("/api/fix/tanggal/iuran", function ($request, $response, $args) {

//     $controller = $this->get("Bimbel\FixData\Controller\FixDataController");
//     $result = $controller->fixTanggalIuran($request, $args, $response);

//     $response = $response->withHeader("Content-Type", "application/json");
//     $response->getBody()->write(json_encode($result));
//     return $response;
// });


$app->post("/api/reset/sequance/tagihan", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\FixData\Controller\FixDataController");
    $result = $controller->resetSequanceTagihan($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/fix/data/tagihan", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\FixData\Controller\FixDataController");
    $result = $controller->fixDataTagihan($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/add/field/keluar/deposit", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\FixData\Controller\FixDataController");
    $result = $controller->addFieldKeluarDeposit($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/add/field/sequance-pendaftaran/kursus", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\FixData\Controller\FixDataController");
    $result = $controller->addFieldSequancePendaftaranKursus($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/update/no/formulir/siswa", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\FixData\Controller\FixDataController");
    $result = $controller->updateNoFormulirSiswa($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/add/field/youtube/web", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\FixData\Controller\FixDataController");
    $result = $controller->addFieldYouTubeWeb($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/add/field/phone/web", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\FixData\Controller\FixDataController");
    $result = $controller->addFieldPhoneWeb($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/add/menu/iuran", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\FixData\Controller\FixDataController");
    $result = $controller->addMenuIuran($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/add/table/riwayat/penarikan", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\FixData\Controller\FixDataController");
    $result = $controller->addTableRiwayatPenarikan($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/patch/1/3", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\FixData\Controller\Patch1Controller");
    $result = $controller->patch3($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/patch/1/4", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\FixData\Controller\Patch1Controller");
    $result = $controller->patch4($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/patch/1/5", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\FixData\Controller\Patch1Controller");
    $result = $controller->patch5($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/patch/1/6", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\FixData\Controller\Patch1Controller");
    $result = $controller->patch6($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/fix/data/iuran", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\FixData\Controller\FixDataIuranController");
    $result = $controller->fixDataIuran($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/patch/1/7", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\FixData\Controller\Patch1Controller");
    $result = $controller->patch7($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});

$app->post("/api/patch/1/8", function ($request, $response, $args) {

    $controller = $this->get("Bimbel\FixData\Controller\Patch1Controller");
    $result = $controller->patch8($request, $args, $response);

    $response = $response->withHeader("Content-Type", "application/json");
    $response->getBody()->write(json_encode($result));
    return $response;
});
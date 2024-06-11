<?php
require '../vendor/autoload.php';
include_once "./JWTToken.php";
header('Access-Control-Allow-Origin: *'); // Allows all origins
header('Content-Type: application/json'); // Indicates JSON response
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // Allows these methods
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With, Authorization'); // Explicitly allows these headers


use Firebase\JWT\JWT;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Return early for OPTIONS method, which is used for CORS preflight
    http_response_code(200);
    exit();
}

if (isset($_REQUEST["action"])) {
    $action = $_REQUEST['action'];
    if ($action == 'dangNhap') {
        $input = json_decode(file_get_contents('php://input'), true);
        $taiKhoan = $input['taiKhoan'] ?? '';
        $matKhau = $input['matKhau'] ?? '';
        $thoiGian = $input['thoiGian'] ?? '';
    }
    switch ($action) {
        case 'dangNhap':
            dangNhap($taiKhoan, $matKhau, $thoiGian);
            break;
        case 'layThongTin':
            layThongTin();
            break;
    }
}

function dangNhap($taiKhoan, $matKhau, $thoiGian)
{
    include('../controller/dangNhap.php');
    $p = new ControllDangNhap();
    $res = $p->dangNhap($taiKhoan, $matKhau);
    if (!$res) {
        echo json_encode($res);
        return;
    }
    $res = createJWT($res, $thoiGian);
    echo json_encode($res);
    return;
}



function layThongTin()
{
    include('../controller/dangNhap.php');
    $p = new ControllDangNhap();
    $decoded = validateToken();
    if (!$decoded) {
        return;
    }
    $data = json_decode(file_get_contents('php://input'), true);
    $maTaiKhoan = $data['maTaiKhoan'];
    $vaiTro = $data['vaiTro'];
    if ($vaiTro == 0) {
        $fullRes = $p->layThongTinSinhVien($maTaiKhoan);
        echo json_encode($fullRes);
        return;
    }
    $fullRes = $p->layThongTinGiangVien($maTaiKhoan);
    echo json_encode($fullRes);
    return;
}

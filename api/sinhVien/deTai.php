
<?php

use ControllerSinhVien\ControlSinhVienDeTai;

require_once '../../vendor/autoload.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Stops script processing and sends the headers if the request method is OPTIONS
    exit;
}

// Router function to handle request
function route($method, $resource, $data)
{
    $controller = new ControlSinhVienDeTai();
    try {
        switch ($method) {
            case 'GET':
                if ($resource === 'deTai') {
                    echo json_encode($controller->layDanhSachDeTai());
                } else if ($resource === 'huongDanTheoSinhVien') {
                    echo json_encode($controller->layHuongDanTheoSinhVien($data));
                } else if ($resource === 'thongTinDoAn') {
                    echo json_encode($controller->layThongTinDoAn());
                } else if ($resource === 'thongTinThanhVien') {
                    echo json_encode($controller->layThongTinThanhVien());
                } else if ($resource === 'danhSachLoiMoi') {
                    echo json_encode($controller->layDanhSachLoiMoi());
                } else if ($resource === 'danhSachTaiLieu') {
                    echo json_encode($controller->layDanhSachTaiLieu());
                } else if ($resource === 'thongTinDiemDanh') {
                    echo json_encode($controller->layThongTinDiemDanh());
                } else {
                    echo json_encode(['error' => 'Resource not found']);
                }
                break;
            case 'POST':
                if ($resource === 'huongDanTheoSinhVien') {
                    echo json_encode($controller->hoanThanhHuongDan($data));
                } else if ($resource === 'dangKyDeTai') {
                    echo json_encode($controller->dangKyDoAn($data));
                } else if ($resource === 'huyDangKyDoAn') {
                    echo json_encode($controller->huyDangKyDoAn($data));
                } else if ($resource === 'guiLoiMoiThamGiaNhom') {
                    echo json_encode($controller->guiLoiMoiThamGiaNhom($data));
                } else if ($resource === 'thamGiaDoAn') {
                    echo json_encode($controller->thamGiaDoAn($data));
                } else if ($resource === 'themTaiLieu') {
                    echo json_encode($controller->themTaiLieu($data));
                } else if ($resource === 'ghiNhanDiemDanh') {
                    echo json_encode($controller->ghiNhanDiemDanh($data));
                } else if ($resource === 'capNhatAnhDaiDien') {
                    echo json_encode($controller->capNhatAnhDaiDien($data));
                } else {
                    echo json_encode(['error' => 'Resource not found']);
                }
                break;
            default:
                echo json_encode(['error' => 'Method not allowed']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Main request handling
$method = $_SERVER['REQUEST_METHOD'];
$resource = isset($_GET['resource']) ? $_GET['resource'] : '';
$data = getDataFromBody();

function getDataFromBody()
{
    if (!empty($_FILES)) {
        $data = $_POST;
        if (isset($_FILES['file'])) {
            $data['file_info'] = $_FILES['file'];
        } else if (isset($_FILES['hinhanh'])) {
            $data['file_info'] = $_FILES['hinhanh'];
        }
        return $data;
    } else {
        return json_decode(file_get_contents('php://input'), true);
    }
}

route($method, $resource, $data);

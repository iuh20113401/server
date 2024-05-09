<?php

use ControllerSinhVien\ControlSinhVienDeTai;

require_once '../../vendor/autoload.php';

// Load CORS headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

// Handle OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// Create an instance of the controller
$controller = new ControlSinhVienDeTai();

// Main request handling
$method = $_SERVER['REQUEST_METHOD'];
$resource = isset($_GET['resource']) ? $_GET['resource'] : '';
$data = getDataFromBody();

// Route the request
route($method, $resource, $data);

// Router function to handle request
function route($method, $resource, $data)
{
    global $controller;
    try {
        switch ($method) {
            case 'GET':
                handleGETRequest($resource);
                break;
            case 'POST':
                handlePOSTRequest($resource, $data);
                break;
            default:
                http_response_code(405); // Method Not Allowed
                echo json_encode(['error' => 'Method Not Allowed']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// Handle GET request
function handleGETRequest($resource)
{
    global $controller;
    switch ($resource) {
        case 'deTai':
            echo json_encode($controller->layDanhSachDeTai());
            break;
        case 'huongDanTheoSinhVien':
            echo json_encode($controller->layHuongDanTheoSinhVien());
            break;
        case 'thongTinDoAn':
            echo json_encode($controller->layThongTinDoAn());
            break;
        case 'thongTinThanhVien':
            echo json_encode($controller->layThongTinThanhVien());
            break;
        case 'danhSachLoiMoi':
            echo json_encode($controller->layDanhSachLoiMoi());
            break;
        case 'danhSachTaiLieu':
            echo json_encode($controller->layDanhSachTaiLieu());
            break;
        case 'thongTinDiemDanh':
            echo json_encode($controller->layThongTinDiemDanh());
            break;
        default:
            http_response_code(404); // Not Found
            echo json_encode(['error' => 'Resource not found']);
    }
}

// Handle POST request
function handlePOSTRequest($resource, $data)
{
    global $controller;
    switch ($resource) {
        case 'huongDanTheoSinhVien':
            echo json_encode($controller->hoanThanhHuongDan($data));
            break;
        case 'dangKyDeTai':
            echo json_encode($controller->dangKyDoAn($data));
            break;
        case 'huyDangKyDoAn':
            echo json_encode($controller->huyDangKyDoAn($data));
            break;
        case 'guiLoiMoiThamGiaNhom':
            echo json_encode($controller->guiLoiMoiThamGiaNhom($data));
            break;
        case 'thamGiaDoAn':
            echo json_encode($controller->thamGiaDoAn($data));
            break;
        case 'themTaiLieu':
            echo json_encode($controller->themTaiLieu($data));
            break;
        case 'ghiNhanDiemDanh':
            echo json_encode($controller->ghiNhanDiemDanh($data));
            break;
        case 'capNhatAnhDaiDien':
            echo json_encode($controller->capNhatAnhDaiDien($data));
            break;
        default:
            http_response_code(404); // Not Found
            echo json_encode(['error' => 'Resource not found']);
    }
}

// Get request body data
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

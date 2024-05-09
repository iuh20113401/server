<?php
require_once '../../vendor/autoload.php';

use ControllerGiangVien\ControlQuanLyDeTai;

// Load CORS headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

// Create instance of the controller
$controller = new ControlQuanLyDeTai();

// Handle OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// Main request handling
$method = $_SERVER['REQUEST_METHOD'];

// Extract resource from URL
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parts = explode('/', $path);
$resource = isset($_REQUEST['resource']) ? $_REQUEST['resource'] : '';

// Handle request based on HTTP method and resource
switch ($method) {
    case 'GET':
        handleGETRequest($resource);
        break;
    case 'POST':
        handlePOSTRequest($resource);
        break;
        // Add handling for PUT and DELETE if needed
    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(['error' => 'Method Not Allowed']);
}

// Handle GET request
function handleGETRequest($resource)
{
    global $controller;
    switch ($resource) {
        case 'sinhVien':
            echo json_encode($controller->layDanhSachSinhVien());
            break;
        case 'doAn':
            echo json_encode($controller->layDanhSachDoAn());
            break;
        case 'thongTinDoAn':
            echo json_encode($controller->layThongTinDoAn());
            break;
        case 'thongTinThanhVien':
            echo json_encode($controller->layThongTinThanhVien());
            break;
        case 'diemQuaTrinh':
            echo json_encode($controller->layDanhSachDiemQuaTrinh());
            break;
        case 'phanBien':
            echo json_encode($controller->layDanhSachPhanBien());
            break;
        case 'huongDanDoAn':
            echo json_encode($controller->layDanhSachHuongDanChoDoAn());
            break;
        default:
            echo json_encode(['error' => 'Resource not found']);
    }
}

// Handle POST request
function handlePOSTRequest($resource)
{
    global $controller;
    $data = getDataFromBody();
    switch ($resource) {
        case 'chamDiemGiuaKy':
            echo json_encode($controller->chamDiemGiuaKy($data));
            break;
        case 'chamDiemCuoiKy':
            echo json_encode($controller->chamDiemCuoiKy($data));
            break;
        case 'chamDiemPhanBien1':
            echo json_encode($controller->chamDiemPhanBien1($data));
            break;
        case 'chamDiemPhanBien2':
            echo json_encode($controller->chamDiemPhanBien2($data));
            break;
        case 'duyetPhanBien':
            echo json_encode($controller->duyetPhanBien($data));
            break;
            // Add more cases for other POST actions
        default:
            echo json_encode(['error' => 'Invalid action']);
    }
}

// Get request body data
function getDataFromBody()
{
    if (!empty($_FILES)) {
        $data = $_POST;
        $data['file_info'] = $_FILES['hinhanh'];
        return $data;
    } else {
        return json_decode(file_get_contents('php://input'), true);
    }
}

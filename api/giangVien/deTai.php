<?php

use ControllerGiangVien\ControlQuanLyDeTai;

include_once '../JWTToken.php';
require_once '../../vendor/autoload.php';

// Load CORS headers
header('Access-Control-Allow-Origin: *'); // Allows all origins
header('Content-Type: application/json'); // Indicates JSON response
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // Allows these methods
header('Access-Control-Allow-Headers: Authorization, Content-Type, Accept, X-Requested-With'); // Explicitly allows these headers

// Handle OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// Router function to handle request
function route($method, $resource, $data)
{
    $controller = new ControlQuanLyDeTai();
    try {
        switch ($method) {
            case 'GET':
                handleGETRequest($resource, $controller);
                break;
            case 'POST':
                handlePOSTRequest($resource, $data, $controller);
                break;
            case 'PUT':
                handlePUTRequest($resource, $data, $controller);
                break;
            case 'DELETE':
                handleDELETERequest($resource, $data, $controller);
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
if (!isset($_SERVER['REQUEST_METHOD'])) {
    echo json_encode(['error' => 'No method specified']);
} else {
    $method = $_SERVER['REQUEST_METHOD'];
    $decoded = validateToken();
    if (!$decoded) {
        return;
    }
    $resource = $_GET['resource'] ?? '';
    $data = getDataFromBody();
    route($method, $resource, $data);
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

// Handle GET request
function handleGETRequest($resource, $controller)
{
    switch ($resource) {
        case 'deTai':
            echo json_encode($controller->layDanhSachDeTai());
            break;
        case "deTaiTheoMa":
            echo json_encode($controller->layDeTai());
            break;
        case 'huongDan':
            echo json_encode($controller->layDanhSachHuongDan());
            break;
        case 'lichHop':
            echo json_encode($controller->layDanhSachLichHop());
            break;
        case 'danhMuc':
            echo json_encode($controller->layDanhSachDanhMuc());
            break;
        case 'danhSachDeTaiDaDangKy':
            echo json_encode($controller->layDanhSachDeTaiDaDangKy());
            break;
        default:
            http_response_code(405); // Not Found
            echo json_encode(['error' => 'Invalid resource']);
    }
}

// Handle POST request
function handlePOSTRequest($resource, $data, $controller)
{
    switch ($resource) {
        case 'deTai':
            echo json_encode($controller->themDeTai($data));
            break;
        case 'capNhatAnhDaiDien':
            echo json_encode($controller->capNhatAnhDaiDien($data));
            break;
        case 'huongDan':
            echo json_encode($controller->themHuongDan($data));
            break;
        case 'huongDanDoAn':
        case 'huongDanDeTai':
            handleDetailedGuidance($resource, $data, $controller);
            break;
        case 'lichHop':
            echo json_encode($controller->themLichHopVaoDeTai($data));
            break;
        case "lichHopDoAn":
            echo json_encode($controller->themLichHopVaoDoAn($data));
            break;
        case 'danhMuc':
            echo json_encode($controller->themDanhMuc($data));
            break;
        case 'maDiemDanh':
            echo json_encode($controller->themMaDiemDanh($data));
            break;
        case 'thongKeDeTai':
            echo json_encode($controller->thongKeDeTai($data));
            break;
        default:
            http_response_code(405); // Not Found

            echo json_encode(['error' => 'Invalid resource']);
    }
}

// Handle PUT request
function handlePUTRequest($resource, $data, $controller)
{
    switch ($resource) {
        case 'thongTinGiangVien':
            echo json_encode($controller->suaThongTinGiangVien($data));
            break;
        case 'deTai':
            echo json_encode($controller->suaDeTai($data));
            break;
        case 'huongDanVaoDeTai':
            echo json_encode($controller->themHuongDanVaoDeTai($data['maDeTai'], $data['maHuongDan']));
            break;
        default:
            http_response_code(405); // Not Found
            echo json_encode(['error' => 'Invalid resource']);
    }
}

// Handle DELETE request
function handleDELETERequest($resource, $data, $controller)
{
    switch ($resource) {
        case 'deTai':
            echo json_encode($controller->xoaDeTai($data['maDeTai']));
            break;
        default:
            http_response_code(405); // Not Found
            echo json_encode(['error' => 'Invalid resource']);
    }
}

// Handle specific cases for detailed guidance
function handleDetailedGuidance($resource, $data, $controller)
{
    if ($resource === 'huongDanDeTai') {
        echo json_encode($controller->themHuongDanVaoDeTai($data['selectedTopics'], $data['maHuongDan']));
    } else if ($resource === 'huongDanDoAn') {
        echo json_encode($controller->themHuongDanVaoDoAn($data['selectedTopics'], $data['maHuongDan']));
    }
}

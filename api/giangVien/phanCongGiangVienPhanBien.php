<?php

require_once '../../vendor/autoload.php';

use ControllerGiangVien\ControlQuanLyDeTai;

// Load CORS headers
header('Access-Control-Allow-Origin: *'); // Allows all origins
header('Content-Type: application/json'); // Indicates JSON response
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // Allows these methods
header('Access-Control-Allow-Headers: Authorization, Content-Type, Accept, X-Requested-With'); // Explicitly allows these headers
// Create instance of the controller
$controller = new ControlQuanLyDeTai();

// Handle OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

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
        case 'layDanhSachPhanCongPhanBien':
            echo json_encode($controller->layDanhSachPhanCongPhanBien());
            break;
        case 'layDanhSachGiangVien':
            echo json_encode($controller->layDanhSachGiangVien());
            break;
        default:
            echo json_encode(['error' => 'Invalid resource']);
    }
}

// Handle POST request
function handlePOSTRequest($resource, $data)
{
    global $controller;

    switch ($resource) {
        case 'phanGiangVienPhanBien':
            echo json_encode($controller->phanGiangVienPhanBien($data));
            break;
        default:
            echo json_encode(['error' => 'Invalid resource']);
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
    route(
        $method,
        $resource,
        $data
    );
}
// Get request body data
function getDataFromBody()
{
    return json_decode(file_get_contents('php://input'), true);
}

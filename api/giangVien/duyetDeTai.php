<?php

use ControllerGiangVien\ControlQuanLyDeTai;

require_once '../../vendor/autoload.php';

header('Access-Control-Allow-Origin: *'); // Allows all origins
header('Content-Type: application/json'); // Indicates JSON response
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // Allows these methods
header('Access-Control-Allow-Headers: Authorization, Content-Type, Accept, X-Requested-With'); // Explicitly allows these headers
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Stops script processing and sends the headers if the request method is OPTIONS
    exit;
}

// Router function to handle request
function route($method, $resource, $data)
{
    $controller = new ControlQuanLyDeTai();
    try {
        switch ($method) {
            case 'GET':
                if ($resource === 'deTaiChoDuyet') {
                    echo json_encode($controller->layDanhSachDeTaiChoDuyet());
                } else {
                    echo json_encode(['error' => 'Resource not found']);
                }
                break;
            case 'PUT':
                if ($resource === 'duyetDeTai') {
                    echo json_encode($controller->duyetDeTai($data));
                } else if ($resource === 'yeuCauChinhSuaDeTai') {
                    echo json_encode($controller->yeuCauChinhSuaDeTai($data));
                } else if ($resource === 'khongDuyetDeTai') {
                    echo json_encode($controller->khongDuyetDeTai($data));
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

function getDataFromBody()
{
    if (!empty($_FILES)) {
        $data = $_POST;
        if (isset($_FILES['hinhanh'])) {
            $data['file_info'] = $_FILES['hinhanh'];
        }
        return $data;
    } else {
        return json_decode(file_get_contents('php://input'), true);
    }
}

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

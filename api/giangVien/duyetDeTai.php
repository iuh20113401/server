<?php

use ControllerGiangVien\ControlQuanLyDeTai;

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
$method = $_SERVER['REQUEST_METHOD'];
$resource = isset($_GET['resource']) ? $_GET['resource'] : '';
$data = getDataFromBody();

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

route($method, $resource, $data);

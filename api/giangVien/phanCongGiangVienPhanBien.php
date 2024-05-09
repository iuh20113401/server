<?php

require_once '../../vendor/autoload.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

use ControllerGiangVien\ControlQuanLyDeTai;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// Router function to handle request
function route($action)
{
    $controller = new ControlQuanLyDeTai();
    try {
        switch ($action) {
            case 'layDanhSachPhanCongPhanBien':
                echo json_encode($controller->layDanhSachPhanCongPhanBien());
                break;
            case 'phanGiangVienPhanBien':
                echo json_encode($controller->phanGiangVienPhanBien(getDataFromBody()));
                break;
            case 'layDanhSachGiangVien':
                echo json_encode($controller->layDanhSachGiangVien());
                break;
            default:
                echo json_encode(['error' => 'Hành động không phù hợp']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

function getDataFromBody()
{
    return json_decode(file_get_contents('php://input'), true);
}

// Main request handling
if (!isset($_REQUEST['action'])) {
    echo json_encode(['error' => 'No action requested']);
} else {
    route($_REQUEST['action']);
}

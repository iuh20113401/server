<?php
require_once '../vendor/autoload.php';;

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

use ControllerSinhVien\ControlSinhVienDeTai;
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
            case 'themLichHopVaoDeTai':
                echo json_encode($controller->themLichHopVaoDeTai(getDataFromBody()));
                break;
            case 'layDanhSachLichHop':
                handleLayLichHop($action);
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
    if (!empty($_FILES)) {
        // Handle form data with file upload
        $data = $_POST;
        $data['file_info'] = $_FILES['hinhanh'];
        return $data;
    } else {
        return json_decode(file_get_contents('php://input'), true);
    }
}

function handleLayLichHop($action)
{
    $controller = new ControlQuanLyDeTai();
    $sinhVienController = new ControlSinhVienDeTai();
    if ($_GET['loai'] === 'sinhVien') {
        echo json_encode($sinhVienController->layDanhSachLichHop());
    } else if ($_GET['loai'] === 'giangVien') {
        echo json_encode($controller->layDanhSachLichHop());
    }
}

// Main request handling
if (!isset($_REQUEST['action'])) {
    echo json_encode(['error' => 'No action requested']);
} else {
    route($_REQUEST['action']);
}

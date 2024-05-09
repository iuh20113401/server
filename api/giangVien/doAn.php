<?php
require_once '../../vendor/autoload.php';

header('Access-Control-Allow-Origin: *'); // Allows all origins
header('Content-Type: application/json'); // Indicates JSON response
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // Allows these methods
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With'); // Explicitly allows these headers
use ControllerGiangVien\ControlQuanLyDeTai;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Stops script processing and sends the headers if the request method is OPTIONS
    exit;
}


function route($action)
{
    $controller = new ControlQuanLyDeTai();
    try {
        switch ($action) {
            case "layDanhSachSinhVien":
                echo json_encode($controller->layDanhSachSinhVien());
                break;
            case "layDanhSachDoAn":
                echo json_encode($controller->layDanhSachDoAn());
                break;
            case "layThongTinDoAn":
                echo json_encode($controller->layThongTinDoAn());
                break;
            case "layThongTinThanhVien":
                echo json_encode($controller->layThongTinThanhVien());
                break;
            case 'layDanhSachDiemQuaTrinh':
                echo json_encode($controller->layDanhSachDiemQuaTrinh());
                break;
            case 'chamDiemGiuaKy':
                echo json_encode($controller->chamDiemGiuaKy(getDataFromBody()));
                break;
            case 'chamDiemCuoiKy':
                echo json_encode($controller->chamDiemCuoiKy(getDataFromBody()));
                break;
            case 'layDanhSachPhanBien':
                echo json_encode($controller->layDanhSachPhanBien());
                break;
            case 'chamDiemPhanBien1':
                echo json_encode($controller->chamDiemPhanBien1(getDataFromBody()));
                break;
            case 'chamDiemPhanBien2':
                echo json_encode($controller->chamDiemPhanBien2(getDataFromBody()));
                break;
            case 'layDanhSachHuongDanChoDoAn':
                echo json_encode($controller->layDanhSachHuongDanChoDoAn());
                break;
            case 'duyetPhanBien':
                echo json_encode($controller->duyetPhanBien(getDataFromBody()));
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

if (!isset($_REQUEST['action'])) {
    echo json_encode(['error' => 'No action requested']);
} else {
    route($_REQUEST['action']);
}

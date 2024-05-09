
<?php

use ControllerGiangVien\ControlQuanLyDeTai;

require_once '../../vendor/autoload.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// Router function to handle request
function route($method, $resource, $data)
{
    /*case "themMaDiemDanh":
                echo json_encode($controller->themMaDiemDanh(getDataFromBody()));
                break;
            case "layDanhSachDanhMuc":
                echo json_encode($controller->layDanhSachDanhMuc());
                break;
            case "themDanhMuc":
                echo json_encode($controller->themDanhMuc(getDataFromBody()));
                break;
            case "thongKeDeTai":
                echo json_encode($controller->thongKeDeTai(getDataFromBody()));
                break; */
    $controller = new ControlQuanLyDeTai();
    try {
        switch ($method) {
            case 'GET':
                if ($resource === 'deTai') {
                    echo json_encode($controller->layDanhSachDeTai());
                } else if ($resource === 'huongDan') {
                    echo json_encode($controller->layDanhSachHuongDan());
                } else if ($resource === 'lichHop') {
                    echo json_encode($controller->layDanhSachLichHop());
                } else if ($resource === 'danhMuc') {
                    echo json_encode($controller->layDanhSachDanhMuc());
                } else if ("danhSachDeTaiDaDangKy") {
                    echo json_encode($controller->layDanhSachDeTaiDaDangKy());
                } else {
                    echo json_encode(['error' => 'Invalid resource']);
                }
                break;
            case 'POST':
                if ($resource === 'deTai') {
                    echo json_encode($controller->themDeTai($data));
                } else if ($resource === "capNhatAnhDaiDien") {
                    echo json_encode($controller->capNhatAnhDaiDien($data));
                } else if ($resource === 'huongDan') {
                    handleDetailedGuidance($resource, $data, $controller);
                } else if ($resource === 'lichHop') {
                    echo json_encode($controller->themLichHopVaoDeTai($data));
                } else if ($resource === 'danhMuc') {
                    echo json_encode($controller->themDanhMuc($data));
                } else if ($resource === 'maDiemDanh') {
                    echo json_encode($controller->themMaDiemDanh($data));
                } else if ($resource === "thongKeDeTai") {
                    echo json_encode($controller->thongKeDeTai($data));
                } else {
                    echo json_encode(['error' => 'Invalid resource']);
                }
                break;
            case 'PUT':
                if ($resource === 'deTai') {
                    echo json_encode($controller->suaDeTai($data));
                } else if ($resource === 'huongDanVaoDeTai') {
                    echo json_encode($controller->themHuongDanVaoDeTai($data['maDeTai'], $data['maHuongDan']));
                } else {
                    echo json_encode(['error' => 'Invalid resource']);
                }
                break;
            case 'DELETE':
                if ($resource === 'deTai') {
                    echo json_encode($controller->xoaDeTai($data['maDeTai']));
                } else {
                    echo json_encode(['error' => 'Invalid resource']);
                }
                break;
            default:
                echo json_encode(['error' => 'Invalid method']);
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
    $resource = $_GET['resource'] ?? '';
    $data = getDataFromBody();
    route($method, $resource, $data);
}
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
function handleDetailedGuidance($resource, $data, $controller)
{
    if ($resource === 'huongDanDeTai') {
        echo json_encode($controller->themHuongDanVaoDeTai($data['maDeTai'], $data['maHuongDan']));
    } else if ($resource === 'huongDanDoAn') {
        echo json_encode($controller->themHuongDanVaoDoAn($data['maDoAn'], $data['maHuongDan']));
    }
}

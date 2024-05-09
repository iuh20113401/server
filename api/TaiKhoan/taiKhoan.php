
<?php

use ConTrolTaiKhoan\ControlQuanLyTaiKhoan;

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
function route($action)
{
    $controller = new ControlQuanLyTaiKhoan();
    try {
        switch ($action) {
            case 'taiKhoan':
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    echo json_encode($controller->xemDanhSachTaiKhoan());
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    echo json_encode($controller->themTaiKhoan(getDataFromBody()));
                } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                    echo json_encode($controller->suaTaiKhoan(getDataFromBody()));
                } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                    echo json_encode($controller->xoaTaiKhoan(getDataFromBody()));
                } else {
                    echo json_encode(['error' => 'Hành động không phù hợp']);
                }
                break;
            case 'kiemTraMaTaiKhoanTonTai':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    echo json_encode($controller->kiemTraMaTaiKhoanTonTai(getDataFromBody()));
                }
                break;
            case 'sinhVien':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    echo json_encode($controller->themSinhVien(getDataFromBody()));
                } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                    echo json_encode($controller->suaSinhVien(getDataFromBody()));
                } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                    echo json_encode($controller->xoaSinhVien(getDataFromBody()));
                } else {
                    echo json_encode(['error' => 'Hành động không phù hợp']);
                }
                break;
            case 'giangVien':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    echo json_encode($controller->themGiangVien(getDataFromBody()));
                } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                    echo json_encode($controller->suaGiangVien(getDataFromBody()));
                } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                    echo json_encode($controller->xoaGiangVien(getDataFromBody()));
                } else {
                    echo json_encode(['error' => 'Hành động không phù hợp']);
                }
                break;
            case 'danhSachSinhVien':
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    echo json_encode($controller->layDanhSachSinhVien());
                } else {
                    echo json_encode(['error' => 'Hành động không phù hợp']);
                }
                break;
            case 'danhSachGiangVien':
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    echo json_encode($controller->layDanhSachGiangVien());
                } else {
                    echo json_encode(['error' => 'Hành động không phù hợp']);
                }
                break;
            case 'nhieuTaiKhoan':
                if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                    echo json_encode($controller->suaNhieuTaiKhoan(getDataFromBody()));
                } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                    echo json_encode($controller->xoaNhieuTaiKhoan(getDataFromBody()));
                } else {
                    echo json_encode(['error' => 'Hành động không phù hợp']);
                }
                break;
            case 'nhieuSinhVien':
                if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                    echo json_encode($controller->xoaNhieuSinhVien(getDataFromBody()));
                } else {
                    echo json_encode(['error' => 'Hành động không phù hợp']);
                }
                break;
            case 'nhieuGiangVien':
                if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                    echo json_encode($controller->xoaNhieuGiangVien(getDataFromBody()));
                } else {
                    echo json_encode(['error' => 'Hành động không phù hợp']);
                }
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
        $data = $_POST;
        $data['file_info'] = $_FILES;
        return $data;
    } else {
        return json_decode(file_get_contents('php://input'), true);
    }
}

// Main request handling
if (!isset($_REQUEST['action'])) {
    echo json_encode(['error' => 'No action requested']);
} else {
    route($_REQUEST['action']);
}

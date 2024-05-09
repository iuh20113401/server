<?php
require_once '../../vendor/autoload.php';

header('Access-Control-Allow-Origin: *'); // Allows all origins
header('Content-Type: application/json'); // Indicates JSON response
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // Allows these methods
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With'); // Explicitly allows these headers

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Stops script processing and sends the headers if the request method is OPTIONS
    exit;
}

include("../../controller/comment/comment.php");

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

switch ($method) {
    case 'GET':
        if ($action === 'layDanhSachComment') {
            layDanhSachComment();
        } else {
            echo json_encode(['error' => 'Hành động không phù hợp']);
        }
        break;
    case 'POST':
        if ($action === 'themComment') {
            themComment();
        } else {
            echo json_encode(['error' => 'Hành động không phù hợp']);
        }
        break;
    default:
        echo json_encode(['error' => 'Phương thức không được hỗ trợ']);
}

function layDanhSachComment()
{
    if (!isset($_REQUEST['maDoAn'])) {
        echo json_encode(['error' => 'Thiếu thông tin maDoAn']);
        return;
    }

    $p = new ControlComment();
    $res = $p->layDanhSachComment($_REQUEST['maDoAn']);
    echo json_encode($res);
}

function themComment()
{
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['maDoAn']) || !isset($data['nguoiGui']) || !isset($data['noiDung'])) {
        echo json_encode(['error' => 'Thiếu thông tin maDoAn, nguoiGui hoặc noiDung']);
        return;
    }

    $p = new ControlComment();
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $data['ngay'] = date('Y-m-d');
    $data['gio'] = date('H:i:s');
    $res = $p->themComment($data['maDoAn'], $data['nguoiGui'], $data['noiDung'], $data['ngay'], $data['gio']);
    echo json_encode($res);
}

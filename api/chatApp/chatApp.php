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

include('../../controller/chat/chatApp.php');

if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
    switch ($action) {
        case 'getChat':
            getChat();
            break;
        case 'layDanhSachLienLac':
            layDanhSachLienLac();
            break;
        case 'sendChat':
            sendChat();
            break;
        case 'layDanhSachLienLacChoGiangVien':
            layDanhSachLienLacChoGiangVien();
            break;
        default:
            echo json_encode(['error' => 'Hành động không phù hợp']);
    }
} else {
    echo json_encode(['error' => 'Không có hành động nào được yêu cầu']);
}

function getChat()
{
    $p = new ControlChatApp();

    $nguoigui = $_REQUEST['nguoiGui'];
    $nguoinhan = $_REQUEST['nguoiNhan'];
    $res = $p->getChat($nguoigui, $nguoinhan);
    $arr = array();
    foreach ($res as $row) {
        $item = array(
            'maTinNhan' => $row['MaTinNhan'],
            'nguoiGui' => $row['NguoiGui'],
            'nguoiNhan' => $row['NguoiNhan'],
            'noiDung' => $row['NoiDung'],
            'ngayGui' => $row['NgayGui']
        );
        $arr[] = $item;
    }
    echo json_encode($arr);
}

function layDanhSachLienLac()
{
    $maSinhVien = $_REQUEST['maSinhVien'];
    $p = new ControlChatApp();
    $res = $p->getContactableUsers($maSinhVien);
    echo json_encode($res);
}

function sendChat()
{
    $p = new ControlChatApp();
    $data = json_decode(file_get_contents('php://input'), true);
    $nguoigui = $data['nguoiGui'];
    $nguoinhan = $data['nguoiNhan'];
    $noidung = $data['noiDung'];
    $res = $p->sendChat($nguoigui, $nguoinhan, $noidung);
    echo json_encode($res);
}
function layDanhSachLienLacChoGiangVien()
{
    $maGiangVien = $_REQUEST['maGiangVien'];
    $p = new ControlChatApp();
    $res = $p->layDanhSachLienLacChoGiangVien($maGiangVien);
    echo json_encode($res);
}

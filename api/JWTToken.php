<?php
// Hàm tạo JWT
use Firebase\JWT\JWT;

function createJWT($res, $thoiGian)
{
    $key = "iuhcongnghemoi";
    $payload = [
        'exp' => time() + (int)$thoiGian,
        'userId' => $res['MaTaiKhoan'],
        'role' => $res['VaiTro']
    ];
    $jwt = JWT::encode($payload, $key, 'HS256');
    $res['token'] = $jwt;
    return $res;
}
function validateToken()
{
    $headers = getallheaders();
    $jwt = $headers['Authorization'] ?? '';
    if (!$jwt) {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized']);
        return null;
    }
    $jwt = str_replace('Bearer ', '', $jwt);
    $key = "iuhcongnghemoi";
    try {
        $decoded = JWT::decode($jwt, new \Firebase\JWT\Key($key, 'HS256'));
        return (array) $decoded;
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized']);
        return null;
    }
}

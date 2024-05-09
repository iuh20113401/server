<?php

use Connect\Connect;

class Comment
{
    private $conn;

    public function __construct()
    {
        $p = new Connect();
        $this->conn = $p->connect();
    }

    public function layDanhSachComment($maDoAn)
    {
        $query = "SELECT c.maComment, c.maDoAn, c.nguoiGui, c.noiDung, c.ngay, c.gio, nv.hoTen AS hoTen, nv.hinhanh
        FROM comment c
            JOIN (
                SELECT MaSinhVien AS MaNguoiGui, HoTen, 'SinhVien' AS LoaiNguoiGui, hinhanh
                FROM sinhvien
                UNION
                SELECT MaGiangVien AS MaNguoiGui, HoTen, 'GiangVien' AS LoaiNguoiGui, hinhanh
                FROM giangvien
            ) nv ON c.NguoiGui = nv.MaNguoiGui
        WHERE c.MaDoAn = :maDoAn";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':maDoAn' => $maDoAn]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function themComment($maDoAn, $nguoiGui, $noiDung, $ngay, $gio)
    {
        $query = "INSERT INTO comment VALUES (null,:maDoAn, :nguoiGui, :noiDung, :ngay, :gio)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':maDoAn' => $maDoAn,
            ':nguoiGui' => $nguoiGui,
            ':noiDung' => $noiDung,
            ':ngay' => $ngay,
            ':gio' => $gio,
        ]);
    }
}

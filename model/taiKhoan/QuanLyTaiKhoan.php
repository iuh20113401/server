<?php

namespace TaiKhoan;

use Connect\Connect;
use PDO;

class QuanLyTaiKhoan
{
    public $conn;

    public function __construct()
    {
        $p = new Connect();
        $this->conn = $p->connect();
    }
    public function xemTaiKhoan()
    {
        $query = "SELECT tk.maTaiKhoan,COALESCE(gv.HoTen, sv.HoTen) as hoTen, COALESCE(gv.email, sv.email) AS email, COALESCE(gv.soDienThoai, sv.soDienThoai) AS soDienThoai, COALESCE(gv.gioiTinh, sv.gioiTinh) AS gioiTinh, COALESCE(gv.HinhAnh, sv.hinhanh) AS hinhAnh , tk.trangThai,tk.vaiTro 
        FROM taikhoan AS tk 
        LEFT JOIN giangvien AS gv ON gv.MaGiangVien = tk.MaTaiKhoan 
        LEFT JOIN sinhvien AS sv ON sv.MaSinhVien = tk.MaTaiKhoan WHERE tk.trangThai = 1;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function themTaiKhoan($maTaiKhoan, $matKhau, $vaiTro, $trangThai)
    {
        $query = "INSERT INTO taikhoan (MaTaiKhoan, MatKhau, VaiTro, trangThai) VALUES (:maTaiKhoan, :matKhau, :vaiTro, :trangThai)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':maTaiKhoan' => $maTaiKhoan,
            ':matKhau' => $matKhau,
            ':vaiTro' => $vaiTro,
            ':trangThai' => $trangThai
        ]);
    }
    public function themSinhVien($maSinhVien, $hoTen, $ngaySinh, $gioiTinh, $lop, $soDienThoai, $email)
    {
        $query = "INSERT INTO sinhvien (MaSinhVien, HoTen, NgaySinh, GioiTinh, Lop, SoDienThoai, Email, MoTa,hinhAnh, TrangThai) VALUES (:maSinhVien, :hoTen, :ngaySinh, :gioiTinh, :lop, :soDienThoai, :email, '', null, 0)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':maSinhVien' => $maSinhVien,
            ':hoTen' => $hoTen,
            ':ngaySinh' => $ngaySinh,
            ':gioiTinh' => $gioiTinh,
            ':lop' => $lop,
            ':soDienThoai' => $soDienThoai,
            ':email' => $email
        ]);
    }
    public function themGiangVien($maGiangVien, $hoTen, $gioiTinh, $ngaySinh, $soDienThoai, $email, $vaiTro)
    {
        $query = "INSERT INTO giangvien (maGiangVien, HoTen, GioiTinh, NgaySinh, SoDienThoai, Email, MoTa,hinhanh, VaiTro, trangthai) VALUES (:maGiangVien, :hoTen, :gioiTinh, :ngaySinh, :soDienThoai, :email, '',null , :vaiTro, 1)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':maGiangVien' => $maGiangVien,
            ':hoTen' => $hoTen,
            ':gioiTinh' => $gioiTinh,
            ':ngaySinh' => $ngaySinh,
            ':soDienThoai' => $soDienThoai,
            ':email' => $email,
            ':vaiTro' => $vaiTro,
        ]);
    }
    //them nhieu tai khoan
    public function kiemTraMaTaiKhoanTonTai($maTaiKhoanArray)
    {
        $placeholders = implode(',', array_fill(0, count($maTaiKhoanArray), '?'));
        $query = "SELECT MaTaiKhoan FROM taikhoan WHERE MaTaiKhoan IN ($placeholders)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute($maTaiKhoanArray);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function suaTaiKhoan($maTaiKhoan, $vaiTro)

    {
        if ($vaiTro == 0) {
            $query = "UPDATE taikhoan SET matKhau = 'sinhvien123' WHERE MaTaiKhoan = :maTaiKhoan";
        } else {
            $query = "UPDATE taikhoan SET matKhau = 'giangvien123' WHERE MaTaiKhoan = :maTaiKhoan";
        }
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':maTaiKhoan' => $maTaiKhoan,
        ]);
    }
    public function suaNhieuTaiKhoan($maTaiKhoanArray, $vaiTro)
    {
        $placeholders = implode(',', array_fill(0, count($maTaiKhoanArray), '?'));
        if ($vaiTro == 0) {
            $query = "UPDATE taikhoan SET matKhau = 'sinhvien123' WHERE MaTaiKhoan IN ($placeholders)";
        } else {
            $query = "UPDATE taikhoan SET matKhau = 'giangvien123' WHERE MaTaiKhoan IN ($placeholders)";
        }
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($maTaiKhoanArray);
    }
    public function xoaTaiKhoan($maTaiKhoan)
    {
        $query = "UPDATE taiKhoan SET trangThai = 0 WHERE MaTaiKhoan = :maTaiKhoan";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([":maTaiKhoan" => $maTaiKhoan]);
    }
    public function xoaNhieuTaiKhoan($maTaiKhoanArray)
    {
        $placeholders = implode(',', array_fill(0, count($maTaiKhoanArray), '?'));
        $query = "UPDATE taiKhoan SET trangThai = 0 WHERE MaTaiKhoan IN ($placeholders)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($maTaiKhoanArray);
    }
    public function suaSinhVien($maSinhVien, $hoTen, $ngaySinh, $gioiTinh, $lop, $soDienThoai, $email, $moTa, $trangThai)
    {
        $query = "UPDATE sinhvien SET HoTen = :hoTen, NgaySinh = :ngaySinh, GioiTinh = :gioiTinh, Lop = :lop, SoDienThoai = :soDienThoai, Email = :email, MoTa = :moTa, TrangThai = :trangThai WHERE MaSinhVien = :maSinhVien";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':maSinhVien' => $maSinhVien,
            ':hoTen' => $hoTen,
            ':ngaySinh' => $ngaySinh,
            ':gioiTinh' => $gioiTinh,
            ':lop' => $lop,
            ':soDienThoai' => $soDienThoai,
            ':email' => $email,
            ':moTa' => $moTa,
            ':trangThai' => $trangThai
        ]);
    }

    public function xoaSinhVien($maSinhVien)
    {
        $query = "UPDATE sinhVien SET trangThai = 0 WHERE MaSinhVien = :maSinhVien";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':maSinhVien' => $maSinhVien]);
    }
    public function xoaNhieuSinhVien($maSinhVienArray)
    {
        $placeholders = implode(',', array_fill(0, count($maSinhVienArray), '?'));
        $query = "UPDATE sinhvien SET trangThai = 0 WHERE MaSinhVien IN ($placeholders)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($maSinhVienArray);
    }
    public function layDanhSachSinhVien()
    {
        $query = "SELECT * FROM sinhvien";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function layDanhSachGiangVien()
    {
        $query = "SELECT * FROM giangvien";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function suaGiangVien($maGiangVien, $hoTen, $gioiTinh, $ngaySinh, $soDienThoai, $email, $moTa, $vaiTro)
    {
        $query = "UPDATE giangvien SET HoTen = :hoTen, GioiTinh = :gioiTinh, NgaySinh = :ngaySinh, SoDienThoai = :soDienThoai, Email = :email, MoTa = :moTa, VaiTro = :vaiTro WHERE MaGiangVien = :maGiangVien";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':maGiangVien' => $maGiangVien,
            ':hoTen' => $hoTen,
            ':gioiTinh' => $gioiTinh,
            ':ngaySinh' => $ngaySinh,
            ':soDienThoai' => $soDienThoai,
            ':email' => $email,
            ':moTa' => $moTa,
            ':vaiTro' => $vaiTro,
        ]);
    }

    public function xoaGiangVien($maGiangVien)
    {
        $query = "UPDATE giangvien SET trangThai = 0 WHERE MaGiangVien = :maGiangVien";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':maGiangVien' => $maGiangVien]);
    }
    public function xoaNhieuGiangVien($maGiangVienArray)
    {
        $placeholders = implode(',', array_fill(0, count($maGiangVienArray), '?'));
        $query = "UPDATE giangvien SET trangThai = 0 WHERE MaGiangVien IN ($placeholders)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($maGiangVienArray);
    }
}

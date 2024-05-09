
<?php

use Connect\Connect;

include("../model/connect/connect.php");


class DangNhap
{
    public $conn;

    public function __construct()
    {
        $p = new Connect();
        $this->conn = $p->connect();
    }
    public function kiemTraDangNhap($taiKhoan, $matKhau)
    {
        $query = "SELECT * FROM taikhoan WHERE mataikhoan = :taiKhoan AND matkhau = :matKhau";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':taiKhoan' => $taiKhoan, ':matKhau' => $matKhau]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function layThongTinhSinhVien($maTaiKhoan)
    {
        $query = "SELECT masinhvien as maSinhVien, hoten as tenSinhVien, ngaysinh as ngaySinh, gioiTinh, lop, soDienThoai, email, moTa, sv.trangthai as trangThaiSinhVien, tk.trangthai as trangThai, vaiTro, sv.hinhanh
        FROM sinhvien as sv JOIN taikhoan as tk ON tk.mataikhoan = sv.masinhvien WHERE mataikhoan = :taiKhoan";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':taiKhoan' => $maTaiKhoan]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function layThongTinGiangVien($maTaiKhoan)
    {
        $query = "SELECT maGiangVien, hoten as tenGiangVien, gioiTinh, ngaySinh, email, soDienThoai, gv.vaiTro, gv.trangThai, hinhAnh FROM giangvien as gv JOIN taikhoan as tk ON tk.mataikhoan = gv.magiangvien WHERE mataikhoan = :taiKhoan";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':taiKhoan' => $maTaiKhoan]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

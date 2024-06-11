<?php

namespace ConTrolTaiKhoan;

use TaiKhoan\QuanLyTaiKhoan;



class ControlQuanLyTaiKhoan
{
    private $quanLyTaiKhoan;

    public function __construct()
    {
        $this->quanLyTaiKhoan = new QuanLyTaiKhoan();
    }

    public function xemDanhSachTaiKhoan()
    {
        return $this->quanLyTaiKhoan->xemTaiKhoan();
    }

    public function themTaiKhoan($data)
    {
        $maTaiKhoan = $data['maTaiKhoan'];
        $matKhau = $data['matKhau'];
        $vaiTro = $data['vaiTro'];

        return $this->quanLyTaiKhoan->themTaiKhoan($maTaiKhoan, $matKhau, $vaiTro, 1);
    }

    public function themSinhVien($data)
    {
        $maSinhVien = $data['maTaiKhoan'];
        $hoTen = $data['hoTen'];
        $ngaySinh = $data['ngaySinh'];
        $gioiTinh = $data['gioiTinh'];
        $lop = $data['lop'];
        $soDienThoai = $data['soDienThoai'];
        $email = $data['email'];
        $trangThai = 0;

        return $this->quanLyTaiKhoan->themSinhVien($maSinhVien, $hoTen, $ngaySinh, $gioiTinh, $lop, $soDienThoai, $email);
    }

    public function themGiangVien($data)
    {
        $maGiangVien = $data['maTaiKhoan'];
        $hoTen = $data['hoTen'];
        $gioiTinh = $data['gioiTinh'];
        $ngaySinh = $data['ngaySinh'];
        $soDienThoai = $data['soDienThoai'] ? $data['soDienThoai'] : null;
        $email = $data['email'] ? $data['email'] : null;
        $vaiTro = $data['vaiTro'];
        return $this->quanLyTaiKhoan->themGiangVien($maGiangVien, $hoTen, $gioiTinh, $ngaySinh, $soDienThoai, $email, $vaiTro);
    }
    function kiemTraMaTaiKhoanTonTai($data)
    {
        $maTaiKhoan = $data['maTaiKhoan'];
        return $this->quanLyTaiKhoan->kiemTraMaTaiKhoanTonTai($maTaiKhoan);
    }
    public function suaTaiKhoan($data)
    {
        $maTaiKhoan = $data['maTaiKhoan'];
        $vaiTro = $data['vaiTro'];
        return $this->quanLyTaiKhoan->suaTaiKhoan($maTaiKhoan, $vaiTro);
    }
    public function suaNhieuTaiKhoan($data)
    {
        $maTaiKhoanArray = $data['maTaiKhoanArray'];
        $vaiTro = $data['vaiTro'];
        return $this->quanLyTaiKhoan->suaNhieuTaiKhoan($maTaiKhoanArray, $vaiTro);
    }
    public function xoaTaiKhoan($data)
    {
        $maTaiKhoan = $data['maTaiKhoan'];
        return $this->quanLyTaiKhoan->xoaTaiKhoan($maTaiKhoan);
    }

    public function xoaNhieuTaiKhoan($data)
    {
        $maTaiKhoanArray = $data['maTaiKhoanArray'];
        return $this->quanLyTaiKhoan->xoaNhieuTaiKhoan($maTaiKhoanArray);
    }

    public function suaSinhVien($data)
    {
        $maSinhVien = $data['maSinhVien'];
        $hoTen = $data['hoTen'];
        $ngaySinh = $data['ngaySinh'];
        $gioiTinh = $data['gioiTinh'];
        $lop = $data['lop'];
        $soDienThoai = $data['soDienThoai'];
        $email = $data['email'];
        $moTa = $data['moTa'];
        $trangThai = $data['trangThai'];

        return $this->quanLyTaiKhoan->suaSinhVien($maSinhVien, $hoTen, $ngaySinh, $gioiTinh, $lop, $soDienThoai, $email, $moTa, $trangThai);
    }

    public function xoaSinhVien($data)
    {
        $maSinhVien = $data['maSinhVien'];

        return $this->quanLyTaiKhoan->xoaSinhVien($maSinhVien);
    }

    public function xoaNhieuSinhVien($data)
    {
        $maSinhVienArray = $data['maSinhVienArray'];

        return  $this->quanLyTaiKhoan->xoaNhieuSinhVien($maSinhVienArray);
    }

    public function layDanhSachSinhVien()
    {
        return $this->quanLyTaiKhoan->layDanhSachSinhVien();
    }

    public function layDanhSachGiangVien()
    {
        return $this->quanLyTaiKhoan->layDanhSachGiangVien();
    }

    public function suaGiangVien($data)
    {
        $maGiangVien = $data['maGiangVien'];
        $hoTen = $data['hoTen'];
        $gioiTinh = $data['gioiTinh'];
        $ngaySinh = $data['ngaySinh'];
        $soDienThoai = $data['soDienThoai'];
        $email = $data['email'];
        $moTa = $data['moTa'];
        $vaiTro = $data['vaiTro'];

        return $this->quanLyTaiKhoan->suaGiangVien($maGiangVien, $hoTen, $gioiTinh, $ngaySinh, $soDienThoai, $email, $moTa, $vaiTro);
    }

    public function xoaGiangVien($data)
    {
        $maGiangVien = $data['maGiangVien'];

        return  $this->quanLyTaiKhoan->xoaGiangVien($maGiangVien);
    }

    public function xoaNhieuGiangVien($data)
    {
        $maGiangVienArray = $data['maGiangVienArray'];

        return $this->quanLyTaiKhoan->xoaNhieuGiangVien($maGiangVienArray);
    }
}

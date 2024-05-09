<?php
include('../model/dangNhap.php');
class ControllDangNhap
{
    public function dangNhap($taiKhoan, $matKhau)
    {
        $p = new DangNhap();
        $res = $p->kiemTraDangNhap($taiKhoan, $matKhau);
        return $res;
    }
    public function layThongTinSinhVien($maTaiKhoan)
    {
        $p = new DangNhap();
        $res = $p->layThongTinhSinhVien($maTaiKhoan);
        return $res;
    }
    public function layThongTinGiangVien($maTaiKhoan)
    {
        $p = new DangNhap();
        $res = $p->layThongTinGiangVien($maTaiKhoan);
        return $res;
    }
}

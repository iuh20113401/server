<?php

namespace SinhVien;

use Connect\Connect;
use PDO;

class SinhVienDeTai
{
    private $conn;
    public function __construct()
    {
        $p = new Connect();
        $this->conn = $p->connect();
    }
    public function capNhatAnhDaiDien($maSinhVien, $hinhanh)
    {
        $query = "UPDATE sinhvien SET hinhanh = :hinhanh WHERE maSinhVien = :maSinhVien";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':hinhanh' => $hinhanh,
            ':maSinhVien' => $maSinhVien,
        ]);
    }
    public function layDanhSachDeTai()
    {
        $query = "SELECT maDeTai,tenDeTai, soLuongDoAn, detai.trangThai,gv.maGiangVien, hoTen as tenGiangVien, detai.moTa, kyNangCanCo,ketQuaCanDat, detai.HinhAnh,Tag, detai.danhMuc, dmdt.tenDanhMuc 
        FROM detai 
        join giangvien as gv on detai.maGiangVien = gv.maGiangVien 
        join danhmucdetai as dmdt on dmdt.maDanhMuc = detai.danhMuc
        where detai.trangThai = 'Đã duyệt' || (detai.trangThai = 'Đã đăng ký' && soLuongDoAn < 2) ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function dangKyDoAn($maDoAn, $maDeTai, $maSinhVien, $maGiangVien, $ngay)
    {
        $query = "INSERT INTO doan VALUES (:maDoAn, :maDeTai, :maGiangVien, :maSinhVien, null, null,null,:ngay,0,0)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':maDeTai' => $maDeTai,
            ':maGiangVien' => $maGiangVien,
            ':maDoAn' => $maDoAn,
            ':maSinhVien' => $maSinhVien,
            ':ngay' => $ngay,
        ]);
    }
    public function layThongTinDoAn($maSinhVien)
    {
        $query = "SELECT da.maSinhVien1, da.maSinhVien2, da.maDoAn, detai.moTa, da.maDeTai, detai.tenDeTai, detai.soLuongDoAn, detai.TrangThai, gv.MaGiangVien, gv.HoTen AS giangVienHD, sv1.HoTen AS tenSinhVien1, sv2.HoTen AS tenSinhVien2, da.trangThai, detai.kyNangCanCo, detai.ketQuaCanDat, detai.Tag, da.giangVienPhanBien1, da.giangVienPhanBien2, gv2.HoTen as tenGiangVienPhanBien1, gv3.HoTen as tenGiangVienPhanBien3 , da.ngayThamGia, 
        COUNT(CASE WHEN td.TrangThai = 1 THEN 1 ELSE NULL END) / COUNT(*) * 100 AS tienDoHoanThanh
        FROM doan AS da 
        JOIN sinhvien AS sv ON sv.maSinhVien = da.maSinhVien1 OR sv.maSinhVien = da.maSinhVien2 
        JOIN detai ON da.maDeTai = detai.maDeTai 
        JOIN giangvien AS gv ON detai.maGiangVien = gv.maGiangVien  
        JOIN sinhvien AS sv1 ON da.maSinhVien1 = sv1.maSinhVien  
        LEFT JOIN sinhvien AS sv2 ON da.maSinhVien2 = sv2.maSinhVien 
        LEFT JOIN giangvien as gv2 on gv2.MaGiangVien = da.giangVienPhanBien1 
        LEFT JOIN giangvien as gv3 ON gv3.MaGiangVien = da.giangVienPhanBien2 
        LEFT JOin tiendo as td on td.MaDoAn = da.MaDoAn
        WHERE sv.maSinhVien = :maSinhVien;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':maSinhVien' => $maSinhVien]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function layThongTinThanhVien($maSinhVien)
    {
        $query = "SELECT da.maSinhVien1, sv1.hoten AS tenSinhVien1, sv1.email as emailSinhVien2, sv1.soDienThoai as soDienThoaiSinhVien1, sv1.ngaysinh as ngaySinhSinhVien1, sv2.hoten AS tenSinhVien2, da.maSinhVien2, sv2.email as emailSinhVien2, sv2.sodienthoai as soDienThoaiSinhVien2,gv.magiangvien, gv.hoten AS giangVienHD, gv.email as emailGiangVien, gv.sodienthoai as soDienThoaiGiangVien, da.giangVienPhanBien1, da.giangVienPhanBien2, gv2.hoten as tenGiangVienPhanBien1, gv2.email as emailGiangVienPhanBien1, gv2.sodienthoai as soDienThoaiGiangVienPhanBien1, gv3.hoten as tenGiangVienPhanBien2, gv3.email as emailGiangVienPhanBien2, gv3.sodienthoai as soDienThoaiGiangVienPhanBien2 
        FROM doan AS da 
        JOIN sinhvien AS sv ON sv.masinhvien = da.masinhvien1 OR sv.masinhvien = da.masinhvien2 
        JOIN detai ON da.madetai = detai.madetai 
        JOIN giangvien AS gv ON detai.magiangvien = gv.magiangvien  
        JOIN sinhvien AS sv1 ON da.masinhvien1 = sv1.masinhvien  
        LEFT JOIN sinhvien AS sv2 ON da.masinhvien2 = sv2.masinhvien 
        LEFT JOIN giangvien as gv2 on gv2.magiangvien = da.giangvienphanbien1 
        LEFT JOIN giangvien as gv3 ON gv3.magiangvien = da.giangvienphanbien2 WHERE sv.masinhvien = :maSinhVien;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':maSinhVien' => $maSinhVien]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    //note huong dẫn
    public function layDanhSachHuongDan($maDoAn)
    {
        $query = "SELECT t.maChiTietHuongDan, t.trangThai, t.ngayBatDau as sinhVienNgayBatDau, t.ngayHoanThanh as sinhVienNgayKetThuc, cthd.maHuongDan, cthd.noiDung, cthd.tieuChiHoanThanh, cthd.ngayBatDau, cthd.ngayHoanThanh 
        FROM tiendo AS t 
        JOIN chitiethuongdan AS cthd ON cthd.maChiTietHuongDan = t.Machitiethuongdan  
        WHERE t.MaDoAn = :maDoAn";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':maDoAn' => $maDoAn]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function hoanThanhHuongDan($maDoAn, $maChiTietHuongDan)
    {
        $query = "UPDATE tiendo set trangthai = 1 
        where maChiTietHuongDan = :maChiTietHuongDan and maDoAn = :maDoAn";
        $stmt = $this->conn->prepare($query);

        return
            $stmt->execute([
                ':maDoAn' => $maDoAn,
                ':maChiTietHuongDan' => $maChiTietHuongDan
            ]);
    }
    public function layHuongDanTheoSinhVien($maSinhVien)
    {
        $query = "SELECT t.trangthai 
        from tiendo as t join chitiethuongdan as cthd on cthd.maChiTietHuongDan = t.Machitiethuongdan join doan as da on t.MaDoAn = da.maDoAn WHERE masinhvien1 = :maSinhVien1 or da.masinhvien2 = :maSinhVien2";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':maSinhVien1' => $maSinhVien,
            ':maSinhVien2' => $maSinhVien
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function huyDangKyDoAn($maDoAn)
    {
        $query = "DELETE FROM doan WHERE maDoAn = :maDoAn";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':maDoAn' => $maDoAn,
        ]);
    }

    public function commentDoAn($maDoAn, $maSinhVien, $noiDung, $ngay, $gio)
    {
        $query = "INSERT INTO comment VALUES (:maDoAn, :maSinhVien, :noiDung, :ngay, :gio)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':maDoAn' => $maDoAn,
            ':maSinhVien' => $maSinhVien,
            ':noiDung' => $noiDung,
            ':ngay' => $ngay,
            ':gio' => $gio,
        ]);
    }
    public function themTaiLieu($tenTaiLieu, $duongDan, $loai, $dungLuong, $ngayDang, $maDoAn)
    {
        $query = "INSERT INTO tailieu VALUES (null,:tenTaiLieu,:duongDan,:loai,:dungLuong,:ngayDang,:maDoAn)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':tenTaiLieu' => $tenTaiLieu,
            ':duongDan' => $duongDan,
            ':loai' => $loai,
            ':dungLuong' => $dungLuong,
            ':ngayDang' => $ngayDang,
            ':maDoAn' => $maDoAn,
        ]);
    }
    public function layDanhSachTaiLieu($maDoAn)
    {
        $query = "SELECT maTaiLieu, tenTaiLieu, dungLuong, duongDan, loai, ngayDang FROM tailieu where maDoAn = :maDoAn";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':maDoAn' => $maDoAn,
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // note lich hop

    public function layDanhSachLichHop($maSinhVien)
    {
        $query = "SELECT lh.maLichHop, lh.tieuDe, lh.ghiChu, lh.ngay, lh.gio, lh.phong, dt.tenDeTai,da.maDoAn, da.maSinhVien1, da.maSinhVien2, sv1.hoTen as tenSinhVien1, sv2.hoten as tenSinhVien2
                  FROM lichhop AS lh
                  JOIN doan AS da ON da.MaDoAn = lh.MaDoAn
                  LEFT JOIN sinhvien as sv1 ON sv1.MaSinhVien = da.maSinhVien1
                  LEFT JOIN sinhvien as sv2 ON sv2.MaSinhVien = da.maSinhVien2
                  JOIN detai AS dt ON dt.MaDeTai = da.maDeTai
                  WHERE da.maSinhVien1 = :maSinhVien OR da.maSinhVien2 = :maSinhVien";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':maSinhVien' => $maSinhVien]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    //loi moi
    public function kiemTraTrangThaiSinhVien($maSinhVien)
    {
        $query = "SELECT trangThai FROM sinhvien WHERE maSinhVien = :maSinhVien";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':maSinhVien' => $maSinhVien]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function guiLoiMoiThamGiaNhom($maSinhVien, $nguoiNhan, $ngay, $noiDung)
    {
        $query = "INSERT INTO loimoi VALUES (NUll, :maSinhVien, :nguoiNhan, :ngay, :noiDung, 0)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':ngay' => $ngay,
            ':nguoiNhan' => $nguoiNhan,
            ':maSinhVien' => $maSinhVien,
            ':noiDung' => $noiDung,
        ]);
    }
    public function layDanhSachLoiMoi($maSinhVien)
    {
        $query = "SELECT dt.tenDeTai, dt.maGiangVien,dt.moTa, dt.kyNangCanCo, dt.ketQuaCanDat, dt.Tag, 
        da.maDoAn, da.maSinhVien1 as maSinhVien, sv.hoTen as tenSinhVien, gv.hoTen as tenGiangVien
        from loimoi as lm
        join sinhvien as sv on sv.masinhvien = lm.nguoigui
        join doan as da on da.maSinhVien1 = lm.nguoigui
        join detai as dt on dt.MaDeTai = da.MaDeTai
        join giangvien as gv on gv.MaGiangVien = dt.MaGiangVien
        where NguoiNhan = :maSinhVien and da.maSinhVien2 is null
        ;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':maSinhVien' => $maSinhVien]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function themThanhVienNhom($maDoAn, $maSinhVien)
    {
        $query = "UPDATE doan SET maSinhVien2 = :maSinhVien WHERE maDoAn = :maDoAn";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':maDoAn' => $maDoAn,
            ':maSinhVien' => $maSinhVien,
        ]);
    }
    public function capNhatTrangThaiSinhVien($maSinhVien)
    {
        $query = "UPDATE sinhvien SET trangThai = 1 WHERE maSinhVien = :maSinhVien";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':maSinhVien' => $maSinhVien,
        ]);
    }
    //note diemdanh

    public function layThongTinDiemDanh($maDiemDanh, $maSinhVien)
    {
        $query = "SELECT sv.maSinhVien , dd.maDiemDanh, dd.ngay, dd.gioBatDau, dd.gioKetThuc, dd.lat, dd.lon, dd.ip, dd.loai 
        FROM diemdanh as dd
        JOIN chitietdiemdanh as ctdd ON dd.maDiemDanh = ctdd.maDiemDanh
        JOIN doan as da ON da.maDoAn = ctdd.maDoAn
        LEFT JOIN sinhvien as sv ON sv.maSinhVien = :maSinhVien
        LEFT JOIN sinhvien as sv2 ON sv2.maSinhVien = :maSinhVien
        WHERE dd.maDiemDanh = :maDiemDanh AND (sv.maSinhVien = :maSinhVien OR sv2.maSinhVien = :maSinhVien)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':maDiemDanh' => $maDiemDanh, ':maSinhVien' => $maSinhVien]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function ghiNhanDiemDanh($maSinhVien, $maDiemDanh)
    {
        $query = "INSERT INTO sinhviendiemdanh (MaSinhVien, MaDiemDanh, TinhTrang) VALUES (:maSinhVien, :maDiemDanh, 1)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':maSinhVien' => $maSinhVien,
            ':maDiemDanh' => $maDiemDanh,
        ]);
    }

    // thongtin sinh vien 
    public function suaThongTinSinhVien($maSinhVien, $hoTen, $soDienThoai, $email, $moTa)
    {
        $query = "UPDATE sinhvien SET HoTen = :hoTen, SoDienThoai = :soDienThoai, Email = :email, MoTa = :moTa WHERE MaSinhVien = :maSinhVien";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':maSinhVien' => $maSinhVien,
            ':hoTen' => $hoTen,
            ':soDienThoai' => $soDienThoai,
            ':email' => $email,
            ':moTa' => $moTa
        ]);
    }
}

<?php

namespace GiangVien;


use PDO;
use PDOException;
use Connect\Connect;

class QuanLyDeTai
{
    public $conn;
    public function __construct()
    {
        $p = new Connect();
        $this->conn = $p->connect();
    }
    //note giang vien
    public function capNhatAnhDaiDien($maGiangVien, $hinhanh)
    {
        $query = "UPDATE giangvien SET hinhanh = :hinhanh WHERE maGiangVien = :maGiangVien";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':hinhanh' => $hinhanh,
            ':maGiangVien' => $maGiangVien,
        ]);
    }
    public function layDanhSachDeTai($maGiangVien)
    {

        $query = "SELECT * FROM detai where maGiangVien = :maGiangVien";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(
            [":maGiangVien" => $maGiangVien]
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function themDeTai($maDeTai, $maGiangVien, $tenDeTai, $moTa, $kyNangCanCo, $ketQuaCanDat, $loai, $danhMuc, $hinhAnh, $tag)
    {
        if (!$this->conn) {
            return false;
        }
        $date = date('Y-m-d H:i:s');

        $query = "INSERT INTO detai VALUES (:maDeTai, :maGiangVien, :tenDeTai, :moTa, :kyNangCanCo, :ketQuaCanDat,:loai, :danhMuc,'$date', 0, NULL,'',:hinhAnh, :tag, 'Chờ duyệt')";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([
            ':maDeTai' => $maDeTai,
            ':maGiangVien' => $maGiangVien,
            ':tenDeTai' => $tenDeTai,
            ':moTa' => $moTa,
            ':kyNangCanCo' => $kyNangCanCo,
            ':ketQuaCanDat' => $ketQuaCanDat,
            ':loai' => $loai,
            ':danhMuc' => $danhMuc,
            ':hinhAnh' => $hinhAnh,
            ':tag' => $tag,
        ]);
        return $result;
    }
    public function themTagVaDeTai($maDeTai, $maTag)
    {
        $query = "INSERT INTO detaivatag VALUES (:maDeTai, :maTag)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':maDeTai' => $maDeTai,
            ':maTag' => $maTag,
        ]);
    }
    public function suaDeTai($maDeTai, $tenDeTai, $moTa, $kyNangCanCo, $ketQuaCanDat)
    {
        if (!$this->conn) {
            return false;
        }
        // Use an UPDATE statement to modify an existing record
        $query = "UPDATE detai SET tenDeTai = :tenDeTai, moTa = :moTa, kyNangCanCo = :kyNangCanCo, ketQuaCanDat = :ketQuaCanDat WHERE maDeTai = :maDeTai";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([
            ':maDeTai' => $maDeTai,
            ':tenDeTai' => $tenDeTai,
            ':moTa' => $moTa,
            ':kyNangCanCo' => $kyNangCanCo,
            ':ketQuaCanDat' => $ketQuaCanDat,
        ]);
        return $result;
    }

    public function xoaDeTai($maDeTai)
    {
        if (!$this->conn) {
            return false;
        }
        // Use an UPDATE statement to modify an existing record
        $query = "DELETE FROM detai  WHERE maDeTai = :maDeTai";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([
            ':maDeTai' => $maDeTai,
        ]);
        return $result;
    }
    public function layDanhSachSinhVien($maGiangVien)
    {
        $query = "SELECT sv.maSinhVien, d.maDoAn, sv.hoTen, sv.email, sv.soDienThoai, sv.lop, d.mucDoHoanThanh as tienDoHoanThanh ,dt.tenDeTai 
        FROM doan as d join detai as dt on dt.MaDeTai = d.maDeTai 
        JOIN sinhvien as sv on sv.MaSinhVien = d.maSinhVien1 || d.maSinhVien2 = sv.MaSinhVien 
        JOIN giangvien as gv on gv.MaGiangVien = dt.MaGiangVien 
        JOIN diem on diem.MaSinhVien = sv.MaSinhVien 
        WHERE d.maGiangVien = :maGiangVien";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':maGiangVien' => $maGiangVien]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function layDanhSachDoAn($maGiangVien)
    {
        $query = "SELECT detai.tenDeTai, da.maSinhVien1,sv1.hoTen as tenSinhVien1, da.maSinhVien2, da.maDoAn, sv2.HoTen AS tenSinhVien2, da.trangThai 
        FROM doan AS da 
        JOIN sinhvien AS sv ON sv.maSinhVien = da.maSinhVien1 
        JOIN detai ON da.maDeTai = detai.maDeTai 
        JOIN giangvien AS gv ON detai.maGiangVien = gv.maGiangVien  
        JOIN sinhvien AS sv1 ON da.maSinhVien1 = sv1.maSinhVien  
        LEFT JOIN sinhvien AS sv2 ON da.maSinhVien2 = sv2.maSinhVien 
        LEFT JOIN giangvien as gv2 on gv2.MaGiangVien = da.giangVienPhanBien1 
        LEFT JOIN giangvien as gv3 ON gv3.MaGiangVien = da.giangVienPhanBien2 
        WHERE gv.maGiangVien = :maGiangVien;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':maGiangVien' => $maGiangVien]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function layThongTinDoAn($maDoAn)
    {
        $query = "SELECT da.maSinhVien1, 
        da.maSinhVien2,
         da.maDoAn, 
         detai.moTa, 
         da.maDeTai, 
         detai.tenDeTai, 
         detai.soLuongDoAn, 
         detai.TrangThai, 
         detai.hinhanh as hinhAnhDeTai,
         gv.MaGiangVien, 
         gv.HoTen AS giangVienHD, 
         gv.hinhAnh as hinhAnhGiangVienHD,
         sv1.HoTen AS tenSinhVien1, 
         sv1.hinhAnh AS hinhAnhSinhVien1,
         sv2.HoTen AS tenSinhVien2, 
         sv2.hinhAnh AS hinhAnhSinhVien2,
         da.trangThai, 
         detai.kyNangCanCo, 
         detai.ketQuaCanDat, 
         detai.Tag, 
         da.ngayThamGia,
         da.giangVienPhanBien1, 
         da.giangVienPhanBien2, 
         gv2.HoTen as tenGiangVienPhanBien1, 
         gv2.hinhAnh as hinhAnhGiangVienPhanBien1,
         gv3.HoTen as tenGiangVienPhanBien3,
         gv3.hinhAnh as hinhAnhGiangVienPhanBien2,
        COUNT(CASE WHEN da.TrangThai = 0 THEN 1 ELSE NULL END) / COUNT(*) * 100 AS tienDo,
        COUNT(td.maChiTietHuongDan)  AS soLuongHuongDan
        FROM doan AS da 
        JOIN sinhvien AS sv ON sv.maSinhVien = da.maSinhVien1 OR sv.maSinhVien = da.maSinhVien2 
        JOIN detai ON da.maDeTai = detai.maDeTai 
        JOIN giangvien AS gv ON detai.maGiangVien = gv.maGiangVien  
        JOIN sinhvien AS sv1 ON da.maSinhVien1 = sv1.maSinhVien  
        LEFT JOIN sinhvien AS sv2 ON da.maSinhVien2 = sv2.maSinhVien 
        LEFT JOIN giangvien as gv2 on gv2.MaGiangVien = da.giangVienPhanBien1 
        LEFT JOIN giangvien as gv3 ON gv3.MaGiangVien = da.giangVienPhanBien2 
        LEFT JOIN tiendo as td on td.maDoAn = da.maDoAn
        WHERE da.maDoAn = :maDoAn;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':maDoAn' => $maDoAn]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function layThongTinThanhVien($maDoAn)
    {
        $query = "SELECT da.maSinhVien1, sv1.HoTen AS tenSinhVien1, sv1.email as emailSinhVien2, sv1.soDienThoai as soDienThoaiSinhVien1, sv1.ngaySinh as ngaySinhSinhVien1, sv2.HoTen AS tenSinhVien2, da.maSinhVien2, sv2.email as emailSinhVien2, sv2.soDienThoai as soDienThoaiSinhVien2,gv.MaGiangVien, gv.HoTen AS giangVienHD, gv.email as emailGiangVien, gv.soDienThoai as soDienThoaiGiangVien, da.giangVienPhanBien1, da.giangVienPhanBien2, gv2.HoTen as tenGiangVienPhanBien1, gv2.email as emailGiangVienPhanBien1, gv2.soDienThoai as soDienThoaiGiangVienPhanBien1, gv3.HoTen as tenGiangVienPhanBien2, gv3.email as emailGiangVienPhanBien2, gv3.soDienThoai as soDienThoaiGiangVienPhanBien2 
        FROM doan AS da 
        JOIN sinhvien AS sv ON sv.maSinhVien = da.maSinhVien1 OR sv.maSinhVien = da.maSinhVien2 
        JOIN detai ON da.maDeTai = detai.maDeTai 
        JOIN giangvien AS gv ON detai.maGiangVien = gv.maGiangVien  
        JOIN sinhvien AS sv1 ON da.maSinhVien1 = sv1.maSinhVien  
        LEFT JOIN sinhvien AS sv2 ON da.maSinhVien2 = sv2.maSinhVien 
        LEFT JOIN giangvien as gv2 on gv2.MaGiangVien = da.giangVienPhanBien1 
        LEFT JOIN giangvien as gv3 ON gv3.MaGiangVien = da.giangVienPhanBien2 
        WHERE da.maDoAn = :maDoAn;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':maDoAn' => $maDoAn]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function layDanhSachHuongDanChoDoAn($maDoAn)
    {
        $query = "SELECT t.maChiTietHuongDan, t.trangThai, t.ngayBatDau as sinhVienNgayBatDau, t.ngayHoanThanh as sinhVienNgayKetThuc, cthd.maHuongDan, cthd.noiDung, cthd.tieuChiHoanThanh, cthd.ngayBatDau, cthd.ngayHoanThanh 
        from tiendo as t 
        join chitiethuongdan as cthd on cthd.maChiTietHuongDan = t.Machitiethuongdan  where t.MaDoAn = :maDoAn";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':maDoAn' => $maDoAn]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function duyetPhanBien($maDoAn)
    {
        $query = "UPDATE doan SET trangThai = 1 WHERE maDoAn = :maDoAn";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([
            ':maDoAn' => $maDoAn,
        ]);
        return $result;
    }

    //note cham diem
    public function layDanhSachDiemQuaTrinh($maGiangVien)
    {
        $query = "SELECT sv.maSinhVien,sv.lop,sv.email, sv.soDienThoai, d.maDoAn, dt.maDeTai, dt.tenDeTai, diem.diemGiuaKy, diem.diemCuoiKy, d.mucDoHoanThanh as tienDoHoanThanh, sv.hoTen as tenSinhVien 
        FROM doan as d join detai as dt on dt.MaDeTai = d.maDeTai 
        JOIN sinhvien as sv on sv.MaSinhVien = d.maSinhVien1 || d.maSinhVien2 = sv.MaSinhVien 
        JOIN giangvien as gv on gv.MaGiangVien = dt.MaGiangVien 
        JOIN diem on diem.MaSinhVien = sv.MaSinhVien WHERE d.maGiangVien = :maGiangVien";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':maGiangVien' => $maGiangVien]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function chamDiemGiuaKy($maSinhVien, $diemGiuaKy)
    {
        $query = "UPDATE diem SET diemGiuaKy = :diemGiuaKy WHERE maSinhVien = :maSinhVien";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([
            ':maSinhVien' => $maSinhVien,
            ':diemGiuaKy' => $diemGiuaKy,
        ]);
        return $result;
    }
    public function chamDiemCuoiKy($maSinhVien, $diemCuoiKy)
    {
        $query = "UPDATE diem SET diemCuoiKy = :diemCuoiKy WHERE maSinhVien = :maSinhVien";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([
            ':maSinhVien' => $maSinhVien,
            ':diemCuoiKy' => $diemCuoiKy,
        ]);
        return $result;
    }
    public function layDanhSachPhanBien($maGiangVien)
    {

        $query = "SELECT gv.maGiangVien, sv.maSinhVien, sv.HoTen as tenSinhVien, gv.HoTen as giangVienHuongDan, da.maDoAn, dt.maDeTai, dt.tenDeTai, d.diemPhanBien1, d.diemPhanBien2, da.mucDoHoanThanh as tienDoHoanThanh, da.giangVienPhanBien1, da.giangVienPhanBien2, dt.Tag, dt.kyNangCanCo, dt.moTa, dt.ketQuaCanDat 
        FROM giangvien as gv 
        JOIN doan as da on da.giangVienPhanBien1 = gv.MaGiangVien || da.giangVienPhanBien2 = gv.MaGiangVien 
        JOIN diem as d on da.maSinhVien1 = d.MaSinhVien || da.maSinhVien2 = d.MaSinhVien 
        join sinhvien as sv on sv.MaSinhVien = d.MaSinhVien 
        JOIN detai as dt on dt.MaDeTai = da.maDeTai where gv.MaGiangVien = :maGiangVien";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':maGiangVien' => $maGiangVien]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function chamDiemPhanBien1($maSinhVien, $diemPhanBien1)
    {
        $query = "UPDATE diem SET diemPhanBien1 = :diemPhanBien1 WHERE maSinhVien = :maSinhVien";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([
            ':maSinhVien' => $maSinhVien,
            ':diemPhanBien1' => $diemPhanBien1,
        ]);
        return $result;
    }
    public function chamDiemPhanBien2($maSinhVien, $diemPhanBien2)
    {
        $query = "UPDATE diem SET diemPhanBien2 = :diemPhanBien2 WHERE maSinhVien = :maSinhVien";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([
            ':maSinhVien' => $maSinhVien,
            ':diemPhanBien1' => $diemPhanBien2,
        ]);
        return $result;
    }
    //note duyet de tai

    public function layDanhSachDeTaiChoDuyet()
    {

        $query = "SELECT dt.maDeTai, gv.hoTen as giangVienHuongDan, dt.moTa, dt.kyNangCanCo, dt.trangThai, dt.ketQuaCanDat, dt.tenDeTai, Tag, danhMuc, loai, ngayTao, gv.maGiangVien 
        FROM detai as dt join giangvien as gv on dt.MaGiangVien = gv.MaGiangVien 
        where dt.trangThai = 'Chờ duyêt' ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function duyetDeTai($maDeTai, $maPheDuyet)
    {
        if (!$this->conn) {
            return false;
        }
        // Use an UPDATE statement to modify an existing record
        $query = "UPDATE detai SET trangThai = 'Đã duyệt', maPheDuyet=:maPheDuyet WHERE maDeTai = :maDeTai";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([
            ':maDeTai' => $maDeTai,
            ':maPheDuyet' => $maPheDuyet,
        ]);
        return $result;
    }
    public function khongDuyetDeTai($maDeTai)
    {
        if (!$this->conn) {
            return false;
        }
        $query = "UPDATE detai SET trangThai = 'Không duyệt'WHERE maDeTai = :maDeTai";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([
            ':maDeTai' => $maDeTai
        ]);
        return $result;
    }
    public function yeuCauChinhSuaDeTai($maDeTai, $ghiChu)
    {
        if (!$this->conn) {
            return false;
        }
        // Use an UPDATE statement to modify an existing record
        $query = "UPDATE detai SET trangThai = 'Chỉnh sửa', ghiChu=:ghiChu WHERE maDeTai = :maDeTai";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([
            ':maDeTai' => $maDeTai,
            ':maPheDuyet' => $ghiChu,
        ]);
        return $result;
    }
    //note phan giang vien phan bien
    public function layDanhSachPhanCongPhanBien()
    {
        $query = "SELECT 
    gv.maGiangVien, 
    sv1.MaSinhVien AS maSinhVien1, 
    sv1.HoTen AS tenSinhVien1, 
    sv2.MaSinhVien as maSinhVien2,
    sv2.HoTen as tenSinhVien2,
    gv.HoTen AS giangVienHuongDan, 
    da.maDoAn, 
    dt.Tag,
    dt.maDeTai, 
    dt.tenDeTai, 
    d.diemPhanBien1, 
    d.diemPhanBien2, 
    da.mucDoHoanThanh, 
    d.diemGiuaKy, 
    d.diemCuoiKy, 
    dt.moTa, 
    dt.hinhAnh,
    dt.kyNangCanCo, 
    dt.ketQuaCanDat 
    FROM giangvien AS gv 
    JOIN doan AS da ON da.maGiangVien = gv.MaGiangVien 
    JOIN diem AS d ON da.maSinhVien1 = d.MaSinhVien 
    LEFT JOIN sinhvien AS sv1 ON sv1.MaSinhVien  = da.maSinhVien1
    LEFT JOIN sinhvien as sv2 ON sv2.MaSinhVien = da.maSinhVien2 
    JOIN detai AS dt ON dt.MaDeTai = da.maDeTai 
    WHERE da.trangThai = 1 AND da.giangVienPhanBien1 IS NULL AND da.giangVienPhanBien2 IS NULL;
    ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function layDanhSachGiangVien()
    {
        $query = "SELECT maGiangVien, HoTen as tenGiangVien FROM giangvien ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function phanGiangVienPhanBien($maDoAn, $giangVien1, $giangVien2)
    {
        if (!$this->conn) {
            return false;
        }
        // Use an UPDATE statement to modify an existing record
        $query = "UPDATE doan SET giangVienPhanBien1 = :giangVien1, giangVienPhanBien2=:giangVien2 WHERE maDoAn = :maDoAn";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([
            ':maDoAn' => $maDoAn,
            ':giangVien1' => $giangVien1,
            ':giangVien2' => $giangVien2,
        ]);
        return $result;
    }
    //note hướng dẫn
    function layDanhSachHuongDan($maGiangVien)
    {
        $query = "SELECT hd.maHuongDan,hd.tenHuongDan, maChiTietHuongDan, noiDung, tieuChiHoanThanh, ngayBatDau, ngayHoanThanh, trangThai,ngayTao FROM chitiethuongdan as cthd JOIN huongdan as hd on hd.MaHuongDan = cthd.MaHuongDan where hd.maGiangVien = :maGiangVien";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':maGiangVien' => $maGiangVien]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function themHuongDan($maHuongDan, $tenHuongDan, $maGiangVien, $ngayTao, $trangThai)
    {
        if (!$this->conn) {
            return false;
        }
        $query = "INSERT INTO huongdan (MaHuongDan, TenHuongDan, MaGiangVien, NgayTao, TrangThai) VALUES (:MaHuongDan, :TenHuongDan, :MaGiangVien, :NgayTao, :TrangThai)";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([
            ':MaHuongDan' => $maHuongDan,
            ':TenHuongDan' => $tenHuongDan,
            ':MaGiangVien' => $maGiangVien,
            ':NgayTao' => $ngayTao,
            ':TrangThai' => $trangThai,
        ]);
        return $result;
    }
    public function themChiTietHuongDan($maChiTietHuongDan, $maHuongDan, $noiDung, $tieuChiHoanThanh, $ngayBatDau, $ngayHoanThanh)
    {
        if (!$this->conn) {
            return false;
        }
        $query = "INSERT INTO chitiethuongdan (MaChiTietHuongDan, MaHuongDan, NoiDung, TieuChiHoanThanh, NgayBatDau, NgayHoanThanh) VALUES (:MaChiTietHuongDan, :MaHuongDan, :NoiDung, :TieuChiHoanThanh, :NgayBatDau, :NgayHoanThanh)";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([
            ':MaChiTietHuongDan' => $maChiTietHuongDan,
            ':MaHuongDan' => $maHuongDan,
            ':NoiDung' => $noiDung,
            ':TieuChiHoanThanh' => $tieuChiHoanThanh,
            ':NgayBatDau' => $ngayBatDau,
            ':NgayHoanThanh' => $ngayHoanThanh,
        ]);
        return $result;
    }
    public function layDanhSachDeTaiDaDangKy($maGiangVien)
    {

        $query = "SELECT dt.maDeTai, dt.tenDeTai, dt.soLuongDoAn FROM detai as dt where maGiangVien = :maGiangVien and (trangThai = 'Đã đăng ký' OR trangThai = 'Đã đầy')";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([":maGiangVien" => $maGiangVien]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function themHuongDanVaoDoAn($maDoAnArray, $maHuongDan)
    {
        if (!$this->conn) {
            return false;
        }

        $query = "INSERT INTO tiendo (MaDoAn, MaChiTietHuongDan, TrangThai, NgayBatDau, NgayHoanThanh)
              SELECT :maDoAn, MaChiTietHuongDan, 0, NgayBatDau, NgayHoanThanh
              FROM chitiethuongdan
              WHERE MaHuongDan = :maHuongDan
              AND NOT EXISTS (
                  SELECT 1
                  FROM tiendo
                  WHERE MaDoAn = :maDoAn
              )";
        $stmt = $this->conn->prepare($query);

        foreach ($maDoAnArray as $maDoAn) {
            $result = $stmt->execute([
                ':maHuongDan' => $maHuongDan,
                ':maDoAn' => $maDoAn,
            ]);
            if (!$result) {
                return false;
            }
        }

        return true;
    }

    function themHuongDanVaoDeTai($maDeTai, $maHuongDan)
    {
        if (!$this->conn) {
            return false;
        }
        foreach ($maDeTai as $ma) {
            $query = "INSERT INTO tiendo (MaDoAn, MaChiTietHuongDan, TrangThai, NgayBatDau, NgayHoanThanh)
            SELECT da.MaDoAn, cthd.MaChiTietHuongDan, 0, null, null
            FROM huongdan as hd 
            JOIN detai as dt on dt.MaGiangVien = hd.MaGiangVien
            JOIN chitiethuongdan as cthd on cthd.MaHuongDan = hd.MaHuongDan
            JOIN doan as da on da.maDeTai = dt.MaDeTai
            LEFT JOIN tiendo as td on td.MaDoAn = da.MaDoAn
            WHERE dt.MaDeTai = :maDeTai 
            AND hd.maHuongDan = :maHuongDan
            AND td.MaDoAn IS NULL
            ";
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([
                ':maHuongDan' => $maHuongDan,
                ':maDeTai' => $ma,
            ]);
        }
        return $result;
    }

    //note Lich hop

    public function themLichHopVaoDeTai($maDeTai, $tieuDe, $ghiChu, $ngay, $gio, $phong)
    {
        if (!$this->conn) {
            return false;
        }
        $query = "INSERT INTO lichhop (MaDoAn, TieuDe, GhiChu, Ngay, Gio, Phong)
        SELECT da.MaDoAn, :tieuDe, :ghiChu, :ngay, :gio, :phong
        FROM detai as dt
        JOIN doan as da ON da.maDeTai = dt.MaDeTai
        WHERE dt.MaDeTai = :maDeTai";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([
            ':maDeTai' => $maDeTai,
            ':tieuDe' => $tieuDe,
            ':ghiChu' => $ghiChu,
            ':ngay' => $ngay,
            ':gio' => $gio,
            ':phong' => $phong,
        ]);
        return $result;
    }
    public function layDanhSachLichHop($maGiangVien)
    {
        $query = "SELECT lh.maLichHop, lh.tieuDe, lh.ghiChu, lh.ngay, lh.gio, lh.phong, dt.tenDeTai,da.maDoAn, da.maSinhVien1, da.maSinhVien2, sv1.hoTen as tenSinhVien1, sv2.hoten as tenSinhVien2
                  FROM lichhop AS lh
                  JOIN doan AS da ON da.MaDoAn = lh.MaDoAn
                  LEFT JOIN sinhvien as sv1 ON sv1.MaSinhVien = da.maSinhVien1
                  LEFT JOIN sinhvien as sv2 ON sv2.MaSinhVien = da.maSinhVien2
                  JOIN detai AS dt ON dt.MaDeTai = da.maDeTai
                  WHERE dt.MaGiangVien = :maGiangVien";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':maGiangVien' => $maGiangVien]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    //note diemdanh
    public function themMaDiemDanh($ngay, $gioBatDau, $gioKetThuc, $lat, $lon, $ip, $loai)
    {
        if (!$this->conn) {
            return false;
        }
        $query = "INSERT INTO diemdanh (Ngay, GioBatDau, GioKetThuc, lat, lon, ip, Loai) VALUES (:ngay, :gioBatDau, :gioKetThuc, :lat, :lon, :ip, :loai)";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([
            ':ngay' => $ngay,
            ':gioBatDau' => $gioBatDau,
            ':gioKetThuc' => $gioKetThuc,
            ':lat' => $lat,
            ':lon' => $lon,
            ':ip' => $ip,
            ':loai' => $loai,
        ]);
        if ($result) {
            return $this->conn->lastInsertId();
        } else {
            return false;
        }
    }


    public function themChiTietMaDiemDanhVaoDeTai($maDiemDanh, $maDeTai)
    {
        if (!$this->conn) {
            return false;
        }
        $query = "INSERT INTO chitietdiemdanh (MaDiemDanh, MaDeTai, MaDoAn) 
        SELECT :maDiemDanh, da.maDeTai, da.maDoAn
        FROM doan AS da
        JOIN detai AS dt ON dt.maDeTai = da.maDeTai
        WHERE dt.maDeTai = :maDeTai";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([
            ':maDiemDanh' => $maDiemDanh,
            ':maDeTai' => $maDeTai,
        ]);
        return $result;
    }
    public function themChiTietMaDiemDanhVaoDoAn($maDiemDanh, $maDoAn)
    {
        if (!$this->conn) {
            return false;
        }
        $query = "INSERT INTO chitietdiemdanh (MaDiemDanh, MaDeTai, MaDoAn) VALUES (:maDiemDanh,null, :maDoAn)";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([
            ':maDiemDanh' => $maDiemDanh,
            ':maDoAn' => $maDoAn,
        ]);
        return $result;
    }
    // note danh muc
    function layDanhSachDanhMuc()
    {
        $query = "SELECT 
                dm.maDanhMuc,
                dm.tenDanhMuc,
                dm.moTa,
                COUNT(DISTINCT dt.MaDeTai) AS soLuongDeTai,
                COUNT(d.maDoAn) AS soLuongDoAn
                FROM 
                    danhmucdetai dm
                JOIN 
                    detai dt ON dm.MaDanhMuc = dt.DanhMuc
                LEFT JOIN 
                    doan d ON dt.MaDeTai = d.maDeTai
                GROUP BY 
                    dm.MaDanhMuc, dm.TenDanhMuc, dm.MoTa;
                ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function themDanhMuc($tenDanhMuc, $moTa, $soLuongToiDa, $soLuongToiThieu)
    {
        $query = "INSERT INTO danhmucdetai (MaDanhMuc, TenDanhMuc, MoTa, SoLuongToiThieu, SoLuongToiDa) VALUES (null, :TenDanhMuc, :MoTa, :SoLuongToiThieu, :SoLuongToiDa)";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([
            ':TenDanhMuc' => $tenDanhMuc,
            ':MoTa' => $moTa,
            ':SoLuongToiThieu' => $soLuongToiThieu,
            ':SoLuongToiDa' => $soLuongToiDa,
        ]);
        return $result;
    }

    //thống kê
    public function thongKeDoAN($maGiangVien)
    {
        $query = "SELECT COUNT(DISTINCT da.maDoAn) as soLuongDoAn,  avg(d.DiemGiuaKy) as diemTrungBinhGiuaKy, avg(d.DiemCuoiKy)as  diemTrungBinhCuoiKy, min(d.DiemGiuaKy) as diemNhoNhatGiuaKy, min(d.DiemCuoiKy)as diemNhoNhatCuoiKy,max(d.DiemGiuaKy) as diemLonNhatGiuaKy, max(d.DiemCuoiKy) as diemLonNhatCuoiKy,COUNT(CASE WHEN da.TrangThai = 1 THEN 1 ELSE NULL END) AS SoLuongTrangThai1,
        COUNT(CASE WHEN da.TrangThai = 0 THEN 1 ELSE NULL END) AS SoLuongTrangThai0
        FROM diem as d 
        JOIN sinhvien as sv on sv.MaSinhVien=d.MaSinhVien 
        LEFT JOIN doan as da on da.maSinhVien1 = sv.MaSinhVien
        LEFT JOIN doan as da2 on da2.maSinhVien2 = sv.MaSinhVien
        WHERE da.maGiangVien = :maGiangVien;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':maGiangVien' => $maGiangVien]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function thongKeTienDo($maGiangVien)
    {
        $query = "SELECT COUNT(CASE WHEN td.TrangThai = 1 THEN 1 ELSE NULL END) / COUNT(*) * 100 AS soLuongTrangThai1,
        COUNT(CASE WHEN td.TrangThai = 0 THEN 1 ELSE NULL END) / COUNT(*) * 100 AS SoLuongTrangThai0
        FROM tiendo as td 
        JOIN doan as da on da.maDoAn = td.MaDoAn
        WHERE da.maGiangVien = :maGiangVien;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':maGiangVien' => $maGiangVien]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //thong ke de tai 
    public function thongKeDeTaiTheoTrangThai($maGiangVien)
    {
        $query = "SELECT COUNT(CASE WHEN dt.TrangThai = 'Chờ duyệt' THEN 1 ELSE NULL END) AS soLuongTrangThaiChoDuyet, 
        COUNT(CASE WHEN dt.TrangThai = 'Đã duyệt' THEN 1 ELSE NULL END) AS soLuongTrangThaiDaDuyet,
        COUNT(CASE WHEN dt.TrangThai = 'Không duyệt' THEN 1 ELSE NULL END) AS soLuongTrangThaiKhongDuyet,
        COUNT(CASE WHEN dt.TrangThai = 'Đã đăng ký' THEN 1 ELSE NULL END) AS soLuongTrangThaiDaDangKy,
        COUNT(CASE WHEN dt.TrangThai = 'Đã đầy' THEN 1 ELSE NULL END) AS soLuongTrangThaiDaDay
        FROM detai as dt
        JOIN danhmucdetai as dmdt on dmdt.MaDanhMuc = dt.DanhMuc
        WHERE dt.maGiangVien = :maGiangVien;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':maGiangVien' => $maGiangVien]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function thongKeDeTaiTheoDanhMuc($maGiangVien)
    {
        $query = "SELECT  dmdt.tenDanhMuc, count(*) as soLuongDeTai
        FROM detai as dt
        JOIN danhmucdetai as dmdt on dmdt.MaDanhMuc = dt.DanhMuc
        WHERE dt.maGiangVien = :maGiangVien
        GROUP BY dmdt.tenDanhMuc;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':maGiangVien' => $maGiangVien]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

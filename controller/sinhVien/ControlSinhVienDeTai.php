<?php

namespace ControllerSinhVien;

use Error;
use SinhVien\SinhVienDeTai;
use Aws\S3\S3Client;

class ControlSinhVienDeTai
{
    private $quanLyDeTai;

    public function __construct()
    {
        $this->quanLyDeTai = new SinhVienDeTai();
    }
    public function capNhatAnhDaiDien($data)
    {
        $targetDir = '../../uploads/';
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $maSinhVien = $data['maSinhVien'];
        $file = $data['file_info'];
        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => 'ap-southeast-2',
            'credentials' => [
                'key'    => 'AKIAZI2LI37WZ5FT53DM',
                'secret' => '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',
            ],
        ]);
        if ($data) {
            // Process each file
            // Check for errors
            if ($file['error'] === UPLOAD_ERR_OK) {
                // Validate file size or other properties here
                if ($file['size'] <= 5000000) { // example limit: 5MB
                    $targetFilePath = $targetDir . basename($file['name']);
                    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

                    if (in_array($fileType, ['png', 'jpg', 'jpeg', 'gif',])) {
                        try {
                            $s3->putObject([
                                'Bucket' => 'iuhcongnghemoi',
                                'Key'    => 'hinhanh/' . $file['name'],
                                'Body'   => fopen(
                                    $file['tmp_name'],
                                    'r'
                                ),
                            ]);
                            return $this->quanLyDeTai->capNhatAnhDaiDien($maSinhVien, "https://iuhcongnghemoi.s3.ap-southeast-2.amazonaws.com/hinhanh/" . basename($file['name']));
                        } catch (\Throwable $th) {
                            http_response_code(409);
                            return json_encode("Không thể tải file " . htmlspecialchars($file['name']));
                        }
                    } else {
                        http_response_code(409);
                        return json_encode("File không hợp lệ " . htmlspecialchars($file['name']));
                    }
                } else {
                    return "File " . htmlspecialchars($file['name']) . " File quá lớn";
                }
            } else {
                return "Error uploading file " . htmlspecialchars($file['name']) . ". Error code: " . $file['error'];
            }
        } else {
            return "No files received.";
        }
    }
    public function layDanhSachDeTai()
    {
        return  $this->quanLyDeTai->layDanhSachDeTai();
    }

    public function dangKyDoAn($data)
    {
        $maDeTai = $data['maDeTai'];
        $maDoAn = $data['maDoAn'];
        $maSinhVien = $data['maSinhVien'];
        $maGiangVien = $data['maGiangVien'];
        return  $this->quanLyDeTai->dangKyDoAn($maDoAn, $maDeTai, $maSinhVien, $maGiangVien, date('Y-m-d'));
    }

    public function huyDangKyDoAn($data)
    {
        $maDoAn = $data['maDoAn'];
        return  $this->quanLyDeTai->huyDangKyDoAn($maDoAn);
    }

    public function layThongTinDoAn()
    {
        $maSinhVien = $_GET['maSinhVien'];
        $res = $this->quanLyDeTai->layThongTinDoAn($maSinhVien);
        $hd = $this->quanLyDeTai->layDanhSachHuongDan($res['maDoAn']);
        $tl = $this->quanLyDeTai->layDanhSachTaiLieu($res['maDoAn']);
        return (object)[
            'thongTinDoAn' => $res,
            'huongDan' => $hd,
            'taiLieu' => $tl
        ];
    }
    public function layThongTinThanhVien()
    {
        $maSinhVien = $_GET['maSinhVien'];
        return $this->quanLyDeTai->layThongTinThanhVien($maSinhVien);
    }
    public function layHuongDanTheoSinhVien($data)
    {
        $maSinhVien = $data['maSinhVien'];
        return $this->quanLyDeTai->layHuongDanTheoSinhVien($maSinhVien);
    }
    public function hoanThanhHuongDan($data)
    {
        $maDoAn = $data['maDoAn'];
        $maChiTietHuongDan = $data['maChiTietHuongDan'];
        return $this->quanLyDeTai->hoanThanhHuongDan($maDoAn, $maChiTietHuongDan);
    }
    //loi moi
    public function layDanhSachLoiMoi()
    {
        $maSinhVien = $_GET['maSinhVien'];
        return $this->quanLyDeTai->layDanhSachLoiMoi($maSinhVien);
    }
    public  function guiLoiMoiThamGiaNhom($data)
    {
        $maSinhVien = $data['maSinhVien'];
        $nguoiNhan = $data['nguoiNhan'];
        $ngay = date('Y-m-d');
        $noiDung = $data['ghiChu'];
        $p = new ControlSinhVienDeTai();
        $res = $this->quanLyDeTai->kiemTraTrangThaiSinhVien($nguoiNhan);
        if ($res === false) {
            http_response_code(409);
            return "không có sinh viên này";
        }
        if ($res['trangThai'] > 0) {
            http_response_code(409);
            return "Sinh viên đã có đồ án";
        }
        return $this->quanLyDeTai->guiLoiMoiThamGiaNhom($maSinhVien, $nguoiNhan, $ngay, $noiDung);
    }
    public function thamGiaDoAn($data)
    {
        $maSinhVien = $data['maSinhVien'];
        $maDoAn = $data['maDoAn'];
        $res1 = $this->quanLyDeTai->capNhatTrangThaiSinhVien($maSinhVien);
        $res2 = $this->quanLyDeTai->themThanhVienNhom($maDoAn, $maSinhVien);
        return $res1 && $res2;
    }

    //comment
    public function commentDoAn($maDoAn, $maSinhVien, $noiDung, $ngay, $gio)
    {
        return $this->quanLyDeTai->commentDoAn($maDoAn, $maSinhVien, $noiDung, $ngay, $gio);
    }
    public function themTaiLieu($data)
    {

        $targetDir = '../../uploads/';
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $ngay = date('Y-m-d');
        $file = $data['file_info'];
        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => 'ap-southeast-2',
            'credentials' => [
                'key'    => 'AKIAZI2LI37WZ5FT53DM',
                'secret' => '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',
            ],
        ]);
        // Check if the file has been uploaded properly
        if ($data) {
            // Process each file
            // Check for errors
            if ($file['error'] === UPLOAD_ERR_OK) {
                // Validate file size or other properties here
                if ($file['size'] <= 5000000) { // example limit: 5MB
                    $targetFilePath = $targetDir . basename($file['name']);
                    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

                    // Validate file type, for example, allow only JPEG images
                    if (in_array($fileType, ['docx', 'ppt', 'pdf'])) {
                        // Move the file to the target upload directory
                        try {
                            $s3->putObject([
                                'Bucket' => 'iuhcongnghemoi',
                                'Key'    => 'tailieu/' . $file['name'],
                                'Body'   => fopen(
                                    $file['tmp_name'],
                                    'r'
                                ),
                            ]);
                            $this->quanLyDeTai->themTaiLieu($file['name'], "https://iuhcongnghemoi.s3.ap-southeast-2.amazonaws.com/tailieu/" . basename($file['name']), $fileType, $file['size'] / 1024 . "MB", $ngay, $data['maDoAn']);
                            return json_encode("File " . htmlspecialchars($file['name']) . " tải thành công.");
                        } catch (\Throwable $th) {
                            http_response_code(409);
                            return json_encode("Không thể tải file " . htmlspecialchars($file['name']));
                        }
                    } else {
                        http_response_code(409);
                        return json_encode("File không hợp lệ " . htmlspecialchars($file['name']));
                    }
                } else {
                    return "File " . htmlspecialchars($file['name']) . " File quá lớn";
                }
            } else {
                return "Error uploading file " . htmlspecialchars($file['name']) . ". Error code: " . $file['error'];
            }
        } else {
            return "No files received.";
        }
    }
    public function layDanhSachTaiLieu()
    {
        $maDoAn = $_GET['maDoAn'];
        return $this->quanLyDeTai->layDanhSachTaiLieu($maDoAn);
    }
    public function themThanhVienNhom($maDoAn, $maSinhVien)
    {
        return true;
    }

    public function layDanhSachLichHop()
    {
        $maSinhVien = $_GET['ma'];
        return $this->quanLyDeTai->layDanhSachLichHop($maSinhVien);
    }

    public function layThongTinDiemDanh()
    {
        $maDiemDanh = $_GET['maDiemDanh'];
        $maSinhVien = $_GET['maSinhVien'];
        return $this->quanLyDeTai->layThongTinDiemDanh($maDiemDanh, $maSinhVien);
    }
    public function ghiNhanDiemDanh($data)
    {
        $maDiemDanh = $data['maDiemDanh'];
        $maSinhVien = $data['maSinhVien'];
        return $this->quanLyDeTai->ghiNhanDiemDanh($maSinhVien, $maDiemDanh);
    }
}

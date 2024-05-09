<?php

namespace ControllerGiangVien;

// Adjust this path based on your actual namespace and directory structure
use GiangVien\QuanLyDeTai;
use Aws\S3\S3Client;

class ControlQuanLyDeTai
{
    private $quanLyDeTai;

    public function __construct()
    {
        $this->quanLyDeTai = new QuanLyDeTai();
    }

    public function layDanhSachDeTai()
    {
        $maGiangVien = $_GET['maGiangVien'];
        return $this->quanLyDeTai->layDanhSachDeTai($maGiangVien);
    }
    public function capNhatAnhDaiDien($data)
    {
        $targetDir = '../../uploads/';
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $maGiangVien = $data['maGiangVien'];
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
                            return $this->quanLyDeTai->capNhatAnhDaiDien($maGiangVien, "https://iuhcongnghemoi.s3.ap-southeast-2.amazonaws.com/hinhanh/" . basename($file['name']));
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
    public function themDeTai($data)
    {
        $success = true;
        if (isset($_FILES['hinhanh'])) {
            $image = $_FILES['hinhanh'];
            $targetPath = "E:/xampp/htdocs/Server/uploads/" . basename($image['name']);
            $success = move_uploaded_file($image['tmp_name'], $targetPath);
        }
        if ($success) {
            return $this->quanLyDeTai->themDeTai(
                $data['maDeTai'],
                $data['maGiangVien'],
                $data['tenDeTai'],
                $data['moTa'],
                $data['kyNangCanCo'],
                $data['ketQuaCanDat'],
                $data['loai'],
                $data['danhMuc'],
                $targetPath,
                $data['tag'],
            );
        } else {
            return ['error' => 'Failed to upload image.'];
        }
    }

    public function suaDeTai($data)
    {
        return $this->quanLyDeTai->suaDeTai(
            $data['maDeTai'],
            $data['tenDeTai'],
            $data['moTa'],
            $data['kyNangCanCo'],
            $data['ketQuaCanDat']
        );
    }

    public function xoaDeTai($maDeTai)
    {
        return $this->quanLyDeTai->xoaDeTai($maDeTai);
    }

    public function themHuongDan($data)
    {
        $data['ngay'] = date('Y-m-d');
        $data['trangThai'] = 1;
        $success = $this->quanLyDeTai->themHuongDan(
            $data['maHuongDan'],
            $data['tenHuongDan'],
            $data['maGiangVien'],
            $data['ngay'],
            $data['trangThai']
        );
        foreach ($data['chitiethuongdan'] as $chiTiet) {
            $this->quanLyDeTai->themChiTietHuongDan(
                $chiTiet['maChiTietHuongDan'],
                $data['maHuongDan'],
                $chiTiet['noiDung'],
                $chiTiet['tieuChiHoanThanh'],
                $chiTiet['ngayBatDau'],
                $chiTiet['ngayKetThuc']
            );
        }
        return $success;
    }

    public function layDanhSachHuongDan()
    {
        $maGiangVien = $_GET['maGiangVien'];
        return $this->quanLyDeTai->layDanhSachHuongDan($maGiangVien);
    }
    public function layDanhSachDeTaiDaDangKy()
    {

        return $this->quanLyDeTai->layDanhSachDeTaiDaDangKy($_GET['maGiangVien']);
    }
    public function themHuongDanVaoDoAn($maDoAn, $maHuongDan)
    {

        return $this->quanLyDeTai->themHuongDanVaoDoAn($maDoAn, $maHuongDan);
    }

    public function themHuongDanVaoDeTai($maDeTai, $maHuongDan)
    {
        return $this->quanLyDeTai->themHuongDanVaoDeTai($maDeTai, $maHuongDan);
    }
    public function layDanhSachPhanCongPhanBien()
    {
        return $this->quanLyDeTai->layDanhSachPhanCongPhanBien();
    }
    public function layDanhSachGiangVien()
    {
        return $this->quanLyDeTai->layDanhSachGiangVien();
    }
    public function phanGiangVienPhanBien($data)
    {
        $maDoAn = $data['maDoAn'];
        $giangVien1 = $data['maGiangVienPhanBien1'];
        $giangVien2 = $data['maGiangVienPhanBien2'];
        return $this->quanLyDeTai->phanGiangVienPhanBien($maDoAn,  $giangVien1, $giangVien2);
    }

    public function layDanhSachDiemQuaTrinh()
    {
        $maGiangVien = $_GET['maGiangVien'];
        return $this->quanLyDeTai->layDanhSachDiemQuaTrinh($maGiangVien);
    }
    public function layDanhSachSinhVien()
    {
        $maGiangVien = $_GET['maGiangVien'];
        return $this->quanLyDeTai->layDanhSachSinhVien($maGiangVien);
    }
    public function layDanhSachDoAn()
    {
        $maGiangVien = $_GET['maGiangVien'];
        return $this->quanLyDeTai->layDanhSachDoAn($maGiangVien);
    }
    public function layThongTinDoAn()
    {
        $maDoAn = $_GET['maDoAn'];
        return $this->quanLyDeTai->layThongTinDoAn($maDoAn);
    }
    public function layThongTinThanhVien()
    {
        $maDoAn = $_GET['maDoAn'];
        return $this->quanLyDeTai->layThongTinThanhVien($maDoAn);
    }
    public function layDanhSachHuongDanChoDoAn()
    {
        $maDoAn = $_GET['maDoAn'];
        return $this->quanLyDeTai->layDanhSachHuongDanChoDoAn($maDoAn);
    }
    public function duyetPhanBien($data)
    {
        $maDoAn = $data['maDoAn'];
        return $this->quanLyDeTai->duyetPhanBien($maDoAn);
    }
    public function chamDiemGiuaKy($data)
    {
        $maSinhVien = $data['maSinhVien'];
        $diem = $data['diem'];
        return $this->quanLyDeTai->chamDiemGiuaKy($maSinhVien, $diem);
    }
    public function chamDiemCuoiKy($data)
    {
        $maSinhVien = $data['maSinhVien'];
        $diem = $data['diem'];
        return $this->quanLyDeTai->chamDiemCuoiKy($maSinhVien, $diem);
    }
    public function layDanhSachPhanBien()
    {
        $maGiangVien = $_GET['maGiangVien'];
        return $this->quanLyDeTai->layDanhSachPhanBien($maGiangVien);
    }

    public function chamDiemPhanBien1($data)
    {
        $maSinhVien = $data['maSinhVien'];
        $diem = $data['diem'];
        return $this->quanLyDeTai->chamDiemPhanBien1($maSinhVien, $diem);
    }

    public function chamDiemPhanBien2($data)
    {
        $maSinhVien = $data['maSinhVien'];
        $diem = $data['diem'];
        return $this->quanLyDeTai->chamDiemPhanBien2($maSinhVien, $diem);
    }
    public function layDanhSachDeTaiChoDuyet()
    {
        return $this->quanLyDeTai->layDanhSachDeTaiChoDuyet();
    }

    public function duyetDeTai($data)
    {
        $maDeTai = $data['maDeTai'];
        $maPheDuyet = $data['maPheDuyet'];
        return $this->quanLyDeTai->duyetDeTai($maDeTai,  $maPheDuyet);
    }
    public function khongDuyetDeTai($data)
    {
        $maDeTai = $data['maDeTai'];
        return $this->quanLyDeTai->khongDuyetDeTai($maDeTai);
    }

    function yeuCauChinhSuaDeTai($data)
    {
        $maDeTai = $data['maDeTai'];
        $ghiChu = $data['ghiChu'];
        return $this->quanLyDeTai->yeuCauChinhSuaDeTai($maDeTai, $ghiChu);
    }

    //note lich hop 
    public function themLichHopVaoDeTai($data)
    {
        $tieuDe = $data['tieuDe'];
        $maDeTai = $data['selectedTopics'];
        $diaDiem = $data['diaDiem'];
        $ghiChu = $data['ghiChu'];
        $thoiGian = $data['thoiGian'];
        $dateTime = explode("T", $thoiGian);
        $date = $dateTime[0];
        $time = date('H:i:s', strtotime($dateTime[1]));

        // Your code here to process the data and perform the desired actions

        // Example code to demonstrate the usage
        $result = [];
        foreach ($maDeTai as $ma) {
            $result[] = $this->quanLyDeTai->themLichHopVaoDeTai($ma, $tieuDe, $ghiChu, $date, $time, $diaDiem);
        }
        return $time;
    }
    public function layDanhSachLichHop()
    {
        $maGiangVien = $_GET['ma'];
        return $this->quanLyDeTai->layDanhSachLichHop($maGiangVien);
    }
    //note diemdanh
    public function themMaDiemDanh($data)
    {
        $diadiem = isset($data['diadiem']) ? $data['diadiem'] : null;
        $ip = isset($data['ip']) ? $data['ip'] : null;
        $loai = $data['loai'];
        $selectedTopics = $data['selectedTopics'];
        $thoiGian = $data['thoiGian'];
        $ngay = date('Y-m-d');
        $gioBatDau = date('Y-m-d H:i:s');
        $gioKetThuc = date('Y-m-d H:i:s', strtotime($thoiGian . ' minutes', strtotime($gioBatDau)));
        try {
            // Example code to demonstrate the usage
            if ($diadiem == null && $ip == null) {
                $result1 = $this->quanLyDeTai->themMaDiemDanh($ngay, $gioBatDau, $gioKetThuc, null, null, $ip, $loai);
            } else {
                $result1 = $this->quanLyDeTai->themMaDiemDanh($ngay, $gioBatDau, $gioKetThuc, $diadiem['lat'], $diadiem['lng'], $ip, $loai);
            }
            $result = $loai === 'dt' ? $this->themChiTietDiemDanhVaoDeTai($result1, $selectedTopics) : $this->themChiTietDiemDanhVaoDoAn($result1, $selectedTopics);
            return $result1;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    function themChiTietDiemDanhVaoDeTai($result1, $selectedTopics)
    {
        $result = [];

        foreach ($selectedTopics as $ma) {
            $result[] = $this->quanLyDeTai->themChiTietMaDiemDanhVaoDeTai($result1, $ma);
        }
        return $result;
    }
    function themChiTietDiemDanhVaoDoAn($result1, $selectedTopics)
    {
        $result = [];
        foreach ($selectedTopics as $ma) {
            $result[] = $this->quanLyDeTai->themChiTietMaDiemDanhVaoDoAn($result1, $ma);
        }
        return $result;
    }

    //note danh muc

    public function layDanhSachDanhMuc()
    {
        return $this->quanLyDeTai->layDanhSachDanhMuc();
    }
    public function themDanhMuc($data)
    {
        $tenDanhMuc = $data['tenDanhMuc'];
        $moTa = $data['moTa'];
        $soLuongToiDa = $data['soLuongToiDa'];
        $soLuongToiThieu = $data['soLuongToiThieu'];
        return $this->quanLyDeTai->themDanhMuc($tenDanhMuc, $moTa, $soLuongToiDa, $soLuongToiThieu);
    }

    // thongke 
    public function thongKeDeTai($data)
    {
        $maGiangVien = $data['maGiangVien'];
        $thongKeDoAN = $this->quanLyDeTai->thongKeDoAN($maGiangVien);
        $thongKeTienDo = $this->quanLyDeTai->thongKeTienDo($maGiangVien);
        $thongKeDeTaiTheoTrangThai = $this->quanLyDeTai->thongKeDeTaiTheoTrangThai($maGiangVien);
        $thongKeDeTaiTheoDanhMuc = $this->quanLyDeTai->thongKeDeTaiTheoDanhMuc($maGiangVien);
        $result = (object) [
            'thongKeDoAN' => $thongKeDoAN,
            'thongKeTienDo' => $thongKeTienDo,
            'thongKeDeTaiTheoTrangThai' => $thongKeDeTaiTheoTrangThai,
            'thongKeDeTaiTheoDanhMuc' => $thongKeDeTaiTheoDanhMuc
        ];
        return $result;
    }
}

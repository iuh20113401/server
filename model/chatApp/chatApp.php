<?php

use Connect\Connect;

class Chat
{
    private $db;
    public function __construct()
    {
        $this->db = new Connect();
        $this->db = $this->db->connect();
    }
    public function getChat($maNguoiGui, $maNguoiNhan)
    {
        $sql = "SELECT MaTinNhan, NoiDung, NgayGui, NguoiGui, NguoiNhan
            FROM tinnhan
            WHERE (NguoiGui = :maNguoiGui AND NguoiNhan = :maNguoiNhan)
               OR (NguoiGui = :maNguoiNhan AND NguoiNhan = :maNguoiGui)
            ORDER BY NgayGui ASC"; // Giả sử cột thời gian gửi là NgayGui

        $stmt = $this->db->prepare($sql);

        $stmt->execute([':maNguoiGui' => $maNguoiGui, ':maNguoiNhan' => $maNguoiNhan]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    public function getContactableUsers($maSinhVien)
    {
        $contacts = [];

        // Lấy danh sách sinh viên trong cùng đoàn
        $query = "SELECT d.maSinhVien1,sv1.HoTen AS tenSinhVien1, d.maSinhVien2, sv2.HoTen AS tenSinhVien2, d.maGiangVien,gv.HoTen AS tenGiangVien
        FROM doan d
        LEFT JOIN sinhvien sv1 ON d.maSinhVien1 = sv1.MaSinhVien
        LEFT JOIN sinhvien sv2 ON d.maSinhVien2 = sv2.MaSinhVien
        LEFT JOIN giangvien gv ON d.maGiangVien = gv.MaGiangVien
        WHERE  d.maSinhVien1 = '{$maSinhVien}' OR d.maSinhVien2 = '{$maSinhVien}'";
        $result = $this->db->query($query);

        if ($result) {
            while ($row = $result->fetch()) {
                if ($row['maSinhVien1'] != $maSinhVien) {
                    $contacts[] = (object)[
                        'ma' => $row['maSinhVien1'],
                        'ten' => $row['tenSinhVien1']
                    ];
                }
                if ($row['maSinhVien2'] != $maSinhVien) {
                    $contacts[] =
                        (object)[
                            'ma' => $row['maSinhVien2'],
                            'ten' => $row['tenSinhVien2']
                        ];
                }
                $contacts[]
                    = (object)[
                        'ma' => $row['maGiangVien'],
                        'ten' => $row['tenGiangVien']
                    ];
            }
        }

        return $contacts;
    }
    public function layDanhSachLienLacChoGiangVien($maGiangVien)
    {
        $contacts = [];

        // Lấy danh sách sinh viên trong cùng đoàn
        $query = "SELECT sv.maSinhVien,sv.HoTen AS tenSinhVien,sv.hinhanh
        FROM doan d
         JOIN sinhvien sv ON d.maSinhVien1 = sv.MaSinhVien OR d.maSinhVien2 = sv.maSinhVien
        WHERE  d.maGiangVien = :maGiangVien";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':maGiangVien' => $maGiangVien]);
        if ($stmt) {
            while ($row = $stmt->fetch()) {
                $contacts[] = (object)[
                    'ma' => $row['maSinhVien'],
                    'ten' => $row['tenSinhVien'],
                    'hinhanh' => $row['hinhanh']
                ];
            }
        }
        return $contacts;
    }
    public function sendChat($maNguoiGui, $maNguoiNhan, $noiDung)
    {
        $sql = "INSERT INTO tinnhan(NguoiGui, NguoiNhan, NoiDung, NgayGui)
            VALUES(:maNguoiGui, :maNguoiNhan, :noiDung, NOW())";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([':maNguoiGui' => $maNguoiGui, ':maNguoiNhan' => $maNguoiNhan, ':noiDung' => $noiDung]);
    }
}

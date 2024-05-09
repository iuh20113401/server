<?php
include('../../model/chatApp/chatApp.php');
class ControlChatApp
{
    private $db;
    public function __construct()
    {
        $this->db = new Chat();
    }
    public function getChat($maNguoiGui, $maNguoiNhan)
    {
        return $this->db->getChat($maNguoiGui, $maNguoiNhan);
    }

    public function getContactableUsers($maSinhVien)
    {
        return $this->db->getContactableUsers($maSinhVien);
    }

    public function sendChat($maNguoiGui, $maNguoiNhan, $noiDung)
    {
        return $this->db->sendChat($maNguoiGui, $maNguoiNhan, $noiDung);
    }

    public function layDanhSachLienLacChoGiangVien($maGiangVien)
    {
        return $this->db->layDanhSachLienLacChoGiangVien($maGiangVien);
    }
}

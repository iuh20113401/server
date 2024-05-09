<?php
include_once('../../model/comment.php');

class ControlComment
{
    private $comment;
    public function __construct()
    {
        $this->comment = new Comment();
    }
    public function layDanhSachComment($maDoAn)
    {
        return $this->comment->layDanhSachComment($maDoAn);
    }
    public function themComment($maDoAn, $nguoiGui, $noiDung, $ngay, $gio)
    {
        return $this->comment->themComment($maDoAn, $nguoiGui, $noiDung, $ngay, $gio);
    }
}

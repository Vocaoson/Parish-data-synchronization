<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');

class GiaoDanCompareCL extends CompareCL {
    public function __construct() {
        parent::__construct();
        $this->load->model('GiaoDanMD');
    }
    public function compare($datas){
        foreach($datas as $data){
            $this->toBool($data);
            // by id, if same id => change
            // get last time change
            $giaoDans = $this->GiaoDanMD->getById($data['MaGiaoDan']);
            if(isset($giaoDans) && count($giaoDans) > 0){ //=> đã tồn tại giáo dân
                $giaoDan = $giaoDans[0];
                if(strtotime($giaoDan->UpdateDate) < strtotime($data['UpdateDate'])){
                    $this->GiaoDanMD->update($data);
                }
                        //else => insert
            } else {
                $this->GiaoDanMD->insert($data);
            }
            // merge
            // by MaNhanDang
            $giaoDans = $this->GiaoDanMD->getByMaNhanDang($data['MaNhanDang']);
            if(isset($giaoDans) && count($giaoDans) > 1){ //=> trùng
                $this->GiaoDanMD->deleteByMaNhanDang($data['MaNhanDang']);
            }
            // by name +  tenthanh + ngaysinh
            if(!empty($data['HoTen']) && !empty($data['NgaySinh']) && !empty($data['TenThanh'])) {
                $giaoDans = $this->GiaoDanMD->getByInfo($data['HoTen'],$data['TenThanh'],$data['NgaySinh']);
                if(isset($giaoDans) && count($giaoDans) > 1){ //=> trùng
                    $this->GiaoDanMD->deleteByInfo($data['HoTen'],$data['TenThanh'],$data['NgaySinh']);
                }
            }
        }
    }
}
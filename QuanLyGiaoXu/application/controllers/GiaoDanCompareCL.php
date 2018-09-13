<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');

class GiaoDanCompareCL extends CompareCL {
    public function __construct($file,$syn) {
        parent::__construct($file,$syn);
        $this->load->model('GiaoDanMD');
    }
    public function compare(){
        foreach($this->data as $data){
            $updated = false;
            $track = new stdClass();
            if(!empty($data['MaNhanDang']) || (!empty($data['HoTen']) && !empty($data['NgaySinh']) && !empty($data['TenThanh']))) {
                $giaoDans = !empty($data['MaNhanDang']) ? $this->GiaoDanMD->getByMaNhanDang($data['MaNhanDang']):$this->GiaoDanMD->getByInfo($data['HoTen'],$data['TenThanh'],$data['NgaySinh']);
                if(isset($giaoDans) && count($giaoDans) > 0) { //=> trùng
                    $updated =  true;
                    if($data['UpdateDate'] > $giaoDans[0]->UpdateDate) {
                        $track->oldIdIsCsv = false;
                        $track->oldId = $giaoDans[0]->MaGiaoDan;
                        $track->newId = $data['MaGiaoDan'];
                    } else {
                        $track->oldIdIsCsv = true;
                        $track->oldId = $data['MaGiaoDan'];
                        $track->newId = $giaoDans[0]->MaGiaoDan;
                        $this->GiaoDanMD->update($data);
                    }
                } else {
                    //create
                    $idNew = $this->GiaoDanMD->insert($data);
                    $track->oldIdIsCsv = true;
                    $track->oldId = $data['MaGiaoDan'];
                    $track->newId = $idNew;
                }
                $this->tracks[] = $track;
            }
            // by name +  tenthanh + ngaysinh
            // if(!empty($data['HoTen']) && !empty($data['NgaySinh']) && !empty($data['TenThanh'])) {
            //     $giaoDans = $this->GiaoDanMD->getByInfo($data['HoTen'],$data['TenThanh'],$data['NgaySinh']);
            //     if(isset($giaoDans) && count($giaoDans) > 1){ //=> trùng
            //         $this->GiaoDanMD->deleteByInfo($data['HoTen'],$data['TenThanh'],$data['NgaySinh']);
            //     }
            // }
        }
    }
}
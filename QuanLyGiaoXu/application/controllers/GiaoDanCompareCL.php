<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class GiaoDanCompareCL extends CompareCL {
    public function __construct($file,$syn) 
    {
        parent::__construct($file,$syn);
        require_once(APPPATH.'models/GiaoDanMD.php');
        $this->GiaoDanMD=new GiaoDanMD();
    }
    //2018/09/22 Gia add start
    private $GiaoDanMD;
    private $listGHThayDoi;
    public $MaGiaoXuRieng;
    public function getListGiaoHoTracks($tracks)
    {
        $this->listGHThayDoi=$tracks;
    }
    //2018/09/22 Gia add end
    public function compare()
    {
        foreach($this->data as $data){
            $giaoDans = array();
            if(!empty($data['MaNhanDang']) || (!empty($data['HoTen']) && !empty($data['NgaySinh']) && !empty($data['TenThanh']))) {
                $giaoDans = !empty($data['MaNhanDang']) ? $this->GiaoDanMD->getByMaNhanDang($data['MaNhanDang'],$this->MaGiaoXuRieng):$this->GiaoDanMD->getByInfo($data['HoTen'],$data['TenThanh'],$data['NgaySinh'],$this->MaGiaoXuRieng);
            }
            //2018/09/22 Gia add start
            $data['MaGiaoHo']=$this->findIdObjectSV($this->listGHThayDoi,$data['MaGiaoHo']);
            //2018/09/22 Gia add end
            if(count($giaoDans) > 0) {
                $this->tracks[] = $this->importObjectMaster($data,"MaGiaoDan",$giaoDans[0],$this->GiaoDanMD);
            } else {
                $this->tracks[] = $this->importObjectMaster($data,"MaGiaoDan",null,$this->GiaoDanMD);
            }     
        }
    }
    public function delete($maGiaoXuRieng) {
        $allGiaoDans = $this->GiaoDanMD->getAll($maGiaoXuRieng);
        foreach ($allGiaoDans as $key => $value) {
            if(!$this->isExist($value->MaGiaoDan)) {
                $this->GiaoDanMD->deleteMaGiaoDan($value->MaGiaoDan,$maGiaoXuRieng);
            }
        }
    }
    public function isExist($maGiaoDan) {
        foreach ($this->tracks as $key => $value) {
            if($maGiaoDan == $value->nowId) {
                return true;;
            }
        }
        return false;
    }
}
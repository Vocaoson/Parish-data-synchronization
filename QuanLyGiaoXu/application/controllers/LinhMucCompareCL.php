<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class LinhMucCompareCL extends CompareCL {
    public function __construct($file,$syn) 
    {
        parent::__construct($file,$syn);
        require_once(APPPATH.'models/LinhMucMD.php');
        $this->LinhMucMD=new LinhMucMD();
    }
    private $LinhMucMD;
    private $listGHThayDoi;
    public $MaGiaoXuRieng;
    public function getListGiaoHoTracks($tracks)
    {
        $this->listGHThayDoi=$tracks;
    }
    public function compare()
    {
        foreach($this->data as $data){
            $linhMucs = array();
            if(!empty($data['TenThanh']) && !empty($data['HoTen']) && !empty($data['ChucVu'])) {
                $linhMucs = $this->LinhMucMD->getByInfo($data['TenThanh'],$data['HoTen'],$data['ChucVu'],$this->MaGiaoXuRieng);
            }
            if(count($linhMucs) > 0) {
                $this->tracks[] = $this->importObjectMaster($data,"MaLinhMuc",$linhMucs[0],$this->LinhMucMD);
            } else {
                $this->tracks[] = $this->importObjectMaster($data,"MaLinhMuc",null,$this->LinhMucMD);
            }     
        }
    }
    public function delete($maGiaoXuRieng) {
        $allLinhMucs = $this->LinhMucMD->getAll($maGiaoXuRieng);
        foreach ($allLinhMucs as $key => $value) {
            if(!$this->isExist($value->MaLinhMuc)) {
                $this->LinhMucMD->deleteById($value->MaLinhMuc,$maGiaoXuRieng);
            }
        }
    }
    public function isExist($maLinhMuc) {
        foreach ($this->tracks as $key => $value) {
            if($maLinhMuc == $value->nowId) {
                return true;;
            }
        }
        return false;
    }
}
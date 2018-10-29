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
            //delete linhmuc
            if(count($linhMucs) > 0 && $this->deleteObjectMaster($data,$linhMucs[0],$this,$this->LinhMucMD)) {
                continue;
            }
            if(count($linhMucs) > 0) {
                $this->tracks[] = $this->importObjectMaster($data,"MaLinhMuc",$linhMucs[0],$this->LinhMucMD);
            } else {
                $this->tracks[] = $this->importObjectMaster($data,"MaLinhMuc",null,$this->LinhMucMD);
            }     
        }
    }
    public function delete($data) {
        $this->LinhMucMD->deleteById($data->MaLinhMuc,$data->MaGiaoXuRieng);
    }
}
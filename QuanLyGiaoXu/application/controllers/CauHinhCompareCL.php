<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class CauHinhCompareCL extends CompareCL {
    public function __construct($file,$syn) 
    {
        parent::__construct($file,$syn);
        require_once(APPPATH.'models/CauHinhMD.php');
        $this->CauHinhMD=new CauHinhMD();
    }
    private $CauHinhMD;
    private $listGHThayDoi;
    public $MaGiaoXuRieng;
    public function getListGiaoHoTracks($tracks)
    {
        $this->listGHThayDoi=$tracks;
    }
    public function compare()
    {
        foreach($this->data as $data){
            $cauHinhs = array();
            $cauHinhs = $this->CauHinhMD->getByInfo($data['MaCauHinh'],$this->MaGiaoXuRieng);
            if(count($cauHinhs) > 0) {
                $this->tracks[] = $this->importObjectMaster($data,"MaCauHinh",$cauHinhs[0],$this->CauHinhMD);
            } else {
                $this->tracks[] = $this->importObjectMaster($data,"MaCauHinh",null,$this->CauHinhMD);
            }     
        }
    }
    public function delete($maGiaoXuRieng) {
        $allCauHinhs = $this->CauHinhMD->getAll($maGiaoXuRieng);
        foreach ($allCauHinhs as $key => $value) {
            if(!$this->isExist($value->MaCauHinh)) {
                $this->CauHinhMD->deleteById($value->MaCauHinh,$maGiaoXuRieng);
            }
        }
    }
    public function isExist($maCauHinh) {
        foreach ($this->tracks as $key => $value) {
            if($maCauHinh == $value->nowId) {
                return true;;
            }
        }
        return false;
    }
}
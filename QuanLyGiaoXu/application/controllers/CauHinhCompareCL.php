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
            //delete linhmuc
            if(count($cauHinhs) > 0 && $this->deleteObjectMaster($data,$cauHinhs[0],$this,$this->CauHinhMD)) {
                continue;
            }
            if(count($cauHinhs) > 0) {
                $this->tracks[] = $this->importObjectMaster($data,"MaCauHinh",$cauHinhs[0],$this->CauHinhMD);
            } else {
                $this->tracks[] = $this->importObjectMaster($data,"MaCauHinh",null,$this->CauHinhMD);
            }     
        }
    }
    public function delete($data) {
        $this->CauHinhMD->deleteById($data->MaCauHinh,$data->MaGiaoXuRieng);
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
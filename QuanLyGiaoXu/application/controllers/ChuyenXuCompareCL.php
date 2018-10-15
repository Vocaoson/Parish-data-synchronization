<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class ChuyenXuCompareCL extends CompareCL {
    private $ChuyenXuMD;
    private $listGDThayDoi;
    public function __construct($file,$syn) 
    {
        parent::__construct($file,$syn);
        require_once(APPPATH.'models/ChuyenXuMD.php');
        $this->ChuyenXuMD=new ChuyenXuMD();
    }
    public function Compare() 
    {
        foreach($this->data as $data) {
            $chuyenXus = array();
            $data['MaGiaoDan']=$this->findIdObjectSV($this->listGDThayDoi,$data['MaGiaoDan']);
            $chuyenXus = $this->ChuyenXuMD->getByIdGiaoDan($data['MaGiaoDan']);
            if(count($chuyenXus) > 0) {
                $this->tracks[] = $this->importObjectMaster($data,"MaChuyenXu",$chuyenXus[0],$this->ChuyenXuMD);
            } else {
                $this->tracks[] = $this->importObjectMaster($data,"MaChuyenXu",null,$this->ChuyenXuMD);
            }
        }
    }
    public function getListGiaoDanTracks($tracks)
	{
		$this->listGDThayDoi=$tracks;
	}
    public function delete($maGiaoXuRieng) {
        $allChuyenXus = $this->ChuyenXuMD->getAll($maGiaoXuRieng);
        foreach ($allChuyenXus as $key => $value) {
            if(!$this->isExist($value->MaChuyenXu)) {
                $this->ChuyenXuMD->deleteById($value->MaChuyenXu,$maGiaoXuRieng);
            }
        }
    }
    public function isExist($maChuyenXu) {
        foreach ($this->tracks as $key => $value) {
            if($maChuyenXu == $value->nowId) {
                return true;
            }
        }
        return false;
    }
}
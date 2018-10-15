<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class TanHienCompareCL extends CompareCL {
    private $TanHienMD;
    private $listGDThayDoi;
    public function __construct($file,$syn) 
    {
        parent::__construct($file,$syn);
        require_once(APPPATH.'models/TanHienMD.php');
        $this->TanHienMD=new TanHienMD();
    }
    public function Compare() 
    {
        foreach($this->data as $data) {
            $tanHiens = array();
            $data['MaGiaoDan']=$this->findIdObjectSV($this->listGDThayDoi,$data['MaGiaoDan']);
            $tanHiens = $this->TanHienMD->getByIdGiaoDan($data['MaGiaoDan']);
            if(count($tanHiens) > 0) {
                $this->tracks[] = $this->importObjectMaster($data,"MaTanHien",$tanHiens[0],$this->TanHienMD);
            } else {
                $this->tracks[] = $this->importObjectMaster($data,"MaTanHien",null,$this->TanHienMD);
            }
        }
    }
    public function getListGiaoDanTracks($tracks)
	{
		$this->listGDThayDoi=$tracks;
	}
    public function delete($maGiaoXuRieng) {
        $allTanHiens = $this->TanHienMD->getAll($maGiaoXuRieng);
        foreach ($allTanHiens as $key => $value) {
            if(!$this->isExist($value->MaTanHien)) {
                $this->TanHienMD->deleteById($value->MaTanHien,$maGiaoXuRieng);
            }
        }
    }
    public function isExist($maTanHien) {
        foreach ($this->tracks as $key => $value) {
            if($maTanHien == $value->nowId) {
                return true;
            }
        }
        return false;
    }
}
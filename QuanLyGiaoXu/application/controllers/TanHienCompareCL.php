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
            if($data['MaGiaoDan'] == 0) {
                continue;
            }
            $tanHiens = $this->TanHienMD->getByIdGiaoDan($data['MaGiaoDan']);
            if(count($tanHiens) > 0 && $this->deleteObjectMaster($data,$tanHiens[0],$this,$this->TanHienMD)) {
                continue;
            }
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
    public function delete($data) {
        $this->TanHienMD->deleteById($data->MaTanHien,$data->MaGiaoXuRieng);
    }
}
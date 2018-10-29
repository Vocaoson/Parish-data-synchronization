<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class RaoHonPhoiCompareCL extends CompareCL {
    private $RaoHonPhoiMD;
    private $listGDThayDoi;
    public function __construct($file,$syn) 
    {
        parent::__construct($file,$syn);
        require_once(APPPATH.'models/RaoHonPhoiMD.php');
        $this->RaoHonPhoiMD=new RaoHonPhoiMD();
    }
    public function Compare() 
    {
        foreach($this->data as $data) {
            $raoHonPhois = array();
            $data['MaGiaoDan1']=$this->findIdObjectSV($this->listGDThayDoi,$data['MaGiaoDan1']);
            $data['MaGiaoDan2']=$this->findIdObjectSV($this->listGDThayDoi,$data['MaGiaoDan2']);
            // $raoHonPhois = $this->RaoHonPhoiMD->getByIdGiaoDan($data['MaGiaoDan']);
            if(count($raoHonPhois) > 0) {
                $this->tracks[] = $this->importObjectMaster($data,"MaRaoHonPhoi",$raoHonPhois[0],$this->RaoHonPhoiMD);
            } else {
                $this->tracks[] = $this->importObjectMaster($data,"MaRaoHonPhoi",null,$this->RaoHonPhoiMD);
            }
        }
    }
    public function getListGiaoDanTracks($tracks)
	{
		$this->listGDThayDoi=$tracks;
	}
    public function delete($data) {
        $this->RaoHonPhoiMD->deleteById($data->MaRaoHonPhoi,$data->MaGiaoXuRieng);
    }
}
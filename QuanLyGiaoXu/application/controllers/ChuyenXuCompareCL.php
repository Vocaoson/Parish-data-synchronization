<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class ChuyenXuCompareCL extends CompareCL {
    private $ChuyenXuMD;
    private $listGDThayDoi;
    public $MaGiaoXuRieng;
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
            // if GiaoDan is deleted
            if($data['MaGiaoDan'] == 0) {
                continue;
            }
            $chuyenXus = $this->ChuyenXuMD->getByIdGiaoDan($data['MaGiaoDan']);
            if(count($chuyenXus) > 0 && $this->deleteObjectMaster($data,$chuyenXus[0],$this,$this->ChuyenXuMD)) {
                continue;
            }
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
    public function delete($data) {
        $this->ChuyenXuMD->deleteById($data->MaChuyenXu,$data->MaGiaoXuRieng);
    }
}
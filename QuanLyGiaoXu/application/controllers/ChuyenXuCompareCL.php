<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class ChuyenXuCompareCL extends CompareCL {
    public function __construct($file,$syn) {
        parent::__construct($file,$syn);
        $this->load->model("ChuyenXuMD");
    }
    public function Compare() 
    {
        foreach($this->data as $data) {
            if($data["MaChuyenXu"]!=null)
			{
                if(!empty($data["KhoaNgoai"]))
                {
                    $ListDataKhoa = $this->csvimport->getListID("MaGiaoDan",$data[$data["KhoaNgoai"]]);
                    if($ListDataKhoa!=null)
                    $data[$data["KhoaNgoai"]]=$ListDataKhoa["MaIDMayChu"];
                }
                $chuyenXuServer=$this->findChuyenXu($data);
                if($chuyenXuServer!=null)
                {
                    if(!empty($data["KhoaChinh"]))
                    {
                        $this->csvimport->WriteData("MaChuyenXu",$data["MaChuyenXu"],$chuyenXuServer->MaChuyenXu,$this->dirData);
                        $data["MaChuyenXu"]=$chuyenXuServer->MaChuyenXu;
                    }
                    $compareDate=$this->CompareTwoDateTime($data['UpdateDate'],$chuyenXuServer->UpdateDate);
                    if($compareDate>=0 )
                    {
                        $this->updateObject($data,$chuyenXuServer,$this->ChuyenXuMD);
                    }
                    continue;
                }
                $idClient=$data["MaChuyenXu"];
                $idServerNew=$this->ChuyenXuMD->insert($data);
                $this->csvimport->WriteData("MaChuyenXu",$idClient,$idServerNew,$this->dirData);
			}
        }
    }
    public function findChuyenXu($data)
    {
        if(empty($data["KhoaChinh"]))
		{
			$rs=$this->ChuyenXuMD->getByMaChuyenXu($data["MaChuyenXu"]);
			if ($rs) {
				return $rs;
			}
        }
        //find MaGiaoDan
		$rs=$this->ChuyenXuMD->getByMaGiaoDan($data["MaGiaoDan"]);
		if ($rs) {
			return $rs;
		}
		return null;
    }
}
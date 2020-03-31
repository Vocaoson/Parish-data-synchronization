<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class RaoHonPhoiCompareCL extends CompareCL {
    public function __construct($file,$syn) 
    {
        parent::__construct($file,$syn);
        $this->load->model("RaoHonPhoiMD");
    }
    public function Compare() 
    {
        foreach($this->data as $data) {
            if($data["MaRaoHonPhoi"]!=null)
			{
				if(!empty($data["KhoaNgoai"]))
				{
					$data=$this->changeID($data,true);
				}
				$raoHonPhoiServer=$this->findRaoHonPhoi($data);
				if($raoHonPhoiServer!=null)
				{
					if(!empty($data["KhoaChinh"]))
					{
						$this->csvimport->WriteData("MaRaoHonPhoi",$data["MaRaoHonPhoi"],$raoHonPhoiServer->MaRaoHonPhoi,$this->dirData);
						$data["ID"]=$raoHonPhoiServer->MaRaoHonPhoi;
					}
					$compareDate=$this->CompareTwoDateTime($data['UpdateDate'],$raoHonPhoiServer->UpdateDate);
					if($compareDate>=0 )
					{
						$this->updateObject($data,$raoHonPhoiServer,$this->RaoHonPhoiMD);
					}
					continue;
				}
				$idClient=$data["MaRaoHonPhoi"];
				$idServerNew=$this->RaoHonPhoiMD->insert($data);
				$this->csvimport->WriteData("MaRaoHonPhoi",$idClient,$idServerNew,$this->dirData);
			}
        }
    }
    public function findRaoHonPhoi($data){
        if(empty($data["KhoaChinh"]))
		{
			$rs=$this->RaoHonPhoiMD->getByMaRaoHonPhoi($data["MaRaoHonPhoi"]);
			if ($rs) {
				return $rs;
			}
        }
        //find TenRaoHonPhoi, MaGiaoDan1, MaGiaoDan2, NgayRaoLan1, NgayRaoLan2, NgayRaoLan3, GhiChu
		$maGiaoXuRieng=$data['MaGiaoXuRieng'];
		$dieuKien="";
		if(!empty($data['TenRaoHonPhoi']))
		{
			$dieuKien.=" and TenRaoHonPhoi = '".str_replace("'","\'",$data['TenRaoHonPhoi'])."'";
		}
		if(!empty($data['MaGiaoDan1']))
		{
			$dieuKien.=" and MaGiaoDan1 = '".str_replace("'","\'",$data['MaGiaoDan1'])."'";
		}
		if(!empty($data['MaGiaoDan2']))
		{
			$dieuKien.=" and MaGiaoDan2 = '".str_replace("'","\'",$data['MaGiaoDan2'])."'";
		}
		if(!empty($data['NgayRaoLan1']))
		{
			$dieuKien.=" and NgayRaoLan1 = '".str_replace("'","\'",$data['NgayRaoLan1'])."'";
		}
		if(!empty($data['NgayRaoLan2']))
		{
			$dieuKien.=" and NgayRaoLan2 = '".str_replace("'","\'",$data['NgayRaoLan2'])."'";
        }
        if(!empty($data['NgayRaoLan3']))
		{
			$dieuKien.=" and NgayRaoLan3 = '".str_replace("'","\'",$data['NgayRaoLan3'])."'";
        }
        if(!empty($data['GhiChu']))
		{
			$dieuKien.=" and GhiChu = '".str_replace("'","\'",$data['GhiChu'])."'";
		}
		if(!empty($dieuKien))
		{
			$dieuKien="true ".$dieuKien;
			$rs=$this->RaoHonPhoiMD->getByInfo($dieuKien,$maGiaoXuRieng);
			if ($rs) {
				return $rs;
			}
		}
		return null;
    }
}
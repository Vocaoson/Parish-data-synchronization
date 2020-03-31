<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class HonPhoiCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		$this->load->model('HonPhoiMD');
	}
	public function compare()
	{
		foreach ($this->data as $data) {
			if($data["MaHonPhoi"]!=null)
			{
				$honPhoiServer=$this->findHonPhoi($data);
				if($honPhoiServer!=null)
				{
					if(!empty($data["KhoaChinh"]))
					{
						$this->csvimport->WriteData("MaHonPhoi",$data["MaHonPhoi"],$honPhoiServer->MaHonPhoi,$this->dirData);
						$data["MaHonPhoi"]=$honPhoiServer->MaHonPhoi;
					}
					$compareDate=$this->CompareTwoDateTime($data['UpdateDate'],$honPhoiServer->UpdateDate);
					if($compareDate>=0 )
					{
						$this->updateObject($data,$honPhoiServer,$this->HonPhoiMD);
					}
					continue;
				}
				else 
				{
				$idClient=$data["MaHonPhoi"];
				$idServerNew=$this->HonPhoiMD->insert($data);
				$this->csvimport->WriteData("MaHonPhoi",$idClient,$idServerNew,$this->dirData);
				}
			}
		}
	}
	public function findHonPhoi($data)
	{
		if(empty($data["KhoaChinh"]))
		{
			$rs=$this->HonPhoiMD->getByMaHonPhoi($data["MaHonPhoi"]);
			if ($rs) {
				return $rs;
			}
		}
		
		$maGiaoXuRieng=$data["MaGiaoXuRieng"];
		//check ma nhan dang
		if (!empty($data["MaNhanDang"])) {
			$rs=$this->HonPhoiMD->getByMaNhanDang($data["MaNhanDang"],$maGiaoXuRieng);
			if ($rs) {
				return $rs;
			}
		}
		//find NgayHonPhoi, SoHonPhoi, TenHonPhoi
		$dieuKien="";
		if(!empty($data['NgayHonPhoi']))
		{
			$dieuKien.=" and NgayHonPhoi = '".str_replace("'","\'",$data['NgayHonPhoi'])."'";
		}
		if(!empty($data['SoHonPhoi']))
		{
			$dieuKien.=" and SoHonPhoi = '".str_replace("'","\'",$data['SoHonPhoi'])."'";
		}
		if(!empty($data['TenHonPhoi']))
		{
			$dieuKien.=" and TenHonPhoi = '".str_replace("'","\'",$data['TenHonPhoi'])."'";
		}
		if(!empty($dieuKien))
		{
			$dieuKien="true ".$dieuKien;
			$rs=$this->HonPhoiMD->getByInfo($dieuKien,$maGiaoXuRieng);
			if ($rs!=null) {
				return $rs;
			}
		}
		return null;
	}
}

/* End of file HonPhoiCompareCL.php */
/* Location: ./application/controllers/HonPhoiCompareCL.php */
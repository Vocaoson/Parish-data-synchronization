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
		if($this->data!=null)
		{
			foreach ($this->data as $data) {
				if($data["MaHonPhoi"]!=null)
				{
					$honPhoiServer=$this->findHonPhoi($data);
					if($honPhoiServer!=null)
					{
						if(!empty($data["KhoaChinh"]))
						{
							$this->csvimport->WriteData("MaHonPhoi",$data["MaHonPhoi"],$honPhoiServer->MaHonPhoi,$this->dirData,$this->MaGiaoXuRieng);
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
						if($data["DeleteClient"]==0)
						{
					$idClient=$data["MaHonPhoi"];
					$idServerNew=$this->HonPhoiMD->insert($data);
					$this->csvimport->WriteData("MaHonPhoi",$idClient,$idServerNew,$this->dirData,$this->MaGiaoXuRieng);
					}
				}
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
		$dieuKien.=" and NgayHonPhoi = '".str_replace("'","\'",$data['NgayHonPhoi'])."'";
		$dieuKien.=" and SoHonPhoi = '".str_replace("'","\'",$data['SoHonPhoi'])."'";
		$dieuKien.=" and NoiHonPhoi = '".str_replace("'","\'",$data['NoiHonPhoi'])."'";
		$dieuKien.=" and LinhMucChung = '".str_replace("'","\'",$data['LinhMucChung'])."'";
		$dieuKien.=" and GhiChu = '".str_replace("'","\'",$data['GhiChu'])."'";
		$dieuKien.=" and NguoiChung1 = '".str_replace("'","\'",$data['NguoiChung1'])."'";
		$dieuKien.=" and NguoiChung2 = '".str_replace("'","\'",$data['NguoiChung2'])."'";
		$dieuKien.=" and TenHonPhoi = '".str_replace("'","\'",$data['TenHonPhoi'])."'";
		$dieuKien="true ".$dieuKien;
		$rs=$this->HonPhoiMD->getByInfo($dieuKien,$maGiaoXuRieng);
		if ($rs!=null) {
			if($this->csvimport->getListID("MaHonPhoi","server+".$rs->MaHonPhoi))
			{
				return null;
			}
			return $rs;
		}
		return null;
	} 
}

/* End of file HonPhoiCompareCL.php */
/* Location: ./application/controllers/HonPhoiCompareCL.php */
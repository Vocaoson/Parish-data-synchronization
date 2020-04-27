<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class HoiDoanCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		$this->load->model("HoiDoanMD");
	}
	public function compare()
	{
		if($this->data!=null)
		{
			foreach ($this->data as $data) {
				if($data["MaHoiDoan"]!=null)
				{
					$hoiDoanServer=$this->findHoiDoan($data);
					if($hoiDoanServer!=null)
					{
						if(!empty($data["KhoaChinh"]))
						{
							$this->csvimport->WriteData("MaHoiDoan",$data["MaHoiDoan"],$hoiDoanServer->MaHoiDoan,$this->dirData,$this->MaGiaoXuRieng);
							$data["MaHoiDoan"]=$hoiDoanServer->MaHoiDoan;
						}
						$compareDate=$this->CompareTwoDateTime($data['UpdateDate'],$hoiDoanServer->UpdateDate);
						if($compareDate>=0 )
						{
							$this->updateObject($data,$hoiDoanServer,$this->HoiDoanMD);
						}
						continue;
					}
					else 
					{
						if($data["DeleteClient"]==0)
						{
					$idClient=$data["MaHoiDoan"];
					$idServerNew=$this->HoiDoanMD->insert($data);
					$this->csvimport->WriteData("MaHoiDoan",$idClient,$idServerNew,$this->dirData,$this->MaGiaoXuRieng);
					}
				}
				}
			}
		}
	}
	public function findHoiDoan($data)
	{
		if(empty($data["KhoaChinh"]))
		{
			$rs=$this->HoiDoanMD->getByMaHoiDoan($data["MaHoiDoan"]);
			if ($rs) {
				return $rs;
			}
        }
        //find TenHoiDoan, ThanhBonMang, NgayBonMang, NgayThanhLap
		$maGiaoXuRieng=$data['MaGiaoXuRieng'];
		$dieuKien="";
		if(!empty($data['TenHoiDoan']))
		{
			$dieuKien.=" and TenHoiDoan = '".str_replace("'","\'",$data['TenHoiDoan'])."'";
		}
		if(!empty($data['ThanhBonMang']))
		{
			$dieuKien.=" and ThanhBonMang = '".str_replace("'","\'",$data['ThanhBonMang'])."'";
		}
		if(!empty($data['NgayBonMang']))
		{
			$dieuKien.=" and NgayBonMang = '".str_replace("'","\'",$data['NgayBonMang'])."'";
		}
		if(!empty($data['NgayThanhLap']))
		{
			$dieuKien.=" and NgayThanhLap = '".str_replace("'","\'",$data['NgayThanhLap'])."'";
		}
		if(!empty($dieuKien))
		{
			$dieuKien="true ".$dieuKien;
			$rs=$this->HoiDoanMD->getByInfo($dieuKien,$maGiaoXuRieng);
			if ($rs) {
				return $rs;
			}
		}
		return null;
	}
}

/* End of file HoiDoanCompareCL.php */
/* Location: ./application/controllers/HoiDoanCompareCL.php */
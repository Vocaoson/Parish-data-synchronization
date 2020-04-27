<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class ChiTietHoiDoanCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		$this->load->model("ChiTietHoiDoanMD");
	}
	public function compare()
	{
		if($this->data!=null)
		{
			foreach ($this->data as $data) {
				if($data["ID"]!=null)
				{
					if(!empty($data["KhoaNgoai"]))
					{
						$data=$this->changeID($data,true);
					}
					$chiTietHoiDoanServer=$this->findChiTietHoiDoan($data);
					if($chiTietHoiDoanServer!=null)
					{
						if(!empty($data["KhoaChinh"]))
						{
							$this->csvimport->WriteData("ID",$data["ID"],$chiTietHoiDoanServer->ID,$this->dirData,$this->MaGiaoXuRieng);
							$data["ID"]=$chiTietHoiDoanServer->ID;
						}
						$compareDate=$this->CompareTwoDateTime($data['UpdateDate'],$chiTietHoiDoanServer->UpdateDate);
						if($compareDate>=0 )
						{
							$this->updateObject($data,$chiTietHoiDoanServer,$this->ChiTietHoiDoanMD);
						}
						continue;
					}
					if($data["DeleteClient"]==0)
						{
							$idClient=$data["ID"];
							$idServerNew=$this->ChiTietHoiDoanMD->insert($data);
							$this->csvimport->WriteData("ID",$idClient,$idServerNew,$this->dirData,$this->MaGiaoXuRieng);
						}
				}
			}
		}
	}
	public function findChiTietHoiDoan($data)
	{
		if(empty($data["KhoaChinh"]))
		{
			$rs=$this->ChiTietHoiDoanMD->getByID($data["ID"]);
			if ($rs) {
				return $rs;
			}
        }
        //find MaHoiDoan, MaGiaoDan, NgayVaoHoiDoan, NgayRaHoiDoan, VaiTro
		$maGiaoXuRieng=$data['MaGiaoXuRieng'];
		$dieuKien="";
		if(!empty($data['MaHoiDoan']))
		{
			$dieuKien.=" and MaHoiDoan = '".str_replace("'","\'",$data['MaHoiDoan'])."'";
		}
		if(!empty($data['MaGiaoDan']))
		{
			$dieuKien.=" and MaGiaoDan = '".str_replace("'","\'",$data['MaGiaoDan'])."'";
		}
		if(!empty($data['NgayVaoHoiDoan']))
		{
			$dieuKien.=" and NgayVaoHoiDoan = '".str_replace("'","\'",$data['NgayVaoHoiDoan'])."'";
		}
		if(!empty($data['NgayRaHoiDoan']))
		{
			$dieuKien.=" and NgayRaHoiDoan = '".str_replace("'","\'",$data['NgayRaHoiDoan'])."'";
		}
		if(!empty($data['VaiTro']))
		{
			$dieuKien.=" and VaiTro = '".str_replace("'","\'",$data['VaiTro'])."'";
		}
		if(!empty($dieuKien))
		{
			$dieuKien="true ".$dieuKien;
			$rs=$this->ChiTietHoiDoanMD->getByInfo($dieuKien,$maGiaoXuRieng);
			if ($rs) {
				return $rs;
			}
		}
		return null;
	}
}

/* End of file HoiDoanCompareCL.php */
/* Location: ./application/controllers/HoiDoanCompareCL.php */
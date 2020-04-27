<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class KhoiGiaoLyCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		$this->load->model("KhoiGiaoLyMD");
	}
	public function compare()
	{
		if($this->data!=null)
		{
			foreach ($this->data as $data) {
				if($data["MaKhoi"]!=null)
				{
					if(!empty($data["KhoaNgoai"]))
				{
					$ListDataKhoa = $this->csvimport->getListID("MaGiaoDan",$data[$data["KhoaNgoai"]]);
					if($ListDataKhoa!=null)
					$data[$data["KhoaNgoai"]]=$ListDataKhoa["MaIDMayChu"];
				}
				$khoiGiaoLyServer=$this->findKhoiGiaoLy($data);
				if($khoiGiaoLyServer!=null)
				{
					if(!empty($data["KhoaChinh"]))
					{
						$this->csvimport->WriteData("MaGiaDinh",$data["MaGiaDinh"],$khoiGiaoLyServer->MaKhoi,$this->dirData,$this->MaGiaoXuRieng);
						$data["MaGiaDinh"]=$khoiGiaoLyServer->MaKhoi;
					}
					$compareDate=$this->CompareTwoDateTime($data['UpdateDate'],$khoiGiaoLyServer->UpdateDate);
					if($compareDate>=0 )
					{
						$this->updateObject($data,$khoiGiaoLyServer,$this->KhoiGiaoLyMD);
					}
					continue;
				}if($data["DeleteClient"]==0)
				{
				$idClient=$data["MaKhoi"];
				$idServerNew=$this->KhoiGiaoLyMD->insert($data);
				$this->csvimport->WriteData("MaKhoi",$idClient,$idServerNew,$this->dirData,$this->MaGiaoXuRieng);
				}}
			}
		}
	}
	
	public function findKhoiGiaoLy($data)
	{
		if(empty($data["KhoaChinh"]))
		{
			$rs=$this->KhoiGiaoLyMD->getByMaKhoi($data["MaKhoi"]);
			if ($rs) {
				return $rs;
			}
		}
		//find TenKhoi, NguoiQuanLy
		$maGiaoXuRieng=$data['MaGiaoXuRieng'];
		$dieuKien="";
		if(!empty($data['TenKhoi']))
		{
			$dieuKien.=" and TenKhoi = '".str_replace("'","\'",$data['TenKhoi'])."'";
		}
		if(!empty($data['NguoiQuanLy']))
		{
			$dieuKien.=" and NguoiQuanLy = '".str_replace("'","\'",$data['NguoiQuanLy'])."'";
		}
		if(!empty($dieuKien))
		{
			$dieuKien="true ".$dieuKien;
			$rs=$this->KhoiGiaoLyMD->getByInfo($dieuKien,$maGiaoXuRieng);
			if ($rs) {
				return $rs;
			}
		}
		
		return null;
	}
	
	
}

/* End of file KhoiGiaoLyCompareCL.php */
/* Location: ./application/controllers/KhoiGiaoLyCompareCL.php */
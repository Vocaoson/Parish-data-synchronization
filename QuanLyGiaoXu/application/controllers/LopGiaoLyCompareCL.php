<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class LopGiaoLyCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		$this->load->model("LopGiaoLyMD");
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
					$ListDataKhoa = $this->csvimport->getListID("MaKhoi",$data[$data["KhoaNgoai"]]);
					if($ListDataKhoa!=null)
					$data[$data["KhoaNgoai"]]=$ListDataKhoa["MaIDMayChu"];
				}
				$lopGiaoLyServer=$this->findLopGiaoLy($data);
				if($lopGiaoLyServer!=null)
				{
					if(!empty($data["KhoaChinh"]))
					{
						$this->csvimport->WriteData("MaLop",$data["MaLop"],$lopGiaoLyServer->MaLop,$this->dirData,$this->MaGiaoXuRieng);
						$data["MaLop"]=$lopGiaoLyServer->MaLop;
					}
					$compareDate=$this->CompareTwoDateTime($data['UpdateDate'],$lopGiaoLyServer->UpdateDate);
					if($compareDate>=0 )
					{
						$this->updateObject($data,$lopGiaoLyServer,$this->LopGiaoLyMD);
					}
					continue;
				}
				if($data["DeleteClient"]==0)
						{
				$idClient=$data["MaLop"];
				$idServerNew=$this->LopGiaoLyMD->insert($data);
				$this->csvimport->WriteData("MaLop",$idClient,$idServerNew,$this->dirData,$this->MaGiaoXuRieng);
				}}
			}
		}
	}
	public function findLopGiaoLy($data)
	{
		if(empty($data["KhoaChinh"]))
		{
			$rs=$this->LopGiaoLyMD->getByMaLop($data["MaLop"]);
			if ($rs) {
				return $rs;
			}
		}
		//TenLop,Nam,MaKhoi,PhongHoc
		$dieuKien="";
		if(!empty($data['TenLop']))
		{
			$dieuKien.=" and TenLop = '".str_replace("'","\'",$data['TenLop'])."'";
		}
		if(!empty($data['Nam']))
		{
			$dieuKien.=" and Nam = '".str_replace("'","\'",$data['Nam'])."'";
		}
		if(!empty($data['MaKhoi']))
		{
			$dieuKien.=" and MaKhoi = '".str_replace("'","\'",$data['MaKhoi'])."'";
		}
		if(!empty($data['PhongHoc']))
		{
			$dieuKien.=" and PhongHoc = '".str_replace("'","\'",$data['PhongHoc'])."'";
		}
		if(!empty($dieuKien))
		{
			$dieuKien="true ".$dieuKien;
			$rs = $this->LopGiaoLyMD->getByInfo($dieuKien,$this->MaGiaoXuRieng);    
			if ($rs) {
				return $rs;
			} 
		}
		return null;
	}
	public function compareHocVien($lopSV,$lopCSV)
	{
		$hocvienSV=$this->ChiTietLopGiaoLyMD->getByMaLop($lopSV->MaLop,$lopSV->MaGiaoXuRieng);
		$hocvienCSV=$this->getListByID($this->listCTLGLCSV,'MaLop',$lopCSV['MaLop']);
		if (count($hocvienSV)==0&&count($hocvienCSV)>0) {
			return true;
		}
		if (count($hocvienSV)==0||count($hocvienCSV)==0) {
			return false;
		}

		foreach ($hocvienCSV as $hvCSV) {
			foreach	($hocvienSV as $hvSV){
				$idGiaoDan=$this->findIdObjectSV($this->listGDThayDoi,$hvCSV['MaGiaoDan']);
				if ($idGiaoDan==$hvSV->MaGiaoDan) {
					return true;
				}
			}
		}
		return false;
	}

}

/* End of file LopGiaoLyCompareCL.php */
/* Location: ./application/controllers/LopGiaoLyCompareCL.php */
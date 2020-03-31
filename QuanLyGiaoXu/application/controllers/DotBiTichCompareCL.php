<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class DotBiTichCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		$this->load->model("DotBiTichMD");
	}
	public function compare()
	{
		foreach ($this->data as $data) {
			if($data["MaDotBiTich"]!=null)
			{
				$dotBiTichServer=$this->findDotBiTich($data);
				if($dotBiTichServer!=null)
				{
					if(!empty($data["KhoaChinh"]))
					{
						$this->csvimport->WriteData("MaDotBiTich",$data["MaDotBiTich"],$dotBiTichServer->MaDotBiTich,$this->dirData);
						$data["MaDotBiTich"]=$dotBiTichServer->MaDotBiTich;
					}
					$compareDate=$this->CompareTwoDateTime($data['UpdateDate'],$dotBiTichServer->UpdateDate);
					if($compareDate>=0 )
					{
						$this->updateObject($data,$dotBiTichServer,$this->DotBiTichMD);
					}
					continue;
				}
				else 
				{
				$idClient=$data["MaDotBiTich"];
				$idServerNew=$this->DotBiTichMD->insert($data);
				$this->csvimport->WriteData("MaDotBiTich",$idClient,$idServerNew,$this->dirData);
				}
			}
		}
	}
	public function findDotBiTich($data)
	{
		if(empty($data["KhoaChinh"]))
		{
			$rs=$this->DotBiTichMD->getByMaDotBiTich($data["MaDotBiTich"]);
			if ($rs) {
				return $rs;
			}
		}
		$maGiaoXuRieng=$data['MaGiaoXuRieng'];
		$dieuKien="";
		if(!empty($data['MoTa']))
		{
			$dieuKien.=" and MoTa = '".str_replace("'","\'",$data['MoTa'])."'";
		}
		if(!empty($data['NgayBiTich']))
		{
			$dieuKien.=" and NgayBiTich = '".str_replace("'","\'",$data['NgayBiTich'])."'";
		}
		if(!empty($data['Loai']))
		{
			$dieuKien.=" and Loai = '".str_replace("'","\'",$data['Loai'])."'";
		}
		if(!empty($data['LinhMuc']))
		{
			$dieuKien.=" and LinhMuc = '".str_replace("'","\'",$data['LinhMuc'])."'";
		}
		
		if(!empty($dieuKien))
		{
			$dieuKien="true ".$dieuKien;
			$rs=$this->DotBiTichMD->getByInfo($dieuKien,$maGiaoXuRieng);
			if ($rs) {
				return $rs;
			}
		}
		
		return null;
	}
}

/* End of file DotBiTichCompareCL.php */
/* Location: ./application/controllers/DotBiTichCompareCL.php */
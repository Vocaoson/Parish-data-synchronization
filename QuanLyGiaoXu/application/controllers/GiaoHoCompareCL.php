<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class GiaoHoCompareCL extends CompareCL {
	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		$this->load->model('GiaoHoMD');
	}
	public function compare()
	{
		foreach ($this->data as $data) {
			if($data["MaGiaoHo"]!=null)
			{
				$giaoHoServer=$this->findGiaoHo($data);
				if($giaoHoServer!=null)
				{
					if(!empty($data["KhoaChinh"]))
					{
						$this->csvimport->WriteData("MaGiaoHo",$data["MaGiaoHo"],$giaoHoServer->MaGiaoHo,$this->dirData);
						$data["MaGiaoHo"]=$giaoHoServer->MaGiaoHo;
					}
					$compareDate=$this->CompareTwoDateTime($data['UpdateDate'],$giaoHoServer->UpdateDate);
					if($compareDate>=0 )
					{
						$this->updateObject($data,$giaoHoServer,$this->GiaoHoMD);
					}
					continue;
				}
				else 
				{
				$idClient=$data["MaGiaoHo"];
				$idServerNew=$this->GiaoHoMD->insert($data);
				$this->csvimport->WriteData("MaGiaoHo",$idClient,$idServerNew,$this->dirData);
				}
			}
		}
		//Chưa làm

		//$this->processMaGiaoHoCha();

	}
	public function processMaGiaoHoCha()
	{
		foreach ($this->data as $data) {
			if (!empty($data["MaGiaoHoCha"])) {
				$idMaGiaoHoCha=$this->findIdObjectSV($this->tracks,$data["MaGiaoHoCha"]);
				if ($idMaGiaoHoCha!=0) {
					$data["MaGiaoHo"]=$this->findIdObjectSV($this->tracks,$data["MaGiaoHo"]);
					$this->GiaoHoMD->updateMaGiaoHoCha($data,$idMaGiaoHoCha);
				}
			}
		}
	}
	public function findGiaoHo($data)
	{
		if(empty($data["KhoaChinh"]))
		{
			$rs=$this->GiaoHoMD->getByMaGiaoHo($data["MaGiaoHo"]);
			if ($rs) {
				return $rs;
			}
		}
		$maGiaoXuRieng=$data['MaGiaoXuRieng'];
		if (!empty($data["MaNhanDang"])) {
			$rs=$this->GiaoHoMD->getByMaNhanDang($data["MaNhanDang"],$maGiaoXuRieng);
			if ($rs) {
				return $rs;
			}
		}
		//find name
		$rs=$this->GiaoHoMD->getByNameGiaoHo($data["TenGiaoHo"],$maGiaoXuRieng);
		if ($rs) {
			return $rs;
		}
		return null;
	}

}

/* End of file GiaoHoCompareCL.php */
/* Location: ./application/controllers/GiaoHoCompareCL.php */
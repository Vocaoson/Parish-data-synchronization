<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class GiaDinhCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		$this->load->model('GiaDinhMD');
	}
	
	public function compare()
	{
		foreach ($this->data as $data) {
			if($data["MaGiaDinh"]!=null)
			{
				if(!empty($data["KhoaNgoai"]))
				{
					$ListDataKhoa = $this->csvimport->getListID("MaGiaoHo",$data[$data["KhoaNgoai"]]);
					if($ListDataKhoa!=null)
					$data[$data["KhoaNgoai"]]=$ListDataKhoa["MaIDMayChu"];
				}
				$giaDinhServer=$this->findGiaDinh($data);
				if($giaDinhServer!=null)
				{
					if(!empty($data["KhoaChinh"]))
					{
						$this->csvimport->WriteData("MaGiaDinh",$data["MaGiaDinh"],$giaDinhServer->MaGiaDinh,$this->dirData);
						$data["MaGiaDinh"]=$giaDinhServer->MaGiaDinh;
					}
					$compareDate=$this->CompareTwoDateTime($data['UpdateDate'],$giaDinhServer->UpdateDate);
					if($compareDate>=0 )
					{
						$this->updateObject($data,$giaDinhServer,$this->GiaDinhMD);
					}
					continue;
				}
				$idClient=$data["MaGiaDinh"];
				$idServerNew=$this->GiaDinhMD->insert($data);
				$this->csvimport->WriteData("MaGiaDinh",$idClient,$idServerNew,$this->dirData);
			}
		}
	}
	public function findGiaDinh($data)
	{
		if(empty($data["KhoaChinh"]))
		{
			$rs=$this->GiaoDinhMD->getByMaGiaDinh($data["MaGiaDinh"]);
			if ($rs) {
				return $rs;
			}
		}

		if (!empty($data["MaNhanDang"])) {
			$rs=$this->GiaDinhMD->getByMaNhanDang($data["MaNhanDang"],$data["MaGiaoXuRieng"]);
			if ($rs) {
				return $rs;
			}
		}
		//find TenGiaDinh, DiaChi, DienThoai, GhiChu
			// Khi có gia đình rồi, cần kiểm tra thêm là các thành viên trong gia đình 
			// có giống nhau không, Nếu có 1 thành viên có 2 bên gia đình và cùng vai trò
			// Suy ra 2 gia đình là 1
		$rs=$this->GiaDinhMD->getByInfo($data["MaGiaoXuRieng"],$data["TenGiaDinh"],$data["DiaChi"],$data["DienThoai"]);
		if ($rs) {
			return $rs;
		}
		return null;
	}
}

/* End of file GiaDinhCompareCL.php */
/* Location: ./application/controllers/GiaDinhCompareCL.php */
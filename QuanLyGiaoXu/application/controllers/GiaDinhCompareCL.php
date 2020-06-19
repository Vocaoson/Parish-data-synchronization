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
		if($this->data!=null)
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
							$this->csvimport->WriteData("MaGiaDinh",$data["MaGiaDinh"],$giaDinhServer->MaGiaDinh,$this->dirData,$this->MaGiaoXuRieng);
							$data["MaGiaDinh"]=$giaDinhServer->MaGiaDinh;
						}
						$compareDate=$this->CompareTwoDateTime($data['UpdateDate'],$giaDinhServer->UpdateDate);
						if($compareDate>=0 )
						{
							$this->updateObject($data,$giaDinhServer,$this->GiaDinhMD);
						}
						continue;
					}
					if($data["DeleteClient"]==0)
						{
							$idClient=$data["MaGiaDinh"];
							$idServerNew=$this->GiaDinhMD->insert($data);
							$this->csvimport->WriteData("MaGiaDinh",$idClient,$idServerNew,$this->dirData,$this->MaGiaoXuRieng);
						}
				}
			}
		}
	}
	public function findGiaDinh($data)
	{
		if(empty($data["KhoaChinh"]))
		{
			$rs=$this->GiaDinhMD->getByMaGiaDinh($data["MaGiaDinh"]);
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
		//find  TenGiaDinh, DiaChi, DienThoai, GhiChu, MaGiaDinhRieng
			// Khi có gia đình rồi, cần kiểm tra thêm là các thành viên trong gia đình 
			// có giống nhau không, Nếu có 1 thành viên có 2 bên gia đình và cùng vai trò
			// Suy ra 2 gia đình là 1
		$rs=$this->GiaDinhMD->getByInfo($data["MaGiaoXuRieng"],$data["TenGiaDinh"],$data["DiaChi"],$data["DienThoai"],$data["MaGiaDinhRieng"]);
		if ($rs) {
			return $rs;
		}
		return null;
	}
}

/* End of file GiaDinhCompareCL.php */
/* Location: ./application/controllers/GiaDinhCompareCL.php */
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class ThanhVienGiaDinhCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		$this->load->model("ThanhVienGiaDinhMD");
	}
	public function compare()
	{
		if($this->data!=null)
		{
			foreach ($this->data as $data) {
				if($data["MaGiaDinh"]!=null)
				{
					//xử lý khóa chính
					if(!empty($data["KhoaChinh"]))
					{
						$data=$this->changeID($data);
					} 
					if($data !== null)
					{
						$thanhVienGiaDinhServer=$this->findThanhVienGiaDinh($data);
						if($thanhVienGiaDinhServer!=null)
						{
							$compareDate=$this->CompareTwoDateTime($data['UpdateDate'],$thanhVienGiaDinhServer->UpdateDate);
							if($compareDate>=0 )
							{
								$this->updateObject($data,$thanhVienGiaDinhServer,$this->ThanhVienGiaDinhMD);
							}
							continue;
						}
						if($data["DeleteClient"]==0)
						{
							$this->ThanhVienGiaDinhMD->insert($data);
						}
					}
				}
			}
		}
	}
	public function findThanhVienGiaDinh($data)
	{
		if(empty($data["KhoaChinh"]))
		{
			$rs=$this->ThanhVienGiaDinhMD->getByMaGiaDinhMaGiaoDan($data["MaGiaDinh"],$data["MaGiaoDan"]);
			if ($rs) {
				return $rs;
			}
		}
		return null;
	}
}

/* End of file ThanhVienGiaDinhCompare.php */
/* Location: ./application/controllers/ThanhVienGiaDinhCompare.php */
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class GiaoLyVienCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		$this->load->model("GiaoLyVienMD");
	}
	public function compare()
	{
		foreach($this->data as $data)
		{
			//xử lý khóa chính
			if(!empty($data["KhoaChinh"]))
			{
				$data=$this->changeID($data);
			}
			if($data !== null)
			{
				$giaoLyVienServer=$this->findGiaoLyVien($data);
				if($giaoLyVienServer!=null)
				{
					$compareDate=$this->CompareTwoDateTime($data['UpdateDate'],$giaoLyVienServer->UpdateDate);
					if($compareDate>=0 )
					{
						$this->updateObject($data,$giaoLyVienServer,$this->GiaoLyVienMD);
					}
					continue;
				}
				$this->GiaoLyVienMD->insert($data);
			}
		}
	}

	public function findGiaoLyVien($data)
	{
		if(empty($data["KhoaChinh"]))
		{
			$rs=$this->GiaoLyVienMD->getByMaLopMaGiaoDan($data["MaLop"],$data["MaGiaoDan"]);
			if ($rs) {
				return $rs;
			}
		}
		return null;
	}
	
}

/* End of file GiaoLyVienCompareCL.php */
/* Location: ./application/controllers/GiaoLyVienCompareCL.php */
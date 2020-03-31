<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class ChiTietLopGiaoLyCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		$this->load->model("ChiTietLopGiaoLyMD");
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
				$chiTietLopGiaoLyServer=$this->findChiTietLopGiaoLy($data);
				if($chiTietLopGiaoLyServer!=null)
				{
					$compareDate=$this->CompareTwoDateTime($data['UpdateDate'],$chiTietLopGiaoLyServer->UpdateDate);
					if($compareDate>=0 )
					{
						$this->updateObject($data,$chiTietLopGiaoLyServer,$this->ChiTietLopGiaoLyMD);
					}
					continue;
				}
				$this->ChiTietLopGiaoLyMD->insert($data);
			}
		}
	}
	public function findChiTietLopGiaoLy($data)
	{
		if(empty($data["KhoaChinh"]))
		{
			$rs=$this->ChiTietLopGiaoLyMD->getByMaLopMaGiaoDan($data["MaLop"],$data["MaGiaoDan"]);
			if ($rs) {
				return $rs;
			}
		}
		return null;
	}

}

/* End of file ChiTietLopGiaoLyCompareCL.php */
/* Location: ./application/controllers/ChiTietLopGiaoLyCompareCL.php */
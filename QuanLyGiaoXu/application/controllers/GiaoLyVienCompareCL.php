<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class GiaoLyVienCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		$this->GiaoLyVienMD=new GiaoLyVienMD();
	}
	private $GiaoLyVienMD;
	public function compare()
	{
		
	}
	// public function delete($listBTCTThayDoi)
	// {
	// 	$listBTCT=$this->BiTichChiTietMD->getAll();
	// 	if (isset($listBTCT)&&count($listBTCT)>0) {
	// 		foreach ($listBTCT as $data) {
	// 			$rs=$this->findBTCT($data,$listBTCTThayDoi);
	// 			if ($rs==0) {
	// 				//delete
	// 				$this->BiTichChiTietMD->delete($data->MaGiaoDan,$data->MaDotBiTich,$data->MaGiaoXuRieng);
	// 			}
	// 		}
	// 	}
	// }
}

/* End of file GiaoLyVienCompareCL.php */
/* Location: ./application/controllers/GiaoLyVienCompareCL.php */
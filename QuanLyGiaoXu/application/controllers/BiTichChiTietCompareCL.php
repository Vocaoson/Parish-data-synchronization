<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class BiTichChiTietCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);

	}
	public function compare()
	{
		
	}

	// public function delete($listBTCTThayDoi,$maGiaoXuRieng)
	// {
	// 	$listBTCT=$this->BiTichChiTietMD->getAll($maGiaoXuRieng);
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
	// public function findBTCT($data,$listBTCTThayDoi)
	// {
	// 	foreach ($listBTCTThayDoi as $value) {
	// 		if ($value->MaDotBiTich=$data->MaDotBiTich&&$value->MaGiaoDan==$data->MaGiaoDan) {
	// 			return 1;
	// 		}
	// 	}
	// 	return 0;
	// }

}

/* End of file BiTichChiTietCompareCL.php */
/* Location: ./application/controllers/BiTichChiTietCompareCL.php */
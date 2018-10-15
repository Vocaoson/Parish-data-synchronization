<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class BiTichChiTietCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		require_once(APPPATH.'models/BiTichChiTietMD.php');
        $this->BiTichChiTietMD=new BiTichChiTietMD();

	}
	private $listGiaoDanTracks;
	private $listDotBiTichTracks;
	private $BiTichChiTietMD;
	public function compare()
	{
		if (isset($this->listDotBiTichTracks)&&count($this->listDotBiTichTracks)>0) {
			foreach ($this->listDotBiTichTracks as $value) {
				$this->importObjectChild($value,$this->data,"MaDotBiTich",$this->listGiaoDanTracks,"MaGiaoDan",$this->BiTichChiTietMD);
			}
		}
	}
	public function delete($maGiaoXuRieng)
	{
		$this->deleteObjecChild($this->tracks,"MaDotBiTich","MaGiaoDan",$this->BiTichChiTietMD,$maGiaoXuRieng);
	}
	public function getListGiaoDanTracks($listTracks)
	{
		$this->listGiaoDanTracks=$listTracks;
	}
	public function getListDotBiTichTracks($listTracks)
	{
		$this->listDotBiTichTracks=$listTracks;
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
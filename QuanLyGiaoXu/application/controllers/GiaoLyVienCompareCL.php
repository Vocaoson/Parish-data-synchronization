<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class GiaoLyVienCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		require_once(APPPATH.'models/GiaoLyVienMD.php');
        $this->GiaoLyVienMD=new GiaoLyVienMD();
	}
	private $GiaoLyVienMD;
	private $listLopGiaoLyTracks;
	private $listGiaoDanTracks;
	public function compare()
	{
		if (isset($this->listLopGiaoLyTracks)&&count($this->listLopGiaoLyTracks)>0) {
			foreach ($this->listLopGiaoLyTracks as $value) {
				$this->importObjectChild($value,$this->data,"MaLop",$this->listGiaoDanTracks,"MaGiaoDan",$this->GiaoLyVienMD);
			}
		}
	}
	public function delete($maGiaoXuRieng)
	{
		$this->deleteObjecChild($this->tracks,"MaLop","MaGiaoDan",$this->GiaoLyVienMD,$maGiaoXuRieng);
	}
	public function getListGiaoDanTracks($listTracks)
	{
		$this->listGiaoDanTracks=$listTracks;
	}
	public function getlistLopGiaoLyTracks($listTracks)
	{
		$this->listLopGiaoLyTracks=$listTracks;
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
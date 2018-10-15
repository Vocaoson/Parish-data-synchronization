<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class ChiTietLopGiaoLyCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		require_once(APPPATH.'models/ChiTietLopGiaoLyMD.php');
        $this->ChiTietLopGiaoLyMD=new ChiTietLopGiaoLyMD();
	}
	private $ChiTietLopGiaoLyMD;
	private $listLopGiaoLyTracks;
	private $listGiaoDanTracks;
	public function compare()
	{
		if (isset($this->listLopGiaoLyTracks)&&count($this->listLopGiaoLyTracks)>0) {
			foreach ($this->listLopGiaoLyTracks as $value) {
				$this->importObjectChild($value,$this->data,"MaLop",$this->listGiaoDanTracks,"MaGiaoDan",$this->ChiTietLopGiaoLyMD);
			}
		}
	}
	public function delete($maGiaoXuRieng)
	{
		$this->deleteObjecChild($this->tracks,"MaLop","MaGiaoDan",$this->ChiTietLopGiaoLyMD,$maGiaoXuRieng);
	}
	public function getListGiaoDanTracks($listTracks)
	{
		$this->listGiaoDanTracks=$listTracks;
	}
	public function getlistLopGiaoLyTracks($listTracks)
	{
		$this->listLopGiaoLyTracks=$listTracks;
	}


}

/* End of file ChiTietLopGiaoLyCompareCL.php */
/* Location: ./application/controllers/ChiTietLopGiaoLyCompareCL.php */
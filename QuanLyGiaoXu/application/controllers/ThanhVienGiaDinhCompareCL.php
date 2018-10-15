<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class ThanhVienGiaDinhCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		require_once(APPPATH.'models/ThanhVienGiaDinhMD.php');
        $this->ThanhVienGiaDinhMD=new ThanhVienGiaDinhMD();
	}
	private $ThanhVienGiaDinhMD;
	private $listGiaDinhTracks;
	private $listGiaoDanTracks;
	public function compare()
	{
		if (isset($this->listGiaDinhTracks)&&count($this->listGiaDinhTracks)>0) {
			foreach ($this->listGiaDinhTracks as $value) {
				$this->importObjectChild($value,$this->data,"MaGiaDinh",$this->listGiaoDanTracks,"MaGiaoDan",$this->ThanhVienGiaDinhMD);
			}
		}
	}
	public function delete($maGiaoXuRieng)
	{
		$this->deleteObjecChild($this->tracks,"MaGiaDinh","MaGiaoDan",$this->ThanhVienGiaDinhMD,$maGiaoXuRieng);
	}
	public function getListGiaoDanTracks($listTracks)
	{
		$this->listGiaoDanTracks=$listTracks;
	}
	public function getListGiaDinhTracks($listTracks)
	{
		$this->listGiaDinhTracks=$listTracks;
	}
	
	// public function findTVGD($data,$listTVGDThayDoi)
	// {
	// 	foreach ($listTVGDThayDoi as $value) {
	// 		if ($value->MaGiaoDan=$data->MaGiaoDan&&$value->MaGiaDinh==$data->MaGiaDinh) {
	// 			return 1;
	// 		}
	// 	}
	// 	return 0;
	// }
}

/* End of file ThanhVienGiaDinhCompare.php */
/* Location: ./application/controllers/ThanhVienGiaDinhCompare.php */
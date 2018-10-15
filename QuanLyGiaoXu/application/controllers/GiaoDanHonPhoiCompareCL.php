<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class GiaoDanHonPhoiCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		require_once(APPPATH.'models/GiaoDanHonPhoiMD.php');
        $this->GiaoDanHonPhoiMD=new GiaoDanHonPhoiMD();
	}
	private $GiaoDanHonPhoiMD;
	private $listHonPhoiTracks;
	private $listGiaoDanTracks;
	public function compare()
	{
		if (isset($this->listHonPhoiTracks)&&count($this->listHonPhoiTracks)>0) {
			foreach ($this->listHonPhoiTracks as $value) {
				$this->importObjectChild($value,$this->data,"MaHonPhoi",$this->listGiaoDanTracks,"MaGiaoDan",$this->GiaoDanHonPhoiMD);
			}
		}
	}
	public function delete($maGiaoXuRieng)
	{
		$this->deleteObjecChild($this->tracks,"MaHonPhoi","MaGiaoDan",$this->GiaoDanHonPhoiMD,$maGiaoXuRieng);
	}
	public function getListGiaoDanTracks($listTracks)
	{
		$this->listGiaoDanTracks=$listTracks;
	}
	public function getlistHonPhoiTracks($listTracks)
	{
		$this->listHonPhoiTracks=$listTracks;
	}

}

/* End of file GiaoDanHonPhoiCompareCL.php */
/* Location: ./application/controllers/GiaoDanHonPhoiCompareCL.php */
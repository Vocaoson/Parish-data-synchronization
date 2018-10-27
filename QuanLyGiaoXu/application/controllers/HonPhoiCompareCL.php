<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class HonPhoiCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		require_once(APPPATH.'models/HonPhoiMD.php');
		$this->HonPhoiMD=new HonPhoiMD();
		require_once(APPPATH.'models/GiaoDanHonPhoiMD.php');
		$this->GiaoDanHonPhoiMD=new GiaoDanHonPhoiMD();
	}
	private $listGiaoDanHonPhoiCSV;
	private $GiaoDanHonPhoiMD;
	private $HonPhoiMD;
	private $listGDThayDoi;
	private $listGDHPThayDoi;

	public function compare()
	{

		foreach ($this->data as $data) {
			
			$honPhoiServer=$this->findHonPhoi($data);
			if ($this->deleteObjectMaster($data,$honPhoiServer,$this,$this->HonPhoiMD)) {
				continue;
			}
			$objectTrack=$this->importObjectMaster($data,'MaHonPhoi',$honPhoiServer,$this->HonPhoiMD);
			$this->tracks[]=$objectTrack;
		}
		// $this->deleteObjecChild($this->listGDHPThayDoi,'MaHonPhoi','MaGiaoDan',$this->GiaoDanHonPhoiMD,$this->MaGiaoXuRieng);
	}
	public function getListGiaoDanTracks($tracks)
	{
		$this->listGDThayDoi=$tracks;
	}
	public function delete($data)
	{
		
		$this->HonPhoiMD->delete($data->MaHonPhoi,$data->MaGiaoXuRieng);

		$this->GiaoDanHonPhoiMD->deleteMaHonPhoi($data->MaHonPhoi,$data->MaGiaoXuRieng);
	}
	public function findHonPhoi($data)
	{
		//check ma nhan dang
		if (!empty($data["MaNhanDang"])) {
			$rs=$this->HonPhoiMD->getHonPhoiByDK1($data);
		}
		
		if ($rs!=null) {
			return $rs;
		}
		//check ten hon phoi ngay hon phoi so hon phoi
		$rs=$this->HonPhoiMD->getHonPhoiByDK2($data);
		if ($rs!=null) {
			return $rs;
		}
		return null;
	}

}

/* End of file HonPhoiCompareCL.php */
/* Location: ./application/controllers/HonPhoiCompareCL.php */
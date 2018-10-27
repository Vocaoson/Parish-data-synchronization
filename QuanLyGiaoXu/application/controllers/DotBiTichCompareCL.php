<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class DotBiTichCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		require_once(APPPATH.'models/DotBiTichMD.php');
		$this->DotBiTichMD=new DotBiTichMD();
		require_once(APPPATH.'models/BiTichChiTietMD.php');
		$this->BiTichChiTietMD=new BiTichChiTietMD();
	}
	private $DotBiTichMD;
	private $BiTichChiTietMD;
	private $listBiTichChiTietCSV;
	private $listGDThayDoi;
	private $listBTCThayDoi;

	public function compare()
	{
		foreach ($this->data as $data) {
			$dotBTSV=$this->findDotBiTich($data);
			if ($this->deleteObjectMaster($data,$dotBTSV,$this,$this->DotBiTichMD)) {
				continue;
			}
			$objectTrack=$this->importObjectMaster($data,'MaDotBiTich',$dotBTSV,$this->DotBiTichMD);
			$this->tracks[]=$objectTrack;
		}
		
	}
	public function delete($data)
	{
		//Delete DotBiTich
		$this->DotBiTichMD->delete($data->MaDotBiTich,$data->MaGiaoXuRieng);
					//Delete Bi tich chi tiet
		$this->BiTichChiTietMD->deleteMaDotBiTich($data->MaDotBiTich,$data->MaGiaoXuRieng);
	}
	public function getListGiaoDanTracks($tracks)
	{
		$this->listGDThayDoi=$tracks;
	}
	
	public function findDotBiTich($data)
	{
		$rs=$this->DotBiTichMD->getByDK1($data['MoTa'],$data['NgayBiTich'],$data['LoaiBiTich'],$data['LinhMuc'],$data['MaGiaoXuRieng']);
		if ($rs) {
			return $rs;
		}
		return null;
	}
}

/* End of file DotBiTichCompareCL.php */
/* Location: ./application/controllers/DotBiTichCompareCL.php */
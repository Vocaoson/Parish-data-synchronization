<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class DotBiTichCompareCL extends CompareCL {

	public function index()
	{
		require_once(APPPATH.'models/DotBiTichMD.php');
		$this->DotBiTichMD=new DotBiTichMD();
	}
	private $DotBiTichMD;
	public function getListGiaoDanTracks($tracks)
	{
		$this->listGDThayDoi=$tracks;
	}
	public function compare()
	{
		foreach ($this->data as $data) {
			$objectTrack=$this->importDotBiTich($data);
			$this->tracks[]=$objectTrack;
		}
	}
	public function importDotBiTich($data)
	{
		$objectTrack=new stdClass();
		$objectTrack->updated=false;
		$objectTrack->oldIdIsCsv=true;
		$dotBTSV=$this->findDotBiTich($data);
	}
	public function findDotBiTich($data)
	{
		
		return null;
	}
}

/* End of file DotBiTichCompareCL.php */
/* Location: ./application/controllers/DotBiTichCompareCL.php */
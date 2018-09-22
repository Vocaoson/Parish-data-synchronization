<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class GiaoHoCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		$this->load->model('GiaoHo');
	}
	public function compare()
	{
		foreach ($this->data as $data) {
			$objectTrack=$this->importGiaoHo();
			$this->tracks[]=$objectTrack;

		}
	}
	public function deleteGiaoHo($maGiaoXuRieng)
	{
		$rs=$this->GiaoHo->getAllListIDGiaoHo($maGiaoXuRieng);
		if (count($rs)>0) {
			foreach ($rs as $data) {
				$idCSV=$this->findIdObjectCSV($this->tracks,$data->MaGiaoHo);
				if ($idCSV==0) {
					$this->GiaoHo->delete($data->MaGiaoHo,$data->MaGiaoXuRieng);
				}
			}
		}
	}
	public function importGiaoHo()
	{
		$giaoHoServer=$this->checkExist($data['MaNhanDang'],$data['MaGiaoXuRieng'])
		$objectTrack=new stdClass();
		$objectTrack->updated=false;
		$objectTrack->oldIdIsCsv=true;
		if ($giaoHoServer==null) {
					//Insert
			$objectTrack->oldId=$data["MaGiaoHo"];
			$objectTrack->newId=$this->GiaoHo->insert($data);
			$objectTrack->nowId=$objectTrack->newId;
		}
		else {
			$objectTrack->updated=true;
			if ($data['UpdateDate']>$giaoHoServer->UpdateDate) {
				$objectTrack->newId=$data["MaGiaoHo"];
				$objectTrack->oldId=$giaoHoServer->MaGiaoHo;
				$objectTrack->nowId=$giaoHoServer->MaGiaoHo;
				$objectTrack->oldIdIsCsv=false;
				$this->GiaDinhMD->update($data,$giaoHoServer->MaGiaoHo);
			}
			else {
				$objectTrack->oldIdIsCsv=true;
				$objectTrack->newId=$giaoHoServer->MaGiaoHo;
				$objectTrack->oldId=$data["MaGiaoHo"];
				$objectTrack->nowId=$giaoHoServer->MaGiaoHo;
			}
		}
		return $objectTrack;
	}
	public function checkExist($maNhanDang,$maGiaoXuRieng)
	{
		$rs=$this->GiaoHo->getByMaNhanDang($maNhanDang,$maGiaoXuRieng);
		if ($rs) {
			return $rs;
		}
		else {
			return null;
		}
	}

}

/* End of file GiaoHoCompareCL.php */
/* Location: ./application/controllers/GiaoHoCompareCL.php */
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
		// require_once('GiaoDanHonPhoiCompareCL.php');
		// $GDHP=new GiaoDanHonPhoiCompareCL('GiaoDanHonPhoi.csv',$this->dir);
		// $this->listGiaoDanHonPhoiCSV=$GDHP->data;
		foreach ($this->data as $data) {
			
			$honPhoiServer=$this->findHonPhoi($data);
			$objectTrack=$this->importObjectMaster($data,'MaHonPhoi',$honPhoiServer,$this->HonPhoiMD);
			// $this->listGDHPThayDoi=$this->importObjectChild($objectTrack,$this->listGiaoDanHonPhoiCSV,'MaHonPhoi',$this->listGDThayDoi,'MaGiaoDan',$this->GiaoDanHonPhoiMD);
			$this->tracks[]=$objectTrack;
		}
		// $this->deleteObjecChild($this->listGDHPThayDoi,'MaHonPhoi','MaGiaoDan',$this->GiaoDanHonPhoiMD,$this->MaGiaoXuRieng);
	}
		public function getListGiaoDanTracks($tracks)
	{
		$this->listGDThayDoi=$tracks;
	}
	public function delete($maGiaoXuRieng)
	{
		$rs=$this->HonPhoiMD->getAll($maGiaoXuRieng);
		if (count($rs)>0) {
			foreach ($rs as $data) {
				$idHP=$this->findIdObjectCSV($this->tracks,$data->MaHonPhoi);
				if ($idHP==0) {

					$this->HonPhoiMD->delete($data->MaHonPhoi,$maGiaoXuRieng);
					
					$this->GiaoDanHonPhoiMD->deleteMaHonPhoi($data->MaHonPhoi,$maGiaoXuRieng);
				}
			}
		}
	}
	// public function importGiaoDanHonPhoi($objectTrack)
	// {
	// 	if ($objectTrack->updated) {
	// 		//update
	// 		if (!$objectTrack->oldIdIsCsv) {
	// 			$gdhpCSV=$this->getListByID($this->listGiaoDanHonPhoiCSV,'MaHonPhoi',$objectTrack->newId);
	// 		}
	// 	}
	// 	else {
	// 		//insert
	// 		$gdhpCSV=$this->getListByID($this->listGiaoDanHonPhoiCSV,'MaHonPhoi',$objectTrack->oldId);
	// 	}
	// 	if (isset($gdhpCSV)&&count($gdhpCSV)>0) {
	// 		foreach ($gdhpCSV as $data) {
	// 			$maGiaoDan=$this->findIdObjectSV($this->listGDThayDoi,$data["MaGiaoDan"]);
	// 			if ($maGiaoDan==0) {
	// 				continue;
	// 			}
	// 			$rs=$this->GiaoDanHonPhoiMD->findGDHPwithID($objectTrack->nowID,$maGiaoDan,$data['MaGiaoXuRieng']);
	// 			$objectTrack=new stdClass();
	// 			$objectTrack->MaGiaoDan=$maGiaoDan;//luu ma gioa dan
	// 			$objectTrack->MaHonPhoi=$objectTrack->nowId;//luu ma gia dinh
	// 			$this->listGDHPThayDoi[]=$objectTrack;
	// 			if ($rs) {
	// 				if ($data['UpdateDate']>$rs->UpdateDate) {
	// 					$this->GiaoDanHonPhoiMD->update($data,$maGiaoDan,$objectTrack->nowID);
	// 				}
	// 				continue;
	// 			}
	// 			$this->GiaoDanHonPhoiMD->insert($data,$maGiaoDan,$objectTrack->nowID);
				
	// 		}
	// 	}

	// }
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
	// public function importHonPhoi($data)
	// {
	// 	$honPhoiServer=$this->findHonPhoi($data);
	// 	$objectTrack=new stdClass();
	// 	$objectTrack->updated=false;
	// 	$objectTrack->oldIdIsCsv=true;
		
	// 	if ($honPhoiServer==null) {
	// 			//Insert
	// 		$objectTrack->oldId=$data["MaHonPhoi"];
	// 		$objectTrack->newId=$this->HonPhoiMD->insert($data);
	// 		$objectTrack->nowId=$objectTrack->newId;
	// 	}
	// 	else {
	// 			//Update
	// 			//Xu ly du lieu Null
	// 		$data=$this->processDataNull($honPhoiServer,$data);
	// 			//check time UpdateDate
	// 		$objectTrack->updated=true;
	// 		if ($data['UpdateDate']>$honPhoiServer->UpdateDate) {
	// 			$objectTrack->newId=$data["MaHonPhoi"];
	// 			$objectTrack->oldId=$honPhoiServer->MaHonPhoi;
	// 			$objectTrack->nowId=$honPhoiServer->MaHonPhoi;
	// 			$objectTrack->oldIdIsCsv=false;
	// 			$this->HonPhoiMD->update($data,$honPhoiServer->MaHonPhoi);
	// 		}
	// 		else {
	// 			$objectTrack->oldIdIsCsv=true;
	// 			$objectTrack->newId=$honPhoiServer->MaHonPhoi;
	// 			$objectTrack->oldId=$data["MaHonPhoi"];
	// 			$objectTrack->nowId=$honPhoiServer->MaHonPhoi;
	// 		}
	// 	}
	// 	return $objectTrack;
	// }
}

/* End of file HonPhoiCompareCL.php */
/* Location: ./application/controllers/HonPhoiCompareCL.php */
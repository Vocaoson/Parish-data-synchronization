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
		require_once('BiTichChiTietCompareCL.php');
		$BTCT=new BiTichChiTietCompareCL('BiTichChiTiet.csv',$this->dir);
		$this->listBiTichChiTietCSV=$BTCT->data;
		foreach ($this->data as $data) {
			$dotBTSV=$this->findDotBiTich($data);
			$objectTrack=$this->importObjectMaster($data,'MaDotBiTich',$dotBTSV,$this->DotBiTichMD);
			$this->listBTCThayDoi=$this->importObjectChild($objectTrack,$this->listBiTichChiTietCSV,'MaDotBiTich',$this->listGDThayDoi,'MaGiaoDan',$this->BiTichChiTietMD);
			$this->tracks[]=$objectTrack;
		}
		$this->deleteObjecChild($this->listBTCThayDoi,'MaDotBiTich','MaGiaoDan',$this->BiTichChiTietMD,$this->MaGiaoXuRieng);
		
	}
	public function delete($maGiaoXuRieng)
	{
		$rs=$this->DotBiTichMD->getAll($maGiaoXuRieng);
		if (count($rs)>0) {
			foreach ($rs as $data) {
				$idDBT=$this->findIdObjectCSV($this->tracks,$data->MaDotBiTich);
				if ($idDBT==0) {
					//Delete DotBiTich
					$this->DotBiTichMD->delete($data->MaDotBiTich,$maGiaoXuRieng);
					//Delete Bi tich chi tiet
					$this->BiTichChiTietMD->deleteMaDotBiTich($data->MaDotBiTich,$maGiaoXuRieng);
				}
			}
		}
	}
	public function getListGiaoDanTracks($tracks)
	{
		$this->listGDThayDoi=$tracks;
	}
	// public function importBTCT($objectTrack)
	// {
	// 	if ($objectTrack->updated) {
	// 		//update
	// 		if (!$objectTrack->oldIdIsCsv) {
	// 			$btctCSV=$this->getListByID($this->listBiTichChiTietCSV,'MaDotBiTich',$objectTrack->newId);
	// 		}
	// 	}
	// 	else {
	// 		//insert
	// 		$btctCSV=$this->getListByID($this->listBiTichChiTietCSV,'MaDotBiTich',$objectTrack->oldId);
	// 	}
	// 	if (isset($btctCSV)&&count($btctCSV)>0) {
	// 		foreach ($btctCSV as $data) {
	// 			$maGiaoDan=$this->findIdObjectSV($this->listGDThayDoi,$data["MaGiaoDan"]);
	// 			if ($maGiaoDan==0) {
	// 				continue;
	// 			}
	// 			$rs=$this->BiTichChiTietMD->findBTCTwithID($objectTrack->nowID,$maGiaoDan,$data['MaGiaoXuRieng']);
	// 			$objectTrack=new stdClass();
	// 			$objectTrack->MaGiaoDan=$maGiaoDan;//luu ma gioa dan
	// 			$objectTrack->MaDotBiTich=$objectTrack->nowId;//luu ma gia dinh
	// 			$this->listBTCThayDoi[]=$objectTrack;
	// 			if ($rs) {
	// 				if ($data['UpdateDate']>$rs->UpdateDate) {
	// 					$this->BiTichChiTietMD->update($data,$maGiaoDan,$objectTrack->nowID);
	// 				}
	// 				continue;
	// 			}
	// 			$this->BiTichChiTietMD->insert($data,$maGiaoDan,$objectTrack->nowID);
	
	// 		}
	// 	}

	// }
	// public function getBTCTCSV($maDotBiTich)
	// {
	// 	$btctCSV=array();
	// 	foreach ($this->listBiTichChiTietCSV as $rowCSV) {
	// 		if ($rowCSV["MaDotBiTich"]==$maDotBiTich) {
	// 			$btctCSV[]=$rowCSV;
	// 		}				
	// 	}
	// 	return $btctCSV;
	// }
	// public function importDotBiTich($data)
	// {
	// 	$objectTrack=new stdClass();
	// 	$objectTrack->updated=false;
	// 	$objectTrack->oldIdIsCsv=true;

	// 	$dotBTSV=$this->findDotBiTich($data);
	// 	if ($dotBTSV==null) {
	// 		//Insert
	// 		$objectTrack->oldID=$data['MaDotBiTich'];
	// 		$objectTrack->newID=$this->DotBiTichMD->insert($data);
	// 		$objectTrack->nowID=$objectTrack->newID;
	// 	}
	// 	else {
	// 		//update
	// 		$data=$this->processDataNull($dotBTSV,$data);
	// 		$objectTrack->updated=true;
	// 		if ($data['UpdateDate']>$dotBTSV->UpdateDate) {
	// 			$objectTrack->oldIdIsCsv=false;
	// 			$objectTrack->newID=$data['MaDotBiTich'];
	// 			$objectTrack->oldID=$dotBTSV->MaDotBiTich;
	// 			$objectTrack->nowID=$dotBTSV->MaDotBiTich;
	// 			$this->DotBiTichMD->update($data,$dotBTSV->MaDotBiTich);
	// 		}
	// 		else {
	// 			$objectTrack->oldIdIsCsv=true;
	// 			$objectTrack->oldID=$data['MaDotBiTich'];
	// 			$objectTrack->nowID=$dotBTSV->MaDotBiTich;
	// 			$objectTrack->newID=$dotBTSV->MaDotBiTich;
	// 		}
	// 	}
	// 	return $objectTrack;
	// }
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
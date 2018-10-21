<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class KhoiGiaoLyCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		require_once(APPPATH.'models/KhoiGiaoLyMD.php');
		$this->KhoiGiaoLyMD=new KhoiGiaoLyMD();

		require_once(APPPATH.'models/LopGiaoLyMD.php');
		$this->LopGiaoLyMD=new LopGiaoLyMD();

		require_once(APPPATH.'models/GiaoLyVienMD.php');
		$this->GiaoLyVienMD=new GiaoLyVienMD();

		require_once(APPPATH.'models/ChiTietLopGiaoLyMD.php');
		$this->ChiTietLopGiaoLyMD=new ChiTietLopGiaoLyMD();
		require_once('LopGiaoLyCompareCL.php');
		$this->LopGiaoLyCompare=new LopGiaoLyCompareCL('LopGiaoLy.csv',$this->dir);
		$this->listLopGiaoLyCSV=$this->LopGiaoLyCompare->data;
	}
	private $KhoiGiaoLyMD;
	private $LopGiaoLyMD;
	private $GiaoLyVienMD;
	private $ChiTietLopGiaoLyMD;
	private $listGDThayDoi;
	private $LopGiaoLyCompare;
	public function compare()
	{
		
		foreach ($this->data as $data) {
			$khoiGiaoLySV=$this->findKhoiGiaoLy($data);
			$maGiaoDan=$this->findIdObjectSV($this->listGDThayDoi,$data['NguoiQuanLy']);
			if ($maGiaoDan==0) {
				continue;
			}
			else {
				$data['NguoiQuanLy']=$maGiaoDan;
			}
			$objectTrack=$this->importObjectMaster($data,$khoiGiaoLySV,$this->KhoiGiaoLyMD);
			$this->tracks[]=$objectTrack;
		}
	}
	public function getListGiaoDanTracks($tracks)
	{
		$this->listGDThayDoi=$tracks;
	}
	public function findKhoiGiaoLy($data)
	{
		//DK1 ten khoi
		$rs=$this->KhoiGiaoLyMD->getByDK1($data);
		if (isset($rs)&&count($rs)>0) {
			foreach ($rs as $item) {
				if ($this->compareLopGiaoLy($item,$data)) {
					return $item;
				}
			}
		}
		return null;
	}
	public function compareLopGiaoLy($khoiSV,$khoiCSV)
	{
		$lglSV=$this->LopGiaoLyMD->getByMaKhoi($khoiSV->MaKhoi,$khoiSV->MaGiaoXuRieng);
		$lglCSV=$this->getListByID($this->listLopGiaoLyCSV,'MaKhoi',$khoiCSV['MaKhoi']);
		if (count($lglSV)==0&&count($lglCSV)==0) {
			return true;
		}
		if (count($lglSV)==0||count($lglCSV)==0) {
			return false;
		}
		for ($i = 0; $i < count($lglCSV); $i++) {
			for ($j = 0; $j <count($lglSV) ; $j++) {
				if ($this->LopGiaoLyCompare->compareHocVien($lglSV[$i],$lglCSV[$j])) {
					// return $lglSV[$i];
					return true;
				}
			}
		}
		return false;

	}

	public function delete($maGiaoXuRieng)
	{
		$rs=$this->KhoiGiaoLyMD->getAll($maGiaoXuRieng);
		if (count($rs)>0) {
			foreach ($rs as $data) {
				$idKGL=$this->findIdObjectCSV($this->tracks,$data->MaKhoi);
				if ($idKGL==0) {
					//Xoa khoi
					$this->KhoiGiaoLyMD->delete($data->MaKhoi,$maGiaoXuRieng);
					//delete Lop Giao Ly
					$listLopGiaoLy=$this->LopGiaoLyMD->getByMaKhoi($data->MaKhoi,$maGiaoXuRieng);
					if (count($listLopGiaoLy)>0) {
						foreach ($listLopGiaoLy as $data2) {
							$this->LopGiaoLyMD->deleteMaLop($data2->MaLop,$data2->MaGiaoXuRieng);
							$this->GiaoLyVienMD->deleteMaLop($data2->MaLop,$data2->MaGiaoXuRieng);
							$this->ChiTietLopGiaoLyMD->deleteMaLop($data2->MaLop,$data2->MaGiaoXuRieng);
						}
					}
				}
			}
		}
	}

	
	// public function importKhoiGiaoLy($data)
	// {
	// 	$khoiGiaoLySV=$this->findKhoiGiaoLy($data);
	// 	$objectTrack=new stdClass();
	// 	$objectTrack->updated=false;
	// 	$objectTrack->oldIdIsCsv=true;

	// 	if ($khoiGiaoLySV==null) {
	// 			//Insert
	// 		$objectTrack->oldId=$data["MaKhoi"];
	// 		$objectTrack->newId=$this->KhoiGiaoLyMD->insert($data);
	// 		$objectTrack->nowId=$objectTrack->newId;
	// 	}
	// 	else {
	// 			//Update
	// 			//Xu ly du lieu Null
	// 		$data=$this->processDataNull($khoiGiaoLySV,$data);
	// 			//check time UpdateDate
	// 		$objectTrack->updated=true;
	// 		if ($data['UpdateDate']>$khoiGiaoLySV->UpdateDate) {
	// 			$objectTrack->newId=$data["MaKhoi"];
	// 			$objectTrack->oldId=$khoiGiaoLySV->MaKhoi;
	// 			$objectTrack->nowId=$khoiGiaoLySV->MaKhoi;
	// 			$objectTrack->oldIdIsCsv=false;
	// 			$this->KhoiGiaoLyMD->update($data,$khoiGiaoLySV->MaKhoi);
	// 		}
	// 		else {
	// 			$objectTrack->oldIdIsCsv=true;
	// 			$objectTrack->newId=$khoiGiaoLySV->MaKhoi;
	// 			$objectTrack->oldId=$data["MaKhoi"];
	// 			$objectTrack->nowId=$khoiGiaoLySV->MaKhoi;
	// 		}
	// 	}
	// 	return $objectTrack;
	// }
}

/* End of file KhoiGiaoLyCompareCL.php */
/* Location: ./application/controllers/KhoiGiaoLyCompareCL.php */
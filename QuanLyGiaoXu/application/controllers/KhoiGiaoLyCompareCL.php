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
	}
	private $KhoiGiaoLyMD;
	private $LopGiaoLyMD;
	private $GiaoLyVienMD;
	private $ChiTietLopGiaoLyMD;
	private $listGDThayDoi;
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
		//ten khoi
		$rs=$this->KhoiGiaoLyMD->getByDK1($data);
		if ($rs) {
			return $rs;
		}
		return null;
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
					$listLopGiaoLy=$this->LopGiaoLy->getByMaKhoi($data->MaKhoi,$maGiaoXuRieng);
					if (count($listLopGiaoLy)>0) {
						foreach ($listLopGiaoLy as $data2) {
							$this->LopGiaoLy->deleteMaLop($data2->MaLop,$data2->MaGiaoXuRieng);
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
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class GiaDinhCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		require_once(APPPATH.'models/GiaDinhMD.php');
        $this->GiaDinhMD=new GiaDinhMD();
		
		require_once(APPPATH.'models/ThanhVienGiaDinhMD.php');
        $this->ThanhVienGiaDinhMD=new ThanhVienGiaDinhMD();
	}
	private $GiaDinhMD;
	private $ThanhVienGiaDinhMD;
	private $listThanhVienGiaDinhCSV;
	private $listGDThayDoi;
	private $listTVGDThayDoi;
	/**
	 * Compare Gia dinh
	 * @return [type] [description]
	 */
	public function compare()
	{
		require_once('ThanhVienGiaDinhCompareCL.php');
		$TVGD=new ThanhVienGiaDinhCompareCL('ThanhVienGiaDinh.csv',$this->dir);
		$this->listThanhVienGiaDinhCSV=$TVGD->data;

		
		foreach ($this->data as $data) {
			$giaDinhServer=$this->findGiaDinh($data);
			$data['MaGiaoHo']=$this->findIdObjectSV($this->listGHThayDoi,$data['MaGiaoHo']);
			$objectTrack=$this->importObjectMaster($data,'MaGiaDinh',$giaDinhServer,$this->GiaDinhMD);
			// $this->listTVGDThayDoi[]=$this->importObjectChild($objectTrack,$this->listThanhVienGiaDinhCSV,'MaGiaDinh',$this->listGDThayDoi,'MaGiaoDan',$this->ThanhVienGiaDinhMD);
			$this->tracks[]=$objectTrack;
		}
  		// $this->deleteObjecChild($this->listTVGDThayDoi,'MaGiaDinh','MaGiaoDan',$this->ThanhVienGiaDinhMD,$this->MaGiaoXuRieng);
	}
	public function delete($maGiaoXuRieng)
	{
		$rs=$this->GiaDinhMD->getAllListIDGiaDinh($maGiaoXuRieng);
		if (count($rs)>0) {
			foreach ($rs as $data) {
				$idGDCSV=$this->findIdObjectCSV($this->tracks,$data->MaGiaDinh);
				if ($idGDCSV==0) {
					//Delete gia dinh
					$this->GiaDinhMD->delete($data->MaGiaDinh,$maGiaoXuRieng);
					//Delete tvgd
					$this->ThanhVienGiaDinhMD->deleteMaGiaDinh($data->MaGiaDinh,$maGiaoXuRieng);
				}
			}
		}
	}
	/**
	 * Import thanh vien gia dinh
	 * @param  [stdClass] $objectTrack [lưu thông tin]
	 * @return [type]              [description]
	 */
	// public function importTVGD($objectTrack)
	// {
	
	// 	//get list thanh vien gia dinh trong file csv
	// 	if ($objectTrack->updated) {
	// 		//update
	// 		if (!$objectTrack->oldIdIsCsv) {
	// 			$tvgdCSV=$this->getListByID($this->listThanhVienGiaDinhCSV,'MaGiaDinh',$objectTrack->newId);
	// 		}
	// 	}
	// 	else {
	// 		//insert
	// 		$tvgdCSV=$this->getListByID($this->listThanhVienGiaDinhCSV,'MaGiaDinh',$objectTrack->oldId);
	// 	}
	// 	if (isset($tvgdCSV)&&count($tvgdCSV)>0) {
	// 		foreach ($tvgdCSV as $data) {
	// 			$maGiaoDan=$this->findIdObjectSV($this->listGDThayDoi,$data["MaGiaoDan"]);
	// 			$vaitro=$data['VaiTro'];
	// 			if ($maGiaoDan==0) {
	// 				continue;
	// 			}
	// 			$rs=$this->ThanhVienGiaDinhMD->findTVGDwithID($maGiaoDan,$objectTrack->nowId,$data['MaGiaoXuRieng']);
	// 			$objectTrack=new stdClass();
	// 			$objectTrack->MaGiaoDan=$maGiaoDan;//luu ma gioa dan
	// 			$objectTrack->MaGiaDinh=$objectTrack->nowId;//luu ma gia dinh
	// 			$this->listTVGDThayDoi[]=$objectTrack;
	// 			if ($rs) {
	// 				if ($data['UpdateDate']>$rs->UpdateDate)) {
	// 					$$data=$this->processDataNull($rs,$data);
	// 					$this->ThanhVienGiaDinhMD->update($data,$maGiaoDan,$objectTrack->nowId);
	// 				}
					 
	// 				continue;
	// 			}
	// 			// if ($vaitro==0||$vaitro==1) {
	// 			// 	$rs=$this->ThanhVienGiaDinhMD->findTVGDwithIDVT($maGiaoDan,$vaitro,$objectTrack->nowId,$data['MaGiaoXuRieng']);
	// 			// 	if ($rs) {
	// 			// 		continue;
	// 			// 	}
	// 			// }
	// 			$this->ThanhVienGiaDinhMD->insert($data,$maGiaoDan,$objectTrack->nowId);
				
	// 		}
	// 	}
		
	// }
	/*
	import gia dinh
	 */
	// public function importGiaDinh($data)
	// {
	// 	$giaDinhServer=$this->findGiaDinh($data);
	// 	$objectTrack=new stdClass();
	// 	$objectTrack->updated=false;
	// 	$objectTrack->oldIdIsCsv=true;
	// 	$data['MaGiaoHo']=$this->findIdObjectSV($this->listGHThayDoi,$data['MaGiaoHo']);
	// 	if ($giaDinhServer==null) {
	// 			//Insert
	// 		$objectTrack->oldId=$data["MaGiaDinh"];
	// 		$objectTrack->newId=$this->GiaDinhMD->insert($data);
	// 		$objectTrack->nowId=$objectTrack->newId;
	// 	}
	// 	else {
	// 			//Update
	// 			//Xu ly du lieu Null
	// 		$data=$this->processDataNull($giaDinhServer,$data);
	// 			//check time UpdateDate
	// 		$objectTrack->updated=true;
	// 		if ($data['UpdateDate']>$giaDinhServer->UpdateDate) {
	// 			$objectTrack->newId=$data["MaGiaDinh"];
	// 			$objectTrack->oldId=$giaDinhServer->MaGiaDinh;
	// 			$objectTrack->nowId=$giaDinhServer->MaGiaDinh;
	// 			$objectTrack->oldIdIsCsv=false;
	// 			$this->GiaDinhMD->update($data,$giaDinhServer->MaGiaDinh);
	// 		}
	// 		else {
	// 			$objectTrack->oldIdIsCsv=true;
	// 			$objectTrack->newId=$giaDinhServer->MaGiaDinh;
	// 			$objectTrack->oldId=$data["MaGiaDinh"];
	// 			$objectTrack->nowId=$giaDinhServer->MaGiaDinh;
	// 		}
	// 	}
	// 	return $objectTrack;
	// }
	private $listGHThayDoi;
    public function getListGiaoHoTracks($tracks)
    {
        $this->listGHThayDoi=$tracks;
    }
	public function getListGiaoDanTracks($tracks)
	{
		$this->listGDThayDoi=$tracks;
	}

	/*
	Tìm gia đình trên server
	 */
	public function findGiaDinh($giadinh)
	{
		// condition DK1: mã nhận dạng
		$rs=$this->GiaDinhMD->getGiaDinhByDK1($giadinh["MaNhanDang"],$giadinh["MaGiaoXuRieng"]);
		if ($rs!=null) {
			return $rs;
		}
		// condition: Tên gia đình,địa chỉ, sdt,ghi chu
		$rs=$this->GiaDinhMD->getGiaDinhByDK2($giadinh["MaGiaoXuRieng"],$giadinh["TenGiaDinh"],$giadinh["DiaChi"],$giadinh["DienThoai"],$giadinh["GhiChu"]);
		if ($rs!=null) {
			// kiem tra su giong nhau giua 2 gia dinh
			// get TVGD ben server
			$tvgdServer=$this->ThanhVienGiaDinhMD->getTVGD($rs->MaGiaoXuRieng,$rs->MaGiaDinh);
			$dicVaiTro=array();
			foreach ($tvgdServer as $data) {
				$idvt=new stdClass();
				$idvt->vaitro=$data->VaiTro;
				$idvt->id=$this->findIdObjectCSV($this->listGDThayDoi,$data->MaGiaoDan);
				$dicVaiTro[]=$idvt;
			}
			// get TVGD ben csv
			$tvgdCSV=$this->getListByID($this->listThanhVienGiaDinhCSV,'MaGiaDinh',$giadinh["MaGiaDinh"]);
			
			foreach ($tvgdCSV as $data) {
				if ($this->containerDic($dicVaiTro,$data["MaGiaoDan"],'MaGiaoDan',$data["VaiTro"],'VaiTro')) {
					return $rs;
				}
			}
		}
		return null;
	}
	/*
	Container in Dic
	 */
	// public function containerDic($dic,$id,$vt)
	// {
	// 	foreach ($dic as $data) {
	// 		if ($data->vaitro==$vt&&$data->id==$id) {
	// 			return true;
	// 		}
	// 	}
	// 	return false;
	// }
	/*
	lay tvgd 
	 */
	// public function getTVGDCSV($maGiaDinh)
	// {
	// 	$tvgdCSV=array();
	// 	foreach ($this->listThanhVienGiaDinhCSV as $rowCSV) {
	// 		if ($rowCSV["MaGiaDinh"]==$maGiaDinh) {
	// 			$tvgdCSV[]=$rowCSV;
	// 		}				
	// 	}
	// 	return $tvgdCSV;
	// }


}

/* End of file GiaDinhCompareCL.php */
/* Location: ./application/controllers/GiaDinhCompareCL.php */
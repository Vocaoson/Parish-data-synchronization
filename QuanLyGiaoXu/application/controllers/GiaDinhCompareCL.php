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
			if ($this->deleteObjectMaster($data,$giaDinhServer,$this,$this->GiaDinhMD)) {
				continue;
			}
			$data['MaGiaoHo']=$this->findIdObjectSV($this->listGHThayDoi,$data['MaGiaoHo']);
			$objectTrack=$this->importObjectMaster($data,'MaGiaDinh',$giaDinhServer,$this->GiaDinhMD);
			// $this->listTVGDThayDoi[]=$this->importObjectChild($objectTrack,$this->listThanhVienGiaDinhCSV,'MaGiaDinh',$this->listGDThayDoi,'MaGiaoDan',$this->ThanhVienGiaDinhMD);
			$this->tracks[]=$objectTrack;
		}
  		// $this->deleteObjecChild($this->listTVGDThayDoi,'MaGiaDinh','MaGiaoDan',$this->ThanhVienGiaDinhMD,$this->MaGiaoXuRieng);
	}

	public function delete($data)
	{
		//Delete gia dinh
		$this->GiaDinhMD->delete($data->MaGiaDinh,$data->MaGiaoXuRieng);
					//Delete tvgd
		$this->ThanhVienGiaDinhMD->deleteMaGiaDinh($data->MaGiaDinh,$data->MaGiaoXuRieng);
	}

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
		if (!empty($giadinh["MaNhanDang"])) {
			$rs=$this->GiaDinhMD->getGiaDinhByDK1($giadinh["MaNhanDang"],$giadinh["MaGiaoXuRieng"]);
		}
		if ($rs!=null) {
			return $rs;
		}
		// condition: Tên gia đình,địa chỉ, sdt,ghi chu
		$rs=$this->GiaDinhMD->getGiaDinhByDK2($giadinh["MaGiaoXuRieng"],$giadinh["TenGiaDinh"],$giadinh["DiaChi"],$giadinh["DienThoai"],$giadinh["GhiChu"]);
		if ($rs!=null&&count($rs)>0) {
			foreach ($rs as $giaDinhDB) {
				// kiem tra su giong nhau giua 2 gia dinh
				// get TVGD ben server
				$tvgdServer=$this->ThanhVienGiaDinhMD->getTVGD($giaDinhDB->MaGiaoXuRieng,$giaDinhDB->MaGiaDinh);
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
						return $giaDinhDB;
					}
				}
			}
		}
		return null;
	}
	

}

/* End of file GiaDinhCompareCL.php */
/* Location: ./application/controllers/GiaDinhCompareCL.php */
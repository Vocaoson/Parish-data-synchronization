<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class LopGiaoLyCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		require_once(APPPATH.'models/LopGiaoLyMD.php');
		$this->LopGiaoLyMD=new LopGiaoLyMD();

		require_once(APPPATH.'models/GiaoLyVienMD.php');
		$this->GiaoLyVienMD=new GiaoLyVienMD();

		require_once(APPPATH.'models/ChiTietLopGiaoLyMD.php');
		$this->ChiTietLopGiaoLyMD=new ChiTietLopGiaoLyMD();

		require_once('GiaoLyVienCompareCL.php');
		$GLV=new GiaoLyVienCompareCL('GiaoLyVien.csv',$this->dir);
		$this->listGiaoLyVienCSV=$GLV->data;

		require_once('ChiTietLopGiaoLyCompareCl.php');
		$CTLGL=new ChiTietLopGiaoLyCompareCl('ChiTietLopGiaoLy.csv',$this->dir);
		$this->listCTLGLCSV=$CTLGL->data;
	}
	private $LopGiaoLyMD;
	private $GiaoLyVienMD;
	private $ChiTietLopGiaoLyMD;
	private $listKhoiLopThayDoi;
	private $listGDThayDoi;
	private $listGLVThayDoi;
	private $listCTLGLThayDoi;
	private $listGiaoLyVienCSV;
	private $listCTLGLCSV;

	public function compare()
	{

		
		foreach ($this->data as $data) {
			$maKhoi=$this->findIdObjectSV($this->listKhoiLopThayDoi,$data['MaKhoi']);
			
			if ($maKhoi==0) {
				continue;
			}
			else {
				$data['MaKhoi']=$maKhoi;
			}
			$lopGiaoLySV=$this->findLopGiaoLy($data);
			if ($this->deleteObjectMaster($data,$lopGiaoLySV,$this,$this->LopGiaoLyMD)) {
				continue;
			}
			$objectTrack=$this->importObjectMaster($data,'MaLop',$lopGiaoLySV,$this->LopGiaoLyMD);
				// $this->listGLVThayDoi=$this->importObjectChild($objectTrack,$this->listGiaoLyVienCSV,'MaLop',$this->listGDThayDoi,'MaGiaoDan',$this->GiaoLyVienMD);

				// $this->listCTLGLThayDoi=$this->importObjectChild($objectTrack,$this->listCTLGLCSV,'MaLop',$this->listGDThayDoi,'MaGiaoDan',$this->ChiTietLopGiaoLyMD);
			$this->tracks[]=$objectTrack;
		}
		
		
		// $this->deleteObjecChild($this->listGLVThayDoi,'MaLop','MaGiaoDan',$this->GiaoLyVienMD,$this->MaGiaoXuRieng);
		// $this->deleteObjecChild($this->listCTLGLThayDoi,'MaLop','MaGiaoDan',$this->ChiTietLopGiaoLyMD,$this->MaGiaoXuRieng);
	}
	public function getListKhoiLopTracks($tracks)
	{
		$this->listKhoiLopThayDoi=$tracks;
	}
	public function getListGiaoDanTracks($tracks)
	{
		$this->listGDThayDoi=$tracks;
	}
	public function delete($data)
	{
		$this->LopGiaoLyMD->deleteMaLop($data->MaLop,$data->MaGiaoXuRieng);

		$this->GiaoLyVienMD->deleteMaLop($data->MaDotBiTich,$data->MaGiaoXuRieng);

		$this->ChiTietLopGiaoLyMD->deleteMaLop($data->MaDotBiTich,$data->MaGiaoXuRieng);
	}
	public function findLopGiaoLy($data)
	{
		//Ten Lop,Nam,Khoi
		$rs=$this->LopGiaoLyMD->getByDK1($data);
		if ($rs) {
			if ($this->compareHocVien($rs,$data)) {
				return $rs;
			}
		}
		return null;
	}
	public function compareHocVien($lopSV,$lopCSV)
	{
		$hocvienSV=$this->ChiTietLopGiaoLyMD->getByMaLop($lopSV->MaLop);
		$hocvienCSV=$this->getListByID($this->listCTLGLCSV,'MaLop',$lopCSV['MaLop']);
		if (count($hocvienSV)==0&&count($hocvienCSV)>0) {
			return true;
		}
		if (count($hocvienSV)==0||count($hocvienCSV)==0) {
			return false;
		}
		$dicCTLGL=array();
		foreach ($hocvienSV as $data) {
			$idSTT=new stdClass();
			$idSTT->stt=$data->SoThuTu;
			$idSTT->id=$this->findIdObjectCSV($this->listGDThayDoi,$data->MaGiaoDan);
			$dicCTLGL[]=$idSTT;
		}
		foreach ($hocvienCSV as $data) {
			if ($this->containerDic($dicCTLGL,$data["MaGiaoDan"],'MaGiaoDan',$data["SoThuTu"],'SoThuTu')) {
				return true;
			}
		}
		return false;
	}

}

/* End of file LopGiaoLyCompareCL.php */
/* Location: ./application/controllers/LopGiaoLyCompareCL.php */
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
		require_once('GiaoLyVienCompareCL.php');
		$GLV=new GiaoLyVienCompareCL('GiaoLyVien.csv',$this->dir);
		$this->listGiaoLyVienCSV=$GLV->data;

		require_once('ChiTietLopGiaoLyCompareCl.php');
		$CTLGL=new ChiTietLopGiaoLyCompareCl('ChiTietLopGiaoLy.csv',$this->dir);
		$this->listCTLGLCSV=$CTLGL->data;
		if ($maGiaoXuRieng!=null) {
			foreach ($this->data as $data) {
				$maKhoi=$this->findIdObjectSV($this->listKhoiLopThayDoi,$data['MaKhoi']);
				if ($maKhoi==0) {
					continue;
				}
				else {
					$data['MaKhoi']=$maKhoi;
				}
				$lopGiaoLySV=$this->findLopGiaoLy($data);
				$objectTrack=$this->importObjectMaster($data,$lopGiaoLySV,$this->LopGiaoLyMD);
				$this->listGLVThayDoi=$this->importObjectChild($objectTrack,$this->listGiaoLyVienCSV,'MaLop',$this->listGDThayDoi,'MaGiaoDan',$this->GiaoLyVienMD);

				$this->listCTLGLThayDoi=$this->importObjectChild($objectTrack,$this->listCTLGLCSV,'MaLop',$this->listGDThayDoi,'MaGiaoDan',$this->ChiTietLopGiaoLyMD);
				$this->tracks[]=$objectTrack;
			}
		}
		
		$this->deleteObjecChild($this->listGLVThayDoi,'MaLop','MaGiaoDan',$this->GiaoLyVienMD,$this->MaGiaoXuRieng);
		$this->deleteObjecChild($this->listCTLGLThayDoi,'MaLop','MaGiaoDan',$this->ChiTietLopGiaoLyMD,$this->MaGiaoXuRieng);
	}
	public function getListKhoiLopTracks($tracks)
	{
		$this->listKhoiLopThayDoi=$tracks;
	}
	public function getListGiaoDanTracks($tracks)
	{
		$this->listGDThayDoi=$tracks;
	}
	public function delete($maGiaoXuRieng)
	{
		$rs=$this->LopGiaoLyMD->getAll($maGiaoXuRieng);
		if (count($rs)>0) {
			foreach ($rs as $data) {
				$idLGL=$this->findIdObjectCSV($this->tracks,$data->MaLop);
				if ($idLGL==0) {
					
					$this->LopGiaoLyMD->deleteMaLop($data->MaLop,$maGiaoXuRieng);
					
					$this->GiaoLyVienMD->deleteMaLop($data->MaDotBiTich,$maGiaoXuRieng);

					$this->ChiTietLopGiaoLyMD->deleteMaLop($data->MaDotBiTich,$maGiaoXuRieng);
				}
			}
		}
	}
	public function findLopGiaoLy($data)
	{
		//Ten Lop,Nam,Khoi
		$rs=$this->LopGiaoLyMD->getByDK1($data);
		if ($rs) {
			return $rs;
		}
		return null;
	}

}

/* End of file LopGiaoLyCompareCL.php */
/* Location: ./application/controllers/LopGiaoLyCompareCL.php */
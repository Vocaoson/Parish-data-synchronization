<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class GiaoHoCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		$this->load->model('GiaoHoMD');
		$this->load->model('GiaoDanMD');
		$this->load->model('ThanhVienGiaDinhMD');
		$this->load->model('BiTichChiTietMD');
		$this->load->model('GiaoDanHonPhoiMD');
		$this->load->model('ChuyenXuMD');
		$this->load->model('TanHienMD');
		$this->load->model('RaoHonPhoiMD');
		$this->load->model('GiaoLyVienMD');
		$this->load->model('ChiTietLopGiaoLyMD');
		
	}
	public function compare()
	{
		foreach ($this->data as $data) {
			$objectTrack=$this->importGiaoHo();
			$this->tracks[]=$objectTrack;

		}
	}
	public function delete($maGiaoXuRieng)
	{
		$rs=$this->GiaoHo->getAllListIDGiaoHo($maGiaoXuRieng);
		if (count($rs)>0) {
			foreach ($rs as $data) {
				$idCSV=$this->findIdObjectCSV($this->tracks,$data->MaGiaoHo);
				if ($idCSV==0) {
					// xoa giao ho
					$this->GiaoHo->delete($data->MaGiaoHo,$data->MaGiaoXuRieng);
					// xoa giao dan  co giao ho
					$listGDGH=$this->GiaoDanMD->getByMaGiaoHo($data->MaGiaoHo,$maGiaoXuRieng);
					if (count($listGDGH)>0) {
						foreach ($listGDGH as $data2) {
							// xoa giao dan
							$this->GiaoDanMD->delete($data2->MaGiaoDan,$maGiaoXuRieng);
							// xoa thanh vien gia dinh co ma giao dan bang
							$this->ThanhVienGiaDinhMD->deleteThanhVien($data2->MaGiaoDan,$maGiaoXuRieng);
							//Xoa bi tich chi tiet
							$this->BiTichChiTietMD->delete($data2->MaGiaoDan,$maGiaoXuRieng);
							//Xoa Giao Dan HonPhoi
							$this->GiaoDanHonPhoiMD->delete($data2->MaGiaoDan,$maGiaoXuRieng);
							//Xoa Chuyen xu
							$this->ChuyenXuMD->delete($data2->MaGiaoDan,$maGiaoXuRieng);
							//Xoa Tan Hien
							$this->TanHienMD->delete($data2->MaGiaoDan,$maGiaoXuRieng);
							//Xoa Rao Hon Phoi
							$this->RaoHonPhoiMD->delete($data2->MaGiaoDan,$maGiaoXuRieng);
							//Xoa chi tiet lop giao ly
							$this->ChiTietLopGiaoLyMD->delete($data2->MaGiaoDan,$maGiaoXuRieng);
							//Xoa giao ly vien
							$this->GiaoLyVienMD->delete($data2->MaGiaoDan,$maGiaoXuRieng);
						}
					}
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
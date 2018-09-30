<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class GiaoHoCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		$this->load->model('GiaoHoMD');
		$this->load->model('GiaoDanMD');
		$this->load->model('GiaDinhMD');
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
			$giaoHoServer=$this->findGiaoHo($data['MaNhanDang'],$data['MaGiaoXuRieng']);
			$objectTrack=$this->importObjectMaster($data,'MaGiaoHo',$giaoHoServer,$this->GiaoHoMD);
			$this->tracks[]=$objectTrack;
		}
	}
	public function delete($maGiaoXuRieng)
	{
		$rs=$this->GiaoHoMD->getAllListIDGiaoHo($maGiaoXuRieng);
		if (count($rs)>0) {
			foreach ($rs as $data) {
				$idCSV=$this->findIdObjectCSV($this->tracks,$data->MaGiaoHo);
				if ($idCSV==0) {
					// xoa giao ho
					$this->GiaoHoMD->delete($data->MaGiaoHo,$maGiaoXuRieng);
					//Xoa gia dinh
					$listGiaDinhGiaoHo=$this->GiaDinhMD->getByMaGiaoHo($data->MaGiaoHo,$maGiaoXuRieng);
					if (count($listGiaDinhGiaoHo)>0) {
						foreach ($listGiaDinhGiaoHo as $data3) {
							//xoa gia dinh
							$this->GiaDinhMD->deleteMaGiaDinh($data3->MaGiaDinh,$maGiaoXuRieng);
							//Xoa thanh vien gia dinh
							$this->ThanhVienGiaDinhMD->deleteMaGiaDinh($data3->MaGiaDinh,$maGiaoXuRieng);
						}
					}
					// xoa giao dan  co giao ho
					$listGDGH=$this->GiaoDanMD->getByMaGiaoHo($data->MaGiaoHo,$maGiaoXuRieng);
					if (count($listGDGH)>0) {
						foreach ($listGDGH as $data2) {
							// xoa giao dan
							$this->GiaoDanMD->deleteMaGiaoDan($data2->MaGiaoDan,$maGiaoXuRieng);
							// xoa thanh vien gia dinh co ma giao dan bang
							$this->ThanhVienGiaDinhMD->deleteMaGiaoDan($data2->MaGiaoDan,$maGiaoXuRieng);
							//Xoa bi tich chi tiet
							$this->BiTichChiTietMD->deleteMaGiaoDan($data2->MaGiaoDan,$maGiaoXuRieng);
							//Xoa Giao Dan HonPhoi
							$this->GiaoDanHonPhoiMD->deleteMaGiaoDan($data2->MaGiaoDan,$maGiaoXuRieng);
							//Xoa Chuyen xu
							$this->ChuyenXuMD->deldeleteMaGiaoDan($data2->MaGiaoDan,$maGiaoXuRieng);
							//Xoa Tan Hien
							$this->TanHienMD->deleteMaGiaoDan($data2->MaGiaoDan,$maGiaoXuRieng);
							//Xoa Rao Hon Phoi
							$this->RaoHonPhoiMD->deleteMaGiaoDan($data2->MaGiaoDan,$maGiaoXuRieng);
							//Xoa chi tiet lop giao ly
							$this->ChiTietLopGiaoLyMD->deleteMaGiaoDan($data2->MaGiaoDan,$maGiaoXuRieng);
							//Xoa giao ly vien
							$this->GiaoLyVienMD->deleteMaGiaoDan($data2->MaGiaoDan,$maGiaoXuRieng);
						}
					}
				}
			}
		}
	}
	// public function importGiaoHo($data)
	// {
	// 	$giaoHoServer=$this->checkExist($data['MaNhanDang'],$data['MaGiaoXuRieng']);
	// 	$objectTrack=new stdClass();
	// 	$objectTrack->updated=false;
	// 	$objectTrack->oldIdIsCsv=true;
	// 	if ($giaoHoServer==null) {
	// 				//Insert
	// 		$objectTrack->oldId=$data["MaGiaoHo"];
	// 		$objectTrack->newId=$this->GiaoHoMD->insert($data);
	// 		$objectTrack->nowId=$objectTrack->newId;
	// 	}
	// 	else {
	// 		$objectTrack->updated=true;
	// 		if ($data['UpdateDate']>$giaoHoServer->UpdateDate) {
	// 			$objectTrack->newId=$data["MaGiaoHo"];
	// 			$objectTrack->oldId=$giaoHoServer->MaGiaoHo;
	// 			$objectTrack->nowId=$giaoHoServer->MaGiaoHo;
	// 			$objectTrack->oldIdIsCsv=false;
	// 			$this->GiaoHoMD->update($data,$giaoHoServer->MaGiaoHo);
	// 		}
	// 		else {
	// 			$objectTrack->oldIdIsCsv=true;
	// 			$objectTrack->newId=$giaoHoServer->MaGiaoHo;
	// 			$objectTrack->oldId=$data["MaGiaoHo"];
	// 			$objectTrack->nowId=$giaoHoServer->MaGiaoHo;
	// 		}
	// 	}
	// 	return $objectTrack;
	// }
	public function findGiaoHo($maNhanDang,$maGiaoXuRieng)
	{
		$rs=$this->GiaoHoMD->getByMaNhanDang($maNhanDang,$maGiaoXuRieng);
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
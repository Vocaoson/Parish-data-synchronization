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
			$giaoHoServer=$this->findGiaoHo($data,$data['MaGiaoXuRieng']);
			if ($this->deleteObjectMaster($data,$giaoHoServer,$this,$this->GiaoHoMD)) {
				continue;
			}
			$objectTrack=$this->importObjectMaster($data,'MaGiaoHo',$giaoHoServer,$this->GiaoHoMD);
			$this->tracks[]=$objectTrack;
		}
		$this->processMaGiaoHoCha();

	}

	public function processMaGiaoHoCha()
	{
		foreach ($this->data as $data) {
			if (!empty($data["MaGiaoHoCha"])) {
				$idMaGiaoHoCha=$this->findIdObjectSV($this->tracks,$data["MaGiaoHoCha"]);
				if ($idMaGiaoHoCha!=0) {
					$data["MaGiaoHo"]=$this->findIdObjectSV($this->tracks,$data["MaGiaoHo"]);
					$this->GiaoHoMD->updateMaGiaoHoCha($data,$idMaGiaoHoCha);
				}
			}
		}
	}
	public function delete($data)
	{
		$this->GiaoHoMD->delete($data->MaGiaoHo,$data->MaGiaoXuRieng);
		//Xoa gia dinh
		$listGiaDinhGiaoHo=$this->GiaDinhMD->getByMaGiaoHo($data->MaGiaoHo,$data->MaGiaoXuRieng);
		if (count($listGiaDinhGiaoHo)>0) {
			foreach ($listGiaDinhGiaoHo as $data3) {
							//xoa gia dinh
				$this->GiaDinhMD->deleteMaGiaDinh($data3->MaGiaDinh,$data->MaGiaoXuRieng);
							//Xoa thanh vien gia dinh
				$this->ThanhVienGiaDinhMD->deleteMaGiaDinh($data3->MaGiaDinh,$data->MaGiaoXuRieng);
			}
		}
					// xoa giao dan  co giao ho
		$listGDGH=$this->GiaoDanMD->getByMaGiaoHo($data->MaGiaoHo,$data->MaGiaoXuRieng);
		if (count($listGDGH)>0) {
			foreach ($listGDGH as $data2) {
							// xoa giao dan
				$this->GiaoDanMD->deleteMaGiaoDan($data2->MaGiaoDan,$data->MaGiaoXuRieng);
							// xoa thanh vien gia dinh co ma giao dan bang
				$this->ThanhVienGiaDinhMD->deleteMaGiaoDan($data2->MaGiaoDan,$data->MaGiaoXuRieng);
							//Xoa bi tich chi tiet
				$this->BiTichChiTietMD->deleteMaGiaoDan($data2->MaGiaoDan,$data->MaGiaoXuRieng);
							//Xoa Giao Dan HonPhoi
				$this->GiaoDanHonPhoiMD->deleteMaGiaoDan($data2->MaGiaoDan,$data->MaGiaoXuRieng);
							//Xoa Chuyen xu
				$this->ChuyenXuMD->deldeleteMaGiaoDan($data2->MaGiaoDan,$data->MaGiaoXuRieng);
							//Xoa Tan Hien
				$this->TanHienMD->deleteMaGiaoDan($data2->MaGiaoDan,$data->MaGiaoXuRieng);
							//Xoa Rao Hon Phoi
				$this->RaoHonPhoiMD->deleteMaGiaoDan($data2->MaGiaoDan,$data->MaGiaoXuRieng);
							//Xoa chi tiet lop giao ly
				$this->ChiTietLopGiaoLyMD->deleteMaGiaoDan($data2->MaGiaoDan,$data->MaGiaoXuRieng);
							//Xoa giao ly vien
				$this->GiaoLyVienMD->deleteMaGiaoDan($data2->MaGiaoDan,$data->MaGiaoXuRieng);
			}
		}
	}
	public function findGiaoHo($data,$maGiaoXuRieng)
	{
		if (!empty($data["MaNhanDang"])) {
			$rs=$this->GiaoHoMD->getByMaNhanDang($data["MaNhanDang"],$maGiaoXuRieng);
		}
		if ($rs) {
			return $rs;
		}
		//find name
		$rs=$this->GiaoHoMD->getByNameGiaoHo($data["TenGiaoHo"],$maGiaoXuRieng);
		if ($rs) {
			return $rs;
		}
		return null;
	}

}

/* End of file GiaoHoCompareCL.php */
/* Location: ./application/controllers/GiaoHoCompareCL.php */
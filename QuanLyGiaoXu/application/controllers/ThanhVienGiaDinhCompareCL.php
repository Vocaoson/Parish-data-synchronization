<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class ThanhVienGiaDinhCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		require_once(APPPATH.'models/ThanhVienGiaDinhMD.php');
        $this->ThanhVienGiaDinhMD=new ThanhVienGiaDinhMD();
	}
	private $ThanhVienGiaDinhMD;
	public function compare()
	{

	}
	public function delete($listTVGDThayDoi)
	{
		$listTVGD=$this->ThanhVienGiaDinhMD->getAll();
		if (isset($listTVGD)&&count($listTVGD)>0) {
			foreach ($listTVGD as $data) {
				$rs=$this->findTVGD($data,$listTVGDThayDoi);
				if ($rs==0) {
					//delete
					$this->ThanhVienGiaDinhMD->delete($data->MaGiaoDan,$data->MaGiaDinh,$data->MaGiaoXuRieng);
				}
			}
		}
	}
	public function findTVGD($data,$listTVGDThayDoi)
	{
		foreach ($listTVGDThayDoi as $value) {
			if ($value->MaGiaoDan=$data->MaGiaoDan&&$value->MaGiaDinh==$data->MaGiaDinh) {
				return 1;
			}
		}
		return 0;
	}
}

/* End of file ThanhVienGiaDinhCompare.php */
/* Location: ./application/controllers/ThanhVienGiaDinhCompare.php */
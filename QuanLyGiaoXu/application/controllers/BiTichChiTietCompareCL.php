<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class BiTichChiTietCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		$this->load->model("BiTichChiTietMD");
	}
	public function compare()
	{
		if($this->data!=null)
		{
			foreach ($this->data as $data) {
				if($data["MaDotBiTich"]!=null)
				{
					//xử lý khóa chính
					if(!empty($data["KhoaChinh"]))
					{
						$data=$this->changeID($data);
					}
					if($data !== null)
					{
						$biTichChiTietServer=$this->findBiTichChiTiet($data);
						if($biTichChiTietServer!=null)
						{
							$compareDate=$this->CompareTwoDateTime($data['UpdateDate'],$biTichChiTietServer->UpdateDate);
							if($compareDate>=0 )
							{
								$this->updateObject($data,$biTichChiTietServer,$this->BiTichChiTietMD);
							}
							continue;
						}
						if($data["DeleteClient"]==0)
						{
							$this->BiTichChiTietMD->insert($data);
						}
					}
				}
			}
		}
	}
	public function findBiTichChiTiet($data)
	{
		if(empty($data["KhoaChinh"]))
		{
			$rs=$this->BiTichChiTietMD->getByMaDotBiTichMaGiaoDan($data["MaDotBiTich"],$data["MaGiaoDan"]);
			if ($rs) {
				return $rs;
			}
		}
		return null;
	}
}

/* End of file BiTichChiTietCompareCL.php */
/* Location: ./application/controllers/BiTichChiTietCompareCL.php */
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class GiaoDanHonPhoiCompareCL extends CompareCL {

	public function __construct($file,$syn) {
		parent::__construct($file,$syn);
		$this->load->model("GiaoDanHonPhoiMD");
	}
	public function compare()
	{
		$temp=null;
		if($this->data!=null)
		{
			foreach ($this->data as $data) 
			{
				$temp=$data;
				if($data["MaHonPhoi"]!=null)
				{
					//xử lý khóa chính
					if(!empty($data["KhoaChinh"]))
					{
						$data=$this->changeID($data);
					}
					if($data !== null)
					{
						$giaoDanHonPhoiServer=$this->findGiaoDanHonPhoi($data);
						if($giaoDanHonPhoiServer!=null)
						{
							$compareDate=$this->CompareTwoDateTime($data['UpdateDate'],$giaoDanHonPhoiServer->UpdateDate);
							if($compareDate>=0 )
							{
								$this->updateObject($data,$giaoDanHonPhoiServer,$this->GiaoDanHonPhoiMD);
							}
							continue;
						}
						if($data["DeleteClient"]==0)
						{
						$this->GiaoDanHonPhoiMD->insert($data);
						}
					}
				}
			}
		}
	}
	public function findGiaoDanHonPhoi($data)
	{
		if(empty($data["KhoaChinh"]))
		{
			$rs=$this->GiaoDanHonPhoiMD->getByMaHonPhoiMaGiaoDan($data["MaHonPhoi"],$data["MaGiaoDan"]);
			if ($rs) {
				return $rs;
			}
		}
		return null;
	}
}

/* End of file GiaoDanHonPhoiCompareCL.php */
/* Location: ./application/controllers/GiaoDanHonPhoiCompareCL.php */
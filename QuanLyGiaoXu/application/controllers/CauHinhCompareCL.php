<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class CauHinhCompareCL extends CompareCL {
    public function __construct($file,$syn) 
    {
        parent::__construct($file,$syn);
        $this->load->model("CauHinhMD");
    }
    public function compare()
    {
		if($this->data!=null)
		{
			foreach($this->data as $data){
				if($data["MaCauHinh"]!=null)
				{
					$cauHinhServer=$this->findCauHinh($data);
					if($cauHinhServer!=null)
					{
						$compareDate=$this->CompareTwoDateTime($data['UpdateDate'],$cauHinhServer->UpdateDate);
						if($compareDate>=0 )
						{
							$this->updateObject($data,$cauHinhServer,$this->CauHinhMD);
						}
						continue;
					}
					else 
					{
						if($data["DeleteClient"]==0)
						{
							$this->CauHinhMD->insert($data);
						}
					}
				}
			}
		}
    }
    public function findCauHinh($data)
    {
		$rs=$this->CauHinhMD->getByMaCauHinhMaGiaoXuRieng($data["MaCauHinh"],$data["MaGiaoXuRieng"]);
		if ($rs) {
			return $rs;
		}
		return null;
    }
}
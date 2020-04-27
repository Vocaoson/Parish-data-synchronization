<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class LinhMucCompareCL extends CompareCL {
    public function __construct($file,$syn) 
    {
        parent::__construct($file,$syn);
        $this->load->model("LinhMucMD");
    }
    public function compare()
    {
		if($this->data!=null)
		{
			foreach ($this->data as $data) {
				if($data["MaLinhMuc"]!=null)
				{
					$linhMucServer=$this->findLinhMuc($data);
					if($linhMucServer!=null)
					{
						if(!empty($data["KhoaChinh"]))
						{
							$this->csvimport->WriteData("MaLinhMuc",$data["MaLinhMuc"],$linhMucServer->MaLinhMuc,$this->dirData,$this->MaGiaoXuRieng);
							$data["MaLinhMuc"]=$linhMucServer->MaLinhMuc;
						}
						$compareDate=$this->CompareTwoDateTime($data['UpdateDate'],$linhMucServer->UpdateDate);
						if($compareDate>=0 )
						{
							$this->updateObject($data,$linhMucServer,$this->LinhMucMD);
						}
						continue;
					}
					else 
					{if($data["DeleteClient"]==0)
						{
						$idClient=$data["MaLinhMuc"];
						$idServerNew=$this->LinhMucMD->insert($data);
						$this->csvimport->WriteData("MaLinhMuc",$idClient,$idServerNew,$this->dirData,$this->MaGiaoXuRieng);
					}}
				}
			}
		}
    }
    public function findLinhMuc($data)
    {
        if(empty($data["KhoaChinh"]))
		{
			$rs=$this->LinhMucMD->getByMaLinhMuc($data["MaLinhMuc"]);
			if ($rs) {
				return $rs;
			}
        }
        //find TenThanh, HoTen, NgaySinh
		$maGiaoXuRieng=$data['MaGiaoXuRieng'];
		$dieuKien="";
		if(!empty($data['TenThanh']))
		{
			$dieuKien.=" and TenThanh = '".str_replace("'","\'",$data['TenThanh'])."'";
		}
		if(!empty($data['HoTen']))
		{
			$dieuKien.=" and HoTen = '".str_replace("'","\'",$data['HoTen'])."'";
		}
		if(!empty($data['NgaySinh']))
		{
			$dieuKien.=" and NgaySinh = '".str_replace("'","\'",$data['NgaySinh'])."'";
		}
		if(!empty($dieuKien))
		{
			$dieuKien="true ".$dieuKien;
			$rs=$this->LinhMucMD->getByInfo($dieuKien,$maGiaoXuRieng);
			if ($rs) {
				return $rs;
			}
		}
		return null;
    }
}
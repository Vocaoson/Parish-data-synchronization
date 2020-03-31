<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class TanHienCompareCL extends CompareCL {
    public function __construct($file,$syn) 
    {
        parent::__construct($file,$syn);
        $this->load->model("TanHienMD");
    }
    public function Compare() 
    {
        foreach($this->data as $data) {
            if($data["MaTanHien"]!=null)
            {
                if(!empty($data["KhoaNgoai"]))
                {
                    $ListDataKhoa = $this->csvimport->getListID("MaGiaoDan",$data[$data["KhoaNgoai"]]);
                    if($ListDataKhoa!=null)
                    $data[$data["KhoaNgoai"]]=$ListDataKhoa["MaIDMayChu"];
                }
                $tanHienServer =$this->findTanHien($data);
                if($tanHienServer!=null)
                {
                    if(!empty($data["KhoaChinh"]))
                    {
                        $this->csvimport->WriteData("MaGiaoDan",$data["MaGiaoDan"],$tanHienServer->MaTanHien,$this->dirData);
                        $data["MaTanHien"]=$tanHienServer->MaTanHien;
                    }
                    $compareDate=$this->CompareTwoDateTime($data['UpdateDate'],$tanHienServer->UpdateDate);
                    if($compareDate>=0 )
                    {
                        $this->updateObject($data,$tanHienServer,$this->TanHienMD);
                    }
                    continue;
                }
                $idClient=$data["MaTanHien"];
                $idServerNew=$this->TanHienMD->insert($data);
                $this->csvimport->WriteData("MaTanHien",$idClient,$idServerNew,$this->dirData);
            }
        }
    }
    public function findTanHien($data)
    {
        if(empty($data["KhoaChinh"]))
		{
			$rs=$this->TanHienMD->getByMaTanHien($data["MaTanHien"]);
			if ($rs) {
				return $rs;
			}
        }
        //find MaGiaoDan, ChucVu, DaHoiTuc
        $dieuKien="";
        if(!empty($data['MaGiaoDan']))
        {
            $dieuKien.=" and MaGiaoDan = '".str_replace("'","\'",$data['MaGiaoDan'])."'";
        }
        if(!empty($data['ChucVu']))
        {
            $dieuKien.=" and ChucVu = '".str_replace("'","\'",$data['ChucVu'])."'";
        }
        if(!empty($data['DaHoiTuc']))
        {
            $dieuKien.=" and DaHoiTuc = '".str_replace("'","\'",$data['DaHoiTuc'])."'";
        }
        if(!empty($dieuKien))
        {
            $dieuKien=="true ".$dieuKien;
            $rs = $this->GiaoDanMD->getByInfo($dieuKien,$this->MaGiaoXuRieng);    
            if ($rs) {
				return $rs;
			} 
        }
        return null;
    }
}
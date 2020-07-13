<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class GiaoDanCompareCL extends CompareCL {
    public function __construct($file,$syn) 
    {
        parent::__construct($file,$syn);
		$this->load->model('GiaoDanMD');
    }

    public function compare()
    {
        if($this->data!=null)
        {
            foreach($this->data as $data) {
                if($data["MaGiaoDan"]!=null)
                {
                    if(!empty($data["KhoaNgoai"]))
                    {
                        $ListDataKhoa = $this->csvimport->getListID("MaGiaoHo",$data[$data["KhoaNgoai"]]);
                        if($ListDataKhoa!=null)
                        $data[$data["KhoaNgoai"]]=$ListDataKhoa["MaIDMayChu"];
                    }
                    $giaoDanServer =$this->findGiaoDan($data);
                    if($giaoDanServer!=null)
                    {
                        //add maGiaoDan file csv
                        if(!empty($data["KhoaChinh"]))
                        {
                            //Kiểm tra xem. Khi tìm thấy 1 người. Nhưng ID server của họ đã có trong list id temp
                            // Chứng tỏ mới thêm. Nên người được tìm sẽ add mới.
                                if($this->csvimport->getListID("MaGiaoDan","server+".$giaoDanServer->MaGiaoDan)!=null)
                            {
                                if($data["DeleteClient"]==0)
                                {
                                    $idClient=$data["MaGiaoDan"];
                                    $idServerNew=$this->GiaoDanMD->insert($data);
                                    $this->csvimport->WriteData("MaGiaoDan",$idClient,$idServerNew,$this->dirData,$this->MaGiaoXuRieng);
                                    continue;
                                }
                            }
                            $this->csvimport->WriteData("MaGiaoDan",$data["MaGiaoDan"],$giaoDanServer->MaGiaoDan,$this->dirData,$this->MaGiaoXuRieng);
                            $data["MaGiaoDan"]=$giaoDanServer->MaGiaoDan;
                        }
                        $compareDate=$this->CompareTwoDateTime($data['UpdateDate'],$giaoDanServer->UpdateDate);
                        if($compareDate>=0 )
                        {
                            $this->updateObject($data,$giaoDanServer,$this->GiaoDanMD);
                        }
                        continue;
                    }
                    if($data["DeleteClient"]==0)
                    {
                        $idClient=$data["MaGiaoDan"];
                        $idServerNew=$this->GiaoDanMD->insert($data);
                        $this->csvimport->WriteData("MaGiaoDan",$idClient,$idServerNew,$this->dirData,$this->MaGiaoXuRieng);
                    }
                    
                }
            }
        }
    }
    public function findGiaoDan($data)
    {
        if(empty($data["KhoaChinh"]))
		{
			$rs=$this->GiaoDanMD->getByMaGiaoDan($data["MaGiaoDan"]);
			if ($rs) {
				return $rs;
			}
		}
		$maGiaoXuRieng=$data['MaGiaoXuRieng'];
		if (!empty($data["MaNhanDang"])) {
			$rs=$this->GiaoDanMD->getByMaNhanDang($data["MaNhanDang"],$maGiaoXuRieng);
			if ($rs) {
				return $rs;
			}
        }
        //find Ten thanh, Ho ten, phai, Ho ten cha, ho ten me, ngay sinh
        $dieuKien="";
        if(!empty($data['TenThanh']))
        {
            $dieuKien.=" and TenThanh = '".str_replace("'","\'",$data['TenThanh'])."'";
        }
        if(!empty($data['MaGiaoHo']))
        {
            $dieuKien.=" and MaGiaoHo = '".str_replace("'","\'",$data['MaGiaoHo'])."'";
        }
        if(!empty($data['HoTen']))
        {
            $dieuKien.=" and HoTen = '".str_replace("'","\'",$data['HoTen'])."'";
        }
        if(!empty($data['Phai']))
        {
            $dieuKien.=" and Phai = '".str_replace("'","\'",$data['Phai'])."'";
        }
        if(!empty($data['HoTenCha']))
        {
            $dieuKien.=" and HoTenCha = '".str_replace("'","\'",$data['HoTenCha'])."'";
        }
        if(!empty($data['HoTenMe']))
        {
            $dieuKien.=" and HoTenMe = '".str_replace("'","\'",$data['HoTenMe'])."'";
        }
        if(!empty($data['NgaySinh']))
        {
            $dieuKien.=" and NgaySinh = '".str_replace("'","\'",$data['NgaySinh'])."'";
        }
        if(!empty($dieuKien))
        {
            $dieuKien="true ".$dieuKien;
            $rs = $this->GiaoDanMD->getByInfo($dieuKien,$this->MaGiaoXuRieng);    
            if ($rs) {
                //Check giáo dân existed in file DongBo
                // if($this->csvimport->getListID("MaGiaoDan","server+".$rs->MaGiaoDan))
                // {
                //     $maGiaoDan=$rs->MaGiaoDan;
                //     $dieukienNew=" and MaGiaoDan <> '"."$maGiaoDan'";
                //     $dieukien.=$dieukienNew;
                //     $rsNew = $this->GiaoDanMD->getByInfo($dieuKien,$this->MaGiaoXuRieng);      
                //     while($rsNew)
                //     {
                //         if(!$this->csvimport->getListID("MaGiaoDan","server+".$rsNew->MaGiaoDan))
                //         {
                //             return $rsNew;
                //         }
                //         $maGiaoDan=$rsNew->MaGiaoDan;
                //         $dieukienNew=" and MaGiaoDan <> '"."$maGiaoDan'";
                //         $dieukien.=$dieukienNew;
                //         $rsNew = $this->GiaoDanMD->getByInfo($dieuKien,$this->MaGiaoXuRieng);      
                //     }
                //     return null;
                // }
				return $rs;
			} 
        }
        return null;
    }
}
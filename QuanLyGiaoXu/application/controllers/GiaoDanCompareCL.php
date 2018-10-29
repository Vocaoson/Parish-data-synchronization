<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class GiaoDanCompareCL extends CompareCL {
    public function __construct($file,$syn) 
    {
        parent::__construct($file,$syn);
        require_once(APPPATH.'models/GiaoDanMD.php');
        $this->GiaoDanMD=new GiaoDanMD();
        require_once(APPPATH.'models/ThanhVienGiaDinhMD.php');
        $this->ThanhVienGiaDinhMD=new ThanhVienGiaDinhMD();
        require_once(APPPATH.'models/BiTichChiTietMD.php');
        $this->BiTichChiTietMD=new BiTichChiTietMD();
        require_once(APPPATH.'models/GiaoDanHonPhoiMD.php');
        $this->GiaoDanHonPhoiMD=new GiaoDanHonPhoiMD();
        require_once(APPPATH.'models/ChuyenXuMD.php');
        $this->ChuyenXuMD=new ChuyenXuMD();
        require_once(APPPATH.'models/TanHienMD.php');
        $this->TanHienMD=new TanHienMD();
        require_once(APPPATH.'models/RaoHonPhoiMD.php');
        $this->RaoHonPhoiMD=new RaoHonPhoiMD();
        require_once(APPPATH.'models/GiaoLyVienMD.php');
        $this->GiaoLyVienMD=new GiaoLyVienMD();
        require_once(APPPATH.'models/ChiTietLopGiaoLyMD.php');
        $this->ChiTietLopGiaoLyMD=new ChiTietLopGiaoLyMD();
    }
    //2018/09/22 Gia add start
    private $GiaoDanMD;
    private $listGHThayDoi;
    public function getListGiaoHoTracks($tracks)
    {
        $this->listGHThayDoi=$tracks;
    }
    //2018/09/22 Gia add end
    public function compare()
    {
        foreach($this->data as $data) {
            $giaoDans = array();
            if(!empty($data['MaNhanDang'])) {
                $giaoDans = $this->GiaoDanMD->getByMaNhanDang($data['MaNhanDang'],$this->MaGiaoXuRieng);
            }
            if(count($giaoDans) <= 0) {
                if(!empty($data['HoTen']) && !empty($data['NgaySinh']) && !empty($data['TenThanh'])) {
                    $giaoDans = $this->GiaoDanMD->getByInfo($data['HoTen'],$data['TenThanh'],$data['NgaySinh'],$this->MaGiaoXuRieng);
                }
            }
            //delete giaodan
            if(count($giaoDans) > 0 && $this->deleteObjectMaster($data,$giaoDans[0],$this,$this->GiaoDanMD)) {
                continue;
            }
            $data['MaGiaoHo']=$this->findIdObjectSV($this->listGHThayDoi,$data['MaGiaoHo']);
            if(count($giaoDans) > 0) {
                $this->tracks[] = $this->importObjectMaster($data,"MaGiaoDan",$giaoDans[0],$this->GiaoDanMD);
            } else {
                $this->tracks[] = $this->importObjectMaster($data,"MaGiaoDan",null,$this->GiaoDanMD);
            }     
        }
    }
    public function delete($data) {
        $this->GiaoDanMD->deleteMaGiaoDan($data->MaGiaoDan,$data->MaGiaoXuRieng);
        $this->ThanhVienGiaDinhMD->deleteMaGiaoDan($data->MaGiaoDan,$data->MaGiaoXuRieng);
        $this->BiTichChiTietMD->deleteMaGiaoDan($data->MaGiaoDan,$data->MaGiaoXuRieng);
        $this->ChuyenXuMD->deleteMaGiaoDan($data->MaGiaoDan,$data->MaGiaoXuRieng);
        $this->TanHienMD->deleteMaGiaoDan($data->MaGiaoDan,$data->MaGiaoXuRieng);
        $this->RaoHonPhoiMD->deleteMaGiaoDan($data->MaGiaoDan,$data->MaGiaoXuRieng);
        $this->ChiTietLopGiaoLyMD->deleteMaGiaoDan($data->MaGiaoDan,$data->MaGiaoXuRieng);
    }
}
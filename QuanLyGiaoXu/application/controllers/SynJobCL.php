<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SynJobCL extends CI_Controller 
{
    private $dirData;
    private $dirDataSyn;
    public function __construct(){
        parent::__construct();
        $this->load->model('SynFileMD');
        $this->load->model('GiaoXuMD');
        $dataImport = array('header'=>true);
        $this->load->library('CsvImport',$dataImport);
        $this->dirData = $this->config->item('data_dir');
        $this->dirDataSyn=$this->config->item('data_dir') . '/syn//';
    }
    public function getFileSyn(){

    }
    public function getJob(){
        $syns = $this->SynFileMD->getAll();
        if(count($syns) > 0){
            $syn = $syns[0];
            // set status = 1
            $this->execute($syn);
            $this->setStatusExe($syn);
        }
    }
    public function setStatusExe($syn) {
        $this->SynFileMD->setExe($syn->ID);
    }
    public function executeByMaGiaoXu($maGiaoXuRieng)
    {
        $begin  = new DateTime();
        $result1 = $begin->format('Y-m-d H:i:s');
        //echo strval($result1."<br>");
        $idFile=$this->SynFileMD->getIDByMaGiaoXu($maGiaoXuRieng)->ID;
        $dir = $this->dirDataSyn . $maGiaoXuRieng . '/' . $idFile;
        //create folder and file dongboID để map ID server và Client 
        $createFile=$this->csvimport->CreateFileAndFolder($this->dirData,$maGiaoXuRieng);
        if(!$createFile)
        {
            echo "Lỗi tạo file DongBoID";
            return;
        }
        require_once('GiaoHoCompareCL.php');
        $giaoHoCompare=new GiaoHoCompareCL('GiaoHo.csv',$dir);
        $giaoHoCompare->compare(); 
        
        require_once('GiaoDanCompareCL.php');
        $giaoDanCompare = new GiaoDanCompareCL("GiaoDan.csv",$dir);
        $giaoDanCompare->compare();
        
        require_once('GiaDinhCompareCL.php');
        $giaDinhCompare=new GiaDinhCompareCL('GiaDinh.csv',$dir);
        $giaDinhCompare->compare();
        
        require_once('ThanhVienGiaDinhCompareCL.php');
        $thanhVienGiaDinhCompare=new ThanhVienGiaDinhCompareCL('ThanhVienGiaDinh.csv',$dir);
        $thanhVienGiaDinhCompare->compare();
        
        require_once('DotBiTichCompareCL.php');
        $dotBiTichCompare=new DotBiTichCompareCL('DotBiTich.csv',$dir);
        $dotBiTichCompare->compare();
        
        require_once('BiTichChiTietCompareCL.php');
        $biTichChiTiet=new BiTichChiTietCompareCL('BiTichChiTiet.csv',$dir);
        $biTichChiTiet->compare();
        
        require_once('HonPhoiCompareCL.php');
        $HonPhoiCompare=new HonPhoiCompareCL('HonPhoi.csv',$dir);
        $HonPhoiCompare->compare();
        
        require_once('GiaoDanHonPhoiCompareCL.php');
        $giaoDanHonPhoiCompare=new GiaoDanHonPhoiCompareCL('GiaoDanHonPhoi.csv',$dir);
        $giaoDanHonPhoiCompare->compare();
        
        require_once('KhoiGiaoLyCompareCL.php');
        $KhoiGiaoLyCompare=new KhoiGiaoLyCompareCL('KhoiGiaoLy.csv',$dir);
        $KhoiGiaoLyCompare->compare();
        
        require_once('LopGiaoLyCompareCL.php');
        $LopGiaoLyCompare=new LopGiaoLyCompareCL('LopGiaoLy.csv',$dir);
        $LopGiaoLyCompare->compare();
        
        require_once('GiaoLyVienCompareCL.php');
        $giaoLyVienCompare=new GiaoLyVienCompareCL('GiaoLyVien.csv',$dir);
        $giaoLyVienCompare->compare();
        
        require_once('ChiTietLopGiaoLyCompareCL.php');
        $chiTietLopGiaoLy=new ChiTietLopGiaoLyCompareCL('ChiTietLopGiaoLy.csv',$dir);
        $chiTietLopGiaoLy->compare();
        
        require_once('HoiDoanCompareCL.php');
        $hoiDoanCompare=new HoiDoanCompareCL('HoiDoan.csv',$dir);
        $hoiDoanCompare->compare();
        
        require_once('ChiTietHoiDoanCompareCL.php');
        $chiTietHoiDoanCompare=new ChiTietHoiDoanCompareCL('ChiTietHoiDoan.csv',$dir);
        $chiTietHoiDoanCompare->compare();
        
        require_once('RaoHonPhoiCompareCL.php');
        $raoHonPhoiCompare=new RaoHonPhoiCompareCL('RaoHonPhoi.csv',$dir);
        $raoHonPhoiCompare->compare();
        
        require_once('ChuyenXuCompareCL.php');
        $chuyenXuCompare=new ChuyenXuCompareCL('ChuyenXu.csv',$dir);
        $chuyenXuCompare->compare();
        
        require_once('TanHienCompareCL.php');
        $tanHienCompare=new TanHienCompareCL('TanHien.csv',$dir);
        $tanHienCompare->compare();
        
        require_once('LinhMucCompareCL.php');
        $linhMucCompareCL = new LinhMucCompareCL("LinhMuc.csv",$dir);
        $linhMucCompareCL->compare();
        
        require_once('CauHinhCompareCL.php');
        $cauHinhCompareCL = new CauHinhCompareCL("CauHinh.csv",$dir);
        $cauHinhCompareCL->compare();
        

        $this->SynFileMD->setExe($idFile);
        $this->GiaoXuMD->setLockSync($maGiaoXuRieng);
        $end = new DateTime();
        $diff = $begin->diff($end);
        //echo("Tổng thời gian: ");
        //echo $diff->format('%H:%I:%S'); 
        $tg=$diff->format('%H:%I:%S'); 
        $result = $end->format('Y-m-d H:i:s');
        //echo strval("<br>".$result);
        echo($tg);
    }
}
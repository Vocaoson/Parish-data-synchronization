<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SynJobCL extends CI_Controller 
{
    private $dirData;
    public function __construct(){
        parent::__construct();
        $this->load->model('SynFileMD');
        $dataImport = array('header'=>true);
        $this->load->library('CsvImport',$dataImport);
        $this->dirData = $this->config->item('data_dir') . '/syn/';
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
    public function execute($syn) {
        // GiaoDan
        $dir = $this->dirData . $syn->MaGiaoXuSyn . '/' . $syn->ID;
        //2018/09/22 Gia add start
        require_once('GiaoHoCompareCL.php');
        $giaoHoCompare=new GiaoHoCompareCL('GiaoHo.csv',$dir);
        $giaoHoCompare->compare();
        $giaoHoCompare->delete($syn->MaGiaoXuSyn);
        //2018/09/22 Gia add end
        require_once('GiaoDanCompareCL.php');
        $giaoDanCompare = new GiaoDanCompareCL("GiaoDan.csv",$dir);
        $giaoDanCompare->getListGiaoHoTracks($giaoHoCompare->tracks);
        $giaoDanCompare->MaGiaoXuRieng = $syn->MaGiaoXuSyn;
        $giaoDanCompare->compare();
        $giaoDanCompare->delete($syn->MaGiaoXuSyn);
        require_once('GiaDinhCompareCL.php');
        $giaDinhCompare=new GiaDinhCompareCL('GiaDinh.csv',$dir);
        $giaDinhCompare->getListGiaoDanTracks($giaoDanCompare->tracks);
        $giaDinhCompare->getListGiaoHoTracks($giaoHoCompare->tracks);
        $giaDinhCompare->compare();
        $giaDinhCompare->delete($syn->MaGiaoXuSyn);
        require_once('DotBiTichCompareCL.php');
        $dotBiTichCompare=new DotBiTichCompareCL('DotBiTich.csv',$dir);
        $dotBiTichCompare->getListGiaoDanTracks($giaoDanCompare->tracks);
        $dotBiTichCompare->compare();
        $dotBiTichCompare->delete($syn->MaGiaoXuSyn);
        require_once('HonPhoiCompareCL.php');
        $HonPhoiCompare=new HonPhoiCompareCL('HonPhoi.csv',$dir);
        $HonPhoiCompare->getListGiaoDanTracks($giaoDanCompare->tracks);
        $HonPhoiCompare->compare();
        $HonPhoiCompare->delete($syn->MaGiaoXuSyn);
        require_once('KhoiGiaoLyCompareCL.php');
        $KhoiGiaoLyCompare=new KhoiGiaoLyCompareCL('HonPhoi.csv',$dir);
        $KhoiGiaoLyCompare->getListGiaoDanTracks($giaoDanCompare->tracks);
        $KhoiGiaoLyCompare->compare();
        $KhoiGiaoLyCompare->delete($syn->MaGiaoXuSyn);
         require_once('LopGiaoLyCompareCL.php');
        $LopGiaoLyCompare=new LopGiaoLyCompareCL('HonPhoi.csv',$dir);
        $LopGiaoLyCompare->getListGiaoDanTracks($giaoDanCompare->tracks);
        $LopGiaoLyCompare->getListKhoiLopTracks($KhoiGiaoLyCompare->tracks);
        $LopGiaoLyCompare->compare();
        $LopGiaoLyCompare->delete($syn->MaGiaoXuSyn);
         // require_once('ChuyenXuCompareCL.php');
        // $chuyenXuCompare=new ChuyenXuCompareCL('ChuyenXu.csv',$dir);
        // $chuyenXuCompare->getListGiaoDanTracks($giaoDanCompare->tracks);
        // $chuyenXuCompare->compare();
        // $chuyenXuCompare->delete($syn->MaGiaoXuSyn);
        // require_once('TanHienCompareCL.php');
        // $tanHienCompare=new TanHienCompareCL('TanHien.csv',$dir);
        // $tanHienCompare->getListGiaoDanTracks($giaoDanCompare->tracks);
        // $tanHienCompare->compare();
        // $tanHienCompare->delete($syn->MaGiaoXuSyn);

        // require_once('LinhMucCompareCL.php');
        // $linhMucCompareCL = new LinhMucCompareCL("LinhMuc.csv",$dir);
        // $linhMucCompareCL->MaGiaoXuRieng = $syn->MaGiaoXuSyn;
        // $linhMucCompareCL->compare();
        // $linhMucCompareCL->delete($syn->MaGiaoXuSyn);
    
        // require_once('CauHinhCompareCL.php');
        // $cauHinhCompareCL = new CauHinhCompareCL("CauHinh.csv",$dir);
        // $cauHinhCompareCL->MaGiaoXuRieng = $syn->MaGiaoXuSyn;
        // $cauHinhCompareCL->compare();
        // $cauHinhCompareCL->delete($syn->MaGiaoXuSyn);

        require_once('RaoHonPhoiCompareCL.php');
        $raoHonPhoiCompare=new RaoHonPhoiCompareCL('RaoHonPhoi.csv',$dir);
        $raoHonPhoiCompare->getListGiaoDanTracks($giaoDanCompare->tracks);
        $raoHonPhoiCompare->compare();
        $raoHonPhoiCompare->delete($syn->MaGiaoXuSyn);
    }
    public function excuteByMaGiaoXu($maGiaoXu)
    {
        $idFile=$this->SynFileMD->getIDByMaGiaoXu($maGiaoXu)->ID;
        $dir = $this->dirData . $maGiaoXu . '/' . $idFile;
        require_once('GiaoHoCompareCL.php');
        $giaoHoCompare=new GiaoHoCompareCL('GiaoHo.csv',$dir);
        $giaoHoCompare->compare();
        $giaoHoCompare->delete($maGiaoXu);
        require_once('GiaoDanCompareCL.php');
        $giaoDanCompare = new GiaoDanCompareCL("GiaoDan.csv",$dir);
        $giaoDanCompare->getListGiaoHoTracks($giaoHoCompare->tracks);
        $giaoDanCompare->compare();
        require_once('GiaDinhCompareCL.php');
        $giaDinhCompare=new GiaDinhCompareCL('GiaDinh.csv',$dir);
        $giaDinhCompare->getListGiaoDanTracks($giaoDanCompare->tracks);
        $giaDinhCompare->getListGiaoHoTracks($giaoHoCompare->tracks);
        $giaDinhCompare->compare();
        $giaDinhCompare->delete($syn->MaGiaoXuSyn);
        $this->SynFileMD->setExe($idFile);
    }

}
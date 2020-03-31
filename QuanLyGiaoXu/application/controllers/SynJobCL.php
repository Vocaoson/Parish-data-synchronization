<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SynJobCL extends CI_Controller 
{
    private $dirData;
    private $dirDataSyn;
    public function __construct(){
        parent::__construct();
        $this->load->model('SynFileMD');
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
    public function executeByMaGiaoXu($maGiaoXu)
    {
        $begin  = new DateTime();
        $result1 = $begin->format('Y-m-d H:i:s');
        echo strval($result1."<br>");
        $idFile=$this->SynFileMD->getIDByMaGiaoXu($maGiaoXu)->ID;
        $dir = $this->dirDataSyn . $maGiaoXu . '/' . $idFile;
        //create folder and file dongboID để map ID server và Client 
        $createFile=$this->csvimport->CreateFileAndFolder($this->dirData);
        if(!$createFile)
        {
            echo -1;
            return;
        }
        //
        $beginGiaoHo  = new DateTime();
        require_once('GiaoHoCompareCL.php');
        $giaoHoCompare=new GiaoHoCompareCL('GiaoHo.csv',$dir);
        $giaoHoCompare->compare(); 
        $endGiaoHo = new DateTime();
        $diffGiaoHo = $beginGiaoHo->diff($endGiaoHo);
        echo("Tổng thời gian Giáo Họ: ");
        echo $diffGiaoHo->format('%H:%I:%S'); 
        echo("<br>");
        //
        $beginGiaoDan  = new DateTime();
        require_once('GiaoDanCompareCL.php');
        $giaoDanCompare = new GiaoDanCompareCL("GiaoDan.csv",$dir);
        $giaoDanCompare->compare();
        $endGiaoDan = new DateTime();
        $diffGiaoDan = $beginGiaoDan->diff($endGiaoDan);
        echo("Tổng thời gian Giáo Dân: ");
        echo $diffGiaoDan->format('%H:%I:%S'); 
        echo("<br>");
        
        //
        $beginGiaDinh  = new DateTime();
        require_once('GiaDinhCompareCL.php');
        $giaDinhCompare=new GiaDinhCompareCL('GiaDinh.csv',$dir);
        $giaDinhCompare->compare();
        $endGiaDinh = new DateTime();
        $diffGiaDinh = $beginGiaDinh->diff($endGiaDinh);
        echo("Tổng thời gian Gia Đình: ");
        echo $diffGiaDinh->format('%H:%I:%S');
        echo("<br>"); 

        //
        $beginTVGD  = new DateTime();
        require_once('ThanhVienGiaDinhCompareCL.php');
        $thanhVienGiaDinhCompare=new ThanhVienGiaDinhCompareCL('ThanhVienGiaDinh.csv',$dir);
        $thanhVienGiaDinhCompare->compare();
        $endTVGD = new DateTime();
        $diffTVGD = $beginTVGD->diff($endTVGD);
        echo("Tổng thời gian TVGD: ");
        echo $diffTVGD->format('%H:%I:%S'); 
        echo("<br>");

        $beginDBT  = new DateTime();
        require_once('DotBiTichCompareCL.php');
        $dotBiTichCompare=new DotBiTichCompareCL('DotBiTich.csv',$dir);
        $dotBiTichCompare->compare();
        $endDBT = new DateTime();
        $diffDBT = $beginDBT->diff($endDBT);
        echo("Tổng thời gian Đợt bí tích: ");
        echo $diffDBT->format('%H:%I:%S'); 
        echo("<br>");

        $beginBTCT  = new DateTime();
        require_once('BiTichChiTietCompareCL.php');
        $biTichChiTiet=new BiTichChiTietCompareCL('BiTichChiTiet.csv',$dir);
        $biTichChiTiet->compare();
        $endBTCT = new DateTime();
        $diffBTCT = $beginBTCT->diff($endBTCT);
        echo("Tổng thời gian Bí tích chi tiết: ");
        echo $diffBTCT->format('%H:%I:%S'); 
        echo("<br>");
        
        $beginHP  = new DateTime();
        require_once('HonPhoiCompareCL.php');
        $HonPhoiCompare=new HonPhoiCompareCL('HonPhoi.csv',$dir);
        $HonPhoiCompare->compare();
        $endHP = new DateTime();
        $diffHP = $beginHP->diff($endHP);
        echo("Tổng thời gian Hôn phối: ");
        echo $diffHP->format('%H:%I:%S'); 
        echo("<br>");


        $beginGDHP  = new DateTime();
        require_once('GiaoDanHonPhoiCompareCL.php');
        $giaoDanHonPhoiCompare=new GiaoDanHonPhoiCompareCL('GiaoDanHonPhoi.csv',$dir);
        $giaoDanHonPhoiCompare->compare();
        $endGDHP = new DateTime();
        $diffGDHP = $beginGDHP->diff($endGDHP);
        echo("Tổng thời gian Giáo dân hôn phối: ");
        echo $diffGDHP->format('%H:%I:%S'); 
        echo("<br>");


        $beginKGL  = new DateTime();
        require_once('KhoiGiaoLyCompareCL.php');
        $KhoiGiaoLyCompare=new KhoiGiaoLyCompareCL('KhoiGiaoLy.csv',$dir);
        $KhoiGiaoLyCompare->compare();
        $endKGL = new DateTime();
        $diffKGL = $beginKGL->diff($endKGL);
        echo("Tổng thời gian Khối giáo lý: ");
        echo $diffKGL->format('%H:%I:%S'); 
        echo("<br>");

        $beginLGL  = new DateTime();
        require_once('LopGiaoLyCompareCL.php');
        $LopGiaoLyCompare=new LopGiaoLyCompareCL('LopGiaoLy.csv',$dir);
        $LopGiaoLyCompare->compare();
        $endLGL = new DateTime();
        $diffLGL = $beginLGL->diff($endLGL);
        echo("Tổng thời gian lớp giáo lý: ");
        echo $diffLGL->format('%H:%I:%S'); 
        echo("<br>");

        $beginGLV  = new DateTime();
        require_once('GiaoLyVienCompareCL.php');
        $giaoLyVienCompare=new GiaoLyVienCompareCL('GiaoLyVien.csv',$dir);
        $giaoLyVienCompare->compare();
        $endGLV = new DateTime();
        $diffGLV = $beginGLV->diff($endGLV);
        echo("Tổng thời gian Giáo lý viên: ");
        echo $diffGLV->format('%H:%I:%S'); 
        echo("<br>");

        $beginCTLGL  = new DateTime();
        require_once('ChiTietLopGiaoLyCompareCL.php');
        $chiTietLopGiaoLy=new ChiTietLopGiaoLyCompareCL('ChiTietLopGiaoLy.csv',$dir);
        $chiTietLopGiaoLy->compare();
        $endCTLGL = new DateTime();
        $diffCTLGL = $beginCTLGL->diff($endCTLGL);
        echo("Tổng thời gian Chi tiết lớp giáo ly: ");
        echo $diffCTLGL->format('%H:%I:%S'); 
        echo("<br>");

        $beginHD  = new DateTime();
        require_once('HoiDoanCompareCL.php');
        $hoiDoanCompare=new HoiDoanCompareCL('HoiDoan.csv',$dir);
        $hoiDoanCompare->compare();
        $endHD = new DateTime();
        $diffHD = $beginHD->diff($endHD);
        echo("Tổng thời gian Hội đoàn: ");
        echo $diffHD->format('%H:%I:%S'); 
        echo("<br>");

        $beginCTHD  = new DateTime();
        require_once('ChiTietHoiDoanCompareCL.php');
        $chiTietHoiDoanCompare=new ChiTietHoiDoanCompareCL('ChiTietHoiDoan.csv',$dir);
        $chiTietHoiDoanCompare->compare();
        $endCTHD  = new DateTime();
        $diffCTHD  = $beginCTHD ->diff($endCTHD );
        echo("Tổng thời gian Chi tiết hội đoàn: ");
        echo $diffCTHD ->format('%H:%I:%S'); 
        echo("<br>");

        $beginRHP  = new DateTime();
        require_once('RaoHonPhoiCompareCL.php');
        $raoHonPhoiCompare=new RaoHonPhoiCompareCL('RaoHonPhoi.csv',$dir);
        $raoHonPhoiCompare->compare();
        $endRHP  = new DateTime();
        $diffRHP  = $beginRHP ->diff($endRHP);
        echo("Tổng thời gian Rao hôn phối: ");
        echo $diffRHP ->format('%H:%I:%S'); 
        echo("<br>");
        
        $beginCX  = new DateTime();
        require_once('ChuyenXuCompareCL.php');
        $chuyenXuCompare=new ChuyenXuCompareCL('ChuyenXu.csv',$dir);
        $chuyenXuCompare->compare();
        $endCX = new DateTime();
        $diffCX = $beginCX->diff($endCX);
        echo("Tổng thời gian Chuyển xứ: ");
        echo $diffCX->format('%H:%I:%S'); 
        echo("<br>");


        $beginTH  = new DateTime();
        require_once('TanHienCompareCL.php');
        $tanHienCompare=new TanHienCompareCL('TanHien.csv',$dir);
        $tanHienCompare->compare();
        $endTH = new DateTime();
        $diffTH = $beginTH->diff($endTH);
        echo("Tổng thời gian Tận hiến: ");
        echo $diffTH->format('%H:%I:%S'); 
        echo("<br>");

        $beginLM  = new DateTime();
        require_once('LinhMucCompareCL.php');
        $linhMucCompareCL = new LinhMucCompareCL("LinhMuc.csv",$dir);
        $linhMucCompareCL->compare();
        $endLM = new DateTime();
        $diffLM = $beginLM->diff($endLM);
        echo("Tổng thời gian Linh Mục: ");
        echo $diffLM->format('%H:%I:%S'); 
        echo("<br>");
        
        // require_once('CauHinhCompareCL.php');
        // $cauHinhCompareCL = new CauHinhCompareCL("CauHinh.csv",$dir);
        // $cauHinhCompareCL->compare();

        // $this->SynFileMD->setExe($idFile);

        $end = new DateTime();
        $diff = $begin->diff($end);
        echo("Tổng thời gian: ");
        echo $diff->format('%H:%I:%S'); 
        $result = $end->format('Y-m-d H:i:s');
        echo strval("<br>".$result);
    }
    public function execute($syn) {
        // GiaoDan
        $dir = $this->dirDataSyn . $syn->MaGiaoXuSyn . '/' . $syn->ID;
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

        require_once('ThanhVienGiaDinhCompareCL.php');
        $thanhVienGiaDinhCompare=new ThanhVienGiaDinhCompareCL('ThanhVienGiaDinh.csv',$dir);
        $thanhVienGiaDinhCompare->getListGiaoDanTracks($giaoDanCompare->tracks);
        $thanhVienGiaDinhCompare->getListGiaDinhTracks($giaDinhCompare->tracks);
        $thanhVienGiaDinhCompare->compare();
        $thanhVienGiaDinhCompare->delete($syn->MaGiaoXuSyn);

        require_once('DotBiTichCompareCL.php');
        $dotBiTichCompare=new DotBiTichCompareCL('DotBiTich.csv',$dir);
        $dotBiTichCompare->getListGiaoDanTracks($giaoDanCompare->tracks);
        $dotBiTichCompare->compare();
        $dotBiTichCompare->delete($syn->MaGiaoXuSyn);

        require_once('BiTichChiTietCompareCL.php');
        $biTichChiTiet=new BiTichChiTietCompareCL('BiTichChiTiet.csv',$dir);
        $biTichChiTiet->getListGiaoDanTracks($giaoDanCompare->tracks);
        $biTichChiTiet->getListDotBiTichTracks($dotBiTichCompare->tracks);
        $biTichChiTiet->compare();
        $biTichChiTiet->delete($syn->MaGiaoXuSyn);

        require_once('HonPhoiCompareCL.php');
        $HonPhoiCompare=new HonPhoiCompareCL('HonPhoi.csv',$dir);
        $HonPhoiCompare->getListGiaoDanTracks($giaoDanCompare->tracks);
        $HonPhoiCompare->compare();
        $HonPhoiCompare->delete($syn->MaGiaoXuSyn);

        require_once('GiaoDanHonPhoiCompareCL.php');
        $giaoDanHonPhoiCompare=new GiaoDanHonPhoiCompareCL('GiaoDanHonPhoi.csv',$dir);
        $giaoDanHonPhoiCompare->getListGiaoDanTracks($giaoDanCompare->tracks);
        $giaoDanHonPhoiCompare->getlistHonPhoiTracks($HonPhoiCompare->tracks);
        $giaoDanHonPhoiCompare->compare();
        $giaoDanHonPhoiCompare->delete($syn->MaGiaoXuSyn);


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

        require_once('GiaoLyVienCompareCL.php');
        $giaoLyVienCompare=new GiaoLyVienCompareCL('GiaoDanHonPhoi.csv',$dir);
        $giaoLyVienCompare->getListGiaoDanTracks($giaoDanCompare->tracks);
        $giaoLyVienCompare->getlistLopGiaoLyTracks($LopGiaoLyCompare->tracks);
        $giaoLyVienCompare->compare();
        $giaoLyVienCompare->delete($syn->MaGiaoXuSyn);

        require_once('ChiTietLopGiaoLyCompareCL.php');
        $chiTietLopGiaoLy=new ChiTietLopGiaoLyCompareCL('ChiTietLopGiaoLy.csv',$dir);
        $chiTietLopGiaoLy->getListGiaoDanTracks($giaoDanCompare->tracks);
        $chiTietLopGiaoLy->getlistLopGiaoLyTracks($LopGiaoLyCompare->tracks);
        $chiTietLopGiaoLy->compare();
        $chiTietLopGiaoLy->delete($syn->MaGiaoXuSyn);

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

        // require_once('RaoHonPhoiCompareCL.php');
        // $raoHonPhoiCompare=new RaoHonPhoiCompareCL('RaoHonPhoi.csv',$dir);
        // $raoHonPhoiCompare->getListGiaoDanTracks($giaoDanCompare->tracks);
        // $raoHonPhoiCompare->compare();
        // $raoHonPhoiCompare->delete($syn->MaGiaoXuSyn);
    }
    
    

}
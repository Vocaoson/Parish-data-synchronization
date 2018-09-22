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
        //2018/09/22 Gia add end
        require_once('GiaoDanCompareCL.php');
        $giaoDanCompare = new GiaoDanCompareCL("GiaoDan.csv",$dir);
        $giaoDanCompare->getListGiaoHoTracks($giaoHoCompare->tracks);
        $giaoDanCompare->compare();
        require_once('GiaDinhCompareCL.php');
        $giaDinhComapre=new GiaDinhCompareCL('GiaDinh.csv',$dir);
        $giaDinhComapre->getListGiaoDanTracks($giaoDanCompare->tracks);
        $giaDinhComapre->getListGiaoHoTracks($giaoHoCompare->tracks);
        $giaDinhComapre->compare();
        $giaDinhComapre->deleteGiaDinh($syn->MaGiaoXuSyn);
        //$giaoDanCompare->compare();
        // GiaDinh -> ThanhVienGiaDinh
        
        // $files = array_diff(scandir($dir), array('.', '..'));
        // foreach($files as $file){
        //     $this->csvimport->setFileName($dir . '/' . $file);
        //     $data = $this->csvimport->get();
        //     if(!empty($data) && $file == 'GiaoDan.csv'){
        //         $className = explode('.',$file)[0] . "CompareCL";
        //         include_once($className . '.php');
        //         $compare = new $className();
        //         $compare->compare($data);
        //     }
        // }
    }

}
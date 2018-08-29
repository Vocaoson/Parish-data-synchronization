<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SynJobCL extends CI_Controller 
{
    public function __construct(){
        parent::__construct();
        $this->load->model('SynFileMD');
        $this->load->model('GiaoDanModel');
        $this->load->model('GiaoDanMD');
        $dataImport = array('header'=>true);
        $this->load->library('CsvImport',$dataImport);
    }
    public function getFileSyn(){

    }
    public function getJob(){
        $syns = $this->SynFileMD->getAll();
        if(count($syns) > 0){
            $syn = $syns[0];
            // set status = 1
            $this->execute($syn);
        }
    }
    public function execute($syn){
        $dirData = 'C:/wamp64/www/qlgx_web/data/syn/';
        $dir = $dirData . $syn->MaGiaoXuSyn . '/' . $syn->ID;
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach($files as $file){
            $this->csvimport->setFileName($dir . '/' . $file);
            $data = $this->csvimport->get();
            if(!empty($data) && $file == 'GiaoDan.csv'){
                $method = "compare" . explode('.',$file)[0];
                $this->$method($data);
            }
        }
    }
    public function compareGiaoDan($datas){
        foreach($datas as $data){
            // by id, if same id => change
            // get last time change
            $giaoDans = $this->GiaoDanMD->getById($data['MaGiaoDan']);
            if(isset($giaoDans) && count($giaoDans) > 0){ //=> đã tồn tại giáo dân
                $giaoDan = $giaoDans[0];
                if(strtotime($giaoDan->UpdateDate) < strtotime($data['UpdateDate'])){
                    $this->GiaoDanMD->update($data);
                }
                        //else => insert
            } else {
                $this->GiaoDanMD->insert($data);
            }
            // merge
            // by MaNhanDang
            $giaoDans = $this->GiaoDanMD->getByMaNhanDang($data['MaNhanDang']);
            if(isset($giaoDans) && count($giaoDans) > 1){ //=> trùng
                $this->GiaoDanMD->deleteByMaNhanDang($data['MaNhanDang']);
            }
            // by name +  tenthanh + ngaysinh
            if(!empty($data['HoTen']) && !empty($data['NgaySinh']) && !empty($data['TenThanh'])) {
                $giaoDans = $this->GiaoDanMD->getByInfo($data['HoTen'],$data['TenThanh'],$data['NgaySinh']);
                if(isset($giaoDans) && count($giaoDans) > 1){ //=> trùng
                    $this->GiaoDanMD->deleteByInfo($data['HoTen'],$data['TenThanh'],$data['NgaySinh']);
                }
            }
        }
    }
    public function compareGiaDinh($data){

    }
    
}
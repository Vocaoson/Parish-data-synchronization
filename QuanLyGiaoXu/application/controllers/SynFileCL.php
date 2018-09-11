<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SynFileCL extends CI_Controller 
{
    private $dataDir;
    public function __construct(){
        parent::__construct();
        $this->load->model('SynFileMD');
        $this->dataDir = $this->config->item("data_dir");
        if(!is_dir($this->dataDir . '/temp_syn/')) {
            mkdir($this->dataDir . '/temp_syn/');
        }
        if(!is_dir($this->dataDir . '/syn/')) {
            mkdir($this->dataDir . '/syn/');
        }
    }
    public function getFileSyn($maGiaoXuSyn){
        if(isset($_FILES) && count($_FILES) > 0){
            $zip = new ZipArchive();
            $dirTemp = $this->dataDir . '/temp_syn/';
            $file = $dirTemp . $_FILES['file']['name'];
            if(count($_FILES) > 0){
                move_uploaded_file($_FILES['file'][tmp_name],$file);
            }
            
            $res = $zip->open($file);
            if($res === true){
                $synId = $this->insert($maGiaoXuSyn);
                if($synId) {
                    $path = $this->getStorePath($maGiaoXuSyn,$synId);
                    if(is_dir($path)){
                        $zip->extractTo($path);
                    }
                }
            } else {
                echo -1;
            }
            $zip->close();
        }
    }
    public function insert($maGiaoXuSyn){
        $this->SynFileMD->uploadedDate = date('m-d-Y H:i:s');
        $this->SynFileMD->maGiaoXuSyn = $maGiaoXuSyn;
        $result = $this->SynFileMD->insert();
        return $result;
    }
    public function getStorePath($maGiaoXuSyn,$synId){
        $dirData = $this->dataDir . '/syn/';
        $dir = $dirData . $maGiaoXuSyn . '/' . $synId;
        if(!is_dir($dir)){
            $rs = mkdir($dir,0700,true);
        }
        return $dir;
    }
    
}
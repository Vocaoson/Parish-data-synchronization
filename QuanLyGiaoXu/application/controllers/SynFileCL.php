<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SynFileCL extends CI_Controller 
{
    public function __construct(){
        parent::__construct();
        $this->load->model('SynFileMD');
    }
    public function getFileSyn($maGiaoXuSyn){
        if(isset($_FILES) && count($_FILES) > 0){
            $zip = new ZipArchive();
            $dirTemp = 'C:/wamp64/www/qlgx_web/data/temp_syn/';
            $file = $dirTemp . $_FILES['file']['name'];
            //$file = 'E:/test.csv.zip';
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
        $dirData = 'C:/wamp64/www/qlgx_web/data/syn/';
        $dir = $dirData . $maGiaoXuSyn . '/' . $synId;
        if(!is_dir($dir)){
            $rs = mkdir($dir,0700,true);
        }
        return $dir;
    }
    
}
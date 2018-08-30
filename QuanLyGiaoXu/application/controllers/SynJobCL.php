<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SynJobCL extends CI_Controller 
{
    public function __construct(){
        parent::__construct();
        $this->load->model('SynFileMD');
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
                $className = explode('.',$file)[0] . "CompareCL";
                include_once($className . '.php');
                $compare = new $className();
                $compare->compare($data);
            }
        }
    }
}
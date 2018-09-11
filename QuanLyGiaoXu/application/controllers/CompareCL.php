<?php
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class CompareCL extends CI_Controller {

    public $file;
    public $data;
    public $dir;
    public function __construct($file,$dir) {
        parent::__construct();
        $dataImport = array('header'=>true);
        $this->load->library('CsvImport',$dataImport);
        $this->file = $file;
        $this->dir = $dir;
        $this->data = $this->getData();
    }
    abstract public function compare();
    public function toBool($data){
        $datas = $data;
        foreach ($datas as $key => &$value) {
            foreach($value as $subKey => $subValue) {
                if($subValue === "False") {
                    $value[$subKey] = 0;
                } else if($subValue === "True") {
                    $value[$key] = -1;
                }
            }
        }
        return $datas;
    }
    public function getData() {
        $this->csvimport->setFileName($this->dir . '/' . $this->file);
        $data = $this->csvimport->get();
        $data = $this->toBool($data);
        return $data;
    }
}
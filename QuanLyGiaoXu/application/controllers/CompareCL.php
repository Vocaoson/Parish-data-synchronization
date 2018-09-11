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
    public function toBool(&$data){
        foreach ($data as $key => $value) {
            if($value === "False") {
                $data[$key] = 0;
            } else if($value === "True") {
                $data[$key] = -1;
            }
        }
    }
    public function getData() {
        $this->csvimport->setFileName($this->dir . '/' . $this->file);
        $data = $this->csvimport->get();
        return $data;
    }
}
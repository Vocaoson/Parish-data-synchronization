<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once('CompareCL.php');
class LinhMucCompareCL extends CompareCL {
    public function __construct($file,$syn) 
    {
        parent::__construct($file,$syn);
        $this->load->model("LinhMucMD");
    }
    public function compare()
    {
        foreach($this->data as $data){
        
        }
    }
    
}
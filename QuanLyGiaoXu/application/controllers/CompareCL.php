<?php
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class CompareCL extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }
    abstract public function compare($data);
    public function toBool(&$data){
        foreach ($data as $key => $value) {
            if($value === "False") {
                $data[$key] = 0;
            } else if($value === "True") {
                $data[$key] = -1;
            }
        }
    }
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GiaoPhanCL extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('GiaoPhanMD');
	}
	public function index()
	{
		
	}
	public function getPassWord()
	{
		foreach (getallheaders() as $name => $value) {
			if (strtolower($name)=="password") {
				if ($value=="admin") {
					return true;
				}
				else{
					return false;
				}
			}
		}
	}
	public function getGPjson()
	{
		if (!$this->getPassWord()) {
			return false;
		}
		$rs=$this->GiaoPhanMD->getGPjsonMD();
		if (count($rs)>0) {
			echo json_encode($rs);
			return;
		}
		else {
			echo -1;
			return;
		}
	}

}

/* End of file GiaoPhanCL.php */
/* Location: ./application/controllers/GiaoPhanCL.php */
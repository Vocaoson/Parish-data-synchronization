<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GiaoHatCL extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('GiaoHatMD');
		$this->load->library('GetAllHeader');
	}
	public function index()
	{
		
	}
	public function getGHByIdGP($idGP,$status = '')
	{
		if($status == "web"){
			$rs=$this->GiaoHatMD->getGHjsonMDWeb($idGP);
		} else {
			$rs=$this->GiaoHatMD->getGHjsonMD($idGP);
		}
		if (count($rs)>0) {
			echo json_encode($rs);
			return;
		}
		else {
			echo -1;
			return;
		}
	}
	public function getPassWord()
	{
		foreach ($this->getallheader->getAllHeaders() as $name => $value) {
			if ($name=="PassWord") {
				if ($value=="admin") {
					return true;
				}
				else{
					return false;
				}
			}
		}
	}
	public function getGHjson()
	{
		if (!$this->getPassWord()) {
			return false;
		}
		$rs=$this->GiaoHatMD->getGHjsonMD();
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

/* End of file GiaoHatCL.php */
/* Location: ./application/controllers/GiaoHatCL.php */
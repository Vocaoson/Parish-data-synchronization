<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TestCL extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
	}
	public function test()
	{
		$connect=odbc_connect('D:\giaoxu', 'Admin', 'khoanvnit');
		if (true) {
			
		}
	}

}

/* End of file TestCL.php */
/* Location: ./application/controllers/TestCL.php */
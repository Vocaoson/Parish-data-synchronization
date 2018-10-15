<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MSMD  {

	public function __construct()
	{
		$this->DBMS=$this->load->database('access',TRUE);
		$this->DBMS->query('Select * from GiaoDan');
		$rs=$this->DBMS->result()
		;
		// $connection = odbc_connect("Driver={Microsoft Access Driver (*.mdb)};Dbq=http://localhost:8080/PHP/ThucTap/ServerQlgx12/giaoxu.mdb", 'Admin', 'khoanvnit');
		
	}
	private $DBMS;
}

/* End of file MSMD.php */
/* Location: ./application/models/MSMD.php */
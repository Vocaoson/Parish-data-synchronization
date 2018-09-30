<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ChuyenXuMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table="chuyenxu";
	}
	public function deldeleteMaGiaoDan($maGiaoDan,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
}

/* End of file ChuyenXuMD.php */
/* Location: ./application/models/ChuyenXuMD.php */
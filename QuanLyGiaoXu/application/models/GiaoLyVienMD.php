<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GiaoLyVienMD extends CI_Model {

	
	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table="GiaoLyVien";
	}
	public function delete($maGiaoDan,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
}

/* End of file GiaoLyVienMD.php */
/* Location: ./application/models/GiaoLyVienMD.php */
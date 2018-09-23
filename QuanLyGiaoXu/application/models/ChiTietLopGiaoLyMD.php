<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ChiTietLopGiaoLyMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table="ChiTietLopGiaoLy";
	}
	public function delete($maGiaoDan,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}

}

/* End of file ChiTietLopGiaoLyMD.php */
/* Location: ./application/models/ChiTietLopGiaoLyMD.php */
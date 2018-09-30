<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GiaoLyVienMD extends CI_Model {

	
	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table="GiaoLyVien";
	}

	public function getAll($MaGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function deleteTwoKey($MaLop,$MaGiaoDan,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaGiaoDan', $MaGiaoDan);
		$this->db->where('MaLop', $MaLop);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	public function deleteMaLop($MaLop,$MaGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		$this->db->where('MaLop', $MaLop);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	public function deleteMaGiaoDan($maGiaoDan,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
}

/* End of file GiaoLyVienMD.php */
/* Location: ./application/models/GiaoLyVienMD.php */
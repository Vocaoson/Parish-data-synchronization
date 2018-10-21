<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KhoiGiaoLyMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table='khoigiaoly';
	}
	public function getAllActive($maGiaoXu)
	{

		$this->db->where('MaGiaoXuRieng', $maGiaoXu);
		$this->db->where('DeleteSV', 0);
		$query=$this->db->get($this->table);
		$data['field']=$this->db->list_fields($this->table);
		$data['data']= $query->result();
		return $data;

	}
	public function getAll($maGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function deleteMaKhoi($MaKhoi,$MaGiaoXuRieng)
	{
		$this->db->where('MaKhoi', $MaKhoi);
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	public function insert($data)
	{
		unset($data['MaKhoi']);
		unset($data['UpdateDate']);
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	public function update($data,$MaKhoi)
	{
		unset($data['MaKhoi']);
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$this->db->where('MaKhoi', $MaKhoi);
		$this->db->update($this->table, $data);
	}	

	public function getByDK1($data)
	{
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$this->db->where('TenKhoi', $data['TenKhoi']);
		$query=$this->db->get($this->table);
		return $query->result();
	}

}

/* End of file KhoiGiaoLyMD.php */
/* Location: ./application/models/KhoiGiaoLyMD.php */
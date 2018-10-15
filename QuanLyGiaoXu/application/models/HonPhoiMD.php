<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HonPhoiMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table='honphoi';
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
	public function delete($MaHonPhoi,$maGiaoXuRieng)
	{
		$this->db->where('MaHonPhoi', $MaHonPhoi);
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	public function getHonPhoiByDK1($data)
	{
		$this->db->where('MaNhanDang', $data['MaNhanDang']);
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$query=$this->db->get($this->table);
		return $query->row();
	}
	public function getHonPhoiByDK2($data)
	{
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$this->db->where('TenHonPhoi', $data['TenHonPhoi']);
		$this->db->where('NgayHonPhoi', $data['NgayHonPhoi']);
		$this->db->where('SoHonPhoi', $data['SoHonPhoi']);
		$query=$this->db->get($this->table);
		return $query->row();
	}
	public function insert($data)
	{
		unset($data['MaHonPhoi']);
		unset($data['UpdateDate']);
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	public function update($data,$maHonPhoi)
	{
		unset($data['MaHonPhoi']);
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$this->db->where('MaHonPhoi', $maHonPhoi);
		$this->db->update($this->table, $data);
	}
}

/* End of file HonPhoiMD.php */
/* Location: ./application/models/HonPhoiMD.php */
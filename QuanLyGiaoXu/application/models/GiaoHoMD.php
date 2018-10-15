<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GiaoHoMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table='GiaoHo';
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
	public function delete($maGiaoHo,$maGiaoXu)
	{
		$this->db->set('DeleteSV',1);
		$this->db->where('MaGiaoHo', $maGiaoHo);
		$this->db->where('MaGiaoXuRieng', $maGiaoXu);
		$this->db->update($this->table);

	}
	public function getAllListIDGiaoHo($maGiaoXu)
	{
		// $this->db->select('MaGiaoHo');
		$this->db->where('MaGiaoXuRieng', $maGiaoXu);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function update($data,$maGiaoHo)
	{
		unset($data['MaGiaoHo']);
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$this->db->where('MaGiaoHo', $maGiaoHo);
		$this->db->update($this->table, $data);
	}
	public function insert($data)
	{
		unset($data['MaGiaoHo']);
		unset($data['UpdateDate']);
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	public function getByMaNhanDang($maNhanDang,$maGiaoXu)
	{
		$this->db->where('MaNhanDang', $maNhanDang);
		$this->db->where('MaGiaoXuRieng', $maGiaoXu);	
		$query=$this->db->get($this->table);
		return $query->row();
	}

}

/* End of file GiaoHoMD.php */
/* Location: ./application/models/GiaoHoMD.php */
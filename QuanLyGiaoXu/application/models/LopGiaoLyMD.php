<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LopGiaoLyMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table='lopgiaoly';
	}
	public function getAllActive($maGiaoXu)
	{

		$this->db->where('MaGiaoXuRieng', $maGiaoXu);
		$query=$this->db->get($this->table);
		$data['field']=$this->db->list_fields($this->table);
		$data['data']= $query->result();
		return $data;

	}
	public function getAll($MaGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function deleteReal($dataSV)
	{
		$this->db->where('MaLop', $dataSV->MaLop);
		$this->db->where('MaGiaoXuRieng', $dataSV->MaGiaoXuRieng);
		$this->db->delete($this->table);
	}
	public function insert($data)
	{
		unset($data['MaLop']);
		unset($data['UpdateDate']);
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	public function update($data,$MaLop)
	{
		unset($data['MaLop']);
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$this->db->where('MaLop', $MaLop);
		$this->db->update($this->table, $data);
	}	

	public function deleteMaLop($MaLop,$MaGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		$this->db->where('MaLop', $MaLop);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	public function getByMaKhoi($MaKhoi,$MaGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		
		$this->db->where('MaKhoi', $MaKhoi);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function getByDK1($data)
	{
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		

		$this->db->where('TenLop', $data['TenLop']);
		$this->db->where('Nam', $data['Nam']);
		$this->db->where('MaKhoi', $data['MaKhoi']);
		$query=$this->db->get($this->table);
		return $query->row();
	}

}

/* End of file LopGiaoLyMD.php */
/* Location: ./application/models/LopGiaoLyMD.php */
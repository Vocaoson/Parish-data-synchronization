<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ChuyenXuMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table="chuyenxu";
	}
	public function getAllActive($maGiaoXu,$timeClient)
	{

		$this->db->where('MaGiaoXuRieng', $maGiaoXu);
		$this->db->where('UpdateDate>', $timeClient);
		$query=$this->db->get($this->table);
		$data['field']=$this->db->list_fields($this->table);
		$data['data']= $query->result();
		return $data;

	}
	public function deleteById($maChuyenXu,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaChuyenXu', $maChuyenXu);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	public function insert($chuyenXuArray)
	{
		unset($chuyenXuArray['MaChuyenXu']);
		unset($chuyenXuArray['UpdateDate']);
		$this->db->insert($this->table, $chuyenXuArray);
		return $this->db->insert_id();
	}
	public function update($chuyenXuArray,$id)
	{
		unset($chuyenXuArray['MaChuyenXu']);
		return $this->db->update($this->table, $chuyenXuArray,"MaChuyenXu='$id'");
	}
	public function getAll($MaGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		$this->db->where('DeleteSV', 0);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function getByIdGiaoDan($maGiaoDan)
	{
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function deleteMaGiaoDan($maGiaoDan,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
}

/* End of file ChuyenXuMD.php */
/* Location: ./application/models/ChuyenXuMD.php */
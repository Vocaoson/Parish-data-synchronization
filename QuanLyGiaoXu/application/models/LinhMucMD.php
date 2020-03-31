<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LinhMucMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table="LinhMuc";
	}

	//Tạm xóa
	/*
	public function getAllActive($maGiaoXu)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXu);
		$query=$this->db->get($this->table);
		$data['field']=$this->db->list_fields($this->table);
		$data['data']= $query->result();
		return $data;

	}
	public function deleteById($maLinhMuc,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaLinhMuc', $maLinhMuc);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	public function insert($linhMucArray)
	{
		unset($linhMucArray['MaLinhMuc']);
		unset($linhMucArray['UpdateDate']);
		$this->db->insert($this->table, $linhMucArray);
		return $this->db->insert_id();
	}
	public function update($linhMucArray,$id)
	{
		unset($linhMucArray['MaLinhMuc']);
		return $this->db->update($this->table, $linhMucArray,"MaLinhMuc='$id'");
	}
	public function getAll($MaGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		$this->db->where('DeleteSV', 0);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function getByInfo($tenThanh,$hoTen,$chucVu,$maGiaoXuRieng) 
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$this->db->where('DeleteSV', 0);
		$this->db->where('TenThanh', $tenThanh);
		$this->db->where('HoTen', $hoTen);
		$this->db->where('ChucVu', $chucVu);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	 */
} 
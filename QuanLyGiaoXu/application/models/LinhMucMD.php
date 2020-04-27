<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LinhMucMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table="LinhMuc";
	}
	public function insert($data)
	{
		unset($data['MaLinhMuc']);
		unset($data['KhoaChinh']);
		unset($data['DeleteClient']);
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	public function update($data)
	{
		$maLinhMuc=$data['MaLinhMuc'];
		unset($data['MaLinhMuc']);
		unset($data['KhoaChinh']);
		unset($data['DeleteClient']);
		$data['DeleteSV']=0;
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$this->db->where('MaLinhMuc', $maLinhMuc);
		$this->db->update($this->table, $data);
	}
	public function delete($objectSV)
	{
		$this->db->set('DeleteSV',1);
		$this->db->where('MaLinhMuc', $objectSV->MaLinhMuc);
		$this->db->where('MaGiaoXuRieng', $objectSV->MaGiaoXuRieng);
		$this->db->update($this->table);
	}
	public function getByInfo($dieuKien,$MaGiaoXuRieng)
	{
		$this->db->select('*');
		$this->db->where($dieuKien);
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->row();
	}  
	public function getByMaLinhMuc($maLinhMuc)
	{
		$this->db->select('*');
		$this->db->where('MaLinhMuc', $maLinhMuc);
		$query=$this->db->get($this->table);
		return $query->row();
	} 
	public function getAllByMaGiaoXuRiengAndDiffMaDinhDanh($maGiaoXuRieng,$maDinhDanh)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$this->db->where('MaDinhDanh !=', $maDinhDanh);
		$query=$this->db->get($this->table);
		$data['field']=$this->db->list_fields($this->table);
		$data['data']= $query->result();
		return $data;
	}   

	//Tạm xóa
	/*
	public function getAll($MaGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		$this->db->where('DeleteSV', 0);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	 */
} 
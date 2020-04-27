<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CauHinhMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table="CauHinh";
	}
	public function insert($data)
	{
		unset($data['DeleteClient']);
		$this->db->insert($this->table, $data);
	}
	public function update($data)
	{
		$maCauHinh=$data['MaCauHinh'];
		$maGiaoXuRieng=$data['MaGiaoXuRieng'];
		unset($data['MaCauHinh']);
		unset($data['MaGiaoXuRieng']);
		unset($data['DeleteClient']);
		$data['DeleteSV']=0;
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$this->db->where('MaCauHinh', $maCauHinh);
		$this->db->update($this->table, $data);
	}
	public function delete($objectSV)
	{
		$this->db->set('DeleteSV',1);
		$this->db->where('MaCauHinh', $objectSV->MaCauHinh);
		$this->db->where('MaGiaoXuRieng', $objectSV->MaGiaoXuRieng);
		$this->db->update($this->table);
	}
	public function getByMaCauHinhMaGiaoXuRieng($maCauHinh,$maGiaoXuRieng)
	{
		$this->db->select('*');
		$this->db->where('MaCauHinh',$maCauHinh);
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
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
	//Tam xoa
	/*
	public function deleteById($maCauHinh,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaCauHinh', $maCauHinh);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	
	public function getAll($MaGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		$this->db->where('DeleteSV', 0);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	*/
} 
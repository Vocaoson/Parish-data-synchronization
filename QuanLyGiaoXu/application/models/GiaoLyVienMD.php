<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GiaoLyVienMD extends CI_Model {

	
	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table="GiaoLyVien";
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
	public function findwithID($MaLop,$maGiaoDan,$maGiaoXu)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXu);
		$this->db->where('MaLop', $MaLop);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$query=$this->db->get($this->table);
		return $query->row();
	}
	public function insert($data,$maGiaoDan,$MaLop)
	{
		unset($data['UpdateDate']);
		//2018/10/29 son add start
		unset($data['DeleteClient']);
		//2018/10/29 son add start
		$data['MaLop']=$MaLop;
		$data['MaGiaoDan']=$maGiaoDan;
		$this->db->insert($this->table, $data);
	}
	public function update($data,$maGiaoDan,$MaLop)
	{
		unset($data['UpdateDate']);
		//2018/10/29 son add start
		unset($data['DeleteClient']);
		//2018/10/29 son add start
		unset($data['MaLop']);
		unset($data['MaGiaoDan']);
		$this->db->where('MaLop', $MaLop);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$this->db->update($this->table, $data);
	}
}

/* End of file GiaoLyVienMD.php */
/* Location: ./application/models/GiaoLyVienMD.php */
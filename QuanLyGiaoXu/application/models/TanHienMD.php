<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TanHienMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table="TanHien";
	}
	public function getAllActive($maGiaoXu)
	{

		$this->db->where('MaGiaoXuRieng', $maGiaoXu);
		$query=$this->db->get($this->table);
		$data['field']=$this->db->list_fields($this->table);
		$data['data']= $query->result();
		return $data;

	}
  	public function insert($tanHienArray)
	{
		unset($tanHienArray['MaTanHien']);
		unset($tanHienArray['UpdateDate']);
		$this->db->insert($this->table, $tanHienArray);
		return $this->db->insert_id();
	}
	public function update($tanHienArray,$id)
	{
		unset($tanHienArray['MaTanHien']);
		return $this->db->update($this->table, $tanHienArray,"MaTanHien='$id'");
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

/* End of file TanHienMD.php */
/* Location: ./application/models/TanHienMD.php */
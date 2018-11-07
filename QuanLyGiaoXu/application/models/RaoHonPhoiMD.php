<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RaoHonPhoiMD extends CI_Model {
	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table="RaoHonPhoi";
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
  	public function deleteById($maRaoHonPhoi,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaRaoHonPhoi', $maRaoHonPhoi);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	public function insert($raoHonPhoiArray)
	{
		unset($raoHonPhoiArray['MaRaoHonPhoi']);
		unset($raoHonPhoiArray['UpdateDate']);
		$this->db->insert($this->table, $raoHonPhoiArray);
		return $this->db->insert_id();
	}
	public function update($raoHonPhoiArray,$id)
	{
		unset($raoHonPhoiArray['MaRaoHonPhoi']);
		return $this->db->update($this->table, $tanHienArray,"MaRaoHonPhoi='$id'");
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
		$this->db->where('MaGiaoDan1', $maGiaoDan);
		$this->db->or_where('MaGiaoDan2', $maGiaoDan);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}

	

}

/* End of file RaoHonPhoiMD.php */
/* Location: ./application/models/RaoHonPhoiMD.php */
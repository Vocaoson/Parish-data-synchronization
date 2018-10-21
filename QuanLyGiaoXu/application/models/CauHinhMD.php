<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CauHinhMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table="CauHinh";
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
	public function deleteById($maCauHinh,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaCauHinh', $maCauHinh);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	public function insert($cauHinhArray)
	{
        unset($cauHinhArray['UpdateDate']);
		$this->db->insert($this->table, $cauHinhArray);
		return $this->db->insert_id();
	}
	public function update($cauHinhArray,$id)
	{
		unset($cauHinhArray['MaCauHinh']);
		return $this->db->update($this->table, $cauHinhArray,"MaCauHinh='$id'");
	}
	public function getAll($MaGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		$this->db->where('DeleteSV', 0);
		$query=$this->db->get($this->table);
		return $query->result();
    }
    public function getByInfo($maCauHinh,$maGiaoXuRieng) 
    {
        $this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
        $this->db->where('DeleteSV', 0);
        $this->db->where('MaCauHinh', $maCauHinh);
		$query=$this->db->get($this->table);
		return $query->result();
    }
} 
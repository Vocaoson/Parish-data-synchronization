<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DotBiTichMD extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->table="dotbitich";
	}
	private $table;
	public function getAllActive($maGiaoXu)
	{

		$this->db->where('MaGiaoXuRieng', $maGiaoXu);
		$this->db->where('DeleteSV', 0);
		$query=$this->db->get($this->table);
		$data['field']=$this->db->list_fields($this->table);
		$data['data']= $query->result();
		return $data;

	}
	public function getAll($maGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function delete($MaDotBiTich,$maGiaoXuRieng)
	{
		$this->db->set('DeleteSV',1);
		$this->db->where('MaDotBiTich', $MaDotBiTich);
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		
		$this->db->update($this->table);
	}
	/**
	 * [getByDK1 Tim Dot bi tich theo MoTa , LoaiBiTich, NgayBiTich,Linh muc]
	 * @param  [type] $discription [description]
	 * @param  [type] $data        [description]
	 * @param  [type] $type        [description]
	 * @param  [type] $linhmuc     [description]
	 * @return [type]              [description]
	 */
	public function getByDK1($discription,$date,$type,$linhmuc,$maGiaoXuRieng)
	{
		$this->db->where('MoTa', $discription);
		$this->db->where('NgayBiTich', $date);
		$this->db->where('LoaiBiTich', $type);
		$this->db->where('LinhMuc', $linhmuc);
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->row();
	}
	public function insert($data)
	{
		unset($data['MaDotBiTich']);
		unset($data['UpdateDate']);
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	public function update($data,$maDotBiTich)
	{
		unset($data['MaDotBiTich']);
		$this->db->where('MaDotBiTich', $maDotBiTich);
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$this->db->update($this->table,$data);

	}

}

/* End of file DotBiTichMD.php */
/* Location: ./application/models/DotBiTichMD.php */
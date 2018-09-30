<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BiTichChiTietMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table='bitichchitiet';
	}
	public function getAll($maGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function deleteTwoKey($MaDotBiTich,$MaGiaoDan,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaGiaoDan', $MaGiaoDan);
		$this->db->where('MaDotBiTich', $MaDotBiTich);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	public function delete($maGiaoDan,$MaDotBiTich,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$this->db->where('MaDotBiTich', $MaDotBiTich);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	public function deleteMaDotBiTich($MaDotBiTich,$MaGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $MaGiaoXuRieng);
		$this->db->where('MaDotBiTich', $MaDotBiTich);
		$this->db-set('DeleteSV',1);
		$this->db->update($this->table);
	}
	public function insert($data,$maGiaoDan,$MaDotBiTich)
	{
		unset($data['UpdateDate']);
		$data['MaDotBiTich']=$MaDotBiTich;
		$data['MaGiaoDan']=$maGiaoDan;
		$this->db->insert($this->table, $data);
		
	}
	public function update($data,$maGiaoDan,$MaDotBiTich)
	{
		unset($data['UpdateDate']);
		unset($data['MaDotBiTich']);
		unset($data['MaGiaoDan']);
		$this->db->where('MaDotBiTich', $MaDotBiTich);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$this->db->update($this->table, $data);
	}
	public function findwithID($maDotBiTich,$maGiaoDan,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaDotBiTich', $maDotBiTich);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$query=$this->db->get($this->table);
		return $query->row();
	}
	public function deleteMaGiaoDan($maGiaoDan,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}

}

/* End of file BiTichChiTietMD.php */
/* Location: ./application/models/BiTichChiTietMD.php */
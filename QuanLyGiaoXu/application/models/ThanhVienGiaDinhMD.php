<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ThanhVienGiaDinhMD extends CI_Model {

	
	public function __construct()
	{
		parent::__construct();
		$this->table="ThanhVienGiaDinh";
	}
	private $table;
	public function delete($maGiaDinh,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaGiaDinh', $maGiaDinh);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	public function findTVGDwithIDVT($maGiaoDan,$vaitro,$magiadinh,$magiaoxurieng)
	{
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$this->db->where('VaiTro', $vaitro);
		$this->db->where('MaGiaDinh', $maGiaDinh);
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$query=$this->db->get($this->table);
		return $query->row();
	}
	/**
	 * [findTVGD ]
	 * @param  [type] $maGiaoDan [description]
	 * @param  [type] $maGiaDinh [description]
	 * @param  [type] $maGiaoXu  [description]
	 * @return [type]            [description]
	 */
	public function findTVGDwithID($maGiaoDan,$maGiaDinh,$maGiaoXu)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXu);
		$this->db->where('MaGiaDinh', $maGiaDinh);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$query=$this->db->get($this->table);
		return $query->row();
	}
	public function update($data,$maGiaoDan,$maGiaDinh)
	{
		unset($data['UpdateDate']);
		$data['MaGiaDinh']=$maGiaDinh;
		$data['MaGiaoDan']=$maGiaoDan;
		$this->db->update($this->table, $data);
	}
	public function delete($maGiaDinh,$maGiaoXu)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXu);
		$this->db->where('MaGiaDinh', $maGiaDinh);
		$this->db->delete($this->table);
	}
	public function insert($data,$maGiaoDan,$maGiaDinh)
	{
		unset($data['UpdateDate']);
		$data['MaGiaDinh']=$maGiaDinh;
		$data['MaGiaoDan']=$maGiaoDan;
		$this->db->insert($this->table, $data);
		
	}
	public function getTVGD($maGiaoXu,$maGiaDinh)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXu);
		$this->db->where('MaGiaDinh', $maGiaDinh);
		$query=$this->db->get($this->table);
		return $query->result();
	}

}

/* End of file ThanhVienGiaDinhMD.php */
/* Location: ./application/models/ThanhVienGiaDinhMD.php */
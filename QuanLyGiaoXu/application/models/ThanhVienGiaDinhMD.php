<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ThanhVienGiaDinhMD extends CI_Model {

	
	public function __construct()
	{
		parent::__construct();
		$this->table="ThanhVienGiaDinh";
	}
	private $table;
	public function getAllActive($maGiaoXu,$timeClient)
	{

		$this->db->where('MaGiaoXuRieng', $maGiaoXu);
		$this->db->where('UpdateDate>', $timeClient);
		$query=$this->db->get($this->table);
		$data['field']=$this->db->list_fields($this->table);
		$data['data']= $query->result();
		return $data;

	}
	public function deleteTwoKey($MaGiaDinh,$MaGiaoDan,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaGiaoDan', $MaGiaoDan);
		$this->db->where('MaGiaDinh', $MaGiaDinh);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	public function delete($maGiaoDan,$maGiaDinh,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$this->db->where('MaGiaDinh', $maGiaDinh);
		$this->db->set('DeleteSV',1);
		$this->db->update($this->table);
	}
	//Moi xua xe sinh bug
	public function deleteMaGiaDinh($maGiaDinh,$magiaoxurieng)
	{
		$this->db->where('MaGiaoXuRieng', $magiaoxurieng);
		$this->db->where('MaGiaDinh', $maGiaDinh);
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
	public function findwithID($maGiaDinh,$maGiaoDan,$maGiaoXu)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXu);
		$this->db->where('MaGiaDinh', $maGiaDinh);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$query=$this->db->get($this->table);
		return $query->row();
	}
	public function getAll($maGiaoXuRieng)
	{
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function update($data,$maGiaoDan,$maGiaDinh)
	{
		unset($data['UpdateDate']);
		//2018/10/29 son add start
		unset($data['DeleteClient']);
		//2018/10/29 son add start
		unset($data['MaGiaDinh']);
		unset($data['MaGiaoDan']);
		$this->db->where('MaGiaDinh', $maGiaDinh);
		$this->db->where('MaGiaoDan', $maGiaoDan);
		$this->db->where('MaGiaoXuRieng', $data['MaGiaoXuRieng']);
		$this->db->update($this->table, $data);
	}
	// public function delete($maGiaDinh,$maGiaoXu)
	// {
	// 	$this->db->where('MaGiaoXuRieng', $maGiaoXu);
	// 	$this->db->where('MaGiaDinh', $maGiaDinh);
	// 	$this->db->set('DeleteSV',1);
	// 	$this->db->update($this->table);
	// }
	public function insert($data,$maGiaoDan,$maGiaDinh)
	{
		unset($data['UpdateDate']);
		//2018/10/29 son add start
		unset($data['DeleteClient']);
		//2018/10/29 son add start
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
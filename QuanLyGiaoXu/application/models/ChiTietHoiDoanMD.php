<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ChiTietHoiDoanMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		$this->table="chitiethoidoan";
	}
	public function delete($objectSV)
	{
		$this->db->set('DeleteSV',1);
		$this->db->where('ID', $objectSV->ID);
		$this->db->where('MaGiaoXuRieng', $objectSV->MaGiaoXuRieng);
		$this->db->update($this->table);
	}
	public function update($data)
	{
		$ID=$data['ID'];
		unset($data['ID']);
		unset($data['DeleteClient']);
		unset($data['KhoaChinh']);
		unset($data['KhoaNgoai']);
		$data['DeleteSV']=0;
		$this->db->where('ID', $ID);
		$this->db->update($this->table, $data);
	}
	public function insert($data)
	{
		unset($data['ID']);
		unset($data['DeleteClient']);
		unset($data['KhoaChinh']);
		unset($data['KhoaNgoai']);
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}
	public function getByID($ID){
		$this->db->where("ID",$ID);
		return $this->db->get($this->table)->row();
	}
	public function getByInfo($dieuKien,$maGiaoXuRieng)
	{
		$this->db->where($dieuKien);
		$this->db->where('MaGiaoXuRieng',$maGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->row();
	}
}

/* End of file ChiTietHoiDoanMD.php */
/* Location: ./application/models/ChiTietHoiDoanMD.php */
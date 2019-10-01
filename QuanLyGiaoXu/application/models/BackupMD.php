<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BackupMD extends CI_Model {

	private $table;
	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->table="backup";
	}
	public function getPathByID($id=-1)
	{
		if ($id!=-1) {
			$this->db->select('PathFile');
			$this->db->where('Id', $id);
			$query=$this->db->get($this->table);
			return $query->row();
		}
	}
	public function removeFile($id=-1)
	{
		if ($id!=-1) {
			$this->db->where('ID', $id);
			$this->db->delete($this->table);		
		}
	}
	public function getIdRemove($idgx)
	{
		$this->db->select('backup.ID,backup.PathFile');
		$this->db->from('giaoxu,backup');
		$this->db->where('giaoxu.ID', $idgx);
		$this->db->where('backup.ID=(SELECT backup.ID
			FROM backup
			WHERE backup.MaGiaoXuRieng=giaoxu.ID
			ORDER  by backup.ID ASC LIMIT 1
		)');
		$query=$this->db->get();
		return $query->row();
	}
	public function countFile($id)
	{
		$this->db->where('MaGiaoXuRieng', $id);
		return $this->db->count_all_results($this->table);
	}
	public function getBackupGx($idgx)
	{
		$this->db->where('MaGiaoXuRieng', $idgx);
		$query=$this->db->get($this->table);
		return $query->result();
	}
	public function getLastUploadMD($maGiaoXuRieng)
	{
		$this->db->select('Time');
		$this->db->where('MaGiaoXuRieng', $maGiaoXuRieng);
		$query=$this->db->get($this->table);
		return $query->row();

	}
	public function insertGiaoXuBackupMD($name,$path,$idgx,$dateUL)
	{
		$objectBK=array(
			"Name"=>$name,
			"PathFile"=>$path,
			"Time"=>$dateUL,
			"MaGiaoXuRieng"=>$idgx);
		$this->db->insert($this->table, $objectBK);
		return $this->db->affected_rows();
	}
}

/* End of file GiaoXuBackupMD.php */
/* Location: ./application/models/GiaoXuBackupMD.php */
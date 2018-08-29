<?php
class SynFileMD extends CI_Model 
{
    private $table;
    public $uploadedDate;
    public $maGiaoXuSyn;
	public function __construct()
	{
        parent::__construct();
        $this->table = 'syn_info';
    }
    public function insert(){
        $objectGX=array(
            'UploadedTime'=>$this->uploadedDate,
            'MaGiaoXuSyn'=>$this->maGiaoXuSyn
        );
		$this->db->insert($this->table, $objectGX);
		return $this->db->insert_id();
    }
    public function getAll(){
        $this->db->select('*');
		$this->db->from($this->table);
		$this->db->where('status = 0');
		$query=$this->db->get();
		return $query->result();
    }
}
?>
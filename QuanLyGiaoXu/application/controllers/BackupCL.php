<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BackupCL extends CI_Controller {
	
	private $vartest;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('BackupMD');
		$this->load->model('GiaoXuMD');
		date_default_timezone_set('Asia/Ho_Chi_Minh');
		$this->vartest=$this->config->item('numfile');
	//Do your magic here
	}

	public function dowloadFile($id=-1)
	{
		if ($id!=-1) {
			$file=$this->BackupMD->getPathByID($id)->PathFile;
			if (file_exists($file)) {
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'.basename($file).'"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($file));
				readfile($file);
				exit;
			}
		}

	}
	public function getBackup($id=-1)
	{
		if ($id!=-1) {
			$rs=$this->BackupMD->getBackupGx($id);
			if (count($rs)>0) {
				echo json_encode($rs);
				return;
			}
			else{
				echo -1;
				return;
			}
		}
		else {
			echo -1;
			return;
		}
	}
	public function index($id=-1)
	{

	}
	public function getLastUpload($maGiaoXuRieng)
	{
		$rs=$this->BackupMD->getLastUploadMD($maGiaoXuRieng);
		if ($rs) {
			echo $rs;
		}
	}
	public function uploadFile($maGiaoXuRieng)
	{
		if (isset($_FILES) && count($_FILES) > 0) {
			$status=$this->GiaoXuMD->checkStatus($maGiaoXuRieng);
			if ($status->status==0) {
				echo 0;
				return;
			}
			$this->checkNumFileBackup($maGiaoXuRieng);
			//kiem tra duong dan
			$serverPath='files/'.$maGiaoXuRieng;
			if (!is_dir($serverPath)) {
				mkdir($serverPath);
			}
			if (move_uploaded_file($_FILES["file"]["tmp_name"],$serverPath.'/'.$_FILES["file"]["name"])) {
				//luu thong tin file lai
				$dateUL=date("Y-m-d H:i:s");
				$rs=$this->BackupMD->insertGiaoXuBackupMD($_FILES["file"]["name"],$serverPath.'/'.$_FILES["file"]["name"],$maGiaoXuRieng,$dateUL);
				if ($rs>0) {
					echo $dateUL;
				}
				else {
					echo 0;
				}
			}
			else{
				echo 0;
			}
		}
		else {
			echo -1;
		}
	}
	public function checkNumFileBackup($idgx=-1)
	{
		if ($idgx!=-1) {
			$totalFile=$this->BackupMD->countFile($idgx);
			if ($totalFile>=$this->vartest) {
				$file=$this->BackupMD->getIdRemove($idgx);
				unlink($file->PathFile);
				$this->BackupMD->removeFile($file->ID);
			}
		}
	}
	public function insertGiaoXuBackup()
	{
		if (isset($_POST)) {
			$this->checkNumFileBackup($_POST["IDGX"]);
			$rs=$this->BackupMD->insertGiaoXuBackupMD(
				$_POST["Name"],$_POST["Path"],$_POST["IDGX"]);
			if ($rs>0) {
				echo 1;
			}
			else {
				echo 0;
			}
		}
		else {
			echo -1;
		}
	}

}

/* End of file GiaoXuBackup.php */
/* Location: ./application/controllers/GiaoXuBackup.php */

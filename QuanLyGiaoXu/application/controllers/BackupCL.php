<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BackupCL extends CI_Controller {
	
	private $vartest;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('BackupMD');
		$this->load->model('GiaoXuMD');
		$this->load->model('MayNhapMD');
		date_default_timezone_set('Asia/Ho_Chi_Minh');
		$this->vartest=$this->config->item('numfile');
	//Do your magic here
	}
	public function uploadFileSyn($id=1)
	{
		if ($_FILES) {
			
		}
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
	public function getBackup($id=-1,$maDinhDanh)
	{
		if ($id!=-1) {
			$rs=$this->BackupMD->getBackupGx($id,$maDinhDanh);
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
	public function getAllFileByMaGiaoXuRieng($id=-1)
	{
		if ($id!=-1) {
			$success=array();
			$noidung="";
			$rs=$this->MayNhapMD->getAllMaDinhDanhByMaGiaoXuRieng($id);
			if (count($rs)>0) {
				for($i=0;$i<count($rs);$i++)
				{
					$noidung.='<hr>';
					$noidung.='<div class="col-xs-4">'.$rs[$i]->TenMay.'</div>';
					$rsListFile=$this->BackupMD->getAllBackupByMaDinhDanh($rs[$i]->MaDinhDanh);
					if(count($rsListFile)>0)
					{
						for($j=0;$j<count($rsListFile);$j++)
						{
							$noidung.='<div class="one-file">';
							$noidung.='<hr>';
							$noidung.='<div class="row">';
							$noidung.='<div class="col-xs-4">';
							$noidung.='<input type="hidden" name="" value="'.$rsListFile[$j]->ID.'">';
							$noidung.='<p class="btnDowload" data-id="'.$rsListFile[$j]->ID.'" data-path="'.$rsListFile[$j]->PathFile.'"><i class="fa fa-file-archive"></i> '.$rsListFile[$j]->Name.'</p>';
							$noidung.='</div>';
							$noidung.='<div class="col-xs-4">'.$rsListFile[$j]->Time.'</div>';
							$noidung.='<div class="col-xs-4">';
							$noidung.='<b class="btn btn-danger btnNewEmail">Soạn Email</b>';
							$noidung.='</div>';
							$noidung.='</div>';
							$noidung.='</div>';
						}
					}
				}
				$success["noidung"]=$noidung;
				echo json_encode($success);
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
	public function uploadFile($maGiaoXuRieng,$maDinhDanh,$tenMay)
	{
		if (isset($_FILES) && count($_FILES) > 0) {
			$status=$this->GiaoXuMD->checkStatus($maGiaoXuRieng);
			if ($status==null) {
				echo 0;
				return;
			}
			$this->checkNumFileBackup($maGiaoXuRieng,$maDinhDanh);
			//kiem tra duong dan
			$serverPath='files/'.$maGiaoXuRieng.'/'.$maDinhDanh;
			if (!is_dir($serverPath)) {
				$check=mkdir($serverPath,0777,TRUE);
			}
			if (move_uploaded_file($_FILES["file"]["tmp_name"],$serverPath.'/'.$_FILES["file"]["name"])) {
				//luu thong tin file lai
				$dateUL=date("Y-m-d H:i:s");
				$rs=$this->BackupMD->insertGiaoXuBackupMD($_FILES["file"]["name"],$serverPath.'/'.$_FILES["file"]["name"],$maGiaoXuRieng,$dateUL,$maDinhDanh,$tenMay);
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

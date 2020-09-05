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
		$this->load->library('MailHandler');
	//Do your magic here
	}
	public function uploadFileSyn($id=1)
	{
		if ($_FILES) {
			
		}
	}
	public function downloadFile($id=-1)
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
	public function getAllFileByMaGiaoXuRieng($maGiaoXuRieng=-1)
	{
		if ($maGiaoXuRieng!=-1) {
			$success=array();
			$noidung="";
			$rs=$this->MayNhapMD->getAllMaDinhDanhByMaGiaoXuRieng($maGiaoXuRieng);
			if (count($rs)>0) {
				for($i=0;$i<count($rs);$i++)
				{
					$noidung.='<hr>';
					$noidung.='<div style="height:20px;font-size:17px" class="col-xs-12">Tên máy nhập: '.$rs[$i]->TenMay.'</div>';
					$rsListFile=$this->BackupMD->getAllBackupByMaDinhDanh($rs[$i]->MaDinhDanh,$maGiaoXuRieng);
					if(count($rsListFile)>0)
					{
						for($j=0;$j<count($rsListFile);$j++)
						{
							$noidung.='<div style="height:20px" class="one-file">';
							$noidung.='<hr>';
							$noidung.='<div style="margin-top:-10px" class="row">';
							$noidung.='<div class="col-xs-3">';
							$noidung.='<input type="hidden" name="" value="'.$rsListFile[$j]->ID.'">';
							$noidung.='<p class="btnDownloadFile" data-id="'.$rsListFile[$j]->ID.'" data-path="'.$rsListFile[$j]->PathFile.'"data-info="'.$rsListFile[$j]->TongGiaoHo.'/'.$rsListFile[$j]->TongGiaoDan.'/'.$rsListFile[$j]->TongGiaDinh.'/'.$rsListFile[$j]->TongHonPhoi.'"><i class="fa fa-file-archive"></i> '.$rsListFile[$j]->Name.'</p>';
							$noidung.='</div>';
							$noidung.='<div class="col-xs-2">'.date('d-m-Y H:i:s',strtotime($rsListFile[$j]->Time)).'</div>';
							$noidung.='<div class="col-xs-4" style="text-align:center">'.$rsListFile[$j]->TongGiaoHo.' - '.$rsListFile[$j]->TongGiaoDan.' - '.$rsListFile[$j]->TongGiaDinh.' - '.$rsListFile[$j]->TongHonPhoi.'</div>';
							$noidung.='<div class="col-xs-3">';
							$noidung.='<b class="btn btn-danger btn-sm btnNewEmail">Soạn Email</b>';
							$noidung.='<b style="margin-left:25px" class="btn btn-primary btn-sm btnCopyFile">Chuyển File</b>';
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
	public function uploadFile($maGiaoXuRieng,$maDinhDanh,$tenMay,$tongGiaoHo,$tongGiaoDan,$tongGiaDinh,$tongHonPhoi)
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
				$rs=$this->BackupMD->insertGiaoXuBackupMD($_FILES["file"]["name"],$serverPath.'/'.$_FILES["file"]["name"],$maGiaoXuRieng,$dateUL,$maDinhDanh,$tenMay,$tongGiaoHo,$tongGiaoDan,$tongGiaDinh,$tongHonPhoi);
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
	public function checkNumFileBackup($maGiaoXuRieng,$maDinhDanh)
	{
		$totalFile=$this->BackupMD->countFile($maGiaoXuRieng,$maDinhDanh);
		if ($totalFile>=$this->vartest) {
			$file=$this->BackupMD->getIdRemove($maGiaoXuRieng,$maDinhDanh);
			unlink($file->PathFile);
			$this->BackupMD->removeFile($file->ID);
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

	public function getMayNhapByMaGiaoXuRieng($id,$maMayNhap)
	{
		$success=array();
		$rs=$this->MayNhapMD->getAllMaDinhDanhByMaGiaoXuRieng($id);
		if(count($rs)<2)
		{
			echo json_encode(-1);
			return;
		}
		$noidunghtml="<option value=\"\" selected disabled hidden>Chọn máy nhập</option>";
		for($i=0;$i<count($rs);$i++)
		{
			if($rs[$i]->MaDinhDanh!=$maMayNhap)
			$noidunghtml.="<option value=".$rs[$i]->MaDinhDanh.">".$rs[$i]->TenMay."</option>";
		}
		$success["noidung"]=$noidunghtml;
		echo json_encode($success);
		return;
	}
	public function copyFileAndInsertTable($maGiaoXuRieng,$maDinhDanhMayNhan,$maDinhDanhMayGui,$fileName,$tenMayNhan,$tongGiaoHo,$tongGiaoDan,$tongGiaDinh,$tongHonPhoi)
	{
		$serverPathNhan='files/'.$maGiaoXuRieng.'/'.$maDinhDanhMayNhan;
		$serverPathGui='files/'.$maGiaoXuRieng.'/'.$maDinhDanhMayGui;
		if (!is_dir($serverPathNhan)) {
			$check=mkdir($serverPathNhan,0777,TRUE);
		}
		if (copy($serverPathGui.'/'.$fileName,$serverPathNhan.'/'.$fileName)) {
			//luu thong tin file lai
			$dateUL=date("Y-m-d H:i:s");
			$rs=$this->BackupMD->insertGiaoXuBackupMD($fileName,$serverPathNhan.'/'.$fileName,$maGiaoXuRieng,$dateUL,$maDinhDanhMayNhan,$tenMayNhan,$tongGiaoHo,$tongGiaoDan,$tongGiaDinh,$tongHonPhoi);
			if ($rs>0) {
				return 1;
			}
			else {
				return 0;
			}
		}
		else{
			return 0;
		}
	}

	public function copyFile()
	{
		$result=-1;

		//data
		$maGiaoXuRieng=$this->input->post('MaGiaoXuRieng');
		$maDinhDanhMayNhan=$this->input->post('MaDinhDanhMayNhan');
		$maDinhDanhMayGui=$this->input->post('MaDinhDanhMayGui');
		$fileName=$this->input->post('FileName');
		$tenMayNhan=$this->input->post('TenMayNhan');
		$tongGiaoHo=$this->input->post('TongGiaoHo');
		$tongGiaoDan=$this->input->post('TongGiaoDan');
		$tongGiaDinh=$this->input->post('TongGiaDinh');
		$tongHonPhoi=$this->input->post('TongHonPhoi');
		
		$to=$this->input->post('To');
		$subject=$this->input->post('Subject');
		$body=$this->input->post('Body');
		//Thực hiện công việc chuyển file
		$cpfile=$this->copyFileAndInsertTable($maGiaoXuRieng,$maDinhDanhMayNhan,$maDinhDanhMayGui,$fileName,$tenMayNhan,$tongGiaoHo,$tongGiaoDan,$tongGiaDinh,$tongHonPhoi);
		if($cpfile==0)
		{
			echo json_encode(-1);
			return;
		}
		//Thực hiện công việc gửi mail
		$rsMail=$this->mailhandler->SendMail($to,$subject,$body);
		if($rsMail)
		{
			$result=1;
		}
		echo json_encode($result);
		return;
	}

}

/* End of file GiaoXuBackup.php */
/* Location: ./application/controllers/GiaoXuBackup.php */

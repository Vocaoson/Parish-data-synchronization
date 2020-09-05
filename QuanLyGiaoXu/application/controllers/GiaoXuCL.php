<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class GiaoXuCL extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('GiaoXuMD');
		$this->load->model('GiaoXuDoiMD');
		$this->load->model('GiaoPhanMD');
		$this->load->model('GiaoHatMD');
		$this->load->model('MayNhapMD');
		$this->numrow=$this->config->item("numrow");
		$this->load->library('MailHandler');
		$this->load->library('GetAllHeader');
		$this->load->helper('string');
	//Do your magic here
	}
	///
	public function checkPermission($maGiaoXuRieng)
	{
		$GX=$this->GiaoXuMD->getGiaoXuByMaGiaoXuRieng($maGiaoXuRieng);
		if($GX!=null)
		{
			if($GX->lockSync!=1)
			{
				$this->GiaoXuMD->setLockSync($maGiaoXuRieng,1);
				echo 1;
				return;
			}
			echo 0;
			return;
		}
		echo -1;
	}
	///
	private $numrow;
	public function checkStatus($idgx=-1)
	{
		if ($idgx!=-1) {
			echo $this->GiaoXuMD->checkStatus($idgx)->status;
			return;
		}
		echo null;
		return;
	}
	public function getAllGX()
	{
		$rs=$this->GiaoXuMD->getGXjsonMD();
		if (count($rs)>0) {
			echo json_encode($rs);
			return;
		}
		else {
			echo -1;
			return;
		}
	}

	public function getGXByIdGH($idGH)
	{
		$rs=$this->GiaoXuMD->getGXjsonMDIDGH($idGH);
		if (count($rs)>0) {
			echo json_encode($rs);
			return;
		}
		else {
			echo -1;
			return;
		}
	}
	public function downloadImg($id=-1)
	{
		if ($id!=-1) {
			$file="filesIMG/".$id."/";
			$file.=$this->GiaoXuMD->downloadImgMD($id)->Hinh;
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
			else {
				echo -1;
				return;
			}		
		}
		else {
			echo -1;
			return;
		}
	}
	
	public function getPassWord()
	{
		foreach ($this->getallheader->getAllHeaders() as $name => $value) {
			if (strtolower($name)=="password") {
				if ($value=="admin") {
					return true;
				}
				else{
					return false;
				}
			}
		}
	}
	public function getGXByIDjson($id=-1)
	{
		if ($id!=-1) {
			if (!$this->getPassWord()) {
				return false;
			}
			$rs=$this->GiaoXuMD->getGXByIDjsonMD($id);
			if (count($rs)>0) {
				echo json_encode($rs);	
				return;		
			}
			else {
				echo -1;
				return;
			}
		}
	}
	public function getGiaoXuDaPheDuyet($maGiaoXuDoi)
	{
		$rs=$this->GiaoXuDoiMD->getGiaoXuDaPheDuyet($maGiaoXuDoi);
		if(count($rs)>0)
		{ 
			echo json_encode($rs);	
			return;	
		}
		else {
			echo -1;
			return;
		}
		echo -1;
		return;
	}
	public function getGiaoXuDoiByMaGiaoXuDoi($maGiaoXuDoi = -1){
		if ($maGiaoXuDoi!=-1) {
			if (!$this->getPassWord()) {
				return false;
			}
			$rs=$this->GiaoXuDoiMD->getGiaoXuDoiByMaGiaoXuDoi($maGiaoXuDoi);
			if (count($rs)>0) {
				echo json_encode($rs);	
				return;		
			}
			else {
				echo -1;
				return;
			}
		}
	}
	public function getGxById($id = -1){
		if ($id!=-1) {
			if (!$this->getPassWord()) {
				return false;
			}
			$rs=$this->GiaoXuMD->getGxById($id);
			if (count($rs)>0) {
				echo json_encode($rs);	
				return;		
			}
			else {
				echo -1;
				return;
			}
		}
	}
	public function getGiaoXusRequest(){
		if (!$this->getPassWord()) {
			return false;
		}
		$rs=$this->GiaoXuDoiMD->getGiaoXuDangDoi();
		if (count($rs)>0) {
			echo json_encode($rs);	
			return;		
		}
		else {
			echo -1;
			return;
		}
	}
	public function getGXjson()
	{
		if (!$this->getPassWord()) {
			return false;
		}
		$rs=$this->GiaoXuMD->getGXjsonMD();
		if (count($rs)>0) {
			echo json_encode($rs);	
			return;		
		}
		else {
			echo -1;
			return;
		}
	}
	public function sendMail()
	{
		if($_POST){
			
			$pathFile=explode("/",$this->input->post('path'));
			$filename = $pathFile[count($pathFile)-1];
			$attachment = $this->input->post('path');
			$to = $this->input->post('to');
			$subject = $this->input->post('subject');
			$body = $this->input->post('content');

			if ($this->mailhandler->SendMail($to,$subject,$body,$attachment)) {
				echo 1;
				return;
			} else {
				echo 0;
				return;

			}
		}
		else {
			echo -1;
			return;
		}

	}
	public function findContent()
	{
		if (isset($_POST["value"])&&!empty($_POST["value"])) {
			$rs=$this->GiaoXuMD->getFind($this->input->post('value'));
			if (count($rs)>0) {
				echo json_encode($rs);
				return;
			}
			else {
				echo -1;
				return;

			}
		}
		else
		{
			echo -1;
			return;
		}
	}
	public function updateGiaoXu()
	{
		$maGiaoXu=$this->input->post('txt-giaoxu-id');
		$tenGiaoXu=$this->input->post('txt-giaoxu-name');
		$diaChi=$this->input->post('txt-diachi');
		$dienThoai=$this->input->post('txt-sdt');
		$email=$this->input->post('txt-email');
		$website=$this->input->post('txt-website');
		$hinh=$this->input->post('txt-giaoxu-hinh');
		$ghiChu=$this->input->post('txt-ghichu');
		$maGiaoHat=$this->input->post('cb-giaohat-nameMa');

		$result = $this->GiaoXuMD->update($maGiaoXu,$tenGiaoXu,$diaChi,$dienThoai,$email,$website,$hinh,$ghiChu,$maGiaoHat);
			if($result >= 0){
				$json = json_encode(array('success'=>'success'));
				die($json);
			}
			die(json_encode(array('success'=>'error')));
	}
	public function denyGiaoXu()
	{
		if(isset($_POST) && count($_POST) > 0){
			$maGiaoXuDoi=$this->input->post('txt-giaoxu-id');
			$email=$this->input->post('txt-email');
			
			$result=$this->GiaoXuDoiMD->updateDenyGiaoXuDoiMD($maGiaoXuDoi);
				if($result>0)
				{
					//sendmail cho người dùng biết
					if($email!="")
					{
						$to=$email;
						$subject="Từ chối giáo xứ yêu cầu";
						$body="Hiện tại giáo xứ của bạn đã bị hệ thống từ chối.Vui lòng liên hệ quản trị viên qlgx.net để được hỗ trợ!</br>Xin cảm ơn!";
						$this->mailhandler->SendMail($to,$subject,$body);
					}
					$json = json_encode(array('success'=>'success'));
					die($json);
				}
			die(json_encode(array('success'=>'error')));
		}
	}
	public function insertGiaoXu()
	{
		if(isset($_POST) && count($_POST) > 0){
			$maGiaoXuDoi=$this->input->post('txt-giaoxu-id');
			$tenGiaoXu=$this->input->post('txt-giaoxu-name');
			$diaChi=$this->input->post('txt-diachi');
			$dienThoai=$this->input->post('txt-sdt');
			$email=$this->input->post('txt-email');
			$website=$this->input->post('txt-website');
			$hinh=$this->input->post('txt-giaoxu-hinh');
			$ghiChu=$this->input->post('txt-ghichu');
			$maGiaoHat=$this->input->post('cb-giaohat-nameMa');
			$tenGiaoHat=$this->input->post('cb-giaohat-nameTen');
			$status=1;
			$tenGiaoPhan=$this->input->post('cb-giaophan-nameTen');
			$maGiaoPhan=$this->input->post('cb-giaophan-nameMa');
			
			if($maGiaoPhan==0)
			{
				$tenGiaoPhan= trim(substr( $tenGiaoPhan, 0, strlen($tenGiaoPhan)-13));
				//add Giáo phân và update mã giáo phận
				$maGiaoPhan=$this->GiaoPhanMD->insertMD($tenGiaoPhan,"",1);
			}

			if($maGiaoHat==0)
			{
				if($maGiaoPhan==0)
				{
					die(json_encode(array('success'=>'error')));
				}
				$tenGiaoHat=trim(substr( $tenGiaoHat, 0, strlen($tenGiaoHat)-13));
				//add Giáo Hạt và update mã giáo hạt
				$maGiaoHat=$this->GiaoHatMD->insertMD($maGiaoPhan,$tenGiaoHat,"",1);
			}
			$maGiaoXuRieng=random_string('alpha', 16);
			$maGiaoXu = $this->GiaoXuMD->insertMD($tenGiaoXu,$diaChi,$dienThoai,$email,$website,$hinh,$ghiChu,$maGiaoHat,$status,$maGiaoXuRieng);
			if($maGiaoXu > 0){
				$result=$this->GiaoXuDoiMD->updateGiaoXuDoiMD($maGiaoXuDoi,$tenGiaoPhan,$maGiaoPhan,$tenGiaoHat,$maGiaoHat,$tenGiaoXu,$maGiaoXuRieng,$diaChi,$dienThoai,$email,$website,$hinh,$ghiChu);
				//sendmail cho người dùng biết
				if($email!="")
				{
					$to=$email;
					$subject="Chấp nhận giáo xứ yêu cầu";
					$body="Hiện tại giáo xứ <b>".$tenGiaoXu."</b> đã được hệ thống chấp nhận.Vui lòng khởi động ứng dụng qlgx để tải dữ liệu!</br>Xin cảm ơn!";
					$this->mailhandler->SendMail($to,$subject,$body);
				}
				$json = json_encode(array('success'=>'success'));
				die($json);
			}
			die(json_encode(array('success'=>'error')));
		}
	}
	public function insertGiaoXuDoi()
	{
		if (isset($_POST)&&count($_POST)>0) {
			$maGiaoXuDoi=array();
			$maGiaoXuDoi["MaGiaoXuDoi"]="error";
			if (isset($_POST["GiaoXu"])) {
				$maGXD=random_string('alpha', 16);
				$rs=$this->GiaoXuDoiMD->insertGiaoXuDoiMD($maGXD,
					$this->input->post('GiaoXuTenGiaoPhan'),
					$this->input->post('GiaoXuMaGiaoPhanRieng'),
					$this->input->post('GiaoXuTenGiaoHat'),
					$this->input->post('GiaoXuMaGiaoHatRieng'),
					$this->input->post('GiaoXuTenGiaoXu'),
					$this->input->post('GiaoXuDiaChi'),
					$this->input->post('GiaoXuDienThoai'),
					$this->input->post('GiaoXuEmail'),
					$this->input->post('GiaoXuWebsite'),
					$this->input->post('GiaoXuHinh'),
					$this->input->post('GiaoXuGhiChu'));
				if ($rs>0) {
					$maGiaoXuDoi["MaGiaoXuDoi"]=$maGXD;
				}
				
			}
			if ($maGiaoXuDoi["MaGiaoXuDoi"]!="error") {
				echo json_encode($maGiaoXuDoi);
				$to=$this->config->item("email");
				$subject="Giáo xứ yêu cầu";
				$body="Giáo xứ <b>".$this->input->post('GiaoXuTenGiaoXu')."</b> đã gửi yêu cầu lên hệ thống.Vui lòng <a href=\"".$this->config->item("urlDashboard")."\">truy cập</a> để xem";
				$this->mailhandler->SendMail($to,$subject,$body);
				return;
			}
			else {
				echo json_encode($maGiaoXuDoi);
				return;
			}
		}
		echo json_encode($maGiaoXuDoi);
		return;
	}
	//hiện tại k dùng
	public function insertInfo()
	{
		if(isset($_POST) && count($_POST) > 0){
			if($this->input->post('hidden-giaophan') == 0){
				// update status to 1
				$rs = $this->GiaoPhanMD->updateStatus($this->input->post('cb-giaophan-name'));
				if(!$rs){
					return json_encode(array('success'=>'error'));
				}
			}
			if($this->input->post('hidden-giaohat') == 0){
				//update status to 1
				$rs = $this->GiaoHatMD->updateStatus($this->input->post('cb-giaohat-name'));
				if(!$rs){
					return json_encode(array('success'=>'error'));
				}
			}
			$id=$this->input->post('txt-giaoxu-id');
			$name=$this->input->post('txt-giaoxu-name');
			$address = $this->input->post('txt-diachi');
			$phone = $this->input->post('txt-sdt');
			$email = $this->input->post('txt-email');
			$website = $this->input->post('txt-website');
			$img = '';
			$note = '';
			$magiaohat = $this->input->post('cb-giaohat-name');
			$result = $this->GiaoXuMD->update($id,$name,$address,$phone,$email,$website,$img,$note,$magiaohat);
			if($result >= 0){
				$json = json_encode(array('success'=>'success'));
				die($json);
			}
			die(json_encode(array('success'=>'error')));
			//insert giao xu
		}

	}
	
	

	public function insert()
	{
		if (isset($_POST)&&count($_POST)>0) {
			
			$allID=array();
			//insert Giao Phan
			if (isset($_POST["GiaoPhan"])) {
				if($this->input->post('GiaoPhanMaGiaoPhanRieng')!="")
				{
					$allID["IDGP"]=$this->input->post('GiaoPhanMaGiaoPhanRieng');
				}
				else{
					$rs=$this->GiaoPhanMD->insertMD(
						$this->input->post('GiaoPhanTenGiaoPhan'),
						$this->input->post('GiaoPhanGhiChu'));
					if ($rs>0) {
						$allID["IDGP"]=$rs;
					}
					else{
						echo -1;
						return;
					}
				}
			}
			else {
				//$allID["IDGP"]=$this->input->post('GiaoPhanId');
				$allID["IDGP"]="-1";
			}
			//insert giao hat
			if (isset($_POST["GiaoHat"])) {
				if($this->input->post('GiaoHatMaGiaoHatRieng')!="")
				{
					$allID["IDGH"]=$this->input->post('GiaoHatMaGiaoHatRieng');
				}else{
					$rs=$this->GiaoHatMD->insertMD(
						$allID["IDGP"],
						$this->input->post('GiaoHatTenGiaoHat'),
						$this->input->post('GiaoHatGhiChu'));
					if ($rs>0) {
						$allID["IDGH"]=$rs;
					}	
					else{
						echo -1;
						return;
					}
				}
			}
			else {
				//$allID["IDGH"]=$this->input->post('GiaoHatId');	
				$allID["IDGH"]="-1";	
			}
			if (isset($_POST["GiaoXu"])) {
				$rs=$this->GiaoXuMD->insertMD(
					$allID["IDGH"],
					$this->input->post('GiaoXuTenGiaoXu'),
					$this->input->post('GiaoXuDiaChi'),
					$this->input->post('GiaoXuDienThoai'),
					$this->input->post('GiaoXuEmail'),
					$this->input->post('GiaoXuWebsite'),
					$this->input->post('GiaoXuHinh'),
					$this->input->post('GiaoXuGhiChu'));
				if ($rs>0) {
					$allID["IDGX"]=$rs;
				}
				
			}
			if (count($allID)==3) {
				echo json_encode($allID);
				return;
			}
			else {
				echo -1;
				return;
			}
		}
		echo -1;
		return;
	}
	public function checkMaGiaoXuRieng($id=-1)
	{
		if ($id!=-1) {
			$rs=$this->GiaoXuMD->checkMaGiaoXuRiengMD($id);
			if (count($rs)>0) {
				echo 1;
			}else {
				echo 0;
			}
		}
		else {
			echo -1;
		}
	}
	public function configPagi($numRow,$totalRow,$url,$option)
	{
		$this->load->library('pagination');
		$config['base_url'] = $url;
		$config['total_rows'] = $totalRow;
		$config['per_page'] = $numRow;
		$config['uri_segment'] = 3;
		$config['num_links'] = 3;
		$config['full_tag_open']   = '<div class="pagging text-center" data-option="'.$option.'"><nav><ul class="pagination">';
		$config['full_tag_close']   = '</ul></nav></div>';
		$config['num_tag_open']   = '<li class="page-item"><span class="page-link">';
		$config['num_tag_close']   = '</span></li>';
		$config['cur_tag_open']   = '<li class="page-item active"><span class="page-link">';
		$config['cur_tag_close']   = '<span class="sr-only">(current)</span></span></li>';
		$config['next_tag_open']   = '<li class="page-item"><span class="page-link">';
		$config['next_tagl_close']   = '<span aria-hidden="true">&raquo;</span></span></li>';
		$config['prev_tag_open']   = '<li class="page-item"><span class="page-link">';
		$config['prev_tagl_close']   = '</span></li>';
		$config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
		$config['first_tagl_close'] = '</span></li>';
		$config['last_tag_open']   = '<li class="page-item"><span class="page-link">';
		$config['last_tagl_close']   = '</span></li>';
		$this->pagination->initialize($config);
	}
	public function index($offset=0,$option=0)
	{
		$totalRow=$this->GiaoXuMD->countRow();
		$numPage=$this->config->item('numrow');
		$rs=$this->GiaoXuMD->getPhanTrang($numPage,$offset);
			$this->configPagi($numPage,$totalRow,base_url().'GiaoXuCL/index',0);
		$data["pagi"]=$this->pagination->create_links();
		if (count($rs)>=0) {
			if ($option==0) {
				$data["data"]=$rs;
				$data["subview"]="GiaoXu/index";
				$this->load->view('Main', $data);
			}
			else {
				$data["data"]=$rs;
				echo json_encode($data);
				return;
			}
		}else {
			echo -1;
			return;
		}
	}
	//uploadAvatar lên server
	public function uploadAvatar($maGXRieng)
	{
		$this->uploadFileFromClient("filesIMG",$maGXRieng);
	}
	//Upload file from client
	public function uploadFileFromClient($pathfolder,$maGiaoXuRieng="")
	{
		if (isset($_FILES) && count($_FILES) > 0) {
			if($pathfolder=="")
			{
				echo -1;
				return;
			}
			//kiem tra folder có tồn tại không
			$serverPath=$pathfolder."/".$maGiaoXuRieng;
			if (!is_dir($serverPath)) {
				//Tạo folder 
				if(!mkdir($serverPath,0777,TRUE))
				{
					echo -1;
					return;
				}
			}
			if (move_uploaded_file($_FILES['file']["tmp_name"],$serverPath.'/'.$_FILES['file']["name"])) {
				echo date("Y-m-d H:i:s");
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
	public function insertMayNhap($maGiaoXuRieng)
	{
		if(isset($_POST)&&count($_POST)>0)
		{
			$id=array();
			$rscount=$this->MayNhapMD->getMayNhap($maGiaoXuRieng,$this->input->post("MaDinhDanh"),$this->input->post("TenMay"));
			if(count($rscount)>0)
			{
				$id["ID"]=$rscount[0]->ID;
				echo json_encode($id);
				return;
			}
			else{
				$rs=$this->MayNhapMD->insertMayNhapMD(
					$this->input->post("MaDinhDanh"),
					$this->input->post("TenMay"),
					$maGiaoXuRieng
				);
				if($rs>0)
				$id["ID"]=$rs;
				echo json_encode($id);
				return;
			}
			
		}else
		{
			echo -1;
			return;
		}
	}
	public function getAllMayNhap($idgx)
	{
		$rs= $this->MayNhapMD->getAllMayNhapByMaGiaoXuRieng($idgx);
		echo json_encode($rs);
		return;
	}

	public function sendMailYeuCauNhanFile()
	{
		$rs=0;
		if(isset($_POST)&&count($_POST)>0)
		{
			$to=$this->config->item('email');
			$subject="[".$this->input->post('TenGiaoXu')."] Yêu cầu nhận dữ liệu từ máy nhập khác!";

			$body="Giáo xứ <b>".$this->input->post('TenGiaoXu')."</b> ";
			$body.="có máy nhập <b>".$this->input->post('TenMay')."</b> ";
			$body.="(Email : ".$this->input->post('Email').") ";
			$body.="đã gửi yêu cầu lên hệ thống xin nhận file database từ máy nhập <b>".$this->input->post('TenMayNhap')."</b>.<br/>";
			if($this->input->post('GhiChu')!="")
			{
				$body.="Ghi chú: <br/>";
				$body.="<b>".$this->input->post('GhiChu')."</b> <br/>";
			}
			$body.="Vui lòng <a href=\"".$this->config->item('urlDashboard')."\">truy cập</a> để xem";
			$this->mailhandler->SendMail($to,$subject,$body);
			$rs=1;
		}
		echo json_encode($rs);
		return;
	}
	//moveGiaoXuDoi
	public function updateGiaoXuDoiMove()
	{
		$maGiaoXuDoi=$this->input->post('MaGiaoXuDoi');
		$tenGiaoPhan=$this->input->post('TenGiaoPhan');
		$maGiaoPhanRieng=$this->input->post('MaGiaoPhanRieng');
		$tenGiaohat=$this->input->post('TenGiaoHat');
		$maGiaoHatRieng=$this->input->post('MaGiaoHatRieng');
		$maGiaoXuRieng=$this->input->post('MaGiaoXuRieng');
		$tenGiaoXu=$this->input->post('TenGiaoXu');
		$email=$this->input->post('Email');

		$result = $this->GiaoXuDoiMD->updateGiaoXuDoiMove($maGiaoXuDoi,$tenGiaoPhan,$maGiaoPhanRieng,$tenGiaohat,$maGiaoHatRieng,$maGiaoXuRieng);
			if($result >= 0){
				$json = json_encode(array('success'=>'success'));
				if($email!="")
				{
					$to=$email;
					$subject="Chấp nhận giáo xứ yêu cầu";
					$body="Hiện tại giáo xứ <b>".$tenGiaoXu."</b> đã được hệ thống chấp nhận.Vui lòng khởi động ứng dụng qlgx để tải dữ liệu!</br>Xin cảm ơn!";
					$this->mailhandler->SendMail($to,$subject,$body);
				}
				die($json);
			}
			die(json_encode(array('success'=>'error')));
	}
function a()
{
	$mya=$this->getallheader->getAllHeaders();
	$myHeader=$this->input->request_headers();
}
}

/* End of file GiaoXuCL.php */
/* Location: ./application/controllers/GiaoXuCL.php */
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class GiaoXuCL extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('GiaoXuMD');
		$this->load->model('GiaoPhanMD');
		$this->load->model('GiaoHatMD');
		$this->numrow=$this->config->item("numrow");
	//Do your magic here
	}
	private $numrow;
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
			$file="filesIMG/";
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
	public function testMail()
	{
		$to = 'gia010203@gmail.com';  
		$subject = 'Test email'; 
		
		$message = "Hello World!\n\nThis is my first mail.";
		
		$headers = "From: webmaster@example.com\r\nReply-To: webmaster@example.com";
		
		$mail_sent = mail( $to, $subject, $message, $headers );
		
		echo $mail_sent ? "Mail sent2" : "Mail failed2";
		
	}
	public function getPassWord()
	{
		foreach (getallheaders() as $name => $value) {
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
		$rs=$this->GiaoXuMD->getGxsRequest();
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

		if ($_POST) {
			
			$pathFile=explode("/",$this->input->post('path'));
			$filename = $pathFile[count($pathFile)-1];
			$file = $this->input->post('path');

			$mailto = $this->input->post('to');
			$subject = $this->input->post('subject');
			$message = $this->input->post('content');

			$content = file_get_contents($file);
			$content = chunk_split(base64_encode($content));
			$separator = md5(time());

			$eol = "\r\n";

			$headers = "From: QLGX.net" . $eol;
			$headers .= "MIME-Version: 1.0" . $eol;
			$headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
			$headers .= "Content-Transfer-Encoding: 7bit" . $eol;
			$headers .= "This is a MIME encoded message." . $eol;
			$body = "--" . $separator . $eol;
			$body .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
			$body .= "Content-Transfer-Encoding: 8bit" . $eol;
			$body .= $message . $eol;
			$body .= "--" . $separator . $eol;
			$body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
			$body .= "Content-Transfer-Encoding: base64" . $eol;
			$body .= "Content-Disposition: attachment" . $eol;
			$body .= $content . $eol;
			$body .= "--" . $separator . "--";
			if (mail($mailto, $subject, $body, $headers)) {
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
			else {
				$allID["IDGP"]=$this->input->post('GiaoPhanId');
			}
			//insert giao hat
			if (isset($_POST["GiaoHat"])) {
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
			else {
				$allID["IDGH"]=$this->input->post('GiaoHatId');	
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
		if (count($rs)>0) {
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

}

/* End of file GiaoXuCL.php */
/* Location: ./application/controllers/GiaoXuCL.php */
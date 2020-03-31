<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SynToClientCL extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('BiTichChiTietMD');
		$this->load->model('ChiTietLopGiaoLyMD');
		$this->load->model('ChuyenXuMD');
		$this->load->model('DotBiTichMD');
		$this->load->model('GiaoDanHonPhoiMD');

		$this->load->model('GiaoLyVienMD');
		$this->load->model('LinhMucMD');
		$this->load->model('HonPhoiMD');
		$this->load->model('KhoiGiaoLyMD');
		$this->load->model('LopGiaoLyMD');
		$this->load->model('RaoHonPhoiMD');
		$this->load->model('TanHienMD');
		$this->load->model('GiaDinhMD');
		$this->load->model('GiaoDanMD');
		$this->load->model('GiaoHoMD');
		$this->load->model('ThanhVienGiaDinhMD');
		$this->load->model('CauHinhMD');
	}
	private function parseDateTime($timeClient)
	{
		$temp=explode('-');
		$date=$temp[0]."-".$temp[1]."-".$temp[2]." ".$temp[3].":".$temp[4].":".$temp[5];
		return $date;
	}
	public function createrFileSyn($maGiaoXu)//)
	{
		// if (!isset($timeClient)||$timeClient==null) {
		// 	$timeClient=1;
		// }
		// else{
		// 	$timeClient=$this->parseDateTime($timeClient);
		// }
		$data['BiTichChiTiet']=$this->BiTichChiTietMD->getAllActive($maGiaoXu);
		$data['CauHinh']=$this->CauHinhMD->getAllActive($maGiaoXu);
		$data['ChiTietLopGiaoLy']=$this->ChiTietLopGiaoLyMD->getAllActive($maGiaoXu);
		$data['ChuyenXu']=$this->ChuyenXuMD->getAllActive($maGiaoXu);
		$data['DotBiTich']=$this->DotBiTichMD->getAllActive($maGiaoXu);
		$data['GiaDinh']=$this->GiaDinhMD->getAllActive($maGiaoXu);
		$data['GiaoDanHonPhoi']=$this->GiaoDanHonPhoiMD->getAllActive($maGiaoXu);
		$data['GiaoHo']=$this->GiaoHoMD->getAllActive($maGiaoXu);
		$data['GiaoDan']=$this->GiaoDanMD->getAllActive($maGiaoXu);
		$data['GiaoLyVien']=$this->GiaoLyVienMD->getAllActive($maGiaoXu);
		$data['HonPhoi']=$this->HonPhoiMD->getAllActive($maGiaoXu);
		$data['KhoiGiaoLy']=$this->KhoiGiaoLyMD->getAllActive($maGiaoXu);
		$data['LinhMuc']=$this->LinhMucMD->getAllActive($maGiaoXu);
		$data['LopGiaoLy']=$this->LopGiaoLyMD->getAllActive($maGiaoXu);
		$data['RaoHonPhoi']=$this->RaoHonPhoiMD->getAllActive($maGiaoXu);
		$data['TanHien']=$this->TanHienMD->getAllActive($maGiaoXu);
		$data['ThanhVienGiaDinh']=$this->ThanhVienGiaDinhMD->getAllActive($maGiaoXu);
	

		foreach ($data as $key=>$table) {

			$dirtemp=$this->config->item("data_dir").'/CsvToClient/'.$maGiaoXu;
			if(!is_dir($dirtemp)) {
				$check= mkdir($dirtemp,0777,TRUE);
			}
			
			$fp = fopen($dirtemp.'\\'.$key.'.csv', 'w');

			
			foreach ($table['field'] as $field) {
					fwrite($fp,$field);
					fwrite($fp,";");
			}
			fwrite($fp,"\n");
			foreach ($table['data'] as $object) {
				foreach ($object as $data) {
					fwrite($fp,$data);
					fwrite($fp,";");
				}
				fwrite($fp,"\n");
			}
			
			
			
		}
		$tempGloble=glob($dirtemp."\*.csv");
		if (count($tempGloble)>0) {
			$zip = new ZipArchive(); 
			$check=$zip->open($dirtemp."/".$maGiaoXu.".zip", ZipArchive::CREATE); 
			$files = array();
			foreach ($tempGloble as  $file) {
				$split=explode ("\\" , $file);
				$zip->addFile($file,$split[count($split)-1]);
			}
			$zip->close();
			echo 1;
			return;
		}
		echo -1;
	}
}

/* End of file SynToClientCL.php */
/* Location: ./application/controllers/SynToClientCL.php */
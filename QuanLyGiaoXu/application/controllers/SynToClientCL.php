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
		$temp=explode('-',$timeClient);
		$date=$temp[0]."-".$temp[1]."-".$temp[2]." ".$temp[3].":".$temp[4].":".$temp[5];
		return $date;
	}
	public function createrFileSyn($maGiaoXu,$timeClient)
	{
		if (!isset($timeClient)||$timeClient==null) {
			$timeClient=1;
		}
		else{
			$timeClient=$this->parseDateTime($timeClient);
		}
		$data['BiTichChiTiet']=$this->BiTichChiTietMD->getAllActive($maGiaoXu,$timeClient);
		$data['CauHinh']=$this->CauHinhMD->getAllActive($maGiaoXu,$timeClient);
		$data['ChiTietLopGiaoLy']=$this->ChiTietLopGiaoLyMD->getAllActive($maGiaoXu,$timeClient);
		$data['ChuyenXu']=$this->ChuyenXuMD->getAllActive($maGiaoXu,$timeClient);
		$data['DotBiTich']=$this->DotBiTichMD->getAllActive($maGiaoXu,$timeClient);
		$data['GiaDinh']=$this->GiaDinhMD->getAllActive($maGiaoXu,$timeClient);
		$data['GiaoDanHonPhoi']=$this->GiaoDanHonPhoiMD->getAllActive($maGiaoXu,$timeClient);
		$data['GiaoHo']=$this->GiaoHoMD->getAllActive($maGiaoXu,$timeClient);
		$data['GiaoDan']=$this->GiaoDanMD->getAllActive($maGiaoXu,$timeClient);
		$data['GiaoLyVien']=$this->GiaoLyVienMD->getAllActive($maGiaoXu,$timeClient);
		$data['HonPhoi']=$this->HonPhoiMD->getAllActive($maGiaoXu,$timeClient);
		$data['KhoiGiaoLy']=$this->KhoiGiaoLyMD->getAllActive($maGiaoXu,$timeClient);
		$data['LinhMuc']=$this->LinhMucMD->getAllActive($maGiaoXu,$timeClient);
		$data['LopGiaoLy']=$this->LopGiaoLyMD->getAllActive($maGiaoXu,$timeClient);
		$data['RaoHonPhoi']=$this->RaoHonPhoiMD->getAllActive($maGiaoXu,$timeClient);
		$data['TanHien']=$this->TanHienMD->getAllActive($maGiaoXu,$timeClient);
		$data['ThanhVienGiaDinh']=$this->ThanhVienGiaDinhMD->getAllActive($maGiaoXu,$timeClient);
	

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
			fclose($fp);
			
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
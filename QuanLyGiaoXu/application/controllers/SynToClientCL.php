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
		$this->load->model('GiaoHatMD');
		$this->load->model('GiaoLyVienMD');
		$this->load->model('GiaoPhanMD');
		$this->load->model('GiaoXuMD');
		$this->load->model('HonPhoiMD');
		$this->load->model('KhoiGiaoLyMD');
		$this->load->model('LopGiaoLyMD');
		$this->load->model('RaoHonPhoiMD');
		$this->load->model('TanHienMD');
		$this->load->model('GiaDinhMD');
		$this->load->model('GiaoDanMD');
		$this->load->model('GiaoHoMD');
		$this->load->model('ThanhVienGiaDinhMD');
	}
	public function createrFileSyn($maGiaoXu)
	{
		$data['GiaDinh']=$this->GiaDinhMD->getAllActive($maGiaoXu);
		$data['GiaoDan']=$this->GiaoDanMD->getAllActive($maGiaoXu);
		$data['GiaoHo']=$this->GiaoHoMD->getAllActive($maGiaoXu);
		$data['ThanhVienGiaDinh']=$this->ThanhVienGiaDinhMD->getAllActive($maGiaoXu);

		foreach ($data as $key=>$table) {

			$path='../data/CsvToClient/'.$maGiaoXu;
			if(!is_dir($path)) {
				$check= mkdir($path,0777,TRUE);
			}
			$dirtemp=realpath ($path);
			$fp = fopen($dirtemp.'\\'.$key.'.csv', 'w');

			
			foreach ($table['field'] as $field) {
				if ($field!="DeleteSV") {
					fwrite($fp,$field);
					fwrite($fp,";");
				}
			}
			fwrite($fp,"\n");
			foreach ($table['data'] as $object) {
				unset($object->DeleteSV);
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
			echo $this->config->item("data_dir")."/CsvToClient/".$maGiaoXu."/".$maGiaoXu.".zip";
			return;
		}
		echo -1;
	}
}

/* End of file SynToClientCL.php */
/* Location: ./application/controllers/SynToClientCL.php */
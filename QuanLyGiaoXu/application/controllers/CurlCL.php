<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include 'Curl/Curl.php';
include 'Curl/CaseInsensitiveArray.php';
include 'Curl/MultiCurl.php';
include	'Curl/Url.php';
include	'Curl/StringUtil.php';
include	'Curl/Decoder.php';
include	'Curl/ArrayUtil.php';
use \Curl\Curl;
class CurlCL extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	//Do your magic here
		
	}
	public function index()
	{
		
	}
	public function testVideo()
	{
		file_put_contents("files/Tmpfile.mp4", fopen("http://zmp3-mp3-mv1-te-vnso-zn-5.zadn.vn/2ab355506915804bd904/2608416747544105165?authen=exp=1534484684~acl=/2ab355506915804bd904/*~hmac=40095afba64d66316a0a9d4bc7e2b567", 'r'));

	}
	public function regedit()
	{
		$data="data-xml=&quot;/media/get-source?type=audio&amp;key=LGxHTZHNWZAJQuxtHyFnZHtLWBzlmpnuW&quot;";
		$pattern='/data-xml=&quot;.+&quot;/';
		if (preg_match_all($pattern, $data,$math) ){
			var_dump($math);
			echo "OK";
		}
		else {
			echo 'NO';
		}
	}
	public function test($url)
	{
		$html=$this->curlPHP("https://mp3.zing.vn/xhr/media/get-source?type=video&key=kHxnTZmaSEZWdkZTnyDmLmykWdSSLlizL");
		return $html;
	}
	public function getLinkMp3()
	{
		if (isset($_GET)) {
			$html=$this->curlPHP($_GET["url"]);
			$pattern='/data-xml=&quot;(.+)&quot; data-id/';
			if (preg_match_all($pattern, $html,$math) ){
				$url="https://mp3.zing.vn/xhr".$math[1][0];
				$url=html_entity_decode($url);
				$html=$this->curlPHP($url);
				$html=$this->dejson($html);
				$source=$html->data->source->mp4;
				echo $source->{"360p"};
			}
			else {
				echo 'NO';
			}
		}
	}
	public function dejson($html)
	{
		$string=str_replace("&quot;","\"",$html);
		$string=json_decode($string);
		return $string;
	}
	public function getZingMp3()
	{

		$html=$this->curlPHP("https://mp3.zing.vn/bai-hat/Ve--Acoustic-Version--Truc-Nhan/ZW6EC7B6.html");
		echo htmlspecialchars($html);
	}
	public function curlClass($url,&$html)
	{
		$curl=new Curl();
		//$curl->setOpts(CURLOPT_ENCODING,'gzip');
		$curl->get($url);
		if ($curl->error) {
			$html=false;
		} else {
			$html=htmlspecialchars($curl->response) ;
		}
		$curl->close();
		
	}
	public function testdowload()
	{
		$temp=$this->downloadFile("https://nhathoconggiao.com/hinh-nha-tho/thumbnails/b380b9e23f20d6cd268309161278d1e0.jpg",'files/abc.jpg');
	}
	public function downloadFile($url,$path)
	{
		$f=fopen($path,"w");
		$ch=curl_init($url);
		curl_setopt($ch,CURLOPT_FILE,$f);
		curl_setopt($ch,CURLOPT_TIMEOUT,28800);
		curl_exec($ch);
		curl_close($ch);
		$e=curl_errno($ch);
		fclose($f);
		return $e;
	}
	public function curlPHP($url)
	{
		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36");
		curl_setopt($ch,CURLOPT_REFERER,"https://www.google.com.vn/");
		curl_setopt($ch,CURLOPT_ENCODING,'');
		curl_setopt($ch,CURLOPT_TIMEOUT,50);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,50);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
		curl_setopt($ch,CURLOPT_MAXREDIRS,5);

		//thong qua proxy
		//curl_setopt($ch,CURLOPT_PROXY,"89.225.253.67:53281");
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
		$rs=curl_exec($ch);
		curl_close($ch);
		return htmlspecialchars($rs);
	}

}

/* End of file CurlCL.php */
/* Location: ./application/controllers/CurlCL.php */
<?php
/**
 	Web-Service Development with PHP's courses
 
*/

include_once("rest/Function.php");
include_once("soap/SoapDataExam.php");

class ServiceExam {
	
	private $soap;
	private $rest;
	function __construct() {
		
		if(null == $this->soap)
			$this->soap = new SoapDataExam();
		
		if(null == $this->rest)
		$this->rest = new RestExam();
		
	}
	
	/**
		this is test function
		@path /info
	*/
	public function getInfo(){
		echo "hello world!";
	}
	
	/**
	 	this is get all gifs
		working with check content-type for a response
		@path soap/gif/all
		@path rest/gif/all
	*/
	public function getAllGif(){
		$data;
		if(false !== strpos($_SERVER['HTTP_ACCEPT'], 'text/html')) {
			header('Content-Type: text/html');
			$data = $this->soap->getAllGif();
			print_r($data);
		} 
		else if(false !== strpos($_SERVER['HTTP_ACCEPT'], 'text/xml')) {
			$data = $this->soap->getAllGif();
			$outXml = simplexml_load_string('<?xml version="1.0" ?><data />');
			foreach($data as $key => $value) {
				$outXml->addChild($key, $value);
			}
			header('Content-Type: text/xml');
			echo $outXml->asXML();
		}
		else {
			header('Content-Type: application/json');
			$data = $this->rest->getAllGif();
			echo json_encode($data);
		}
	}

}
?>
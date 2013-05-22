<?php
/*
 	Web-Service Development with PHP's courses
 
*/
class RestExam{
	
	/**
		get random gif
		return random of 1 value of gifs
	*/
	public function getGif() {
		
		$gifs = array(
		"1 Coca cola",
		"1 7-UP",
		"1 Cup of coffe",
		"2 Cup of Tea"
		);
		
		$gif = $gifs[array_rand($gifs)];
		
		return json_encode($gif);
	}
	
	/**
		get all gifs by json
		return all value of gifs
	*/
	public function getAllGif() {
		
		$gifs = array(
			array("name" => "Coca cola","total" => "1"),
			array("name" => "7-UP","total" => "1"),
			array("name" => "Cup of coffe","total" => "2"),
			array("name" => "Cup of Tea","total" => "2")
		);
		
		return json_encode($gifs);
	}
}
?>
<?php
/**
 	Web-Service Development with PHP's courses
 
*/
class Utils{
	/**
		this is url parser function.
	*/
	public static $base_url="webservice.dev";
		
	public static function parseUri() {
		$home = "info";
		
		$nh = preg_replace('#^http[s]*://[^/]+/#i', '/', $base_url);
		$nh = preg_replace('#/[^/]+/\.\./#i', '/', $nh);
		$ru = trim(isset($_SERVER['REQUEST_URI']) ? urldecode($_SERVER['REQUEST_URI']) : '/');
		$ru = str_replace($nh, '', $ru);
		$ru = preg_split('#[\ \t]*[/]+[\ \t]*#i', $ru, -1, PREG_SPLIT_NO_EMPTY);
		$ru = array_map('trim', $ru);
		
		$result = array();
		if (!count($ru))
		{
			$result['act'] = $home;
			$result['type'] = "";
			$result['sub'] = "";
		}
		else{
			$result['type'] = $ru[0];
			$result['act'] = $ru[1];
			$result['sub'] = $ru[2];
		}
		return $result;
		
	}
	
	/**
		retrieve a XML node's value
	*/
	public static function getValue($xPath, $path)
	{
		if (empty($xPath)){
			return "";
		}
		
		$nodes = $xPath->query($path);
		if (empty($nodes) || $nodes->length == 0){
			return "";
		}
			
		return $nodes->item(0)->nodeValue;
	}
	
	/**
		retrieve a XML node's atrribute value
	*/
	public static function getAttrib($xPath, $path, $attrName)
	{
		if (empty($xPath)){
			return "";
		}
		
		$nodes = $xPath->query($path);
		if (empty($nodes) || $nodes->length == 0){
			return "";
		}
			
		return $nodes->item(0)->getAttribute($attrName);
	}
}
?>
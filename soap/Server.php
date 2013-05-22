<?php
ProcessSoapRequest($_SERVER["HTTP_SOAPACTION"], $HTTP_RAW_POST_DATA);

function ProcessSoapRequest($soapAction, $rawPostData)
{
	require_once("../inc.php");
	require_once("SoapDataExam.php");

	$dom = new DOMDocument("1.0");
	$dom->loadXML($rawPostData, LIBXML_NOERROR | LIBXML_NOWARNING);
	
	$xPath = new DOMXpath($dom);
	
	$dataRepo = new SoapDataExam();
	$data;
	
	switch ($soapAction)
	{
		case "all": 
			$datalist = $dataRepo->getAllGif();
			foreach($datalist as $key => $value){
				$data .= "      	<item>{$value['name']}</item>\r\n";	
			}
			break;
		case "rand":  
			$data = $dataRepo->getGif();
			break;
			
		default: writeSoapError("INVALID_ACTION", "Invalid Action: '$soapAction'");
	}
	
	echo "<?xml version=\"1.0\"?>\r\n".
	     "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\">\r\n".
	     "   <soapenv:Header/>\r\n".
	     "   <soapenv:Body>\r\n".
	     "      <Action>\r\n".
		 "         $soapAction\r\n".
		 "      </Action>\r\n".
	     "      <Data>\r\n".
		 "$data".
		 "      </Data>\r\n".
	     "   </soapenv:Body>\r\n".
	     "</soapenv:Envelope>\r\n";
	exit;
}

function writeSoapError($errCode, $errMessage)
{
	header("HTTP/1.1 500 Internal Server Error");
	echo "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\">\r\n".
	     "  <soapenv:Body>\r\n".
	     "    <soapenv:Fault>\r\n".
	     "      <faultcode>$errCode</faultcode>\r\n".
	     "      <faultstring>$errMessage</faultstring>\r\n".
	     "    </soapenv:Fault>\r\n".
	     "  </soapenv:Body>\r\n".
	     "</soapenv:Envelope>";
	exit;
}
?>
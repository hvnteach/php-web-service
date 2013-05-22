<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include_once("SoapDataExam.php");
function getGIFs($action) { 
  $data = new SoapDataExam();
  if($action=='all')
          return $data->getAllGif();
  elseif ($action='rand')
           return $data->getGif();
}

function getTestMessage($testMessage)
{
    return "Hello your text is:" . $testMessage;

}

$server = new SoapServer("gif.wsdl"); 
$server->addFunction(array("getGIFs", "getTestMessage")); 
$server->handle(); 


?>
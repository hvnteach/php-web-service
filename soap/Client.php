<?php

?>
<html>
<head>
<title>Soap WebService Client Demo</title>
</head>
<body>
<h1>Soap Webservice (Local testing)</h1>
<?php
// ----------- localhost Soap Webservice --------------

require_once("../inc.php");
require_once("WebService.php");

$SERVER_URL = "http://" . Utils::$base_url . "/soap/Server.php";

$service = new Webservice($SERVER_URL);

$action  = $_REQUEST["Action"];

$soap = "<?xml version=\"1.0\"?>
<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\">
  <soapenv:Header/>
  <soapenv:Body>
    <Action>
		$action
	</Action>
  </soapenv:Body>
</soapenv:Envelope>";
flush();
if ($_REQUEST["Debug"] == "on") $service->DEBUG = true;
$response = $service->sendRequest($soap, $action);

$XPath = $response["XPath"];

echo "<h3>Result:</h3><pre>";
echo "<b>Error</b>:   " .Utils::getValue ($XPath, "//soapenv:Body/soapenv:Fault/faultstring")."<br>";
echo "<b>Action</b>:  $action<br>";
echo "<b>Data</b>:  $data<br>";
echo "<b>Message</b>: " .Utils::getValue ($XPath, "//soapenv:Body/$answer/Data")."<br>";
echo "</pre>";

?>
</body>
</html>
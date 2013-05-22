<html>
<head>
<title>ConversionRate Webservice Client</title>

</head>
<body>
<h1>ConversionRate Webservice</h1>

<?php
$SERVER_URL = "http://www.webservicex.com/currencyconvertor.asmx";

require_once("../inc.php");
require_once("Webservice.php");

$params["FromCurrency"] =  $_REQUEST["FromCurrency"];
$params["ToCurrency"] =  $_REQUEST["ToCurrency"];
$method =  $_REQUEST["method"];
if("soap" !== $method){
	$SERVER_URL .= "/ConversionRate";
}
$service = new Webservice($SERVER_URL, $method, "utf-8");
if ($_REQUEST["Debug"] == "on") $service->DEBUG = true;

if("soap" == $method){
	$soap = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
	<soap:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\">
	  <soap:Body>
		<ConversionRate xmlns=\"http://www.webserviceX.NET/\">
		  <FromCurrency>{$params['FromCurrency']}</FromCurrency>
		  <ToCurrency>{$params['ToCurrency']}</ToCurrency>
		</ConversionRate>
	  </soap:Body>
	</soap:Envelope>";
	flush();
	$pesponse = $service->sendRequest($soap,"http://www.webserviceX.NET/ConversionRate");
}
else{
	$pesponse = $service->sendRequest($params,"http://www.webserviceX.NET/ConversionRate");
}

/* returns $Response["Body"]=
HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8
Content-Length: length

<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <ConversionRateResponse xmlns="http://www.webserviceX.NET/">
      <ConversionRateResult>double</ConversionRateResult>
    </ConversionRateResponse>
  </soap:Body>
</soap:Envelope>
*/ 

$xPath = $pesponse["XPath"];
if("soap" == $method){
echo "<h3>Result:</h3><pre>";
echo "<hr width=350 align=left>";
echo "<b>ConversionRateResult</b>:       " .Utils::getValue ($xPath, "//soap:Envelope"). "<br>";

echo "</pre>";
}
?>
</body>
</html>

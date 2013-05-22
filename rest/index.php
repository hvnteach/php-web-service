<?php
$url = "http://www.webservicex.com/currencyconvertor.asmx?WSDL";
$soap_action = "http://www.webservicex.com/currencyconvertor.asmx/ConversionRate";
$header     = new SoapHeader($soap_action, "APICredentials", null, false); 
$client     = new SoapClient($url, array("trace" => 1, "exception" => 0));
$result = $client->__soapCall(
							"ConversionRate", 
							array(
								"ConversionRate" => array(
														"FromCurrency" => "USD",
														"ToCurrency"    => "VND"
														) 
							),
							NULL,
							$header);  
// print the result 
echo "<pre>".print_r($result, true)."</pre>"; 

?>
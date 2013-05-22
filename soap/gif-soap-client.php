<?php  
  $client = new SoapClient("http://webservice.dev/soap/gif-soap-server.php?wsdl");
  $action = 'rand';
  $testMessage = "Hello World!";
  $response = $client->getGifs($action);
  $response2 = $client->getTestMessage($testMessage);

  echo $response;
  echo "<br /><br />";
  echo $response2;
?>
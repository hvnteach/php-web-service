<?php
class Webservice
{
	const TIME_OUT = 10;

	public  $DEBUG = false;
	private $charSet = "utf-8";
	private $method = "soap";
	private $userAgent = "PHP WebService Client";
	private $url;
	
	public function __construct($serverUrl,  $method = "soap", $charSet = "utf-8", $userAgent="")
	{
		// Avoids Notice when run on WAMP
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		
		if (empty($userAgent)) $userAgent = "PHP WebService Client";
		
		$this->url       = parse_url($serverUrl);
		$this->userAgent = $userAgent;
		$this->charSet = $charSet;
		$this->method = strtoupper($method);
		
		if (empty($this->url["path"])) $this->url["path"] = "/";
	}
	
	public function sendRequest($data, $soapAction="")
	{
		if (is_array($data))
		{
			$dispParams = "<table cellspacing=0 cellpadding=0>";
	        $params = array();
	        foreach ($data as $key => $value) 
	        {
	        	$encode = str_replace('%7E', '~', rawurlencode($value));
	            $params[] = "$key=$encode";
	            $dispParams .= "<tr><td>$key</td><td>&nbsp;=&nbsp;$value</td></tr>";
	        }
	        $dispParams .= "</table>";
	        $query = implode('&', $params);
		}
		else $dispParams = $data;
		switch ($this->method)
		{
			case "POST":
				$sendData  = "POST ".$this->url["path"]." HTTP/1.0\r\n";
				$sendData .= "Host: " . $this->url['host'] . "\r\n";
				$sendData .= "Content-Type: application/x-www-form-urlencoded; charset=".$this->charSet."\r\n";
				$sendData .= "Content-Length: " . strlen($query) . "\r\n";
				$sendData .= "User-Agent: " . $this->userAgent . "\r\n";
				$sendData .= "\r\n";
				$sendData .= $query;
				break;
	
			case "SOAP":
				$dom = new DOMDocument("1.0", $this->charSet);
				$dom->loadXML($data);
				$dom->formatOutput = true;
				$soap = $dom->saveXml();
			
				$sendData  = "POST ".$this->url["path"]." HTTP/1.0\r\n";
				$sendData .= "Host: " . $this->url['host'] . "\r\n";
				$sendData .= "Content-Type: text/xml; charset=".$this->charSet."\r\n";
				$sendData .= "Content-Length: " . strlen($soap) . "\r\n";
				$sendData .= "SOAPAction: $soapAction\r\n";
				$sendData .= "User-Agent: " . $this->userAgent . "\r\n";
				$sendData .= "\r\n";
				$sendData .= $soap;
				break;
			default:
				throw new Exception("Invalid Method: ".$this->method);	
		}
		if ($this->DEBUG)
		{
			echo "<h3>Url:</h3><pre>".$this->url['scheme']."://".$this->url['host'].$this->url['path']."</pre>";
			
			if (is_Array($data))
			{
				echo "<h3>Parameter:</h3><pre>$dispParams</pre>";
			}
			$html = $sendData;
			$html = str_replace("<", "&lt;", $html);
			$html = str_replace(">", "&gt;", $html);
			$html = trim($html);
			echo "<h3>Request:</h3><pre>$html</pre>";
			echo "<hr>";
			flush();
		}
        $port = array_key_exists('port',$this->url) ? $this->url['port'] : null;
		
		switch ($this->url['scheme']) 
        {
            case 'https':
                $scheme = 'ssl://';
                $port = ($port === null) ? 443 : $port;
                break;

            case 'http':
                $scheme = '';
                $port = ($port === null) ? 80 : $port;
                break;
                
            default:
            	echo ("Invalid protocol in: ".$this->serverUrl);
        }
        $socket = @fsockopen($scheme . $this->url['host'], $port, $ErrNo, $ErrStr, WebService::TIME_OUT);
		
        if (!$socket)
        	throw new Exception ("Unable to establish connection to host " . $this->url['host'] . " $ErrStr");
        fwrite($socket, $sendData);

        $response = "";
        while (!feof($socket)) 
        {
        	$response .= fgets($socket, 1000);
        }
        fclose($socket);

		// Between Header and ResponseBody there are two empty lines
        list($header, $responseBody) = explode("\r\n\r\n", $response, 2);
        
        $split = preg_split("/\r\n|\n|\r/", $header);
       
        // Decode the first line of the header: "HTTP/1.1 200 OK"
        list($protocol, $statusCode, $statusText) = explode(' ', trim(array_shift($split)), 3);
    	
    	$retArray["Status"] = $statusCode;
    	$retArray["Body"]   = $responseBody;
    	
    	try
    	{
			$dom = new DOMDocument("1.0", $this->charSet);
    	    $dom->loadXML($responseBody);
    	    
    	    $retArray["XPath"] = new DOMXpath($dom);
			
    	}
    	catch (Exception $ex) {
			echo "loi roi";
		}
    	
		if ($this->DEBUG)
		{
			if ($dom->hasChildNodes())
			{
				$dom->formatOutput = true;
				$body = $dom->saveXml();
			}
			else $body = $responseBody;

			$body = str_replace("<", "&lt;", $body);
			$body = str_replace(">", "&gt;", $body);

			echo "<h3>Response Header:</h3><pre>$header</pre>";
			echo "<h3>Response Status:</h3><pre>$statusCode</pre>";
			echo "<h3>Response Body:  </h3><pre>$body</pre>";
			flush();
		}
        return $retArray;
    }
}

?>
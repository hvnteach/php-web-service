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

1)
from public weater service via:

http://wsf.cdyne.com/WeatherWS/Weather.asmx

Using php SoapClient class to get weather 02109(Boston), via get city weather by zip code.

2) 
with WSDL (sum.wsdl) and small class (service.php):
fix code in class and wsdl to exporting service in localhost


service.php

<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

public class calculator{
	function sum($add1, $add2) {
		return $add1 + $add2;
	}
}
//fix your url in sum.wsdl
$server = new SoapServer("sum.wsdl"); 
//fix code into ...
$server->addFunction(array("...")); 
//what the method handle soap here?
$server->...(); 

?>

sum.wsdl
<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/"
                  targetNamespace="urn:test:calculator"
                  xmlns:tns="urn:test:calculator"
                  xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/"
                  xmlns:xsd="http://www.w3.org/2001/XMLSchema">
    <wsdl:types>
        <xsd:schema targetNamespace="urn:test:calculator">
            <xsd:element name="add">
                <xsd:complexType>
                    <xsd:sequence>
                        <xsd:element name="value1" type="xsd:int"/>
                        <xsd:element name="value2" type="xsd:int"/>
                    </xsd:sequence>
                </xsd:complexType>
            </xsd:element>
            <xsd:element name="addResponse">
                <xsd:complexType>
                    <xsd:sequence>
                        <xsd:element name="sum" type="xsd:int"/>
                    </xsd:sequence>
                </xsd:complexType>
            </xsd:element>
        </xsd:schema>
    </wsdl:types>
    <wsdl:message name="addRequest">
        <wsdl:part element="tns:add" name="body"/>
    </wsdl:message>
    <wsdl:message name="addResponse">
        <wsdl:part element="tns:addResponse" name="body"/>
    </wsdl:message>
    <wsdl:portType name="Calculator">
        <wsdl:operation name="add">
            <wsdl:input message="tns:addRequest"/>
            <wsdl:output message="tns:addResponse"/>
        </wsdl:operation>
    </wsdl:portType>
    <wsdl:binding name="CalculatorSOAP" type="tns:Calculator">
        <soap:binding style="document" transport="http://schemas.xmlsoap.org/soap/http" />
        <wsdl:operation name="add">
            <soap:operation soapAction="urn:test:calculator:add"/>
            <wsdl:input>
                <soap:body use="literal"/>
            </wsdl:input>
            <wsdl:output>
                <soap:body use="literal"/>
            </wsdl:output>
        </wsdl:operation>
    </wsdl:binding>
    <wsdl:service name="CalculatorService">
        <wsdl:port binding="tns:CalculatorSOAP" name="CalculatorSOAP">
            <soap:address location="..."/>
        </wsdl:port>
    </wsdl:service>
</wsdl:definitions>


<?php

class soap_helper extends SoapClient {

    var $XMLStr = "";

    function setXMLStr($value) {
        $this->XMLStr = $value;
    }

    function getXMLStr() {
        return $this->XMLStr;
    }

    function __doRequest($request, $location, $action, $version, $one_way = 1) {
        //die($request);
        $request = $this->XMLStr;
        $dom = new DOMDocument('1.0');
        try {
            $dom->loadXML($request);
        } catch (DOMException $e) {
            die($e->code);
        }

        $request = $dom->saveXML();

        return parent::__doRequest($request, $location, $action, $version, $one_way);
    }

    function SoapClientCallfuture($SOAPXML) {

        return $this->setXMLStr($SOAPXML);
    }

}

function soapCall($wsdlURL, $callFunction, $XMLString) {
    try {

        
    

         $client = new soap_helper($wsdlURL, array('trace' => true, 'soap_version' => SOAP_1_2));
       
        
                            

        
        $reply = $client->SoapClientCallfuture($XMLString);
        //print_r( $client->__getFunctions());
        $client->__call("$callFunction", array(), array());
       /* die($XMLString);*/
       
        return $client->__getLastResponse();
    } catch (Exception $e) {
        echo 'Message: ' .$e->getMessage();
    }
}

//echo $response = soapCallfuture($api_url, $api_call_func, $api_body_content); die();
?>

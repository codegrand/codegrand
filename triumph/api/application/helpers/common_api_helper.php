<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('test_soap_url')) {//test_soap_url(base_url(),'Shriram_privatecar');

    function test_soap_url($var_url, $param2) {
        $var_url = base_url();

        if ($var_url == "http://10.213.122.10/insurance_dev/") {
            switch ($param2) {
                case 'Shriram_travel':
                    echo $soap_sriram_travel_url = "http://119.226.131.2/ShriramService/TravelService.asmx?wsdl";
                    break;

                case 'Shriram_privatecar':
                    echo $soap_sriram_privatecar_url = "http://119.226.131.2/ShriramService/ShriramService.asmx?wsdl";
                    break;

                case 'Bharti_bike':
                    echo $soap_sriram_bike_url = "http://119.226.131.2/ShriramService/ShriramService.asmx?wsdl";
                    break;

                case 'Bharti_privatecar':
                    echo $soap_bharti_privatecar_url_old = "https://www.bhartiaxaonline.co.in/cordys/WSDLGateway.wcp?service=http%3A%2F%2Fschemas.cordys.com%2FManufacturProvider/serveRequest&organization=o%3DB2B%2Ccn%3Dcordys%2Ccn%3DPROD01%2Co%3Daxa-in.intraxa";
                    echo $soap_bharti_privatecar_url_new = "https://www.bhartiaxaonline.co.in/cordys/com.eibus.web.soap.Gateway.wcp?organization=o=B2B,cn=cordys,cn=PROD01,o=axa-in.intraxa";
                    break;

                case 'Bharti_travel':
                    echo $soap_bharti_travel_url = "https://uat.bhartiaxaonline.co.in/cordys/WSDLGateway.wcp?service=http%3A%2F%2Fschemas.cordys.com%2Fgateway%2FProvider/serve&organization=o%3DB2C%2Ccn%3Dcordys%2Ccn%3DdefaultInst106%2Co%3Dmydomain.com";
                    break;

                case 'Future_privatecar':
                    echo $soap_future_url = "http://fglpg001.futuregenerali.in/BO/Service.svc?wsdl";
                    break;

                case 'HDFC_privatecar':
                    echo $soap_hdfc_privatecar_url = "http://202.191.196.210/uat/onlineproducts/wscalculate/service.asmx?WSDL";
                    break;
            }
        }
        if ($var_url == "https://www.mypolicynow.com/insurance_dev/") {
            echo $soap_url_sriram = "http://online.shriramgi.net/ShriramService/ShriramService.asmx?wsdl";
        }
    }

}


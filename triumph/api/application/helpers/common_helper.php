<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');



if (!function_exists('send_otp')) {

    function send_otp($otp_mobile, $id) {
        $varname = 'buypolicy_otpfor_' . $id;
        //$otp_mobile = $this->input->post('otp_mobile');
        $otp = str_pad(mt_rand(0, 9999), 6, '0', STR_PAD_LEFT);


        $mmsg = "Your OTP is " . $otp . " to login to your account at mypolicynow.com";
        $msg = NULL;
        $from = "info@mypolicynow.com";
        $headers = "From:{$from}";
        $msg .= "Dear Sir/ Madam,Your OTP is  " . $otp . " ";
        $subject = "MOBILE VERIFICATION";
        //include("dependencies/smsapi.php");
        //include("dependencies/mail-function.php");
        $result = sendsms($otp_mobile, $mmsg);
        //$result = 'success';
        $_SESSION[$varname] = $otp;
        //sendmail($user_email, "OTP to login at mypolicynow.com", $msg, "");
        //echo $otp;
        return $result;
    }

}
   
if (!function_exists('getDiffDays')) {
    function getDiffDays($start_date, $end_date) {
        $datetime1 = new DateTime(str_replace('/', '-', $start_date));
        $datetime2 = new DateTime(str_replace('/', '-', $end_date));
        $difference = $datetime1->diff($datetime2);
        return $difference;
    }
    }


 function getIndianCurrency(float $number)
{
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'one', 2 => 'two',
        3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
        7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve',
        13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
        16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
        19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
        40 => 'forty', 50 => 'fifty', 60 => 'sixty',
        70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
    $digits = array('', 'hundred','thousand','lakh', 'crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise ;
}


function mandateauthpassword(){

   $CI = &get_instance();
    $CI->load->helper("soap_helper");
    $CI->load->model("Api_Model");
    $bse_pass_key_data = $CI->Api_Model->bse_key_data();
    $member_id=$bse_pass_key_data['memberid'];
    $user_id=$bse_pass_key_data['userid'];
    $Password_bse=$bse_pass_key_data['password'];
    $Password_key=$bse_pass_key_data['passkey'];
    $euin=$bse_pass_key_data['euin'];


    $islive=$CI->config->item('is_live');
    $soap_url=$CI->config->item('PROVORDERSTATUS')[$islive];
    $soap_method = "GetAccessToken";
    $soap_body_1='
<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ns="http://www.bsestarmf.in/2016/01/" xmlns:star="http://schemas.datacontract.org/2004/07/StarMFWebService">
   <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action>http://www.bsestarmf.in/2016/01/IStarMFWebService/GetAccessToken</wsa:Action><wsa:To>'.$CI->config->item('GETTING_ORDER_ACTION_URL')[$islive].'</wsa:To></soap:Header>
   <soap:Body>
      <ns:GetAccessToken>
         <!--Optional:-->
         <ns:Param>
            <!--Optional:-->
            <star:MemberId>'.$member_id.'</star:MemberId>
            <!--Optional:-->
            <star:PassKey>'.$Password_key.'</star:PassKey>
            <!--Optional:-->
            <star:Password>'.$Password_bse.'</star:Password>
            <!--Optional:-->
            <star:RequestType>MANDATE</star:RequestType>
            <!--Optional:-->
            <star:UserId>'.$user_id.'</star:UserId>
         </ns:Param>
      </ns:GetAccessToken>
   </soap:Body>
</soap:Envelope>
';
$response = soapCall($soap_url, $soap_method, $soap_body_1);
$response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
$xml = new  SimpleXMLElement($response);
   $password_string=(array)$xml->sBody->GetAccessTokenResponse->GetAccessTokenResult->bResponseString;   
$password=$password_string[0];
return $password;

}

/*Password set */
function Passwordsetup1()
{
    $CI = &get_instance();
    $CI->load->helper("soap_helper");
    $CI->load->model("Api_Model");
    $bse_pass_key_data = $CI->Api_Model->bse_key_data();
    $member_id=$bse_pass_key_data['memberid'];
    $user_id=$bse_pass_key_data['userid'];
    $Password_bse=$bse_pass_key_data['password'];
    $Password_key=$bse_pass_key_data['passkey'];
    $euin=$bse_pass_key_data['euin'];

    $islive=$CI->config->item('is_live'); //Bse URL
    // $this->load->helper("bharti_soap_helper");
    $soap_url = $CI->config->item('WSDL_ORDER_URL')[$islive];
    //$end_point_url = 'http://bsestarmfdemo.bseindia.com/MFOrderEntry/MFOrder.svc';
    $soap_method = "getPassword";
    $soap_body_1 = '';

    $soap_body_1 = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ns="http://bsestarmf.in/">
  <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action soap:mustUnderstand="1">http://bsestarmf.in/MFOrderEntry/getPassword</wsa:Action ><wsa:To soap:mustUnderstand="1">'.$CI->config->item('SVC_ORDER_URL')[$islive].'</wsa:To></soap:Header>
   <soap:Body>
      <ns:getPassword>
         <ns:UserId>'.$user_id.'</ns:UserId>
         <ns:MemberId>'.$member_id.'</ns:MemberId>
         <!--Optional:-->
         <ns:Password>'.$Password_bse.'</ns:Password>
         <!--Optional:-->
         <ns:PassKey>'.$Password_key.'</ns:PassKey>
      </ns:getPassword>
   </soap:Body>
</soap:Envelope>';

    $data_password= $response = soapCall($soap_url,$soap_method,$soap_body_1);

    $explode = explode("|",$data_password);            

    return $explode[1];

}

function PasswordsetupUpload()
{

    
   
    $CI = &get_instance();
    $CI->load->helper("soap_helper");
      $CI->load->model("Api_Model");
     $bse_pass_key_data = $CI->Api_Model->bse_key_data();
     $islive=$CI->config->item('is_live'); //Bse URL

    $member_id=$bse_pass_key_data['memberid'];
    $user_id=$bse_pass_key_data['userid'];
    $Password_bse=$bse_pass_key_data['password'];
    $Password_key=$bse_pass_key_data['passkey'];
    $euin=$bse_pass_key_data['euin'];
    // $this->load->helper("bharti_soap_helper");
    $soap_url = $CI->config->item('WSDL_UPLOAD_URL')[$islive];
    $soap_method = "getPassword";
    $soap_body_1 = '';


/*<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ns="http://www.bsestarmf.in/2016/01/">
   <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action>http://www.bsestarmf.in/2016/01/IStarMFWebService/getPassword</wsa:Action><wsa:To>http://www.bsestarmf.in/StarMFWebService/StarMFWebService.svc/Basic</wsa:To></soap:Header>
   <soap:Body>
      <ns:getPassword>
         <!--Optional:-->
         <ns:UserId>2492101</ns:UserId>
         <!--Optional:-->
         <ns:MemberId>24921</ns:MemberId>
         <!--Optional:-->
         <ns:Password>hero@123</ns:Password>
         <!--Optional:-->
         <ns:PassKey>12345</ns:PassKey>
      </ns:getPassword>
   </soap:Body>
</soap:Envelope>"*/

    $soap_body_1 = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ns="'.$CI->config->item('XMLNS_URL')[$islive].'">
  <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action soap:mustUnderstand="1">'.$CI->config->item('GET_PASSWORD_PAYMENT_URL')[$islive].'</wsa:Action ><wsa:To soap:mustUnderstand="1">'.$CI->config->item('SVC_UPLOAD_URL')[$islive].'</wsa:To></soap:Header>
   <soap:Body>
      <ns:getPassword>
         <!--Optional:-->
         <ns:UserId>'.$user_id.'</ns:UserId>
         <!--Optional:-->
         <ns:MemberId>'.$member_id.'</ns:MemberId>
         <!--Optional:-->
         <ns:Password>'.$Password_bse.'</ns:Password>
         <!--Optional:-->
         <ns:PassKey>'.$Password_key.'</ns:PassKey>
      </ns:getPassword>
   </soap:Body>
</soap:Envelope>';
    $data_password= $response = soapCall($soap_url,$soap_method,$soap_body_1);

    $explode = explode("|",$data_password);            

    return $explode[1];


}




function bse_logs($client_code, $user_id, $request, $response,$type)
{
    $CI = &get_instance();
    $array = array(
        "client_code" => $client_code,
        "user_id" => $user_id,
        "request_data"=> $request,
        "response_data"=> $response,
        "timestamp" =>date('Y-m-d H:i:s'),
        "type"=>$type,
    );
    $CI->db->insert("bse_logs", $array);
} 



function bse_key()
{
    $CI = &get_instance();
    
    $CI->load->model("Api_Model");

    $bse_pass_key_data = $CI->Api_Model->bse_key_data();
    $member_id=$bse_pass_key_data['memberid'];
    $user_id=$bse_pass_key_data['userid'];
    $Password_bse=$bse_pass_key_data['password'];
    $Password_key=$bse_pass_key_data['passkey'];
    $euin=$bse_pass_key_data['euin'];
    $broker_code=$bse_pass_key_data['broker_code'];

        $array=array
        (
        'member_id'=>$member_id,
        'user_id'=>$user_id,
        'Password_bse'=>$Password_bse,
        'Password_key'=>$Password_key,
        'euin'=>$euin,
        'broker_code'=>$broker_code,


        );
        return $array;

}

function PasswordsetupFileUpload()
{ 
   

  //    // $this->load->helper("bharti_soap_helper");
    //$this->config->item('STAR_MF_FILE_UPLOAD_SERVICE')[$islive]


    $CI = &get_instance();
    $CI->load->helper("soap_helper");
    $bse_pass_key_data = $CI->Api_Model->bse_key_data();

    $islive=$CI->config->item('is_live');

    $member_id=$bse_pass_key_data['memberid'];
    $user_id=$bse_pass_key_data['userid'];
    $Password_bse=$bse_pass_key_data['password'];
    $Password_key=$bse_pass_key_data['passkey'];
    $euin=$bse_pass_key_data['euin'];
    // $this->load->helper("bharti_soap_helper");
    $soap_url = $CI->config->item('STAR_MF_FILE_UPLOAD_SERVICE')[$islive];
    //$end_point_url = 'http://bsestarmfdemo.bseindia.com/MFOrderEntry/MFOrder.svc';
    $soap_method = "getPassword";
    $soap_body_1 = '';

    $soap_body_1 = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:tem="http://tempuri.org/" xmlns:star="http://schemas.datacontract.org/2004/07/StarMFFileUploadService">
   <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action>http://tempuri.org/IStarMFFileUploadService/GetPassword</wsa:Action><wsa:To>'.$CI->config->item('METHOD_STAR_UPLOAD_SVC')[$islive].'</wsa:To></soap:Header>
   <soap:Body>
      <tem:GetPassword>
         <!--Optional:-->
         <tem:Param>
            <!--Optional:-->
            <star:MemberId>'.$member_id.'</star:MemberId>
            <!--Optional:-->
            <star:Password>'.$Password_bse.'</star:Password>
            <!--Optional:-->
            <star:UserId>'.$user_id.'</star:UserId>
         </tem:Param>
      </tem:GetPassword>
   </soap:Body>
</soap:Envelope>';

    $data_password= $response = soapCall($soap_url,$soap_method,$soap_body_1);

    $explode = explode("<b:ResponseString>",$data_password);  
    $data=str_replace('</getPasswordResult></getPasswordResponse></s:Body></s:Envelope>', '', $explode[1]);  
    $data=str_replace('</b:ResponseString><b:Status>100</b:Status></GetPasswordResult></GetPasswordResponse></s:Body></s:Envelope>', '', $explode[1]); 
    //print_r($data);exit();         

    return $data;


}


function PasswordsetupChildOrder()
{
    $CI = &get_instance();
    $CI->load->helper("soap_helper");
    $bse_pass_key_data = $CI->Api_Model->bse_key_data();
  
   $islive=$CI->config->item('is_live');
/*$CI->config->item('CHILD_ORDER_TO_URL')[$islive];*/
    $member_id=$bse_pass_key_data['memberid'];
    $user_id=$bse_pass_key_data['userid'];
    $Password_bse=$bse_pass_key_data['password'];
    $Password_key=$bse_pass_key_data['passkey'];
    $euin=$bse_pass_key_data['euin'];
    $soap_url = $CI->config->item('GETTING_CHILD_ORDER_DATA_URL')[$islive];
    //$end_point_url = 'http://bsestarmfdemo.bseindia.com/MFOrderEntry/MFOrder.svc';
    $soap_method = "GetPasswordForChildOrder";
    $soap_body_1 = '';

    $soap_body_1 = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ns="http://www.bsestarmf.in/2016/01/" xmlns:star="http://schemas.datacontract.org/2004/07/StarMFWebService">
   <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action>http://www.bsestarmf.in/2016/01/IStarMFWebService/GetPasswordForChildOrder</wsa:Action><wsa:To>'.$CI->config->item('CHILD_ORDER_TO_URL')[$islive].'</wsa:To></soap:Header>
   <soap:Body>
      <ns:GetPasswordForChildOrder>
         <!--Optional:-->
         <ns:Param>
            <!--Optional:-->
            <star:MemberId>'.$member_id.'</star:MemberId>
            <!--Optional:-->
            <star:PassKey>'.$Password_key.'</star:PassKey>
            <!--Optional:-->
            <star:Password>'.$Password_bse.'</star:Password>
            <!--Optional:-->
            <star:RequestType></star:RequestType>
            <!--Optional:-->
            <star:UserId>'.$user_id.'</star:UserId>
         </ns:Param>
      </ns:GetPasswordForChildOrder>
   </soap:Body>
</soap:Envelope>';
    $response = soapCall($soap_url, $soap_method, $soap_body_1);


    $string_rep_s= str_replace('s:','', $response);
    $string_rep_b= str_replace('b:','', $string_rep_s);

    $xml=@simplexml_load_string($string_rep_b);
    $json = json_encode($xml);
    $response_array = json_decode($json, TRUE);
    $password=$response_array['Body']['GetPasswordForChildOrderResponse']['GetPasswordForChildOrderResult']['ResponseString'];
    return $password;
}

/**/


function PasswordsetupPaymentGateway()
{
    $CI = &get_instance();
    $CI->load->helper("soap_helper");
    $bse_pass_key_data = $CI->Api_Model->bse_key_data();

    $member_id=$bse_pass_key_data['memberid'];
    $user_id=$bse_pass_key_data['userid'];
    $Password_bse=$bse_pass_key_data['password'];
    $Password_key=$bse_pass_key_data['passkey'];
    $euin=$bse_pass_key_data['euin'];

    $soap_url = 'https://bsestarmfdemo.bseindia.com/StarMFPaymentGatewayService/StarMFPaymentGatewayService.svc?singleWsdl';
    //$end_point_url = 'http://bsestarmfdemo.bseindia.com/MFOrderEntry/MFOrder.svc';
    $soap_method = "GetPassword";
    $soap_body_1 = '';

    $soap_body_1 = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:tem="http://tempuri.org/" xmlns:star="http://schemas.datacontract.org/2004/07/StarMFPaymentGatewayService">
   <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action>http://tempuri.org/IStarMFPaymentGatewayService/GetPassword</wsa:Action><wsa:To>http://bsestarmfdemo.bseindia.com/StarMFPaymentGatewayService/StarMFPaymentGatewayService.svc/Basic</wsa:To></soap:Header>
   <soap:Body>
      <tem:GetPassword>
         <!--Optional:-->
         <tem:Param>
            <!--Optional:-->
            <star:MemberId>'.$member_id.'</star:MemberId>
            <!--Optional:-->
            <star:PassKey>'.$Password_key.'</star:PassKey>
            <!--Optional:-->
            <star:Password>'.$Password_bse.'</star:Password>
            <!--Optional:-->
            <star:UserId>'.$user_id.'</star:UserId>
         </tem:Param>
      </tem:GetPassword>
   </soap:Body>
</soap:Envelope>';
    $response = soapCall($soap_url, $soap_method, $soap_body_1);
    $str_rep_s=str_replace('s:','', $response);
    $str_rep_b=str_replace('b:','', $str_rep_s);

    $xml=simplexml_load_string($str_rep_b);
    $json = json_encode($xml);
    $response_array = json_decode($json, TRUE);

    $password= $response_array['Body']['GetPasswordResponse']['GetPasswordResult']['ResponseString'];
    return $password;
}


function Mandateapi($array,$password,$type,$user_id,$pan){

  /*$WSDL_UPLOAD_URL */
     

  //    // $this->load->helper("bharti_soap_helper");
    //$this->config->item('WSDL_UPLOAD_URL')[$islive]


    $CI = &get_instance();
    $CI->load->helper("soap_helper");
    $bse_pass_key_data = $CI->Api_Model->bse_key_data();

    $islive=$CI->config->item('is_live');

    $member_id=$bse_pass_key_data['memberid'];
    $bse_user_id=$bse_pass_key_data['userid'];
    $Password_bse=$bse_pass_key_data['password'];
    $Password_key=$bse_pass_key_data['passkey'];
    $euin=$bse_pass_key_data['euin'];

    $piped_array = array();
    foreach($array as $user=>$key)
    {

        array_push($piped_array,$key);
    }

    $piped_string = implode("|",$piped_array);
    $soap_url = $CI->config->item('WSDL_UPLOAD_URL')[$islive];
    //$end_point_url = 'http://bsestarmfdemo.bseindia.com/MFOrderEntry/MFOrder.svc';
    $soap_method = "MFAPI";
    $soap_body_1 = '';
    $soap_body_1 = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ns="'.$CI->config->item('XMLNS_URL')[$islive].'">
   <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action soap:mustUnderstand="1">'.$CI->config->item('ACTION_MFAPI_URL')[$islive].'</wsa:Action><wsa:To soap:mustUnderstand="1">'.$CI->config->item('SVC_UPLOAD_URL')[$islive].'</wsa:To></soap:Header>
   <soap:Body>
      <ns:MFAPI>
         <!--Optional:-->
         <ns:Flag>06</ns:Flag>
         <!--Optional:-->
         <ns:UserId>'.$bse_user_id.'</ns:UserId>
         <!--Optional:-->
         <ns:EncryptedPassword>'.$password.'</ns:EncryptedPassword>
         <!--Optional:-->
         <ns:param>'.$piped_string.'</ns:param>
      </ns:MFAPI>

   </soap:Body>
</soap:Envelope>';
    //echo $soap_body_1;exit;
    $data= $response = soapCall($soap_url,$soap_method,$soap_body_1);
    $response_string="";


    $data=str_replace('s:','', $data);
    //print_r($data);
    //$data=str_replace('a:','', $data);

    $xml = @simplexml_load_string($data);
    $response_array= (array)$xml;

    $data_array_string=(array)$response_array['Body'];
    $data_array_string=(array)$data_array_string['MFAPIResponse'];
    //$json = json_encode($xml);
    //$response_array = json_decode($json, TRUE);
    $response_string=$data_array_string['MFAPIResult'];
    $response_string_array=explode('|', $response_string);

    $otm='';
    if($response_string_array[0]==100){
        $otm=$response_string_array[2];

    }


    //$data_update=array("otm"=>$otm);

    $data_update= array();
    switch ($type) {
        case 'E':
            $data_update=array("otm"=>$otm);
            break;
        case 'X':
            $data_update=array("otm2"=>$otm);
            break;
        case 'I':
            $data_update=array("otm1"=>$otm,
                               "kyc"=>'Yes',
                               "bse_active"=>1);
            break;
        default:
            # code...
            break;
    }

    $array = array(
        "client_code" => $pan,
        "user_id" =>$bse_user_id,
        "request_data" => $soap_body_1,
        "response_data" => $data,
        "timestamp" =>date('Y-m-d H:i:s'),
        "type"=>'Mandate',
    );  
    $CI->db->insert("bse_logs", $array);



    $CI->db->where('id',$user_id);
    $CI->db->update('users',$data_update);
    //print_r($CI->db->last_query());



    return  $otm;
}




if (!function_exists('sendSms')) {

    function sendSms($Mobilenumber, $Message) {
        error_reporting(1);
        $uid = "MPOLNW";
        $pwd = "Pwe$NiTm";
        $sid = "HEROMF";
        $method = "POST";
        $message = urlencode($Message);
        $CI = &get_instance();
        //$get_url = "http://www.k3digitalmedia.in/vendorsms/pushsms.aspx?user=" . $uid . "&password=" . $pwd . "&msisdn=" . $Mobilenumber . "&sid=" . $sid . "&msg=" . $message . "&fl=0&gwid=2";
       //http://123.108.46.12/API/WebSMS/Http/v1.0a/index.php?username=MPOLNW&password=Pwe$NiTm&sender=HEROMF&to=9004054630&message=test
		$get_url = 'http://123.108.46.12/API/WebSMS/Http/v1.0a/index.php?username=MPOLNW&password=Pwe$NiTm&sender='.$sid.'&to='.$Mobilenumber.'&message='.$message.'';
		
	   
        function httpGet($url) 
        {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
            $output = curl_exec($ch);
            curl_close($ch);
            return $output;
        }

        $result = httpGet($get_url);

        function old() {
            $ch = curl_init($get_url);

            $curlversion = curl_version();
            curl_setopt($ch, CURLOPT_USERAGENT, 'PHP ' . phpversion() . ' + Curl ' . $curlversion['version']);
            curl_setopt($ch, CURLOPT_REFERER, null);

            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            if ($method == "POST") {
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
            } else {
                curl_setopt($ch, CURLOPT_POST, 0);
                curl_setopt($ch, CURLOPT_URL, $get_url);
            }

            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            // DO NOT RETURN HTTP HEADERS
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // RETURN THE CONTENTS OF THE CALL
            $buffer = curl_exec($ch);
            $err = curl_errno($ch);
            $errmsg = curl_error($ch);
            $header = curl_getinfo($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $_SESSION['buffer'] = $err . "   " . $errmsg . "   " . $buffer;
            $_SESSION['curlURL'] = $get_url;
            curl_close($ch);
        }

        return $result;
    }

}


function SendFileAOF($pan,$AccessFrom){

    $CI = &get_instance();

    $CI->load->helper("soap_helper");
    $CI->load->model("Api_Model");
 
    $islive=$CI->config->item('is_live'); //Bse URL
    $bse_pass_key_data = $CI->Api_Model->bse_key_data();
    
    $CI->Api_Model->generate_pdf($pan,$AccessFrom);

    $fetch_customer_details = $CI->Api_Model->fetch_customer_details($pan);
    $image = FCPATH.''.$fetch_customer_details['aof_pdf_link']; 
    $name= $fetch_customer_details['name'];

    // be careful that the path is correct
    $data = fopen($image, 'rb');
    $size = filesize($image);
    $contents = fread($data, $size);
    fclose($data);
    $encoded_string = base64_encode($contents);
    $member_id=$bse_pass_key_data['memberid'];
    $user_bse_id=$bse_pass_key_data['userid'];
    $Password_bse=$bse_pass_key_data['password'];
    $Password_key=$bse_pass_key_data['passkey'];
    $euin=$bse_pass_key_data['euin'];

   

     
    $now = DateTime::createFromFormat('U.u', microtime(true));
    $id = $member_id.$pan.$now->format('dmY');
    $file_name=$id.".tiff";


$encoded_string = str_replace('data:image/png;base64,', '', $encoded_string);
$encoded_string = str_replace('data:image/jpeg;base64,', '', $encoded_string);
$encoded_string = str_replace('data:image/jpg;base64,', '', $encoded_string);
$encoded_string = str_replace('data:image/gif;base64,', '', $encoded_string);
$encoded_string = str_replace('data:image/bmp;base64,', '', $encoded_string);

    
    $message='<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
        <tr>
            <td align="center" bgcolor="#f5f5f5">
                <table style="max-width:500px" width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                        <tr>
                            <td style="padding:15px 0" valign="top" align="center">
                                <a href="http://103.87.174.12/heromutual/dev/" style="margin: 0 auto; display: inline-block" target="_blank">                                     
                                    <img id="" style="width: 170px; margin-top: 15px; margin-bottom: 10px; border: 0px; outline: none" alt="'.$CI->config->item('BUINESS_SHORT_NAME').' Logo" src="http://103.87.174.12/heromutual/dev/assets/images/logo.png"></a> 
                                <br>
                            </td></tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding:70px 15px 70px 15px" align="center" bgcolor="#ffffff">
                    <table style="max-width:500px" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tbody>
                            <tr>
                                <td>
                                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)">Dear '.$name.',<br>
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)">Please see the attached document.
                                                    <br>
                                                </td>
                                            </tr>
                                           
                                            <tr>
                                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)">For more information please write to us at <a href="mailto:'.$CI->config->item('BUINESS_EMAIL_ID').'" style="font-weight:bold" target="_blank">'.$CI->config->item('BUINESS_EMAIL_ID').'</a><br></td>
                                            </tr>
                                            <tr>
                                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)"><div>Best regards, <br>
                                                </div>
                                                <div> Team '.$CI->config->item('BUINESS_FULL_NAME').'<br></div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding:20px 0px" align="center" bgcolor="#E6E9ED">
                <table style="max-width:500px" align="center" cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tbody>
                        <tr>
                            <td style="font-size:12px;line-height:18px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)" align="center">
                                <a style="color:rgb(66,139,202)" href="mailto:'.$CI->config->item('BUINESS_EMAIL_ID').'" target="_blank">'.$CI->config->item('BUINESS_EMAIL_ID').'</a>
                                <br>
                            </td>
                        </tr>
                      

                        </tbody>
                    </table>
                    </td>
                </tr>
                </tbody>
                </table>';

    $subject="Attached : AOF PDF";
    $data_pdf_file=FCPATH.$fetch_customer_details['aof_pdf_link'];
    SendMail($fetch_customer_details['email'],$CI->config->item('BUINESS_EMAIL_ID'),$message,$subject,$data_pdf_file,'AOF PDF');

    //  $CI->bse_logs($pan,$user_id,$soap_body_1,$response);

}

function UploadFileAOF($pan){

    $CI = &get_instance();

    $CI->load->helper("soap_helper");
    $CI->load->model("Api_Model");
 
    $islive=$CI->config->item('is_live'); //Bse URL
    $bse_pass_key_data = $CI->Api_Model->bse_key_data();
    
    $CI->Api_Model->generate_pdf($pan);

    $fetch_customer_details = $CI->Api_Model->fetch_customer_details($pan);
    $image = FCPATH.''.$fetch_customer_details['aof_pdf_link']; 
    $name= $fetch_customer_details['name'];

    // be careful that the path is correct
    $data = fopen($image, 'rb');
    $size = filesize($image);
    $contents = fread($data, $size);
    fclose($data);
    $encoded_string = base64_encode($contents);
    $member_id=$bse_pass_key_data['memberid'];
    $user_bse_id=$bse_pass_key_data['userid'];
    $Password_bse=$bse_pass_key_data['password'];
    $Password_key=$bse_pass_key_data['passkey'];
    $euin=$bse_pass_key_data['euin'];

   

   	 
    $now = DateTime::createFromFormat('U.u', microtime(true));
    $id = $member_id.$pan.$now->format('dmY');
    $file_name=$id.".tiff";
    $password=PasswordsetupFileUpload();
    $soap_url = $CI->config->item('STAR_MF_FILE_UPLOAD_SERVICE')[$islive];    
    $soap_method = "UploadFile";        


$encoded_string = str_replace('data:image/png;base64,', '', $encoded_string);
$encoded_string = str_replace('data:image/jpeg;base64,', '', $encoded_string);
$encoded_string = str_replace('data:image/jpg;base64,', '', $encoded_string);
$encoded_string = str_replace('data:image/gif;base64,', '', $encoded_string);
$encoded_string = str_replace('data:image/bmp;base64,', '', $encoded_string);

$soap_body_1 = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:tem="http://tempuri.org/" xmlns:star="http://schemas.datacontract.org/2004/07/StarMFFileUploadService">
   <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action>http://tempuri.org/IStarMFFileUploadService/UploadFile</wsa:Action><wsa:To>'.$CI->config->item('METHOD_STAR_UPLOAD_SVC')[$islive].'</wsa:To></soap:Header>
   <soap:Body>
      <tem:UploadFile>
         <!--Optional:-->
         <tem:data>
            <!--Optional:-->
            <star:ClientCode>'.$pan.'</star:ClientCode>
            <!--Optional:-->
            <star:DocumentType>NRM</star:DocumentType>
            <!--Optional:-->
            <star:EncryptedPassword>'.$password.'</star:EncryptedPassword>
            <!--Optional:-->
            <star:FileName>'.$file_name.'</star:FileName>
            <!--Optional:-->
            <star:Filler1/>
            <!--Optional:-->
            <star:Filler2/>
            <!--Optional:-->
            <star:Flag>UCC</star:Flag>
            <!--Optional:-->
            <star:MemberCode>'.$member_id.'</star:MemberCode>
            <!--Optional:-->
            <star:UserId>'.$user_bse_id.'</star:UserId>
            <!--Optional:-->
            <star:pFileBytes>'.$encoded_string.'</star:pFileBytes>
         </tem:data>
      </tem:UploadFile>
   </soap:Body>
</soap:Envelope>';
    $response = soapCall($soap_url,$soap_method,$soap_body_1);
    $arr123 = array(
        "client_code" => $pan,
        "user_id" => $user_id,
        "request_data" => $soap_body_1,
        "response_data" => $response,
        "type"=>"UploadAOF"
    );  
    $CI->db->insert("bse_logs", $arr123);
    
    $message='<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
        <tr>
            <td align="center" bgcolor="#f5f5f5">
                <table style="max-width:500px" width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                        <tr>
                            <td style="padding:15px 0" valign="top" align="center">
                                <a><img src="images/logo.png" border="0" style="display:block;font-family:Helvetica,Arial,sans-serif;font-size:16px;padding:5px" alt="" width="82" class=""> </a>
                                <br>
                            </td></tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding:70px 15px 70px 15px" align="center" bgcolor="#ffffff">
                    <table style="max-width:500px" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tbody>
                            <tr>
                                <td>
                                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)">Dear '.$name.',<br>
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)">Please see the attached document.
                                                    <br>
                                                </td>
                                            </tr>
                                           
                                            <tr>
                                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)">For more information please visit <a style="font-weight:bold">www.herocorp.com</a> or call <span class="" style="color:rgb(203,153,55)"><b>+91-2226820489</b></span> or write to us at <a href="mailto:'.$CI->config->item('BUINESS_EMAIL_ID').'" style="font-weight:bold" target="_blank">'.$CI->config->item('BUINESS_EMAIL_ID').'</a><br></td>
                                            </tr>
                                            <tr>
                                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)"><div>Best regards, <br>
                                                </div>
                                                <div> Team '.$CI->config->item('BUINESS_FULL_NAME').'<br></div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding:20px 0px" align="center" bgcolor="#E6E9ED">
                <table style="max-width:500px" align="center" cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tbody>
                        <tr>
                            <td style="font-size:12px;line-height:18px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)" align="center">
                                <a style="color:rgb(66,139,202)" href="mailto:'.$CI->config->item('BUINESS_EMAIL_ID').'" target="_blank">'.$CI->config->item('BUINESS_EMAIL_ID').'</a>
                                <br>
                            </td>
                        </tr>
                       

                        </tbody>
                    </table>
                    </td>
                </tr>
                </tbody>
                </table>';

    $subject="Attached : AOF PDF";
    $data_pdf_file=FCPATH.$fetch_customer_details['aof_pdf_link'];
   	SendMail($fetch_customer_details['email'],$CI->config->item('BUINESS_EMAIL_ID'),$message,$subject,$data_pdf_file,'AOF');

    //  $CI->bse_logs($pan,$user_id,$soap_body_1,$response);

}

function UploadFile($user_id,$pan,$string,$file_name){

    $CI = &get_instance();


  //    // $this->load->helper("bharti_soap_helper");
    //$this->config->item('WSDL_UPLOAD_URL')[$islive]

    $islive=$CI->config->item('is_live'); //Bse URL
    // $this->load->helper("bharti_soap_helper");


    $CI->load->helper("soap_helper");
    $bse_pass_key_data = $CI->Api_Model->bse_key_data();

    $member_id=$bse_pass_key_data['memberid'];
    $user_bse_id=$bse_pass_key_data['userid'];
    $Password_bse=$bse_pass_key_data['password'];
    $Password_key=$bse_pass_key_data['passkey'];
    $euin=$bse_pass_key_data['euin'];

    $password=PasswordsetupFileUpload();
    // print_r($password);exit();
    $soap_url = $CI->config->item('STAR_MF_FILE_UPLOAD_SERVICE')[$islive];    
    $soap_method = "UploadFile";        
 /*   $soap_body_1 = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:tem="http://tempuri.org/" xmlns:star="http://schemas.datacontract.org/2004/07/StarMFFileUploadService">
   <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action>http://tempuri.org/IStarMFFileUploadService/UploadFile</wsa:Action><wsa:To>'.$CI->config->item('METHOD_STAR_UPLOAD_SVC')[$islive].'</wsa:To></soap:Header>
   <soap:Body>
      <tem:UploadFile>
         <!--Optional:-->
         <tem:data>
            <!--Optional:-->
            <star:ClientCode>'.$pan.'</star:ClientCode>
            <!--Optional:-->
            <star:DocumentType>NRM</star:DocumentType>
            <!--Optional:-->
            <star:EncryptedPassword>'.$password.'</star:EncryptedPassword>
            <!--Optional:-->
            <star:FileName>'.$file_name.'</star:FileName>
            <!--Optional:-->
            <star:Filler1/>
            <!--Optional:-->
            <star:Filler2/>
            <!--Optional:-->
            <star:Flag>UCC</star:Flag>
            <!--Optional:-->
            <star:MemberCode>'.$member_id.'</star:MemberCode>
            <!--Optional:-->
            <star:UserId>'.$user_bse_id.'</star:UserId>
            <!--Optional:-->
            <star:pFileBytes>'.$string.'</star:pFileBytes>
         </tem:data>
      </tem:UploadFile>
   </soap:Body>
</soap:Envelope>';*/

/*<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:tem="http://tempuri.org/" xmlns:star="http://schemas.datacontract.org/2004/07/StarMFFileUploadService">
   <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action>http://tempuri.org/IStarMFFileUploadService/UploadFile</wsa:Action><wsa:To>http://www.bsestarmf.in/StarMFFileUploadService/StarMFFileUploadService.svc/Basic</wsa:To></soap:Header>
   <soap:Body>
      <tem:UploadFile>
         <!--Optional:-->
         <tem:data>
            <!--Optional:-->
            <star:ClientCode>?</star:ClientCode>
            <!--Optional:-->
            <star:DocumentType>?</star:DocumentType>
            <!--Optional:-->
            <star:EncryptedPassword>?</star:EncryptedPassword>
            <!--Optional:-->
            <star:FileName>?</star:FileName>
            <!--Optional:-->
            <star:Filler1>?</star:Filler1>
            <!--Optional:-->
            <star:Filler2>?</star:Filler2>
            <!--Optional:-->
            <star:Flag>?</star:Flag>
            <!--Optional:-->
            <star:MemberCode>?</star:MemberCode>
            <!--Optional:-->
            <star:UserId>?</star:UserId>
            <!--Optional:-->
            <star:pFileBytes>cid:676705776989</star:pFileBytes>
         </tem:data>
      </tem:UploadFile>
   </soap:Body>
</soap:Envelope>"*/


$string = str_replace('data:image/png;base64,', '', $string);
$string = str_replace('data:image/jpeg;base64,', '', $string);
$string = str_replace('data:image/jpg;base64,', '', $string);
$string = str_replace('data:image/gif;base64,', '', $string);
$string = str_replace('data:image/bmp;base64,', '', $string);

   $soap_body_1 = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:tem="http://tempuri.org/" xmlns:star="http://schemas.datacontract.org/2004/07/StarMFFileUploadService">
   <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action>http://tempuri.org/IStarMFFileUploadService/UploadFile</wsa:Action><wsa:To>'.$CI->config->item('METHOD_STAR_UPLOAD_SVC')[$islive].'</wsa:To></soap:Header>
   <soap:Body>
      <tem:UploadFile>
         <!--Optional:-->
         <tem:data>
            <!--Optional:-->
            <star:ClientCode>'.$pan.'</star:ClientCode>
            <!--Optional:-->
            <star:DocumentType>NRM</star:DocumentType>
            <!--Optional:-->
            <star:EncryptedPassword>'.$password.'</star:EncryptedPassword>
            <!--Optional:-->
            <star:FileName>'.$file_name.'</star:FileName>
            <!--Optional:-->
            <star:Filler1/>
            <!--Optional:-->
            <star:Filler2/>
            <!--Optional:-->
            <star:Flag>UCC</star:Flag>
            <!--Optional:-->
            <star:MemberCode>'.$member_id.'</star:MemberCode>
            <!--Optional:-->
            <star:UserId>'.$user_bse_id.'</star:UserId>
            <!--Optional:-->
            <star:pFileBytes>'.$string.' </star:pFileBytes>
         </tem:data>
      </tem:UploadFile>
   </soap:Body>
</soap:Envelope>';

    //echo $string;exit;

    $response = soapCall($soap_url,$soap_method,$soap_body_1);
    $array = array(
        "client_code" => $pan,
        "user_id" => $user_id,
        "request_data" => $soap_body_1,
        "response_data" => $response,
        "type"=>"Upload File"

    );  
    $CI->db->insert("bse_logs", $array);
    //  $CI->bse_logs($pan,$user_id,$soap_body_1,$response);

} 



function check_date($sip_day){


    $sip_date=date(''.$sip_day.'-m-Y');
    $sip_date_timestamp=strtotime($sip_date);
    $today_date_timestamp=time();
    if($sip_date_timestamp < $today_date_timestamp)
    {
        $date= date("d/m/Y", strtotime("+1 month",  strtotime($sip_date)));
        $database_date= date('Y-m-d', strtotime($sip_date. ' +1 month'));


    }
    else
    {
        $date= date('d/m/Y', strtotime($sip_date));  
        $database_date= date('Y-m-d', strtotime($sip_date));
    }

    return $date;   

}    


if (!function_exists('welcome_email')) {
    function welcome_email($name,$email,$pan,$password) {
    $CI = &get_instance();
        $welecome_template='<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody><tr>
        <td bgcolor="#f5f5f5" align="center">

            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:500px" class="">
                <tbody><tr>
                    <td align="center" valign="top" style="padding:15px 0" class="">
                        <a href="http://103.87.174.12/heromutual/dev/" style="margin: 0 auto; display: inline-block" target="_blank">                                     
                                    <img id="" style="width: 170px; margin-top: 15px; margin-bottom: 10px; border: 0px; outline: none" alt="'.$CI->config->item('BUINESS_SHORT_NAME').' Logo" src="http://103.87.174.12/heromutual/dev/assets/images/logo.png"></a>  
                    </td>
                </tr>
            </tbody></table>

        </td>
    </tr>
    <tr>
        <td bgcolor="#ffffff" align="center" style="padding:70px 15px 70px 15px" class="">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:500px" class="table">
                <tbody><tr>
                    <td>

                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody><tr>
                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:#666666" class="">Dear '.$name.',</td>
                            </tr>
                            <tr>
                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:#666666" class="">Welcome to <a style="text-decoration:none;font-weight:bold">'.$CI->config->item('BUINESS_SHORT_NAME').' </a> - Your Wealth Manager!</td>
                            </tr>
                            <tr>
                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:#666666" class="">Please find below your login credentials and view your Portfolio -</td>
                            </tr>
                            <tr>
                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:#666666" class="">Visit : <a style="text-decoration:none;font-weight:bold">www.<span class="il">herocorp</span>.com</a><br>
                                Login Id : '.$pan.' <br>
                                Password : '.$password.'</td>
                            </tr>
                            <tr>
                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:#666666" class="">For more information please write to us at <a style="font-weight:bold" href="mailto: '.$CI->config->item('BUINESS_EMAIL_ID').'" target="_blank">'.$CI->config->item('BUINESS_EMAIL_ID').'</a></td>
                            </tr>
                            <tr>
                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:#666666" class="">Best regards, <br>
                                Team '.$CI->config->item('BUINESS_FULL_NAME').'</td>
                            </tr>
                        </tbody></table>
                    </td>
                </tr>
            </tbody></table>
        </td>
    </tr>
    <tr>
        <td bgcolor="#E6E9ED" align="center" style="padding:20px 0px">


            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="max-width:500px" class="">
                    <tbody><tr>
                    <td align="center" style="font-size:12px;line-height:18px;font-family:Helvetica,Arial,sans-serif;color:#666666">
                    <a href="mailto:'.$CI->config->item('BUINESS_EMAIL_ID').'" style="color:#428bca" target="_blank">'.$CI->config->item('BUINESS_EMAIL_ID').'</a>
                    </td>
                    </tr>
                    
                    
            </tbody></table>

        </td>
    </tr>
</tbody></table>';

        $from = $CI->config->item('BUINESS_EMAIL_ID');
        $to = $email;
        $subject = "Welcome to ".$CI->config->item('BUINESS_FULL_NAME')."";
        SendMail($to,$from,$welecome_template,$subject,'','');

    }

}



if (!function_exists('send_mail_otp')) {

    function send_mail_otp($name,$email_otp,$email) {
       $CI = &get_instance();
        $email_verified_html='
<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
        <tr>
            <td align="center" bgcolor="#f5f5f5">
                <table style="max-width:500px" width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                        <tr>
                            <td style="padding:15px 0" valign="top" align="center">
                                <a href="http://103.87.174.12/heromutual/dev/" style="margin: 0 auto; display: inline-block" target="_blank">                                     
                                    <img id="" style="width: 170px; margin-top: 15px; margin-bottom: 10px; border: 0px; outline: none" alt="'.$CI->config->item('BUINESS_SHORT_NAME').' Logo" src="http://103.87.174.12/heromutual/dev/assets/images/logo.png"></a>  
                                <br>
                            </td></tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding:70px 15px 70px 15px" align="center" bgcolor="#ffffff">
                    <table style="max-width:500px" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tbody>
                            <tr>
                                <td>
                                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)">Dear '.$name.',<br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)">Welcome to <a style="text-decoration:none;font-weight:bold">'.$CI->config->item('BUINESS_SHORT_NAME').'</a> - Your Wealth Manager!<br></td>
                                            </tr>
                                            <tr>
                                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)">Please enter the following verification code to set your password.
                                                    <br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)">Email Verification Code : '.$email_otp.'<br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)">For more information please write to us at <a href="mailto:'.$CI->config->item('BUINESS_EMAIL_ID').'" style="font-weight:bold" target="_blank">'.$CI->config->item('BUINESS_EMAIL_ID').'</a><br></td>
                                            </tr>
                                            <tr>
                                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)"><div>Best regards, <br>
                                                </div>
                                                <div> Team '.$CI->config->item('BUINESS_FULL_NAME').'<br></div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding:20px 0px" align="center" bgcolor="#E6E9ED">
                <table style="max-width:500px" align="center" cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tbody>
                        <tr>
                            <td style="font-size:12px;line-height:18px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)" align="center">
                                <a style="color:rgb(66,139,202)" href="mailto:'.$CI->config->item('BUINESS_EMAIL_ID').' target="_blank">'.$CI->config->item('BUINESS_EMAIL_ID').'</a>
                                <br>
                            </td>
                        </tr>
                        

                        </tbody>
                    </table>
                    </td>
                </tr>
                </tbody>
                </table>';

                

        /*SMS */
        $from = $CI->config->item('BUINESS_EMAIL_ID');
        $to = $email;
        $subject = "".$CI->config->item('BUINESS_FULL_NAME')." - Email Verification Code";
        SendMail($to,$from,$email_verified_html,$subject,'','');
    }

}


if (!function_exists('send_mobile_otp')) {
    function send_mobile_otp($name,$email_opt,$email,$Message) {
       $CI = &get_instance();
        $email_verified_html='
<table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
        <tr>
            <td align="center" bgcolor="#f5f5f5">
                <table style="max-width:500px" width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                        <tr>
                            <td style="padding:15px 0" valign="top" align="center">
                                <a href="http://103.87.174.12/heromutual/dev/" style="margin: 0 auto; display: inline-block" target="_blank">                                     
                                    <img id="" style="width: 170px; margin-top: 15px; margin-bottom: 10px; border: 0px; outline: none" alt="'.$CI->config->item('BUINESS_SHORT_NAME').' Logo" src="http://103.87.174.12/heromutual/dev/assets/images/logo.png"></a> 
                                <br>
                            </td></tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding:70px 15px 70px 15px" align="center" bgcolor="#ffffff">
                    <table style="max-width:500px" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tbody>
                            <tr>
                                <td>
                                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)">Dear '.$name.',<br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)">Welcome to <a style="text-decoration:none;font-weight:bold">'.$CI->config->item('BUINESS_SHORT_NAME').'</a> - Your Wealth Manager!<br></td>
                                            </tr>
                                            <tr>
                                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)">Please enter the following verification code to set your password.
                                                    <br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)">'.$Message.'<br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)">For more information please write to us at <a href="mailto:'.$CI->config->item('BUINESS_EMAIL_ID').'" style="font-weight:bold" target="_blank">'.$CI->config->item('BUINESS_EMAIL_ID').'</a><br></td>
                                            </tr>
                                            <tr>
                                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)"><div>Best regards, <br>
                                                </div>
                                                <div> Team '.$CI->config->item('BUINESS_FULL_NAME').'<br></div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding:20px 0px" align="center" bgcolor="#E6E9ED">
                <table style="max-width:500px" align="center" cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tbody>
                        <tr>
                            <td style="font-size:12px;line-height:18px;font-family:Helvetica,Arial,sans-serif;color:rgb(102,102,102)" align="center">
                                <a style="color:rgb(66,139,202)" href="mailto:'.$CI->config->item('BUINESS_EMAIL_ID').'" target="_blank">'.$CI->config->item('BUINESS_EMAIL_ID').'</a>
                                <br>
                            </td>
                        </tr>
                       </tbody>
                    </table>
                    </td>
                </tr>
                </tbody>
                </table>';

                

        /*SMS */
        $from = $CI->config->item('BUINESS_EMAIL_ID');
        $to = $email;
        $subject = "".$CI->config->item('BUINESS_FULL_NAME')." - Mobile Verification Code";
        SendMail($to,$from,$email_verified_html,$subject,'','');
    }

}





function sendbankmndatetemplate($name){

 $CI = &get_instance();
$template='<div style="background-color: rgb(245, 246, 251); margin: 0px; padding: 15px 10px; font-family: sans-serif !important">     
    <center>         
        <table border="0" bgcolor="#ffffff" cellpadding="0" cellspacing="0" style="display: block !important; max-width: 500px !important; margin: 0 auto !important; clear: both !important" align="center">             
            <tbody><tr>                 
                <td style="width: 600px; padding: 0">                     
                    <table style="display: block !important; margin: 0 auto !important; clear: both !important; background-color: rgb(255, 255, 255)" align="center">                         <tbody style="display: table; width: 100%">                             
                        <tr>                                 
                            <td style="text-align: center; width: 600px">                                     
                                <a href="http://103.87.174.12/heromutual/dev/" style="margin: 0 auto; display: inline-block" target="_blank">                                     
                                    <img id="" style="width: 170px; margin-top: 15px; margin-bottom: 10px; border: 0px; outline: none" alt="'.$CI->config->item('BUINESS_SHORT_NAME').' Logo" src="http://103.87.174.12/heromutual/dev/assets/images/logo.png"></a>                                 </td>                             
                        </tr>                             <tr>                                 
                        <td style="border-top: 1px solid rgb(232, 235, 240)">                                     
                            <table style="margin: 0px auto; clear: both !important" align="center">                                         
                                <tbody>
                                    <tr>                                             
                                        <td style="padding: 15px 15px 0px">                                                 
                                            <h3 style="margin: 0px; font-family: sans-serif; font-size: 16px; font-weight: normal; margin-bottom: 15px; line-height: 25px; color: rgb(92, 107, 126)">Hi '.$name.' ,</h3>                                                              
                                             <p style="font-size: 14px; line-height: 1.4; margin-bottom: 20px; font-family: sans-serif; color: rgb(92, 107, 126)">Please find attached Mandate(OTM) form that needs to be printed, signed and uploaded on app. This is essential for activating your upcoming SIPs and enabling one-click buy on app.</p>                                                 <p style="font-size: 14px; line-height: 1.4; font-family: sans-serif; color: rgb(92, 107, 126); margin-bottom: 5px; margin-bottom: 5px">Please follow these steps for successful submission of Mandate(OTM) form:</p>                           <ol type="1" style="margin: 0px; padding: 5px 5px 5px 25px">                           
                                                   <li style="font-size: 13px; line-height: 1.4; font-family: sans-serif; color: rgb(92, 107, 126); margin-bottom: 5px">Download the attached Mandate(OTM) form</li>                                                     <li style="font-size: 13px; line-height: 1.4; font-family: sans-serif; color: rgb(92, 107, 126); margin-bottom: 5px">Print the form on A4 size paper</li>                                                     <li style="font-size: 13px; line-height: 1.4; font-family: sans-serif; color: rgb(92, 107, 126); margin-bottom: 5px">Sign as per your bank records above your name</li>                                                     <li style="font-size: 13px; line-height: 1.4; font-family: sans-serif; color: rgb(92, 107, 126); margin-bottom: 5px">Click or upload the clear picture of form on
                                            <a href="https://play.google.com/store/apps/details?id=com.indicosmic.www.heromfapp" style="color: rgb(83, 147, 236); text-decoration: none" target="_blank">app</a>                                                   
                                            </li>                                                                                              
                                            </ol>
                                        </td>                                        
                                    </tr>                                     
                                </tbody>
                            </table>                                 
                        </td>                            
                        </tr>                        
                        </tbody>                    
                    </table>                     
                    <table style="display: block !important; margin: 0 auto !important; clear: both !important; background-color: rgb(255, 255, 255)" align="center">                         
                        <tbody style="display: table; width: 100%">                             
                            <tr>                                 
                                <td style="padding: 0px 15px 10px">                                     
                                    <table style="margin: 0px auto; clear: both !important" align="center">                                         
                                        <tbody><tr>                                             
                                            <td>                                                 
                                                <p style="font-size: 13px; line-height: 1.4; margin-top: 0; margin-bottom: 20px; font-family: sans-serif; color: rgb(81, 93, 109)"><b>Note:</b> Mandate(OTM) limit is the maximum allowed limit per investment transaction. The actual debit amount will be as per your chosen investment amount.</p>                                                
                                                <p style="font-size: 13px; line-height: 1.4; margin-bottom: 20px; font-family: sans-serif; color: rgb(81, 93, 109)">For any queries, please write to us at <a href="mailto:'.$CI->config->item('BUINESS_EMAIL_ID').'" style="color: rgb(83, 147, 236); text-decoration: none" target="_blank">'.$CI->config->item('BUINESS_EMAIL_ID').'</a>
                                                </p>                                                 
                                                <p style="font-size: 16px; line-height: 1.4; margin: 0px; font-weight: normal; font-family: sans-serif; color: rgb(92, 107, 126); padding-top: 10px">Regards,                                                     
                                                    <br><b style="font-size: 16px; line-height: 1.4; margin: 0px; font-weight: normal; font-family: sans-serif; color: rgb(92, 107, 126)">Team '.$CI->config->item('BUINESS_FULL_NAME').'</b> </p>                                             </td>                                         </tr>                                     
                                        </tbody>
                                    </table>                                 
                                </td>                             
                            </tr>                        
                        </tbody>                     
                    </table>                 
                </td>             
                </tr>         
            </tbody>
        </table>     
    </center>
</div>';

return $template;
}


function SendMailsystem($to,$from,$message,$subject,$data) 
{
$CI = &get_instance();
$CI->load->library('email');
$config=array(

 'protocol' => 'smtp',
            'smtp_host' => 'mail.myassistancenow.com',
            'smtp_port' => '2525',
            'smtp_user' => 'info@myassistancenow.com',
            'smtp_pass' => 'Assist@1234',
            'mailtype'  => 'html', 
            'charset'   => 'utf-8'
);

$CI->email->initialize($config);
$CI->email->from($from,$CI->config->item('BUINESS_SHORT_NAME'));
$CI->email->to($to);
$CI->email->subject($subject);

if($data!="")
{

$CI->email->attach($data);
}

$CI->email->message($message);
if ($CI->email->send())
{
 return true;
}
else
 { 
    print_r($CI->email->print_debugger());
}



}

function SendMail($to,$from,$message,$subject,$data="",$title="") 
{

  
$CI = &get_instance();
$CI->load->library('email');
$config=array(
'charset'=>'utf-8',
'wordwrap'=> TRUE,
'mailtype' => 'html',
'priority'=>1,
'charset'   => 'iso-8859-1'
);
 
$CI->email->initialize($config);
$CI->email->from($from, $CI->config->item('BUINESS_SHORT_NAME'));
$CI->email->to($to);
$CI->email->subject($subject);
if($data!="")
{
$CI->email->attach($data);
$CI->email->attach($data, 'attachment',$title, 'application/pdf');
}
$CI->email->message($message);
if ($CI->email->send())
  
 return true;
else
 return false;
}


function BseDailyactity($name, $scheme_code,$amc_id,$type,$pan,$nav_value,$current_value,$current_unit,$invested_amount,$invested_unit,$status,$message,$redemption_type)
{
    $CI = &get_instance();
    $CI->load->model("Api_Model");


    $array_data=array
    (

     "name"=>$name,
      "scheme_code"=>$scheme_code, 
      "amc_id"=>$amc_id,  
      "type"=>$type,  
      "pan"=>$pan, 
      "aum_date"=>date('Y-m-d'),
      "nav_value"=>$nav_value,
      "current_value"=>$current_value,
      "current_unit"=>$current_unit,
      "invested_amount"=>$invested_amount,
      "invested_unit"=>$invested_unit,
      "invested_type"=>$redemption_type,
      "status"=>$status
    );
    $data = $CI->Api_Model->insertIntoTable("users_portfolio_daily_total", $array_data);
   
}

function SendSmslog($mobile,$message,$response)
{
    $CI = &get_instance();
    $CI->load->model("Api_Model");

    $data=array
    (
      "send_date"=>date('Y-m-d H:i:s'),
      "to_mobile"=>$mobile,
      "message"=>$message,
      "response"=>$response,
    );
    $data = $CI->Api_Model->insertIntoTable("log_sms", $data);
    return $data;
}



function SendMaillog($to_email,$cc_email,$bcc_email,$subject,$content,$response)
{
    $CI = &get_instance();
    $CI->load->model("Api_Model");

    $data=array
    (
      "send_date"=>date('Y-m-d H:i:s'),
      "to_email"=>$to_email,
      "cc_email"=>$cc_email,
      "bcc_email"=>$bcc_email,
      "subject"=>$subject,
      "content"=>$content,
      "response"=>$response
    );
    $data = $CI->Api_Model->insertIntoTable("log_email", $data);
    return $data;
}

function business_level($user_id)
{
    $CI = &get_instance();
    $CI->load->model("Api_Model");
    $bse_pass_key_data = $CI->Api_Model->business_partner_level($user_id);
    
    $array_parent_id=array();
     foreach ($bse_pass_key_data as $id1) {
                array_push($array_parent_id, $id1['user_id']);
            }
     return  $parent_id_implode=implode(",", $array_parent_id);

}


  function getSchemePerformanceByISIN($scheme_isin){
/*key=465f1f45-78bd-4968-93ee-cb7841194cdf&isin=INF209K01EN2*/
            $isin = $scheme_isin;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,"https://mfapi.advisorkhoj.com/getSchemePerformanceByISIN?key=465f1f45-78bd-4968-93ee-cb7841194cdf&isin=$isin");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,
                        "isin=$isin");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_output = curl_exec($ch);

            $json = utf8_encode($server_output);

             $server_json_data = json_decode($json);

            
            return $server_json_data->nav;exit;
           
            $server_array=array();
            if($server_output!="")
            {
            $server_json_data = json_decode($json);
            $output_array=(array)$server_json_data;  
            $nav_date_format=date('d/m/Y',strtotime($output_array['nav_date']));
            $output_array['nav_date']=$nav_date_format;
            $output_array['status']=200;

            $server_array['mf_data']=$output_array;
            }
            else{
            $output_array['status']=400;
            $server_array['mf_data']=$output_array;
            }

            $bse_data=$this->Api_Model->fetch_bse_scheme_by_isin_number($isin);
            if($bse_data!=""){
            $bse_data['status']=200;
            $server_array['bse']=$bse_data;
            }
            else{
            $bse_data['status']=400;
            $server_array['bse']=$bse_data;
            }
            $bse_sip_data=$this->Api_Model->fetch_bse_sip_scheme_by_isin_number($isin);
            if($bse_sip_data!=""){
            $bse_sip_data['status']=200;
            $server_array['sip']=$bse_sip_data;
            }
            else{
            $bse_sip_data['status']=400;
            $server_array['sip']=$bse_sip_data;
            }
            $data=json_encode($server_array);
            /*$array_database->test = 'test...';*/
            curl_close ($ch);
            return $data;
    }




  function SchemePerformanceByISIN($isin){
/*key=465f1f45-78bd-4968-93ee-cb7841194cdf&isin=INF209K01EN2*/
      

    $CI = &get_instance();
    $CI->load->model("Api_Model");
   
   
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"https://mfapi.advisorkhoj.com/getSchemePerformanceByISIN?key=465f1f45-78bd-4968-93ee-cb7841194cdf&isin=$isin");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
                    "isin=$isin");

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

       
        $server_output = curl_exec($ch);

        $json = utf8_encode($server_output);
       
        $server_array=array();
        if($server_output!="")
        {
        $server_json_data = json_decode($json);
        $output_array=(array)$server_json_data;  
        $nav_date_format=date('d/m/Y',strtotime($output_array['nav_date']));
        $output_array['nav_date']=$nav_date_format;
        $output_array['status']=200;

        $server_array['mf_data']=$output_array;
        }
        else{
        $output_array['status']=400;
        $server_array['mf_data']=$output_array;
        }

        $bse_data=$CI->Api_Model->fetch_bse_scheme_by_isin_number($isin);
        if($bse_data!=""){
        $bse_data['status']=200;
        $server_array['bse']=$bse_data;
        }
        else{
        $bse_data['status']=400;
        $server_array['bse']=$bse_data;
        }

        $bse_sip_data=$CI->Api_Model->fetch_bse_sip_scheme_by_isin_number($isin);
        if($bse_sip_data!=""){
        $bse_sip_data['status']=200;
        $server_array['sip']=$bse_sip_data;

        }
        else{
        $bse_sip_data['status']=400;
        $server_array['sip']=$bse_sip_data;
        }

        return $server_array['mf_data'];
        /*$array_database->test = 'test...';*/
        curl_close ($ch);
    }





//######################################################################################################################

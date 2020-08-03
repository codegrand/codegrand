<?php

class Api_Model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

	//For TRUMPH PROJECT
	 public function fetch_app_update_details(){
        $query = $this->db->query("select * from app_updates where is_force_update = '1' order by id desc limit 1")
            ->row_array();
        return $query;

    }
	
	
	
	public function checkmobile_exist($Mobile=''){
		 $query = $this->db->query("select * from user where mobile_no = '".$Mobile."'")->row_array();
		 //echo $this->db->last_query();exit;
		if(!empty($query)){
			return '1';
		}else {
			return '0'; 
		}
	 }
	 
	 public function checkemail_exist($Email=''){
		 $query = $this->db->query("select * from user where email_id = '".$Email."'")->row_array();
		 //echo $this->db->last_query();exit;
		if(!empty($query)){
			return '1';
		}else {
			return '0'; 
		}
	 }
	 
	 
	
	 public function checklogin($Mobile='',$Password=''){
		 $query = $this->db->query("select * from user where mobile_no = '".$Mobile."' AND password = '".$Password."'");
		//echo $this->db->last_query();exit;
        return $query->row_array();
	 }
	
	 public function fetch_customer_details($mobile='',$email_id=''){
        $query = $this->db->query("select * from user where mobile_no = '".$mobile."' AND email_id = '".$email_id."'");
		//echo $this->db->last_query();exit;
        return $query->row_array();
    }
	
	public function fetchUserDetailFromMobile($mobile=''){
        $query = $this->db->query("select * from user where mobile_no = '".$mobile."'");
		//echo $this->db->last_query();exit;
        return $query->row_array();
    }
	
	public function fetchUserDetailFromEmail($email=''){
        $query = $this->db->query("select * from user where email_id = '".$email."'");
		//echo $this->db->last_query();exit;
        return $query->row_array();
    }
	
	public function getUserRegisteredVehicleList($user_id=''){
		
		$query = $this->db->query("select * from users_vehicles where user_id = '".$user_id."' AND is_active_vehicle='1'");
		//echo $this->db->last_query();exit;
        return $query->result();
	}
	
	public function getUserDeletedVehicleList($user_id=''){
		
		$query = $this->db->query("select * from users_vehicles where user_id = '".$user_id."' AND is_active_vehicle='0'");
		//echo $this->db->last_query();exit;
        return $query->result();
	}
	
	public function getUserRegisteredVehicleDetail($user_id='',$vehicle_id=''){
		
		 $query = $this->db->query("select * from users_vehicles where user_id = '".$user_id."' AND id = '".$vehicle_id."'");
		//echo $this->db->last_query();exit;
        return $query->row_array();
	}
	
	 public function check_user_id_availability($userid=''){
        $query = $this->db->query("select * from user where user_id = '".$userid."'");
		//echo $this->db->last_query();exit;
        return $query->row_array();
    }
	
	 public function get_customer_details($mobile='',$email=''){
        $query = $this->db->query("select * from user where mobile_no = '".$mobile."' or email_id = '".$email."'");
		//echo $this->db->last_query();exit;
        return $query->row_array();
    }







   /* public function fetch_customer_details($pan_no){
        $query = $this->db->query("select 
            name,pan,mobile,email,type_id,zone,branch,active,user_password,is_password,user_pass,father_name,gender,date_of_birth,marital_status,aadhaar,emp_code,street_1,street_2,street_3,city,pincode,state,country,phone_office,phone_residence,email_verified,client_code,email_verified,email_authcode,mobile_verified,mobile_otp,bse_customer,steps_completed,contact_info,bank_info,signature_info,tax_status,bank_name,bank_code,bank_mode,bank_branch,bank_address,bank_city,bank_state,cheque,cheque_string,bank_account_number,bank_account_holder_name,bank_account_type,bank_ifsc_code,bank_micr_code,default_bank,otm_flag,otm,otm_approved,otm_flag1,otm1,otm_approved1,otm_flag2,otm2,otm_approved2,otm_flag3,otm3,otm_approved3,bse_active,source_of_wealth,annual_income,annual_income_code,occupation,occupation_code,political,political_code,client_holding,second_app_name,kyc,imei_number,is_active,is_check_tnc,aof_pdf_link,is_check_privacy_policy
         from users where pan ='$pan_no'");
        return $query->row_array();

    }
*/


   

      public function fetchReedmptions($pan_no){

        $query = $this->db->query("SELECT schemecode FROM `bse_order_entry` WHERE `order_status` = 'Purchase' and clientcode=$pan_no")->result();
//print_r($query);die();

    }
    
    public function getProfile($pan_no){
        $query = $this->db->query(
        "select id,name,pan,mobile,email,gender,date_of_birth,marital_status,aadhaar,
        street_1,street_2,street_3,city,pincode,state,bank_account_holder_name,bank_name,bank_code,
        bank_branch,bank_address,bank_city,bank_state,bank_account_number,
        bank_account_type,bank_ifsc_code,bank_micr_code,nominee_name,
        nominee_relationship,nominee_dob,tax_status,client_holding,gender,country,state,place_of_birth,occupation_code,annual_income_code,source_of_wealth_code,political,address_type,bank_account_type,bank_state,occupation_code,annual_income_code,source_of_wealth_code,political,second_app_name,second_app_pan,third_app_name,third_app_pan

        from users 
        where pan ='$pan_no'");
        return $query->row_array();

    }

    

     public function check_login_data($Pan,$password)
    {
       if($password==""){
         $query = $this->db->query("select * from users where pan ='$Pan' and bse_active=1");
       }
       else{
         $query = $this->db->query("select * from users where pan ='$Pan' and user_password='$password'");
       }
    return $query->row_array();

    }



    public  function fetch_tnc_deatils($pan,$type)
    {
    $query = $this->db->query("select * from master_tnc_privacy where active =1 and type=$type");
    return $query->row();
    }
    
    public function question_data(){
        $query = $this->db->query("select *  from question");
        return $query->result_array();

    }
      public function question_option_data($id,$name){

        $query = $this->db->query("select * from question_option where question_id=$id");
        $result= $query->result_array();

        $result_array_data=array();
        foreach ($result as $key => $value) {
          $result_array_data[]=array(
             "id"=>$value['id'],
             "option_number"=>$key+1,
             "option"=>$value['option'],
             "marks"=>$value['marks']
            
          );
        }
      
    $question_array=array(
        "QuestionNo"=>$id,
        "Question"=>$name,
        "Option"=>$result_array_data

    );
    return $question_array;
    }

    public function fetch_state_master_details($state_name){
        $query = $this->db->query("select * from state_master where code ='$state_name'");
        return $query->row_array();

    }
    public function fetch_order_details($order_id){
        $query = $this->db->query("select * from bse_order_entry where id ='$order_id'");
        return $query->row_array();

    }
    public function fetch_save_robo_plan($pan,$robo_type){
        $query = $this->db->query("select * from bse_robo_plan where pan_number ='$pan' and robo_type='$robo_type'");
        return $query->result_array();

    }

    public function fetch_save_single_robo_plan($bseroboplanid){
        $query = $this->db->query("select * from bse_robo_plan where id ='$bseroboplanid'");
        return $query->result_array();

    }
    public function generate_pdf($pan_number,$AccessFrom)
    {

$fetch_customer_details = $this->fetch_customer_details($pan_number);
$fetch_state_details = $this->fetch_state_master_details($fetch_customer_details['state']);
if($fetch_customer_details['client_holding']=="SI"){
    $client_holding="Single";
}
if($fetch_customer_details['client_holding']=="JO"){
 $client_holding="Joint";   
}
elseif($fetch_customer_details['client_holding']=="AS"){
 $client_holding="Anyone Or Survivor";   
}
else
{
       $client_holding="Single";
}



if($fetch_customer_details['bank_account_type']=="SB"){
    $account_type="SAVINGS";
}
if($fetch_customer_details['bank_account_type']=="CB"){
 $account_type="CURRENT";   
}
elseif($fetch_customer_details['bank_account_type']=="NE"){
 $account_type="NRE";   
}
elseif($fetch_customer_details['bank_account_type']=="NO"){
 $account_type="NRO";   
}

else
{
       $account_type="SAVINGS";
}
$bse_key=$this->bse_key_data();
$memberid=$bse_key['memberid'];
$file_name="AOF_".$memberid.'_'.$pan_number;

 ob_start();
 $this->load->library('Tcpdf/Tcpdf.php');
     
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
/*$pdf->SetAuthor('Nicola Asuni');
*/
$pdf->SetTitle($file_name);
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(5, 5, 5);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 5);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

//$signature_string = $fetch_customer_details['signature_string'];
$signature_string = $fetch_customer_details['signature_string'];
if($AccessFrom=="WEB"){
$signature_image='<img src="'.$signature_string.'">';    



}
else
{
$signature_image='<img src="data:image/jpg;base64,'.$signature_string.'">';

}

// ---------------------------------------------------------
// set font
$pdf->SetFont('helvetica', '', 8);
// add a page
$pdf->AddPage();
// set some text to print
$html = '
<style>
    .textcenter {text-align:center;}
    .textright {text-align:right;}
    .border, .boxtable td {border:1px solid #000;}
</style>

<table cellpadding="0" border="0" cellspacing="0" style="color: #000; font-size: 8.5pt; line-height:13px; color:#000;">  
    <tr>
        <td>
            <table cellpadding="4" border="0" cellspacing="0" class="textcenter border">
                <tr>
                    <td><img src="images/BSE-Star-MF-logo.jpg" width="120"></td>
                </tr>
            </table>
            <table cellpadding="5" border="0" cellspacing="0" class="boxtable">
                <tr>
                    <td width="25%">Broker/Agent Code ARN: </td>
                    <td width="25%">ARN-'.$bse_key['arn_number'].'</td>
                    <td width="15%">SUB-BROKER </td>
                    <td width="10%"></td>
                    <td width="15%">EUIN</td>
                    <td width="10%">'.$bse_key['euin'].'</td>
                </tr>
                <tr>
                    <td colspan"6" width="100%"><b>Unit Folder Information</b></td>
                </tr>
                <tr>
                    <td width="25%"><b>Name of the First Applicant : </b> </td>
                    <td colspan"6" width="75%">'.$fetch_customer_details['name'].'</td>
                </tr>
            </table>
            <table cellpadding="5" border="0" cellspacing="0" class="boxtable">
                <tr>
                    <td width="35%">PAN Number : '.$fetch_customer_details['pan'].'</td>
                    <td width="30%">KYC : </td>
                    <td width="35%">Date Of Birth : '.date('d-m-Y',strtotime($fetch_customer_details['date_of_birth'])).'</td>
                </tr>
                <tr>
                    <td colspan="2">Name of Guardian:</td>
                    <td>PAN: </td>
                </tr>
                <tr>
                    <td colspan="3"><b>Contact Address:</b> '.$fetch_customer_details['street_1'].'</td>
                </tr>
                <tr>
                    <td colspan="3"> '.$fetch_customer_details['street_2'].' </td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;'.$fetch_customer_details['street_3'].' </td>
                </tr>               
            </table>
            <table cellpadding="5" border="0" cellspacing="0" class="boxtable">
                <tr>
                    <td width="25%">City: '.$fetch_customer_details['city'].'</td>
                    <td width="25%">Pincode: '.$fetch_customer_details['pincode'].'  </td>
                    <td width="25%">State: '.$fetch_state_details['name'].' </td>
                    <td width="25%">Country: '.$fetch_customer_details['country'].'</td>
                </tr>
            </table>
            <table cellpadding="5" border="0" cellspacing="0" class="boxtable">
                <tr>
                    <td width="25%">Tel.(Off): </td>
                    <td width="37%">Tel.(Res): </td>
                    <td width="38%">Email: '.$fetch_customer_details['email'].'</td>
                </tr>
                <tr>
                    <td>Fax(Off): </td>
                    <td>Fax(Res): </td>
                    <td>Mobile: '.$fetch_customer_details['mobile'].'</td>
                </tr>
                <tr>
                    <td colspan="2">Mode of Holding: '.strtoupper($client_holding).'</td>
                    <td>Occupation: '.$fetch_customer_details['occupation'].'</td>
                </tr>
                <tr>
                    <td><b>Name of the Second Applicant :</b></td>
                    <td colspan="2"></td>
                </tr>
            </table>
            <table cellpadding="5" border="0" cellspacing="0" class="boxtable">
                <tr>
                    <td width="35%">PAN Number : </td>
                    <td width="30%">KYC : </td>
                    <td width="35%">Date Of Birth : </td>
                </tr>
            </table>
            <table cellpadding="5" border="0" cellspacing="0" class="boxtable">
                <tr>
                    <td width="25%"><b>Name of the Third Applicant :</b></td>
                    <td colspan="2" width="75%"></td>
                </tr>
            </table>
            <table cellpadding="5" border="0" cellspacing="0" class="boxtable">
                <tr>
                    <td width="35%">PAN Number : </td>
                    <td width="30%">KYC : </td>
                    <td width="35%">Date Of Birth : </td>
                </tr>
            </table>
            <table cellpadding="5" border="0" cellspacing="0" class="boxtable">
                <tr>
                    <td width="100%" colspan="3">Other Details of Sole / 1st Applicant</td>
                </tr>
                <tr>
                    <td colspan="3">Overseas Address(In case of NRI Investor): </td>
                </tr>
                <tr>
                    <td width="25%">City: </td>
                    <td width="37%">Pincode: </td>
                    <td width="38%">Country: </td>
                </tr>
                <tr>
                    <td colspan="3"><b>Bank Mandate Details</b></td>
                </tr>
                <tr>
                    <td colspan="2">Name of Bank: '.$fetch_customer_details['bank_name'].'</td>
                    <td>Branch: '.$fetch_customer_details['bank_branch'].'</td>
                </tr>
                <tr>
                    <td>A/C No.: '.$fetch_customer_details['bank_account_number'].' </td>
                    <td>A/C Type: '.$account_type.'</td>
                    <td>IFSC Code: '.$fetch_customer_details['bank_ifsc_code'].' </td>
                </tr>
                <tr>
                    <td colspan="3"><b>Bank Address:</b> '.$fetch_customer_details['bank_address'].'</td>
                </tr>
            </table>
            <table cellpadding="5" border="0" cellspacing="0" class="boxtable">
                <tr>
                    <td width="25%">City: '.$fetch_customer_details['bank_city'].' </td>
                    <td width="25%">Pincode:  '.$fetch_customer_details['pincode'].'</td>
                    <td width="25%">State: '.$fetch_state_details['name'].'</td>
                    <td width="25%">Country: '.$fetch_customer_details['country'].'</td>
                </tr>
                <tr>
                    <td colspan="4"><b>Nomination Details</b></td>
                </tr>
            </table>
            <table cellpadding="5" border="0" cellspacing="0" class="boxtable">
                <tr>
                    <td width="62%" colspan="2">Nominee Name: '.$fetch_customer_details['nominee_name'].' </td>
                    <td width="38%">Relationship: '.$fetch_customer_details['nominee_relationship'].'</td>
                </tr>
                <tr>
                    <td colspan="3">Guardian Name(If Nominee is Minor): </td>
                </tr>
                <tr>
                    <td colspan="3"><b>Nominee Address:Same As Above</b></td>
                </tr>
                <tr>
                    <td width="25%">City:</td>
                    <td width="37%">Pincode: </td>
                    <td width="38%">State: </td>
                </tr>
                <tr>
                    <td colspan="3"><u>Declaration and Signature</u><br>I/We confirm that details provided by me/us are true and correct. The ARN holder has disclosed to me/us all the commission (In the form of trail commission or any other mode), payable to him for the different competing Schemes of various Mutual Fund From amongst which the scheme is being recommended to me/us</td>
                </tr>
            </table>
            <table cellpadding="5" border="0" cellspacing="0" class="boxtable">
                <tr>
                    <td width="50%">Date :</td>
                    <td width="50%">Place :</td>
                </tr>
            </table>
            <table cellpadding="5" border="0" cellspacing="0" class="boxtable">
                <tr>
                    <td width="25%">'.$signature_image.'</td>
                    <td width="37%"><img src="" width="120"></td>
                    <td width="38%"><img src="" width="120"></td>
                </tr>
                <tr>
                    <td>1st applicant Signature :</td>
                    <td>2nd applicant Signature : </td>
                    <td>3rd applicant Signature :</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
';
    $pdf->writeHtml($html);
    $target_path=FCPATH."uploads/user_data/aof/";
    
    $pdf->Output($target_path.$file_name.'.pdf', 'F');
    $pdfFile='uploads/user_data/aof/'.$file_name.'.pdf';
   
    $updatedArr=array(
    'aof_pdf_link'=>$pdfFile
    );
    $client_where = array('pan' => $pan_number);
    $this->Api_Model->updateTable('users', $updatedArr, $client_where);


    }

    public function remove_special_chacter_string($string){
       
    $string = str_replace(array('[\', \']'), '', $string);
    $string=preg_replace('/[^a-zA-Z0-9_ -]/s','',$string);
    $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '', $string);
    $string = htmlentities($string, ENT_COMPAT, 'utf-8');
    $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '', $string );
   // $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , ' ', $string);
    return $string;

    }


    /*
    public function fetch_schemes(){
        $query = $this->db->query("select * from amc_list")
            ->result_array();
        return $query;
    }*/

    public function fetch_schemes($investment_type){
        if($investment_type=="SIP"){
            $sql="select al.amc_id,al.amc_name from bse_sip_schemes as bse,amc_list as al where al.amc_id=bse.amc_id and sip_status=1 and sip_frequency='MONTHLY' AND bse.sip_transaction_mode='DP' AND ( bse.sip_minimum_installment_amount >=500 AND bse.sip_maximum_installment_amount <=200000) group by al.amc_id";
        }
        else
        {
            $sql="select al.amc_id,al.amc_name from bse_schemes as bs,amc_list as al where al.amc_id=bs.amc_id and bs.scheme_plan ='NORMAL' and bs.dividend_reinvestment_flag='Z' and bs.purchase_transaction_mode='DP'   and bs.purchase_allowed='Y' and (bs.minimum_purchase_amount >= 500  and bs.minimum_purchase_amount < 200000) group by al.amc_id";
        }

        //"select * from amc_list"
        $query = $this->db->query($sql)
            ->result_array();

        return $query;
    }

     public function fetch_schemes_elss($investment_type){
        if($investment_type=="SIP"){
            $sql="select al.amc_id,al.amc_name from bse_sip_schemes as bse,amc_list as al where al.amc_id=bse.amc_id and sip_status=1 and sip_frequency='MONTHLY' AND  bse.scheme_type='ELSS' and bse.sip_transaction_mode='DP' AND ( bse.sip_minimum_installment_amount >=500 AND bse.sip_maximum_installment_amount <=200000) group by al.amc_id";
        }
        else
        {
            $sql="select al.amc_id,al.amc_name from bse_schemes as bs,amc_list as al where al.amc_id=bs.amc_id and  bs.scheme_type='ELSS' and bs.scheme_plan ='NORMAL' and bs.dividend_reinvestment_flag='Z' and bs.purchase_transaction_mode='DP'   and bs.purchase_allowed='Y' and (bs.minimum_purchase_amount >= 500  and bs.minimum_purchase_amount < 200000) group by al.amc_id";
        }

        //"select * from amc_list"
        $query = $this->db->query($sql)
            ->result_array();

        return $query;
    }
    public function fetch_bse_scheme_by_isin_number($isin){
        $query = $this->db->query("SELECT 
a.images_name,b.unique_no,b.scheme_code,b.rta_scheme_code,b.amc_scheme_code,isin,
b.amc_code,b.scheme_type,b.scheme_plan,b.scheme_name,b.purchase_allowed,b.purchase_transaction_mode,b.minimum_purchase_amount,b.additional_purchase_amount,
b.redemption_transaction_mode,b.maximum_purchase_amount FROM `bse_schemes` b 
inner join amc_list a on a.amc_id=b.amc_id WHERE b.`isin` = '$isin' AND `minimum_purchase_amount` < '200000' limit 1 ")
            ->row_array();
        return $query;
        

    }
   /* public function fetch_bse_scheme_by_isin_number($isin){
        $query = $this->db->query("SELECT unique_no,scheme_code,rta_scheme_code,amc_scheme_code,isin,amc_code,scheme_type,scheme_plan,scheme_name,purchase_allowed,purchase_transaction_mode,minimum_purchase_amount,additional_purchase_amount,redemption_transaction_mode,maximum_purchase_amount FROM `bse_schemes` WHERE `isin` = '$isin' AND `minimum_purchase_amount` < '200000'")
            ->row_array();
        return $query;

    }*/
    /* public function fetch_bse_sip_scheme_by_isin_number($isin){
        $query = $this->db->query("SELECT amc_code,amc_name,scheme_code,scheme_name,sip_transaction_mode,sip_frequency,sip_dates,sip_minimum_installment_amount,sip_maximum_installment_amount,sip_multiplier_amount,sip_minimum_installment_numbers,sip_maximum_installment_numbers,scheme_isin,is_robo FROM `bse_sip_schemes` WHERE `scheme_isin` = '$isin' AND `sip_frequency` = 'MONTHLY' AND `sip_minimum_installment_amount` < '200000' ")
            ->row_array();
        return $query;

    }*/

      public function fetch_bse_sip_scheme_by_isin_number($isin){
        $query = $this->db->query("SELECT amc_list.images_name, bse_sip_schemes.amc_code,bse_sip_schemes.amc_name,bse_sip_schemes.scheme_code,bse_sip_schemes.scheme_name,bse_sip_schemes.sip_transaction_mode,bse_sip_schemes.sip_frequency,bse_sip_schemes.sip_dates,bse_sip_schemes.sip_minimum_installment_amount,bse_sip_schemes.sip_maximum_installment_amount,bse_sip_schemes.sip_multiplier_amount,bse_sip_schemes.sip_minimum_installment_numbers,bse_sip_schemes.sip_maximum_installment_numbers,bse_sip_schemes.scheme_isin,bse_sip_schemes.is_robo 
            FROM `bse_sip_schemes` inner join `amc_list` on bse_sip_schemes.amc_id=amc_list.amc_id 
            WHERE `scheme_isin` = '$isin' AND `sip_frequency` = 'MONTHLY'  AND  `sip_minimum_installment_amount` < '200000' limit 1 ")
            ->row_array();
        return $query;

    }

    public function get_typeby_amc_fetch_schemes($amc_id){


        $query = $this->db->query("select scheme_type from bse_schemes where amc_id=$amc_id and scheme_plan ='NORMAL' and purchase_transaction_mode='DP' AND `dividend_reinvestment_flag` = 'Z'  and purchase_allowed='Y' and (minimum_purchase_amount >= 500  and minimum_purchase_amount < 200000) group by  scheme_type")
            ->result();
        return $query;

    }

    public function get_typeby_amc_sip_fetch_schemes($amc_id){


        $query = $this->db->query("select scheme_type from bse_sip_schemes where amc_id=$amc_id and sip_transaction_mode='DP' AND ( sip_minimum_installment_amount >=500 AND  sip_frequency='MONTHLY' AND sip_maximum_installment_amount <=200000)  group by  scheme_type")
            ->result();
        return $query;

    }



    public function sendMailAOFFile()
    {



    }

    public function fetch_schemes_type($amc_id,$type,$investment_type){



        if($type=="ALL"){
            if($investment_type=="SIP"){
                $sql="select * from bse_schemes where amc_id=$amc_id  and scheme_plan ='NORMAL' and purchase_transaction_mode='DP' and sip_frequency='MONTHLY' AND dividend_reinvestment_flag='Z' and purchase_allowed='Y' and (minimum_purchase_amount >= 500  and minimum_purchase_amount < 200000)";
            }
            else
            {
                $sql="select * from bse_schemes where amc_id=$amc_id  and scheme_plan ='NORMAL'  and dividend_reinvestment_flag='Z' and purchase_transaction_mode='DP' and purchase_allowed='Y' and (minimum_purchase_amount >= 500  and minimum_purchase_amount < 200000)  ";
            }

        }

        /*select scheme_type from bse_schemes where amc_id=$amc_id and scheme_plan ='NORMAL' and purchase_transaction_mode='DP' and sip_flag='Y'  and purchase_allowed='Y' and (minimum_purchase_amount >= 500  and minimum_purchase_amount < 200000) group by  scheme_type*/
        else {
            if($investment_type=="SIP"){
                $sql="select * from bse_schemes where amc_id=$amc_id and scheme_plan ='NORMAL' and dividend_reinvestment_flag='Z'  and sip_frequency='MONTHLY' AND purchase_transaction_mode='DP' and purchase_allowed='Y' and (minimum_purchase_amount >= 500  and minimum_purchase_amount < 200000)";
            }else{
                $sql="select * from bse_schemes where amc_id=$amc_id  and scheme_plan ='NORMAL' and dividend_reinvestment_flag='Z' and purchase_transaction_mode='DP' and purchase_allowed='Y' and (minimum_purchase_amount >= 500  and minimum_purchase_amount < 200000) and scheme_type='$type'";
            }
        }


        $query = $this->db->query($sql)
            ->result_array();

        return $query;

    }



     public function fetch_schemes_type_sip($amc_id,$type,$investment_type){

/*select *  from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND `minimum_purchase_amount` <= '$max_count' AND `dividend_reinvestment_flag` = 'Z' AND  (`amc_id` != '10' AND `amc_id` != '15' and amc_id!='16' and amc_id!='9' and amc_id!='12')  AND scheme_type='EQUITY'  group by amc_id ORDER by rand()



/*select *  from bse_sip_schemes WHERE `is_robo` = 'Y' and scheme_type='EQUITY' and sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,`sip_dates`)   group by amc_id ORDER by rand()*/
      
        if($type=="ALL"){
            if($investment_type=="SIP"){
                $sql="select * from bse_sip_schemes where amc_id=$amc_id and sip_transaction_mode='DP' and sip_status=1 AND ( sip_minimum_installment_amount >=500 AND sip_frequency='MONTHLY' AND  sip_maximum_installment_amount <=200000)";
            }
            else
            {
                $sql="select * from bse_sip_schemes where amc_id=$amc_id and sip_transaction_mode='DP' and sip_status=1 AND ( sip_minimum_installment_amount >=500  AND sip_frequency='MONTHLY' AND  sip_maximum_installment_amount <=200000) ";
            }

        }
        /*select scheme_type from bse_sip_schemes where amc_id=$amc_id and sip_transaction_mode='DP' and `sip_frequency` = 'MONTHLY' AND ( sip_minimum_installment_amount >=500 AND  sip_maximum_installment_amount <=200000)  group by  scheme_type*/

        else {
            if($investment_type=="SIP"){

                $sql="select * from bse_sip_schemes where amc_id=$amc_id and scheme_type='$type' and sip_status=1 AND  sip_transaction_mode='DP'  AND (sip_minimum_installment_amount >=500 AND sip_frequency='MONTHLY' AND  sip_maximum_installment_amount <=200000) ";
            }else{
                $sql="select * from bse_sip_schemes where amc_id=$amc_id and scheme_type='$type'  and sip_status=1 and sip_transaction_mode='DP'  AND ( sip_minimum_installment_amount >=500 AND  sip_frequency='MONTHLY' AND sip_maximum_installment_amount <=200000)";
            }
        }
        $query = $this->db->query($sql)
            ->result_array();

        return $query;

    }

    public function insert_customer_details($array)
    {
        $query = $this->db->insert('users',$array);
        $last_id = $this->db->insert_id();
        return $last_id;

    }

    public function insert_bse_robo_data($insert_array_robo)
    {
        $query = $this->db->insert('bse_robo_plan',$insert_array_robo);
        $last_id = $this->db->insert_id();
        return $last_id;

    }


    public function insert_bse_order_details($array)
    {
        $query = $this->db->insert('bse_order_entry',$array);
        $last_id = $this->db->insert_id();
        return $last_id;

    }

    public function insert_order($array)
    {
        // echo '<pre>'; print_r($array);die;
        /* $query = $this->db->insert('order',$array);
        $last_id = $this->db->insert_id();*/

        $this->db->insert('order', $array);
        $insert_id = $this->db->insert_id();

        return  $insert_id;

        // return $last_id;

    }



    public function update_user_data($mobile,$data){
        $this->db->where('mobile_no', $mobile);
        $this->db->update('user', $data);
			
		
    } 
    public function update_order_update_data($id,$data){
        $this->db->where('id', $id);
        $this->db->update('bse_order_entry', $data);
        

    } 


    public function check_email_data($email,$pin){
        $query = $this->db->query("select * from user where email_id ='".$email."' and email_pin = '".$pin."' ")
            ->row_array();
        return $query;

    }

    public function check_mobile_data($mobile,$otp){
        $query = $this->db->query("select * from user where mobile_no ='".$mobile."' and mobile_otp = '".$otp."' ")
            ->row_array();
        return $query;

    }
   
    public function bse_key_data(){
    
        $query = $this->db->query("select * from bse_key where bse_live=1")
            ->row_array();
        return $query;

    }

    public function fetch_master_risk_allocation($risk_type)
    {
        $sql="select type_of_funds,allocated_percent from master_risk_allocation   where risk_type='$risk_type' and  is_robo_active=1";
        $query = $this->db->query($sql)
            ->result_array();
        return $query;


    }

    public function fetch_new_schemes($risk_type)
    {
        $sql="SELECT * FROM `new_schemes` WHERE `is_visible_robo_advisory` = 'TRUE'  ";
        $query = $this->db->query($sql)
            ->result_array();
        return $query;


    }
    public function fetch_data_sip($pan){
        $query = $this->db->query("select * from bse_order_entry where order_type='SIP' and bse_reg_id!='0' and clientcode='$pan'")
            ->result_array();
        return $query;

    }

    public function fetch_data_lumsum_array($pan){
        $today_date=date('Y-m-d');
        $query = $this->db->query("
            SELECT * 
            FROM `bse_order_entry` 
            WHERE `order_number` IS NULL 
            AND `clientcode` = '$pan' 
            AND `order_type` = 'SIP'  
            AND `order_number` IS NULL");
        /* echo "SELECT * FROM `bse_order_entry` WHERE `order_number` IS NULL AND `clientcode` = '$pan' AND `order_type` = 'SIP' AND `reg_number` != '0'  AND `order_number` IS NULL";exit();*/

        foreach ($query->result_array() as $row)
        {
            $insert_order_data_id= $row['id'];
            $bse_REG_ID= $row['bse_reg_id'];
            $pan= $row['clientcode'];
            $this->GettingOrderdata($insert_order_data_id,$pan,$bse_REG_ID);

        }
    }


   


    public function fetch_order_sip_data($pan){
        $today_date=date('Y-m-d');
        $query = $this->db->query("
            SELECT 
            * 
            FROM `bse_order_entry` 
            WHERE `order_number` IS NULL 
            AND `clientcode` = '$pan' 
            AND `order_type` = 'SIP'  
            AND `order_number` IS NULL 
            AND  order_status='Pending' 
            AND reg_number!=0 
            AND startdate='$today_date'");

        /* echo "SELECT * FROM `bse_order_entry` WHERE `order_number` IS NULL AND `clientcode` = '$pan' AND `order_type` = 'SIP' AND `reg_number` != '0'  AND `order_number` IS NULL";exit();*/

        foreach ($query->result_array() as $row)
        {
            $insert_order_data_id= $row['id'];
            $bse_REG_ID= $row['bse_reg_id'];
            $pan= $row['clientcode'];
            $this->GettingOrderdata($insert_order_data_id,$pan,$bse_REG_ID);

        }


        $query = $this->db->query("select * from bse_order_entry where   clientcode='$pan'  and  reg_number!=0  and  reg_number!=0  and order_type='SIP' and order_status!='Cancel' ")
            ->result_array();
        return $query;
    }



    public function fetch_data_lumsum($pan){


        $query = $this->db->query("
            select 
            schemecode,order_number,order_status,Installmentamount,reg_number,id
            from bse_order_entry 
            where   clientcode='$pan' 
            and order_number!='' and 
            order_type='Lumpsum' AND `order_status`='pending'")
            ->result_array();
        return $query;
    }






    public function fetch_count($table_name,$col_name1,$col_name2)
    {
        $sql="SELECT count(scheme_id)as total FROM $table_name WHERE `is_visible_robo_advisory` = 'TRUE' AND `scheme_type` = '$col_name1' AND  $col_name2!=''";
        $query = $this->db->query($sql)
            ->row_array();
        return $query;


    }


    public function fetch_new_schemes_type($table_name,$col_name1,$col_name2,$max_count,$amount)
    {
        $sql="SELECT * FROM $table_name WHERE `is_visible_robo_advisory` = 'TRUE' AND `scheme_type` = '$col_name1' and $col_name2!='' order by $col_name2 DESC limit $max_count ";

        $query = $this->db->query($sql)
            ->result_array();
        return $query;


    }
     
    public function activeAMCId(){
        $sql="SELECT amc_id FROM amc_list WHERE `status_lumsum` = 1";
        $query = $this->db->query($sql)
            ->result_array();

        //echo $query[0]['amc_id'];
        //echo "<pre>";print_r($query);exit;

        $result1 = array();
        for($i=0;$i<count($query);$i++){
            $result1[$i] = $query[$i]['amc_id'];
        }

        return implode(',', array_values($result1));
    }

    
    public function new_risk_master_schemes($risk_type,$max_count,$amount){
        $isin_number = "";
        $risk="SELECT allocated_percent,type_of_funds FROM `master_risk_allocation` WHERE `is_robo_active` = '1' AND `risk_type` = '$risk_type'";


        $risk_array= $this->db->query($risk)
            ->result_array();
        $EQUITY_PER=$risk_array[0]['allocated_percent'];
        $BALANCED_PER=$risk_array[1]['allocated_percent'];

        $amc_ids = $this->activeAMCId();

        if($risk_type=="AGGRESSIVE"){
            $sql="select * from (select *,(select images_name from amc_list where amc_id=bse_schemes.amc_id)as image_name  from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL'  AND `dividend_reinvestment_flag` = 'Z' AND  `amc_id` IN (".$amc_ids.") AND scheme_type='EQUITY' ORDER by rand() limit 5) as j order by purchase_amount_multiplier DESC";

            // $sql1="select *  from bse_schemes WHERE `is_robo` = 'Y' and `sip_flag` = 'Y' and scheme_type='BALANCED' ORDER by rand() limit 1";

            $query= $this->db->query($sql)
                ->result_array();

            $scheme_all_amount =round($amount/3);

            $mutiplier_total_amout = $amount;
            $i=0;
            foreach ($query as $key => $value) {
                $isin=$value['isin'];
                $isin_id=$value['id'];
    $isin_fetch_data= $this->getSchemePerformanceByISINSchemedatabase_get($isin_id);               
                /**//*$isin_fetch_data = json_decode($isin_fetch_data);*/

                if(!empty($isin_fetch_data) && $i < 3){
                   // continue;
                
                    $multiplier_split = 1;
                
                    if($key==0){
                        $multiplier_split = 3;
                    }elseif($key==1){
                        $multiplier_split = 2;
                    }

                    $multiplier_amount=$value['purchase_amount_multiplier'];
                    $scheme_final_amount = $this->calculate_multiplier($key,$multiplier_split,$multiplier_amount,$scheme_all_amount,$mutiplier_total_amout);

                    $mutiplier_total_amout = $mutiplier_total_amout-$scheme_final_amount;
                   

                    if(!empty($isin_fetch_data)){
                        /*$isin_fetch_data->nav_date_format = date( 'd/m/Y',strtotime($isin_fetch_data->nav_date));
*/
                    }

                    $query[$key]['isin_fetch_data']= $isin_fetch_data;
                    //echo "<pre>"; print_r($isin_fetch_data); exit;
                    $query[$key]['image_name']= $this->getImageName($query[$key]['amc_id']);;


                    if($key==0){
                        $scheme_all_amount = floor($mutiplier_total_amout/2);
                    }elseif($key==1){
                        $scheme_all_amount = $mutiplier_total_amout;
                    }


                    $query[$key]['scheme_allocated_amount']=$scheme_final_amount;
                    $query[$key]['isin']=$isin;

                    $i++;
                }else{
                    unset($query[$key]);
                }

               
               
              
               
               
            }

            $count_array = count($query);
            if($count_array < 3){
                $calculate = $amount/$count_array;
                foreach($query as $key => $row){
                    $query[$key]['scheme_allocated_amount']=$calculate;
                }
            }
            $result= $query;

        }

        if($risk_type=="MODERATE"){

            $ALLOCATED_BALANCED_AMOUNT=($BALANCED_PER * $amount)/100;
            $ALLOCATED_BALANCED_AMOUNT_MOD = fmod($ALLOCATED_BALANCED_AMOUNT,1000);
            $ALLOCATED_EQUITY_AMOUNT=($EQUITY_PER * $amount)/100;
            //Check 1000 multiplier
            if($ALLOCATED_BALANCED_AMOUNT_MOD>0){
                $DIFF_ALLOC_BALANCE_AMOUNT = 1000-$ALLOCATED_BALANCED_AMOUNT_MOD;
                $ALLOCATED_BALANCED_AMOUNT = $ALLOCATED_BALANCED_AMOUNT+ $DIFF_ALLOC_BALANCE_AMOUNT;
                $ALLOCATED_EQUITY_AMOUNT= $ALLOCATED_EQUITY_AMOUNT-$DIFF_ALLOC_BALANCE_AMOUNT;
            }

            
            

           if($ALLOCATED_BALANCED_AMOUNT< 5000)
            {
                $diff_amount=5000-$ALLOCATED_BALANCED_AMOUNT;
                $ALLOCATED_BALANCED_AMOUNT=$ALLOCATED_BALANCED_AMOUNT+$diff_amount;
                $ALLOCATED_EQUITY_AMOUNT=$ALLOCATED_EQUITY_AMOUNT - $diff_amount;

            }else if($ALLOCATED_EQUITY_AMOUNT< 5000){

                $diff_amount=5000-$ALLOCATED_EQUITY_AMOUNT;
                $ALLOCATED_EQUITY_AMOUNT=$ALLOCATED_EQUITY_AMOUNT+$diff_amount;
                $ALLOCATED_BALANCED_AMOUNT=$ALLOCATED_BALANCED_AMOUNT - $diff_amount;
            }

            if($ALLOCATED_EQUITY_AMOUNT>=10000){
                $limit = 2;
            }else{
                 $limit = 1;
            }


            $amc_ids = $this->activeAMCId();

            $sql="select * ,(select images_name from amc_list where amc_id=bse_schemes.amc_id)as image_name  from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL'  AND `dividend_reinvestment_flag` = 'Z' AND `amc_id` IN (".$amc_ids.") AND scheme_type='EQUITY' ORDER by rand() limit 5";

            /**/

            
            $query= $this->db->query($sql)
                ->result_array();
            $i = 0;
            foreach ($query as $key => $value) {
                $scheme_allocated_amount =round(($ALLOCATED_EQUITY_AMOUNT/$limit),0);
                $isin=$value['isin'];
                $isin_id=$value['id'];

                $isin_fetch_data= $this->getSchemePerformanceByISINSchemedatabase_get($isin_id); 
                /**//*$isin_fetch_data = json_decode($isin_fetch_data);*/
               
                if(!empty($isin_fetch_data) && $i < $limit){

                    /*if(!empty($isin_fetch_data)){
                     $isin_fetch_data->nav_date_format = date( 'd/m/Y',strtotime($isin_fetch_data->nav_date));                     
                    }*/

                    $query[$key]['image_name']= $this->getImageName($query[$key]['amc_id']);
                    $query[$key]['isin_fetch_data']= $isin_fetch_data;


                    $query[$key]['scheme_allocated_amount']=$scheme_allocated_amount;
                    $query[$key]['isin']=$isin;

                    $i++;
                }else{
                    unset($query[$key]);
                }

            }

            $sql1="select * ,(select images_name from amc_list where amc_id=bse_schemes.amc_id)as image_name  from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL'  AND `dividend_reinvestment_flag` = 'Z' AND `amc_id` IN (".$amc_ids.") AND  scheme_type='BALANCED' ORDER by rand() limit 5";

            $query1= $this->db->query($sql1)
                ->result_array();

            $j= 0;
            foreach ($query1 as $key => $value) {

                $isin=$value['isin'];
                $isin_id=$value['id'];
                //$isin_fetch_data = $this->getSchemePerformanceByISIN_get($isin);
                 $isin_fetch_data= $this->getSchemePerformanceByISINSchemedatabase_get($isin_id);  
                /**//*$isin_fetch_data = json_decode($isin_fetch_data);*/

                if(!empty($isin_fetch_data) && $j < 1){

                    $scheme_allocated_amount =round(($ALLOCATED_BALANCED_AMOUNT/1),0);
                    //$isin_fetch_data= $this->getSchemePerformanceByISIN_get($isin);
                     $isin_fetch_data= $this->getSchemePerformanceByISINSchemedatabase_get($isin_id);  
                   
                    /**//*$isin_fetch_data = json_decode($isin_fetch_data);*/
                    if(!empty($isin_fetch_data)){
                        /* $isin_fetch_data->nav_date_format = date( 'd/m/Y',strtotime($isin_fetch_data->nav_date));*/

                    }

                   
                    $query1[$key]['isin_fetch_data'] = $isin_fetch_data;
                    $query1[$key]['image_name'] = $this->getImageName($query1[$key]['amc_id']);


                    $query1[$key]['scheme_allocated_amount']=$scheme_allocated_amount;
                    $query1[$key]['isin']=$isin;

                    $j++;
                }else{
                    unset($query1[$key]);
                }

                //echo "<pre>"; print_r($query1);exit;
                
            }

            $result= (array_merge($query,$query1));
        }

        if($risk_type=="CONSERVATIVE"){

            $amc_ids = $this->activeAMCId();

            $sql1="select * from (select * ,(select images_name from amc_list where amc_id=bse_schemes.amc_id)as image_name   from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL'  AND `dividend_reinvestment_flag` = 'Z' AND `amc_id` IN (".$amc_ids.") AND scheme_type='BALANCED' ORDER by rand() limit 5) as j order by purchase_amount_multiplier DESC";


            $query1= $this->db->query($sql1)
                ->result_array();

            $scheme_all_amount =$amount/3;

            $mutiplier_total_amout = $amount;
             $i=0;
            foreach ($query1 as $key => $value) {
                $isin_id=$value['id'];
                $isin=$value['isin'];
                $isin_fetch_data= $this->getSchemePerformanceByISINSchemedatabase_get($isin_id);
                /**//*$isin_fetch_data = json_decode($isin_fetch_data);*/

            if(!empty($isin_fetch_data) && $i < 3){   
                $multiplier_split = 1;
                
                if($key==0){
                    $multiplier_split = 3;
                }elseif($key==1){
                    $multiplier_split = 2;
                }

                $multiplier_amount=$value['purchase_amount_multiplier'];
                $scheme_final_amount = $this->calculate_multiplier($key,$multiplier_split,$multiplier_amount,$scheme_all_amount,$mutiplier_total_amout);

                $mutiplier_total_amout = $mutiplier_total_amout-$scheme_final_amount;   
                //$isin=$value['isin'];
               // $isin_fetch_data= $this->getSchemePerformanceByISIN_get($isin);

               /*$isin_fetch_data = json_decode($isin_fetch_data);*/
               

                if(!empty($isin_fetch_data)){
                     /*$isin_fetch_data->nav_date_format = date( 'd/m/Y',strtotime($isin_fetch_data->nav_date));*/
                     
                }

                $query1[$key]['isin_fetch_data']= $isin_fetch_data;;
                $query1[$key]['image_name']= $this->getImageName($query1[$key]['amc_id']);


                if($key==0){
                    $scheme_all_amount = floor($mutiplier_total_amout/2);
                }elseif($key==1){
                    $scheme_all_amount = $mutiplier_total_amout;
                }

                $query1[$key]['scheme_allocated_amount']=$scheme_final_amount;
                $query1[$key]['isin']=$isin;
                
                //$isin_fetch_data= $this->getSchemePerformanceByISIN_get($isin);

               /*$isin_fetch_data = json_decode($isin_fetch_data);*/
                if(!empty($isin_fetch_data)){
                    /*$isin_fetch_data->nav_date_format = date( 'd/m/Y',strtotime($isin_fetch_data->nav_date));*/
                    
                }                

                $query1[$key]['isin_fetch_data']= $isin_fetch_data;

            }else{
                    unset($query1[$key]);
            }
                //$query1[$key]['image_name']= $this->getImageName($query1[0]['amc_id']);
                
            }
            
            $count_array = count($query1);
            if($count_array < 3){
                $calculate = $amount/$count_array;
                foreach($query1 as $key => $row){
                    $query1[$key]['scheme_allocated_amount']=$calculate;
                }
            }
            $result= $query1;
        }

        if($risk_type=="ELSS"){

            $amc_ids = $this->activeAMCId();

            $sql="select  * from (select * ,(select images_name from amc_list where amc_id=bse_schemes.amc_id)as image_name   from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL'  AND `dividend_reinvestment_flag` = 'Z' AND `amc_id` IN (".$amc_ids.") AND scheme_type='ELSS' ORDER by rand() limit 3) as j order by purchase_amount_multiplier DESC" ;
            $query= $this->db->query($sql)
                ->result_array();

           // echo "<pre>"; print_r($query); exit;
            $scheme_all_amount =floor($amount/3);;

            /*$splitmod=fmod($scheme_all_amount,1);
            $addAmount = 0;
            if($splitmod>0){
                $addAmount = (($splitmod*3)>1)?2:1; 
            }
            */
            $mutiplier_total_amout = $amount;
            $i=0;
            foreach ($query as $key => $value) {
                $isin=$value['isin'];
                $isin_id=$value['id'];
                $isin_fetch_data= $this->getSchemePerformanceByISINSchemedatabase_get($isin_id);
                /**//*$isin_fetch_data = json_decode($isin_fetch_data);*/

                if(!empty($isin_fetch_data) && $i < 3){

                $multiplier_split = 1;
                
                if($key==0){
                    $multiplier_split = 3;
                }elseif($key==1){
                    $multiplier_split = 2;
                }
                //$isin= $isin_number =  $value['isin'];

                // echo "<pre>"; print_r($isin); exit;
                // $isin_fetch_data= $this->getSchemePerformanceByISIN_get($isin);
                /*$isin_fetch_data = json_decode($isin_fetch_data);*/

                if(!empty($isin_fetch_data)){
                     /*$isin_fetch_data->nav_date_format = date( 'd/m/Y',strtotime($isin_fetch_data->nav_date));*/

                     
                }

               
                $query[$key]['isin_fetch_data']= $isin_fetch_data;
                $query[$key]['image_name']= $this->getImageName($query[$key]['amc_id']);

                //$nav_date = $isin_fetch_data->nav_date ;
               

                $multiplier_amount=$value['purchase_amount_multiplier'];
                $scheme_final_amount = $this->calculate_multiplier($key,$multiplier_split,$multiplier_amount,$scheme_all_amount,$mutiplier_total_amout);

                $mutiplier_total_amout = $mutiplier_total_amout-$scheme_final_amount;

                if($key==0){
                    $scheme_all_amount = floor($mutiplier_total_amout/2);
                }elseif($key==1){
                    $scheme_all_amount = $mutiplier_total_amout;
                }


                /*if($key==2){
                    
                    $scheme_allocated_amount = floor($scheme_all_amount+$addAmount);
                }else{
                    $scheme_allocated_amount = floor($scheme_all_amount);
                }*/


                $query[$key]['scheme_allocated_amount']=$scheme_final_amount;
                $query[$key]['isin']= $isin  ;

             }else{
                    unset($query[$key]);
            }

               // $query[$key]['isin_number']= $isin_number;
                
                
             
            }

            //die();
            $count_array = count($query);
            if($count_array < 3){
                $calculate = $amount/$count_array;
                foreach($query as $key => $row){
                    $query[$key]['scheme_allocated_amount']=$calculate;
                }
            }
            $result = $query;
        }

        //echo "innnnnnnnn"; exit;
       
        //echo "<pre>"; print_r($result);exit;
        // $result_new = array();
        // if(!empty($result)){
        //     foreach($result as $row){
        //         if(!empty($row['isin_fetch_data']) && count($result_new) < 3 ){
        //             $result_new[] = $row;
        //         } 
        //     }
        // }
       
        //echo "<pre>"; print_r($result);exit;
        return $result;
    }

    public function calculate_multiplier($key,$multiplier_split,$multiplier_amount,$split,$amount){
        switch ($key) {
            case '0':
                $mutiple_check_devide=$split/$multiplier_amount;
                if($mutiple_check_devide>=1){
                    $mutiple_check_mod = fmod($split,$multiplier_amount);
                    if($mutiple_check_mod>0){
                        $diff_multiplier_amount = $multiplier_amount-$mutiple_check_mod;
                        return $final_amount = $split+$diff_multiplier_amount;
                    }else{
                        return $final_amount = $split;
                    }
                }else{
                    $mutiple_check_mod = fmod($split,$multiplier_amount);
                    $diff_multiplier_amount = $multiplier_amount-$mutiple_check_mod;
                    return $final_amount = $split+$diff_multiplier_amount;
                }
                break;
            case '1':
                $mutiple_check_devide=$split/$multiplier_amount;
                if($mutiple_check_devide>=1){
                    $mutiple_check_mod = fmod($split,$multiplier_amount);
                    if($mutiple_check_mod>0){
                        $diff_multiplier_amount = $multiplier_amount-$mutiple_check_mod;
                        return $final_amount = $split+$diff_multiplier_amount;
                    }else{
                        return $final_amount = $split;
                    }
                }else{
                    $mutiple_check_mod = fmod($split,$multiplier_amount);
                    $diff_multiplier_amount = $multiplier_amount-$mutiple_check_mod;
                    return $final_amount = $split+$diff_multiplier_amount;
                }
                break;
            case '2':
                return $split;
                break;
        }
        
    }

    public function new_risk_sip_master_schemes($risk_type,$day,$amount){
        /*
        $date= date('d', strtotime($today_date. ' + 1 days'));
        $day= date('d', strtotime($today_date. ' + 1 days'));
*/
        $result_array1=array();
        $result_array=array();
        $final_scheme_unselected1=array();
        $final_scheme_unselected=array();

        $risk="SELECT allocated_percent,type_of_funds FROM `master_risk_allocation` WHERE `is_robo_active` = '1' AND `risk_type` = '$risk_type'";
        $risk_array= $this->db->query($risk)
            ->result_array();
        $EQUITY_PER=$risk_array[0]['allocated_percent'];
        $BALANCED_PER=$risk_array[1]['allocated_percent'];

        $ALLOCATED_EQUITY_AMOUNT=($EQUITY_PER * $amount)/100;
        $AEAMod = $ALLOCATED_EQUITY_AMOUNT%500;
        if($AEAMod>0 && $amount!=500){
            $ALLOCATED_EQUITY_AMOUNT = $amount;
            $ALLOCATED_BALANCED_AMOUNT=0;
        }else{
            $ALLOCATED_EQUITY_AMOUNT = (floor($ALLOCATED_EQUITY_AMOUNT/500)*500)+500;
            $ALLOCATED_BALANCED_AMOUNT=$amount- $ALLOCATED_EQUITY_AMOUNT;
        }


        
        /**/
        $min_amount_array=$this->check_min_bal_amount($ALLOCATED_BALANCED_AMOUNT,$amount,$day);
        
        $ALLOCATED_BALANCED_AMOUNT=$min_amount_array['amount'];

        $date_check=$min_amount_array['date_check'];

        $ALLOCATED_EQUITY_AMOUNT=$amount- $ALLOCATED_BALANCED_AMOUNT;

        $equity_per= round(($ALLOCATED_EQUITY_AMOUNT/$amount)*100);
        $balance_per=100-$equity_per;



        if($amount < 3000 && $amount >= 2000){
            $limit=2;
            if($amount==2500){
                $split1=1500;
                $split2=1000;   
            }
            else{
                $split1=1000;
                $split2=1000;

            }
            $split_array=array($split1,$split2);

        }else if($amount < 2000){

            $limit=1;
            $split1=$amount;
            $split_array[]=$split1;

        }else
        {
            $limit=3;

            $split_array= $this->splitAmount($limit,$amount);
            $split1=$split_array[0];
            $split2=$split_array[1];
            $split3=$split_array[2];
        }


        /*
print_r($split1); echo "<br>";      
print_r($split2); echo "<br>";      
print_r($split3); echo "<br>";      
die();*/
        if($risk_type=="AGGRESSIVE"){
            //$scheme_allocated_amount =round(($amount/$limit),0);

          $sql="select DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime  from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and    b.`is_robo` = 'Y' and b.sip_status=1 and  b.scheme_type='EQUITY' and b.sip_status=1 and b.sip_frequency='MONTHLY'  and 
b.sip_minimum_installment_amount <= '$split1' and  FIND_IN_SET($day,b.`sip_dates`)    ORDER by rand() ";



            $query= $this->db->query($sql)
                ->result_array();

            $count= count($query);


            $scheme_allocated_amount =round(($amount/$count),0);

            foreach ($query as $key => $value) {

                //$query[$key]['scheme_allocated_amount']=$scheme_allocated_amount;
                $resultArry[$key]['scheme_code']=$value['scheme_code'];
                $resultArry[$key]['sip_minimum_installment_amount']=$value['sip_minimum_installment_amount'];



            }
            $final_scheme=array();
            for ($i=0; $i < $limit ; $i++) { 
                $split_check= ($split_array[$i])%1000 ;
                if($split_check==0){
                    foreach ($resultArry as $key=>$value) {
                        //echo $key;
                        if($value['sip_minimum_installment_amount'] <= $split_array[$i]){
                            $final_scheme[]=array(
                                'scheme_code'=>$value['scheme_code'],
                                'sip_minimum_installment_amount'=>$value['sip_minimum_installment_amount'],
                                'split_amount'=>$split_array[$i]
                            );
                            unset($resultArry[$key]);
                            break;
                        }    
                    }
                }
                else{

                    foreach ($resultArry as $key=>$value) {
                        //echo $key;
                        if($value['sip_minimum_installment_amount']%500==0 && $value['sip_minimum_installment_amount'] <= $split_array[$i]){
                            $final_scheme[]=array(
                                'scheme_code'=>$value['scheme_code'],
                                'sip_minimum_installment_amount'=>$value['sip_minimum_installment_amount'],
                                'split_amount'=>$split_array[$i]
                            ); 
                            unset($resultArry[$key]); 
                            break;
                        }    
                    }
                }
            }  

            // ($final_schem);

            foreach ($final_scheme as  $value) {
                $sql="select * from bse_sip_schemes where  scheme_code='".$value['scheme_code']."' and sip_status=1   and `is_robo` = 'Y' and  scheme_type='EQUITY' and sip_frequency='MONTHLY'  and  FIND_IN_SET($day,`sip_dates`)" ;
                $query = $this->db->query($sql)
                    ->result_array();

                $amc_id=$query[0]['amc_id'];
                $image_name=$this->getamcname($amc_id);

                $isin_fetch_data= $this->getSchemePerformanceByISINdatabase_get($query[0]['id']);
                /*$isin_fetch_data = json_decode($isin_fetch_data);*/

                //echo "<pre>"; print_r($query[0]['amc_id']); exit;




                //$query[0]
                $result_array[]=array(

                    'amc_code'=>$query[0]['amc_code'],
                    'amc_name'=>$query[0]['amc_name'],
                    'scheme_code'=>$query[0]['scheme_code'],
                    'scheme_name'=>$query[0]['scheme_name'],
                    'sip_transaction_mode'=>$query[0]['sip_transaction_mode'],
                    'sip_frequency'=>$query[0]['sip_frequency'],
                    'sip_dates'=>$query[0]['sip_dates'], 
                    'sip_minimum_gap'=>$query[0]['sip_minimum_gap'], 
                    'sip_maximum_gap'=>$query[0]['sip_maximum_gap'], 
                    'sip_installment_gap'=>$query[0]['sip_installment_gap'], 
                    'sip_status'=>$query[0]['sip_status'], 
                    'sip_minimum_installment_amount'=>$query[0]['sip_minimum_installment_amount'], 
                    'sip_maximum_installment_amount'=>$query[0]['sip_maximum_installment_amount'], 
                    'sip_multiplier_amount'=>$query[0]['sip_multiplier_amount'], 
                    'sip_minimum_installment_numbers'=>$query[0]['sip_minimum_installment_numbers'], 
                    'sip_maximum_installment_numbers'=>$query[0]['sip_maximum_installment_numbers'], 
                    'scheme_isin'=>$query[0]['scheme_isin'],
                    'isin'=>$query[0]['scheme_isin'],
                    'image_name' => $this->getImageName(str_replace("\r", '', $query[0]['amc_id'])),
                    'isin_fetch_data'=>$isin_fetch_data,

                    'scheme_type'=>$query[0]['scheme_type'], 
                    'is_robo'=>$query[0]['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $query[0]['amc_id']),
                    'scheme_allocated_amount'=>$value['split_amount'],
                    

                );

            }

            $result= $result_array;

        }
        if($risk_type=="MODERATE"){

            /* if($ALLOCATED_EQUITY_AMOUNT < 3000){
                $limit=2;
            }else if($ALLOCATED_EQUITY_AMOUNT < 2000){

                $limit=1;
            }else
            {
                $limit=2;
            }*/ 

            

            if($ALLOCATED_EQUITY_AMOUNT < 2000){
                $split1=$ALLOCATED_EQUITY_AMOUNT;
                $limit=1;
                $split_array_mod= array($split1);
            }else
            {
                $limit=2;
                $split_array_mod= $this->splitmodAmount($limit,$ALLOCATED_EQUITY_AMOUNT);
            } 



            /*Balances*/
            $limit1=0;

            if($ALLOCATED_BALANCED_AMOUNT < 3000 && $ALLOCATED_BALANCED_AMOUNT >= 2000){
                $limit1=1;
            }else if($ALLOCATED_BALANCED_AMOUNT < 2000 && $ALLOCATED_BALANCED_AMOUNT>0){

                $limit1=1;
            }elseif($ALLOCATED_BALANCED_AMOUNT >= 3000){
                 $limit1=1;
            }

            if($limit1>0){
                $scheme_allocated_amount =round(($ALLOCATED_EQUITY_AMOUNT/$limit),0);
                
                $scheme_allocated_amount1 =round(($ALLOCATED_BALANCED_AMOUNT/$limit1),0);
                if($scheme_allocated_amount1<1000 && $limit1==1){
                    $scheme_allocated_amount1_diff = 1000-$ALLOCATED_BALANCED_AMOUNT;
                    $ALLOCATED_BALANCED_AMOUNT = $ALLOCATED_BALANCED_AMOUNT+$scheme_allocated_amount1_diff;

                    $scheme_allocated_amount1 =round(($ALLOCATED_BALANCED_AMOUNT/$limit1),0);

                    $ALLOCATED_EQUITY_AMOUNT = $ALLOCATED_EQUITY_AMOUNT-$scheme_allocated_amount1_diff;

                    //repeat Process of equity split
                    if($ALLOCATED_EQUITY_AMOUNT < 2000){
                        $split1=$ALLOCATED_EQUITY_AMOUNT;
                        $limit=1;
                        $split_array_mod= array($split1);
                    }else
                    {
                        $limit=2;
                        $split_array_mod= $this->splitmodAmount($limit,$ALLOCATED_EQUITY_AMOUNT);
                    } 
                }
            }


            $sql="select DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and b.`is_robo` = 'Y' and b.scheme_type='EQUITY' and sip_status=1 and b.sip_frequency='MONTHLY' and b.sip_minimum_installment_amount <= '$split1' and  FIND_IN_SET($day,b.`sip_dates`)   ORDER by rand()";
            $query= $this->db->query($sql)
                ->result_array();
            foreach ($query as $key => $value) {

                //$query[$key]['scheme_allocated_amount']=$scheme_allocated_amount;
                $resultArry[$key]['scheme_code']=$value['scheme_code'];
                $resultArry[$key]['sip_minimum_installment_amount']=$value['sip_minimum_installment_amount'];


            }

            //print_r($split_array_mod);exit;

            $final_scheme=array();
            for ($i=0; $i < $limit ; $i++) { 
                $split_check= ($split_array_mod[$i])%1000 ;
                if($split_check==0){
                    foreach ($resultArry as $key=>$value) {
                        //echo $key;
                        if($value['sip_minimum_installment_amount'] <= $split_array_mod[$i]){
                            $final_scheme[]=array(
                                'scheme_code'=>$value['scheme_code'],
                                'sip_minimum_installment_amount'=>$value['sip_minimum_installment_amount'],
                                'split_amount'=>$split_array_mod[$i]
                            );
                            unset($resultArry[$key]);
                            break;
                        }    
                    }
                }
                else{

                    foreach ($resultArry as $key=>$value) {
                        //echo $key;
                        if($value['sip_minimum_installment_amount']%500==0 && $value['sip_minimum_installment_amount'] <= $split_array_mod[$i]){
                            $final_scheme[]=array(
                                'scheme_code'=>$value['scheme_code'],
                                'sip_minimum_installment_amount'=>$value['sip_minimum_installment_amount'],
                                'split_amount'=>$split_array_mod[$i]
                            ); 

                            unset($resultArry[$key]); 
                            break;
                        }    
                    }
                }
            }


            
            // ($final_schem);
            foreach ($final_scheme as  $value) {
                $sql3="select * from bse_sip_schemes where scheme_code='".$value['scheme_code']."'   and sip_status=1 and `is_robo` = 'Y' and scheme_type='EQUITY' and sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$split1' and  FIND_IN_SET($day,`sip_dates`)" ;
                $query3 = $this->db->query($sql3)
                    ->result_array();
                $amc_id=$query3[0]['amc_id'];
                $image_name=$this->getamcname($amc_id);

                $isin_fetch_data= $this->getSchemePerformanceByISINdatabase_get($query3[0]['id']);
               /* $isin_fetch_data = json_decode($isin_fetch_data);*/

                //$query[0]
                $result_array[]=array(
                    'amc_code'=>$query3[0]['amc_code'],
                    'amc_name'=>$query3[0]['amc_name'],
                    'scheme_code'=>$query3[0]['scheme_code'],
                    'scheme_name'=>$query3[0]['scheme_name'],
                    'sip_transaction_mode'=>$query3[0]['sip_transaction_mode'],
                    'sip_frequency'=>$query3[0]['sip_frequency'],
                    'sip_dates'=>$query3[0]['sip_dates'], 
                    'sip_minimum_gap'=>$query3[0]['sip_minimum_gap'], 
                    'sip_maximum_gap'=>$query3[0]['sip_maximum_gap'], 
                    'sip_installment_gap'=>$query3[0]['sip_installment_gap'], 
                    'sip_status'=>$query3[0]['sip_status'], 
                    'sip_minimum_installment_amount'=>$query3[0]['sip_minimum_installment_amount'], 
                    'sip_maximum_installment_amount'=>$query3[0]['sip_maximum_installment_amount'], 
                    'sip_multiplier_amount'=>$query3[0]['sip_multiplier_amount'], 
                    'sip_minimum_installment_numbers'=>$query3[0]['sip_minimum_installment_numbers'], 
                    'sip_maximum_installment_numbers'=>$query3[0]['sip_maximum_installment_numbers'], 
                    'scheme_isin'=>$query3[0]['scheme_isin'], 
                    'isin'=>$query3[0]['scheme_isin'], 


                    'image_name' => $this->getImageName(str_replace("\r", '', $query3[0]['amc_id'])),
                    'isin_fetch_data'=>$isin_fetch_data,

                    'scheme_type'=>$query3[0]['scheme_type'], 
                    'is_robo'=>$query3[0]['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $query3[0]['amc_id']),
                    'scheme_allocated_amount'=>$value['split_amount'],
                    
                );

            }


            

           $sql1="select b.* from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and  b.`is_robo` = 'Y' and  b.sip_status=1 and  b.scheme_type='BALANCED' and  b.sip_frequency='MONTHLY' and 
         b.sip_minimum_installment_amount <= '$amount' and  FIND_IN_SET($day, b.`sip_dates`)   ORDER by rand() limit $limit1";
            $query1= $this->db->query($sql1)
                ->result_array();
            foreach ($query1 as $key => $value) {

                //$query[$key]['scheme_allocated_amount']=$scheme_allocated_amount;
                $resultArry1[$key]['scheme_code']=$value['scheme_code'];
                $resultArry1[$key]['sip_minimum_installment_amount']=$value['sip_minimum_installment_amount'];


            }

            $final_scheme1=array();
            foreach ($resultArry1 as $key=>$value) {
                //echo $value['sip_minimum_installment_amount'];
                if($value['sip_minimum_installment_amount'] <= $ALLOCATED_BALANCED_AMOUNT){
                    $final_scheme1[]=array(
                        'scheme_code'=>$value['scheme_code'],
                        'sip_minimum_installment_amount'=>$value['sip_minimum_installment_amount'],
                        'split_amount'=>$ALLOCATED_BALANCED_AMOUNT
                    );
                    unset($resultArry1[$key]);
                    break;
                }    
            }

            
            foreach ($final_scheme1 as  $value) {
                $sql="select * from bse_sip_schemes where scheme_code='".$value['scheme_code']."' and sip_status=1 and  `is_robo` = 'Y' and scheme_type='BALANCED' and sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$ALLOCATED_BALANCED_AMOUNT' and  FIND_IN_SET($day,`sip_dates`)" ;
                $query = $this->db->query($sql)
                    ->result_array();
                       $amc_id=$query[0]['amc_id'];
                    $image_name=$this->getamcname($amc_id);

                $isin_fetch_data= $this->getSchemePerformanceByISINdatabase_get($query[0]['id']);
                /**//*$isin_fetch_data = json_decode($isin_fetch_data);*/


                //$query[0]
                $result_array1[]=array(
                    'amc_code'=>$query[0]['amc_code'],
                    'amc_name'=>$query[0]['amc_name'],
                    'scheme_code'=>$query[0]['scheme_code'],
                    'scheme_name'=>$query[0]['scheme_name'],
                    'sip_transaction_mode'=>$query[0]['sip_transaction_mode'],
                    'sip_frequency'=>$query[0]['sip_frequency'],
                    'sip_dates'=>$query[0]['sip_dates'], 
                    'sip_minimum_gap'=>$query[0]['sip_minimum_gap'], 
                    'sip_maximum_gap'=>$query[0]['sip_maximum_gap'], 
                    'sip_installment_gap'=>$query[0]['sip_installment_gap'], 
                    'sip_status'=>$query[0]['sip_status'], 
                    'sip_minimum_installment_amount'=>$query[0]['sip_minimum_installment_amount'], 
                    'sip_maximum_installment_amount'=>$query[0]['sip_maximum_installment_amount'], 
                    'sip_multiplier_amount'=>$query[0]['sip_multiplier_amount'], 
                    'sip_minimum_installment_numbers'=>$query[0]['sip_minimum_installment_numbers'], 
                    'sip_maximum_installment_numbers'=>$query[0]['sip_maximum_installment_numbers'], 
                    'scheme_isin'=>$query[0]['scheme_isin'], 
                    'isin'=>$query[0]['scheme_isin'], 

                    'image_name' => $this->getImageName(str_replace("\r", '', $query[0]['amc_id'])),
                    'isin_fetch_data'=>$isin_fetch_data,


                    'scheme_type'=>$query[0]['scheme_type'], 
                    'is_robo'=>$query[0]['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $query[0]['amc_id']),
                    'scheme_allocated_amount'=>$value['split_amount'],
                    
                );
            }
            $result= (array_merge($result_array,$result_array1));
        }
        if($risk_type=="CONSERVATIVE"){
            if($amount < 3000 && $amount >= 2000){
                $limit=2;
                if($amount==2500){
                    $split1=1500;
                    $split2=1000;   
                }
                else{
                    $split1=1000;
                    $split2=1000;

                }
                $split_array=array($split1,$split2);

            }else if($amount < 2000){

                $limit=1;
                $split1=$amount;
                $split_array[]=$split1;
            }
            else
            {
                $limit=3;
                $split_array= $this->splitAmount($limit,$amount);
                $split1=$split_array[0];
                $split2=$split_array[1];
                $split3=$split_array[2];
            }

            $scheme_allocated_amount =round(($amount/$limit),0);
            $sql1="select DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and  b.scheme_type='BALANCED'  and  b.`is_robo` = 'Y'  and b.sip_frequency='MONTHLY' and b.sip_status=1 and  b.sip_minimum_installment_amount <= '$split1'and  FIND_IN_SET($day,b.`sip_dates`)   ORDER by rand() ";
            $query= $this->db->query($sql1)
                ->result_array();

            foreach ($query as $key => $value) {
                $resultArry[$key]['scheme_code']=$value['scheme_code'];
                $resultArry[$key]['sip_minimum_installment_amount']=$value['sip_minimum_installment_amount'];
            }

            $limit1= count($resultArry)<$limit?count($resultArry):$limit;
            if($limit1==1 && count($resultArry)<$limit){
                $split_array = array($amount);
            }elseif($limit1==2 && count($resultArry)<$limit){
                $split1 = $split_array[0];
                $split2 = $split_array[1]+$split_array[2];
                $split_array = array($split1,$split2);
            }
            /*print_r($split_array);die();*/

            $final_scheme=array();
            for ($i=0; $i < $limit1 ; $i++) { 
                $split_check= ($split_array[$i])%1000 ;
                if($split_check==0){
                    foreach ($resultArry as $key=>$value) {
                        //echo $key;
                        if($value['sip_minimum_installment_amount'] <= $split_array[$i]){ 


                            $final_scheme[]=array(
                                'scheme_code'=>$value['scheme_code'],
                                'sip_minimum_installment_amount'=>$value['sip_minimum_installment_amount'],
                                'split_amount'=>$split_array[$i]
                            ); 

                            unset($resultArry[$key]);
                            break;
                        }    
                    }
                }
                else{

                    foreach ($resultArry as $key=>$value) {
                        //echo $key;
                        if($value['sip_minimum_installment_amount']%500==0 && $value['sip_minimum_installment_amount'] <= $split_array[$i]){
                            $final_scheme[]=array(
                                'scheme_code'=>$value['scheme_code'],
                                'sip_minimum_installment_amount'=>$value['sip_minimum_installment_amount'],
                                'split_amount'=>$split_array[$i]
                            ); 
                            unset($resultArry[$key]); 
                            break;
                        }    
                    }
                }
            }  

            if($limit1 >count($final_scheme))
            {

                switch (count($final_scheme)) {
                    case '1':
                       $final_scheme[0]['split_amount']=$amount;
                        break;
                    case '2':
                    $final_scheme[1]['split_amount']=$split_array[1]+$split_array[2];
                    break;

                }
            }
            // ($final_schem);
            foreach ($final_scheme as  $value) {
                $sql="select * from bse_sip_schemes where scheme_code='".$value['scheme_code']."' and sip_status=1 and  scheme_type='BALANCED' and  `is_robo` = 'Y'  and  sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$split1'and  FIND_IN_SET($day,`sip_dates`)" ;
                $query = $this->db->query($sql)
                    ->result_array();

                        $amc_id=$query[0]['amc_id'];
                    $image_name=$this->getamcname($amc_id);

                $isin_fetch_data= $this->getSchemePerformanceByISINdatabase_get($query[0]['id']);
                /**//*$isin_fetch_data = json_decode($isin_fetch_data);*/
                //$
                //$query[0]
                $result_array[]=array(
                    'amc_code'=>$query[0]['amc_code'],
                    'amc_name'=>$query[0]['amc_name'],
                    'scheme_code'=>$query[0]['scheme_code'],
                    'scheme_name'=>$query[0]['scheme_name'],
                    'sip_transaction_mode'=>$query[0]['sip_transaction_mode'],
                    'sip_frequency'=>$query[0]['sip_frequency'],
                    'sip_dates'=>$query[0]['sip_dates'], 
                    'sip_minimum_gap'=>$query[0]['sip_minimum_gap'], 
                    'sip_maximum_gap'=>$query[0]['sip_maximum_gap'], 
                    'sip_installment_gap'=>$query[0]['sip_installment_gap'], 
                    'sip_status'=>$query[0]['sip_status'], 
                    'sip_minimum_installment_amount'=>$query[0]['sip_minimum_installment_amount'], 
                    'sip_maximum_installment_amount'=>$query[0]['sip_maximum_installment_amount'], 
                    'sip_multiplier_amount'=>$query[0]['sip_multiplier_amount'], 
                    'sip_minimum_installment_numbers'=>$query[0]['sip_minimum_installment_numbers'], 
                    'sip_maximum_installment_numbers'=>$query[0]['sip_maximum_installment_numbers'], 
                    'scheme_isin'=>$query[0]['scheme_isin'], 
                    'isin'=>$query[0]['scheme_isin'], 

                    'image_name' => $this->getImageName(str_replace("\r", '', $query[0]['amc_id'])),
                    'isin_fetch_data'=>$isin_fetch_data,

                    'scheme_type'=>$query[0]['scheme_type'], 
                    'is_robo'=>$query[0]['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $query[0]['amc_id']),
                    'scheme_allocated_amount'=>$value['split_amount'],
                   
                );

            }
            $result= $result_array;
        }

        if($risk_type=="ELSS"){

            $sql="select DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and b.`is_robo` = 'Y'  and b.scheme_type='ELSS' and sip_status=1 and b.sip_frequency='MONTHLY' and b.sip_minimum_installment_amount <= '$split1' and  FIND_IN_SET($day,b.`sip_dates`)   ORDER by rand()";
            $query= $this->db->query($sql)
                ->result_array();
            $count= count($query);

            $scheme_allocated_amount =round(($amount/$count),0);
            foreach ($query as $key => $value) {
                $resultArry[$key]['scheme_code']=$value['scheme_code'];
                $resultArry[$key]['sip_minimum_installment_amount']=$value['sip_minimum_installment_amount'];


            }

            $final_scheme=array();
            for ($i=0; $i < $limit ; $i++) { 
                $split_check= ($split_array[$i])%1000 ;
                if($split_check==0){
                    foreach ($resultArry as $key=>$value) {
                        //echo $key;
                        if($value['sip_minimum_installment_amount'] <= $split_array[$i]){
                            $final_scheme[]=array(
                                'scheme_code'=>$value['scheme_code'],
                                'sip_minimum_installment_amount'=>$value['sip_minimum_installment_amount'],
                                'split_amount'=>$split_array[$i]
                            );
                            unset($resultArry[$key]);
                            break;
                        }    
                    }
                }
                else{

                    foreach ($resultArry as $key=>$value) {
                        //echo $key;
                        if($value['sip_minimum_installment_amount']%500==0 && $value['sip_minimum_installment_amount'] <= $split_array[$i]){
                            $final_scheme[]=array(
                                'scheme_code'=>$value['scheme_code'],
                                'sip_minimum_installment_amount'=>$value['sip_minimum_installment_amount'],
                                'split_amount'=>$split_array[$i]
                            ); 
                            unset($resultArry[$key]); 
                            break;
                        }    
                    }
                }
            }  


            // ($final_schem);
            foreach ($final_scheme as  $value) {
                $sql="select * from bse_sip_schemes where scheme_code='".$value['scheme_code']."' and sip_status=1 and scheme_type='ELSS' and sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$split1' and  FIND_IN_SET($day,`sip_dates`)" ;
                $query = $this->db->query($sql)
                    ->result_array();

                        $amc_id=$query[0]['amc_id'];
                    $image_name=$this->getamcname($amc_id);
                
                $isin_fetch_data= $this->getSchemePerformanceByISINdatabase_get($query[0]['id']);
                /**//*$isin_fetch_data = json_decode($isin_fetch_data);*/

                //$query[0]
                $result_array[]=array(
                    'amc_code'=>$query[0]['amc_code'],
                    'amc_name'=>$query[0]['amc_name'],
                    'scheme_code'=>$query[0]['scheme_code'],
                    'scheme_name'=>$query[0]['scheme_name'],
                    'sip_transaction_mode'=>$query[0]['sip_transaction_mode'],
                    'sip_frequency'=>$query[0]['sip_frequency'],
                    'sip_dates'=>$query[0]['sip_dates'], 
                    'sip_minimum_gap'=>$query[0]['sip_minimum_gap'], 
                    'sip_maximum_gap'=>$query[0]['sip_maximum_gap'], 
                    'sip_installment_gap'=>$query[0]['sip_installment_gap'], 
                    'sip_status'=>$query[0]['sip_status'], 
                    'sip_minimum_installment_amount'=>$query[0]['sip_minimum_installment_amount'], 
                    'sip_maximum_installment_amount'=>$query[0]['sip_maximum_installment_amount'], 
                    'sip_multiplier_amount'=>$query[0]['sip_multiplier_amount'], 
                    'sip_minimum_installment_numbers'=>$query[0]['sip_minimum_installment_numbers'], 
                    'sip_maximum_installment_numbers'=>$query[0]['sip_maximum_installment_numbers'], 
                    'scheme_isin'=>$query[0]['scheme_isin'], 
                    'isin'=>$query[0]['scheme_isin'], 

                    'image_name' => $this->getImageName(str_replace("\r", '', $query[0]['amc_id'])),
                    'isin_fetch_data'=>$isin_fetch_data,

                    'scheme_type'=>$query[0]['scheme_type'], 
                    'is_robo'=>$query[0]['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $query[0]['amc_id']),
                    'scheme_allocated_amount'=>$value['split_amount'],
                    
                );

            }
            $result= $result_array;

        }

        return $result;
    }   
/**/
    public function robo_master_schemes($risk_type,$day,$amount,$age){
       
        $date= date('d');
        $day= date('d');
      
        $result_array1=array();
        $result_array=array();
        $final_scheme_unselected1=array();
        $final_scheme_unselected=array();
        $risk="SELECT allocated_percent,type_of_funds FROM `master_risk_allocation` WHERE `is_robo_active` = '1' AND `risk_type` = '$risk_type' and min_age <=$age and max_age >=$age";

        $risk_array= $this->db->query($risk)
            ->result_array();

        $EQUITY_PER=$risk_array[0]['allocated_percent'];
        $BALANCED_PER=$risk_array[1]['allocated_percent'];

         $ALLOCATED_EQUITY_AMOUNT=($EQUITY_PER * $amount)/100;

        //$ALLOCATED_BALANCED_AMOUNT=($BALANCED_PER * $amount)/100;

        $AEAMod = $ALLOCATED_EQUITY_AMOUNT%1000;

        if($AEAMod>0){
         $ALLOCATED_EQUITY_AMOUNT = (floor($ALLOCATED_EQUITY_AMOUNT/1000)*1000)+1000;
       
        }


        $ALLOCATED_BALANCED_AMOUNT=$amount- $ALLOCATED_EQUITY_AMOUNT;

        //echo $ALLOCATED_EQUITY_AMOUNT;
        //echo "==>".$ALLOCATED_BALANCED_AMOUNT;exit;
        
        if($risk_type=="AGGRESSIVE" &&  $ALLOCATED_BALANCED_AMOUNT< 5000)
        {
            $diff_amount=5000-$ALLOCATED_BALANCED_AMOUNT;
            $ALLOCATED_BALANCED_AMOUNT=$ALLOCATED_BALANCED_AMOUNT+$diff_amount;
            $ALLOCATED_EQUITY_AMOUNT=$ALLOCATED_EQUITY_AMOUNT - $diff_amount;

        }

        if($risk_type=="MODERATE" &&  $ALLOCATED_BALANCED_AMOUNT< 5000)
        {
            $diff_amount=5000-$ALLOCATED_BALANCED_AMOUNT;
            $ALLOCATED_BALANCED_AMOUNT=$ALLOCATED_BALANCED_AMOUNT+$diff_amount;
            $ALLOCATED_EQUITY_AMOUNT=$ALLOCATED_EQUITY_AMOUNT - $diff_amount;

        }else if($risk_type=="MODERATE" &&  $ALLOCATED_EQUITY_AMOUNT< 5000){

            $diff_amount=5000-$ALLOCATED_EQUITY_AMOUNT;
            $ALLOCATED_EQUITY_AMOUNT=$ALLOCATED_EQUITY_AMOUNT+$diff_amount;
            $ALLOCATED_BALANCED_AMOUNT=$ALLOCATED_BALANCED_AMOUNT - $diff_amount;
        }

        if($risk_type=="CONSERVATIVE" &&  $ALLOCATED_EQUITY_AMOUNT< 5000){
            
            $diff_amount=5000-$ALLOCATED_EQUITY_AMOUNT;
            $ALLOCATED_EQUITY_AMOUNT=$ALLOCATED_EQUITY_AMOUNT+$diff_amount;
            $ALLOCATED_BALANCED_AMOUNT=$ALLOCATED_BALANCED_AMOUNT - $diff_amount;
        }


        $min_amount_array=$this->check_min_bal_amount_lumpsum($ALLOCATED_BALANCED_AMOUNT,$amount,$day);
        

        //print_r($min_amount_array);die();
        $ALLOCATED_BALANCED_AMOUNT=$min_amount_array['amount'];

        $date_check=$min_amount_array['date_check'];

        $ALLOCATED_EQUITY_AMOUNT=$amount- $ALLOCATED_BALANCED_AMOUNT;

        $equity_per= round(($ALLOCATED_EQUITY_AMOUNT/$amount)*100);
        $balance_per=100-$equity_per;


        $split_count=floor($ALLOCATED_EQUITY_AMOUNT/5000); 
        if($split_count >= 3){
        
            $limit=3;

            $split1=$ALLOCATED_EQUITY_AMOUNT/3;
            $splitmod=fmod($split1,1);

            $addAmount = (($splitmod*3)>1)?2:1;
            
            if($splitmod==0){
                $split2=$split1;   
                $split3=$split1;
            }
            else
            {
                $split1=floor($split1);    
                $split2=floor($split1);   
                $split3=floor($split1)+$addAmount;  
            }

            $split_array=array($split1,$split2,$split3);

        }else if($split_count == 2 && $split_count >1){
        
            $limit=2;

            $split1=$ALLOCATED_EQUITY_AMOUNT/2;
            $splitmod=fmod($split1,1);
            
            if($splitmod==0){
                $split2=$split1;   
                
            }
            else
            {
                $split1=floor($split1);    
                $split2=floor($split1)+1;   
            }
            $split_array=array($split1,$split2);
        }else{
            $limit=1;
                $split1=$ALLOCATED_EQUITY_AMOUNT;
                $split_array=array($split1);
        }
      
        //Balanced 
        $split_count_1=floor($ALLOCATED_BALANCED_AMOUNT/5000); 

        if($split_count_1 >= 3){
        
            $limit1=3;

            $split11=$ALLOCATED_BALANCED_AMOUNT/3;
            $splitmod1=fmod($split11,1);

            $addAmount1 = (($splitmod1*3)>1)?2:1;
            
            if($splitmod1==0){
                $split21=$split11;   
                $split31=$split11;
            }
            else
            {
                $split11=floor($split11);    
                $split21=floor($split11);   
                $split31=floor($split11)+$addAmount1;  
            }
            $split_array1=array($split11,$split21,$split31);
        }else if($split_count_1 == 2 && $split_count_1 >1){
        
            $limit1=2;

            $split11=$ALLOCATED_BALANCED_AMOUNT/2;

            $splitmod1=fmod($split11,1);
            
            if($splitmod1==0){
                $split21=$split11;   
                
            }
            else
            {
                $split11=floor($split11);    
                $split21=floor($split11)+1;   
            }


            $split_array1=array($split11,$split21);
        }else{
            $limit1=1;
                $split11=$ALLOCATED_BALANCED_AMOUNT;
                $split_array1=array($split11);
        }




        if($risk_type=="AGGRESSIVE")
        {


         $scheme_allocated_amount =round(($ALLOCATED_EQUITY_AMOUNT/$limit),0);

        $scheme_allocated_amount1 =round(($ALLOCATED_BALANCED_AMOUNT/$limit1),0);

        // echo "<pre>";
        // print_r($limit);
        // echo "......";
        // print_r($limit1);
       // exit;

            $max_count=max($split_array);

            $amc_ids = $this->activeAMCId();

            $sql="select DISTINCT amc_id, id,unique_no,scheme_code,rta_scheme_code,amc_scheme_code,isin,amc_code,scheme_type,scheme_plan,scheme_name,purchase_allowed,purchase_transaction_mode,minimum_purchase_amount,additional_purchase_amount,maximum_purchase_amount,purchase_amount_multiplier,purchase_cutoff_time,redemption_allowed,redemption_transaction_mode,minimum_redemption_qty,redemption_qty_multiplier,maximum_redemption_qty,redemption_amount_minimum,redemption_amount_maximum,redemption_amount_multiple,redemption_cut_off_time,rta_agent_code,amc_active_flag,dividend_reinvestment_flag,sip_flag,stp_flag,swp_flag,switch_flag,settlemnt_type,amc_ind,face_value,start_date,end_date,exit_load_flag,exit_load,lock_in_period_flag,lock_In_period,channel_partner_code
             ,(select images_name from amc_list where amc_id=bse_schemes.amc_id)as image_name from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND `minimum_purchase_amount` <= '$max_count' AND `dividend_reinvestment_flag` = 'Z' AND  `amc_id` IN (".$amc_ids.") AND scheme_type='EQUITY'   ORDER by rand() ";

            $query= $this->db->query($sql)
                ->result_array();

            //echo "<pre>"; print_r($query); exit;

            $result_scheme_array=$this->schemeAvailLump($query,$max_count,$day,$limit,$split_array);




            //print_r($result_scheme_array);exit;
            
            $resultArry = $result_scheme_array['resultArry'];
            $final_scheme_selected = $result_scheme_array['final_scheme_selected'];

            
            if(count($final_scheme_selected)<=$limit){


                $limit = count($final_scheme_selected);

                $split_array = $this->slipAmountByLimitLumpsum($limit,$ALLOCATED_EQUITY_AMOUNT);

                //echo "==>";print_r($split_array);exit;

                $max_count=max($split_array);

                

                $sql="select DISTINCT amc_id, id,unique_no,scheme_code,rta_scheme_code,amc_scheme_code,isin,amc_code,scheme_type,scheme_plan,scheme_name,purchase_allowed,purchase_transaction_mode,minimum_purchase_amount,additional_purchase_amount,maximum_purchase_amount,purchase_amount_multiplier,purchase_cutoff_time,redemption_allowed,redemption_transaction_mode,minimum_redemption_qty,redemption_qty_multiplier,maximum_redemption_qty,redemption_amount_minimum,redemption_amount_maximum,redemption_amount_multiple,redemption_cut_off_time,rta_agent_code,amc_active_flag,dividend_reinvestment_flag,sip_flag,stp_flag,swp_flag,switch_flag,settlemnt_type,amc_ind,face_value,start_date,end_date,exit_load_flag,exit_load,lock_in_period_flag,lock_In_period,channel_partner_code,amc_id  from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND `minimum_purchase_amount` <= '$max_count' AND `dividend_reinvestment_flag` = 'Z' AND `amc_id` IN (".$amc_ids.") AND scheme_type='EQUITY'   ORDER by rand() ";
                $query= $this->db->query($sql)
                    ->result_array();

                $result_scheme_array=$this->schemeAvailLump($query,$max_count,$day,$limit,$split_array);

                $resultArry = $result_scheme_array['resultArry'];
                $final_scheme_selected = $result_scheme_array['final_scheme_selected'];

            }



            foreach ($resultArry as $key=>$value) {
                //echo $key;

                $final_scheme_unselected[]=array(
                    'amc_code'=>$value['amc_code'],
                    //'amc_name'=>$value['amc_name'],
                    'scheme_code'=>$value['scheme_code'],
                    'scheme_name'=>$value['scheme_name'],
                    'purchase_transaction_mode'=>$value['purchase_transaction_mode'],
                    //'sip_frequency'=>$value['sip_frequency'],
                    //'sip_dates'=>$value['sip_dates'], 
                   /// 'sip_minimum_gap'=>$value['sip_minimum_gap'], 
                  //  'sip_maximum_gap'=>$value['sip_maximum_gap'], 
                   // 'sip_installment_gap'=>$value['sip_installment_gap'], 
                  //  'sip_status'=>$value['sip_status'], 
                    'minimum_purchase_amount'=>$value['minimum_purchase_amount'], 
                    'maximum_purchase_amount'=>$value['maximum_purchase_amount'], 
                    'purchase_amount_multiplier'=>$value['purchase_amount_multiplier'], 
                    //'sip_minimum_installment_numbers'=>$value['sip_minimum_installment_numbers'], 
                   // 'sip_maximum_installment_numbers'=>$value['sip_maximum_installment_numbers'], 
                    'isin'=>$value['isin'], 
                    'isin_fetch_data' => $value['isin_fetch_data'],

                    'image_name'=>$this->getImageName(str_replace("\r", '', $value['amc_id'])),

                    


                    'scheme_type'=>$value['scheme_type'], 
                    'is_robo'=>$value['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $value['amc_id']),
                    //"plan_date"=>$day
                );
                unset($resultArry[$key]);
                break;

            }
            //echo "<pre>";
            //   print_r($final_scheme_unselected);exit();

            // ($final_schem);
           
            foreach ($final_scheme_selected as  $value) {
               
                


                $sql="select DISTINCT amc_id,id,unique_no,scheme_code,rta_scheme_code,amc_scheme_code,isin,amc_code,scheme_type,scheme_plan,scheme_name,purchase_allowed,purchase_transaction_mode,minimum_purchase_amount,additional_purchase_amount,maximum_purchase_amount,purchase_amount_multiplier,purchase_cutoff_time,redemption_allowed,redemption_transaction_mode,minimum_redemption_qty,redemption_qty_multiplier,maximum_redemption_qty,redemption_amount_minimum,redemption_amount_maximum,redemption_amount_multiple,redemption_cut_off_time,rta_agent_code,amc_active_flag,dividend_reinvestment_flag,sip_flag,stp_flag,swp_flag,switch_flag,settlemnt_type,amc_ind,face_value,start_date,end_date,exit_load_flag,exit_load,lock_in_period_flag,lock_In_period,channel_partner_code  from bse_schemes WHERE  scheme_code='".$value['scheme_code']."' AND  `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND `minimum_purchase_amount` <= '$max_count' AND `dividend_reinvestment_flag` = 'Z' AND  `amc_id` IN (".$amc_ids.") AND scheme_type='EQUITY'  ORDER by rand()" ;

                $query = $this->db->query($sql)
                    ->result_array();
              
                


                //$query[0]
                $next_plan_date=$day;
                // $isin_number = $query[0]['isin'];
                // $isin_fetch_data= $this->getSchemePerformanceByISIN_get($isin_number);
                /*$isin_fetch_data = json_decode($isin_fetch_data);*/

                //echo "<pre>"; print_r($isin_fetch_data); exit;

               // if(!empty($isin_fetch_data)){
                    $result_array[]=array(
                        'amc_code'=>$query[0]['amc_code'],
                      //  'amc_name'=>$query[0]['amc_name'],
                        'scheme_code'=>$query[0]['scheme_code'],
                        'scheme_name'=>$query[0]['scheme_name'],
                        'purchase_transaction_mode'=>$query[0]['purchase_transaction_mode'],
                       // 'sip_frequency'=>$query[0]['sip_frequency'],
                        //'sip_dates'=>$query[0]['sip_dates'], 
                        //'sip_minimum_gap'=>$query[0]['sip_minimum_gap'], 
                        //'sip_maximum_gap'=>$query[0]['sip_maximum_gap'], 
                        //'sip_installment_gap'=>$query[0]['sip_installment_gap'], 
                       // 'sip_status'=>$query[0]['sip_status'], 
                        'minimum_purchase_amount'=>$query[0]['minimum_purchase_amount'], 
                        'maximum_purchase_amount'=>$query[0]['maximum_purchase_amount'], 
                        'purchase_amount_multiplier'=>$query[0]['purchase_amount_multiplier'], 
                        //'sip_minimum_installment_numbers'=>$query[0]['sip_minimum_installment_numbers'], 
                        //'sip_maximum_installment_numbers'=>$query[0]['sip_maximum_installment_numbers'], 
                        'isin'=>$query[0]['isin'], 
                        'isin_fetch_data' => $value['isin_fetch_data'],
                        'image_name'=>$this->getImageName(str_replace("\r", '', $query[0]['amc_id'])),

                        'scheme_type'=>$query[0]['scheme_type'], 
                        'is_robo'=>$query[0]['is_robo'], 
                        'amc_id'=>str_replace("\r", '', $query[0]['amc_id']),
                        'scheme_allocated_amount'=>$value['split_amount'],
                       // "plan_date"=>$day
                    );

                //}
               

               

            }

            $max_count=max($split_array1);

            //if($max_count >=0)

          
                /*
                */
            $sql1="select DISTINCT amc_id,id,unique_no,scheme_code,rta_scheme_code,amc_scheme_code,isin,amc_code,scheme_type,scheme_plan,scheme_name,purchase_allowed,purchase_transaction_mode,minimum_purchase_amount,additional_purchase_amount,maximum_purchase_amount,purchase_amount_multiplier,purchase_cutoff_time,redemption_allowed,redemption_transaction_mode,minimum_redemption_qty,redemption_qty_multiplier,maximum_redemption_qty,redemption_amount_minimum,redemption_amount_maximum,redemption_amount_multiple,redemption_cut_off_time,rta_agent_code,amc_active_flag,dividend_reinvestment_flag,sip_flag,stp_flag,swp_flag,switch_flag,settlemnt_type,amc_ind,face_value,start_date,end_date,exit_load_flag,exit_load,lock_in_period_flag,lock_In_period,channel_partner_code  from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND  minimum_purchase_amount <= '$max_count'  AND  `dividend_reinvestment_flag` = 'Z' AND  `amc_id` IN (".$amc_ids.") AND scheme_type='BALANCED' ORDER by rand() ";
                $query1= $this->db->query($sql1)
                ->result_array();
                $next_plan_date=$day;
       
     

            $result_scheme_array=$this->schemeAvailLump($query1,$max_count,$day,$limit1,$split_array1);





            $resultArry1 = $result_scheme_array['resultArry'];

            $final_scheme_selected1 = $result_scheme_array['final_scheme_selected'];

            if(count($final_scheme_selected1)<$limit1 ){


                $limit1 = count($final_scheme_selected1);
                $split_array1 = $this->slipAmountByLimitLumpsum($limit1,$ALLOCATED_BALANCED_AMOUNT);

                $max_count=max($split_array1);

        
                $sql1="select DISTINCT amc_id,id,unique_no,scheme_code,rta_scheme_code,amc_scheme_code,isin,amc_code,scheme_type,scheme_plan,scheme_name,purchase_allowed,purchase_transaction_mode,minimum_purchase_amount,additional_purchase_amount,maximum_purchase_amount,purchase_amount_multiplier,purchase_cutoff_time,redemption_allowed,redemption_transaction_mode,minimum_redemption_qty,redemption_qty_multiplier,maximum_redemption_qty,redemption_amount_minimum,redemption_amount_maximum,redemption_amount_multiple,redemption_cut_off_time,rta_agent_code,amc_active_flag,dividend_reinvestment_flag,sip_flag,stp_flag,swp_flag,switch_flag,settlemnt_type,amc_ind,face_value,start_date,end_date,exit_load_flag,exit_load,lock_in_period_flag,lock_In_period,channel_partner_code  from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND  minimum_purchase_amount <= '$max_count'  AND  `dividend_reinvestment_flag` = 'Z' AND  `amc_id` IN (".$amc_ids.") AND scheme_type='BALANCED'  ORDER by rand()";
              $query1= $this->db->query($sql1)
                    ->result_array();

                $result_scheme_array=$this->schemeAvailLump($query1,$max_count,$day,$limit1,$split_array1);
                $resultArry1 = $result_scheme_array['resultArry'];

                $final_scheme_selected1 = $result_scheme_array['final_scheme_selected'];
            }

            foreach ($final_scheme_selected1 as  $value) {
                /**/
             
                $sql="select DISTINCT amc_id,id,unique_no,scheme_code,rta_scheme_code,amc_scheme_code,isin,amc_code,scheme_type,scheme_plan,scheme_name,purchase_allowed,purchase_transaction_mode,minimum_purchase_amount,additional_purchase_amount,maximum_purchase_amount,purchase_amount_multiplier,purchase_cutoff_time,redemption_allowed,redemption_transaction_mode,minimum_redemption_qty,redemption_qty_multiplier,maximum_redemption_qty,redemption_amount_minimum,redemption_amount_maximum,redemption_amount_multiple,redemption_cut_off_time,rta_agent_code,amc_active_flag,dividend_reinvestment_flag,sip_flag,stp_flag,swp_flag,switch_flag,settlemnt_type,amc_ind,face_value,start_date,end_date,exit_load_flag,exit_load,lock_in_period_flag,lock_In_period,channel_partner_code  from bse_schemes WHERE  scheme_code='".$value['scheme_code']."' AND `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND  minimum_purchase_amount <= '$max_count'  AND  `dividend_reinvestment_flag` = 'Z' AND  `amc_id` IN (".$amc_ids.") AND scheme_type='BALANCED'  ORDER by rand()" ;
                $query = $this->db->query($sql)
                    ->result_array();
                    $next_plan_date=$day;
    

                //  $isin_number = $query[0]['isin'];
                // $isin_fetch_data= $this->getSchemePerformanceByISIN_get($isin_number);
                /*$isin_fetch_data = json_decode($isin_fetch_data);*/

                //$query[0]
                //if(!empty($isin_fetch_data)){
                    $result_array1[]=array(
                        'amc_code'=>$query[0]['amc_code'],
                        //'amc_name'=>$query[0]['amc_name'],
                        'scheme_code'=>$query[0]['scheme_code'],
                        'scheme_name'=>$query[0]['scheme_name'],
                        'purchase_transaction_mode'=>$query[0]['purchase_transaction_mode'],
                        //'sip_frequency'=>$query[0]['sip_frequency'],
                        //'sip_dates'=>$query[0]['sip_dates'], 
                        //'sip_minimum_gap'=>$query[0]['sip_minimum_gap'], 
                       // 'sip_maximum_gap'=>$query[0]['sip_maximum_gap'], 
                       // 'sip_installment_gap'=>$query[0]['sip_installment_gap'], 
                       // 'sip_status'=>$query[0]['sip_status'], 
                        'minimum_purchase_amount'=>$query[0]['minimum_purchase_amount'], 
                        'maximum_purchase_amount'=>$query[0]['maximum_purchase_amount'], 
                        'purchase_amount_multiplier'=>$query[0]['purchase_amount_multiplier'], 
                       // 'sip_minimum_installment_numbers'=>$query[0]['sip_minimum_installment_numbers'], 
                       // 'sip_maximum_installment_numbers'=>$query[0]['sip_maximum_installment_numbers'], 
                        'isin'=>$query[0]['isin'], 
                        'isin_fetch_data' => $value['isin_fetch_data'],
                        'image_name'=>$this->getImageName(str_replace("\r", '', $query[0]['amc_id'])),
                        //'isin_fetch_data' => $isin_fetch_data,
                        'scheme_type'=>$query[0]['scheme_type'], 
                        'is_robo'=>$query[0]['is_robo'], 
                        'amc_id'=>str_replace("\r", '', $query[0]['amc_id']),
                        'scheme_allocated_amount'=>$value['split_amount'],
                      //  "plan_date"=>$next_plan_date
                    );

                //}
                
            }
                             

            foreach ($resultArry1 as $key=>$value) {
                //echo $key;

                $final_scheme_unselected1[]=array(
                    'amc_code'=>$value['amc_code'],
                    //'amc_name'=>$value['amc_name'],
                    'scheme_code'=>$value['scheme_code'],
                    'scheme_name'=>$value['scheme_name'],
                    'purchase_transaction_mode'=>$value['purchase_transaction_mode'],
                    //'sip_frequency'=>$value['sip_frequency'],
                  //  'sip_dates'=>$value['sip_dates'], 
                   // 'sip_minimum_gap'=>$value['sip_minimum_gap'], 
                    //'sip_maximum_gap'=>$value['sip_maximum_gap'], 
                    //'sip_installment_gap'=>$value['sip_installment_gap'], 
                    //'sip_status'=>$value['sip_status'], 
                    'minimum_purchase_amount'=>$value['minimum_purchase_amount'], 
                    'maximum_purchase_amount'=>$value['maximum_purchase_amount'], 
                    'purchase_amount_multiplier'=>$value['purchase_amount_multiplier'], 
                    //'sip_minimum_installment_numbers'=>$value['sip_minimum_installment_numbers'], 
                    //'sip_maximum_installment_numbers'=>$value['sip_maximum_installment_numbers'], 
                    'isin'=>$value['isin'], 
                    'isin_fetch_data' => $value['isin_fetch_data'],
                    'image_name'=>$this->getImageName(str_replace("\r", '', $value['amc_id'])),

                    'scheme_type'=>$value['scheme_type'], 
                    'is_robo'=>$value['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $value['amc_id']),
                    //"plan_date"=>$next_plan_date
                );
                unset($resultArry[$key]);
                break;

            }
            //    $result=array_merge($result_array,$result_array1);
            $result = array
                (
                "SELECT_ARRAY"=> array_merge($result_array,$result_array1),
                "UNSELECT_ARRAY"=> array_merge($final_scheme_unselected,$final_scheme_unselected1),
                "EQUITY_PER"=> $equity_per,
                "BALANCE_PER"=> $balance_per,
               
            );
        }

        if($risk_type=="MODERATE"){

            $amc_ids = $this->activeAMCId();

            /*if($ALLOCATED_EQUITY_AMOUNT < 3000 && $ALLOCATED_EQUITY_AMOUNT >= 2000){
                $limit=2;
                if($ALLOCATED_EQUITY_AMOUNT==2500){
                    $split1=1500;
                    $split2=1000;   
                }
                else{
                    $split1=1000;
                    $split2=1000;

                }  
                $split_array=array($split1,$split2);

            }else if($ALLOCATED_EQUITY_AMOUNT < 2000){

                $limit=1;
                $split1=$ALLOCATED_EQUITY_AMOUNT;
                $split_array[]=$split1;

            }else
            {
                $limit=3;
                $split_array= $this->splitAmount($limit,$ALLOCATED_EQUITY_AMOUNT);
                $split1=$split_array[0];
                $split2=$split_array[1];
                $split3=$split_array[2];
            }   


            if($ALLOCATED_BALANCED_AMOUNT < 3000 && $ALLOCATED_BALANCED_AMOUNT >= 2000){
                $limit1=2;
                if($ALLOCATED_BALANCED_AMOUNT==2500){
                    $split1=1500;
                    $split2=1000;   
                }
                else{
                    $split1=1000;
                    $split2=1000;

                }  
                $split_array1=array($split1,$split2);


            }else if($ALLOCATED_BALANCED_AMOUNT < 2000){

                $limit1=1;
                $split1=$ALLOCATED_BALANCED_AMOUNT;
                $split_array1[]=$split1;
            }else
            {
                $limit1=3;
                $split_array1= $this->splitAmount($limit1,$ALLOCATED_BALANCED_AMOUNT);

                $split1=$split_array1[0];
                $split2=$split_array1[1];
                $split3=$split_array1[2];
            }*/

            $scheme_allocated_amount =round(($ALLOCATED_EQUITY_AMOUNT/$limit),0);
            $scheme_allocated_amount1 =round(($ALLOCATED_BALANCED_AMOUNT/$limit1),0);
            
            $max_count = max($split_array);
            
            /*select *  from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND `dividend_reinvestment_flag` = 'Z' AND  (`amc_id` != '10' AND `amc_id` != '15' and amc_id!='16' and amc_id!='9' and amc_id!='12')  AND scheme_type='EQUITY' ORDER by rand() limit 3"*/

            /*select *  from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND `minimum_purchase_amount` <= '$max_count' AND `dividend_reinvestment_flag` = 'Z' AND  (`amc_id` != '10' AND `amc_id` != '15' and amc_id!='16' and amc_id!='9' and amc_id!='12')  AND scheme_type='EQUITY'  group by amc_id ORDER by rand()*/

            $sql="select DISTINCT amc_id,id,unique_no,scheme_code,rta_scheme_code,amc_scheme_code,isin,amc_code,scheme_type,scheme_plan,scheme_name,purchase_allowed,purchase_transaction_mode,minimum_purchase_amount,additional_purchase_amount,maximum_purchase_amount,purchase_amount_multiplier,purchase_cutoff_time,redemption_allowed,redemption_transaction_mode,minimum_redemption_qty,redemption_qty_multiplier,maximum_redemption_qty,redemption_amount_minimum,redemption_amount_maximum,redemption_amount_multiple,redemption_cut_off_time,rta_agent_code,amc_active_flag,dividend_reinvestment_flag,sip_flag,stp_flag,swp_flag,switch_flag,settlemnt_type,amc_ind,face_value,start_date,end_date,exit_load_flag,exit_load,lock_in_period_flag,lock_In_period,channel_partner_code  from bse_schemes WHERE  `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND `minimum_purchase_amount` <= '$max_count' AND `dividend_reinvestment_flag` = 'Z' AND  `amc_id` IN (".$amc_ids.") AND scheme_type='EQUITY'   ORDER by rand() ";

            $query= $this->db->query($sql)
                ->result_array();

            $result_scheme_array=$this->schemeAvailLump($query,$max_count,$day,$limit,$split_array);

            $resultArry = $result_scheme_array['resultArry'];
            $final_scheme_selected = $result_scheme_array['final_scheme_selected'];

            if(count($final_scheme_selected)<$limit){


                $limit = count($final_scheme_selected);
                $split_array = $this->slipAmountByLimitLumpsum($limit,$ALLOCATED_EQUITY_AMOUNT);

                $max_count=max($split_array);

                /*select *  from bse_schemes WHERE  `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND `minimum_purchase_amount` <= '$max_count' AND `dividend_reinvestment_flag` = 'Z' AND  (`amc_id` != '10' AND `amc_id` != '15' and amc_id!='16' and amc_id!='9' and amc_id!='12')  AND scheme_type='EQUITY'  group by amc_id ORDER by rand()*/

                $sql="select DISTINCT amc_id,id,unique_no,scheme_code,rta_scheme_code,amc_scheme_code,isin,amc_code,scheme_type,scheme_plan,scheme_name,purchase_allowed,purchase_transaction_mode,minimum_purchase_amount,additional_purchase_amount,maximum_purchase_amount,purchase_amount_multiplier,purchase_cutoff_time,redemption_allowed,redemption_transaction_mode,minimum_redemption_qty,redemption_qty_multiplier,maximum_redemption_qty,redemption_amount_minimum,redemption_amount_maximum,redemption_amount_multiple,redemption_cut_off_time,rta_agent_code,amc_active_flag,dividend_reinvestment_flag,sip_flag,stp_flag,swp_flag,switch_flag,settlemnt_type,amc_ind,face_value,start_date,end_date,exit_load_flag,exit_load,lock_in_period_flag,lock_In_period,channel_partner_code   from bse_schemes WHERE  `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND `minimum_purchase_amount` <= '$max_count' AND `dividend_reinvestment_flag` = 'Z' AND  `amc_id` IN (".$amc_ids.") AND scheme_type='EQUITY'   ORDER by rand() ";
                $query= $this->db->query($sql)
                    ->result_array();

                $result_scheme_array=$this->schemeAvailLump($query,$max_count,$day,$limit,$split_array);
                $resultArry = $result_scheme_array['resultArry'];
                $final_scheme_selected = $result_scheme_array['final_scheme_selected'];
            }



            /*unSelected Final scheme array */
            //echo "<pre>";print_r($resultArry);exit();
            foreach ($resultArry as $key=>$value) {
                //echo $key;

                $final_scheme_unselected[]=array(
                    'amc_code'=>$value['amc_code'],
                    //'amc_name'=>$value['amc_name'],
                    'scheme_code'=>$value['scheme_code'],
                    'scheme_name'=>$value['scheme_name'],
                    'purchase_transaction_mode'=>$value['purchase_transaction_mode'],
                    //'sip_frequency'=>$value['sip_frequency'],
                    //'sip_dates'=>$value['sip_dates'], 
                   /// 'sip_minimum_gap'=>$value['sip_minimum_gap'], 
                  //  'sip_maximum_gap'=>$value['sip_maximum_gap'], 
                   // 'sip_installment_gap'=>$value['sip_installment_gap'], 
                  //  'sip_status'=>$value['sip_status'], 
                    'minimum_purchase_amount'=>$value['minimum_purchase_amount'], 
                    'maximum_purchase_amount'=>$value['maximum_purchase_amount'], 
                    'purchase_amount_multiplier'=>$value['purchase_amount_multiplier'], 
                    //'sip_minimum_installment_numbers'=>$value['sip_minimum_installment_numbers'], 
                   // 'sip_maximum_installment_numbers'=>$value['sip_maximum_installment_numbers'], 
                    'isin'=>$value['isin'], 
                    'isin_fetch_data' => $value['isin_fetch_data'],
                    'image_name'=>$this->getImageName(str_replace("\r", '', $value['amc_id'])),
                    'scheme_type'=>$value['scheme_type'], 
                    'is_robo'=>$value['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $value['amc_id']),
                    //"plan_date"=>$day
                );
                unset($resultArry[$key]);
                break;

            }

            //echo "<pre>";
            //   print_r($final_scheme_unselected);exit();
            /*          echo "<pre>";
             print_r($final_scheme_unselected);*/


            // ($final_schem);
/*select *  from bse_schemes WHERE  scheme_code='".$value['scheme_code']."' AND  `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND `minimum_purchase_amount` <= '$max_count' AND `dividend_reinvestment_flag` = 'Z' AND  (`amc_id` != '10' AND `amc_id` != '15' and amc_id!='16' and amc_id!='9' and amc_id!='12')  AND scheme_type='EQUITY'  group by amc_id ORDER by rand()*/

            foreach ($final_scheme_selected as  $value) {
                $sql="select *  from bse_schemes WHERE  scheme_code='".$value['scheme_code']."' AND  `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND `minimum_purchase_amount` <= '$max_count' AND `dividend_reinvestment_flag` = 'Z' AND  `amc_id` IN (".$amc_ids.") AND scheme_type='EQUITY' " ;
                $query = $this->db->query($sql)
                    ->result_array();
                //$query[0]
                $result_array[]=array(
                     'amc_code'=>$query[0]['amc_code'],
                  //  'amc_name'=>$query[0]['amc_name'],
                    'scheme_code'=>$query[0]['scheme_code'],
                    'scheme_name'=>$query[0]['scheme_name'],
                    'purchase_transaction_mode'=>$query[0]['purchase_transaction_mode'],
                   // 'sip_frequency'=>$query[0]['sip_frequency'],
                    //'sip_dates'=>$query[0]['sip_dates'], 
                    //'sip_minimum_gap'=>$query[0]['sip_minimum_gap'], 
                    //'sip_maximum_gap'=>$query[0]['sip_maximum_gap'], 
                    //'sip_installment_gap'=>$query[0]['sip_installment_gap'], 
                   // 'sip_status'=>$query[0]['sip_status'], 
                    'minimum_purchase_amount'=>$query[0]['minimum_purchase_amount'], 
                    'maximum_purchase_amount'=>$query[0]['maximum_purchase_amount'], 
                    'purchase_amount_multiplier'=>$query[0]['purchase_amount_multiplier'], 
                    //'sip_minimum_installment_numbers'=>$query[0]['sip_minimum_installment_numbers'], 
                    //'sip_maximum_installment_numbers'=>$query[0]['sip_maximum_installment_numbers'], 
                    'isin'=>$query[0]['isin'], 
                    'isin_fetch_data' => $value['isin_fetch_data'],
                    'image_name'=>$this->getImageName(str_replace("\r", '', $query[0]['amc_id'])),

                    'scheme_type'=>$query[0]['scheme_type'], 
                    'is_robo'=>$query[0]['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $query[0]['amc_id']),
                    'scheme_allocated_amount'=>$value['split_amount'],
                   // "plan_date"=>$day
                );

            }


            $max_count = max($split_array1);
            /*
            select *  from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND  minimum_purchase_amount <= '$max_count'  AND  `dividend_reinvestment_flag` = 'Z' AND  (`amc_id` != '10' AND `amc_id` != '15' and amc_id!='16' and amc_id!='9' and amc_id!='12')  AND scheme_type='BALANCED' group by amc_id ORDER by rand()
            */
            $sql1="select DISTINCT amc_id,id,unique_no,scheme_code,rta_scheme_code,amc_scheme_code,isin,amc_code,scheme_type,scheme_plan,scheme_name,purchase_allowed,purchase_transaction_mode,minimum_purchase_amount,additional_purchase_amount,maximum_purchase_amount,purchase_amount_multiplier,purchase_cutoff_time,redemption_allowed,redemption_transaction_mode,minimum_redemption_qty,redemption_qty_multiplier,maximum_redemption_qty,redemption_amount_minimum,redemption_amount_maximum,redemption_amount_multiple,redemption_cut_off_time,rta_agent_code,amc_active_flag,dividend_reinvestment_flag,sip_flag,stp_flag,swp_flag,switch_flag,settlemnt_type,amc_ind,face_value,start_date,end_date,exit_load_flag,exit_load,lock_in_period_flag,lock_In_period,channel_partner_code  from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND  minimum_purchase_amount <= '$max_count'  AND  `dividend_reinvestment_flag` = 'Z' AND  `amc_id` IN (".$amc_ids.") AND scheme_type='BALANCED'  ORDER by rand() ";
 $query1= $this->db->query($sql1)
                ->result_array();
                $next_plan_date=$day;



           

            $result_scheme_array=$this->schemeAvailLump($query1,$max_count,$day,$limit1,$split_array1);



            $resultArry1 = $result_scheme_array['resultArry'];
            $final_scheme_selected1 = $result_scheme_array['final_scheme_selected'];

            if(count($final_scheme_selected1)<$limit1){


                $limit1 = count($final_scheme_selected1);
                $split_array1 = $this->slipAmountByLimitLumpsum($limit1,$ALLOCATED_BALANCED_AMOUNT);

                $max_count=max($split_array1);

                $sql1="select DISTINCT amc_id,id,unique_no,scheme_code,rta_scheme_code,amc_scheme_code,isin,amc_code,scheme_type,scheme_plan,scheme_name,purchase_allowed,purchase_transaction_mode,minimum_purchase_amount,additional_purchase_amount,maximum_purchase_amount,purchase_amount_multiplier,purchase_cutoff_time,redemption_allowed,redemption_transaction_mode,minimum_redemption_qty,redemption_qty_multiplier,maximum_redemption_qty,redemption_amount_minimum,redemption_amount_maximum,redemption_amount_multiple,redemption_cut_off_time,rta_agent_code,amc_active_flag,dividend_reinvestment_flag,sip_flag,stp_flag,swp_flag,switch_flag,settlemnt_type,amc_ind,face_value,start_date,end_date,exit_load_flag,exit_load,lock_in_period_flag,lock_In_period,channel_partner_code  from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND  minimum_purchase_amount <= '$max_count'  AND  `dividend_reinvestment_flag` = 'Z' AND  `amc_id` IN (".$amc_ids.") AND scheme_type='BALANCED'  ORDER by rand() ";


                $query1= $this->db->query($sql1)
                    ->result_array();


                $result_scheme_array=$this->schemeAvailLump($query1,$max_count,$day,$limit1,$split_array1);
                $resultArry1 = $result_scheme_array['resultArry'];
                $final_scheme_selected1 = $result_scheme_array['final_scheme_selected'];
            }


            foreach ($final_scheme_selected1 as  $value) {

                $sql="select DISTINCT amc_id,id,unique_no,scheme_code,rta_scheme_code,amc_scheme_code,isin,amc_code,scheme_type,scheme_plan,scheme_name,purchase_allowed,purchase_transaction_mode,minimum_purchase_amount,additional_purchase_amount,maximum_purchase_amount,purchase_amount_multiplier,purchase_cutoff_time,redemption_allowed,redemption_transaction_mode,minimum_redemption_qty,redemption_qty_multiplier,maximum_redemption_qty,redemption_amount_minimum,redemption_amount_maximum,redemption_amount_multiple,redemption_cut_off_time,rta_agent_code,amc_active_flag,dividend_reinvestment_flag,sip_flag,stp_flag,swp_flag,switch_flag,settlemnt_type,amc_ind,face_value,start_date,end_date,exit_load_flag,exit_load,lock_in_period_flag,lock_In_period,channel_partner_code   from bse_schemes WHERE  scheme_code='".$value['scheme_code']."' AND `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND  minimum_purchase_amount <= '$max_count'  AND  `dividend_reinvestment_flag` = 'Z' AND  `amc_id` IN (".$amc_ids.") AND scheme_type='BALANCED'  ORDER by rand()" ;
                $query = $this->db->query($sql)
                    ->result_array();

                

                //$query[0]
                $result_array1[]=array(
                    'amc_code'=>$query[0]['amc_code'],
                    //'amc_name'=>$query[0]['amc_name'],
                    'scheme_code'=>$query[0]['scheme_code'],
                    'scheme_name'=>$query[0]['scheme_name'],
                    'purchase_transaction_mode'=>$query[0]['purchase_transaction_mode'],
                    //'sip_frequency'=>$query[0]['sip_frequency'],
                    //'sip_dates'=>$query[0]['sip_dates'], 
                    //'sip_minimum_gap'=>$query[0]['sip_minimum_gap'], 
                   // 'sip_maximum_gap'=>$query[0]['sip_maximum_gap'], 
                   // 'sip_installment_gap'=>$query[0]['sip_installment_gap'], 
                   // 'sip_status'=>$query[0]['sip_status'], 
                    'minimum_purchase_amount'=>$query[0]['minimum_purchase_amount'], 
                    'maximum_purchase_amount'=>$query[0]['maximum_purchase_amount'], 
                    'purchase_amount_multiplier'=>$query[0]['purchase_amount_multiplier'], 
                   // 'sip_minimum_installment_numbers'=>$query[0]['sip_minimum_installment_numbers'], 
                   // 'sip_maximum_installment_numbers'=>$query[0]['sip_maximum_installment_numbers'], 
                    'isin'=>$query[0]['isin'], 
                    'isin_fetch_data' => $value['isin_fetch_data'],
                    'image_name'=>$this->getImageName(str_replace("\r", '', $query[0]['amc_id'])),
                    'scheme_type'=>$query[0]['scheme_type'], 
                    'is_robo'=>$query[0]['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $query[0]['amc_id']),
                    'scheme_allocated_amount'=>$value['split_amount'],
                  //  "plan_date"=>$next_plan_date
                );
            }
            $final_scheme_unselected1=array();
            foreach ($resultArry1 as $key=>$value) {
                //echo $key;
                    //echo $key;
               
                $final_scheme_unselected1[]=array(
                    'amc_code'=>$value['amc_code'],
                    //'amc_name'=>$value['amc_name'],
                    'scheme_code'=>$value['scheme_code'],
                    'scheme_name'=>$value['scheme_name'],
                    'purchase_transaction_mode'=>$value['purchase_transaction_mode'],
                    //'sip_frequency'=>$value['sip_frequency'],
                  //  'sip_dates'=>$value['sip_dates'], 
                   // 'sip_minimum_gap'=>$value['sip_minimum_gap'], 
                    //'sip_maximum_gap'=>$value['sip_maximum_gap'], 
                    //'sip_installment_gap'=>$value['sip_installment_gap'], 
                    //'sip_status'=>$value['sip_status'], 
                    'minimum_purchase_amount'=>$value['minimum_purchase_amount'], 
                    'maximum_purchase_amount'=>$value['maximum_purchase_amount'], 
                    'purchase_amount_multiplier'=>$value['purchase_amount_multiplier'], 
                    //'sip_minimum_installment_numbers'=>$value['sip_minimum_installment_numbers'], 
                    //'sip_maximum_installment_numbers'=>$value['sip_maximum_installment_numbers'], 
                    'isin'=>$value['isin'], 
                    'isin_fetch_data' => $value['isin_fetch_data'],
                    'image_name'=>$this->getImageName(str_replace("\r", '', $value['amc_id'])),
                    'scheme_type'=>$value['scheme_type'], 
                    'is_robo'=>$value['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $value['amc_id']),
                    //"plan_date"=>$next_plan_date
                );
                unset($resultArry1[$key]);
                break;

            }


            // echo "<pre>"; 
            // print_r($result_array); 
            // echo "<pre>"; echo ".........";
            // print_r($result_array1); 
            // exit;
            $result = array
                (
                "SELECT_ARRAY"=> array_merge($result_array,$result_array1),
                "UNSELECT_ARRAY"=>array_merge($final_scheme_unselected,$final_scheme_unselected1),
                "EQUITY_PER"=> $equity_per,
                "BALANCE_PER"=> $balance_per
            );

        }

        if($risk_type=="CONSERVATIVE"){

            $amc_ids = $this->activeAMCId();

           $scheme_allocated_amount =round(($ALLOCATED_EQUITY_AMOUNT/$limit),0);
          $scheme_allocated_amount1 =round(($ALLOCATED_BALANCED_AMOUNT/$limit1),0);


            $max_count=max($split_array);


            $sql="select DISTINCT amc_id,id,unique_no,scheme_code,rta_scheme_code,amc_scheme_code,isin,amc_code,scheme_type,scheme_plan,scheme_name,purchase_allowed,purchase_transaction_mode,minimum_purchase_amount,additional_purchase_amount,maximum_purchase_amount,purchase_amount_multiplier,purchase_cutoff_time,redemption_allowed,redemption_transaction_mode,minimum_redemption_qty,redemption_qty_multiplier,maximum_redemption_qty,redemption_amount_minimum,redemption_amount_maximum,redemption_amount_multiple,redemption_cut_off_time,rta_agent_code,amc_active_flag,dividend_reinvestment_flag,sip_flag,stp_flag,swp_flag,switch_flag,settlemnt_type,amc_ind,face_value,start_date,end_date,exit_load_flag,exit_load,lock_in_period_flag,lock_In_period,channel_partner_code  from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND `minimum_purchase_amount` <= '$max_count' AND `dividend_reinvestment_flag` = 'Z' AND  `amc_id` IN (".$amc_ids.") AND scheme_type='EQUITY'  ORDER by rand() ";
            $query= $this->db->query($sql)
                ->result_array();


            $result_scheme_array=$this->schemeAvailLump($query,$max_count,$day,$limit,$split_array);

            //print_r($result_scheme_array);exit;
            
            $resultArry = $result_scheme_array['resultArry'];
            $final_scheme_selected = $result_scheme_array['final_scheme_selected'];

            
            if(count($final_scheme_selected)<=$limit){


                $limit = count($final_scheme_selected);

                $split_array = $this->slipAmountByLimitLumpsum($limit,$ALLOCATED_EQUITY_AMOUNT);

                $max_count=max($split_array);

                $sql="select DISTINCT amc_id,id,unique_no,scheme_code,rta_scheme_code,amc_scheme_code,isin,amc_code,scheme_type,scheme_plan,scheme_name,purchase_allowed,purchase_transaction_mode,minimum_purchase_amount,additional_purchase_amount,maximum_purchase_amount,purchase_amount_multiplier,purchase_cutoff_time,redemption_allowed,redemption_transaction_mode,minimum_redemption_qty,redemption_qty_multiplier,maximum_redemption_qty,redemption_amount_minimum,redemption_amount_maximum,redemption_amount_multiple,redemption_cut_off_time,rta_agent_code,amc_active_flag,dividend_reinvestment_flag,sip_flag,stp_flag,swp_flag,switch_flag,settlemnt_type,amc_ind,face_value,start_date,end_date,exit_load_flag,exit_load,lock_in_period_flag,lock_In_period,channel_partner_code   from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND `minimum_purchase_amount` <= '$max_count' AND `dividend_reinvestment_flag` = 'Z' AND  `amc_id` IN (".$amc_ids.") AND scheme_type='EQUITY'   ORDER by rand() ";
                $query= $this->db->query($sql)
                    ->result_array();

                $result_scheme_array=$this->schemeAvailLump($query,$max_count,$day,$limit,$split_array);

                $resultArry = $result_scheme_array['resultArry'];
                $final_scheme_selected = $result_scheme_array['final_scheme_selected'];

            }

            foreach ($resultArry as $key=>$value) {
                //echo $key;

                $final_scheme_unselected[]=array(
                    'amc_code'=>$value['amc_code'],
                    //'amc_name'=>$value['amc_name'],
                    'scheme_code'=>$value['scheme_code'],
                    'scheme_name'=>$value['scheme_name'],
                    'purchase_transaction_mode'=>$value['purchase_transaction_mode'],
                    //'sip_frequency'=>$value['sip_frequency'],
                    //'sip_dates'=>$value['sip_dates'], 
                   /// 'sip_minimum_gap'=>$value['sip_minimum_gap'], 
                  //  'sip_maximum_gap'=>$value['sip_maximum_gap'], 
                   // 'sip_installment_gap'=>$value['sip_installment_gap'], 
                  //  'sip_status'=>$value['sip_status'], 
                    'minimum_purchase_amount'=>$value['minimum_purchase_amount'], 
                    'maximum_purchase_amount'=>$value['maximum_purchase_amount'], 
                    'purchase_amount_multiplier'=>$value['purchase_amount_multiplier'], 
                    //'sip_minimum_installment_numbers'=>$value['sip_minimum_installment_numbers'], 
                   // 'sip_maximum_installment_numbers'=>$value['sip_maximum_installment_numbers'], 
                    'isin'=>$value['isin'], 
                    'isin_fetch_data' => $value['isin_fetch_data'],
                    'image_name'=>$this->getImageName(str_replace("\r", '', $value['amc_id'])),
                    'scheme_type'=>$value['scheme_type'], 
                    'is_robo'=>$value['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $value['amc_id']),
                    //"plan_date"=>$day
                );
                unset($resultArry[$key]);
                break;

            }
            //echo "<pre>";
            //   print_r($final_scheme_unselected);exit();

            // ($final_schem);

            foreach ($final_scheme_selected as  $value) {
               
                


                $sql="select DISTINCT amc_id,id,unique_no,scheme_code,rta_scheme_code,amc_scheme_code,isin,amc_code,scheme_type,scheme_plan,scheme_name,purchase_allowed,purchase_transaction_mode,minimum_purchase_amount,additional_purchase_amount,maximum_purchase_amount,purchase_amount_multiplier,purchase_cutoff_time,redemption_allowed,redemption_transaction_mode,minimum_redemption_qty,redemption_qty_multiplier,maximum_redemption_qty,redemption_amount_minimum,redemption_amount_maximum,redemption_amount_multiple,redemption_cut_off_time,rta_agent_code,amc_active_flag,dividend_reinvestment_flag,sip_flag,stp_flag,swp_flag,switch_flag,settlemnt_type,amc_ind,face_value,start_date,end_date,exit_load_flag,exit_load,lock_in_period_flag,lock_In_period,channel_partner_code  from bse_schemes WHERE  scheme_code='".$value['scheme_code']."' AND  `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND `minimum_purchase_amount` <= '$max_count' AND `dividend_reinvestment_flag` = 'Z' AND  `amc_id` IN (".$amc_ids.") AND scheme_type='EQUITY'  ORDER by rand()" ;

                $query = $this->db->query($sql)
                    ->result_array();

                //$query[0]
                $next_plan_date=$day;
                $result_array[]=array(
                    'amc_code'=>$query[0]['amc_code'],
                  //  'amc_name'=>$query[0]['amc_name'],
                    'scheme_code'=>$query[0]['scheme_code'],
                    'scheme_name'=>$query[0]['scheme_name'],
                    'purchase_transaction_mode'=>$query[0]['purchase_transaction_mode'],
                   // 'sip_frequency'=>$query[0]['sip_frequency'],
                    //'sip_dates'=>$query[0]['sip_dates'], 
                    //'sip_minimum_gap'=>$query[0]['sip_minimum_gap'], 
                    //'sip_maximum_gap'=>$query[0]['sip_maximum_gap'], 
                    //'sip_installment_gap'=>$query[0]['sip_installment_gap'], 
                   // 'sip_status'=>$query[0]['sip_status'], 
                    'minimum_purchase_amount'=>$query[0]['minimum_purchase_amount'], 
                    'maximum_purchase_amount'=>$query[0]['maximum_purchase_amount'], 
                    'purchase_amount_multiplier'=>$query[0]['purchase_amount_multiplier'], 
                    //'sip_minimum_installment_numbers'=>$query[0]['sip_minimum_installment_numbers'], 
                    //'sip_maximum_installment_numbers'=>$query[0]['sip_maximum_installment_numbers'], 
                    'isin'=>$query[0]['isin'], 
                    'isin_fetch_data' => $value['isin_fetch_data'],
                    'image_name'=>$this->getImageName(str_replace("\r", '', $query[0]['amc_id'])),
                    'scheme_type'=>$query[0]['scheme_type'], 
                    'is_robo'=>$query[0]['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $query[0]['amc_id']),
                    'scheme_allocated_amount'=>$value['split_amount'],
                   // "plan_date"=>$day
                );

            }

            $max_count=max($split_array1);

            //if($max_count >=0)

          
                /*
                */
            $sql1="select DISTINCT amc_id,id,unique_no,scheme_code,rta_scheme_code,amc_scheme_code,isin,amc_code,scheme_type,scheme_plan,scheme_name,purchase_allowed,purchase_transaction_mode,minimum_purchase_amount,additional_purchase_amount,maximum_purchase_amount,purchase_amount_multiplier,purchase_cutoff_time,redemption_allowed,redemption_transaction_mode,minimum_redemption_qty,redemption_qty_multiplier,maximum_redemption_qty,redemption_amount_minimum,redemption_amount_maximum,redemption_amount_multiple,redemption_cut_off_time,rta_agent_code,amc_active_flag,dividend_reinvestment_flag,sip_flag,stp_flag,swp_flag,switch_flag,settlemnt_type,amc_ind,face_value,start_date,end_date,exit_load_flag,exit_load,lock_in_period_flag,lock_In_period,channel_partner_code   from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND  minimum_purchase_amount <= '$max_count'  AND  `dividend_reinvestment_flag` = 'Z' AND  `amc_id` IN (".$amc_ids.") AND scheme_type='BALANCED'  ORDER by rand() ";
                $query1= $this->db->query($sql1)
                ->result_array();
                $next_plan_date=$day;
       
     

            $result_scheme_array=$this->schemeAvailLump($query1,$max_count,$day,$limit1,$split_array1);



            $resultArry1 = $result_scheme_array['resultArry'];

            $final_scheme_selected1 = $result_scheme_array['final_scheme_selected'];

            if(count($final_scheme_selected1)<$limit1 ){


                $limit1 = count($final_scheme_selected1);
                $split_array1 = $this->slipAmountByLimitLumpsum($limit1,$ALLOCATED_BALANCED_AMOUNT);

                $max_count=max($split_array1);

        
                $sql1="select DISTINCT amc_id,id,unique_no,scheme_code,rta_scheme_code,amc_scheme_code,isin,amc_code,scheme_type,scheme_plan,scheme_name,purchase_allowed,purchase_transaction_mode,minimum_purchase_amount,additional_purchase_amount,maximum_purchase_amount,purchase_amount_multiplier,purchase_cutoff_time,redemption_allowed,redemption_transaction_mode,minimum_redemption_qty,redemption_qty_multiplier,maximum_redemption_qty,redemption_amount_minimum,redemption_amount_maximum,redemption_amount_multiple,redemption_cut_off_time,rta_agent_code,amc_active_flag,dividend_reinvestment_flag,sip_flag,stp_flag,swp_flag,switch_flag,settlemnt_type,amc_ind,face_value,start_date,end_date,exit_load_flag,exit_load,lock_in_period_flag,lock_In_period,channel_partner_code  from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND  minimum_purchase_amount <= '$max_count'  AND  `dividend_reinvestment_flag` = 'Z' AND  `amc_id` IN (".$amc_ids.") AND scheme_type='BALANCED'  ORDER by rand()";
              $query1= $this->db->query($sql1)
                    ->result_array();

                $result_scheme_array=$this->schemeAvailLump($query1,$max_count,$day,$limit1,$split_array1);
                $resultArry1 = $result_scheme_array['resultArry'];

                $final_scheme_selected1 = $result_scheme_array['final_scheme_selected'];
            }

            foreach ($final_scheme_selected1 as  $value) {
                /**/
             
                $sql="select DISTINCT amc_id,id,unique_no,scheme_code,rta_scheme_code,amc_scheme_code,isin,amc_code,scheme_type,scheme_plan,scheme_name,purchase_allowed,purchase_transaction_mode,minimum_purchase_amount,additional_purchase_amount,maximum_purchase_amount,purchase_amount_multiplier,purchase_cutoff_time,redemption_allowed,redemption_transaction_mode,minimum_redemption_qty,redemption_qty_multiplier,maximum_redemption_qty,redemption_amount_minimum,redemption_amount_maximum,redemption_amount_multiple,redemption_cut_off_time,rta_agent_code,amc_active_flag,dividend_reinvestment_flag,sip_flag,stp_flag,swp_flag,switch_flag,settlemnt_type,amc_ind,face_value,start_date,end_date,exit_load_flag,exit_load,lock_in_period_flag,lock_In_period,channel_partner_code  from bse_schemes WHERE  scheme_code='".$value['scheme_code']."' AND `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND  minimum_purchase_amount <= '$max_count'  AND  `dividend_reinvestment_flag` = 'Z' AND  `amc_id` IN (".$amc_ids.") AND scheme_type='BALANCED'  ORDER by rand()" ;
                $query = $this->db->query($sql)
                    ->result_array();
                    $next_plan_date=$day;
    
                //$query[0]
                $result_array1[]=array(
                    'amc_code'=>$query[0]['amc_code'],
                    //'amc_name'=>$query[0]['amc_name'],
                    'scheme_code'=>$query[0]['scheme_code'],
                    'scheme_name'=>$query[0]['scheme_name'],
                    'purchase_transaction_mode'=>$query[0]['purchase_transaction_mode'],
                    //'sip_frequency'=>$query[0]['sip_frequency'],
                    //'sip_dates'=>$query[0]['sip_dates'], 
                    //'sip_minimum_gap'=>$query[0]['sip_minimum_gap'], 
                   // 'sip_maximum_gap'=>$query[0]['sip_maximum_gap'], 
                   // 'sip_installment_gap'=>$query[0]['sip_installment_gap'], 
                   // 'sip_status'=>$query[0]['sip_status'], 
                    'minimum_purchase_amount'=>$query[0]['minimum_purchase_amount'], 
                    'maximum_purchase_amount'=>$query[0]['maximum_purchase_amount'], 
                    'purchase_amount_multiplier'=>$query[0]['purchase_amount_multiplier'], 
                   // 'sip_minimum_installment_numbers'=>$query[0]['sip_minimum_installment_numbers'], 
                   // 'sip_maximum_installment_numbers'=>$query[0]['sip_maximum_installment_numbers'], 
                    'isin'=>$query[0]['isin'], 
                    'isin_fetch_data' => $value['isin_fetch_data'],
                    'image_name'=>$this->getImageName(str_replace("\r", '', $query[0]['amc_id'])),
                    'scheme_type'=>$query[0]['scheme_type'], 
                    'is_robo'=>$query[0]['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $query[0]['amc_id']),
                    'scheme_allocated_amount'=>$value['split_amount'],
                  //  "plan_date"=>$next_plan_date
                );
            }
                             

            foreach ($resultArry1 as $key=>$value) {
                //echo $key;

                $final_scheme_unselected1[]=array(
                    'amc_code'=>$value['amc_code'],
                    //'amc_name'=>$value['amc_name'],
                    'scheme_code'=>$value['scheme_code'],
                    'scheme_name'=>$value['scheme_name'],
                    'purchase_transaction_mode'=>$value['purchase_transaction_mode'],
                    //'sip_frequency'=>$value['sip_frequency'],
                  //  'sip_dates'=>$value['sip_dates'], 
                   // 'sip_minimum_gap'=>$value['sip_minimum_gap'], 
                    //'sip_maximum_gap'=>$value['sip_maximum_gap'], 
                    //'sip_installment_gap'=>$value['sip_installment_gap'], 
                    //'sip_status'=>$value['sip_status'], 
                    'minimum_purchase_amount'=>$value['minimum_purchase_amount'], 
                    'maximum_purchase_amount'=>$value['maximum_purchase_amount'], 
                    'purchase_amount_multiplier'=>$value['purchase_amount_multiplier'], 
                    //'sip_minimum_installment_numbers'=>$value['sip_minimum_installment_numbers'], 
                    //'sip_maximum_installment_numbers'=>$value['sip_maximum_installment_numbers'], 
                    'isin'=>$value['isin'], 
                    'isin_fetch_data' => $value['isin_fetch_data'],
                    'image_name'=>$this->getImageName(str_replace("\r", '', $value['amc_id'])),
                    'scheme_type'=>$value['scheme_type'], 
                    'is_robo'=>$value['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $value['amc_id']),
                    //"plan_date"=>$next_plan_date
                );
                unset($resultArry[$key]);
                break;

            }
            //    $result=array_merge($result_array,$result_array1);
            $result = array
                (
                "SELECT_ARRAY"=> array_merge($result_array,$result_array1),
                "UNSELECT_ARRAY"=> array_merge($final_scheme_unselected,$final_scheme_unselected1),
                "EQUITY_PER"=> $equity_per,
                "BALANCE_PER"=> $balance_per,
               
            );
               

        }

        if($risk_type=="ELSS"){
        }

        return $result;


    }
/**/    
    public function robo_master_schemes1($risk_type,$max_count,$amount){

        $risk="SELECT allocated_percent,type_of_funds FROM `master_risk_allocation` WHERE `is_robo_active` = '1' AND `risk_type` = '$risk_type'";
        $risk_array= $this->db->query($risk)
            ->result_array();
        $EQUITY_PER=$risk_array[0]['allocated_percent'];
        $BALANCED_PER=$risk_array[1]['allocated_percent'];

        $ALLOCATED_EQUITY_AMOUNT=($EQUITY_PER * $amount)/100;
        $ALLOCATED_BALANCED_AMOUNT=($BALANCED_PER * $amount)/100;

        if($risk_type=="AGGRESSIVE"){
            $sql="select *  from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND `dividend_reinvestment_flag` = 'Z' AND  (`amc_id` != '10' AND `amc_id` != '15' and amc_id!='16' and amc_id!='9' and amc_id!='12')  AND scheme_type='EQUITY' ORDER by rand() limit 4";

            $sql1="select *  from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND `dividend_reinvestment_flag` = 'Z' AND  (`amc_id` != '10' AND `amc_id` != '15' and amc_id!='16' and amc_id!='9' and amc_id!='12')  AND scheme_type='BALANCED' ORDER by rand() limit 2";

            $query= $this->db->query($sql)
                ->result_array();

            foreach ($query as $key => $value) {
                $scheme_allocated_amount =round(($ALLOCATED_EQUITY_AMOUNT/4),0);
                $query[$key]['scheme_allocated_amount']=$scheme_allocated_amount;
            }


            $query1= $this->db->query($sql1)
                ->result_array();

            foreach ($query1 as $key => $value) {
                $scheme_allocated_amount =round(($ALLOCATED_BALANCED_AMOUNT/2),0);
                $query1[$key]['scheme_allocated_amount']=$scheme_allocated_amount;
            }


            $result= (array_merge($query,$query1));

        }

        if($risk_type=="MODERATE"){
            $sql="select *  from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND `dividend_reinvestment_flag` = 'Z' AND  (`amc_id` != '10' AND `amc_id` != '15' and amc_id!='16' and amc_id!='9' and amc_id!='12')  AND scheme_type='EQUITY' ORDER by rand() limit 3";

            $sql1="select *  from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND `dividend_reinvestment_flag` = 'Z' AND  (`amc_id` != '10' AND `amc_id` != '15' and amc_id!='16' and amc_id!='9' and amc_id!='12')  AND scheme_type='BALANCED' ORDER by rand() limit 3";
            $query= $this->db->query($sql)
                ->result_array();

            foreach ($query as $key => $value) {
                $scheme_allocated_amount =round(($ALLOCATED_EQUITY_AMOUNT/3),0);
                $query[$key]['scheme_allocated_amount']=$scheme_allocated_amount;
            }


            $query1= $this->db->query($sql1)
                ->result_array();

            foreach ($query1 as $key => $value) {
                $scheme_allocated_amount =round(($ALLOCATED_BALANCED_AMOUNT/3),0);
                $query1[$key]['scheme_allocated_amount']=$scheme_allocated_amount;
            }

            $result= (array_merge($query,$query1));
        }

        if($risk_type=="CONSERVATIVE"){
            $sql="select *  from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND `dividend_reinvestment_flag` = 'Z' AND  (`amc_id` != '10' AND `amc_id` != '15' and amc_id!='16' and amc_id!='9' and amc_id!='12')  AND scheme_type='EQUITY' ORDER by rand() limit 2";

            $sql1="select *  from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND `dividend_reinvestment_flag` = 'Z' AND  (`amc_id` != '10' AND `amc_id` != '15' and amc_id!='16' and amc_id!='9' and amc_id!='12')  AND scheme_type='BALANCED' ORDER by rand() limit 4";

            $query= $this->db->query($sql)
                ->result_array();

            foreach ($query as $key => $value) {
                $scheme_allocated_amount =round(($ALLOCATED_EQUITY_AMOUNT/2),0);
                $query[$key]['scheme_allocated_amount']=$scheme_allocated_amount;
            }


            $query1= $this->db->query($sql1)
                ->result_array();

            foreach ($query1 as $key => $value) {
                $scheme_allocated_amount =round(($ALLOCATED_BALANCED_AMOUNT/4),0);
                $query1[$key]['scheme_allocated_amount']=$scheme_allocated_amount;
            }

            $result= (array_merge($query,$query1));
        }

        if($risk_type=="ELSS"){
            $sql="select *  from bse_schemes WHERE `is_robo` = 'Y' and `sip_flag` = 'Y' and scheme_type='ELSS' ORDER by rand() limit 6";
            $query= $this->db->query($sql)
                ->result_array();
            foreach ($query as $key => $value) {
                $scheme_allocated_amount =round(($amount/6),0);
                $query[$key]['scheme_allocated_amount']=$scheme_allocated_amount;
            }

            $result= $query;

        }
        return $result;

    }


    public function robo_sip_master($risk_type,$day,$amount,$age){

        $date= date('d');
        $day= date('d');
      
        $result_array1=array();
        $result_array=array();
        $final_scheme_unselected1=array();
        $final_scheme_unselected=array();
        $risk="SELECT allocated_percent,type_of_funds FROM `master_risk_allocation` WHERE `is_robo_active` = '1' AND `risk_type` = '$risk_type' and min_age <=$age and max_age >=$age";

        $risk_array= $this->db->query($risk)
            ->result_array();

        $EQUITY_PER=$risk_array[0]['allocated_percent'];
        $BALANCED_PER=$risk_array[1]['allocated_percent'];

         $ALLOCATED_EQUITY_AMOUNT=($EQUITY_PER * $amount)/100;
        //$ALLOCATED_BALANCED_AMOUNT=($BALANCED_PER * $amount)/100;

        $AEAMod = $ALLOCATED_EQUITY_AMOUNT%500;
        if($AEAMod>0){
         $ALLOCATED_EQUITY_AMOUNT = (floor($ALLOCATED_EQUITY_AMOUNT/500)*500)+500;
        }

        $ALLOCATED_BALANCED_AMOUNT=$amount- $ALLOCATED_EQUITY_AMOUNT;

        //Newly added for balance minimum 1000
        if($ALLOCATED_BALANCED_AMOUNT==500){
            $ALLOCATED_BALANCED_AMOUNT = 1000;
            $ALLOCATED_EQUITY_AMOUNT = $amount-$ALLOCATED_BALANCED_AMOUNT;
        }

        $min_amount_array=$this->check_min_bal_amount($ALLOCATED_BALANCED_AMOUNT,$amount,$day);
        
        $ALLOCATED_BALANCED_AMOUNT=$min_amount_array['amount'];

        $date_check=$min_amount_array['date_check'];

        $ALLOCATED_EQUITY_AMOUNT=$amount- $ALLOCATED_BALANCED_AMOUNT;

        $equity_per= round(($ALLOCATED_EQUITY_AMOUNT/$amount)*100);
        $balance_per=100-$equity_per;
      
        if($risk_type=="AGGRESSIVE"){

            if($ALLOCATED_EQUITY_AMOUNT < 4000 && $ALLOCATED_EQUITY_AMOUNT >= 3000){
                $limit=3;

                if($ALLOCATED_EQUITY_AMOUNT==3500){
                    $split1=1500;
                    $split2=1000;   
                    $split3=1000;   
                }
                else{

                    $split1=1000;
                    $split2=1000;   
                    $split3=1000;  

                }
                $split_array=array($split1,$split2,$split3);

            }

            else if($ALLOCATED_EQUITY_AMOUNT < 3000 && $ALLOCATED_EQUITY_AMOUNT >= 2000){
                $limit=2;
                if($ALLOCATED_EQUITY_AMOUNT==2500){
                    $split1=1500;
                    $split2=1000;   
                }
                else{
                    $split1=1000;
                    $split2=1000;

                }
                $split_array=array($split1,$split2);


            }else if($ALLOCATED_EQUITY_AMOUNT < 2000){
                $limit=1;
                $split1=$ALLOCATED_EQUITY_AMOUNT;
                $split_array[]=$split1;

            }else
            {
                $limit=4;
                $split_array= $this->splitRoboAggrAmount($limit,$ALLOCATED_EQUITY_AMOUNT);
                $split1=$split_array[0];
                $split2=$split_array[1];
                $split3=$split_array[2];
                $split4=$split_array[3];
            }   


            if($ALLOCATED_BALANCED_AMOUNT < 3000 && $ALLOCATED_BALANCED_AMOUNT >= 2000){
                $limit1=2;
                if($ALLOCATED_BALANCED_AMOUNT==2500){
                    $split1=1500;
                    $split2=1000;   
                }
                else{
                    $split1=1000;
                    $split2=1000;

                }
                $split_array1=array($split1,$split2);


            }else if($ALLOCATED_BALANCED_AMOUNT < 2000){

                $limit1=1;
                $split_array1[]=$ALLOCATED_BALANCED_AMOUNT;
            }else
            {
                $limit1=2;
                $split_array1= $this->splitmodAmount($limit1,$ALLOCATED_BALANCED_AMOUNT);
            }

            $scheme_allocated_amount =round(($ALLOCATED_EQUITY_AMOUNT/$limit),0);
            $scheme_allocated_amount1 =round(($ALLOCATED_BALANCED_AMOUNT/$limit1),0);


            $max_count=max($split_array);

        if($date_check=="yes"){
            $sql="select DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and  b.`is_robo` = 'Y' and  b.scheme_type='EQUITY' and  b.sip_status=1 and  b.sip_frequency='MONTHLY' and  b.sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day, b.`sip_dates`)    ORDER by rand() ";
           
                $query= $this->db->query($sql)
                ->result_array();
                $next_plan_date=$day;
        }
        else{

            $sql="select DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and  b.`is_robo` = 'Y' and b.scheme_type='EQUITY' and b.sip_status=1 and b.sip_frequency='MONTHLY' and b.sip_minimum_installment_amount <= '$max_count'  ORDER by rand() ";
             $query= $this->db->query($sql)
                ->result_array();
                 $date_exp=$query[0]['sip_dates'];
                    $inputDate = date('Y/m/d'); // filled date, not actual date
                    $next_plan_date = $this->findDate($inputDate, $date_exp);
                  

        }
            /*$sql="select *  from bse_sip_schemes WHERE `is_robo` = 'Y' and scheme_type='EQUITY' and sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,`sip_dates`)   group by amc_id ORDER by rand() ";
            $query= $this->db->query($sql)
                ->result_array();*/


            $result_scheme_array=$this->schemeAvail($query,$max_count,$day,$limit,$split_array);

            $resultArry = $result_scheme_array['resultArry'];
            $final_scheme_selected = $result_scheme_array['final_scheme_selected'];

            if(count($final_scheme_selected)<=$limit){


                $limit = count($final_scheme_selected);

                $split_array = $this->slipAmountByLimit($limit,$ALLOCATED_EQUITY_AMOUNT);

                $max_count=max($split_array);


            if($date_check=="yes")
            {
                $sql="select DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and b.`is_robo` = 'Y' and b.scheme_type='EQUITY' and b.sip_status=1 and b.sip_frequency='MONTHLY' and b.sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,b.`sip_dates`)    ORDER by rand()  ";
            }               
            else
            {
             $sql="select DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and  b.`is_robo` = 'Y' and  b.scheme_type='EQUITY' and  b.sip_status=1 and  b.sip_frequency='MONTHLY' and  b.sip_minimum_installment_amount <= '$max_count'    ORDER by rand()  ";
            }
               /* $sql="select *  from bse_sip_schemes WHERE `is_robo` = 'Y' and scheme_type='EQUITY' and sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,`sip_dates`)   group by amc_id ORDER by rand() ";*/
                $query= $this->db->query($sql)
                    ->result_array();

                $result_scheme_array=$this->schemeAvail($query,$max_count,$day,$limit,$split_array);

                $resultArry = $result_scheme_array['resultArry'];
                $final_scheme_selected = $result_scheme_array['final_scheme_selected'];

            }

            foreach ($resultArry as $key=>$value) {
                //echo $key;
                 if($date_check=="no"){
                    $date_exp=$value['sip_dates'];
                    $inputDate = date('Y/m/d'); // filled date, not actual date
                    $next_plan_date = $this->findDate($inputDate, $date_exp);
                }


                $final_scheme_unselected[]=array(
                    'amc_code'=>$value['amc_code'],
                    'amc_name'=>$value['amc_name'],
                    'scheme_code'=>$value['scheme_code'],
                    'scheme_name'=>$value['scheme_name'],
                    'sip_transaction_mode'=>$value['sip_transaction_mode'],
                    'sip_frequency'=>$value['sip_frequency'],
                    'sip_dates'=>$value['sip_dates'], 
                    'sip_minimum_gap'=>$value['sip_minimum_gap'], 
                    'sip_maximum_gap'=>$value['sip_maximum_gap'], 
                    'sip_installment_gap'=>$value['sip_installment_gap'], 
                    'sip_status'=>$value['sip_status'], 
                    'sip_minimum_installment_amount'=>$value['sip_minimum_installment_amount'], 
                    'sip_maximum_installment_amount'=>$value['sip_maximum_installment_amount'], 
                    'sip_multiplier_amount'=>$value['sip_multiplier_amount'], 
                    'sip_minimum_installment_numbers'=>$value['sip_minimum_installment_numbers'], 
                    'sip_maximum_installment_numbers'=>$value['sip_maximum_installment_numbers'], 
                    'scheme_isin'=>$value['scheme_isin'], 
                    'isin'=>$value['scheme_isin'], 

                    'image_name'=>$this->getImageName(str_replace("\r", '', $value['amc_id'])),

                    'isin_fetch_data' => $value['isin_fetch_data'],
                    'scheme_type'=>$value['scheme_type'], 
                    'is_robo'=>$value['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $value['amc_id']),
                    "plan_date"=>$next_plan_date
                );
                unset($resultArry[$key]);
                break;

            }
            //echo "<pre>";
            //   print_r($final_scheme_unselected);exit();
            /*          echo "<pre>";
             print_r($final_scheme_unselected);*/

            // ($final_schem);

            foreach ($final_scheme_selected as  $value) {

                if($date_check =="yes"){
                $sql="select * from bse_sip_schemes where scheme_code='".$value['scheme_code']."' and sip_status=1 and `is_robo` = 'Y' and scheme_type='EQUITY' and sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,`sip_dates`)" ;
                $query = $this->db->query($sql)
                    ->result_array();
                    $next_plan_date=$day;
            }
            else
            {
                    $sql="select * from bse_sip_schemes where scheme_code='".$value['scheme_code']."' and sip_status=1 and `is_robo` = 'Y' and scheme_type='EQUITY' and sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$max_count' " ;
                    $query = $this->db->query($sql)
                    ->result_array();
                    $date_exp=$query[0]['sip_dates'];
                    $inputDate = date('Y/m/d'); // filled date, not actual date
                    $next_plan_date = $this->findDate($inputDate, $date_exp);
            
            }

                /*$sql="select * from bse_sip_schemes where scheme_code='".$value['scheme_code']."' and `is_robo` = 'Y' and scheme_type='EQUITY' and sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,`sip_dates`)" ;*/

                $query = $this->db->query($sql)
                    ->result_array();

                //$query[0]
                $next_plan_date=$day;
                $result_array[]=array(
                    'amc_code'=>$query[0]['amc_code'],
                    'amc_name'=>$query[0]['amc_name'],
                    'scheme_code'=>$query[0]['scheme_code'],
                    'scheme_name'=>$query[0]['scheme_name'],
                    'sip_transaction_mode'=>$query[0]['sip_transaction_mode'],
                    'sip_frequency'=>$query[0]['sip_frequency'],
                    'sip_dates'=>$query[0]['sip_dates'], 
                    'sip_minimum_gap'=>$query[0]['sip_minimum_gap'], 
                    'sip_maximum_gap'=>$query[0]['sip_maximum_gap'], 
                    'sip_installment_gap'=>$query[0]['sip_installment_gap'], 
                    'sip_status'=>$query[0]['sip_status'], 
                    'sip_minimum_installment_amount'=>$query[0]['sip_minimum_installment_amount'], 
                    'sip_maximum_installment_amount'=>$query[0]['sip_maximum_installment_amount'], 
                    'sip_multiplier_amount'=>$query[0]['sip_multiplier_amount'], 
                    'sip_minimum_installment_numbers'=>$query[0]['sip_minimum_installment_numbers'], 
                    'sip_maximum_installment_numbers'=>$query[0]['sip_maximum_installment_numbers'], 
                    'scheme_isin'=>$query[0]['scheme_isin'], 
                    'isin'=>$query[0]['scheme_isin'], 
                   
                    'image_name'=>$this->getImageName(str_replace("\r", '', $query[0]['amc_id'])),


                    'isin_fetch_data' => $value['isin_fetch_data'],
                    'scheme_type'=>$query[0]['scheme_type'], 
                    'is_robo'=>$query[0]['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $query[0]['amc_id']),
                    'scheme_allocated_amount'=>$value['split_amount'],
                    "plan_date"=>$next_plan_date
                );

            }

            $max_count=max($split_array1);
            //if($max_count >=0)

            if($date_check=="yes"){
            $sql1="select DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and  b.scheme_type='BALANCED' and b.`is_robo` = 'Y'  and b.sip_status=1 and b.sip_frequency='MONTHLY'   and b.sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,b.`sip_dates`)   ORDER by rand() ";
                $query1= $this->db->query($sql1)
                ->result_array();
                $next_plan_date=$day;
        }
        else{

            $sql1="select DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and   b.scheme_type='BALANCED' and  b.`is_robo` = 'Y' and  b.sip_status=1 and  b.sip_frequency='MONTHLY'   and  b.sip_minimum_installment_amount <= '$max_count'    ORDER by rand() ";
             $query1= $this->db->query($sql1)
                ->result_array();
                 $date_exp=$query1[0]['sip_dates'];
                    $inputDate = date('Y/m/d'); // filled date, not actual date
                    $next_plan_date = $this->findDate($inputDate, $date_exp);
                  

        }

            $result_scheme_array=$this->schemeAvail($query1,$max_count,$day,$limit1,$split_array1);



            $resultArry1 = $result_scheme_array['resultArry'];

            $final_scheme_selected1 = $result_scheme_array['final_scheme_selected'];

            if(count($final_scheme_selected1)<$limit1 ){


                $limit1 = count($final_scheme_selected1);
                $split_array1 = $this->slipAmountByLimit($limit1,$ALLOCATED_BALANCED_AMOUNT);

                $max_count=max($split_array1);
                if($date_check=="yes"){
                $sql1="select DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and  b.scheme_type='BALANCED' and b.`is_robo` = 'Y' and sip_status=1 and sip_frequency='MONTHLY'   and 
b.sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,b.`sip_dates`)   ORDER by rand() ";
}else{
    $sql1="select DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and  b.scheme_type='BALANCED' and b.`is_robo` = 'Y' and b.sip_frequency='MONTHLY'   and b.sip_status=1 and 
b.sip_minimum_installment_amount <= '$max_count'    ORDER by rand() ";
}
                $query1= $this->db->query($sql1)
                    ->result_array();

                $result_scheme_array=$this->schemeAvail($query1,$max_count,$day,$limit1,$split_array1);
                $resultArry1 = $result_scheme_array['resultArry'];
                $final_scheme_selected1 = $result_scheme_array['final_scheme_selected'];
            }

            foreach ($final_scheme_selected1 as  $value) {

                if($date_check =="yes"){
                $sql="select * from bse_sip_schemes where scheme_code='".$value['scheme_code']."' and sip_status=1 and scheme_type='BALANCED' and sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,`sip_dates`)" ;
                $query = $this->db->query($sql)
                    ->result_array();
                    $next_plan_date=$day;
            }
            else
            {
                    $sql="select * from bse_sip_schemes where scheme_code='".$value['scheme_code']."' and sip_status=1 and scheme_type='BALANCED' and sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$max_count' " ;
                    $query = $this->db->query($sql)
                    ->result_array();

                    $date_exp=$query[0]['sip_dates'];


                    $inputDate = date('Y/m/d'); // filled date, not actual date

                    $next_plan_date = $this->findDate($inputDate, $date_exp);
            
            }
 
                //$query[0]
                $result_array1[]=array(
                    'amc_code'=>$query[0]['amc_code'],
                    'amc_name'=>$query[0]['amc_name'],
                    'scheme_code'=>$query[0]['scheme_code'],
                    'scheme_name'=>$query[0]['scheme_name'],
                    'sip_transaction_mode'=>$query[0]['sip_transaction_mode'],
                    'sip_frequency'=>$query[0]['sip_frequency'],
                    'sip_dates'=>$query[0]['sip_dates'], 
                    'sip_minimum_gap'=>$query[0]['sip_minimum_gap'], 
                    'sip_maximum_gap'=>$query[0]['sip_maximum_gap'], 
                    'sip_installment_gap'=>$query[0]['sip_installment_gap'], 
                    'sip_status'=>$query[0]['sip_status'], 
                    'sip_minimum_installment_amount'=>$query[0]['sip_minimum_installment_amount'], 
                    'sip_maximum_installment_amount'=>$query[0]['sip_maximum_installment_amount'], 
                    'sip_multiplier_amount'=>$query[0]['sip_multiplier_amount'], 
                    'sip_minimum_installment_numbers'=>$query[0]['sip_minimum_installment_numbers'], 
                    'sip_maximum_installment_numbers'=>$query[0]['sip_maximum_installment_numbers'], 
                    'scheme_isin'=>$query[0]['scheme_isin'],
                    'isin'=>$query[0]['scheme_isin'],
                    'image_name'=>$this->getImageName(str_replace("\r", '', $query[0]['amc_id'])),

                    'isin_fetch_data' => $value['isin_fetch_data'], 
                    'scheme_type'=>$query[0]['scheme_type'], 
                    'is_robo'=>$query[0]['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $query[0]['amc_id']),
                    'scheme_allocated_amount'=>$value['split_amount'],
                    "plan_date"=>$next_plan_date
                );
            }
                             

            foreach ($resultArry1 as $key=>$value) {
                //echo $key;
                $next_plan_date=$day;
                if($date_check=="no"){
                    $date_exp=$value['sip_dates'];
                    $inputDate = date('Y/m/d'); // filled date, not actual date
                    $next_plan_date = $this->findDate($inputDate, $date_exp);
                }

                $final_scheme_unselected1[]=array(
                    'amc_code'=>$value['amc_code'],
                    'amc_name'=>$value['amc_name'],
                    'scheme_code'=>$value['scheme_code'],
                    'scheme_name'=>$value['scheme_name'],
                    'sip_transaction_mode'=>$value['sip_transaction_mode'],
                    'sip_frequency'=>$value['sip_frequency'],
                    'sip_dates'=>$value['sip_dates'], 
                    'sip_minimum_gap'=>$value['sip_minimum_gap'], 
                    'sip_maximum_gap'=>$value['sip_maximum_gap'], 
                    'sip_installment_gap'=>$value['sip_installment_gap'], 
                    'sip_status'=>$value['sip_status'], 
                    'sip_minimum_installment_amount'=>$value['sip_minimum_installment_amount'], 
                    'sip_maximum_installment_amount'=>$value['sip_maximum_installment_amount'], 
                    'sip_multiplier_amount'=>$value['sip_multiplier_amount'], 
                    'sip_minimum_installment_numbers'=>$value['sip_minimum_installment_numbers'], 
                    'sip_maximum_installment_numbers'=>$value['sip_maximum_installment_numbers'], 
                    'scheme_isin'=>$value['scheme_isin'], 
                    'isin'=>$value['scheme_isin'], 
                    'image_name'=>$this->getImageName(str_replace("\r", '', $value['amc_id'])),

                    'isin_fetch_data' => $value['isin_fetch_data'],
                    'scheme_type'=>$value['scheme_type'], 
                    'is_robo'=>$value['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $value['amc_id']),
                    "plan_date"=>$next_plan_date
                );
                unset($resultArry[$key]);
                break;

            }
            //    $result=array_merge($result_array,$result_array1);
            $result = array
                (
                "SELECT_ARRAY"=> array_merge($result_array,$result_array1),
                "UNSELECT_ARRAY"=> array_merge($final_scheme_unselected,$final_scheme_unselected1),
                "EQUITY_PER"=> $equity_per,
                "BALANCE_PER"=> $balance_per,
               
            );
        }

        if($risk_type=="MODERATE"){
            if($ALLOCATED_EQUITY_AMOUNT < 3000 && $ALLOCATED_EQUITY_AMOUNT >= 2000){
                $limit=2;
                if($ALLOCATED_EQUITY_AMOUNT==2500){
                    $split1=1500;
                    $split2=1000;   
                }
                else{
                    $split1=1000;
                    $split2=1000;

                }  
                $split_array=array($split1,$split2);

            }else if($ALLOCATED_EQUITY_AMOUNT < 2000){

                $limit=1;
                $split1=$ALLOCATED_EQUITY_AMOUNT;
                $split_array[]=$split1;

            }else
            {
                $limit=3;
                $split_array= $this->splitAmount($limit,$ALLOCATED_EQUITY_AMOUNT);
                $split1=$split_array[0];
                $split2=$split_array[1];
                $split3=$split_array[2];
            }   


            if($ALLOCATED_BALANCED_AMOUNT < 3000 && $ALLOCATED_BALANCED_AMOUNT >= 2000){
                $limit1=2;
                if($ALLOCATED_BALANCED_AMOUNT==2500){
                    $split1=1500;
                    $split2=1000;   
                }
                else{
                    $split1=1000;
                    $split2=1000;

                }  
                $split_array1=array($split1,$split2);


            }else if($ALLOCATED_BALANCED_AMOUNT < 2000){

                $limit1=1;
                $split1=$ALLOCATED_BALANCED_AMOUNT;
                $split_array1[]=$split1;
            }else
            {
                $limit1=3;
                $split_array1= $this->splitAmount($limit1,$ALLOCATED_BALANCED_AMOUNT);

                $split1=$split_array1[0];
                $split2=$split_array1[1];
                $split3=$split_array1[2];
            }

            $scheme_allocated_amount =round(($ALLOCATED_EQUITY_AMOUNT/$limit),0);
            $scheme_allocated_amount1 =round(($ALLOCATED_BALANCED_AMOUNT/$limit1),0);
            
            $max_count = max($split_array);
            
            
        if($date_check=="yes"){
            $sql="select DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime  from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and b.`is_robo` = 'Y' and b.scheme_type='EQUITY' and b.sip_status=1  and b.sip_frequency='MONTHLY' and b.sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,b.`sip_dates`)   ORDER by rand() ";
                $query= $this->db->query($sql)
                ->result_array();
                $next_plan_date=$day;
        }
        else
        {
            $sql="select  DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime  from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and b.`is_robo` = 'Y'  and b.scheme_type='EQUITY' and b.sip_status=1  and b.sip_frequency='MONTHLY' and b.sip_minimum_installment_amount <= '$max_count'  ORDER by rand() ";

                 $query= $this->db->query($sql)
                ->result_array();
                 $date_exp=$query[0]['sip_dates'];
                    $inputDate = date('Y/m/d'); // filled date, not actual date
                    $next_plan_date = $this->findDate($inputDate, $date_exp);
        }

            /*$sql="select *  from bse_sip_schemes WHERE `is_robo` = 'Y' and scheme_type='EQUITY'  and sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,`sip_dates`)  group by amc_id ORDER by rand() ";

            $query= $this->db->query($sql)
                ->result_array();*/

            $result_scheme_array=$this->schemeAvail($query,$max_count,$day,$limit,$split_array);

            $resultArry = $result_scheme_array['resultArry'];
            $final_scheme_selected = $result_scheme_array['final_scheme_selected'];

            if(count($final_scheme_selected)<$limit){


                $limit = count($final_scheme_selected);
                $split_array = $this->slipAmountByLimit($limit,$ALLOCATED_EQUITY_AMOUNT);

                $max_count=max($split_array);

                 if($date_check=="yes"){
                $sql="select  DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and b.`is_robo` = 'Y' and b.scheme_type='EQUITY' and b.sip_status=1  and b.sip_frequency='MONTHLY' and b.sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,b.`sip_dates`)   ORDER by rand() ";
            }
            else
            {
                $sql="select  DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and b.`is_robo` = 'Y' and b.scheme_type='EQUITY' and b.sip_status=1 and b.sip_frequency='MONTHLY' and b.sip_minimum_installment_amount <= '$max_count'   ORDER by rand() ";
            }
               /* $sql="select *  from bse_sip_schemes WHERE `is_robo` = 'Y' and scheme_type='EQUITY'  and sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,`sip_dates`)  group by amc_id ORDER by rand() ";*/
                $query= $this->db->query($sql)
                    ->result_array();

                $result_scheme_array=$this->schemeAvail($query,$max_count,$day,$limit,$split_array);
                $resultArry = $result_scheme_array['resultArry'];
                $final_scheme_selected = $result_scheme_array['final_scheme_selected'];
            }



            /*unSelected Final scheme array */
            // print_r($resultArry);exit();
            foreach ($resultArry as $key=>$value) {
                //echo $key;
                $next_plan_date=$day;
                if($date_check=="no"){
                    $date_exp=$value['sip_dates'];
                    $inputDate = date('Y/m/d'); // filled date, not actual date
                    $next_plan_date = $this->findDate($inputDate, $date_exp);
                }
                $final_scheme_unselected[]=array(
                    'amc_code'=>$value['amc_code'],
                    'amc_name'=>$value['amc_name'],
                    'scheme_code'=>$value['scheme_code'],
                    'scheme_name'=>$value['scheme_name'],
                    'sip_transaction_mode'=>$value['sip_transaction_mode'],
                    'sip_frequency'=>$value['sip_frequency'],
                    'sip_dates'=>$value['sip_dates'], 
                    'sip_minimum_gap'=>$value['sip_minimum_gap'], 
                    'sip_maximum_gap'=>$value['sip_maximum_gap'], 
                    'sip_installment_gap'=>$value['sip_installment_gap'], 
                    'sip_status'=>$value['sip_status'], 
                    'sip_minimum_installment_amount'=>$value['sip_minimum_installment_amount'], 
                    'sip_maximum_installment_amount'=>$value['sip_maximum_installment_amount'], 
                    'sip_multiplier_amount'=>$value['sip_multiplier_amount'], 
                    'sip_minimum_installment_numbers'=>$value['sip_minimum_installment_numbers'], 
                    'sip_maximum_installment_numbers'=>$value['sip_maximum_installment_numbers'], 
                    'scheme_isin'=>$value['scheme_isin'], 
                    'isin'=>$value['scheme_isin'], 
                    'image_name'=>$this->getImageName(str_replace("\r", '', $value['amc_id'])),

                    'isin_fetch_data' => $value['isin_fetch_data'],
                    'scheme_type'=>$value['scheme_type'], 
                    'is_robo'=>$value['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $value['amc_id']),
                    "plan_date"=>$next_plan_date
                );
                unset($resultArry[$key]);
                break;

            }

            //echo "<pre>";
            //   print_r($final_scheme_unselected);exit();
            /*          echo "<pre>";
             print_r($final_scheme_unselected);*/

            // ($final_schem);
            foreach ($final_scheme_selected as  $value) {
                
                 if($date_check =="yes"){
                $sql="select * from bse_sip_schemes where scheme_code='".$value['scheme_code']."' and `is_robo` = 'Y' and sip_status=1 and scheme_type='EQUITY' and sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,`sip_dates`)" ;
                $query = $this->db->query($sql)
                    ->result_array();
                    $next_plan_date=$day;
            }
            else
            {
                    $sql="select * from bse_sip_schemes where scheme_code='".$value['scheme_code']."' and sip_status=1 and `is_robo` = 'Y' and scheme_type='EQUITY' and sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$max_count'" ;
                    $query = $this->db->query($sql)
                    ->result_array();
                    $date_exp=$query[0]['sip_dates'];
                    $inputDate = date('Y/m/d'); // filled date, not actual date
                    $next_plan_date = $this->findDate($inputDate, $date_exp);
            
            }



             /*   $sql="select * from bse_sip_schemes where scheme_code='".$value['scheme_code']."' and `is_robo` = 'Y' and scheme_type='EQUITY' and sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,`sip_dates`)" ;


                $query = $this->db->query($sql)
                    ->result_array();*/
                //$query[0]
                $result_array[]=array(
                    'amc_code'=>$query[0]['amc_code'],
                    'amc_name'=>$query[0]['amc_name'],
                    'scheme_code'=>$query[0]['scheme_code'],
                    'scheme_name'=>$query[0]['scheme_name'],
                    'sip_transaction_mode'=>$query[0]['sip_transaction_mode'],
                    'sip_frequency'=>$query[0]['sip_frequency'],
                    'sip_dates'=>$query[0]['sip_dates'], 
                    'sip_minimum_gap'=>$query[0]['sip_minimum_gap'], 
                    'sip_maximum_gap'=>$query[0]['sip_maximum_gap'], 
                    'sip_installment_gap'=>$query[0]['sip_installment_gap'], 
                    'sip_status'=>$query[0]['sip_status'], 
                    'sip_minimum_installment_amount'=>$query[0]['sip_minimum_installment_amount'], 
                    'sip_maximum_installment_amount'=>$query[0]['sip_maximum_installment_amount'], 
                    'sip_multiplier_amount'=>$query[0]['sip_multiplier_amount'], 
                    'sip_minimum_installment_numbers'=>$query[0]['sip_minimum_installment_numbers'], 
                    'sip_maximum_installment_numbers'=>$query[0]['sip_maximum_installment_numbers'], 
                    'scheme_isin'=>$query[0]['scheme_isin'], 
                    'isin'=>$query[0]['scheme_isin'], 
                    'image_name'=>$this->getImageName(str_replace("\r", '', $query[0]['amc_id'])),

                    'isin_fetch_data' => $value['isin_fetch_data'],
                    'scheme_type'=>$query[0]['scheme_type'], 
                    'is_robo'=>$query[0]['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $query[0]['amc_id']),
                    'scheme_allocated_amount'=>$value['split_amount'],
                    "plan_date"=>$next_plan_date
                );

            }

            $max_count = max($split_array1);
             if($date_check=="yes"){
            $sql1="select DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and  b.scheme_type='BALANCED' and b.`is_robo` = 'Y' and b.sip_status=1 and b.sip_frequency='MONTHLY' and 
b.sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,b.`sip_dates`)   ORDER by rand() ";
 $query1= $this->db->query($sql1)
                ->result_array();
                $next_plan_date=$day;
}
else
{
          $sql1="select DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and  b.scheme_type='BALANCED' and b.`is_robo` = 'Y' and b.sip_status=1 and b.sip_frequency='MONTHLY' and 
        b.sip_minimum_installment_amount <= '$max_count'    ORDER by rand() ";
                $query1= $this->db->query($sql1)
                ->result_array();

                 $date_exp=$query1[0]['sip_dates'];
                    $inputDate = date('Y/m/d'); // filled date, not actual date
                    $next_plan_date = $this->findDate($inputDate, $date_exp);

}

           

            $result_scheme_array=$this->schemeAvail($query1,$max_count,$day,$limit1,$split_array1);



            $resultArry1 = $result_scheme_array['resultArry'];
            $final_scheme_selected1 = $result_scheme_array['final_scheme_selected'];

            if(count($final_scheme_selected1)<$limit1){


                $limit1 = count($final_scheme_selected1);
                $split_array1 = $this->slipAmountByLimit($limit1,$ALLOCATED_BALANCED_AMOUNT);

                $max_count=max($split_array1);
  if($date_check=="yes"){
                $sql1="select DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and  b.scheme_type='BALANCED' and b.`is_robo` = 'Y' and b.sip_status=1 and b.sip_frequency='MONTHLY' and 
b.sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,b.`sip_dates`)   ORDER by rand() ";
}
else
{

                $sql1="select DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and  b.scheme_type='BALANCED' and b.`is_robo` = 'Y' and b.sip_status=1 and b.sip_frequency='MONTHLY' and 
b.sip_minimum_installment_amount <= '$max_count'    ORDER by rand() ";

}
                $query1= $this->db->query($sql1)
                    ->result_array();


                $result_scheme_array=$this->schemeAvail($query1,$max_count,$day,$limit1,$split_array1);
                $resultArry1 = $result_scheme_array['resultArry'];
                $final_scheme_selected1 = $result_scheme_array['final_scheme_selected'];
            }


            foreach ($final_scheme_selected1 as  $value) {

                if($date_check =="yes"){
                $sql="select * from bse_sip_schemes where scheme_code='".$value['scheme_code']."'  and sip_status=1 and `is_robo` = 'Y' and scheme_type='BALANCED' and sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,`sip_dates`)" ;
                $query = $this->db->query($sql)
                    ->result_array();

                }
                else
                {
                    $sql="select * from bse_sip_schemes where scheme_code='".$value['scheme_code']."'  and sip_status=1 and `is_robo` = 'Y' and scheme_type='BALANCED' and sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$max_count'" ;
                $query = $this->db->query($sql)
                    ->result_array();
                     $date_exp=$query[0]['sip_dates'];
                    $inputDate = date('Y/m/d'); // filled date, not actual date
                    $next_plan_date = $this->findDate($inputDate, $date_exp);
                }

                //$query[0]
                $result_array1[]=array(
                    'amc_code'=>$query[0]['amc_code'],
                    'amc_name'=>$query[0]['amc_name'],
                    'scheme_code'=>$query[0]['scheme_code'],
                    'scheme_name'=>$query[0]['scheme_name'],
                    'sip_transaction_mode'=>$query[0]['sip_transaction_mode'],
                    'sip_frequency'=>$query[0]['sip_frequency'],
                    'sip_dates'=>$query[0]['sip_dates'], 
                    'sip_minimum_gap'=>$query[0]['sip_minimum_gap'], 
                    'sip_maximum_gap'=>$query[0]['sip_maximum_gap'], 
                    'sip_installment_gap'=>$query[0]['sip_installment_gap'], 
                    'sip_status'=>$query[0]['sip_status'], 
                    'sip_minimum_installment_amount'=>$query[0]['sip_minimum_installment_amount'], 
                    'sip_maximum_installment_amount'=>$query[0]['sip_maximum_installment_amount'], 
                    'sip_multiplier_amount'=>$query[0]['sip_multiplier_amount'], 
                    'sip_minimum_installment_numbers'=>$query[0]['sip_minimum_installment_numbers'], 
                    'sip_maximum_installment_numbers'=>$query[0]['sip_maximum_installment_numbers'], 
                    'scheme_isin'=>$query[0]['scheme_isin'], 
                    'isin'=>$query[0]['scheme_isin'], 
                    'image_name'=>$this->getImageName(str_replace("\r", '', $query[0]['amc_id'])),

                    'isin_fetch_data' => $value['isin_fetch_data'],
                    'scheme_type'=>$query[0]['scheme_type'], 
                    'is_robo'=>$query[0]['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $query[0]['amc_id']),
                    'scheme_allocated_amount'=>$value['split_amount'],
                    "plan_date"=>$next_plan_date
                );
            }
            $final_scheme_unselected1=array();
            foreach ($resultArry1 as $key=>$value) {
                //echo $key;
                    //echo $key;
                $next_plan_date=$day;
                if($date_check=="no"){
                    $date_exp=$value['sip_dates'];
                    $inputDate = date('Y/m/d'); // filled date, not actual date
                    $next_plan_date = $this->findDate($inputDate, $date_exp);
                }

                $final_scheme_unselected1[]=array(
                    'amc_code'=>$value['amc_code'],
                    'amc_name'=>$value['amc_name'],
                    'scheme_code'=>$value['scheme_code'],
                    'scheme_name'=>$value['scheme_name'],
                    'sip_transaction_mode'=>$value['sip_transaction_mode'],
                    'sip_frequency'=>$value['sip_frequency'],
                    'sip_dates'=>$value['sip_dates'], 
                    'sip_minimum_gap'=>$value['sip_minimum_gap'], 
                    'sip_maximum_gap'=>$value['sip_maximum_gap'], 
                    'sip_installment_gap'=>$value['sip_installment_gap'], 
                    'sip_status'=>$value['sip_status'], 
                    'sip_minimum_installment_amount'=>$value['sip_minimum_installment_amount'], 
                    'sip_maximum_installment_amount'=>$value['sip_maximum_installment_amount'], 
                    'sip_multiplier_amount'=>$value['sip_multiplier_amount'], 
                    'sip_minimum_installment_numbers'=>$value['sip_minimum_installment_numbers'], 
                    'sip_maximum_installment_numbers'=>$value['sip_maximum_installment_numbers'], 
                    'scheme_isin'=>$value['scheme_isin'], 
                    'isin'=>$value['scheme_isin'], 
                    'image_name'=>$this->getImageName(str_replace("\r", '', $value['amc_id'])),

                    'isin_fetch_data' => $value['isin_fetch_data'],
                    'scheme_type'=>$value['scheme_type'], 
                    'is_robo'=>$value['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $value['amc_id']),
                     "plan_date"=>$next_plan_date
                );
                unset($resultArry1[$key]);
                break;

            }



            $result = array
                (
                "SELECT_ARRAY"=> array_merge($result_array,$result_array1),
                "UNSELECT_ARRAY"=>array_merge($final_scheme_unselected,$final_scheme_unselected1),
                "EQUITY_PER"=> $equity_per,
                "BALANCE_PER"=> $balance_per
            );

        }

        if($risk_type=="CONSERVATIVE"){

            if($ALLOCATED_EQUITY_AMOUNT < 3000 && $ALLOCATED_EQUITY_AMOUNT >= 2000){
                $limit=2;
                if($ALLOCATED_EQUITY_AMOUNT==2500){
                    $split1=1500;
                    $split2=1000;   
                }
                else{
                    $split1=1000;
                    $split2=1000;

                }
                $split_array=array($split1,$split2);

            }else if($ALLOCATED_EQUITY_AMOUNT < 2000){

                $limit=1;
                $split_array[]=$ALLOCATED_EQUITY_AMOUNT;
            }else
            {
                $limit=2;
                $split_array= $this->splitmodAmount($limit,$ALLOCATED_EQUITY_AMOUNT);
            }
            /*Start*/

            if($ALLOCATED_BALANCED_AMOUNT < 4000 && $ALLOCATED_BALANCED_AMOUNT >= 3000){
                $limit1=3;
                if($ALLOCATED_BALANCED_AMOUNT==3500){
                    $split1=1500;
                    $split2=1000;   
                    $split3=1000;   
                }
                else{


                    $split1=1000;
                    $split2=1000;   
                    $split3=1000;  

                }
                $split_array1=array($split1,$split2,$split3);

            }
            else if($ALLOCATED_BALANCED_AMOUNT < 3000 && $ALLOCATED_BALANCED_AMOUNT >= 2000){
                $limit1=2;
                if($ALLOCATED_BALANCED_AMOUNT==2500){
                    $split1=1500;
                    $split2=1000;   
                }
                else{
                    $split1=1000;
                    $split2=1000;

                }
                $split_array1=array($split1,$split2);


            }else if($ALLOCATED_BALANCED_AMOUNT < 2000){
                $limit1=1;
                $split1=$ALLOCATED_BALANCED_AMOUNT;
                $split_array1[]=$split1;
            }else
            {
                $limit1=4;
                $split_array1= $this->splitRoboAggrAmount($limit1,$ALLOCATED_BALANCED_AMOUNT);
                $split1=$split_array1[0];
                $split2=$split_array1[1];
                $split3=$split_array1[2];
                $split4=$split_array1[3];
            } 
            

            $scheme_allocated_amount =round(($ALLOCATED_EQUITY_AMOUNT/$limit),0);  
            $scheme_allocated_amount1 =round(($ALLOCATED_BALANCED_AMOUNT/$limit1),0);
            
            $max_count = max($split_array);
            
            if($date_check=="yes"){
            $sql="select  DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and b.`is_robo` = 'Y' and b.scheme_type='EQUITY' and b.sip_status=1 and b.sip_frequency='MONTHLY' and 
    b.sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,b.`sip_dates`)    ORDER by rand() ";
                $query= $this->db->query($sql)
                ->result_array();
                $next_plan_date=$day;
        }
        else{

            $sql="select  DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and b.`is_robo` = 'Y' and b.scheme_type='EQUITY' and b.sip_status=1 and b.sip_frequency='MONTHLY' and 
b.sip_minimum_installment_amount <= '$max_count'   ORDER by rand() ";
             $query= $this->db->query($sql)
                ->result_array();
                 $date_exp=$query[0]['sip_dates'];
                    $inputDate = date('Y/m/d'); // filled date, not actual date
                    $next_plan_date = $this->findDate($inputDate, $date_exp);
                  

        }

            /*$sql="select *  from bse_sip_schemes WHERE `is_robo` = 'Y' and scheme_type='EQUITY' and sip_frequency='MONTHLY' and 
sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,`sip_dates`)   group by amc_id ORDER by rand()";*/
            /*$query= $this->db->query($sql)
                ->result_array();*/

            $result_scheme_array=$this->schemeAvail($query,$max_count,$day,$limit,$split_array);

            $resultArry = $result_scheme_array['resultArry'];
            $final_scheme_selected = $result_scheme_array['final_scheme_selected'];

            if(count($final_scheme_selected)<$limit){


                $limit = count($final_scheme_selected);
                $split_array = $this->slipAmountByLimit($limit,$ALLOCATED_EQUITY_AMOUNT);

                $max_count=max($split_array);

                         if($date_check=="yes"){
                $sql="select DISTINCT amc_id,b.id,b.amc_code,.amc_name,b.scheme_code,.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,.sip_installment_gap,.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and b.`is_robo` = 'Y' and b.scheme_type='EQUITY' and b.sip_status=1 and b.sip_frequency='MONTHLY' and 
    b.sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,b.`sip_dates`)    ORDER by rand() ";
}else{
    $sql="select b.* from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and b.`is_robo` = 'Y' and b.scheme_type='EQUITY' and b.sip_frequency='MONTHLY' and b.sip_status=1 and 
b.sip_minimum_installment_amount <= '$max_count'   ORDER by rand() ";
}
                /*$sql="select *  from bse_sip_schemes WHERE `is_robo` = 'Y' and scheme_type='EQUITY' and sip_frequency='MONTHLY' and 
sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,`sip_dates`)   group by amc_id ORDER by rand()";*/
                $query= $this->db->query($sql)
                    ->result_array();

                $result_scheme_array=$this->schemeAvail($query,$max_count,$day,$limit,$split_array);
                $resultArry = $result_scheme_array['resultArry'];
                $final_scheme_selected = $result_scheme_array['final_scheme_selected'];
            }



            /*unSelected Final scheme array */
            // print_r($resultArry);exit();
            $final_scheme_unselected=array();

            foreach ($resultArry as $key=>$value) {
                //echo $key;
                 $next_plan_date=$day;
                if($date_check=="no"){
                    $date_exp=$value['sip_dates'];
                    $inputDate = date('Y/m/d'); // filled date, not actual date
                    $next_plan_date = $this->findDate($inputDate, $date_exp);
                }

                $final_scheme_unselected[]=array(
                    'amc_code'=>$value['amc_code'],
                    'amc_name'=>$value['amc_name'],
                    'scheme_code'=>$value['scheme_code'],
                    'scheme_name'=>$value['scheme_name'],
                    'sip_transaction_mode'=>$value['sip_transaction_mode'],
                    'sip_frequency'=>$value['sip_frequency'],
                    'sip_dates'=>$value['sip_dates'], 
                    'sip_minimum_gap'=>$value['sip_minimum_gap'], 
                    'sip_maximum_gap'=>$value['sip_maximum_gap'], 
                    'sip_installment_gap'=>$value['sip_installment_gap'], 
                    'sip_status'=>$value['sip_status'], 
                    'sip_minimum_installment_amount'=>$value['sip_minimum_installment_amount'], 
                    'sip_maximum_installment_amount'=>$value['sip_maximum_installment_amount'], 
                    'sip_multiplier_amount'=>$value['sip_multiplier_amount'], 
                    'sip_minimum_installment_numbers'=>$value['sip_minimum_installment_numbers'], 
                    'sip_maximum_installment_numbers'=>$value['sip_maximum_installment_numbers'], 
                    'scheme_isin'=>$value['scheme_isin'], 
                    'isin'=>$value['scheme_isin'], 
                    'image_name'=>$this->getImageName(str_replace("\r", '', $value['amc_id'])),


                    'isin_fetch_data' => $value['isin_fetch_data'],
                    'scheme_type'=>$value['scheme_type'], 
                    'is_robo'=>$value['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $value['amc_id']),
                    "plan_date"=>$next_plan_date
                );
                unset($resultArry[$key]);
                break;

            }

            //echo "<pre>";
            //   print_r($final_scheme_unselected);exit();
            /*          echo "<pre>";
             print_r($final_scheme_unselected);*/

            // ($final_schem);
            foreach ($final_scheme_selected as  $value) {
                
            if($date_check =="yes"){
                $sql="select * from bse_sip_schemes where scheme_code='".$value['scheme_code']."' and sip_status=1 and `is_robo` = 'Y' and scheme_type='EQUITY' and sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,`sip_dates`)" ;
                $query = $this->db->query($sql)
                    ->result_array();
                    $next_plan_date=$day;
            }
            else
            {
            $sql="select * from bse_sip_schemes where scheme_code='".$value['scheme_code']."' and `is_robo` = 'Y' and sip_status=1 and scheme_type='EQUITY' and sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$max_count' " ;
                    $query = $this->db->query($sql)
                    ->result_array();
                    $date_exp=$query[0]['sip_dates'];
                    $inputDate = date('Y/m/d'); // filled date, not actual date
                    $next_plan_date = $this->findDate($inputDate, $date_exp);
            
            }
              

               /* $sql="select * from bse_sip_schemes where scheme_code='".$value['scheme_code']."' and `is_robo` = 'Y' and scheme_type='EQUITY' and sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,`sip_dates`)" ;


                $query = $this->db->query($sql)
                    ->result_array();*/
                //$query[0]
                $result_array[]=array(
                    'amc_code'=>$query[0]['amc_code'],
                    'amc_name'=>$query[0]['amc_name'],
                    'scheme_code'=>$query[0]['scheme_code'],
                    'scheme_name'=>$query[0]['scheme_name'],
                    'sip_transaction_mode'=>$query[0]['sip_transaction_mode'],
                    'sip_frequency'=>$query[0]['sip_frequency'],
                    'sip_dates'=>$query[0]['sip_dates'], 
                    'sip_minimum_gap'=>$query[0]['sip_minimum_gap'], 
                    'sip_maximum_gap'=>$query[0]['sip_maximum_gap'], 
                    'sip_installment_gap'=>$query[0]['sip_installment_gap'], 
                    'sip_status'=>$query[0]['sip_status'], 
                    'sip_minimum_installment_amount'=>$query[0]['sip_minimum_installment_amount'], 
                    'sip_maximum_installment_amount'=>$query[0]['sip_maximum_installment_amount'], 
                    'sip_multiplier_amount'=>$query[0]['sip_multiplier_amount'], 
                    'sip_minimum_installment_numbers'=>$query[0]['sip_minimum_installment_numbers'], 
                    'sip_maximum_installment_numbers'=>$query[0]['sip_maximum_installment_numbers'], 
                    'scheme_isin'=>$query[0]['scheme_isin'], 
                    'isin'=>$query[0]['scheme_isin'], 
                    'image_name'=>$this->getImageName(str_replace("\r", '', $query[0]['amc_id'])),


                    'isin_fetch_data' => $value['isin_fetch_data'],
                    'scheme_type'=>$query[0]['scheme_type'], 
                    'is_robo'=>$query[0]['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $query[0]['amc_id']),
                    'scheme_allocated_amount'=>$value['split_amount'],
                    "plan_date"=>$next_plan_date
                );

            }

            $max_count=max($split_array1);

                        if($date_check=="yes"){
            $sql1="select DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and b.scheme_type='BALANCED' and b.`is_robo` = 'Y' and b.sip_status=1 and b.sip_frequency='MONTHLY' and 
b.sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,b.`sip_dates`)   ORDER by rand() ";

$query1= $this->db->query($sql1)
                ->result_array();
}
else
{

 $sql1="select DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and b.scheme_type='BALANCED' and b.`is_robo` = 'Y' and b.sip_status=1 and b.sip_frequency='MONTHLY' and b.sip_status=1 and 
b.sip_minimum_installment_amount <= '$max_count'    ORDER by rand() ";

$query1= $this->db->query($sql1)
                ->result_array();
                 $date_exp=$query1[0]['sip_dates'];
                    $inputDate = date('Y/m/d'); // filled date, not actual date
                    $next_plan_date = $this->findDate($inputDate, $date_exp);

}
            

            $result_scheme_array=$this->schemeAvail($query1,$max_count,$day,$limit1,$split_array1);



            $resultArry1 = $result_scheme_array['resultArry'];
            $final_scheme_selected1 = $result_scheme_array['final_scheme_selected'];

            if(count($final_scheme_selected1)<$limit1){


                $limit1 = count($final_scheme_selected1);
                $split_array1 = $this->slipAmountByLimit($limit1,$ALLOCATED_BALANCED_AMOUNT);

                $max_count=max($split_array1);
  if($date_check=="yes"){
                $sql1="select DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and  b.scheme_type='BALANCED' and b.`is_robo` = 'Y' and b.sip_status=1  and b.sip_frequency='MONTHLY' and 
b.sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,b.`sip_dates`)   ORDER by rand() ";
}else
{
 $sql1="select DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and  b.scheme_type='BALANCED' and b.`is_robo` = 'Y' and b.sip_frequency='MONTHLY' and b.sip_status=1 and 
b.sip_minimum_installment_amount <= '$max_count'    ORDER by rand() ";

}
                $query1= $this->db->query($sql1)
                    ->result_array();


                $result_scheme_array=$this->schemeAvail($query1,$max_count,$day,$limit1,$split_array1);
                $resultArry1 = $result_scheme_array['resultArry'];
                $final_scheme_selected1 = $result_scheme_array['final_scheme_selected'];
            }




            foreach ($final_scheme_selected1 as  $value) {
   if($date_check =="yes"){
                $sql="select * from bse_sip_schemes where scheme_code='".$value['scheme_code']."' and sip_status=1 and scheme_type='BALANCED' and `is_robo` = 'Y' and sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,`sip_dates`)" ;
                $query = $this->db->query($sql)
                    ->result_array();
                }
                else{
   $sql="select * from bse_sip_schemes where scheme_code='".$value['scheme_code']."' and scheme_type='BALANCED' and sip_status=1 and `is_robo` = 'Y' and sip_frequency='MONTHLY' and sip_minimum_installment_amount <= '$max_count' " ;
                $query = $this->db->query($sql)
                    ->result_array();

                    $date_exp=$query[0]['sip_dates'];
                    $inputDate = date('Y/m/d'); // filled date, not actual date
                    $next_plan_date = $this->findDate($inputDate, $date_exp);

                }
                //$query[0]
                $result_array1[]=array(
                    'amc_code'=>$query[0]['amc_code'],
                    'amc_name'=>$query[0]['amc_name'],
                    'scheme_code'=>$query[0]['scheme_code'],
                    'scheme_name'=>$query[0]['scheme_name'],
                    'sip_transaction_mode'=>$query[0]['sip_transaction_mode'],
                    'sip_frequency'=>$query[0]['sip_frequency'],
                    'sip_dates'=>$query[0]['sip_dates'], 
                    'sip_minimum_gap'=>$query[0]['sip_minimum_gap'], 
                    'sip_maximum_gap'=>$query[0]['sip_maximum_gap'], 
                    'sip_installment_gap'=>$query[0]['sip_installment_gap'], 
                    'sip_status'=>$query[0]['sip_status'], 
                    'sip_minimum_installment_amount'=>$query[0]['sip_minimum_installment_amount'], 
                    'sip_maximum_installment_amount'=>$query[0]['sip_maximum_installment_amount'], 
                    'sip_multiplier_amount'=>$query[0]['sip_multiplier_amount'], 
                    'sip_minimum_installment_numbers'=>$query[0]['sip_minimum_installment_numbers'], 
                    'sip_maximum_installment_numbers'=>$query[0]['sip_maximum_installment_numbers'], 
                    'scheme_isin'=>$query[0]['scheme_isin'],
                    'isin'=>$query[0]['scheme_isin'],
                    'image_name'=>$this->getImageName(str_replace("\r", '', $query[0]['amc_id'])),

                    'isin_fetch_data' => $value['isin_fetch_data'], 
                    'scheme_type'=>$query[0]['scheme_type'], 
                    'is_robo'=>$query[0]['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $query[0]['amc_id']),
                    'scheme_allocated_amount'=>$value['split_amount'],
                     "plan_date"=>$next_plan_date
                );
            }
            $final_scheme_unselected1=array();
            foreach ($resultArry1 as $key=>$value) {
                  $next_plan_date=$day;
                if($date_check=="no"){
                    $date_exp=$value['sip_dates'];
                    $inputDate = date('Y/m/d'); // filled date, not actual date
                    $next_plan_date = $this->findDate($inputDate, $date_exp);
                }



                $final_scheme_unselected1[]=array(
                    'amc_code'=>$value['amc_code'],
                    'amc_name'=>$value['amc_name'],
                    'scheme_code'=>$value['scheme_code'],
                    'scheme_name'=>$value['scheme_name'],
                    'sip_transaction_mode'=>$value['sip_transaction_mode'],
                    'sip_frequency'=>$value['sip_frequency'],
                    'sip_dates'=>$value['sip_dates'], 
                    'sip_minimum_gap'=>$value['sip_minimum_gap'], 
                    'sip_maximum_gap'=>$value['sip_maximum_gap'], 
                    'sip_installment_gap'=>$value['sip_installment_gap'], 
                    'sip_status'=>$value['sip_status'], 
                    'sip_minimum_installment_amount'=>$value['sip_minimum_installment_amount'], 
                    'sip_maximum_installment_amount'=>$value['sip_maximum_installment_amount'], 
                    'sip_multiplier_amount'=>$value['sip_multiplier_amount'], 
                    'sip_minimum_installment_numbers'=>$value['sip_minimum_installment_numbers'], 
                    'sip_maximum_installment_numbers'=>$value['sip_maximum_installment_numbers'], 
                    'scheme_isin'=>$value['scheme_isin'], 
                    'isin'=>$value['scheme_isin'], 
                    'image_name'=>$this->getImageName(str_replace("\r", '', $value['amc_id'])),

                    'isin_fetch_data' => $value['isin_fetch_data'],
                    'scheme_type'=>$value['scheme_type'], 
                    'is_robo'=>$value['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $value['amc_id']),
                    "plan_date"=>$next_plan_date
                );
                unset($resultArry[$key]);
                break;

            }
       
            //    $result=array_merge($result_array,$result_array1);
            $result = array
                (
                "SELECT_ARRAY"=> array_merge($result_array,$result_array1),
                "UNSELECT_ARRAY"=>array_merge($final_scheme_unselected,$final_scheme_unselected1),
                "EQUITY_PER"=> $equity_per,
                "BALANCE_PER"=> $balance_per
            );

        }

        if($risk_type=="ELSS"){

            $split_array1=array();
            $split_array=array();
            if($amount < 3000){
                $limit=2;
                if($amount==2500){
                    $split1=1500;
                    $split2=1000;   
                }
                else{
                    $split1=1000;
                    $split2=1000;

                }
                $split_array=array($split1,$split2);

            }else if($amount < 2000){

                $limit=1;
                $split1=$amount;
                $split_array[]=$split1;

            }else
            {
                $limit=6;
                $half_split=($amount/2);
                $mod_amount= fmod($half_split,500);
                if($mod_amount==0){

                    $split_array=$this->splitAmount(3,$half_split);
                    $split_array1=$this->splitAmount(3,$half_split);

                }
                else
                {
                    $half_split1=($half_split-$mod_amount)+500;
                    $half_split2=$amount-$half_split1;
                    $split_array=$this->splitAmount(3,$half_split1);
                    $split_array1=$this->splitAmount(3,$half_split2);
                }

            }

            $split_array=array_merge($split_array,$split_array1);
            $scheme_allocated_amount =round(($amount/$limit),0);

            $max_count = max($split_array);

            $sql="select DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and b.`is_robo` = 'Y'  and b.scheme_type='ELSS' and b.sip_status=1 and b.sip_frequency='MONTHLY' and  b.sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,b.`sip_dates`)  ORDER by rand() ";

            $query= $this->db->query($sql)
                ->result_array();
            // print_r($query);exit();

            $result_scheme_array=$this->schemeAvail($query,$max_count,$day,$limit,$split_array);

            $resultArry = $result_scheme_array['resultArry'];
            $final_scheme_selected = $result_scheme_array['final_scheme_selected'];

            if(count($final_scheme_selected)<$limit){


                $limit = count($final_scheme_selected);
                $split_array = $this->slipAmountByLimit($limit,$amount);

                $max_count=max($split_array);

                $sql="select DISTINCT b.amc_id,b.id,b.amc_code,b.amc_name,b.scheme_code,b.scheme_name,b.sip_transaction_mode,b.sip_frequency,b.sip_dates,b.sip_minimum_gap,b.sip_maximum_gap,b.sip_installment_gap,b.sip_status,b.sip_minimum_installment_amount,b.sip_maximum_installment_amount,b.sip_multiplier_amount,b.sip_minimum_installment_numbers,b.sip_maximum_installment_numbers,b.scheme_isin,b.scheme_type,b.is_robo,b.amc_id,b.updatetime  from bse_sip_schemes as b, bse_sip_isin_history bs  WHERE b.id=bs.bse_id and b.`is_robo` = 'Y'  and b.scheme_type='ELSS' and b.sip_status=1 and b.sip_frequency='MONTHLY' and  b.sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,b.`sip_dates`)   ORDER by rand() ";
                $query= $this->db->query($sql)
                    ->result_array();

                $result_scheme_array=$this->schemeAvail($query,$max_count,$day,$limit,$split_array);
                $resultArry = $result_scheme_array['resultArry'];
                $final_scheme_selected = $result_scheme_array['final_scheme_selected'];
            }


            /*unSelected Final scheme array */
            // print_r($resultArry);exit();
            $final_scheme_unselected=array();

            foreach ($resultArry as $key=>$value) {
                //echo $key;

                $final_scheme_unselected[]=array(
                    'amc_code'=>$value['amc_code'],
                    'amc_name'=>$value['amc_name'],
                    'scheme_code'=>$value['scheme_code'],
                    'scheme_name'=>$value['scheme_name'],
                    'sip_transaction_mode'=>$value['sip_transaction_mode'],
                    'sip_frequency'=>$value['sip_frequency'],
                    'sip_dates'=>$value['sip_dates'], 
                    'sip_minimum_gap'=>$value['sip_minimum_gap'], 
                    'sip_maximum_gap'=>$value['sip_maximum_gap'], 
                    'sip_installment_gap'=>$value['sip_installment_gap'], 
                    'sip_status'=>$value['sip_status'], 
                    'sip_minimum_installment_amount'=>$value['sip_minimum_installment_amount'], 
                    'sip_maximum_installment_amount'=>$value['sip_maximum_installment_amount'], 
                    'sip_multiplier_amount'=>$value['sip_multiplier_amount'], 
                    'sip_minimum_installment_numbers'=>$value['sip_minimum_installment_numbers'], 
                    'sip_maximum_installment_numbers'=>$value['sip_maximum_installment_numbers'], 
                    'scheme_isin'=>$value['scheme_isin'], 
                    'isin'=>$value['scheme_isin'], 
                    'image_name'=>$this->getImageName(str_replace("\r", '', $value['amc_id'])),

                    'isin_fetch_data' => $value['isin_fetch_data'],
                    'scheme_type'=>$value['scheme_type'], 
                    'is_robo'=>$value['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $value['amc_id'])
                );
                unset($resultArry[$key]);
                break;

            }

            //echo "<pre>";
            //   print_r($final_scheme_unselected);exit();
            /*          echo "<pre>";
             print_r($final_scheme_unselected);*/

            // ($final_schem);
            foreach ($final_scheme_selected as  $value) {
                $sql="select * from bse_sip_schemes where scheme_code='".$value['scheme_code']."' and sip_status=1 and `is_robo` = 'Y'  and scheme_type='ELSS' and sip_frequency='MONTHLY' and  sip_minimum_installment_amount <= '$max_count' and  FIND_IN_SET($day,`sip_dates`)" ;
                $query = $this->db->query($sql)
                    ->result_array();
                //$query[0]
                $result_array[]=array(
                    'amc_code'=>$query[0]['amc_code'],
                    'amc_name'=>$query[0]['amc_name'],
                    'scheme_code'=>$query[0]['scheme_code'],
                    'scheme_name'=>$query[0]['scheme_name'],
                    'sip_transaction_mode'=>$query[0]['sip_transaction_mode'],
                    'sip_frequency'=>$query[0]['sip_frequency'],
                    'sip_dates'=>$query[0]['sip_dates'], 
                    'sip_minimum_gap'=>$query[0]['sip_minimum_gap'], 
                    'sip_maximum_gap'=>$query[0]['sip_maximum_gap'], 
                    'sip_installment_gap'=>$query[0]['sip_installment_gap'], 
                    'sip_status'=>$query[0]['sip_status'], 
                    'sip_minimum_installment_amount'=>$query[0]['sip_minimum_installment_amount'], 
                    'sip_maximum_installment_amount'=>$query[0]['sip_maximum_installment_amount'], 
                    'sip_multiplier_amount'=>$query[0]['sip_multiplier_amount'], 
                    'sip_minimum_installment_numbers'=>$query[0]['sip_minimum_installment_numbers'], 
                    'sip_maximum_installment_numbers'=>$query[0]['sip_maximum_installment_numbers'], 
                    'scheme_isin'=>$query[0]['scheme_isin'], 
                    'isin'=>$query[0]['scheme_isin'], 

                    'image_name'=>$this->getImageName(str_replace("\r", '', $query[0]['amc_id'])),


                    'isin_fetch_data' => $value['isin_fetch_data'],
                    'scheme_type'=>$query[0]['scheme_type'], 
                    'is_robo'=>$query[0]['is_robo'], 
                    'amc_id'=>str_replace("\r", '', $query[0]['amc_id']),
                    'scheme_allocated_amount'=>$value['split_amount']
                );

            }

            $result = array
                (
                "SELECT_ARRAY"=> $result_array,
                "UNSELECT_ARRAY"=>$final_scheme_unselected,
                "EQUITY_PER"=>100,
                "BALANCE_PER"=>0,

            );


        }

        return $result;


    }
    public function insert_bse_mutiple_order_details($insert_data,$pan,$Passwordsetup){
        $this->load->helper("soap_helper");
        $this->load->helper("common_helper");


        $amount =$insert_data['Installmentamount'];
        $schemecode= $insert_data['schemecode'];
        $scheme_name= $insert_data['schemename'];


        /*Fetch data bse key table */
        $query = $this->db->query("select * from bse_key where bse_live=0")
            ->row_array();
        $bse_pass_key_data= $query;

        $member_id=$bse_pass_key_data['memberid'];
        $user_id=$bse_pass_key_data['userid'];
        $Password_bse=$bse_pass_key_data['password'];
        $Password_key=$bse_pass_key_data['passkey'];
        $euin=$bse_pass_key_data['euin'];
        $TransNo=date('Ymd').'322601'.mt_rand(100000,999999);


/*$sql="select amc_code,isin,scheme_code,scheme_name,scheme_type where "
*/
    

    $sql="SELECT `scheme_type`, `isin`, `amc_code`, `amc_id`, `scheme_code`, `scheme_name` FROM `bse_schemes` WHERE `scheme_name` = '$scheme_name' AND `scheme_code` = '$schemecode'";
    $result=$this->db->query($sql)->row();

        $insert_data=array(
            "transactioncode"=>"New",
            "uniquerefNo"=>$TransNo,
            "schemecode"=>$schemecode,
            "membercode"=>$member_id,
            "clientcode"=>$pan,
            "userid"=>$user_id,
            "internalrefno"=>$internalrefno,
            "transmode"=>$transmode,
            "dptxnmode"=>'P',
            "startdate"=>date('Y-m-d'),
            "frequencytype"=>$frequencytype,
            "FrequencyAllowed"=>$FrequencyAllowed,
            "Installmentamount"=>$amount,
            "noofInstallment"=>$noofInstallment,
            "foliono"=>$foliono,
            "firstorderflag"=>$firstorderflag,
            "subbercode"=>$subbercode,
            "euin"=>$euin,
            "euinval"=>'N',
            "dpc"=>'Y',
            "regId"=>$regId,
            "ipadd"=>$ipadd,
            "order_type"=>'Lumpsum',
            "amc_id"=>$result->amc_id,
            "scheme_name"=>$scheme_name,
            "timestamp"=>date('Y-m-d h:i:s')
        );

        $query = $this->db->insert('bse_order_entry',$insert_data);
        $last_id = $this->db->insert_id();
        $data_last_id= $last_id;

        $islive=$this->config->item('is_live');

  //    // $this->load->helper("bharti_soap_helper");
    //$this->config->item('SVC_ORDER_URL')[$islive]
        /*BSE String */
        //print_r($Passwordsetup);exit();
        $soap_url = $this->config->item('WSDL_ORDER_URL')[$islive];
        //$end_point_url = 'http://bsestarmfdemo.bseindia.com/MFOrderEntry/MFOrder.svc';
        $soap_method = "orderEntryParam";
        $soap_body_1 = '';
        /*Live add Secure*/


        $soap_body_1 = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:bses="http://bsestarmf.in/">
   <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action soap:mustUnderstand="1">http://bsestarmf.in/MFOrderEntry/orderEntryParam</wsa:Action><wsa:To soap:mustUnderstand="1">'.$this->config->item('SVC_ORDER_URL')[$islive].'</wsa:To></soap:Header>
    <soap:Body>
      <bses:orderEntryParam>
         <!--Optional:-->
         <bses:TransCode>New</bses:TransCode>
         <!--Optional:-->
         <bses:TransNo>'.$TransNo.'</bses:TransNo>
         <!--Optional:-->
         <bses:OrderId/>
         <!--Optional:-->
         <bses:UserID>'.$user_id.'</bses:UserID>
         <!--Optional:-->
         <bses:MemberId>'.$member_id.'</bses:MemberId>
         <!--Optional:-->
         <bses:ClientCode>'.$pan.'</bses:ClientCode>
         <!--Optional:-->
         <bses:SchemeCd>'.$schemecode.'</bses:SchemeCd>
         <!--Optional:-->
         <bses:BuySell>P</bses:BuySell>
         <!--Optional:-->
         <bses:BuySellType>FRESH</bses:BuySellType>
         <!--Optional:-->
         <bses:DPTxn>P</bses:DPTxn>
         <!--Optional:-->
         <bses:OrderVal>'.$amount.'</bses:OrderVal>
         <!--Optional:-->
         <bses:Qty/>
         <!--Optional:-->
         <bses:AllRedeem>N</bses:AllRedeem>
         <!--Optional:-->
         <bses:FolioNo/>
         <!--Optional:-->
         <bses:Remarks/>
         <!--Optional:-->
         <bses:KYCStatus>Y</bses:KYCStatus>
         <!--Optional:-->
         <bses:RefNo/>
         <!--Optional:-->
         <bses:SubBrCode/>
         <!--Optional:-->
         <bses:EUIN>'.$euin.'</bses:EUIN>
         <!--Optional:-->
         <bses:EUINVal>N</bses:EUINVal>
         <!--Optional:-->
         <bses:MinRedeem>N</bses:MinRedeem>
         <!--Optional:-->
         <bses:DPC>Y</bses:DPC>
         <!--Optional:-->
         <bses:IPAdd/>
         <!--Optional:-->
         <bses:Password>'.$Passwordsetup.'</bses:Password>
         <!--Optional:-->
         <bses:PassKey>'.$Password_key.'</bses:PassKey>
         <!--Optional:-->
         <bses:Parma1/>
         <!--Optional:-->
         <bses:Param2/>
         <!--Optional:-->
         <bses:Param3/>
      </bses:orderEntryParam>
   </soap:Body>

</soap:Envelope>';
        $this->load->helper("soap_helper");
        //echo $soap_body_1;exit;
        $response = soapCall($soap_url, $soap_method, $soap_body_1);
        bse_logs($pan,$member_id,$soap_body_1, $response,"LumpsumOrder Mutiple Order");

        $string_array=str_replace('s:','',$response);
        $xml = @simplexml_load_string($string_array);
        $json = json_encode($xml);
        $response_array = json_decode($json, TRUE);

        $response_string=$response_array['Body']['orderEntryParamResponse']['orderEntryParamResult'];
        $response_string_array=explode('|', $response_string);
        $bse_order_type=$response_string_array['0'];/*Order type */
        $bse_uniqueref_no=$response_string_array['1'];/*Order Unique  number */
        $bse_member_id=$response_string_array['2'];/*Order Member ID number */
        $bse_pan_number=$response_string_array['3'];/*Order Pan  number */
        $bse_user_number=$response_string_array['4'];/*Order User  number */
        $bse_REG_ID=$response_string_array['5'];/*Order bse_REG_ID   */
        $bse_order_number=$response_string_array['6'];/*Order Order Number   */

        $expol_data=explode(':', $bse_order_number);

        $order_number=$expol_data['9'];

        //$on=preg_replace('', '', $$order_number);
        preg_match('/\d+/', $order_number, $matches); 
        $on= $matches[0]; 

        $data_update=array(
            "order_number_string"=>$bse_order_number,
            "order_number"=>$on,
            "bse_reg_id"=>$bse_REG_ID,
            "order_status"=>'Pending',
        );

        $this->db->where('id', $data_last_id);
        $this->db->update('bse_order_entry', $data_update);


        $insert_data_account=array(
            "pan"=>$pan,
            "amc_id"=>$result->amc_id,
            "amc_code"=>$result->amc_code,
            "scheme_code"=>$schemecode,
            "scheme_isin"=>$result->isin,
            "bse_order_entry_id"=>$data_last_id,
            "scheme"=>$scheme_name,
            "amount"=>$amount,
            "scheme_type"=>$result->scheme_type,
            "bse_purchase_type"=>'LUMPSUM',
            "create_date"=>date('Y-m-d H:i:s')
        );
        $this->db->insert('scheme_account_master',$insert_data_account);

        return $bse_order_number;

    }   


    public function insert_bse_mutiple_sip_order_details($insert_data,$pan,$Passwordsetup){
        $this->load->helper("soap_helper");
        $this->load->helper("common_helper");

        $Installmentamount =$insert_data['Installmentamount'];
        $schemecd= $insert_data['schemecode'];
        $noofInstallment= $insert_data['noofInstallment'];
        $frequencytype= $insert_data['frequencytype'];
        $sip_day= $insert_data['sip_day'];

        /*Fetch data bse key table */
        $query = $this->db->query("select * from bse_key where bse_live=0")
            ->row_array();
        $bse_pass_key_data= $query;

        $member_id=$bse_pass_key_data['memberid'];
        $user_id=$bse_pass_key_data['userid'];
        $Password_bse=$bse_pass_key_data['password'];
        $Password_key=$bse_pass_key_data['passkey'];
        $euin=$bse_pass_key_data['euin'];
        $today_date = date('Y-m-d');
        $today_day = date('d');

        if($sip_day==''){
            $sip_day=$today_day;
        }
        $SIP_DAY=check_date($sip_day);



        $TransNo=date('Ymd').'322601'.mt_rand(100000,999999);

        $Passwordsetup=Passwordsetup1();
        $Passwordsetup=str_replace('</getPasswordResult></getPasswordResponse></s:Body></s:Envelope>', '', $Passwordsetup);
        $TransNo=date('Ymd').'322601'.mt_rand(100000,999999);

        $insert_data=array(
            "transactioncode"=>'NEW',
            "uniquerefNo"=>$TransNo,
            "schemecode"=>$schemecd,
            "membercode"=>$member_id,
            "clientcode"=>$pan,
            "userid"=>$user_id,
            "internalrefno"=>'',
            "transmode"=>'D',
            "dptxnmode"=>'C',
            "startdate"=>date('Y-m-d',strtotime($SIP_DAY)),
            "frequencytype"=>$frequencytype,
            "FrequencyAllowed"=>$FrequencyAllowed,
            "Installmentamount"=>$Installmentamount,
            "noofInstallment"=>$noofInstallment,
            "foliono"=>$foliono,
            "firstorderflag"=>$firstorderflag,
            "subbercode"=>$subbercode,
            "euin"=>$euin,
            "euinval"=>$euinval,
            "dpc"=>$dpc,
            "regId"=>$regId,
            "ipadd"=>$ipadd,
            "order_type"=>'SIP',
            "timestamp"=>date('Y-m-d H:i:s'),
        );

        $insert_order_data= $this->Api_Model->insert_bse_order_details($insert_data);
        $insert_order_data_id=$insert_order_data;

          $islive=$this->config->item('is_live');

  //    // $this->load->helper("bharti_soap_helper");
    //$this->config->item('SVC_ORDER_URL')[$islive]
        /*BSE String */
        $soap_url = $this->config->item('WSDL_ORDER_URL')[$islive];
        $soap_method = "sipOrderEntryParam";
        $soap_body_1 = '';


        $soap_body_1 = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:bses="http://bsestarmf.in/">
   <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action soap:mustUnderstand="1">http://bsestarmf.in/MFOrderEntry/sipOrderEntryParam</wsa:Action><wsa:To soap:mustUnderstand="1">'.$this->config->item('SVC_ORDER_URL')[$islive].'</wsa:To></soap:Header>
   <soap:Body>
        <bses:sipOrderEntryParam>
         <!--Optional:-->
         <bses:TransactionCode>NEW</bses:TransactionCode>
         <!--Optional:-->
         <bses:UniqueRefNo>'.$TransNo.'</bses:UniqueRefNo>
         <!--Optional:-->
         <bses:SchemeCode>'.$schemecd.'</bses:SchemeCode>
         <!--Optional:-->
         <bses:MemberCode>'.$member_id.'</bses:MemberCode>
         <!--Optional:-->
         <bses:ClientCode>'.$pan.'</bses:ClientCode>
         <!--Optional:-->
         <bses:UserID>'.$user_id.'</bses:UserID>
         <!--Optional:-->
         <bses:InternalRefNo/>
         <!--Optional:-->
         <bses:TransMode>P</bses:TransMode>
         <!--Optional:-->
         <bses:DpTxnMode>P</bses:DpTxnMode>
         <!--Optional:-->
         <bses:StartDate>'.$SIP_DAY.'</bses:StartDate>
         <!--Optional:-->
         <bses:FrequencyType>'.$frequencytype.'</bses:FrequencyType>
         <!--Optional:-->
         <bses:FrequencyAllowed>1</bses:FrequencyAllowed>
         <!--Optional:-->
         <bses:InstallmentAmount>'.$Installmentamount.'</bses:InstallmentAmount>
         <!--Optional:-->
         <bses:NoOfInstallment>'.$noofInstallment.'</bses:NoOfInstallment>
         <!--Optional:-->
         <bses:Remarks/>
         <!--Optional:-->
         <bses:FolioNo/>
         <!--Optional:-->
         <bses:FirstOrderFlag>Y</bses:FirstOrderFlag>
         <!--Optional:-->
         <bses:SubberCode/>
         <!--Optional:-->
         <bses:Euin>'.$euin.'</bses:Euin>
         <!--Optional:-->
         <bses:EuinVal>N</bses:EuinVal>
         <!--Optional:-->
         <bses:DPC>Y</bses:DPC>
         <!--Optional:-->
         <bses:RegId/>
         <!--Optional:-->
         <bses:IPAdd/>
         <!--Optional:-->
         <bses:Password>'.$Passwordsetup.'</bses:Password>
         <!--Optional:-->
         <bses:PassKey>'.$Password_key.'</bses:PassKey>
         <!--Optional:-->
         <bses:Param1/>
         <!--Optional:-->
         <bses:Param2/>
         <!--Optional:-->
         <bses:Param3/>
      </bses:sipOrderEntryParam>
   </soap:Body>
</soap:Envelope>';
        $this->load->helper("soap_helper");
        $data= $response = soapCall($soap_url, $soap_method, $soap_body_1);

        bse_logs($pan,$member_id,$soap_body_1, $data,"SIP Mutiple ORDER");

        $string_array=str_replace('s:','',$data);
        $xml = simplexml_load_string($string_array);

        $json = json_encode($xml);
        $response_array = json_decode($json, TRUE);
        $response_string=$response_array['Body']['sipOrderEntryParamResponse']['sipOrderEntryParamResult'];
        $response_string_array=explode('|', $response_string);

        $bse_order_type=$response_string_array['0'];/*Order type */
        $bse_uniqueref_no=$response_string_array['1'];/*Order Unique  number */
        $bse_member_id=$response_string_array['2'];/*Order Member ID number */
        $bse_pan_number=$response_string_array['3'];/*Order Pan  number */
        $bse_user_number=$response_string_array['4'];/*Order User  number */
        $bse_REG_ID=$response_string_array['5'];/*Order bse_REG_ID   */
        $bse_order_number=$response_string_array['6'];/*Order Order Number   */
        $data_update=array(
            "order_number_string"=>$bse_order_number,
            "bse_reg_id"=>$bse_REG_ID,
            "reg_number"=>$bse_REG_ID,
            "order_status"=>'Pending',
        );


        $this->db->where('id', $insert_order_data_id);
        $this->db->update('bse_order_entry', $data_update);
        //   $this->GettingOrderdata($insert_order_data_id,$pan,$bse_REG_ID);

        return $bse_order_number;


    }   




    public function GettingOrderdata($insert_order_data_id,$pan,$bse_REG_ID){

        $this->load->model('Api_Model');
        $this->load->helper("common_helper");

        $pasword_child_order=PasswordsetupChildOrder();
        $bse_key=$this->Api_Model->bse_key_data();
        $user_id=$bse_key['userid'];

        $member_id=$bse_key['memberid'];
        $password_bse=$bse_key['password'];
        $passkey=$bse_key['passkey'];
        $euin=$bse_key['euin'];

        $query = $this->db->query("select * from bse_order_entry where id ='$insert_order_data_id'")
            ->row_array();

        $result=$query;
        $date_start_date=$result['startdate'];

           $islive=$this->config->item('is_live');

  //    // $this->load->helper("bharti_soap_helper");
    //$this->config->item('GETTING_ORDER_ACTION_URL')[$islive]
        /*BSE String */


        $this->load->helper("soap_helper");
        // $this->load->helper("bharti_soap_helper");
        $soap_url = $this->config->item('GETTING_ORDER_DATA_URL')[$islive];
        //$end_point_url = 'http://bsestarmfdemo.bseindia.com/MFOrderEntry/MFOrder.svc';
        $soap_method = "ChildOrderDetails";
        $soap_body_1 = '';
        $soap_body_1 = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ns="http://www.bsestarmf.in/2016/01/" xmlns:star="http://schemas.datacontract.org/2004/07/StarMFWebService">
   <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action>http://www.bsestarmf.in/2016/01/IStarMFWebService/ChildOrderDetails</wsa:Action><wsa:To>'.$this->config->item('GETTING_ORDER_ACTION_URL')[$islive].'</wsa:To></soap:Header>
   <soap:Body>
      <ns:ChildOrderDetails>
         <!--Optional:-->
         <ns:Param>
            <!--Optional:-->
            <star:ClientCode>'.$pan.'</star:ClientCode>
            <!--Optional:-->
            <star:Date>  '.date('d M Y',strtotime($date_start_date)).'</star:Date>
            <!--Optional:-->
            <star:EncryptedPassword>'.$pasword_child_order.'</star:EncryptedPassword>
            <!--Optional:-->
            <star:MemberCode>'.$member_id.'</star:MemberCode>
            <!--Optional:-->
            <star:RegnNo>'.$bse_REG_ID.'</star:RegnNo>
            <!--Optional:-->
            <star:SystematicPlanType>SIP</star:SystematicPlanType>
         </ns:Param>
      </ns:ChildOrderDetails>
   </soap:Body>
</soap:Envelope>';
        $response = soapCall($soap_url, $soap_method, $soap_body_1);
        $string_rep_s= str_replace('s:','', $response);
        $string_rep_b= str_replace('b:','', $string_rep_s);
/*           bse_logs($pan,$member_id,$soap_body_1, $data,"SIP CHILD ORDER");
*/

        $array = array(
            "client_code" => $pan,
            "user_id" => $user_id,
            "request_data"=> $soap_body_1,
            "response_data"=> $response,
            "timestamp" =>date('Y-m-d H:i:s'),
            "type"=>"Child Order",
        );  
        $this->db->insert("bse_logs", $array);

        $xml=@simplexml_load_string($string_rep_b);
        $json = json_encode($xml);
        $response_array = json_decode($json, TRUE);
        $order_no =$response_array['Body']['ChildOrderDetailsResponse']['ChildOrderDetailsResult']['ChildOrderDetails']['ChildOrderDetails']['OrderNumber'];
        $data_update=array(
            "order_number"=>$order_no,
            "order_status"=>'Pending',
        );
        $this->db->where('id', $insert_order_data_id);
        $this->db->update('bse_order_entry', $data_update);

    }

    function splitAmount($limit,$amount){
        $split_amount=$amount/$limit;
        $final_split=0;
        //$multiMod = $split_amount%1000;
        $multiMod = fmod($split_amount,1000);
        if($multiMod == 0 ||  $multiMod == 500){
            $split1 =$split_amount;
            $split2 =$split_amount;
            $split3 =$split_amount;
        }
        else
        {
            $multiModT = floor($multiMod);
            switch ($multiModT) {
                case '166':
                    $split1 = ($split_amount-$multiMod)+500;
                    $splitT  = $amount-$split1;

                    $split_amount2 = $splitT/($limit-1);

                    $split2  = $split_amount2;
                    $split3  = $split_amount2;
                    break;
                case '333':
                    $split1 = ($split_amount-$multiMod)+1000;
                    $splitT  = $amount-$split1;

                    $split_amount2 = $splitT/($limit-1);

                    $split2  = $split_amount2;
                    $split3  = $split_amount2;
                    break;
                case '666':
                    $split1 = ($split_amount-$multiMod)+1000;
                    $splitT  = $amount-$split1;

                    $split_amount2 = $splitT/($limit-1);

                    $split2  = $split_amount2;
                    $split3  = $split_amount2;
                    break;
                case '833':
                    $split1 = ($split_amount-$multiMod)+1000;
                    $split2 = $split1;
                    $split3  = $amount-($split1*2);
                    # code...
                    break;
                default:
                    # code...
                    break;
            }




        }


        $result=array($split1,$split2,$split3);

        return $result;
    }

    function splitAmountLumpsum($limit,$amount){
        $split_amount=$amount/$limit;
        $final_split=0;
        //$multiMod = $split_amount%1000;
        $multiMod = fmod($split_amount,1000);
        if($multiMod == 0){
            $split1 =$split_amount;
            $split2 =$split_amount;
            $split3 =$split_amount;
        }
        else
        {
            
            $multiMod_diff=1000-$multiMod;
            $split1 = $split_amount+ $multiMod_diff;


            $split_new_array = $this->splitmodAmountLumpsum(2,$amount-$split1);
            $split2  = $split_new_array[0];
            $split3  = $split_new_array[1];

        }


        $result=array($split1,$split2,$split3);
        return $result;
    }

    function splitRoboAggrAmount($limit,$amount){

        $split_amount=$amount/$limit;
        $final_split=0;

        $multiMod = fmod($split_amount,500);


        if($multiMod == 0){
            $split1 =$split_amount;
            $split2 =$split_amount;
            $split3 =$split_amount;
            $split4 =$split_amount;
            $result=array($split1,$split2,$split3,$split4);
        }
        else
        {
            if($multiMod < 500){
                $split1= ($split_amount - $multiMod) + 500;
            }
            else{
                $split1=($split_amount - $multiMod) + 1000;

            }
            $splitT=$amount-$split1;
            $s_array=$this->splitAmount($limit-1,$splitT);
            // $s_array= array_unshift($s_array, $split1);
            $result=array($split1,$s_array[0],$s_array[1],$s_array[2]);
        }




        return $result;
    }

    function splitFiveAmount($limit,$amount){

        $split_amount=$amount/$limit;
        $final_split=0;

        $multiMod = fmod($split_amount,500);


        if($multiMod == 0){
            $split1 =$split_amount;
            $split2 =$split_amount;
            $split3 =$split_amount;
            $split4 =$split_amount;
            $split5 =$split_amount;
            $result=array($split1,$split2,$split3,$split4,$split5);
        }
        else
        {
            if($multiMod < 500){
                $split1= ($split_amount - $multiMod) + 500;
            }
            else{
                $split1=($split_amount - $multiMod) + 1000;

            }
            $splitT=$amount-$split1;
            $s_array=$this->splitRoboAggrAmount($limit-1,$splitT);
            // $s_array= array_unshift($s_array, $split1);
            $result=array($split1,$s_array[0],$s_array[1],$s_array[2],$s_array[3]);
        }




        return $result;
    }

    function splitmodAmount($limit,$amount){
        $split_amount=$amount/$limit;
        $final_split=0;
        $multiMod = fmod($split_amount,500);
        if($multiMod == 0){
            $split1 =$split_amount;
            $split2 =$split_amount;
        }
        else
        {
            $multiMod_diff=500-$multiMod;

            $split1 = $split_amount+ $multiMod_diff;
            $split2  = $amount-$split1;

            
        }


        $result=array($split1,$split2);
        return $result;
    }

    function splitmodAmountLumpsum($limit,$amount){
        $split_amount=$amount/$limit;
        $final_split=0;
        $multiMod = fmod($split_amount,1000);
        if($multiMod == 0){
            $split1 =$split_amount;
            $split2 =$split_amount;
        }
        else
        {
            $multiMod_diff=1000-$multiMod;

            $split1 = $split_amount+ $multiMod_diff;
            $split2  = $amount-$split1;

            /*$multiModT = floor($multiMod);
            $split1 = ($split_amount-$multiMod)+500;
            $split2  = $amount-$split1;*/
        }


        $result=array($split1,$split2);

        return $result;
    }

    function schemeAvailLump($query,$max_count,$day,$limit,$split_array)
    {

        foreach ($query as $key => $value) {

            $isin_number = $query[$key]['isin'];
            $isin_id = $query[$key]['id'];
            $isin_fetch_data= $this->getSchemePerformanceByISINSchemedatabase_get($isin_id);
            /**//*$isin_fetch_data = json_decode($isin_fetch_data);*/

            if($isin_fetch_data || !empty($isin_fetch_data) || $isin_fetch_data != NULL || $isin_fetch_data != '' || $isin_fetch_data != null ){

                //$query[$key]['scheme_allocated_amount']=$scheme_allocated_amount;
                    //'amc_name'=>$value['amc_name'],
                   
                $resultArry[$key]['amc_code']=$query[$key]['amc_code'];
                //$resultArry[$key]['amc_name']=$query[$key]['amc_name'];
                $resultArry[$key]['scheme_code']=$query[$key]['scheme_code'];
                $resultArry[$key]['scheme_name']=$query[$key]['scheme_name'];
                $resultArry[$key]['purchase_transaction_mode']=$query[$key]['purchase_transaction_mode'];
                $resultArry[$key]['minimum_purchase_amount']=$query[$key]['minimum_purchase_amount']; 
                $resultArry[$key]['maximum_purchase_amount']=$query[$key]['maximum_purchase_amount']; 
                $resultArry[$key]['purchase_amount_multiplier']=$query[$key]['purchase_amount_multiplier'];             
                $resultArry[$key]['isin']=$query[$key]['isin']; 

                $resultArry[$key]['isin_fetch_data']= $isin_fetch_data; 

                

                $resultArry[$key]['scheme_type']=$query[$key]['scheme_type']; 
                $resultArry[$key]['is_robo']=$query[$key]['is_robo']; 
                $resultArry[$key]['amc_id']=str_replace("\r", '', $query[$key]['amc_id']);
            }

        }

        //echo "<pre>"; print_r($resultArry);exit;
        /*Selected Final scheme array */
        $final_scheme_selected=array();
        //echo "==>".$limit;
        for ($i=0; $i < $limit ; $i++) { 
                foreach ($resultArry as $key=>$value) {
                    if($value['minimum_purchase_amount'] <= $split_array[$i]){
                        //if($isin_fetch_data){
                            $final_scheme_selected[]=array(
                                'scheme_code'=>$value['scheme_code'],
                                'minimum_purchase_amount'=>$value['minimum_purchase_amount'],
                                 'purchase_amount_multiplier'=>$value['purchase_amount_multiplier'],
                                'split_amount'=>$split_array[$i],
                                'isin_fetch_data' => $value['isin_fetch_data']
                            );
                            unset($resultArry[$key]);
                            break;

                        //}
                        
                    }    
                }
        }  

        $result=array(
            "resultArry"=>$resultArry,
            "final_scheme_selected"=>$final_scheme_selected
        );
        return $result;

    }

     function schemeAvail($query,$max_count,$day,$limit,$split_array)
    {
        foreach ($query as $key => $value) {

            $isin_number = $query[$key]['scheme_isin'];
            $bse_sip_id = $query[$key]['id'];
            $isin_fetch_data= $this->getSchemePerformanceByISINdatabase_get($bse_sip_id);
            /**//*$isin_fetch_data = json_decode($isin_fetch_data);*/

            if($isin_fetch_data || !empty($isin_fetch_data) || $isin_fetch_data != NULL || $isin_fetch_data != '' || $isin_fetch_data != null ){

            //$query[$key]['scheme_allocated_amount']=$scheme_allocated_amount;

                $resultArry[$key]['amc_code']=$query[$key]['amc_code'];
                $resultArry[$key]['amc_name']=$query[$key]['amc_name'];
                $resultArry[$key]['scheme_code']=$query[$key]['scheme_code'];
                $resultArry[$key]['scheme_name']=$query[$key]['scheme_name'];
                $resultArry[$key]['sip_transaction_mode']=$query[$key]['sip_transaction_mode'];
                $resultArry[$key]['sip_frequency']=$query[$key]['sip_frequency'];
                $resultArry[$key]['sip_dates']=$query[$key]['sip_dates']; 
                $resultArry[$key]['sip_minimum_gap']=$query[$key]['sip_minimum_gap']; 
                $resultArry[$key]['sip_maximum_gap']=$query[$key]['sip_maximum_gap']; 
                $resultArry[$key]['sip_installment_gap']=$query[$key]['sip_installment_gap']; 
                $resultArry[$key]['sip_status']=$query[$key]['sip_status']; 
                $resultArry[$key]['sip_minimum_installment_amount']=$query[$key]['sip_minimum_installment_amount']; 
                $resultArry[$key]['sip_maximum_installment_amount']=$query[$key]['sip_maximum_installment_amount']; 
                $resultArry[$key]['sip_multiplier_amount']=$query[$key]['sip_multiplier_amount']; 
                $resultArry[$key]['sip_minimum_installment_numbers']=$query[$key]['sip_minimum_installment_numbers']; 
                $resultArry[$key]['sip_maximum_installment_numbers']=$query[$key]['sip_maximum_installment_numbers']; 
                $resultArry[$key]['scheme_isin']=$query[$key]['scheme_isin']; 

                $resultArry[$key]['isin_fetch_data']= $isin_fetch_data; 


                $resultArry[$key]['scheme_type']=$query[$key]['scheme_type']; 
                $resultArry[$key]['is_robo']=$query[$key]['is_robo']; 
                $resultArry[$key]['amc_id']=str_replace("\r", '', $query[$key]['amc_id']);

            }


        }
        /*Selected Final scheme array */
        $final_scheme_selected=array();
        for ($i=0; $i < $limit ; $i++) { 
            $split_check= ($split_array[$i])%1000 ;
            if($split_check==0){
                foreach ($resultArry as $key=>$value) {
                    //echo $key;
                    if($value['sip_minimum_installment_amount'] <= $split_array[$i]){
                        $final_scheme_selected[]=array(
                            'scheme_code'=>$value['scheme_code'],
                            'sip_minimum_installment_amount'=>$value['sip_minimum_installment_amount'],
                            'split_amount'=>$split_array[$i],
                            'isin_fetch_data' => $value['isin_fetch_data']
                        );
                        unset($resultArry[$key]);
                        break;
                    }    
                }
            }
            else{

                foreach ($resultArry as $key=>$value) {
                    //echo $key;
                    if($value['sip_minimum_installment_amount']%500==0 && $value['sip_minimum_installment_amount'] <= $split_array[$i]){
                        $final_scheme_selected[]=array(
                            'scheme_code'=>$value['scheme_code'],
                            'sip_minimum_installment_amount'=>$value['sip_minimum_installment_amount'],
                            'split_amount'=>$split_array[$i],
                            'isin_fetch_data' => $isin_fetch_data
                        ); 
                        unset($resultArry[$key]); 
                        break;
                    }    
                }
            }
        }  

        $result=array(
            "resultArry"=>$resultArry,
            "final_scheme_selected"=>$final_scheme_selected
        );
        return $result;

    }

    function slipAmountByLimit($limit,$amount){
        switch ($limit) {
            case '1':
                $split_array = array($amount);
                return $split_array;
                break;
            case '2':
                $split_array = $this->splitmodAmount($limit,$amount);
                return $split_array;
                break;
            case '3':
                $split_array = $this->splitAmount($limit,$amount);
                return $split_array;
                break;
            case '4':
                $split_array = $this->splitRoboAggrAmount($limit,$amount);
                return $split_array;
                break;
            case '5':
                $split_array = $this->splitFiveAmount($limit,$amount);
                return $split_array;
                # code...
                break;
            default:
                # code...
                break;


        }

    }

     function slipAmountByLimitLumpsum($limit,$amount){

        switch ($limit) {
            case '1':
                $split_array = array($amount);
                return $split_array;
                break;
            case '2':
                $split_array = $this->splitmodAmountLumpsum($limit,$amount);
                return $split_array;
                break;
            case '3':
                $split_array = $this->splitAmountLumpsum($limit,$amount);
                return $split_array;
                break;
        }

    }

    function check_min_bal_amount_lumpsum($balance_amount,$amount,$day)
    {

        $date_check="yes";
/*select *  from bse_schemes WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND  minimum_purchase_amount <= '$max_count'  AND  `dividend_reinvestment_flag` = 'Z' AND  (`amc_id` != '10' AND `amc_id` != '15' and amc_id!='16' and amc_id!='9' and amc_id!='12')  AND scheme_type='BALANCED' group by amc_id ORDER by rand()*/
    $sql="SELECT *
FROM `bse_schemes`
WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND  minimum_purchase_amount <= '$balance_amount'  AND  `dividend_reinvestment_flag` = 'Z' AND  (`amc_id` != '10' AND `amc_id` != '15' and amc_id!='16' and amc_id!='9' and amc_id!='12')  AND scheme_type='BALANCED' ";

 
        $array= $this->db->query($sql)

            ->result_array();

            if(empty($array))
            {

             $minimum_purchase_amount="SELECT MIN(minimum_purchase_amount) as min_amount 
FROM `bse_schemes`
WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND  minimum_purchase_amount <= '$balance_amount'  AND  `dividend_reinvestment_flag` = 'Z' AND  (`amc_id` != '10' AND `amc_id` != '15' and amc_id!='16' and amc_id!='9' and amc_id!='12')  AND scheme_type='BALANCED'";

$array_sip= $this->db->query($minimum_purchase_amount)
            ->row();
            /*check  */
if($array_sip->min_amount=="")
{

$minimum_purchase_amount="SELECT MIN(minimum_purchase_amount) as min_amount 
FROM `bse_schemes`
WHERE `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` < '200000' AND `scheme_plan` = 'NORMAL' AND  minimum_purchase_amount <= '$balance_amount'  AND  `dividend_reinvestment_flag` = 'Z' AND  (`amc_id` != '10' AND `amc_id` != '15' and amc_id!='16' and amc_id!='9' and amc_id!='12')  AND scheme_type='BALANCED'";
$array_sip= $this->db->query($minimum_purchase_amount)
            ->row();

        $date_check="no";



}


            $min_amount=$array_sip->min_amount;
            //
             $perct=(($min_amount/$amount)*100);
             $round_amount=round($perct);
           if($round_amount <= 50)
           {
            $min_amount_array=array( 
                "amount"=>$min_amount,
                "date_check"=>$date_check
            );
            return $min_amount_array;

           }
           else
           {

             $min_amount_array=array( 
                "amount"=>0,
                "date_check"=>$date_check
            );

            return $min_amount_array;
           }



            }
            else{
                 $min_amount_array=array( 
                "amount"=>$balance_amount,
                "date_check"=>$date_check
            );

                 return  $min_amount_array;
            }

    }

    function check_min_bal_amount($balance_amount,$amount,$day)
    {

        $date_check="yes";

    $sql="SELECT *
FROM `bse_sip_schemes`
WHERE `scheme_type`= 'BALANCED'  and sip_status=1 
AND `is_robo` = 'y' AND sip_minimum_installment_amount <=$balance_amount 
 and FIND_IN_SET($day,`sip_dates`)
";
        $array= $this->db->query($sql)

            ->result_array();

            if(empty($array))
            {

             $sip_minimum_installment_amount="SELECT MIN(sip_minimum_installment_amount) as min_amount 
FROM `bse_sip_schemes`
WHERE `scheme_type`= 'BALANCED' and sip_status=1 AND `is_robo` = 'y' and  FIND_IN_SET($day,`sip_dates`)";

$array_sip= $this->db->query($sip_minimum_installment_amount)
            ->row();
            /*check  */
if($array_sip->min_amount=="")
{

$sip_minimum_installment_amount="SELECT MIN(sip_minimum_installment_amount) as min_amount 
FROM `bse_sip_schemes`
WHERE `scheme_type`= 'BALANCED' and sip_status=1 AND `is_robo` = 'y' ";
$array_sip= $this->db->query($sip_minimum_installment_amount)
            ->row();

        $date_check="no";



}

            
            $min_amount=$array_sip->min_amount;
            //
             $perct=(($min_amount/$amount)*100);
             $round_amount=round($perct);
           if($round_amount <= 50)
           {
            $min_amount_array=array( 
                "amount"=>$balance_amount,
                "date_check"=>$date_check
            );
            return $min_amount_array;

           }
           else
           {

             $min_amount_array=array( 
                "amount"=>0,
                "date_check"=>$date_check
            );

            return $min_amount_array;
           }



            }
            else{
                 $min_amount_array=array( 
                "amount"=>$balance_amount,
                "date_check"=>$date_check
            );

                 return  $min_amount_array;
            }

    }


function findDate($inputDate, $offerDays) {
    $offerDays=explode(',', $offerDays);
$date = DateTime::createFromFormat('Y/m/d', $inputDate);
   $num = $date->format('d');

   $min = 31; //initialize minimum days

   foreach($offerDays as $o){  //loop through all the offerdays to find the minimum difference
     $dif = $o - $num;
     if($dif>0 && $dif < $min){
        $min = $dif ;
       }
   }
   // if minimum days is not changed then get the first offerday from next week
   if($min == 10){
      $min = 6 - $num + min($offerDays);
   }

   //add the days till next offerday
   $add = new DateInterval('P'.$min.'D');
   $nextOfferDay = $date->add($add)->format('d');

   return $nextOfferDay;
}

function getDataFromTable($table_name = null, $where = null) {
        if ($where != null) {
            $this->db->where($where);
        }
        return $result = $this->db
            ->from($table_name)
            ->get()
            ->result_array();
    }


    function getDataFromTableWithOject($table_name = null, $where = null) {
        if ($where != null) {
            $this->db->where($where);
        }
        return $result = $this->db
            ->from($table_name)
            ->get()
            ->result();
    }

    function getRowDataFromTable($table_name = null, $where = null) {
        if ($where != null) {
            $this->db->where($where);
        }
        return $result = $this->db
            ->from($table_name)
            ->get()
            ->row_array();
    }


    function getRowDataFromTableWithOject($table_name = null, $where = null) {
        if ($where != null) {
            $this->db->where($where);
        }
        return $result = $this->db
            ->from($table_name)
            ->get()
            ->row();
    }

    function getRowDataFromTableUsingLike($table_name = null, $where = null) {
        if ($where != null) {
            $this->db->like($where);
        }
        return $result = $this->db
            ->from($table_name)
            ->get()
            ->row_array();
    }

    function deleteTable($where, $table) {
        $this->db->where($where);
        if ($this->db->delete($table)) {
            return true;
        } else {
            return false;
        }
    }

    function updateTable($table, $data, $where) {
      
/*echo $table;echo "=1";
print_r($data);echo "=2";
print_r($where);echo "=3";*/

/*die();*/
        $this->db->where($where);
        $this->db->set($data);

        if ($this->db->update($table)) {
            return true;
        } else {
            return false;
        }
    }

    function insertIntoTable($table_name, $data) {
        $this->db->insert($table_name, $data);
		
            return $this->db->insert_id();
        
    }

    function insertIntoTableBatch($table_name, $data) {
        if ($this->db->insert_batch($table_name, $data)) {
            return true;
        } else {
            return false;
        }
    }


    function get_url_e_mandate($pan)
    {

      $this->load->helper("soap_helper");
      $islive=$this->config->item('is_live');
      $bse_key=$this->bse_key_data();
      $memberid=$bse_key['memberid'];
      $password=$bse_key['password'];
      $userid=$bse_key['userid'];
      $fetch_customer_details = $this->fetch_customer_details($pan);

      $otm=$fetch_customer_details['otm'];
      $otm1=$fetch_customer_details['otm1'];
      $otm2=$fetch_customer_details['otm2'];
  
        $soap_url=$this->config->item('PROVORDERSTATUS')[$islive];
            $soap_method = "EMandateAuthURL";
            $soap_body_1='<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ns="http://www.bsestarmf.in/2016/01/" xmlns:star="http://schemas.datacontract.org/2004/07/StarMFWebService">
   <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action>http://www.bsestarmf.in/2016/01/IStarMFWebService/EMandateAuthURL</wsa:Action><wsa:To>'.$this->config->item('CHILD_ORDER_TO_URL')[$islive].'</wsa:To></soap:Header>
   <soap:Body>
      <ns:EMandateAuthURL>
         <!--Optional:-->
         <ns:Param>
            <!--Optional:-->
            <star:ClientCode>'.$pan.'</star:ClientCode>
            <!--Optional:-->
            <star:MandateID>'.$otm2.'</star:MandateID>
            <!--Optional:-->
            <star:MemberCode>'.$memberid.'</star:MemberCode>
            <!--Optional:-->
            <star:Password>'.$password.'</star:Password>
            <!--Optional:-->
            <star:UserId>'.$userid.'</star:UserId> 
         </ns:Param>
      </ns:EMandateAuthURL>
   </soap:Body>
</soap:Envelope>';
$response = soapCall($soap_url, $soap_method, $soap_body_1);

$response_data=$response;


$response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
$xml = new  SimpleXMLElement($response);
bse_logs($pan,$memberid,$soap_body_1,$response_data,"Mandate API");
  return $xml;
    }






public function bank_mandate($pan_number,$type)
{
	$bAmount = 0;
	$number_text = '';
   $this->load->helper("common_helper");

/**/
$fetch_customer_details = $this->fetch_customer_details($pan_number);


$mandate_array = $this->get_url_mandate($pan_number,$mandate_array['otm2']);

$to=$fetch_customer_details['email'];
$client_holding=$fetch_customer_details['client_holding'];



$url_string=$mandate_array->sBody->MandateDetailsResponse->MandateDetailsResult->bMandateDetails;
$bank_array_url_string=(array)$url_string;
$bank_array=($bank_array_url_string['bMandateDetails']);
$count=count($bank_array);
$mandata=(array)$bank_array[$count-1];
/*$array_data_mandate=(array)$url_string->bMandateDetails;
*/
$bAmount=$mandata['bAmount'];
$reg_date=$mandata['bRegnDate'];
$name=strtoupper($fetch_customer_details['name']);
$bank_account_type=strtoupper($fetch_customer_details['bank_account_type']);
//echo "==>".print_r($bAmount);die();
$number_text = getIndianCurrency($bAmount);


$date_form=date('d m Y',strtotime($reg_date));
$date_form_1=date('d-m-Y',strtotime($reg_date));

$date_expol=explode('-', $date_form_1);

$signature_string = $fetch_customer_details['signature_string'];

$signature_image='';


// <img src="images/check-icon-nb-2.png" height="8px;"> 
$sb='';
$ca='';
$cc='';
$sbnre='';
$sbnro='';
$other='';
$second_holding_name='';


if($client_holding=="JO" ||$client_holding=="AS" ){
$second_holding_name=$fetch_customer_details['second_app_name'];
}



switch ($bank_account_type) {
    case 'SB':
        $sb='<img src="images/check-icon-nb-2.png" height="8px;">';
        break;
        case 'CB':
        $ca='<img src="images/check-icon-nb-2.png" height="8px;">';
        break;
        case 'NE':
        $sbnre='<img src="images/check-icon-nb-2.png" height="8px;">';
        break;
        case 'NO':
        $sbnro='<img src="images/check-icon-nb-2.png" height="8px;">';
        break;

    default:
            $sb='<img src="images/check-icon-nb-2.png" height="8px;">';
            break;
}

/*<img src="data:image/jpg;base64,'.$signature_string.'"  width=10% height=5%>*/

 ob_start();
 $this->load->library('Tcpdf/Tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Bank Mandate Form');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(10, 15, 10);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 10);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 8);

// add a page
$pdf->AddPage();



// set some text to print

$html = '
<style>
    .textcenter {text-align:center;}
    .textright {text-align:right;}
    .border {border:1px solid #000;}
</style>

<table cellpadding="0" border="0" cellspacing="0" style="color: #000; font-size: 8pt; line-height:12px; color:#000;">  
    <tr>
        <td>
            <table cellpadding="4" border="0" cellspacing="2">
                <tr>
                    <td width="7%"><b>UMRN:</b></td>
                    <td width="50%" class="border"></td>
                    <td width="28%" class="textright"><b>Date:</b></td>
                    <td width="14.7%" class="border">'.$date_form_1.'</td>
                </tr>
            </table>
            <table cellpadding="2" border="0" cellspacing="0">
                <tr>
                    <td width="15%">
                        <table cellpadding="4" border="0" cellspacing="3" width="95%">
                            <tr>
                                <td>Tick ( <img src="images/check-icon-nb-2.png" height="8px;"> )</td>
                            </tr>
                            <tr>
                                <td class="border">CREATE <img src="images/check-icon-nb-2.png" height="8px;"></td>
                            </tr>
                            <tr>
                                <td class="border">MODIFY</td>
                            </tr>
                            <tr>
                                <td class="border">CANCEL</td>
                            </tr>
                        </table>
                    </td>
                    <td width="85%">
                        <table cellpadding="4" border="0" cellspacing="2">
                            <tr>
                                <td colspan="5">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="22%"><b>Sponsor Bank Code:</b></td>
                                <td width="28%" class="border">CITI000PIGW</td>
                                <td width="5%"></td>
                                <td width="13%"><b>Utility Code:</b></td>
                                <td width="32%" class="border">CITI00002000000037</td>
                            </tr>
                        </table>
                        <table cellpadding="4" border="0" cellspacing="2">
                            <tr>
                                <td width="22%"><b>I/We hereby authorize:</b></td>
                                <td width="18%" class="border"> BSE LIMITED</td>
                                <td width="3%"></td>
                                <td width="13%"><b>to be Tick :<img src="images/check-icon-nb-2.png" height="8px;"></b></td>
                                <td width="44%">SB '.$sb.'/ CA  '.$ca.' / CC '.$cc.'/  SB-NRE  '.$sbnre.'/SB-NRO '.$sbnro.' / Other '.$other.' </td>
                            </tr>
                        </table>
                        <table cellpadding="4" border="0" cellspacing="2">
                            <tr>
                                <td width="21.8%"><b>Bank a/c number:</b></td>
                                <td width="78.2%" class="border">'.$fetch_customer_details['bank_account_number'].'</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table cellpadding="4" border="0" cellspacing="2">
                <tr> 
                    <td width="10%"><b>With Bank:</b></td>
                    <td width="29%" class="border">'.$fetch_customer_details['bank_name'].'</td>
                    <td width="2%"></td>
                    <td width="6%"><b>IFSC:</b></td>
                    <td width="20%" class="border">'.$fetch_customer_details['bank_ifsc_code'].'</td>
                    <td width="2%"></td>
                    <td width="8%"><b>or MICR:</b></td>
                    <td width="22.7%" class="border">'.$fetch_customer_details['bank_micr_code'].'</td>
                </tr>
            </table>
            <table cellpadding="4" border="0" cellspacing="2">
                <tr>
                    <td width="18%"><b>an Amount of Rupees:</b></td>
                    <td width="53%" class="border">'.strtoupper($number_text).'</td>
                    <td width="2%"></td>
                    <td width="4.2%"><b>Rs.</b> </td>
                    <td width="22.6%" class="border"> '.$bAmount.'</td>
                </tr>
            </table>
            <table cellpadding="4" border="0" cellspacing="2">
                <tr>
                    <td width="60%"><b>FREQUENCY: &nbsp; &nbsp;</b> <img src="images/na-uncheck-icon.jpg" height="8px;"> Mthly &nbsp; &nbsp;<img src="images/na-uncheck-icon.jpg" height="8px;"> Qtly &nbsp; &nbsp;<img src="images/na-uncheck-icon.jpg" height="8px;"> H-Yrly  &nbsp; &nbsp; <img src="images/na-uncheck-icon.jpg" height="8px;"> Yrly &nbsp; &nbsp; <img src="images/check-icon.jpg" height="8px;"> As & when presented </td>
                    <td width="40%" class="textright"><b>DEBIT TYPE: &nbsp; &nbsp;</b> <img src="images/na-uncheck-icon.jpg" height="8px;"> Fixed Amount &nbsp; &nbsp;<img src="images/check-icon.jpg" height="8px;"> Maximum Amount</td>
                </tr>
            </table>
            <table cellpadding="4" border="0" cellspacing="2">
                <tr>
                    <td width="11%"><b>Unique ID:</b></td>
                    <td width="42%" class="border">'.$fetch_customer_details['otm2'].'</td>
                    <td width="2%"></td>
                    <td width="10%"><b>Phone No:</b></td>
                    <td width="34.6%" class="border">'.$fetch_customer_details['mobile'].'</td>
                </tr>
            </table>
            <table cellpadding="4" border="0" cellspacing="2">
                <tr>
                    <td width="11%"><b>Reference 2:</b></td>
                    <td width="42%" class="border">'.$fetch_customer_details['otm2'].'</td>
                    <td width="2%"></td>
                    <td width="10%"><b>Email ID:</b></td>
                    <td width="34.6%" class="border">'.$fetch_customer_details['email'].'</td>
                </tr>
            </table>
            <table cellpadding="4" border="0" cellspacing="2">
                <tr>
                    <td style="font-size:7pt;">I agree for the debit of mandate processing charges by the bank whom I am authorizing to debit my account as per latest schedule of charges of the bank.</td>
                </tr>
            </table>
            <table cellpadding="0" border="0" cellspacing="0">
                <tr>
                    <td width="23%">
                        <table cellpadding="4" border="0" cellspacing="4">
                            <tr>
                                <td colspan="2"><b>PERIOD</b></td>
                            </tr>
                            <tr>
                                <td width="25%">From</td>
                                <td width="20%" class="border">'.$date_expol['0'].'</td>
                                <td width="20%" class="border">'.$date_expol['1'].'</td>
                                <td width="35%" class="border">'.$date_expol['2'].'</td>
                            </tr>
                            <tr>
                                <td>To</td>
                                <td class="border">XX</td>
                                <td class="border">XX</td>
                                <td class="border">XXXX</td>
                            </tr>
                            <tr>
                                <td>Or</td>
                                <td class="border" colspan="3"><img src="images/check-icon.jpg" height="8px;"> Until Cancelled</td>
                            </tr>
                        </table>
                    </td>
                    <td width="1%"></td>
                    <td width="75%">
                        <table cellpadding="4" border="0" cellspacing="4">
                            <tr>
                                 <td width="4%"></td>
                                <td colspan="4"><b>Account Holder Signature</b></td>
                            </tr>
                            <tr>
                                <td width="4%"></td>
                                <td width="45%" class="border" height="55"></td>
                                <td width="2%"></td>
                                <td width="4%"></td>
                                <td width="45%" class="border"></td>
                            </tr>
                            <tr>
                                <td>1.</td>
                                <td class="border" height="10">'.strtoupper($fetch_customer_details['name']).'</td>
                                <td></td>
                                <td>2.</td>
                                <td class="border">'.strtoupper($second_holding_name).'</td>
                            </tr>
                        </table>
                    </td>                   
                </tr>
                <tr>
                    <td></td>
                </tr>
            </table>
            <table cellpadding="4" border="0" cellspacing="2" style="font-size:6.5pt; line-height:8pt; border-top:1px solid #000;">
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td>- This is to confirm that the declaration has been carefully read, understood & made by me/us. I am authorizing the user entity/ Corporate to debit my account, based on the instructions as agreed and signed by me.</td>
                </tr>
                <tr>
                    <td>- I have understood that I am authorised to cancel/amend this mandate by appropriately communicating the cancellation / amendment request to the User entity / Corporate or the bank where I have authorized the debit.</td>
                </tr>
                <tr>
                    <td style="border-bottom:1px dashed #000;"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
';



$pdf->writeHTML($html, true, 0, true, 0, '');

if($type=="send_mandate_mail")
{
$data_pdf_file=$pdf->Output('Bank-Mandate-Form.pdf','S');
/*$data_pdf_file='http://103.87.174.12/heromutual/dev/assets/images/logo.png';
*/
$template=sendbankmndatetemplate($name);
SendMail($to,'heromf@herocorp.com',$template,'Your  Mandate form',$data_pdf_file,'Mandate Form');
/*SendMail('kumari.jayashree@mysolutionnow.com',$from,$template,'Your  Mandate form','');
*/}

else
{
$pdf->Output('Bank-Mandate-Form.pdf', 'I');
}
}
    function get_url_mandate($pan,$Mandate_id)
    {

      $this->load->helper("soap_helper");
      $this->load->helper("common_helper");
      $password_auth=mandateauthpassword();

      $islive=$this->config->item('is_live');
      $bse_key=$this->bse_key_data();
      $memberid=$bse_key['memberid'];
      $password=$bse_key['password'];
      $userid=$bse_key['userid'];
      $fetch_customer_details = $this->fetch_customer_details($pan);

    $now_date = new DateTime('now');
    $to_date = new DateTime('now');

    $now_date->modify('-3 month'); // or you can use '-90 day' for deduct
    $start_date = $now_date->format('d/m/Y');

    $to_date->modify('+1 day'); // or you can use '-90 day' for deduct
    $end_date = $to_date->format('d/m/Y');




      $otm=$fetch_customer_details['otm'];
      $otm1=$fetch_customer_details['otm1'];
      $otm2=$fetch_customer_details['otm2'];
        $soap_url=$this->config->item('PROVORDERSTATUS')[$islive];
            $soap_method = "MandateDetails";
            $soap_body_1='<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ns="http://www.bsestarmf.in/2016/01/" xmlns:star="http://schemas.datacontract.org/2004/07/StarMFWebService">
   <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action>http://www.bsestarmf.in/2016/01/IStarMFWebService/MandateDetails</wsa:Action><wsa:To>'.$this->config->item('CHILD_ORDER_TO_URL')[$islive].'</wsa:To></soap:Header>
   <soap:Body>
      <ns:MandateDetails>
         <!--Optional:-->
         <ns:Param>
            <!--Optional:-->
            <star:ClientCode>'.$pan.'</star:ClientCode>
            <!--Optional:-->
            <star:EncryptedPassword>'.$password_auth.'</star:EncryptedPassword>
            <!--Optional:-->
            <star:FromDate>'.$start_date.'</star:FromDate>
            <!--Optional:-->
            <star:MandateId>'.$Mandate_id.'</star:MandateId>
            <!--Optional:-->
            <star:MemberCode>'.$memberid.'</star:MemberCode>
            <!--Optional:-->
            <star:ToDate>'.$end_date.'</star:ToDate>
         </ns:Param>
      </ns:MandateDetails>
   </soap:Body>
</soap:Envelope>';
$response = soapCall($soap_url, $soap_method, $soap_body_1);
$response_data=$response;
$response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
$xml = new  SimpleXMLElement($response);
/*$array_data=(array)$xml->sBody->MandateDetailsResponse->MandateDetailsResult->bMandateDetails;*/
/* $mandata=($array_data['bMandateDetails']);*/
bse_logs($pan,$memberid,$soap_body_1,$response_data,"Mandate Auth API");
  return $xml;
    }


public function getamcname($amc_id)
{
$sql="select images_name from amc_list where amc_id=$amc_id";
return $this->db->query($sql)->row();


}
public function getSchemePerformanceByISINdatabase_get($isin_id){
    $output_array=array();
$sql="select output from bse_sip_isin_history where bse_id=$isin_id";
$server_output= $this->db->query($sql)->row();
        $json = utf8_encode($server_output->output);
        $server_json_data = json_decode($json);
        $output_array=(array)$server_json_data;
        $nav_date_format=date('d/m/Y',strtotime($output_array['nav_date']));
        $output_array['nav_date']=$nav_date_format;
        return $output_array;

}

public function getSchemePerformanceByISINSchemedatabase_get($isin_id){
    $output_array=array();
	$sql="select output from bse_isin_history where bse_id=$isin_id";
	$server_output= $this->db->query($sql)->row();
        $json = utf8_encode($server_output->output);
        $server_json_data = json_decode($json);
        $output_array=(array)$server_json_data;
        $nav_date_format=date('d/m/Y',strtotime($output_array['nav_date']));
        $output_array['nav_date']=$nav_date_format;
        return $output_array;

}


public function getSchemePerformanceByISIN_get($isin,$i=0){
/*key=77b06a01-3bb1-4fa2-b31f-09809570abed&isin=INF209K01EN2*/
       // echo $isin; exit;
        
        $http_status = REST_Controller::HTTP_OK;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"https://www.advisorkhoj.com/api/mutual-funds-research/getSchemePerformanceByISIN?key=77b06a01-3bb1-4fa2-b31f-09809570abed&isin=$isin");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
                    "isin=$isin");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        $json = utf8_encode($server_output);
        $server_json_data = json_decode($json);
        $output_array=(array)$server_json_data;
        $nav_date_format=date('d/m/Y',strtotime($output_array['nav_date']));
        $output_array['nav_date']=$nav_date_format;

      if(@$output_array['status'] !=200 && $i<3){
        $i++;
        $server_output=$this->getSchemePerformanceByISIN_get($isin,$i);
       }
        return $output_array;
        /*$array_database->test = 'test...';*/
        curl_close ($ch);
    }


    function getImageName($amc_id){
        $sql = "select images_name from amc_list where amc_id=".$amc_id;
        return $this->db->query($sql)->row()->images_name;
    }

     function insert_register_details($array)
    {
        $query = $this->db->insert('redemption_transactions_data',$array);
        $last_id = $this->db->insert_id();
       // echo $this->db->last_query();
        //return $last_id;

    }

    function insert_payment_details($array)
    {
        $query = $this->db->insert('purchase_transaction_details',$array);
        $last_id = $this->db->insert_id();
        //echo $this->db->last_query();
        //return $last_id;
    }

    function get_transaction_no($trxn_no){
        $sql = "select trxnno from  purchase_transaction_details where trxnno=".$trxn_no;
        return $this->db->query($sql)->row();
    }
    

    function getIsipStatus($Pan){
        $sql = "select 
        otm_flag,
        otm,
        otm_approved,
        otm_created_date,

        otm_flag1,
        otm1,
        otm_approved1,
        otm_created_date1,

        otm_flag2,
        otm2,
        otm_approved2,
        otm_created_date2,

        otm_flag3,
        otm3,
        otm_approved3,
        otm_created_date3,

        xsip_otm_flag,
        xsip_otm,
        xsip_otm_approved,
        xsip_otm_created_date

        from users where pan = '$Pan'";
        return $this->db->query($sql)->row();
    }



   function business_partner_level($user_id)
    {
        $sql="
    select  id, 
        parent_id,user_id         
from    (select * from user_type_link
         order by parent_id, id) products_sorted,
        (select @pv := '$user_id') initialisation
where   find_in_set(parent_id, @pv)
and     length(@pv := concat(@pv, ',', user_id))";
//print_r($sql);die();

    $result=$this->db->query($sql)->result_array();
    return $result;



    }

    function get_amc_name($amc_id){
        $sql="SELECT * FROM `amc_list` WHERE `amc_id` = '$amc_id'";
        return $this->db->query($sql)->row();
    }


}

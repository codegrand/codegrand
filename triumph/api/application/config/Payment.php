<?php

defined('BASEPATH') OR exit('No direct script access allowed');

Class Payment extends Front_Controller {

    public function __construct() {
        parent::__construct();
		
        $this->load->model('Motor_Model');
        $this->load->model('Policy_Model');
        $this->load->model('Proposal_Model');
        $this->load->model('Common_Model');
        }

    //$this->load->hometemplate('proposal/proposal-bike', $this->data);


    function payment_response() {

        $view = "payment/payment_success";
        $this->load->hometemplate($view, $this->data);
    }

    function updatePaymentInfo() {

        $vehiclesession = vehicleGenrateVariableExtract();
         extract($vehiclesession); 
      
        $data['status']     = array("status" => false);
        $payment_response   = json_encode($_REQUEST);        
        $return_url         = $_REQUEST['return_url'];
        $query  = "SELECT online_response,payment_api From ic_integration_content Where ic_id=" . $mpn_ic_id . "";
        $string = $this->db->query($query)->result();
        $status = false;

        // 1)  Online Response
        eval($string[0]->online_response);      
        
        //2)) True  & false Response
        if ($status == true) {
            $response           = json_encode($_REQUEST);
            $message            = "Thank you for the payment. Your policy successfully created.";
            $proposal_status_id = 6; //approved
            $data['status']     = true;
        } else {
            $message            = "Error in Payment. Please try again after some time.";
            $proposal_status_id = 3; //failesd
        }

        //3)) Status  True
        if ($proposal_status_id == 6) {
            
            switch($mpn_ic_id){
                case in_array($mpn_ic_id, range(13,23)):  
            //4)) Payment String  
                eval($string[0]->payment_api);      
            }

            //5)) Update Status
            $proposal_list_array = array(
                'proposal_status_id'          => $proposal_status_id,
                'pg_responded_data'           => $response,
                'payment_transaction_type_id' => 1,
                "64b_status"                  => 'pending',                     
                "paying_status"               => 1,
                'payment_date'                => date('Y-m-d H:m:i')
            );

            $proposal_list_data             = $this->Proposal_Model->getProposalDetailByQuoteLink($mpn_proposal_quote_forward_link);
            $check_policy_data_exist        =  $this->Common_Model->check_policy_data_exist($proposal_list_data);

            if($check_policy_data_exist['status']==true) {

                //6)) Insert Policy Table
                $policy_id                  = $this->Policy_Model->insertPolicyRewamp($proposal_list_data,$proposal_list_array);
                $data['issued_policy_id']   = $policy_id;
                $data['status']             = true;
            }
            else {
                $data['status']             = false;
                $data['message']            = $check_policy_data_exist['message'];   
            }
        }

            if (!empty($data['issued_policy_id'])) {
                $_SESSION['policy_detail']['issued_policy_id'] = $data['issued_policy_id'];
            } 
            echo json_encode($data);
    }

    function getpolicyAcko($quote_id){

        $this->load->helper("soap_helper");
        if ($this->config->item('is_live')) {
            $soap_url = $this->config->item('api_acko_policy_live');
            $partner_id = "l8Ax61JJcD4HbKnohInemg";
        } else {
            $soap_url = $this->config->item('api_acko_policy_test');
            $partner_id = "3dKmpHA5kAU_NfMsUxVi8A";
        }

        $requested_param =array(
            "partner_id" => $partner_id,
            "quote_id" => $quote_id
        );
        $curl_body = json_encode($requested_param);
        $acko_response = $this->getCurlRequestForAcko($curl_body,$soap_url);

        return $acko_response;
    }

    function getCurlRequestForAcko($api_body_content,$URL) {

        $ch = curl_init($URL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "$api_body_content");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
       
        $a = json_decode($output,true);
        curl_close($ch);
        return $a;
    }

     public function downloadWelcomepage($vehiclesession,$pdf_postfix,$folder_name)
    {
        $this->load->model('Proposal_Model');
        extract($vehiclesession);
        $pdf = $mpn_proposal_quote_forward_link."._Welcome.pdf";
        $proposal_list_data = $this->Proposal_Model->getProposalDetailByQuoteLink($mpn_proposal_quote_forward_link);
        $view_html="";
        $view_html.=$this->load->view("front/proposal/tcpdfpdf/welcome",$proposal_list_data, true);
        $extra_paramter['url']=FCPATH.$folder_name.$pdf;
        GenerateTCPDF($view_html, "Policy Pdf", "F", $pdf, $extra_paramter);
    }
    

    /*        $this->savePdf($response_data['schedulePathHC'], $vehiclesession,$pdf_postfix,'uploads/acko/');
*/

    function savegodigit($url, $vehiclesession,$pdf_postfix,$folder_name)
    {
        extract($vehiclesession);
        $pdf = $mpn_proposal_quote_forward_link.".pdf";
        $path = $folder_name.$pdf;
        file_put_contents($path,file_get_contents($url));

    }

    function savePdf($url, $vehiclesession,$pdf_postfix,$folder_name){

        $pdf = $mpn_proposal_quote_forward_link.".pdf";
        $path = $folder_name.$pdf;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        $data = curl_exec($ch);
        curl_close($ch);
        $result = file_put_contents($path, $data);
    }

}

?>
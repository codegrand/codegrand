<?php


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');


defined('BASEPATH') OR exit('No direct script access allowed');


//require __DIR__ . '/vendor/autoload.php';
require APPPATH . '../vendor/autoload.php';
use \Firebase\JWT\JWT;
require APPPATH . 'libraries/REST_Controller.php';


Class Admin extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Admin_Model');
		$this->load->model('Api_Model');
         $this->load->helper("common_helper");
    }

    function api_response($data, $http_status = REST_Controller::HTTP_OK){
        //header('Access-Control-Allow-Origin: *');
        $this->response($data, $http_status);
    }


     function permissions_get()
     {


      $status = FALSE;
      $message = "Menu not found";
      $result = "";
      $isValidToken = $this->Admin_Model->isValidToken();
      //if($isValidToken){
        $sql="select * from resources  where type=2  ORDER BY `sorting_order`";
            $result = $this->db->query($sql)->result();

            $childs = array();
            foreach($result as $item)
                $childs[$item->parent_id][] = $item;
              
            foreach($result as $item) if (isset($childs[$item->resource_id]))
                $item->childs = $childs[$item->resource_id];


        $items = array();
        $i=0;
        if(!empty($childs)){
          foreach($childs[0] as $row){
            $items[$i]['text'] = $row->title;
            $items[$i]['value'] = $row->resource_id;
            if($row->childs){
              $j=0;
              foreach($row->childs as $child){
                $items[$i]['children'][$j]['text'] = $child->title;
                $items[$i]['children'][$j]['value'] = $child->resource_id;;
                $j++;
              }
              
            }
            $i++;            
          }
          
        }

        //$items = json_encode($items);
        //echo "<pre>"; print_r($items); exit;
        $message = "Menu data not found.";

        if(!empty($result)){
          $message = "side menu data get succesfully.";
          $status  = TRUE;
        }
        

      // }else {
      //   $message = "Invalid token. Please logout and login again";
      
      // }
      
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $items,
        
                      
      ];
      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);


     }





     function Submit_permissions_post()
    {

      $user_id = $this->input->post('user_id');
      $select_checkbox = $this->input->post('select_checkbox');


      $this->db->select('id,user_type_id');
      $user_data_array=$this->Admin_Model->getRowDataFromTableWithOject('users',array("id"=>$user_id));
      $user_id=$user_data_array->id;
      $user_type_id=$user_data_array->user_type_id;


      $query = $this->db->query("select u.id,up.*  from users u inner join user_type_previleges_resource up on u.id=up.uid where u.id='$user_id'");
      $result_checker= $query->row();


      $sql_array="select resource_id,parent_id  from resources where resource_id in ($select_checkbox) and parent_id=0";
      $query = $this->db->query($sql_array);
      $result_parent= $query->result_array();



      $sql_array1="select resource_id ,parent_id  from resources where resource_id in ($select_checkbox) and parent_id!=0";
      $query1 = $this->db->query($sql_array1);
      $result_child= $query1->result_array();




        $parent_ids = array();
            foreach ($result_parent as $id) {
                array_push($parent_ids, $id['resource_id']);
            }
        $child_ids = array();

        $child_ids_parnet=array();
            foreach ($result_child as $id) {
                array_push($child_ids, $id['resource_id']);
                array_push($parent_ids, $id['parent_id']);

               // array_push($child_ids_parnet, $id['parent_id']);

            }
         
          $parent_ids =  array_unique($parent_ids);


           $child_ids =  array_unique($child_ids);

          $main_array=array(
            "parent"=>array_values($parent_ids),
            "children"=>array_values($child_ids),
          );

          $insert_data=json_encode($main_array);


            $array_result=array
            (
              "uid"=>$user_id,
              "resource"=>$insert_data,
              "privileges"=>'{"privileges":"create,read,update,delete,all,assign"}',
              "user_type"=>$user_type_id
            );
        if(empty($result_checker))
          {
          $this->Admin_Model->insertIntoTable("user_type_previleges_resource", $array_result);
          }
          else
          {
        $this->Admin_Model->updateTable('user_type_previleges_resource', $array_result, array("uid"=>$user_id));

        }


          $output_data = [
          'status' => TRUE,
          'message' => 'Data Update',
        ];
      $this->api_response($output_data);
      
      
    }

    function getUserTypes_get(){

      $status = FALSE;
      $message = "User type not found";
      $result = "";
      $isValidToken = $this->Admin_Model->isValidToken();

      //if($isValidToken){
        $this->db->select('id,title');
        $result = $this->Admin_Model->getDataFromTableWithOject('business_partner_type','');
        $message = "User type not found.";
        if(!empty($result)){
          $message = "User type get succesfully.";
          $status  = TRUE;
        }
      
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result
      ];
      
      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);

}

    function Login_post(){
      $pancard = $this->input->post('pancard');
      $password = $this->input->post('password');
      
      //$jwt_key = $this->config->item('jwt_key');
      $where = array(
        'email' => $pancard, //email id 
        'user_password' => md5($password) ,
      );


      $result1 = $this->Admin_Model->getRowDataFromTableWithOject('users',$where);


     // echo "";
      $output_data = [
          'status' => FALSE,
          'message' => 'Wrong login details',
          'token' => '',
          'result' => ''
              
      ];

    $business_partner_id=0;
      if(!empty($result1)){
            $user_id=$result1->id;
            $user_type_id=$result1->user_type_id;

            switch ($user_type_id) {
            case '1':
            $side_menu_bar = $this->Admin_Model->getRowDataFromTableWithOject('user_type_previleges_resource', array('uid'=>$user_id));
            $json_encode_menu_bar=json_decode($side_menu_bar->resource); 
            $sql="select bt.id as business_partner_id, u.id,u.user_type_id,ut.*,bt.resource
            from users u  inner join user_type_link ut 
            on u.id=ut.user_id inner join business_partner_type bt on ut.business_type_id=bt.id 
            where u.user_type_id =1 and u.id=$user_id";
            $result=$this->db->query($sql)->row();
            $business_partner_id=$result->business_partner_id;
            if(empty($json_encode_menu_bar)){
            $json_encode_menu_bar=json_decode($result->resource); 
            }
            break;

            case '3':
            $side_menu_bar = $this->Admin_Model->getRowDataFromTableWithOject('user_type_previleges_resource', array('uid'=>$user_id));
            $json_encode_menu_bar=json_decode($side_menu_bar->resource); 
         
            $sql="select bt.id as business_partner_id, u.id,u.user_type_id,ut.*,bt.resource
            from users u  inner join user_type_link ut 
            on u.id=ut.user_id inner join business_partner_type bt on ut.business_type_id=bt.id 
            where u.user_type_id =3 and u.id=$user_id";

            $result=$this->db->query($sql)->row();



            $business_partner_id=$result->business_partner_id;
            if(empty($json_encode_menu_bar)){
            $json_encode_menu_bar=json_decode($result->resource); 
            }

            break;
            case '5':
            $side_menu_bar = $this->Admin_Model->getRowDataFromTableWithOject('user_type_previleges_resource', array('uid'=>$user_id));
            $json_encode_menu_bar=json_decode($side_menu_bar->resource); 
            break;
            }

            $ids = array();
            foreach ($json_encode_menu_bar->parent as $id) {
                array_push($ids, $id);
            }
            foreach ($json_encode_menu_bar->children as $id1) {
                array_push($ids, $id1);
            }
            
            $side_menu_bar_array=implode(",", $ids);

            $sql="select * from resources  where resource_id  IN ($side_menu_bar_array)  ORDER BY `sorting_order`";
            $result = $this->db->query($sql)->result();

            $childs = array();
            foreach($result as $item)
                $childs[$item->parent_id][] = $item;
              
            foreach($result as $item) if (isset($childs[$item->resource_id]))
                $item->childs = $childs[$item->resource_id];


        $jwt_generate = $this->Admin_Model->generateToken($result->id,$result->name,$result->pan); 
        $output_data = [
          'status' => TRUE,
          'message' => 'Login Succesfully',
          'token' => $jwt_generate,
          'result' => $result1,
          'business_partner_id'=>$business_partner_id,
          'side_menu_bar'=>$childs[0]
              
        ];

      }

      $this->api_response($output_data);
    }

    function dashboard_get(){
     
      $output_data = [
          'status' => FALSE,
          'message' => 'Record not found',
                       
      ];
      $isValidToken = $this->Admin_Model->isValidToken();
      if(!empty($isValidToken)){
        $output_data = [
          'status' => TRUE,
          'message' => 'Dashboard data get succesfully',
          'result' => ''
                       
        ];
      }
      
      $this->api_response($output_data);
    }

     function BankMadateDetails_get(){
     $pan=$this->get('Pan');
      $output_data = [
          'status' => FALSE,
          'message' => 'Record not found',
                       
      ];
    $sql="select * from bank_mandate where pan_no='$pan'";
    $result=$this->db->query($sql)->row();      
        $output_data = [
          'status' => TRUE,
          'message' => 'succesfully',
          'result' =>$result
                       
        ];
      
      $this->api_response($output_data);
    }

   
    function SumbitApprovalBankMandate_post()
    {  
	
	  $this->load->helper("common_helper");
      $status = FALSE;
      $message = "profile not found";
      $result = "";
	  

      $pan_no=$this->input->post('Pan');
      $mandate_comment=$this->input->post('mandate_comment');
      $comment_type=$this->input->post('comment_type');
	$update_array=array(
	  "status"=>$comment_type,
	  "admin_comment"=>$mandate_comment,
	);
		
      $this->Admin_Model->updateTable("bank_mandate", $update_array, array("pan_no"=>$pan_no));
      $this->db->select('id,name,kyc,pan,mobile,email,is_active,bse_active');
      $result = $this->Admin_Model->getRowDataFromTableWithOject('users',array("pan"=>$pan_no));
	  
	  $fetch_customer_details = $this->Api_Model->fetch_customer_details($pan_no);
	  
	   $email_id = $fetch_customer_details['email'];
      $bank_mandate_array = $this->Admin_Model->getRowDataFromTableWithOject('bank_mandate',array("pan_no"=>$pan_no));
	 
     
      $to=$result->email;
      $from='heromf@herocorp.com';
       $name=$result->name;
	  
     
		 //
      switch ($comment_type) {
        case 'Rejected':
        $mail_template='<div style="background-color: rgb(245, 246, 251); margin: 0px; padding: 15px 10px; font-family: sans-serif !important">     
    <center>         
        <table border="0" bgcolor="#ffffff" cellpadding="0" cellspacing="0" style="display: block !important; max-width: 500px !important; margin: 0 auto !important; clear: both !important" align="center">             
            <tbody><tr>                 
                <td style="width: 600px; padding: 0">                     
                    <table style="display: block !important; margin: 0 auto !important; clear: both !important; background-color: rgb(255, 255, 255)" align="center"><tbody style="display: table; width: 100%">                             
                        <tr>                                 
                            <td style="text-align: center; width: 600px">                                     
                                <a href="http://103.87.174.12/heromutual/dev/" style="margin: 0 auto; display: inline-block" target="_blank">                                     
                                    <img id="" style="width: 170px; margin-top: 15px; margin-bottom: 10px; border: 0px; outline: none" alt="Hero Corp Logo" src="http://103.87.174.12/heromutual/dev/assets/images/logo.png"></a>                                 </td>                             
                        </tr>                             <tr>                                 
                        <td style="border-top: 1px solid rgb(232, 235, 240)">                                     
                            <table style="margin: 0px auto; clear: both !important" align="center">                                         
                                <tbody>
                                    <tr>                                             
                                        <td style="padding: 15px 15px 0px">                                                 
                                            <h3 style="margin: 0px; font-family: sans-serif; font-size: 16px; font-weight: normal; margin-bottom: 15px; line-height: 25px; color: rgb(92, 107, 126)">Dear '.$name.' , </h3>                                                              
                                             <p style="font-size: 14px; line-height: 1.4; margin-bottom: 20px; font-family: sans-serif; color: rgb(92, 107, 126)">We are sorry to inform you that your request for registration of Hero Corporate Service has been rejected due to the following reason</p>   
                                             <p style="font-size: 14px; line-height: 1.4; font-family: sans-serif; color: rgb(92, 107, 126); margin-bottom: 5px; margin-bottom: 5px"><b>'.$mandate_comment.'</b></p>   

                                        <p style="font-size: 14px; line-height: 1.4; margin-bottom: 20px; font-family: sans-serif; color: rgb(92, 107, 126)">Kindly print, sign and upload a clear picture of new form sent to you.
                                        </p> 

                                        <p style="font-size: 14px; line-height: 1.4; margin-bottom: 20px; font-family: sans-serif; color: rgb(92, 107, 126)">We know it is painful and we tried our best to make it seamless. However this is a one-time setup and mandatory if you want to do monthly (SIP) investments.


                                        </p> 

                                            
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
                                                <p style="font-size: 13px; line-height: 1.4; margin-bottom: 20px; font-family: sans-serif; color: rgb(81, 93, 109)">For any queries, please write to us at <a href="mailto:heromf@herocorp.com" style="color: rgb(83, 147, 236); text-decoration: none" target="_blank">heromf@herocorp.com</a>
                                                </p>                                                 
                                                <p style="font-size: 16px; line-height: 1.4; margin: 0px; font-weight: normal; font-family: sans-serif; color: rgb(92, 107, 126); padding-top: 10px">Regards,                                                     
                                                    <br><b style="font-size: 16px; line-height: 1.4; margin: 0px; font-weight: normal; font-family: sans-serif; color: rgb(92, 107, 126)">Team Hero Corporate Private Limited</b> </p>                                             </td>                                         </tr>                                     
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
        $subject="Bank Mandate Form: Rejection - Miscellaneous Reason";
        $title='Bank Mandate Form';
        $data=$bank_mandate_array->bank_mandate_file;
        break;
		
        case 'Approved':
        $mail_template='<div style="background-color: rgb(245, 246, 251); margin: 0px; padding: 15px 10px; font-family: sans-serif !important">     
    <center>         
        <table border="0" bgcolor="#ffffff" cellpadding="0" cellspacing="0" style="display: block !important; max-width: 500px !important; margin: 0 auto !important; clear: both !important" align="center">             
            <tbody><tr>                 
                <td style="width: 600px; padding: 0">                     
                    <table style="display: block !important; margin: 0 auto !important; clear: both !important; background-color: rgb(255, 255, 255)" align="center"><tbody style="display: table; width: 100%">                             
                        <tr>                                 
                            <td style="text-align: center; width: 600px">                                     
                                <a href="http://103.87.174.12/heromutual/dev/" style="margin: 0 auto; display: inline-block" target="_blank">                                     
                                    <img id="" style="width: 170px; margin-top: 15px; margin-bottom: 10px; border: 0px; outline: none" alt="Hero Corp Logo" src="http://103.87.174.12/heromutual/dev/assets/images/logo.png"></a>                                 </td>                             
                        </tr>                             <tr>                                 
                        <td style="border-top: 1px solid rgb(232, 235, 240)">                                     
                            <table style="margin: 0px auto; clear: both !important" align="center">                                         
                                <tbody>
                                    <tr>                                             
                                        <td style="padding: 15px 15px 0px">                                                 
                                            <h3 style="margin: 0px; font-family: sans-serif; font-size: 16px; font-weight: normal; margin-bottom: 15px; line-height: 25px; color: rgb(92, 107, 126)">Dear '.$name.' , </h3>                                                              
                                             <p style="font-size: 14px; line-height: 1.4; margin-bottom: 20px; font-family: sans-serif; color: rgb(92, 107, 126)">We would like to inform you that your mandate form uploaded is Approved.</p>   
                                             

                                            
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
                                                <p style="font-size: 13px; line-height: 1.4; margin-bottom: 20px; font-family: sans-serif; color: rgb(81, 93, 109)">For any queries, please write to us at <a href="mailto:heromf@herocorp.com" style="color: rgb(83, 147, 236); text-decoration: none" target="_blank">heromf@herocorp.com</a>
                                                </p>                                                 
                                                <p style="font-size: 16px; line-height: 1.4; margin: 0px; font-weight: normal; font-family: sans-serif; color: rgb(92, 107, 126); padding-top: 10px">Regards,                                                     
                                                    <br><b style="font-size: 16px; line-height: 1.4; margin: 0px; font-weight: normal; font-family: sans-serif; color: rgb(92, 107, 126)">Team Hero Corporate Private Limited</b> </p>                                             </td>                                         </tr>                                     
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
        $title='Bank Mandate Form';
        $subject="Bank Mandate Form: Approved";
        $data=$bank_mandate_array->bank_mandate_file;
        break;

      }
		
		
		
		SendMail($to,$from,$mail_template,$subject,$data,$title);
		
      
	  	
	  if($bank_mandate_array)
	  {
		$status=TRUE;
		$message="DATA UPDATE";
		$result=$bank_mandate_array;
	  }
       $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result
                     
      ];
       $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);




    }

    function getProfileData_post(){
      
     

      $status = FALSE;
      $message = "profile not found";
      $result = "";
      $isValidToken = $this->Admin_Model->isValidToken();


      if($isValidToken){
        $pancard = $this->input->post('pancard');
        $where = array(
          'pan' => $pancard        
        );
        $result = $this->Admin_Model->getRowDataFromTableWithOject('users',$where);
        $message = "Profile data not found.";
        if(!empty($result)){
          $message = "Profile data get succesfully.";
          $status  = TRUE;
        }
        

      }else {
        $message = "Invalid token. Please logout and login again";
      
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result
                     
      ];
       $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);
      //$this->api_response($output_data);
    }

    

    function uploadDatabaseFile_post(){
      $status =  True;
      $error = "";
      $file_handle = "";
      $lines = "";
      $message = "File Uploaded succesfully.";
      $result = $_FILES['formDatabaseFile'] ;
      $file_result_array = array();
      $VALUES = "";
      $final_array = array();
      if ($_FILES["formDatabaseFile"]["error"] > 0){
        $status =  FALSE;
        $error =  "Error: " . $_FILES["formDatabaseFile"]["error"] . "<br />";
      }elseif ($_FILES["formDatabaseFile"]["type"] !== "text/plain"){
        $status =  FALSE;
        $error = "File must be a .txt";
      }else{
        $open = fopen($_FILES["formDatabaseFile"]["tmp_name"], "r"); 
        $i=0;
        $file_result_array = "";
        while (!feof($open)) {
          $getTextLine = fgets($open);
          $explodeLine = explode("|",$getTextLine);
 
          list(
            $AMCCode,
            $AMCNAME,
            $SCHEMECODE,
            $SchemePlan,
            $SchemeName,
            $SIPTRANSACTIONMODE,
            $SIPFREQUENCY,
            $SIPDATES,
            $SIPMINIMUMGAP,
            $SIPMAXIMUMGAP,
            $SIPINSTALLMENTGAP,
            $SIPSTATUS,
            $SIPMINIMUMINSTALLMENTAMOUNT,
            $SIPMAXIMUMINSTALLMENTAMOUNT,
            $SIPMULTIPLIERAMOUNT,
            $SIPMINIMUMINSTALLMENTNUMBERS,
            $SIPMAXIMUMINSTALLMENTNUMBERS,
            $SCHEMEISIN,
            $SCHEMETYPE           
          ) = $explodeLine;


           $qry = "INSERT INTO `bse_sip_schemes` 
           (  
                `amc_code`, 
                `amc_name`, 
                `scheme_code`, 
                `scheme_name`, 
                `sip_transaction_mode`, 
                `sip_frequency`, 
                `sip_dates`, 
                `sip_minimum_gap`, 
                `sip_maximum_gap`, 
                `sip_installment_gap`, 
                `sip_status`, 
                `sip_minimum_installment_amount`, 
                `sip_maximum_installment_amount`, 
                `sip_multiplier_amount`, 
                `sip_minimum_installment_numbers`, 
                `sip_maximum_installment_numbers`, 
                `scheme_isin`, 
                `scheme_type`, 
                `is_robo`, 
                `amc_id`
           )

              VALUES (
                '$AMCCode',
                '$AMCNAME',
                '$SCHEMECODE',
                '$SchemePlan',
                '$SchemeName',
                '$SIPTRANSACTIONMODE',
                '$SIPFREQUENCY',
                '$SIPDATES',
                '$SIPMINIMUMGAP',
                '$SIPMAXIMUMGAP',
                '$SIPINSTALLMENTGAP',
                '$SIPSTATUS',
                '$SIPMINIMUMINSTALLMENTAMOUNT',
                '$SIPMAXIMUMINSTALLMENTAMOUNT',
                '$SIPMULTIPLIERAMOUNT',
                '$SIPMINIMUMINSTALLMENTNUMBERS',
                '$SIPMAXIMUMINSTALLMENTNUMBERS',
                '$SCHEMEISIN',
                '$SCHEMETYPE'      
              
              );
              ";
        
        
            
            
        }
      }

      //$result[] = file_get_contents($this->input->post('fileUpload'));
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $file_result_array,
        'qry' => $qry
        

                     
      ];
       $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);
    }

    function uploadDatabaseFileTestDemo_post(){
      $status =  True;
      $error = "";
      $file_handle = "";
      $lines = "";
      $message = "File Uploaded succesfully.";
      $result = $_FILES['formDatabaseFile'] ;
      $file_result_array = array();
      $VALUES = "";
      $final_array = array();
      if ($_FILES["formDatabaseFile"]["error"] > 0){
        $status =  FALSE;
        $error =  "Error: " . $_FILES["formDatabaseFile"]["error"] . "<br />";
      }elseif ($_FILES["formDatabaseFile"]["type"] !== "text/plain"){
        $status =  FALSE;
        $error = "File must be a .txt";
      }else{
        $open = fopen($_FILES["formDatabaseFile"]["tmp_name"], "r"); 
        $i=0;
        $file_result_array = "";
        while (!feof($open)) {
          $getTextLine = fgets($open);
          $explodeLine = explode("|",$getTextLine);
 
          list(
            $AMCCode,
            $AMCNAME,
            $SCHEMECODE,
            $SchemePlan,
            $SchemeName,
            $SIPTRANSACTIONMODE,
            $SIPFREQUENCY,
            $SIPDATES,
            $SIPMINIMUMGAP,
            $SIPMAXIMUMGAP,
            $SIPINSTALLMENTGAP,
            $SIPSTATUS,
            $SIPMINIMUMINSTALLMENTAMOUNT,
            $SIPMAXIMUMINSTALLMENTAMOUNT,
            $SIPMULTIPLIERAMOUNT,
            $SIPMINIMUMINSTALLMENTNUMBERS,
            $SIPMAXIMUMINSTALLMENTNUMBERS,
            $SCHEMEISIN,
            $SCHEMETYPE           
          ) = $explodeLine;


           $qry = "INSERT INTO `bse_sip_schemes_demo` 
           (  
                `amc_code`, 
                `amc_name`, 
                `scheme_code`, 
                `scheme_name`, 
                `sip_transaction_mode`, 
                `sip_frequency`, 
                `sip_dates`, 
                `sip_minimum_gap`, 
                `sip_maximum_gap`, 
                `sip_installment_gap`, 
                `sip_status`, 
                `sip_minimum_installment_amount`, 
                `sip_maximum_installment_amount`, 
                `sip_multiplier_amount`, 
                `sip_minimum_installment_numbers`, 
                `sip_maximum_installment_numbers`, 
                `scheme_isin`, 
                `scheme_type`, 
                `is_robo`, 
                `amc_id`
           )

              VALUES (
                '$AMCCode',
                '$AMCNAME',
                '$SCHEMECODE',
                '$SchemePlan',
                '$SchemeName',
                '$SIPTRANSACTIONMODE',
                '$SIPFREQUENCY',
                '$SIPDATES',
                '$SIPMINIMUMGAP',
                '$SIPMAXIMUMGAP',
                '$SIPINSTALLMENTGAP',
                '$SIPSTATUS',
                '$SIPMINIMUMINSTALLMENTAMOUNT',
                '$SIPMAXIMUMINSTALLMENTAMOUNT',
                '$SIPMULTIPLIERAMOUNT',
                '$SIPMINIMUMINSTALLMENTNUMBERS',
                '$SIPMAXIMUMINSTALLMENTNUMBERS',
                '$SCHEMEISIN',
                '$SCHEMETYPE'      
              
              );
              ";
        
        
            
            
        }
      }

      //$result[] = file_get_contents($this->input->post('fileUpload'));
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $file_result_array,
        'qry' => $qry
        

                     
      ];
       $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);
    }



// state module start

    function getStates_get(){

      $status = FALSE;
      $message = "State not found";
      $result = "";
      $isValidToken = $this->Admin_Model->isValidToken();


      //if($isValidToken){
       
        $where = array(
          'is_deleted' => 0
        );
        $this->db->order_by('name','Asc');
        $result = $this->Admin_Model->getDataFromTableWithOject('states',$where);
       
        $message = "State data not found.";
        if(!empty($result)){
          $message = "State data get succesfully.";
          $status  = TRUE;
        }
        

      // }else {
      //   $message = "Invalid token. Please logout and login again";
      
      // }
      
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result,
        'countries' => $countries
                      
      ];
      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);

    }


      function submitContactForm_post(){
      
      $status = TRUE;
      $message = "Data get succesfully";
      echo $message;die();
      /*$isValidToken = $this->Admin_Model->isValidToken();
      $id = $this->input->post('id');
      $table_name = 'states';
      $data =  array(
        'code' => $this->input->post('code'),
        'name' => $this->input->post('name')
      );
      if($id == 0){
        $this->Admin_Model->insertIntoTable($table_name, $data);
      }else{
        $where = array(
          'id' => $this->input->post('id')
        );
        $this->Admin_Model->updateTable($table_name, $data, $where);
      }
            
      if(!empty($isValidToken)){
        $status = TRUE;
        $message = "Data get succesfully";
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);*/

    }


    function getStateDataById_post(){
      
      $status = TRUE;
      $message = "Data get succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();
     
     // echo "<pre>"; print_r($ids); exit;

      $id = $this->input->post('id');
      $table = 'states';

      $where = array(
        'id' => $id       
      );

      $result = $this->Admin_Model->getRowDataFromTableWithOject($table,$where);
      if(!empty($isValidToken)){
        $status = TRUE;
        $message = "Data get succesfully";
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);

    }
    function updateStateTable_post(){
      
      $status = TRUE;
      $message = "Data get succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();
      $id = $this->input->post('id');
      $table_name = 'states';
      $data =  array(
        'code' => $this->input->post('code'),
        'name' => $this->input->post('name')
      );
      if($id == 0){
        $this->Admin_Model->insertIntoTable($table_name, $data);
      }else{
        $where = array(
          'id' => $this->input->post('id')
        );
        $this->Admin_Model->updateTable($table_name, $data, $where);
      }
            
      if(!empty($isValidToken)){
        $status = TRUE;
        $message = "Data get succesfully";
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);

    }
    function removeState_post(){
      $status = TRUE;
      $message = "Data get succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();

      $id = $this->input->post('id');
      $table_name = 'states';
      $where = array(
        'id' => $this->input->post('id')
      );
      $data = array(
        'is_deleted' => 1
      );
      $result = $this->Admin_Model->updateTable($table_name, $data, $where);

      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }

    function activeInactiveState_post(){
      $status = TRUE;
     
      $isValidToken = $this->Admin_Model->isValidToken();

      $activeInactive = $this->input->post('activeInactive');
      $message = "State Inactive succesfully";
      if($activeInactive == 1){
        $message = "State active succesfully";
      }

      $table_name = 'states';
      $where = array(
        'id' =>  $this->input->post('id')
      );
      $data = array(
        'is_active' => $this->input->post('activeInactive')
      );
      $result = $this->Admin_Model->updateTable($table_name, $data, $where);

      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }
// end module end

// annual salary module start

    function getannualSalaries_get(){

      $status = FALSE;
      $message = "Annual Salary not found";
      $result = "";
      $isValidToken = $this->Admin_Model->isValidToken();


      //if($isValidToken){
       
        $where = array(
          'is_deleted' => 0
        );
        $this->db->order_by('code','Asc');
        $result = $this->Admin_Model->getDataFromTableWithOject('income',$where);
       
        $message = "Annual Salary data not found.";
        if(!empty($result)){
          $message = "Annual Salary data get succesfully.";
          $status  = TRUE;
        }
        

      // }else {
      //   $message = "Invalid token. Please logout and login again";
      
      // }
      
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result,
        'countries' => $countries
                      
      ];
      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);

    }

    function getAnnualSalaryDataById_post(){
      
      $status = TRUE;
      $message = "Annual Salary get succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();
     
     // echo "<pre>"; print_r($ids); exit;

      $id = $this->input->post('id');
      $table = 'income';

      $where = array(
        'id' => $id       
      );

      $result = $this->Admin_Model->getRowDataFromTableWithOject($table,$where);
      if(!empty($isValidToken)){
        $status = TRUE;
        $message = "Annual Salary get succesfully";
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);

    }

    function updateAnnualSalaryTable_post(){
      
      $status = TRUE;
      $message = "Annual Salary succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();
      $id = $this->input->post('id');
      $table_name = 'income';
      $data =  array(
        'code' => $this->input->post('code'),
        'income' => $this->input->post('income')
      );
      if($id == 0){
        $this->Admin_Model->insertIntoTable($table_name, $data);
      }else{
        $where = array(
          'id' => $this->input->post('id')
        );
        $this->Admin_Model->updateTable($table_name, $data, $where);
      }
            
      if(!empty($isValidToken)){
        $status = TRUE;
        $message = "Annual Salary get succesfully";
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);

    }
    function removeAnnualSalary_post(){
      $status = TRUE;
      $message = "Remove Annual Salary succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();

      $id = $this->input->post('id');
      $table_name = 'income';
      $where = array(
        'id' => $this->input->post('id')
      );
      $data = array(
        'is_deleted' => 1
      );
      $result = $this->Admin_Model->updateTable($table_name, $data, $where);

      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }

    function activeInactiveAnnualSalary_post(){
      $status = TRUE;
     
      $isValidToken = $this->Admin_Model->isValidToken();

      $activeInactive = $this->input->post('activeInactive');
      $message = "Annual Salary Inactive succesfully";
      if($activeInactive == 1){
        $message = "Annual Salary active succesfully";
      }

      $table_name = 'income';
      $where = array(
        'id' =>  $this->input->post('id')
      );
      $data = array(
        'is_active' => $this->input->post('activeInactive')
      );
      $result = $this->Admin_Model->updateTable($table_name, $data, $where);

      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }
// end Annual Salary module end

// clientHoldings module start

    function getclientHoldings_get(){

      $status = FALSE;
      $message = "Client Holdings not found";
      $result = "";
      $isValidToken = $this->Admin_Model->isValidToken();


      //if($isValidToken){
       
        $where = array(
          'is_deleted' => 0
        );
        $this->db->order_by('detail','Asc');
        $result = $this->Admin_Model->getDataFromTableWithOject('client_holdings',$where);
       
        $message = "Client Holdings data not found.";
        if(!empty($result)){
          $message = "Client Holdings data get succesfully.";
          $status  = TRUE;
        }
        

      // }else {
      //   $message = "Invalid token. Please logout and login again";
      
      // }
      
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result,
        'countries' => $countries
                      
      ];
      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);

    }
    
    function getClientHoldingDataById_post(){
      
      $status = TRUE;
      $message = "Client Holdings get succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();
     
     // echo "<pre>"; print_r($ids); exit;

      $id = $this->input->post('id');
      $table = 'client_holdings';

      $where = array(
        'id' => $id       
      );

      $result = $this->Admin_Model->getRowDataFromTableWithOject($table,$where);
      if(!empty($isValidToken)){
        $status = TRUE;
        $message = "Client Holdings get succesfully";
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);

    }

    function updateClientHoldingTable_post(){
      
      $status = TRUE;
      $message = "Client Holdings succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();
      $id = $this->input->post('id');
      $table_name = 'client_holdings';
      $data =  array(
        'code' => $this->input->post('code'),
        'detail' => $this->input->post('detail')
      );
      if($id == 0){
        $this->Admin_Model->insertIntoTable($table_name, $data);
      }else{
        $where = array(
          'id' => $this->input->post('id')
        );
        $this->Admin_Model->updateTable($table_name, $data, $where);
      }
            
      if(!empty($isValidToken)){
        $status = TRUE;
        $message = "Client Holdings get succesfully";
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);

    }
    function removeClientHolding_post(){
      $status = TRUE;
      $message = "Client Holdings Salary succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();

      $id = $this->input->post('id');
      $table_name = 'client_holdings';
      $where = array(
        'id' => $this->input->post('id')
      );
      $data = array(
        'is_deleted' => 1
      );
      $result = $this->Admin_Model->updateTable($table_name, $data, $where);

      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }

    function activeInactiveClientHolding_post(){
      $status = TRUE;
     
      $isValidToken = $this->Admin_Model->isValidToken();

      $activeInactive = $this->input->post('activeInactive');
      $message = "Client Holding Inactive succesfully";
      if($activeInactive == 1){
        $message = "Client Holding active succesfully";
      }

      $table_name = 'client_holdings';
      $where = array(
        'id' =>  $this->input->post('id')
      );
      $data = array(
        'is_active' => $this->input->post('activeInactive')
      );
      $result = $this->Admin_Model->updateTable($table_name, $data, $where);

      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }
// end Client Holding module end

// occupation Employment Status module start

    function getEmploymentStatus_get(){

      $status = FALSE;
      $message = "Occupation Status not found";
      $result = "";
      $isValidToken = $this->Admin_Model->isValidToken();


      //if($isValidToken){
       
        $where = array(
          'is_deleted' => 0
        );
        $this->db->order_by('code','Asc');
        $result = $this->Admin_Model->getDataFromTableWithOject('occupation',$where);
       
        $message = "Occupation Status data not found.";
        if(!empty($result)){
          $message = "Occupation Status data get succesfully.";
          $status  = TRUE;
        }
        

      // }else {
      //   $message = "Invalid token. Please logout and login again";
      
      // }
      
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result,
        'countries' => $countries
                      
      ];
      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);

    }
    
    function getEmploymentStatusDataById_post(){
      
      $status = TRUE;
      $message = "Occupation get succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();
     
     // echo "<pre>"; print_r($ids); exit;

      $id = $this->input->post('id');
      $table = 'occupation';

      $where = array(
        'id' => $id       
      );

      $result = $this->Admin_Model->getRowDataFromTableWithOject($table,$where);
      if(!empty($isValidToken)){
        $status = TRUE;
        $message = "Occupation get succesfully";
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where,
        'sql' => $this->db->last_query()
                     
      ];
      $this->api_response($output_data);

    }

    function updateEmploymentStatusTable_post(){
      
      $status = TRUE;
      $message = "Occupation update succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();
      $id = $this->input->post('id');
      $table_name = 'occupation';
      $data =  array(
        'code' => $this->input->post('code'),
        'name' => $this->input->post('occupationname')
      );
      if($id == 0){
        $this->Admin_Model->insertIntoTable($table_name, $data);
      }else{
        $where = array(
          'id' => $this->input->post('id')
        );
        $this->Admin_Model->updateTable($table_name, $data, $where);
      }
            
      if(!empty($isValidToken)){
        $status = TRUE;
        $message = "Occupation get succesfully";
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);

    }
    function removeEmploymentStatus_post(){
      $status = TRUE;
      $message = "Occupation remove succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();

      $id = $this->input->post('id');
      $table_name = 'occupation';
      $where = array(
        'id' => $this->input->post('id')
      );
      $data = array(
        'is_deleted' => 1
      );
      $result = $this->Admin_Model->updateTable($table_name, $data, $where);

      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }

    function activeInactiveEmploymentStatus_post(){
      $status = TRUE;
     
      $isValidToken = $this->Admin_Model->isValidToken();

      $activeInactive = $this->input->post('activeInactive');
      $message = "occupation Inactive succesfully";
      if($activeInactive == 1){
        $message = "occupation active succesfully";
      }

      $table_name = 'occupation';
      $where = array(
        'id' =>  $this->input->post('id')
      );
      $data = array(
        'is_active' => $this->input->post('activeInactive')
      );
      $result = $this->Admin_Model->updateTable($table_name, $data, $where);

      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }
// end Employment Status module end





// Source of Income module start

    function getSourceOfIncome_get(){

      $status = FALSE;
      $message = "Source of Income not found";
      $result = "";
      $isValidToken = $this->Admin_Model->isValidToken();


      //if($isValidToken){
       
        $where = array(
          'is_deleted' => 0
        );
        $this->db->order_by('source','Asc');
        $result = $this->Admin_Model->getDataFromTableWithOject('income_source',$where);
       
        $message = "Source of Income data not found.";
        if(!empty($result)){
          $message = "Source of Income data get succesfully.";
          $status  = TRUE;
        }
        

      // }else {
      //   $message = "Invalid token. Please logout and login again";
      
      // }
      
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result,
        'countries' => $countries
                      
      ];
      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);

    }
    
    function getSourceOfIncomeDataById_post(){
      
      $status = TRUE;
      $message = "Source of Income get succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();
     
     // echo "<pre>"; print_r($ids); exit;

      $id = $this->input->post('id');
      $table = 'income_source';

      $where = array(
        'id' => $id       
      );

      $result = $this->Admin_Model->getRowDataFromTableWithOject($table,$where);
      if(!empty($isValidToken)){
        $status = TRUE;
        $message = "Source of Income get succesfully";
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);

    }

    function updateSourceOfIncomeTable_post(){
      
      $status = TRUE;
      $message = "Source of Income succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();
      $id = $this->input->post('id');
      $table_name = 'income_source';
      $data =  array(
        'code' => $this->input->post('code'),
        'source' => $this->input->post('incomesource')
      );
      if($id == 0){
        $this->Admin_Model->insertIntoTable($table_name, $data);
      }else{
        $where = array(
          'id' => $this->input->post('id')
        );
        $this->Admin_Model->updateTable($table_name, $data, $where);
      }
            
      if(!empty($isValidToken)){
        $status = TRUE;
        $message = "Source of Income get succesfully";
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }

    function removeSourceOfIncome_post(){
      $status = TRUE;
      $message = "Source of Income  succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();

      $id = $this->input->post('id');
      $table_name = 'income_source';
      $where = array(
        'id' => $this->input->post('id')
      );
      $data = array(
        'is_deleted' => 1
      );
      $result = $this->Admin_Model->updateTable($table_name, $data, $where);

      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }

    function activeInactiveSourceOfIncome_post(){
      $status = TRUE;
     
      $isValidToken = $this->Admin_Model->isValidToken();

      $activeInactive = $this->input->post('activeInactive');
      $message = "Source of Income Inactive succesfully";
      if($activeInactive == 1){
        $message = "Source of Income active succesfully";
      }

      $table_name = 'income_source';
      $where = array(
        'id' =>  $this->input->post('id')
      );
      $data = array(
        'is_active' => $this->input->post('activeInactive')
      );
      $result = $this->Admin_Model->updateTable($table_name, $data, $where);

      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }
// end Source of Income module end

// Bank Account Types module start

    function getBankAccountTypes_get(){

      $status = FALSE;
      $message = "Bank Account Types not found";
      $result = "";
      $isValidToken = $this->Admin_Model->isValidToken();


      //if($isValidToken){
       
        $where = array(
          'is_deleted' => 0
        );
        $this->db->order_by('type','Asc');
        $result = $this->Admin_Model->getDataFromTableWithOject('account_types',$where);
       
        $message = "Bank Account Type data not found.";
        if(!empty($result)){
          $message = "Bank Account Type data get succesfully.";
          $status  = TRUE;
        }
        

      // }else {
      //   $message = "Invalid token. Please logout and login again";
      
      // }
      
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result,
        'countries' => $countries
                      
      ];
      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);

    }
    
    function getBankAccountTypeDataById_post(){
      
      $status = TRUE;
      $message = "Bank Account Type get succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();
     
     // echo "<pre>"; print_r($ids); exit;

      $id = $this->input->post('id');
      $table = 'account_types';

      $where = array(
        'id' => $id       
      );

      $result = $this->Admin_Model->getRowDataFromTableWithOject($table,$where);
      if(!empty($isValidToken)){
        $status = TRUE;
        $message = "Bank Account Type get succesfully";
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);

    }

    function updateBankAccountTypeTable_post(){
      
      $status = TRUE;
      $message = "Bank Account Type succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();
      $id = $this->input->post('id');
      $table_name = 'account_types';
      $data =  array(
        'code' => $this->input->post('code'),
        'type' => $this->input->post('type')
      );
      if($id == 0){
        $this->Admin_Model->insertIntoTable($table_name, $data);
      }else{
        $where = array(
          'id' => $this->input->post('id')
        );
        $this->Admin_Model->updateTable($table_name, $data, $where);
      }
            
      if(!empty($isValidToken)){
        $status = TRUE;
        $message = "Bank Account Type get succesfully";
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }

    function removeBankAccountType_post(){
      $status = TRUE;
      $message = "Bank Account Type  succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();

      $id = $this->input->post('id');
      $table_name = 'account_types';
      $where = array(
        'id' => $this->input->post('id')
      );
      $data = array(
        'is_deleted' => 1
      );
      $result = $this->Admin_Model->updateTable($table_name, $data, $where);

      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }

    function activeInactiveBankAccountType_post(){
      $status = TRUE;
     
      $isValidToken = $this->Admin_Model->isValidToken();

      $activeInactive = $this->input->post('activeInactive');
      $message = "Bank Account Type Inactive succesfully";
      if($activeInactive == 1){
        $message = "Bank Account Type active succesfully";
      }

      $table_name = 'account_types';
      $where = array(
        'id' =>  $this->input->post('id')
      );
      $data = array(
        'is_active' => $this->input->post('activeInactive')
      );
      $result = $this->Admin_Model->updateTable($table_name, $data, $where);

      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'sql' => $this->db->last_query(),
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }
// end Source of Income module end

// TaxList module start

    function getTaxList_get(){

      $status = FALSE;
      $message = "Tax List not found";
      $result = "";
      $isValidToken = $this->Admin_Model->isValidToken();


      //if($isValidToken){
       
        $where = array(
          'is_deleted' => 0
        );
        $this->db->order_by('id','Desc');
        $result = $this->Admin_Model->getDataFromTableWithOject('tax',$where);
       
        $message = "Tax List data not found.";
        if(!empty($result)){
          $message = "Tax List data get succesfully.";
          $status  = TRUE;
        }
        

      // }else {
      //   $message = "Invalid token. Please logout and login again";
      
      // }
      
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result,
        'countries' => $countries
                      
      ];
      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);

    }
    
    function getTaxListDataById_post(){
      
      $status = TRUE;
      $message = "Tax List get succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();
     
     // echo "<pre>"; print_r($ids); exit;

      $id = $this->input->post('id');
      $table = 'tax';

      $where = array(
        'id' => $id       
      );

      $result = $this->Admin_Model->getRowDataFromTableWithOject($table,$where);
      if(!empty($isValidToken)){
        $status = TRUE;
        $message = "Tax List get succesfully";
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);

    }

    function updateTaxListTable_post(){
      
      $status = TRUE;
      $message = "Tax List succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();
      $id = $this->input->post('id');
      $table_name = 'tax';
      $data =  array(
        's_no' => $this->input->post('s_no'),
        'tax_code' => $this->input->post('tax_code'),
        'tax_status' => $this->input->post('tax_status'),
        'category' => $this->input->post('category')

      );
      if($id == 0){
        $this->Admin_Model->insertIntoTable($table_name, $data);
      }else{
        $where = array(
          'id' => $this->input->post('id')
        );
        $this->Admin_Model->updateTable($table_name, $data, $where);
      }
            
      if(!empty($isValidToken)){
        $status = TRUE;
        $message = "Tax List get succesfully";
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }

    function removeTaxList_post(){
      $status = TRUE;
      $message = "Tax List succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();

      $id = $this->input->post('id');
      $table_name = 'tax';
      $where = array(
        'id' => $this->input->post('id')
      );
      $data = array(
        'is_deleted' => 1
      );
      $result = $this->Admin_Model->updateTable($table_name, $data, $where);

      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }

    function activeInactiveTaxList_post(){
      $status = TRUE;
     
      $isValidToken = $this->Admin_Model->isValidToken();

      $activeInactive = $this->input->post('activeInactive');
      $message = "Tax List Inactive succesfully";
      if($activeInactive == 1){
        $message = "Tax List active succesfully";
      }

      $table_name = 'tax';
      $where = array(
        'id' =>  $this->input->post('id')
      );
      $data = array(
        'is_active' => $this->input->post('activeInactive')
      );
      $result = $this->Admin_Model->updateTable($table_name, $data, $where);

      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }
// end Tax List module end


function updateCustomerTable_post(){

      $this->load->helper("common_helper");
      $status = TRUE;
      $message = "User succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();
     
      $id = $this->input->post('id');
      $business_partner_id = $this->input->post('business_partner_id');
      $dealer_id = $this->input->post('dealer_id');
    
     /*CHECK IF PAN NUMBER AND MOBILE NUMBER EXIST */
     $mobile=$this->input->post('mobile');
     $email=$this->input->post('email');
     $pan=$this->input->post('pan');
     $name=$this->input->post('name');

     $sql="select id,mobile,email,pan from users where mobile='$mobile' or email='$email' or pan ='$pan'";
     $result_check_database=$this->db->query($sql)->row();

   
    $sms="";

      $table_name = 'users';
     
    if($id == 0){


      $data =  array(
        'name' => $this->input->post('name'),
        'pan' => $this->input->post('pan'),
        'mobile' => $this->input->post('mobile'),
        'email' => $this->input->post('email'),  
        "user_type_id"=>1,
        //"user_password"=>md5('123456')

      );
    }
    else{


      $data =  array(
        'name' => $this->input->post('name'),
        'pan' => $this->input->post('pan'),
        'mobile' => $this->input->post('mobile'),
        'email' => $this->input->post('email'),  
        "user_type_id"=>1,

      );

    }

$Mobile_Error="";
$Email_Error="";
$Pan_Error="";


    if($id == 0){
      if($result_check_database->mobile==$mobile){
      $status=FALSE;
      $message="Mobile number already User";
      $Mobile_Error="Mobile_Error";
      $result = array();
       $where = array();
     }
     elseif ($result_check_database->email==$email) 
     {
      $status=FALSE;
      $message="email number already User";
      $Email_Error="Email_Error";
      $result = array();
       $where = array();

      } 
    elseif ($result_check_database->pan==$pan) {
       $status=FALSE;
       $Pan_Error="Pan_Error";
      $message="Pan number already User";
      $result = array();
      $where = array();
    }
    else
    {
      /*Congratulation Mr Linto Francis your account has been succesfully created in Hero Crop Portal.Please Proceed to complete the registaton by clicking below link Website : http://103.87.174.12/heromutual/dev/admin-login*/
        $Messagesms="Congratulation $name , Your account has been succesfully created in Hero Corp. Please Proceed to complete the registration by clicking below link Website : http://103.87.174.12/heromutual/dev/signup";
   $sms= sendSms($this->input->post('mobile'), $Messagesms);
    SendSmslog($mobile,$Messagesms,$sms);
     $from = "heromf@herocorp.com";
    $to = $this->input->post('email');
	
	 $welecome_template='<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody><tr>
        <td bgcolor="#f5f5f5" align="center">

            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:500px" class="">
                <tbody><tr>
                    <td align="center" valign="top" style="padding:15px 0" class="">
                        <a href="http://103.87.174.12/heromutual/dev/" style="margin: 0 auto; display: inline-block" target="_blank">                                     
                                    <img id="" style="width: 170px; margin-top: 15px; margin-bottom: 10px; border: 0px; outline: none" alt="Hero Corp Logo" src="http://103.87.174.12/heromutual/dev/assets/images/logo.png"></a>  
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
                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:#666666" class="">Welcome to <a style="text-decoration:none;font-weight:bold">Hero Corporate Service </a> - Your Wealth Manager!</td>
                            </tr>
                            <tr>
                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:#666666" class="">Thank you for choosing Hero Corporate Services. Please visit below link to complete the process of Registration : http://103.87.174.12/heromutual/dev/signup </td>
                            </tr>
                           
                            <tr>
                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:#666666" class="">For more information please write to us at <a style="font-weight:bold" href="mailto: heromf@herocorp.com" target="_blank">heromf@herocorp.com</a></td>
                            </tr>
                            <tr>
                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:#666666" class="">Best regards, <br>
                                Team Hero Corporate Private Limited</td>
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
                    <a href="mailto:heromf@herocorp.com" style="color:#428bca" target="_blank">heromf@herocorp.com</a>
                    </td>
                    </tr>
                    
                    
            </tbody></table>

        </td>
    </tr>
</tbody></table>';
	
/*$Messagemail="Dear Customer  $name, Thank you for choosing Hero Crop ut your policy.Please process to complete process below link Website : http://103.87.174.12/heromutual/dev/login ";*/
$subject="Thank you for choosing Hero Corp";
 $mail=SendMail($to,$from,$welecome_template,$subject,"","");
 SendMaillog($to,$from,'',$subject,$welecome_template,$mail);
        $last_insert_id=$this->Admin_Model->insertIntoTable($table_name, $data);
        $user_type_link_array=array
        (
          "user_id"=>$last_insert_id,
          "is_dealer"=>0,
          "user_sub_customer_id"=>$dealer_id,
          "is_business"=>1,
          "business_id"=>1,
           "parent_id"=>$dealer_id,
          "business_type_id"=>$business_partner_id,
          "user_type"=>1,
        );
        $this->Admin_Model->insertIntoTable('user_type_link', $user_type_link_array);
      $status=TRUE;
      $message="Data insert succesfully";
      $result = $last_insert_id;
      $where = array();
      }
      }
      else
      {


      $sql_query="select ul.id as user_type_link_id, u.id,u.mobile,u.email,u.pan
from users u inner join user_type_link ul on u.id=ul.user_id where u.id=$id and(u.mobile='$mobile' or u.email='$email' or u.pan ='$pan')";
     $result_check_database_id_wise=$this->db->query($sql_query)->row();

     $user_type_link_id=$result_check_database_id_wise->user_type_link_id;
  $where=array("id"=>$id);

/*If cehck data is empty*/
 if(empty($result_check_database_id_wise))
  {

     if($result_check_database->mobile==$mobile){
      $status=FALSE;
      $message="Mobile number already User";
      $Mobile_Error="Mobile_Error";
      $result = array();
       $where = array();
     }
     elseif ($result_check_database->email==$email) 
     {
      $status=FALSE;
      $message="email number already User";
      $Email_Error="Email_Error";
      $result = array();
       $where = array();
      } 
    elseif ($result_check_database->pan==$pan) {
       $status=FALSE;
       $Pan_Error="Pan_Error";
      $message="Pan number already User";
      $result = array();
      $where = array();
    }
    else
    {
      $data_update= $this->Admin_Model->updateTable($table_name, $data, $where);
        $user_type_link_array=array
        (
          "user_id"=>$last_insert_id,
          "is_dealer"=>0,
          "user_sub_customer_id"=>$dealer_id,
          "is_business"=>1,
          "business_id"=>1,
           "parent_id"=>$dealer_id,
          "business_type_id"=>$business_partner_id,
          "user_type"=>1,
        );
        $where_array=array("id"=>$user_type_link_id);
        $this->Admin_Model->updateTable('user_type_link', $user_type_link_array, $where_array);

    }

  }

/*If cehck data is Not empty*/
else
    {

$mobile_database=$this->Admin_Model->getRowDataFromTableWithOject('users', array("mobile"=>$mobile,'id'=>$id));
$email_database=$this->Admin_Model->getRowDataFromTableWithOject('users', array("email"=>$email,'id'=>$id));
$pan_database=$this->Admin_Model->getRowDataFromTableWithOject('users', array("pan"=>$pan,'id'=>$id));

if(empty($mobile_database) || empty($email_database) || empty($pan_database))
{

/*check on database single entery*/
$mobile_database=$this->Admin_Model->getRowDataFromTableWithOject('users', array("mobile"=>$mobile));
$email_database=$this->Admin_Model->getRowDataFromTableWithOject('users', array("email"=>$email));
$pan_database=$this->Admin_Model->getRowDataFromTableWithOject('users', array("pan"=>$pan));

/*check without id */
$mobile_database_without_id=$this->Admin_Model->getdatawithoutid('users',$id,"mobile",$mobile);
$email_database_without_id=$this->Admin_Model->getdatawithoutid('users', $id,"email",$email);
$pan_database_without_id=$this->Admin_Model->getdatawithoutid('users',  $id,"pan",$pan);


if($mobile_database_without_id->mobile==$mobile){
      $status=FALSE;
      $message="Mobile number already User";
      $Mobile_Error="Mobile_Error";
      $result = array();
       $where = array();

     }
	

elseif($email_database_without_id->email==$email) 
     {
      $status=FALSE;
      $message="email number already User";
      $Email_Error="Email_Error";
      $result = array();
       $where = array();
  } 

elseif ($pan_database_without_id->pan==$pan) {
       $status=FALSE;
       $Pan_Error="Pan_Error";
      $message="Pan number already User";
      $result = array();
      $where = array();
    }
    else

    {
  $data_update= $this->Admin_Model->updateTable($table_name, $data, $where);
 $user_type_link_array=array
        (
          "user_id"=>$last_insert_id,
          "is_dealer"=>0,
          "user_sub_customer_id"=>$dealer_id,
          "is_business"=>1,
          "business_id"=>1,
           "parent_id"=>$dealer_id,
          "business_type_id"=>$business_partner_id,
          "user_type"=>1,
        );
        $where_array=array("id"=>$user_type_link_id);
        $this->Admin_Model->updateTable('user_type_link', $user_type_link_array, $where_array);

    }

}

}
}
   
  
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where,
        "sms"=>$sms,
        "Mobile_Error"=>$Mobile_Error,
        "Email_Error"=>$Email_Error,
        "Pan_Error"=>$Pan_Error,
                     
      ];
      $this->api_response($output_data);
    }


// User module start

    function getUsers_get(){

      $user_id=$this->get('user_id');

      $status = FALSE;
      $message = "Users not found";
      $result = "";
      $isValidToken = $this->Admin_Model->isValidToken();


      $this->db->select('id,user_type_id');
      $where_users=array('id'=>$user_id);
      $check_list = $this->Admin_Model->getRowDataFromTableWithOject('users',$where_users);


      if($check_list->user_type_id==5)
{
      //if($isValidToken){
        $where = array(
          'is_deleted' =>0,
          'user_type_id'=>1
        );
          $sql="select  u.id,bpt.title as user_type_name, 
                u.name,bpt.id as business_partner_id,
                u.pan, 
                u.mobile, 
                u.email, 
                u.date_of_birth, 
                u.is_active,user_type_id
                from users u left join user_type_link ul 
                on u.id=ul.user_id 
               inner join business_partner_type bpt on bpt.id=ul.business_type_id where  is_deleted=0 and  u.id!=$user_id order by u.id Desc";
        $result = $this->db->query($sql)->result_array();


}
else
{

 $this->load->helper("common_helper");
// check business level parent
/*                $sql_data_parent="select  bpt.parent_id              
                from users u left join user_type_link ul 
                on u.id=ul.user_id 
               inner join business_partner_type bpt on bpt.id=ul.business_type_id where u.id=$user_id";

$result_parent_id = $this->db->query($sql_data_parent)->row();
*/

$business_level_array=business_level($user_id);

if($business_level_array!=""){
$sql="select  u.id,bpt.title as user_type_name, 
                u.name,bpt.id as business_partner_id,
                u.pan, 
                u.mobile, 
                u.email, 
                u.date_of_birth, 
                u.is_active,user_type_id
                from users u left join user_type_link ul 
                on u.id=ul.user_id 
               inner join business_partner_type bpt on bpt.id=ul.business_type_id where is_deleted=0 and u.id in($business_level_array)  order by u.id Desc";
}

}
if($business_level_array!=""){

        $result = $this->db->query($sql)->result_array();
}

        $message = "Users data not found.";

        if(!empty($result)){
          $message = "Users data get succesfully.";
          $status  = TRUE;
        }

        

      // }else {
      //   $message = "Invalid token. Please logout and login again";
      
      // }
      
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result,
                      
      ];


      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);

    }
    
    function getUserDataById_post(){
      
      $status = TRUE;
      $message = "User get succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();
     
     // echo "<pre>"; print_r($ids); exit;

      $id = $this->input->post('id');
      $table = 'users';

      $where = array(
        'id' => $id       
      );

      $result = $this->Admin_Model->getRowDataFromTableWithOject($table,$where);

      $sql="select ut.parent_id, bt.id as business_partner_id, u.id,u.user_type_id,ut.*,bt.resource
            from users u  inner join user_type_link ut 
            on u.id=ut.user_id inner join business_partner_type bt on ut.business_type_id=bt.id 
            where user_type =3 and u.id=$id";

            
            $result_parent=$this->db->query($sql)->row();
            $business_partner_id=$result_parent->business_partner_id;
            $parent_id=$result_parent->parent_id;
           


      if(!empty($isValidToken)){
        $status = TRUE;
        $message = "User get succesfully";
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'business_partner_id'=>$business_partner_id,
        'parent_id'=>$parent_id,
        'where' => $where
                     
      ];
      $this->api_response($output_data);

    }


  function getCustmoerDataById_post(){
      $status = TRUE;
      $message = "User get succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();
     
     // echo "<pre>"; print_r($ids); exit;

      $id = $this->input->post('id');
      $table = 'users';

      $where = array(
        'id' => $id       
      );

      $result = $this->Admin_Model->getRowDataFromTableWithOject($table,$where);

/*
      $sql="select bt.id as business_partner_id, u.id,u.user_type_id,ut.is_dealer,ut.user_sub_customer_id,ut.is_business,ut.business_id,ut.parent_id,bt.resource
            from users u  inner join user_type_link ut 
            on u.id=ut.user_id inner join business_partner_type bt on ut.business_type_id=bt.id 
            where user_type =1 and u.id=$id";
*/

            $sql="select u.id, u.pan,u.user_type_id,ut.is_dealer,ut.user_sub_customer_id,ut.is_business,ut.business_id,ut.parent_id
            from users u  inner join user_type_link ut 
            on u.id=ut.user_id  
            where user_type =1 and u.id=$id";
            $result_parent=$this->db->query($sql)->row();
            $business_partner_id=$result_parent->business_partner_id;
            $parent_id=$result_parent->parent_id;

      if(!empty($isValidToken)){
        $status = TRUE;
        $message = "User get succesfully";
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'business_partner_id'=>$business_partner_id,
        'parent_id'=>$parent_id,
        'where' => $where
                     
      ];
      $this->api_response($output_data);

    }

    function getMonthlyReport_get(){

      $status = FALSE;
      $message = "Monthly Report not found";
      $result = "";
      $isValidToken = $this->Admin_Model->isValidToken();


      //if($isValidToken){
       
        // $where = array(
        //   'is_deleted' => 0
        // );
        $this->db->order_by('id','Desc');
        $this->db->select('id,ref_no,amc_name,report_month,net_brokerage,arn_code');
        $result = $this->Admin_Model->getDataFromTableWithOject('commission_summary_report');
       
        $message = "Monthly Report data not found.";
        if(!empty($result)){
          $message = "Monthly Report data get succesfully.";
          $status  = TRUE;
        }
        

      // }else {
      //   $message = "Invalid token. Please logout and login again";
      
      // }
      
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result,
        'countries' => $countries
                      
      ];
      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);

    }

     function getMonthlyReport_post(){

      $status = FALSE;
      $message = "Monthly Report not found";
      $result = "";
      $isValidToken = $this->Admin_Model->isValidToken();


      //if($isValidToken){
       
        // $where = array(
        //   'is_deleted' => 0
        // );
        $this->db->order_by('id','Desc');
        $this->db->select('id,ref_no,amc_name,report_month,net_brokerage,arn_code');
        $result = $this->Admin_Model->getDataFromTableWithOject('commission_summary_report');
       
        $message = "Monthly Report data not found.";
        if(!empty($result)){
          $message = "Monthly Report data get succesfully.";
          $status  = TRUE;
        }
        

      // }else {
      //   $message = "Invalid token. Please logout and login again";
      
      // }
      
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result,
        'countries' => $countries
                      
      ];
      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);

    }

    function updateUserTable_post(){
      $this->load->helper("common_helper");
      $status = TRUE;
      $message = "User succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();
      $id = $this->input->post('id');
      $business_partner_id = $this->input->post('business_partner_id');
      $parent_id = $this->input->post('parent_id');



      $mobile = $this->input->post('mobile');
      $email = $this->input->post('email');
      $name = $this->input->post('name');


      $Mobile_Error="";
      $Email_Error="";
      $Pan_Error="";

      $sql="select id,mobile,email,pan from users where mobile='$mobile' or email='$email' or pan ='$pan'";
      $result_check_database=$this->db->query($sql)->row();
      $sms="";
      $table_name = 'users';


    if($id == 0){
      $data =  array(
        'name' => $this->input->post('name'),
        'mobile' => $this->input->post('mobile'),
        'email' => $this->input->post('email'),  
        "user_type_id"=>3,
        "user_password"=>md5('123456')
      );
    }
    else
    {
       $data =  array(
        'name' => $this->input->post('name'),
        'mobile' => $this->input->post('mobile'),
        'email' => $this->input->post('email'),  
        "user_type_id"=>3
      );

    } 


      if($id == 0){


      if($result_check_database->mobile==$mobile){
      $status=FALSE;
      $message="Mobile number already User";
      $Mobile_Error="Mobile_Error";
      $result = array();
       $where = array();


     }
     if($result_check_database->email==$email) 
     {
      $status=FALSE;
      $message="email number already User";
      $Email_Error="Email_Error";
      $result = array();
       $where = array();

      } 
  
if($Pan_Error=="" && $Email_Error=="" && $Mobile_Error=="" )
    {

        $Messagesms="Congratulation $name Your account is succesfully created with Hero Crop. Please visit : http://103.87.174.12/heromutual/dev/admin-login";
   $sms= sendSms($this->input->post('mobile'), $Messagesms);
    SendSmslog($mobile,$Messagesms,$sms);
    
	$from = "heromf@herocorp.com";
    $to = $this->input->post('email');
	
	 $welecome_template='<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody><tr>
        <td bgcolor="#f5f5f5" align="center">

            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:500px" class="">
                <tbody><tr>
                    <td align="center" valign="top" style="padding:15px 0" class="">
                        <a href="http://103.87.174.12/heromutual/dev/" style="margin: 0 auto; display: inline-block" target="_blank">                                     
                                    <img id="" style="width: 170px; margin-top: 15px; margin-bottom: 10px; border: 0px; outline: none" alt="Hero Corp Logo" src="http://103.87.174.12/heromutual/dev/assets/images/logo.png"></a>  
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
                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:#666666" class="">Welcome to <a style="text-decoration:none;font-weight:bold">Hero Corporate Service </a> - Your Wealth Manager!</td>
                            </tr>
                            <tr>
                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:#666666" class="">Thank you for choosing Hero Corporate Services. Your account is successfully created with Hero Corp. </td>
                            </tr>
							 <tr>
                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:#666666" class="">Visit : <a style="text-decoration:none;font-weight:bold" href="http://103.87.174.12/heromutual/dev/admin-login">http://103.87.174.12/heromutual/dev/admin-login</a><br>
                                Login Id : '.$this->input->post('email').' <br>
                                Password : 123456</td>
                            </tr>
                           
                            <tr>
                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:#666666" class="">For more information please write to us at <a style="font-weight:bold" href="mailto: heromf@herocorp.com" target="_blank">heromf@herocorp.com</a></td>
                            </tr>
                            <tr>
                                <td style="padding:20px 0 0 0;font-size:16px;line-height:25px;font-family:Helvetica,Arial,sans-serif;color:#666666" class="">Best regards, <br>
                                Team Hero Corporate Private Limited</td>
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
                    <a href="mailto:heromf@herocorp.com" style="color:#428bca" target="_blank">heromf@herocorp.com</a>
                    </td>
                    </tr>
                    
                    
            </tbody></table>

        </td>
    </tr>
</tbody></table>';
/*$Messagemail="Dear   $name, Thank you for choosing Hero Crop ut your policy.Please process to complete process below link Website : http://103.87.174.12/heromutual/dev/login ";*/
$subject="Hero Corp Registration process";
 $mail=SendMail($to,$from,$welecome_template,$subject,"","");
 SendMaillog($to,$from,'',$subject,$welecome_template,$mail);
        $last_insert_id=$this->Admin_Model->insertIntoTable($table_name, $data);
        $user_type_link_array=array
        (
          "user_id"=>$last_insert_id,
          "is_dealer"=>0,
          "user_sub_customer_id"=>0,
          "is_business"=>1,
          "business_id"=>1,
          "business_type_id"=>$business_partner_id,
          "user_type"=>3,
          "parent_id"=>$parent_id
        );
        $this->Admin_Model->insertIntoTable('user_type_link', $user_type_link_array);
      }
    }
    else
    {

  $sql_query="select ul.id as user_type_link_id, u.id,u.mobile,u.email,u.pan
from users u inner join user_type_link ul on u.id=ul.user_id where u.id=$id and(u.mobile='$mobile' or u.email='$email' or u.pan ='$pan')";
     $result_check_database_id_wise=$this->db->query($sql_query)->row();

     $user_type_link_id=$result_check_database_id_wise->user_type_link_id;
      $where=array("id"=>$id);



      /*If cehck data is empty*/
 if(empty($result_check_database_id_wise))
  {

     if($result_check_database->mobile==$mobile){
      $status=FALSE;
      $message="Mobile number already User";
      $Mobile_Error="Mobile_Error";
      $result = array();
       $where = array();
     }
     if ($result_check_database->email==$email) 
     {
      $status=FALSE;
      $message="email number already User";
      $Email_Error="Email_Error";
      $result = array();
       $where = array();
      } 
  
    if($Email_Error=="" && $Mobile_Error=="" )
    {
      $data_update= $this->Admin_Model->updateTable($table_name, $data, $where);
        $user_type_link_array=array
        (
          "user_id"=>$last_insert_id,
          "is_dealer"=>0,
          "user_sub_customer_id"=>$dealer_id,
          "is_business"=>1,
          "business_id"=>1,
           "parent_id"=>$dealer_id,
          "business_type_id"=>$business_partner_id,
          "user_type"=>1,
        );
        $where_array=array("id"=>$user_type_link_id);
        $this->Admin_Model->updateTable('user_type_link', $user_type_link_array, $where_array);

    }

  }

/*If cehck data is Not empty*/
else
    {


$mobile_database=$this->Admin_Model->getdatawithid('users',$id,"mobile",$mobile);
$email_database=$this->Admin_Model->getdatawithid('users',$id,"email",$email);
if(empty($mobile_database->mobile) || empty($email_database->email) )
{
/*check on database single entery*/
/*$mobile_database=$this->Admin_Model->getRowDataFromTableWithOject('users', array("mobile"=>$mobile));
$email_database=$this->Admin_Model->getRowDataFromTableWithOject('users', array("email"=>$email));
$pan_database=$this->Admin_Model->getRowDataFromTableWithOject('users', array("pan"=>$pan));
*/
/*check without id */
$mobile_database_without_id=$this->Admin_Model->getdatawithoutid('users',$id,"mobile",$mobile);
$email_database_without_id=$this->Admin_Model->getdatawithoutid('users', $id,"email",$email);


if($mobile_database_without_id->mobile==$mobile){
      $status=FALSE;
      $message="Mobile number already User";
      $Mobile_Error="Mobile_Error";
      $result = array();
       $where = array();

     }
  

if($email_database_without_id->email==$email) 
     {
      $status=FALSE;
      $message="email number already User";
      $Email_Error="Email_Error";
      $result = array();
       $where = array();
  } 


  if( $Email_Error=="" && $Mobile_Error=="" )

    {
  $data_update= $this->Admin_Model->updateTable($table_name, $data, $where);
 $user_type_link_array=array
        (
          "user_id"=>$id,
          "is_dealer"=>0,
          "user_sub_customer_id"=>0,
          "is_business"=>1,
          "business_id"=>1,
          "business_type_id"=>$business_partner_id,
          "user_type"=>3,
           "parent_id"=>$parent_id
        );
        $where_array=array("id"=>$user_type_link_id);
        $this->Admin_Model->updateTable('user_type_link', $user_type_link_array, $where_array);

    }

}

}



/*
      $where = array(
          'id' => $this->input->post('id')
        );
        $this->Admin_Model->updateTable($table_name, $data, $where);

        $user_type_link_array=array
        (
          "user_id"=>$id,
          "is_dealer"=>0,
          "user_sub_customer_id"=>0,
          "is_business"=>1,
          "business_id"=>1,
          "business_type_id"=>$business_partner_id,
          "user_type"=>3,
           "parent_id"=>$parent_id
        );

        $where_array=array("user_id"=>$id);
        $this->Admin_Model->updateTable('user_type_link', $user_type_link_array, $where_array);*/

      }


            
      if(!empty($isValidToken)){
        $status = TRUE;
        $message = "User get succesfully";
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where,
        "sms"=>$sms,
        "Mobile_Error"=>$Mobile_Error,
        "Email_Error"=>$Email_Error,
                     
      ];

      $this->api_response($output_data);
    }

    function removeUser_post(){
      $status = TRUE;
      $message = "User succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();

      $id = $this->input->post('id');
      $table_name = 'users';
      $where = array(
        'id' => $this->input->post('id')
      );
      $data = array(
        'is_deleted' => 1
      );
      $result = $this->Admin_Model->updateTable($table_name, $data, $where);

      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }

    function activeInactiveUser_post(){
      $status = TRUE;
     
      $isValidToken = $this->Admin_Model->isValidToken();

      $activeInactive = $this->input->post('activeInactive');
      $message = "User Inactive succesfully";
      if($activeInactive == 1){
        $message = "User active succesfully";
      }

      $table_name = 'users';
      $where = array(
        'id' =>  $this->input->post('id')
      );
      $data = array(
        'is_active' => $this->input->post('activeInactive')
      );
      $result = $this->Admin_Model->updateTable($table_name, $data, $where);

      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }
// end User module end




// AMC names start

    function getAmc_get(){

      $status = FALSE;
      $message = "AMC not found";
      $result = "";
      $isValidToken = $this->Admin_Model->isValidToken();


      //if($isValidToken){
       
        $where = array(
          'is_deleted' => '0'
        );
        $this->db->order_by('amc_name','Asc');
        $result = $this->Admin_Model->getDataFromTableWithOject('amc_list',$where);
       
        $message = "AMC data not found.";
        if(!empty($result)){
          $message = "AMC data get succesfully.";
          $status  = TRUE;
        }
        

      // }else {
      //   $message = "Invalid token. Please logout and login again";
      
      // }
      
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result,
        'sql' => $this->db->last_query(),
        'countries' => $countries
                      
      ];
      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);

    }
    
    function getAmcDataById_post(){
      
      $status = TRUE;
      $message = "AMC get succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();
     
     // echo "<pre>"; print_r($ids); exit;

      $id = $this->input->post('id');
      $table = 'amc_list';

      $where = array(
        'amc_id' => $id       
      );

      $result = $this->Admin_Model->getRowDataFromTableWithOject($table,$where);
      if(!empty($isValidToken)){
        $status = TRUE;
        $message = "AMC get succesfully";
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);

    }

    function updateAmcTable_post(){
      
      $status = TRUE;
      $message = "AMC succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();
      $id = $this->input->post('id');
      $table_name = 'amc_list';
      $data =  array(
        'amc_name' => $this->input->post('name'),
        'amc_code' => $this->input->post('code')
      );
      if($id == 0){
        $this->Admin_Model->insertIntoTable($table_name, $data);
      }else{
        $where = array(
          'amc_id' => $this->input->post('id')
        );
        $this->Admin_Model->updateTable($table_name, $data, $where);
      }
            
      if(!empty($isValidToken)){
        $status = TRUE;
        $message = "AMC get succesfully";
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }

    function removeAmc_post(){
      $status = TRUE;
      $message = "AMC succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();

      $id = $this->input->post('id');
      $table_name = 'amc_list';
      $where = array(
        'amc_id' => $this->input->post('id')
      );
      $data = array(
        'is_deleted' => 1
      );
      $result = $this->Admin_Model->updateTable($table_name, $data, $where);

      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }

    function activeInactiveAmc_post(){
      $status = TRUE;
     
      $isValidToken = $this->Admin_Model->isValidToken();

      $activeInactive = $this->input->post('activeInactive');
      $message = "AMC Inactive succesfully";
      if($activeInactive == '1'){
        $message = "AMC active succesfully";
      }

      $table_name = 'amc_list';
      $where = array(
        'amc_id' =>  $this->input->post('id')
      );
      $data = array(
        'is_active' => $this->input->post('activeInactive')
      );
      $result = $this->Admin_Model->updateTable($table_name, $data, $where);

      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }
// end AMC module end

    // BANK names start

    function getBanks_get(){

      $status = FALSE;
      $message = "Bank not found";
      $result = "";
      $isValidToken = $this->Admin_Model->isValidToken();


      //if($isValidToken){
       
        $where = array(
          'is_deleted' => 0
        );
        $this->db->order_by('name','Asc');
        $result = $this->Admin_Model->getDataFromTableWithOject('banks',$where);
       
        $message = "Bank data not found.";
        if(!empty($result)){
          $message = "Bank data get succesfully.";
          $status  = TRUE;
        }
        

      // }else {
      //   $message = "Invalid token. Please logout and login again";
      
      // }
      
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result,
        'countries' => $countries
                      
      ];
      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);

    }

     function getCustomerdata_get(){
      $this->load->helper("common_helper");
      $user_id=$this->get('user_id');
      $business_partner_id=$this->get('business_partner_id');
      $status = FALSE;
      $message = "Custmoer not found";
      $result = "";
      $isValidToken = $this->Admin_Model->isValidToken();
      $where=array("id"=>$user_id);
      $this->db->select('id,user_type_id');
      $result_check = $this->Admin_Model->getDataFromTableWithOject('users',$where);

      $user_type_id=$result_check[0]->user_type_id;

      if($user_type_id==3){
        /*Patner login condtion*/
        /*Check if dealer login 1st */
        $sql_dealer="select (select count(id)as total from bank_mandate where pan_no=pan)as flag_check, u.id,u.pan,u.name,u.mobile,u.email,u.user_type_id,u.is_active,u.date_of_birth,ut.user_sub_customer_id,ut.is_business,ut.business_id,ut.business_id,ut.parent_id,ut.user_type from users u inner join user_type_link ut on u.id=ut.user_id where user_type_id=1 and parent_id=$user_id ";

        $result=$this->db->query($sql_dealer)->result_array();
        /*End*/
        if(empty($result)){
        /*if Dealer Not login another business parent login */
       $business_partner_type_array=$this->Admin_Model->getRowDataFromTableWithOject('business_partner_type',array('id'=>$business_partner_id));
          $business_partner_id_data=$business_partner_type_array->parent_id;
          $is_dealer_check=$business_partner_type_array->is_dealer_check;

      
          if($business_partner_id_data==0) //if partner is zero 
          {
            $parent_id=$business_partner_id;
          }
          else
          {
            $parent_id=$business_partner_type_array->parent_id;

          }

          /*Check level */
        $business_level_array=business_level($user_id);
          /*End*/

if($business_level_array!=""){

  

       $sql_business="select (select count(id)as total from bank_mandate where pan_no=pan)as flag_check, u.id,u.pan,u.name,u.mobile,u.email,u.user_type_id,u.is_active,u.date_of_birth,ut.user_sub_customer_id,ut.is_business,ut.business_id,ut.business_id,ut.parent_id,ut.user_type from users u inner join user_type_link ut on u.id=ut.user_id  where u.id in ($business_level_array) and u.user_type_id=1 ";
        $result=$this->db->query($sql_business)->result_array();
      
      if(empty($result))
      {
        $result=array();

      }
}
else{
        $result=array();


}
         /*End*/

        /*1st get parent id*/
        /*End*/
     /*   
    $sql="select u.id,u.name,u.mobile,u.email,u.user_type_id,u.is_active,u.date_of_birth,ut.* from users u inner join user_type_link ut on u.id=ut.user_id where user_type_id=1 and user_sub_customer_id=$user_id ";*/
           
     }

      }
      else
      {
/*Superadmin condtion*/
        $where_array=array("user_type_id"=>1,"is_deleted"=>0);
      $this->db->select('id,name,pan,mobile,email,(select count(id)as total from bank_mandate where pan_no=pan)as flag_check, date_of_birth,is_active,user_type_id');
      $result = $this->Admin_Model->getDataFromTableWithOject('users',$where_array);
      }
        $message = "Custmoer data not found.";
        if(!empty($result)){
          $message = "Custmoer data get succesfully.";
          $status  = TRUE;
        }
        

      // }else {
      //   $message = "Invalid token. Please logout and login again";
      
      // }
      
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result,
                      
      ];
      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);

    }



     function getContact_get(){

      $status = FALSE;
      $message = "Contact not found";
      $result = "";
      $isValidToken = $this->Admin_Model->isValidToken();


      //if($isValidToken){
       
        /*$where = array(
          'is_deleted' => 0
        );*/
        $this->db->order_by('name','Asc');

        $result = $this->Admin_Model->getDataFromTableWithOject('contact','');
       
        $message = "Contact data not found.";
        if(!empty($result)){
          $message = "Contact data get succesfully.";
          $status  = TRUE;
        }
        

      // }else {
      //   $message = "Invalid token. Please logout and login again";
      
      // }
      
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result,
        'countries' => $countries
                      
      ];
      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);

    }

    
    function getBankDataById_post(){
      
      $status = TRUE;
      $message = "Bank get succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();
     
     // echo "<pre>"; print_r($ids); exit;

      $id = $this->input->post('id');
      $table = 'banks';

      $where = array(
        'id' => $id       
      );

      $result = $this->Admin_Model->getRowDataFromTableWithOject($table,$where);
      if(!empty($isValidToken)){
        $status = TRUE;
        $message = "Bank get succesfully";
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);

    }

    function updateBankTable_post(){
      
      $status = TRUE;
      $message = "Bank succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();
      $id = $this->input->post('id');
      $table_name = 'banks';
      $data =  array(
        'code' => $this->input->post('code'),
        'name' => $this->input->post('name'),
        'mode' => $this->input->post('mode')
      );
      if($id == 0){
        $this->Admin_Model->insertIntoTable($table_name, $data);
      }else{
        $where = array(
          'id' => $this->input->post('id')
        );
        $this->Admin_Model->updateTable($table_name, $data, $where);
      }
            
      if(!empty($isValidToken)){
        $status = TRUE;
        $message = "Bank get succesfully";
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }

      function UserPrevileges_post()
      {

       $user_id = $this->input->post('user_id');



        $this->db->select('id,name,pan,mobile,email,date_of_birth,is_active,user_type_id');
        $result=$this->Admin_Model->getRowDataFromTableWithOject('users', array("id"=>$user_id));
        $user_type_id=$result->user_type_id;  
        switch ($user_type_id) {
        case '1':
        $result_user_perv=$this->Admin_Model->getRowDataFromTableWithOject('user_type_previleges_resource', array("uid"=>$user_id));
          break;
          case '3':
        $result_user_perv=$this->Admin_Model->getRowDataFromTableWithOject('user_type_previleges_resource', array("uid"=>$user_id));
        if(empty($result_user_perv)){
            $sql="select bt.id as business_partner_id, u.id,u.user_type_id,ut.*,bt.resource
            from users u  inner join user_type_link ut 
            on u.id=ut.user_id inner join business_partner_type bt on ut.business_type_id=bt.id 
            where user_type =3 and u.id=$user_id";
            $result_user_perv=$this->db->query($sql)->row();
          }
            break;
          case '5':
            $result_user_perv = $this->Admin_Model->getRowDataFromTableWithOject('user_type_previleges_resource', array('uid'=>$user_id));
            break;
        }

        if(!empty($result_user_perv)){

        $resource_previleges=$result_user_perv->resource;
        $json_encode_menu_bar=json_decode($resource_previleges);
        $selected_array_parent=$json_encode_menu_bar->parent;
        $selected_array_children=$json_encode_menu_bar->children;



      $sql="select * from resources  where type=2  ORDER BY `sorting_order`";
            $result = $this->db->query($sql)->result();

            $childs = array();
            foreach($result as $item)
                $childs[$item->parent_id][] = $item;
              
            foreach($result as $item) if (isset($childs[$item->resource_id]))
                $item->childs = $childs[$item->resource_id];


        $items = array();
        $i=0;
        if(!empty($childs)){
          foreach($childs[0] as $row){
            $items[$i]['text'] = $row->title;
            $items[$i]['value'] = $row->resource_id;
              $items[$i]['checked'] = false;
          if(in_array($row->resource_id, $selected_array_parent)){
               $items[$i]['checked'] = true;
            }

            if($row->childs){
              $j=0;
              foreach($row->childs as $child){
              $items[$i]['children'][$j]['checked'] = false;
                if(in_array($child->resource_id, $selected_array_children)){
                   $items[$i]['children'][$j]['checked'] = true;
                }

                $items[$i]['children'][$j]['text'] = $child->title;
                $items[$i]['children'][$j]['value'] = $child->resource_id;
               
                

                $j++;
              }
              
            }
            $i++;            
          }
          
        }



    /*        $ids = array();
            foreach ($json_encode_menu_bar->parent as $id) {
                array_push($ids, $id);
            }
            foreach ($json_encode_menu_bar->children as $id1) {
                array_push($ids, $id1);
            }
            
            $side_menu_bar_array=implode(",", $ids);

             $sql="select * from resources  where  resource_id in ($side_menu_bar_array)   ORDER BY `sorting_order`";
            $result_resources = $this->db->query($sql)->result();

            $childs = array();
            foreach($result_resources as $item)
                $childs[$item->parent_id][] = $item;
              
            foreach($result as $item) if (isset($childs[$item->resource_id]))
                $item->childs = $childs[$item->resource_id];

        $items = array();
        $i=0;
        if(!empty($childs)){
          foreach($childs[0] as $row){
            $items[$i]['text'] = $row->title;
            $items[$i]['value'] = $row->resource_id;
            if($row->childs){
              $j=0;
              foreach($row->childs as $child){
                $items[$i]['children'][$j]['text'] = $child->title;
                $items[$i]['children'][$j]['value'] = $child->resource_id;;
                $j++;
              }
              
            }
            $i++;            
          }
          
        }*/

        $message = "Menu data not found.";

        if(!empty($result)){
          $message = "side menu data get succesfully.";
          $status  = TRUE;
        }
      
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $items,
        
                      
      ];
           




        }

        else{

    $sql="select * from resources  where type=2  ORDER BY `sorting_order`";
            $result = $this->db->query($sql)->result();

            $childs = array();
            foreach($result as $item)
                $childs[$item->parent_id][] = $item;
              
            foreach($result as $item) if (isset($childs[$item->resource_id]))
                $item->childs = $childs[$item->resource_id];


        $items = array();
        $i=0;
        if(!empty($childs)){
          foreach($childs[0] as $row){
            $items[$i]['text'] = $row->title;
            $items[$i]['value'] = $row->resource_id;
            if($row->childs){
              $j=0;
              foreach($row->childs as $child){
                $items[$i]['children'][$j]['text'] = $child->title;
                $items[$i]['children'][$j]['value'] = $child->resource_id;;
                $j++;
              }
              
            }
            $i++;            
          }
          
        }



 /*  $sql="select * from resources    ORDER BY `sorting_order`";
            $result_resources = $this->db->query($sql)->result();

            $childs = array();
            foreach($result_resources as $item)
                $childs[$item->parent_id][] = $item;
              
            foreach($result as $item) if (isset($childs[$item->resource_id]))
                $item->childs = $childs[$item->resource_id];

        $items = array();
        $i=0;
        if(!empty($childs)){
          foreach($childs[0] as $row){
            $items[$i]['text'] = $row->title;
            $items[$i]['value'] = $row->resource_id;

            $items[$i]['checked'] = false;
            if(in_array($row->resource_id, $selected_array_parent)){
               $items[$i]['checked'] = true;
            }

            if($row->childs){
              $j=0;
              foreach($row->childs as $child){
                $items[$i]['children'][$j]['text'] = $child->title;
                $items[$i]['children'][$j]['value'] = $child->resource_id;

                

                if(in_array($row->resource_id, $selected_array_children)){
                   $items[$i]['children'][$j]['checked'] = true;
                }
                

                $j++;
              }
              
            }
            $i++;            
          }
          
        }*/





     $output_data = [
        'status' => 1,
        'message' => $message,
         'result' => $items,
        
                      
      ];
         

        }


         $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);
  


      }

    function removeBank_post(){
      $status = TRUE;
      $message = "Bank succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();

      $id = $this->input->post('id');
      $table_name = 'banks';
      $where = array(
        'id' => $this->input->post('id')
      );
      $data = array(
        'is_deleted' => 1
      );
      $result = $this->Admin_Model->updateTable($table_name, $data, $where);

      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }

    function activeInactiveBank_post(){
      $status = TRUE;
     
      $isValidToken = $this->Admin_Model->isValidToken();

      $activeInactive = $this->input->post('activeInactive');
      $message = "Bank Inactive succesfully";
      if($activeInactive == 1){
        $message = "Bank active succesfully";
      }

      $table_name = 'banks';
      $where = array(
        'id' =>  $this->input->post('id')
      );
      $data = array(
        'is_active' => $this->input->post('activeInactive')
      );
      $result = $this->Admin_Model->updateTable($table_name, $data, $where);

      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }
// end banks module end


// upload report file start
  function uploadReportFile_post(){
    $uploaded_successfully = false;
    $file = $_FILES["file"];
    $amc_id = $this->input->post('amc_id');
    $amc_name = $this->input->post('amc_name');
    $file_type = $this->input->post('file_type');

    $new_file_name = $this->Admin_Model->uploadFile('file','reports');

    // error_reporting(E_ALL);
    // ini_set('display_errors', TRUE);
    // ini_set('display_startup_errors', TRUE);

    // define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

    // date_default_timezone_set('Europe/London');

    /** Include PHPExcel_IOFactory */
    // require_once dirname(__FILE__) . '/phpexcellatest/Classes/PHPExcel/IOFactory.php';


    // if (!file_exists("DCB-ICICI~4362527~ARN-125938.xls")) {
    //   exit("Please run 05featuredemo.php first." . EOL);
    // }
    
    // $callStartTime = microtime(true);
    // $inputFileName = "DCB-ICICI~4362527~ARN-125938.xls";
    // $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    // $objReader = PHPExcel_IOFactory::createReader($inputFileType);

    // $sheetList = $objReader->listWorksheetNames($inputFileName);
    // $sheetInfo = $objReader->listWorksheetInfo($inputFileName);

    // for($i=0;$i<count($sheetList);$i++){

    //   $objReader->setLoadSheetsOnly($sheetList[$i]);
    //   $objPHPExcel = $objReader->load($inputFileName);


    //   $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
    //   $amc_name = 'uti';  
      
    //   switch ($sheetList[$i]) {
    //     case 'Summary':
    //       summaryEntry($sheetData,$amc_name);
    //       break;
    //     case 'Scheme wise summary':
    //       scemewisesummary($sheetData,$amc_name);
    //       break;
    //     case 'Annex1':
    //       annex($sheetData,$amc_name);
    //       break;
        
    //   }
    // }

    if($uploaded_successfully){
      $status = true;
      $message = "file uploaded succesfully.";
    }else{
      $status = false;
      $message = "file not uploaded succesfully.";
    }

    $output_data = [
      'status' => $status,
      'message' => $message     
                   
    ];
    $this->api_response($output_data);
    
  }

  function summaryEntry($sheetData,$amc_name){

    switch ($amc_name) {
      case 'uti':
        summaryEntryUTI($sheetData);
        break;
      default:
        $CustomerId = summaryEntryUTI($sheetData);
        break;
    }
  }

  function scemewisesummary($sheetData,$amc_name){
    switch ($amc_name) {
      case 'uti':
        scemeWithSummaryUTI($sheetData);
        break;
      default:
        $CustomerId = scemeWithSummaryUTI($sheetData);
        break;
    }
  }

  function annex($sheetData,$amc_name){
    switch ($amc_name) {
      case 'uti':
        annexUTI($sheetData);
        break;
      default:
        $CustomerId = annexUTI($sheetData);
        break;
    }
  }

  function summaryEntryUTI($sheetData){
    // echo "<pre>";
    // print_r($sheetData);exit;
    $company_data = array();
    $commission_summary = array();
    $business_summary = array();
    $asset_summary = array();
    $sip_status_summary = array();

    $buss_summ_start = 0;
    $asset_start = 0;
    $sip_status_start = 0;

    for($j=1;$j<count($sheetData);$j++){
      //column A
      if($sheetData[$j]['A']!=''){
        $company_data[] = $sheetData[$j]['A'];
      }

      //Commision Summary
      if($sheetData[$j]['D']=='Upfront'){
        $upfront = $sheetData[$j]['F']; 
      }elseif($sheetData[$j]['D']=='Additional Upfront'){
        $additionalfront = $sheetData[$j]['F']; 
      }elseif($sheetData[$j]['D']=='Annualised [First Year Trail - FYT]'){
        $annualised_fyt = $sheetData[$j]['F'];  
      }elseif($sheetData[$j]['D']=='Trail Fee [Long Term Trail - LTT]'){
        $trial_free = $sheetData[$j]['F'];  
      }elseif($sheetData[$j]['D']=='TransactionCharges'){
        $transaction_charges = $sheetData[$j]['F']; 
      }elseif($sheetData[$j]['D']=='Others'){
        $others = $sheetData[$j]['F'];  
      }elseif($sheetData[$j]['D']=='Total Gross (A)'){
        $total_gross = $sheetData[$j]['F'];
      }

      if($sheetData[$j]['H']=='Recovery Post GST'){
        $recovery_post_gst = $sheetData[$j]['J']; 
      }elseif($sheetData[$j]['H']=='Recovery Pre GST'){
        $recovery_pre_gst = $sheetData[$j]['J'];  
      }elseif($sheetData[$j]['H']=='SGST'){
        $sgst = $sheetData[$j]['J'];  
      }elseif($sheetData[$j]['H']=='CGST'){
        //echo "==>".$sheetData[$j]['J'];
        $cgst = $sheetData[$j]['J'];  
      }elseif($sheetData[$j]['H']=='IGST'){
        $igst = $sheetData[$j]['J'];  
      }elseif($sheetData[$j]['H']=='Total Deductions (B)'){
        $total_deduction = $sheetData[$j]['J']; 
      }elseif($sheetData[$j]['H']=='Net Brokerage (A-B)'){
        $net_brokerage = $sheetData[$j]['J']; 
      }

      $business_summary_flag = 0;
      $asset_summary_flag = 0;
      $sip_status_flag = 0;
      $common_flag = 0;
      
      if (strpos($sheetData[$j]['D'], 'Business Summary :') !== false) {
          $buss_summ_start = 1;
          $business_summary_flag = 1;
      }

      if (strpos($sheetData[$j]['D'], 'Asset class wise AUM as on (INR Lakhs)') !== false) {
          $asset_start = 1;
          $asset_summary_flag = 1;
      }

      if (strpos($sheetData[$j]['D'], 'SIP Status') !== false) {
          $sip_status_start = 1;
          $sip_status_flag = 1;
      }

      //Business Summary
      if($buss_summ_start == 1 && $asset_start == 0 && $sip_status_start == 0 && $business_summary_flag==0){
        if($sheetData[$j]['D']!='' && $sheetData[$j]['D']!='Type'){
          $business_summary[] = array(
            'type' =>$sheetData[$j]['D'],
            'subscriptions1' =>$sheetData[$j]['E'],
            'subscriptions2' =>$sheetData[$j]['F'],
            'redemptions1' =>$sheetData[$j]['G'],
            'redemptions2' =>$sheetData[$j]['H'],
            'redemptions1' =>$sheetData[$j]['I'],
            'redemptions2' =>$sheetData[$j]['J']
          );
        }
        $common_flag=1;
      }

      //Asset class wise AUM as on 
      if($asset_start == 1 && $sip_status_start == 0 && $common_flag==0){
        if($sheetData[$j]['D']!='' && $sheetData[$j]['D']!='Type' && $sip_status_flag ==0)
        {
          $asset_summary[] = array(
            'type' =>$sheetData[$j]['D'],
            'folio_count_from' =>$sheetData[$j]['E'],
            'aum_from' =>$sheetData[$j]['F'],
            'folio_count_to' =>$sheetData[$j]['G'],
            'aum_to' =>$sheetData[$j]['H'],
            'change_count' =>$sheetData[$j]['I'],
            'change_aum' =>$sheetData[$j]['J']
          );
        }
        $common_flag=1;
      }

      //SIP Status 
      if($sip_status_start == 1 && $sip_status_flag==0){
        if($sheetData[$j]['D']!='' && $sheetData[$j]['D']!='Type' && $sheetData[$j]['D']!='S.No' && is_numeric($sheetData[$j]['D']))
        {
          $sip_status_summary[] = array(
            'srno' =>$sheetData[$j]['D'],
            'type' =>$sheetData[$j]['E'],
            'countason_from' =>$sheetData[$j]['G'],
            'countason_to' =>$sheetData[$j]['I'],
            'change' =>$sheetData[$j]['J']
          );
        }
      }
    }

    $commission_summary[] = array(
      'upfront' => $upfront,
      'additionalfront' => $additionalfront,
      'annualised_fyt' => $annualised_fyt,
      'trial_free' => $trial_free,
      'transaction_charges' => $transaction_charges,
      'others' => $others,
      'total_gross' => $total_gross,
      'recovery_post_gst' => $recovery_post_gst,
      'recovery_pre_gst' => $recovery_pre_gst,
      'sgst' => $sgst,
      'cgst' => $cgst,
      'igst' => $igst,
      'total_deduction' => $total_deduction,
      'net_brokerage' => $net_brokerage,
    );

    // echo "company_data<pre>";
    // print_r($company_data);
    // echo "commission_summary<pre>";
    // print_r($commission_summary);
    // echo "business_summary<pre>";
    // print_r($business_summary);
    // echo "asset_summary<pre>";
    // print_r($asset_summary);
    // echo "sip_status_summary<pre>";
    // print_r($sip_status_summary);
  }





  function scemeWithSummaryUTI($sheetData){
    // echo "<pre>";
    // print_r($sheetData);exit;
    $sceme_wise_summary = array();
    for($j=1;$j<count($sheetData);$j++){
      if($sheetData[$j]['A']!='Payout summary - Scheme wise, Commission type wise'){
        $sceme_wise_summary[] = array(
          'scheme'=>$sheetData[$j]['A'],
          'category'=>'',
          'amount_unfront'=>$sheetData[$j]['B'],
          'amount_fyt'=>$sheetData[$j]['C'],
          'amount_ltt'=>$sheetData[$j]['D'],
          'amount_others'=>$sheetData[$j]['E'],
          'gross_brokerage'=>$sheetData[$j]['F'],
          'sgst'=>$sheetData[$j]['G'],
          'cgst'=>$sheetData[$j]['H'],
          'igst'=>$sheetData[$j]['I'],
          'utgst'=>$sheetData[$j]['J'],
          'deduction_amount'=>'',
          'net_brokerage'=>$sheetData[$j]['K'],
          'distribution_incentive'=>'',
          'additional_incentive'=>'',
          'transaction_charges'=>'',
        );
      }
    }
  }

 /* function annexUTI($sheetData){
    // echo "<pre>";
    // print_r($sheetData);exit;

    $annex = array();
    $subtotal = "";
    $planname = "";

    for($j=1;$j<count($sheetData);$j++){
      if($sheetData[$j]['A']!=""){
        $planname = $sheetData[$j]['A'];
      }
      if($sheetData[$j]['C']=="Sub Totals"){
        $subtotal = $sheetData[$j]['T'];
      }elseif($sheetData[$j]['A']==""){
        $annex[] = array(
          'plan'=>$planname,
          'account_number'=>$sheetData[$j]['B'],
          'investor_name'=>$sheetData[$j]['C'],
          'kyc'=>$sheetData[$j]['D'],
          'city'=>$sheetData[$j]['E'],
          'sub_broker'=>$sheetData[$j]['F'],
          'gst'=>$sheetData[$j]['G'],
          'brok_type'=>$sheetData[$j]['H'],
          'trxn_type'=>$sheetData[$j]['I'],
          'appno'=>$sheetData[$j]['J'],
          'mocode'=>$sheetData[$j]['K'],
          'trxn_date'=>$sheetData[$j]['L'],
          'period'=>$sheetData[$j]['M'],
          'days'=>$sheetData[$j]['N'],
          'cnav'=>$sheetData[$j]['O'],
          'units'=>$sheetData[$j]['P'],
          'amount'=>$sheetData[$j]['Q'],
          'daily_product'=>$sheetData[$j]['R'],
          'rate'=>$sheetData[$j]['S'],
          'gross_commission'=>$sheetData[$j]['T'],
          'sub_total'=>$subtotal
        );
      }
    }

    echo "<pre>";
    print_r($annex);
  }*/
  // function uploadReportFile_post(){
  //   $uploaded_successfully = false;
  //   $file = $_FILES["file"];
  //   $amc_id = $this->input->post('amc_id');
  //   $amc_name = $this->input->post('amc_name');
  //   $file_type = $this->input->post('file_type');

  //   //echo "<pre>"; print_r($file); 
  //   switch ($file_type) {
  //     case 'pdf':
  //         $this->pdfReadData($file,$amc_id);
  //         break;
  //     case 'csv':
  //         $this->csvReadData($file,$amc_id);
  //         break;
  //     case 'excel':
  //         $this->excelReadData($file,$amc_id);
  //         break;
      
  //   }
  //   $data_summary = array(
  //     'amc_id' => $this->input->post('amc_id'),
  //     'amc_name' => $this->input->post('amc_name'),
      
  //   );

  //   $this->Admin_Model->insertIntoTable('scheme_wise_summary', $data_summary);

  //   $data_annex = array(
  //     'amc_id' => $this->input->post('amc_id'),
  //     'amc_name' => $this->input->post('amc_name'),
         
  //   );
  //   $this->Admin_Model->insertIntoTable('annex', $data_annex);


  //   if($uploaded_successfully){
  //     $status = true;
  //     $message = "file uploaded succesfully.";
  //   }else{
  //     $status = false;
  //     $message = "file not uploaded succesfully.";
  //   }

  //   $output_data = [
  //     'status' => $status,
  //     'message' => $message     
                   
  //   ];
  //   $this->api_response($output_data);
    
  // }

     function getReportByIdDetails_post(){
      $status = TRUE;
      $message = "Report Details Fetch succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();
      $id = $this->input->post('id');
      $table_annex = 'annex';
      $table_scheme_wise_summary = 'scheme_wise_summary';
      $table_scheme_commission_summary = 'commission_summary_report';

      $where = array(
        'id' => $id
      );

      $summary_report_where = array(
        'summary_report_id' => $id
      );



      // print_r($where);
      // die;
      // summary_report_id   scheme_wise_summary,annex
      
      $result['annex_details'] = $this->Admin_Model->getDataFromTable($table_annex, $summary_report_where);
      $commission_summary_annex_details = $this->Admin_Model->getDataFromTable($table_scheme_commission_summary, $where);
      $commission_summary_detail['business_summary'] =json_decode($commission_summary_annex_details[0]['business_summary']);
      $commission_summary_detail['commision_summary'] =json_decode($commission_summary_annex_details[0]['commision_summary']);
      $commission_summary_detail['asset_class'] =json_decode($commission_summary_annex_details[0]['asset_class']);
      $commission_summary_detail['sip_status'] =json_decode($commission_summary_annex_details[0]['sip_status']);
      
      
      $result['commission_summary_report'] = $commission_summary_detail;
      $result['scheme_wise_summary_details'] = $this->Admin_Model->getDataFromTable($table_scheme_wise_summary, $summary_report_where);
      if(!empty($isValidToken)){
        $status = TRUE;
        $message = "AMC get succesfully";
      }
      
      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);

    }

    /*Custmoer*/


    function removeCustomer_post(){
      $status = TRUE;
      $message = "User succesfully";
      $isValidToken = $this->Admin_Model->isValidToken();

      $id = $this->input->post('id');
      $table_name = 'users';
      $where = array(
        'id' => $this->input->post('id')
      );
      $data = array(
        'is_deleted' => 1
      );
      $result = $this->Admin_Model->updateTable($table_name, $data, $where);

      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }

    function activeInactiveCustomer_post(){
      $status = TRUE;
     
      $isValidToken = $this->Admin_Model->isValidToken();

      $activeInactive = $this->input->post('activeInactive');
      $message = "User Inactive succesfully";
      if($activeInactive == 1){
        $message = "User active succesfully";
      }

      $table_name = 'users';
      $where = array(
        'id' =>  $this->input->post('id')
      );
      $data = array(
        'is_active' => $this->input->post('activeInactive')
      );
      $result = $this->Admin_Model->updateTable($table_name, $data, $where);

      $output_data = [
        'status' => $status,
        'message' => $message ,
        'result' => $result,
        'where' => $where
                     
      ];
      $this->api_response($output_data);
    }


         function get_business_partner_type_get()
         {
      $business_partner_id=$this->get('business_partner_id');

      $status = FALSE;
      $message = "Menu not found";
      $result = "";
      
      $isValidToken = $this->Admin_Model->isValidToken();
      
        $sql="select * from business_partner_type  where id=$business_partner_id";
        $result_child = $this->db->query($sql)->row();


        if($result_child->parent_id!=0){
        $sql_database="select u.id as user_id, name ,pan,email ,ul.business_type_id
from users u inner join user_type_link ul on u.id=ul.user_id
where ul.business_type_id =$result_child->parent_id AND u.is_deleted=0";
        $result = $this->db->query($sql_database)->result();

        if(empty($result)){

          $result=array();
        }
      }
      else
      {
        $result=array();

      }

        $message = "Menu data not found.";

        if(!empty($result)){
          $message = "Data fetch succesfully";
          $status  = TRUE;
        }
        
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result,
        
                      
      ];
      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);


         }


         function SwitchGetNoSelected_get()
         {

      $status = FALSE;
      $message = "Data not  found";
      $result = "";
      $isValidToken = $this->Admin_Model->isValidToken();

        $this->db->order_by('id','Desc');
        $this->db->select('id,folio_no,trxnno,amc_code,amount,usrtrxno,time1');
        $result = $this->Admin_Model->getDataFromTableWithOject('transaction_deatils');
       
        $message = "Transaction Report data not found.";
        if(!empty($result)){
          $message = "Transaction Report data get succesfully.";
          $status  = TRUE;
        }
        

      // }else {
      //   $message = "Invalid token. Please logout and login again";
      
      // }
      
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result,
        'countries' => $countries
                      
      ];
      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);


         }



          function get_Dealer_list_post()
         {

      $userId=$this->post('userId');
      $BusinessPartnerTypeId=$this->post('BusinessPartnerTypeId');


      $status = FALSE;
      $message = "Dealer not found";
      $result = "";
      
      $isValidToken = $this->Admin_Model->isValidToken();
      

        $sql_database="select u.id , ul.id as user_type_link,u.name ,u.pan,u.email ,ul.business_type_id
from users u inner join user_type_link ul on u.id=ul.user_id where business_type_id=7 and u.user_type_id!=1 ";
        $result = $this->db->query($sql_database)->result();

        if(empty($result)){

          $result=array();
        }

        $message = "Dealer data not found.";

        if(!empty($result)){
          $message = "Data fetch succesfully";
          $status  = TRUE;
        }
        
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result,
        
                      
      ];
      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);


}


  function getTransactionReport_get(){

      $status = FALSE;
      $message = "Monthly Report not found";
      $result = "";
      $isValidToken = $this->Admin_Model->isValidToken();


      //if($isValidToken){
       
        // $where = array(
        //   'is_deleted' => 0
        // );
        $this->db->order_by('id','Desc');
        $this->db->select('id,folio_no,trxnno,amc_code,amount,usrtrxno,time1');
        $result = $this->Admin_Model->getDataFromTableWithOject('transaction_deatils');
       
        $message = "Transaction Report data not found.";
        if(!empty($result)){
          $message = "Transaction Report data get succesfully.";
          $status  = TRUE;
        }
        

      // }else {
      //   $message = "Invalid token. Please logout and login again";
      
      // }
      
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result,
        'countries' => $countries
                      
      ];
      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);

    }



     function redemptionProcess_post(){

                $status = FALSE;
                $message = "Data not insert succesfully";
                $result = "";
                $isValidToken = $this->Admin_Model->isValidToken();

                $pan=$this->post('Pan');
                $amc_name=$this->post('amc_name');
                $scheme_name=$this->post('scheme_name');
                $folio_number=$this->post('folio_number');
                $current_value=$this->post('current_value');
                $redemption_units=$this->post('redemption_units');
                $redemption_amount=$this->post('redemption_amount');
                $redemption_type=$this->post('redemption_type');
                $holding_unit=$this->post('holding_unit');


                $allunit="N";
                $amount="";
                $qty="";

                if($redemption_type=="ALL")
                {
                  $allunit="Y";
                }
                elseif($redemption_type=="Amount")
                {
                  $amount=$redemption_amount;
                }
                elseif($redemption_type=="Units")
                {
                  $unit=$redemption_units;
                }

                  /*Fetch data bse key table */

                $Passwordsetup=Passwordsetup1();
                $Passwordsetup=str_replace('</getPasswordResult></getPasswordResponse></s:Body></s:Envelope>', '', $Passwordsetup);

  
                $query = $this->db->query("select * from bse_key where bse_live=0")
                  ->row_array();
                $bse_pass_key_data= $query;
                $member_id=$bse_pass_key_data['memberid'];
                $user_id=$bse_pass_key_data['userid'];
                $Password_bse=$bse_pass_key_data['password'];
                $Password_key=$bse_pass_key_data['passkey'];
                $euin=$bse_pass_key_data['euin'];
                $islive=$this->config->item('is_live');
                $this->config->item('SVC_ORDER_URL')[$islive];
                $islive=$this->config->item('is_live');
                $soap_url = $this->config->item('WSDL_ORDER_URL')[$islive];


            $master_scheme_data=$this->Admin_Model->getRowDataFromTableWithOject('scheme_account_master',array("Folio_no"=>$folio_number));
            $schemecode=$master_scheme_data->scheme_code;
            $bse_order_entry_id=$master_scheme_data->bse_order_entry_id;
            $isin_code=$master_scheme_data->scheme_isin;
            $unit_database=$master_scheme_data->unit;
            $amount_database=$master_scheme_data->amount;
            $scheme_account_master_id=$master_scheme_data->id;
            $amc_id=$master_scheme_data->amc_id;

             $soap_method = "orderEntryParam";

                /*BSE String */
            $islive=$this->config->item('is_live');
/*Live add Secure*/
            $TransNo=date('Ymd').'322601'.mt_rand(100000,999999);
            $soap_body_1='<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:bses="http://bsestarmf.in/">
               <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action>http://bsestarmf.in/MFOrderEntry/orderEntryParam</wsa:Action><wsa:To>'.$this->config->item('SVC_ORDER_URL')[$islive].'</wsa:To></soap:Header>
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
                     <bses:BuySell>R</bses:BuySell>
                     <!--Optional:-->
                     <bses:BuySellType>FRESH</bses:BuySellType>
                     <!--Optional:-->
                     <bses:DPTxn>P</bses:DPTxn>
                     <!--Optional:-->
                     <bses:OrderVal>'.$amount.'</bses:OrderVal>
                     <!--Optional:-->
                     <bses:Qty>'.$unit.'</bses:Qty>
                     <!--Optional:-->
                     <bses:AllRedeem>'.$allunit.'</bses:AllRedeem>
                     <!--Optional:-->
                     <bses:FolioNo>'.$folio_number.'</bses:FolioNo>
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
                     <bses:EUINVal>Y</bses:EUINVal>
                     <!--Optional:-->
                     <bses:MinRedeem>Y</bses:MinRedeem>
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
               </soap:Body></soap:Envelope>';


 // $data= $response = soapCall($soap_url, $soap_method, $soap_body_1);
   bse_logs($pan,$member_id,$soap_body_1, $data,"redemptions");
$data='<s:Envelope xmlns:s="http://www.w3.org/2003/05/soap-envelope" xmlns:a="http://www.w3.org/2005/08/addressing"><s:Header><a:Action s:mustUnderstand="1">http://bsestarmf.in/MFOrderEntry/orderEntryParamResponse</a:Action></s:Header><s:Body><orderEntryParamResponse xmlns="http://bsestarmf.in/"><orderEntryParamResult>New|20190124322601119377|59369880|2492101|24921|AEDPT6232J|ORD CONF: Your Request for FRESH REDEMPTION 9000000.000 UNITS in SCHEME: 103G THRO : PHYSICAL is confirmed for CLIENT : linto francis (Code: AEDPT6232J)  CONFIRMATION TIME: Jan 24 2019 12:27PM ENTRY BY:  ORDER NO: 59369880|0</orderEntryParamResult></orderEntryParamResponse></s:Body></s:Envelope>';

    $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $data);
    $xml = new SimpleXMLElement($response);
    $expold= explode('|', $xml->sBody->orderEntryParamResponse->orderEntryParamResult[0]);
    $status_SOAP=end($expold);
    /*if status 1 is error and status 0 is working */
    if($status_SOAP==1)
    {
      $message=$expold[6];
      $status=FALSE;
      $result=array();
    }
    else
    {
      /*($name,$scheme_code,$amc_id,$type,$pan,$nav_value,$current_value,$current_unit,$invested_amount,$invested_unit,$status)*/
       $nav_value=  getSchemePerformanceByISIN($isin_code);

       /*print_r($schemecode);echo "<br/>";
       print_r($amc_id);echo "<br/>";
       print_r($pan);echo "<br/>";
       print_r($nav_value);echo "<br/>";
       print_r($current_value);echo "<br/>";
       print_r($holding_unit);echo "<br/>";
       print_r($invested_amount);echo "<br/>";
       print_r($invested_unit);echo "<br/>";
       print_r($message);echo "<br/>";

       die();*/
      
      BseDailyactity("",$schemecode,$amc_id,"Redemptions",$pan,$nav_value,$current_value,$holding_unit,$redemption_amount,$redemption_units,"P",$message,$redemption_type);

                $bse_amount="";
                $amount_mins=$redemption_amount;
                $unit_mins=$holding_unit;


               if($redemption_type=="ALL")
                {


                $redemption_amount_in_navvalue=round($redemption_units * $nav_value);
                $bse_order_array=array("total_amount"=>$redemption_amount_in_navvalue);
                $where_bse=array("id"=>$bse_order_entry_id);
                $this->Admin_Model->updateTable("bse_order_entry", $bse_order_array,$where_bse);

                $scheme_account_master_array=array("amount"=>$redemption_amount_in_navvalue);
                $where_master=array("id"=>$scheme_account_master_id);
                $this->Admin_Model->updateTable("scheme_account_master", $scheme_account_master_array,$where_master);

                }
                elseif($redemption_type=="Amount")
                {
                /*$amount_mins=abs($current_value-$redemption_amount);*/
                $invested_amount = $unit_database*$nav_value;
                $amount_mins = $current_value-$invested_amount;

                $bse_order_array=array("total_amount"=>$amount_mins);
                $where_bse=array("id"=>$bse_order_entry_id);
                $this->Admin_Model->updateTable("bse_order_entry", $bse_order_array,$where_bse );
                $scheme_account_master_array=array("amount"=>$amount_mins);
                $where_master=array("id"=>$scheme_account_master_id);
                $this->Admin_Model->updateTable("scheme_account_master", $scheme_account_master_array,$where_master);

                }
                elseif($redemption_type=="Units")
                {
                  //get unit  
                $cal_unit = $current_value/$nav_value;
                $unit_total = $redemption_units-$cal_unit;

                $bse_order_array=array("units"=>$unit_total);
                $where_bse=array("id"=>$bse_order_entry_id);
                $this->Admin_Model->updateTable("bse_order_entry", $bse_order_array,$where_bse);

                $scheme_account_master_array=array("unit"=>$unit_total);
                $where_master=array("id"=>$scheme_account_master_id);
                $this->Admin_Model->updateTable("scheme_account_master", $scheme_account_master_array,$where_master);
                 


                }

                $status=TRUE;
                $message=$expold[6];
                /*If data order status sucess */
                /*calculate unit and amount current*/

    }




       $http_status = REST_Controller::HTTP_OK;
      $output_data = [
          'status' => $status,
        'message' => $message,
        'result' => $result,
                ]; 
          
      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);

    }




     function AdminSendOtp_post()
         {

      $email_id=$this->post('email');
      


      $status = FALSE;
      $message = "OTP not Send";
      $result = "";
      
      $isValidToken = $this->Admin_Model->isValidToken();
        
      $this->db->select('id,name,kyc,pan,mobile,email,is_active,bse_active');
      $check_data = $this->Admin_Model->getRowDataFromTableWithOject('users', array('email'=>$email_id));
      
      $mobile=$check_data->mobile;
      $email=$check_data->email;
      $mobile_otp=substr(str_shuffle("0123456789"), 0, 4);

      $name=$check_data->name;
      $data_array=array(
                        "mobile_otp "=>$mobile_otp,
                            );
      $this->Admin_Model->update_user_data($email,$data_array);

      $Message="Hello $name, Your verification code is $mobile_otp. Any help? write to us heromf@herocorp.com";
      sendSms($mobile, $Message);


       $mail=SendMail($email,$from,$Message,"Hello $name, Your Mobile verification code is $mobile_otp. Any help? write to us heromf@herocorp.com","","");
      SendMaillog($email,$from,'',$subject,$Message,$mail);


      $this->db->select('id,name,kyc,pan,mobile,email,is_active,bse_active,mobile_otp');
      $result = $this->Admin_Model->getRowDataFromTableWithOject('users', array('email'=>$email_id));



      if(empty($result)){
          $result=array();
       }

        if(!empty($result)){
          $message = "OTP Send successfully";
          $status  = TRUE;
        }
        
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result,
        
                      
      ];
      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);


}


 function VerifyAdminOtpData_post()
         {


      $email_id=$this->post('email');
      $otp=$this->post('otp');
      

      $status = FALSE;
      $message = "OTP not match";
      $result = "";
      
      $isValidToken = $this->Admin_Model->isValidToken();
      
      $sql="select pan,mobile,email from users where email='$email_id' and mobile_otp='$otp'";
      $result=$this->db->query($sql)->row();

      if(empty($result)){
          $result=array();
       }

        if(!empty($result)){
          $message = "OTP Match successfully";
          $status  = TRUE;
        }
        
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result,
        
                      
      ];
      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);


}

function UpdateAdminPassword_post()
         {


      $email_id=$this->post('email');
      $password=$this->post('New_password');
      

      $status = FALSE;
      $message = "Password Not Update";
      $result = "";
      
      $isValidToken = $this->Admin_Model->isValidToken();
     

        $result=array(
                            "user_password"=>md5($password),
                            );
      $this->Admin_Model->update_user_data($email,$data_array);
      if(empty($result)){
          $result=array();
       }

        if(!empty($result)){
          $message = "OTP Match successfully";
          $status  = TRUE;
        }
        
      $output_data = [
        'status' => $status,
        'message' => $message,
        'result' => $result,
        
                      
      ];
      $http_status = REST_Controller::HTTP_OK;
      $this->api_response($output_data,$http_status);


}









    
}

          
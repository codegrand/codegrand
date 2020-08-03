<?php
header('Access-Control-Allow-Origin: *');
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */

/** ---------- HTTP Request Type List Starts ---------- **/
## REST_Controller::HTTP_OK // OK (200) being the HTTP response code                   ##
## REST_Controller::HTTP_NOT_FOUND // NOT_FOUND (404) being the HTTP response code     ##
## REST_Controller::HTTP_CREATED // CREATED (201) being the HTTP response code         ##
## REST_Controller::HTTP_BAD_REQUEST // BAD_REQUEST (400) being the HTTP response code ##
## REST_Controller::HTTP_NO_CONTENT  // NO_CONTENT (204) being the HTTP response code  ##
/** ---------- HTTP Request Type List Ends ---------- **/

require APPPATH . 'libraries/REST_Controller.php';

Class User extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Api_Model');
		$this->load->helper("common_helper");

    }

    private function api_response($data, $http_status = REST_Controller::HTTP_OK){
        header('Access-Control-Allow-Origin: *');
        $this->response($data, $http_status);
    }

   public function fetchUserDetailFromMobile_get(){
	   
	   $mobile = $this->get('mobile');
	   
	   $fetchUserDetailFromMobile = $this->Api_Model->fetchUserDetailFromMobile($mobile);
	   
	   if(!empty($fetchUserDetailFromMobile)){
			
		$is_email_verified = $fetch_customer_details['is_email_verified'];
		$is_mobile_verified = $fetch_customer_details['is_mobile_verified'];	
			if($is_mobile_verified== '1' && $is_email_verified=='1'){
				$update_data = array(
					'is_verified_user'=> '1'
				);
				
				$where = array('mobile_no' => $mobile);
				$customer_id = $this->Api_Model->updateTable('user',$update_data,$where);	
			}	
			
        $http_status = REST_Controller::HTTP_OK;
        $customer_data = array(
            'status' => TRUE,
            'message' => 'Fetched Customer Data Succesfully',
            'data' => $fetchUserDetailFromMobile
            
        );
       

      }else {
		 
		  
		  $http_status = REST_Controller::HTTP_OK;
		  
		  
		   
		    $customer_data = array(
				'status' => FALSE,
				'message' => 'CREATE NEW PROFILE',
				'data' => ''
				
			);
		   
		  
	  }
      $this->api_response($customer_data,$http_status);

	   
	   
   }
   
   public function fetchUserDetailFromEmail_get(){
	   
	   $email = $this->get('email');
	   
	   $fetchUserDetailFromEmail = $this->Api_Model->fetchUserDetailFromEmail($email);
	   
	   if(!empty($fetchUserDetailFromEmail)){
			
		$is_email_verified = $fetch_customer_details['is_email_verified'];
		$is_mobile_verified = $fetch_customer_details['is_mobile_verified'];	
			if($is_mobile_verified== '1' && $is_email_verified=='1'){
				$update_data = array(
					'is_verified_user'=> '1'
				);
				
				$where = array('email_id'=> $email);
				$customer_id = $this->Api_Model->updateTable('user',$update_data,$where);	
			}
        $http_status = REST_Controller::HTTP_OK;
        $customer_data = array(
            'status' => TRUE,
            'message' => 'Fetched Customer Data Succesfully',
            'data' => $fetchUserDetailFromEmail
            
        );
       

      }else {
		 
		  
		  $http_status = REST_Controller::HTTP_OK;
		  
		  
		   
		    $customer_data = array(
				'status' => FALSE,
				'message' => 'CREATE NEW PROFILE',
				'data' => ''
				
			);
		   
		  
	  }
      $this->api_response($customer_data,$http_status);

	   
	   
   }
   
	
    public function registerUser_post(){
		$name = $this->post('name');
		$email_id = $this->post('email_id');
		$mobile = $this->post('mobile');
		$device_id = $this->post('device_id');
		$device_version = $this->post('device_version');
		$is_verified_user = $this->post('is_verified_user');
		$password = $this->post('password');
		
      
	  $fetch_customer_details = $this->Api_Model->fetch_customer_details($mobile,$email_id);
	  //
	  
      if(!empty($fetch_customer_details)){
			
        $dev_id = $fetch_customer_details['device_id'];
        $dev_version = $fetch_customer_details['device_version'];
		$is_email_verified = $fetch_customer_details['is_email_verified'];
		$is_mobile_verified = $fetch_customer_details['is_mobile_verified'];
		//print_r($is_email_verified); 
		//echo "<br>"; print_r($is_mobile_verified);exit();
		if($dev_id == '' || $dev_version=='0'){
			
			
			$update_data = array(
				'name' => $name,
				'device_id' => $device_id,
				'device_version'=>$device_version,
				'is_email_verified'=>$is_verified_user,
				'password'=>$password,
				'is_password_set'=>'1'
			);
			
			$where = array('mobile_no' => $mobile,'email_id'=> $email_id);
			$customer_id = $this->Api_Model->updateTable('user',$update_data,$where);
			if($customer_id!=null){
					$fetch_customer_details = $this->Api_Model->fetch_customer_details($mobile,$email_id);
			}

			$http_status = REST_Controller::HTTP_OK;
			$customer_data = array(
			'status' => TRUE,
			'message' => 'Fetched Customer Data Succesfully',
			'data' => $fetch_customer_details,
			//'token' => $token
			);
			//echo "".$this->db->last_query(); exit;
		}else if($dev_id != '' || $dev_version!='0') {
			
			$update_data = array(
				'name' => $name,
				'device_id' => $device_id,
				'device_version'=>$device_version,
				'is_email_verified'=>$is_verified_user,
				'password'=>$password,
				'is_password_set'=>'1'
			);
			
			$where = array('mobile_no' => $mobile,'email_id'=> $email_id);
			$customer_id = $this->Api_Model->updateTable('user',$update_data,$where);
			if($customer_id!=null){
					$fetch_customer_details = $this->Api_Model->fetch_customer_details($mobile,$email_id);
			}
			
			$is_email_verified = $fetch_customer_details['is_email_verified'];
			$is_mobile_verified = $fetch_customer_details['is_mobile_verified'];
			
			if($is_mobile_verified== '1' && $is_email_verified=='1'){
				$update_data = array(
					'is_verified_user'=> '1'
				);
				
				$where = array('mobile_no' => $mobile,'email_id'=> $email_id);
				$customer_id = $this->Api_Model->updateTable('user',$update_data,$where);
				
				if($customer_id!=null){
					$fetch_customer_details = $this->Api_Model->fetch_customer_details($mobile,$email_id);
				}
			}
		        
        

        $http_status = REST_Controller::HTTP_OK;
        $customer_data = array(
            'status' => TRUE,
            'message' => 'Fetched Customer Data Succesfully',
            'data' => $fetch_customer_details,
            //'token' => $token
        );
       // echo "<pre>"; print_r($customer_data); exit;

      }
	  }else {
		  //echo "else"; exit;
		  $pin = $this->generatePIN();
		  $otp = $this->generatePIN();
		  $insert_data = array(
			'name' => $name,
			'email_id'=> $email_id,
            'mobile_no' => $mobile,
            'device_id' => $device_id,
			'device_version'=>$device_version,
            'mobile_otp' => $pin,
			'email_pin'=>$otp,
			'is_email_verified' => $is_verified_user,
			'password'=>$password,
			'is_password_set'=>'1'
            
        );
		  $http_status = REST_Controller::HTTP_OK;
		   $customer_id = $this->Api_Model->insertIntoTable('user',$insert_data);
		   if($customer_id!=null){
		   $fetch_customer_details = $this->Api_Model->fetch_customer_details($mobile,$email_id);
			   $customer_data = array(
				'status' => TRUE,
				'message' => 'New Customer Created Successfully',
				'data' => $fetch_customer_details,
				//'token' => $token
			);
		   }
		  
	  }
      $this->api_response($customer_data,$http_status);

    }
	
	
	public function checkUserIdAvailability_post(){
		
		$UserId = $this->post('UserId');
		
		$check_user_id_exist = $this->Api_Model->check_user_id_availability($UserId);
		 //print_r($check_user_id_exist); exit(); 
		 if(!empty($check_user_id_exist)){
			
        $http_status = REST_Controller::HTTP_OK;
        $customer_data = array(
            'status' => FALSE,
            'message' => 'USER ID ALREADY EXIST!!'
            
        );

      }else {
		  $http_status = REST_Controller::HTTP_OK;
			$customer_data = array(
            'status' => TRUE,
            'message' => 'USER ID DOES NOT EXIST!!'
        );
		  
	  }
		 
		 $this->api_response($customer_data,$http_status);
	}
	
	
	public function updateProfileData_post(){
		$user_data = $this->input->post();
		$Id = $this->post('Id');
		$Name = $this->post('Name');
		$Email = $this->post('Email');
		$Mobile = $this->post('Mobile');
		$Password = $this->post('Password');
		$UserId = $this->post('UserId');

		
        //$Pan = $this->get('Pan');
        $config_user_validation = [
            [
                'field' => 'Id',
                'label' => 'Id',
                'rules' => 'required'
            ],
			[
                'field' => 'Email',
                'label' => 'Email',
                'rules' => 'required'
            ],
			[
                'field' => 'Mobile',
                'label' => 'Mobile',
                'rules' => 'required'
            ]

        ];
        $this->form_validation->set_data($user_data);
        $this->form_validation->set_rules($config_user_validation);
        if ($this->form_validation->run() != FALSE)
        {
			
				$update_data = array(
					'name' => $Name,
					'user_id' => $UserId,
					'password'=>$Password,
					'mobile_no'=>$Mobile,
					'email_id' => $Email,
					'is_password_set'=>'1'
				);

				$where = array('id' => $Id);
				$customer_id = $this->Api_Model->updateTable('user',$update_data,$where);
				
				if(!empty($customer_id)){
					$http_status = REST_Controller::HTTP_OK;
					$customer_data = array(
						'status' => TRUE,
						'message' => 'Profile Updated Succesfully'
						
					);
				}else {
					$http_status = REST_Controller::HTTP_OK;
					$customer_data = array(
					'status' => FALSE,
					'message' => 'FAILED TO UPDATE PROFILE!!'
					);

				}
			//echo "".$this->db->last_query(); exit;
			
		}else {
				$http_status = REST_Controller::HTTP_OK;
				$customer_data = array(
				'status' => FALSE,
				'message' => 'FAILED TO UPDATE PROFILE!!'
				);

		}		
		 $this->api_response($customer_data,$http_status);
		
		
		
	}
	
	public function login_post(){
		$user_data = $this->input->post();
		
		$Mobile = $this->post('Mobile');
		$Password = $this->post('Password');
		

		
        //$Pan = $this->get('Pan');
        $config_user_validation = [
            [
                'field' => 'Mobile',
                'label' => 'Mobile',
                'rules' => 'required'
            ],
			 [
                'field' => 'Password',
                'label' => 'Password',
                'rules' => 'required'
            ]

        ];
        $this->form_validation->set_data($user_data);
        $this->form_validation->set_rules($config_user_validation);
        if ($this->form_validation->run() != FALSE)
        {
			
		$check_user_id_exist = $this->Api_Model->checklogin($Mobile,$Password);
		  
		 if(!empty($check_user_id_exist)){
			
				$http_status = REST_Controller::HTTP_OK;
				$customer_data = array(
					'status' => TRUE,
					'message' => 'LOGIN SUCCESSFUL!!',
					'data'=> $check_user_id_exist
					
				);

		  }else {
			  $http_status = REST_Controller::HTTP_OK;
				$customer_data = array(
				'status' => FALSE,
					'message' => 'FAILED TO LOGIN!!',
					'data'=> ''
			);
			  
		  }
		
		}else {
				$http_status = REST_Controller::HTTP_OK;
				$customer_data = array(
					'status' => FALSE,
					'message' => 'BAD REQUEST!!',
					'data'=> ''
				);

		}		
		 $this->api_response($customer_data,$http_status);
		
		
		
	}
	
	 public function getUserByMobile_Email_get(){
      $mobile = $this->get('mobile_no');
      $email = $this->get('email');
	  
      
	  $get_customer_details = $this->Api_Model->get_customer_details($mobile,$email);
	  
	  
      if(!empty($get_customer_details)){
			
        
        $http_status = REST_Controller::HTTP_OK;
        $customer_data = array(
            'status' => TRUE,
            'message' => 'Fetched Customer Data Succesfully',
            'data' => $get_customer_details,
            
        );
     
      }else {
		   $http_status = REST_Controller::HTTP_OK;
		   $customer_data = array(
				'status' => FALSE,
				'message' => 'Failed to get record',
				'data' => '',
				
			);
	    }
		  
	  
      $this->api_response($customer_data,$http_status);

    }
	
	
	public function generatePIN($digits = 4){
    $i = 0; //counter
    $pin = ""; //our default pin is blank.
    while($i < $digits){
        //generate a random number between 0 and 9.
        $pin .= mt_rand(0, 9);
        $i++;
    }
    return $pin;
}

    public function verifyUserMobile_post(){
        $user_data = $this->input->post();
       
        $mobile = $this->post('Mobile');
        $otp= $this->post('OTP');
  
        //$custmoer_id = $this->post('custmoer_id');
        $config_user_validation = [
            [
                'field' => 'OTP',
                'label' => 'OTP',
                'rules' => 'required'
            ],

        ];
        $this->form_validation->set_data($user_data);
        $this->form_validation->set_rules($config_user_validation);
        if ($this->form_validation->run() != FALSE)
        {
            $check_data = $this->Api_Model->check_mobile_data($mobile,$otp);
            if (!empty($check_data))
            {
				$update_data = array(
					'is_mobile_verified' =>'1'     
				);
			
			$where = array('mobile_no' => $mobile);
			$customer_id = $this->Api_Model->updateTable('user',$update_data,$where);
				
				
                $http_status = REST_Controller::HTTP_OK;
                $agent_data = [
                    'status' => TRUE,
                    'message' => 'OTP MATCH',
                    'data' =>array()
                ];

            }
            else
            {
                $http_status = REST_Controller::HTTP_OK;
                $agent_data = [
                    'status' => FALSE,
                    'message' => 'Invalid Otp',
                    'data' => array()
                ]; 

            }


        }

        else
        {

            $http_status = REST_Controller::HTTP_OK;
            $agent_data = [
                'status' => 'FALSE',
                'message' => 'Please Enter OTP',
                'data' => $this->form_validation->error_array()
            ]; 

        }
        $this->api_response($agent_data,$http_status);

    }
	
	public function verifyUserEmail_post(){
        $user_data = $this->input->post();
        //$Pan = $this->post('Pan');
        $email = $this->post('email');
        $pin= $this->post('pin');
  
        //$custmoer_id = $this->post('custmoer_id');
        $config_user_validation = [
            [
                'field' => 'pin',
                'label' => 'pin',
                'rules' => 'required'
            ],

        ];
        $this->form_validation->set_data($user_data);
        $this->form_validation->set_rules($config_user_validation);
        if ($this->form_validation->run() != FALSE)
        {
            $check_data = $this->Api_Model->check_email_data($email,$pin);
            if (!empty($check_data))
            {
				$update_data = array(
					'is_email_verified' =>'1'     
				);
			
			$where = array('email_id' => $email);
			$customer_id = $this->Api_Model->updateTable('user',$update_data,$where);
				
				
                $http_status = REST_Controller::HTTP_OK;
                $agent_data = [
                    'status' => TRUE,
                    'message' => 'PIN MATCH',
                    'data' =>array()
                ];

            }
            else
            {
                $http_status = REST_Controller::HTTP_OK;
                $agent_data = [
                    'status' => FALSE,
                    'message' => 'INVALID PIN',
                    'data' => array()
                ]; 

            }


        }

        else
        {

            $http_status = REST_Controller::HTTP_OK;
            $agent_data = [
                'status' => 'FALSE',
                'message' => 'Please Enter PIN',
                'data' => $this->form_validation->error_array()
            ]; 

        }
        $this->api_response($agent_data,$http_status);

    }

	public function getUserRegisteredVehicleList_get(){
        /*Check pan number in database*/
         //$this->load->helper("common_helper");

         $user_id = $this->get('user_id');
        
        $getUserRegisteredVehicleList = $this->Api_Model->getUserRegisteredVehicleList($user_id);

        if(!empty($getUserRegisteredVehicleList)){
            $http_status = REST_Controller::HTTP_OK;
			
            $customer_data = [
                'status' => TRUE,
                'message' => 'Fetched Vehicle List Succesfully',
                'data' => $getUserRegisteredVehicleList
            ];
        }
        else
        {   
            
            $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => FALSE,
                'message' => 'Failed to Fetched Vehicle List',
                'data' => ''
            ];
        }
        $this->api_response($customer_data,$http_status);
    }
	
	
	public function getUserDeletedVehicleList_get(){
		
         $user_id = $this->get('user_id');
        
        $getUserDeletedVehicleList = $this->Api_Model->getUserDeletedVehicleList($user_id);

        if(!empty($getUserDeletedVehicleList)){
            $http_status = REST_Controller::HTTP_OK;
			
            $customer_data = [
                'status' => TRUE,
                'message' => 'Fetched Deleted Vehicle List Succesfully',
                'data' => $getUserDeletedVehicleList
            ];
        }
        else
        {   
            
            $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => FALSE,
                'message' => 'Failed to Fetched Deleted Vehicle List',
                'data' => ''
            ];
        }
        $this->api_response($customer_data,$http_status);
	}
	
	public function getUserRegisteredVehicleDetail_get(){
        /*Check pan number in database*/
         //$this->load->helper("common_helper");

         $user_id = $this->get('user_id');
		 $vehicle_id = $this->get('vehicle_id');
        $getUserRegisteredVehicleDetail = $this->Api_Model->getUserRegisteredVehicleDetail($user_id,$vehicle_id);

        if(!empty($getUserRegisteredVehicleDetail)){
            $http_status = REST_Controller::HTTP_OK;
			
            $customer_data = [
                'status' => TRUE,
                'message' => 'Fetched Vehicle List Succesfully',
                'data' => $getUserRegisteredVehicleDetail
            ];
        }
        else
        {   
            
            $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => FALSE,
                'message' => 'Failed to Fetched Vehicle List',
                'data' => ''
            ];
        }
        $this->api_response($customer_data,$http_status);
    }
	
	
	public function addvehicle_post(){
		
		$vehicle_data = $this->input->post();
		//print_r($vehicle_data);die();
		 $user_id = $this->post('user_id');
        $vehicle_id = $this->post('vehicle_id');
		$vehicle_type = $this->post('vehicle_type');
		$vehicle_name = $this->post('vehicle_name');
        $registration_no = $this->post('registration_no');
        $registration_date= $this->post('registration_date');
		$engine_no= $this->post('engine_no');
		$chassis_no= $this->post('chassis_no');
		$vehicle_make= $this->post('vehicle_make');
		$vehicle_model= $this->post('vehicle_model');
		$vehicle_variant= $this->post('vehicle_variant');
		$rc_image1= $this->post('rc_image1');
		$rc_image2= $this->post('rc_image2');
		$rc_image3= $this->post('rc_image3');
		$puc_date_of_issue= $this->post('puc_date_of_issue');
		$puc_date_of_expiry= $this->post('puc_date_of_expiry');
		$puc_serial_no= $this->post('puc_serial_no');
		$puc_carbon_monoxide_per= $this->post('puc_carbon_monoxide_per');
		$puc_hydro_carbon= $this->post('puc_hydro_carbon');
		$puc_nm_hc_level= $this->post('puc_nm_hc_level');
		$puc_reactive_hc= $this->post('puc_reactive_hc');
		$puc_image= $this->post('puc_image');
		$puc_renewal_reminder= $this->post('puc_renewal_reminder');
		
                       
                       
                      
        $config_vehicle_validation = [
            [
                'field' => 'user_id',
                'label' => 'user_id',
                'rules' => 'required'
            ],
			[
                'field' => 'registration_no',
                'label' => 'registration_no',
                'rules' => 'required'
            ],
			[
                'field' => 'registration_date',
                'label' => 'registration_date',
                'rules' => 'required'
            ],
			[
                'field' => 'vehicle_make',
                'label' => 'vehicle_make',
                'rules' => 'required'
            ],
			[
                'field' => 'vehicle_model',
                'label' => 'vehicle_model',
                'rules' => 'required'
            ]			

        ];
        $this->form_validation->set_data($vehicle_data);
        $this->form_validation->set_rules($config_vehicle_validation);
        if($this->form_validation->run() != FALSE)
        {
			
			if($vehicle_id==null && $vehicle_id==""){
				$insert_data = array(
					'user_id' => $user_id,
					'vehicle_make'=> $vehicle_make,
					'vehicle_type'=> $vehicle_type,
					'vehicle_given_name' => $vehicle_name,
					'vehicle_model' => $vehicle_model,
					'vehicle_variant_name' => $vehicle_variant,
					'registration_number'=>$registration_no,
					'registration_date' => $registration_date,
					'engine_number'=>$engine_no,
					'chassis_number' => $chassis_no,
					'rc_copy_image1'=> $rc_image1,
					'rc_copy_image2'=> $rc_image2,
					'rc_copy_image3'=> $rc_image3,
					'puc_date_of_issue'=> $puc_date_of_issue,
					'puc_date_of_expiry'=> $puc_date_of_expiry,
					'puc_certificate_no'=> $puc_serial_no,
					'puc_renewal_reminder'=> $puc_renewal_reminder,
					'puc_image'=> $puc_image,
					'co_per_level'=> $puc_carbon_monoxide_per,
					'hc_level'=> $puc_hydro_carbon,
					'nm_hc_level'=> $puc_nm_hc_level,
					'rhc_level'=> $puc_reactive_hc
					
				);
				  $http_status = REST_Controller::HTTP_OK;
				   $vehicle_ids = $this->Api_Model->insertIntoTable('users_vehicles',$insert_data);
				   if($vehicle_ids!=null){
				   $getUserRegisteredVehicleDetail = $this->Api_Model->getUserRegisteredVehicleDetail($user_id,$vehicle_ids);
					   $vehicle_data = array(
						'status' => TRUE,
						'message' => 'New Vehicle Added Successfully',
						'data' => $getUserRegisteredVehicleDetail
					);
				   }
				
				
				
			}else{
			
			
				$getUserRegisteredVehicleDetail = $this->Api_Model->getUserRegisteredVehicleDetail($user_id,$vehicle_id);
					if (!empty($getUserRegisteredVehicleDetail))
					{
						$update_data = array(
							'vehicle_type'=> $vehicle_type,
							'vehicle_make'=> $vehicle_make,
							'vehicle_model' => $vehicle_model,
							'vehicle_given_name' => $vehicle_name,
							'vehicle_variant_name' => $vehicle_variant,
							'registration_number'=>$registration_no,
							'registration_date' => $registration_date,
							'engine_number'=>$engine_no,
							'chassis_number' => $chassis_no,
							'rc_copy_image1'=> $rc_image1,
							'rc_copy_image2'=> $rc_image2,
							'rc_copy_image3'=> $rc_image3,
							'puc_date_of_issue'=> $puc_date_of_issue,
							'puc_date_of_expiry'=> $puc_date_of_expiry,
							'puc_certificate_no'=> $puc_serial_no,
							'puc_renewal_reminder'=> $puc_renewal_reminder,
							'puc_image'=> $puc_image,
							'co_per_level'=> $puc_carbon_monoxide_per,
							'hc_level'=> $puc_hydro_carbon,
							'nm_hc_level'=> $puc_nm_hc_level,
							'rhc_level'=> $puc_reactive_hc   
						);
					
						$where = array('id' => $vehicle_id);
						$vehicle_ids = $this->Api_Model->updateTable('users_vehicles',$update_data,$where);
					
					
					   $getUserRegisteredVehicleDetail = $this->Api_Model->getUserRegisteredVehicleDetail($user_id,$vehicle_ids);
						   $http_status = REST_Controller::HTTP_OK;
						   $vehicle_data =array(
							'status' => TRUE,
							'message' => 'Vehicle Data Updated Successfully',
							'data' =>$getUserRegisteredVehicleDetail
						);
						
				   }
					
					

            }
           


        }else
       {

          $http_status = REST_Controller::HTTP_OK;
           $vehicle_data = array(
                'status' => FALSE,
                'message' => 'Please Enter Mandatory Parameters',
                'data' => $this->form_validation->error_array()
            ); 

        }
        $this->api_response($vehicle_data,$http_status);

		
		
		
	}

	//OLD METHODS UNUSED
    
			public function updateInvestorInfo_post()
			{
       

          $user_data = $this->input->post();
          $custmoer_id = $this->input->post('custmoer_id'); 
          $pan = $this->input->post('Pan'); 
          $gender = $this->input->post('Gender'); 
          $country = $this->input->post('Country'); 
          $state = $this->input->post('State'); 
          $birthplace = $this->input->post('BirthPlace'); 
          $occupation = $this->input->post('Occupation'); 
          $annualsalary = $this->input->post('AnnualSalary'); 
          $annual_income_code = $this->input->post('AnnualSalaryCode'); 
          $incomesource = $this->input->post('IncomeSource'); 
          $stateofuserForPolitics = $this->input->post('StateofUserForPolitics'); 
/*          $source_of_wealth = $this->input->post('SourceofWealth'); 
*/        $source_of_wealth_code = $this->input->post('IncomeSourceCode'); 
          $occupation_code = $this->input->post('OccupationCode'); 


         $config_user_validation = [
            [
                'field' => 'Pan',
                'label' => 'Pan',
                'rules' => 'required'
            ]

        ];

        $this->form_validation->set_data($user_data);
        $this->form_validation->set_rules($config_user_validation);
        if ($this->form_validation->run() != FALSE)
        {
        $fetch_customer_details = $this->Api_Model->fetch_customer_details($pan);
                     $data_array=array(
                            "gender"=>$gender,
                            "country"=>'INDIA',
                            "state"=>$state,
                            "place_of_birth"=>$birthplace,
                            "occupation"=>$occupation,
                            "annual_income"=>$annualsalary,
                            "income_source"=>$incomesource,
                            "political"=>$stateofuserForPolitics,
                            "annual_income_code"=>$annual_income_code,
                            "source_of_wealth_code"=>$source_of_wealth_code,
                            "occupation_code"=>$occupation_code,
                            "investor_info"=>1,
                             "steps_completed "=>2,
                            );
                            
            $this->Api_Model->update_user_data($pan,$data_array);
            $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => TRUE,
                'message' => 'Data updated successfully',
                'data' => array()
            ];
        }
        else
        {
            $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => FALSE,
                'message' => 'Please select mandatory data',
                'data' => array()
            ];
        }
        $this->api_response($customer_data,$http_status);
    }

			public function updateNomineeInfo_post()
			{
       

          $user_data = $this->input->post();
          $pan = $this->input->post('Pan'); 
          $custmoer_id = $this->input->post('custmoer_id'); 
          $isnomineeassigned = $this->input->post('IsNomineeAssigned'); 
          $nomineename = $this->input->post('NomineeName'); 
          $nomineereleation = $this->input->post('NomineeReleation'); 
         
         $config_user_validation = [
            [
                'field' => 'Pan',
                'label' => 'Pan',
                'rules' => 'required'
            ]

        ];
        $this->form_validation->set_data($user_data);
        $this->form_validation->set_rules($config_user_validation);
        if ($this->form_validation->run() != FALSE)
        {
        $fetch_customer_details = $this->Api_Model->fetch_customer_details($pan);
                     $data_array=array(
                            "nominee_required"=>$isnomineeassigned,
                            "nominee_name"=>$nomineename,
                            "nominee_relationship"=>$nomineereleation,
                            "nominee_info"=>1,
                             "steps_completed "=>3,
                            );
                            
            $this->Api_Model->update_user_data($pan,$data_array);
            $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => TRUE,
                'message' => 'Data updated successfully',
                'data' => array()
            ];
        }
        else
        {
            $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => FALSE,
                'message' => 'Please select mandatory data',
                'data' => array()
            ];
        }
        $this->api_response($customer_data,$http_status);
    }

			public function updateJointHoldingInfo_post()
			{
       

          $user_data = $this->input->post();
         // $custmoer_id = $this->input->post('custmoer_id'); 
          $pan = $this->input->post('Pan'); 



          $NameApplicantII = $this->input->post('NameApplicantII'); 
          $EmailApplicantII = $this->input->post('EmailApplicantII'); 
          $MobileApplicantII = $this->input->post('MobileApplicantII'); 
          $PanApplicantII = $this->input->post('PanApplicantII'); 

          $NameApplicantIII = $this->input->post('NameApplicantIII'); 
          $EmailApplicantIII = $this->input->post('EmailApplicantIII'); 
          $MobileApplicantIII = $this->input->post('MobileApplicantIII'); 
          $PanApplicantIII = $this->input->post('PanApplicantIII'); 
         
         $config_user_validation = [
            [
                'field' => 'Pan',
                'label' => 'Pan',
                'rules' => 'required'
            ]

        ];
        $this->form_validation->set_data($user_data);
        $this->form_validation->set_rules($config_user_validation);
        if ($this->form_validation->run() != FALSE)
        {
        $fetch_customer_details = $this->Api_Model->fetch_customer_details($pan);
                     $data_array=array(
                            "second_app_name"=>$NameApplicantII,
                            "second_app_pan"=>$PanApplicantII,
                            "third_app_name"=>$NameApplicantIII,
                            "third_app_pan"=>$PanApplicantIII,
                            );


                            
            $this->Api_Model->update_user_data($pan,$data_array);
            $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => TRUE,
                'message' => 'Data updated successfully',
                'data' => array()
            ];
        }
        else
        {
            $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => FALSE,
                'message' => 'Please select mandatory data',
                'data' => array()
            ];
        }
        $this->api_response($customer_data,$http_status);
    }

            public function uploadUserSignature_post()
            {
                 $user_data = $this->input->post();
                 $binarydataforsignature  =$this->input->post('BinaryDataForSignature');
                    $pan = $this->input->post('Pan'); 

         $config_user_validation = [
            [
                'field' => 'BinaryDataForSignature',
                'label' => 'BinaryDataForSignature',
                'rules' => 'required'
            ]


        ];

        $this->form_validation->set_data($user_data);
        $this->form_validation->set_rules($config_user_validation);
        if ($this->form_validation->run() != FALSE)
        {
            //            $json_decode=json_decode($ans_data);

      
            $fetch_customer_details = $this->Api_Model->fetch_customer_details($pan);
            $file_name = $this->upload($binarydataforsignature,$pan);
        //
            $data_array=array(
                'signature' =>$file_name,
                'signature_string' =>$binarydataforsignature,
                'signature_info' =>1,
                
            );
            $this->Api_Model->update_user_data($pan,$data_array);

            $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => TRUE,
                'message' => 'Thank you',
                'data' => array()
            ];
        }
        else
        {
            $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => FALSE,
                'message' => 'Please select mandatory data',
                'data' => array()
            ];

        }
        $this->api_response($customer_data,$http_status);


            }

			public function uploadBankMandate_post()
            {


                 $user_data = $this->input->post();
                 $BinaryDataForBankMandate  =$this->input->post('BinaryDataForBankMandate');
                $pan = $this->input->post('Pan'); 


         $config_user_validation = [
            [
                'field' => 'BinaryDataForBankMandate',
                'label' => 'BinaryDataForBankMandate',
                'rules' => 'required'
            ]


        ];

        $this->form_validation->set_data($user_data);
        $this->form_validation->set_rules($config_user_validation);
        if ($this->form_validation->run() != FALSE)
        {
            //            $json_decode=json_decode($ans_data);

            
        $result = $this->db->query("select id,pan,otm2 from users where pan ='$pan'")->row_array();

          $this->load->model('Api_Model');
        $bse_pass_key_data = $this->Api_Model->bse_key_data();

        $member_id=$bse_pass_key_data['memberid'];
        $user_id=$bse_pass_key_data['userid'];
        $Password_bse=$bse_pass_key_data['password'];
        $Password_key=$bse_pass_key_data['passkey'];
        $euin=$bse_pass_key_data['euin'];
        
        $check_database=$this->Api_Model->getRowDataFromTableWithOject('bank_mandate', array("pan_no"=>$pan));




          $file_name = $this->uploadBankMadateFile($BinaryDataForBankMandate,$pan,$member_id,$result['otm2']);
        //
          if(empty($check_database)){
            $data_array=array(
                'pan_no'=>$pan,
                'bank_mandate_file_bse_star' =>$file_name['tiff_format'],
                'bank_mandate_file' =>$file_name['jpg_fromat'],
                'bank_mandate_string' =>$BinaryDataForBankMandate,
                'status'=>'Pending'
                
            );
            $this->Api_Model->insertIntoTable('bank_mandate',$data_array); 
          }
          else

          {

             $data_array=array(
                'bank_mandate_file_bse_star' =>$file_name['tiff_format'],
                'bank_mandate_file' =>$file_name['jpg_fromat'],
                'bank_mandate_string' =>$BinaryDataForBankMandate,
                'status'=>'Pending'
                
            );
            $this->Api_Model->updateTable('bank_mandate', $data_array, array("pan_no"=>$pan)); 

          }

            $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => TRUE,
                'message' => 'Data Add Succesfully',
                'data' => array()
            ];
        }
        else
        {
            $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => FALSE,
                'message' => 'Please select mandatory data',
                'data' => array()
            ];

        }
        $this->api_response($customer_data,$http_status);


            }   

			public function updateContactInfo_post()
			{
       

          $user_data = $this->input->post();
         // $custmoer_id = $this->input->post('custmoer_id'); 
          $pan = $this->input->post('Pan'); 
          $pincode = $this->input->post('Pincode'); 
          $address1 = $this->input->post('Address1'); 
          $address2 = $this->input->post('Address2'); 
          $address3 = $this->input->post('Address3'); 
          $city = $this->input->post('City'); 
          $state = $this->input->post('State'); 
          $addresstype = $this->input->post('AddressType'); 
         
         $config_user_validation = [
            [
                'field' => 'Pan',
                'label' => 'Pan',
                'rules' => 'required'
            ]

        ];
        $this->form_validation->set_data($user_data);
        $this->form_validation->set_rules($config_user_validation);
        if ($this->form_validation->run() != FALSE)
        {
        $fetch_customer_details = $this->Api_Model->fetch_customer_details($pan);
                     $data_array=array(
                           
                            "state"=>$state,
                            "pincode"=>$pincode,
                            "address_type"=>$addresstype,
                            "street_1"=>$address1,
                            "street_2"=>$address2,
                            "street_3"=>$address3,
                            "city"=>$city,
                            "contact_info"=>1,
                             "steps_completed "=>4,
                            );
                            
            $this->Api_Model->update_user_data($pan,$data_array);
            $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => TRUE,
                'message' => 'Data updated successfully',
                'data' => array()
            ];
        }
        else
        {
            $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => FALSE,
                'message' => 'Please select mandatory data',
                'data' => array()
            ];
        }
        $this->api_response($customer_data,$http_status);
    }
         

             function upload($image,$pan,$memberid=null ){
       // $member_code='GIIB';
        $client_code=$pan;
        //create unique image file name based on micro time and date
        if(!empty($image)){
            $now = DateTime::createFromFormat('U.u', microtime(true));
            $id = $memberid.''.$client_code.''.$now->format('dmY');

            $upload_folder = "uploads/user_data/signature/"; 
            $path = $upload_folder."/".$id.".jpeg";
            $url=$id.".tiff";
            file_put_contents($path, base64_decode($image));
            return $url;          
            //Cannot use "== true"
        }
    }


			function uploadCheque($image,$pan){
       // $member_code='GIIB';
        $client_code=$pan;
        $member_id = $pan;
        //create unique image file name based on micro time and date
        if(!empty($image)){
            $now = DateTime::createFromFormat('U.u', microtime(true));
            $id = $member_id.''.$now->format('dmY');

            $upload_folder = "uploads/user_data/cheque/"; 
            $path = $upload_folder."/".$id.".jpeg";
            $url=$id.".tiff";
            file_put_contents($path, base64_decode($image));
            return $url;          
            //Cannot use "== true"
        }
    }

			public function updateUserBankingInfo_post()
			{

       // echo "<pre>"; print_r($this->input->post()); exit;

       $this->load->model('Api_Model');
        $bse_pass_key_data = $this->Api_Model->bse_key_data();

        $member_id=$bse_pass_key_data['memberid'];
        $user_id=$bse_pass_key_data['userid'];
        $Password_bse=$bse_pass_key_data['password'];
        $Password_key=$bse_pass_key_data['passkey'];
        $euin=$bse_pass_key_data['euin'];


          $this->load->helper("common_helper");
          $user_data = $this->input->post();
          $custmoer_id = $this->input->post('custmoer_id'); 
          $pan = $this->input->post('Pan'); 
          $accountNo = $this->input->post('AccountNo'); 
          $AccountHolderName = $this->input->post('AccountHolderName'); 
          $ifsc = $this->input->post('IFSC'); 
          $branch = $this->input->post('Branch'); 
          $address = $this->input->post('Address'); 
          $bankname = $this->input->post('BankName'); 
          $bankcode = $this->input->post('BankCode'); 
          $bankmode = $this->input->post('BankMode'); 
          $city = $this->input->post('City'); 
         // $state = $this->input->post('State'); 
          $accountType = $this->input->post('AccountType'); 

          $isemadaterequired = $this->input->post('IsEmadateRequired'); 
          if($isemadaterequired == true){ $isemadaterequired = 1;  }

          $isisiprequired = $this->input->post('IsISipRequired'); 
          if($isisiprequired == true){  $isisiprequired = 1; }

          $isxsipRequired  = $this->input->post('IsXSipRequired'); 
          if($isxsipRequired == true){  $isxsipRequired = 1; }

          $binarydataforcheque  = $this->input->post('BinaryDataForCheque'); 
          $bankcity  = $this->input->post('BankCity'); 
          $bankstate  = $this->input->post('BankState'); 
           $bank_micr_code  = $this->input->post('BankMicrCode'); 
          



        $iscancelledchequeuploaded = $this->input->post('IsCancelledChequeUploaded'); 
         
         $config_user_validation = [
            [
                'field' => 'Pan',
                'label' => 'Pan',
                'rules' => 'required'
            ]

        ];
        $this->form_validation->set_data($user_data);
        $this->form_validation->set_rules($config_user_validation);
        if ($this->form_validation->run() != FALSE)
        {
        $tnc_table= $this->Api_Model->fetch_tnc_deatils($pan,1); 
        $PrivacyPolicy_table= $this->Api_Model->fetch_tnc_deatils($pan,2); 

        $fetch_customer_details = $this->Api_Model->fetch_customer_details($pan);
        //   $file_name = $this->uploadCheque($binarydataforcheque,$pan);
                     $data_array=array(
                            "bank_account_number"=>$accountNo,
                            "bank_account_holder_name"=>$AccountHolderName,
                            "bank_ifsc_code"=>$ifsc,
                            "bank_name"=>$bankname,
                           "bank_code"=>$bankcode,
                            "bank_mode"=>$bankmode,
                            "bank_branch"=>$branch,
                            "bank_address"=>$address,
                            "bank_account_type"=>$accountType,
                            "emandate_otm_flag"=>$isemadaterequired,
                            "xsip_otm_flag"=>$isisiprequired,
                            "xsip_otm_flag1"=>$isxsipRequired,
                            "active"=>1,
                            "chequeleaf"=>$iscancelledchequeuploaded,
                            "cheque"=>$file_name,
                           "cheque_string"=>$binarydataforcheque,
                            "bank_city"=>$bankcity,
                            "bank_micr_code"=>$bank_micr_code,
                            "bank_info"=>1,
                            "steps_completed "=>6,
                            "default_bank"=>'Y',
                            "is_check_tnc"=>$tnc_table->id,
                            "is_check_privacy_policy"=>$PrivacyPolicy_table->id,
                            );
                            
         //  echo "<pre>"; print_r($data_array); exit;
            $result= $this->Api_Model->update_user_data($pan,$data_array);
           /* $array_log_tnc_log=array
            (
                "pan_no"=>$pan,
                 "tnc_id"=>$tnc_table->id,
                  "pp_id"=>$PrivacyPolicy_table->id,
                  "date"=>date('Y-m-d'),

            );
            $this->Api_Model->insertIntoTable("tnc_log", $array_log_tnc_log) ;
*/

//$fetch_customer_details = $this->Api_Model->fetch_customer_details($pan_number);

/* "client_code "=>$number,
                            "tax_status "=>01,*/

$fetch_details = $this->Api_Model->fetch_customer_details($pan);

$user_data=array(
    "CLIENT CODE"=>$fetch_details['pan'],
    "CLIENT HOLDING"=>$fetch_details['client_holding'],
    "CLIENT TAXSTATUS"=>$fetch_details['tax_status'],
    "CLIENT OCCUPATIONCODE"=>$fetch_details['occupation_code'],
    "CLIENT APPNAME1"=>$fetch_details['name'], //custmoer Full Name 1 
    "CLIENT APPNAME2"=>$fetch_details['second_app_name'],// Joint Partner full Name
    "CLIENT APPNAME3"=>$fetch_details['third_app_name'],// Joint Partner full Name 2
    "CLIENT DOB"=>date('d/m/Y',strtotime($fetch_details['date_of_birth'])),// Client DOB
    "CLIENT GENDER"=>$fetch_details['gender'],// Client gender
    "CLIENT FATHERHUSBAND"=>$fetch_details['father_name'],// Client father_name 
    "CLIENT PAN"=>$fetch_customer_details['pan'],// Client Pan 
    "CLIENT NOMINEE"=>$fetch_details['nominee_name'],// Client nominee_name 
    "CLIENT NOMINEE RELATION"=>$fetch_details['nominee_relationship'],// Client 
    "CLIENT GUARDIANPAN"=>$fetch_details['guard_name'],// Client guard_name 
    "CLIENT TYPE"=>'P',// Client guard_name 
    "CLIENT DEFAULTDP"=>'',// Client Pan CDSL
    "CLIENT CDSLDPID"=>'',// 12056700
    "CLIENT CDSLCLTID"=>'',// $fetch_details['client_code']
    "CLIENT NSDLDPID"=>'',// add in database
    "CLIENT NSDLCLTID"=>'',// 
    "CLIENT ACCTYPE 1"=>$fetch_details['bank_account_type'],
    "CLIENT ACCNO 1"=>$fetch_details['bank_account_number'],
    "CLIENT MICRNO 1"=>$fetch_details['bank_micr_code'], 
    "CLIENT NEFT/IFSCCODE 1"=>$fetch_details['bank_ifsc_code'],
    "default bank flag 1"=>$fetch_details['default_bank'],
    "CLIENT ACCTYPE 2"=>$fetch_details['bank_account_type1'],
    "CLIENT ACCNO 2"=>$fetch_details['bank_account_number1'],
    "CLIENT MICRNO 2"=>$fetch_details['bank_micr_code1'], 
    "CLIENT NEFT/IFSCCODE 2"=>$fetch_details['bank_ifsc_code1'],
    "default bank flag 2"=>$fetch_details['default_bank1'],
    "CLIENT ACCTYPE 3"=>$fetch_details['bank_account_type2'],
    "CLIENT ACCNO 3"=>$fetch_details['bank_account_number2'],
    "CLIENT MICRNO 3"=>$fetch_details['bank_micr_code2'],
    "CLIENT NEFT/IFSCCODE 3"=>$fetch_details['bank_ifsc_code2'],
    "default bank flag 3"=>$fetch_details['default_bank2'],
    "CLIENT ACCTYPE 4"=>$fetch_details['bank_account_type3'],
    "CLIENT ACCNO 4"=>$fetch_details['bank_account_number3'],
    "CLIENT MICRNO 4"=>$fetch_details['bank_micr_code3'], 
    "CLIENT NEFT/IFSCCODE 4"=>$fetch_details['bank_ifsc_code3'], 
    "default bank flag 4"=>$fetch_details['default_bank3'],
    "CLIENT ACCTYPE 5"=>$fetch_details['bank_account_type4'],
    "CLIENT ACCNO 5"=>$fetch_details['bank_account_number4'],
    "CLIENT MICRNO 5"=>$fetch_details['bank_micr_code4'], 
    "CLIENT NEFT/IFSCCODE 5"=>$fetch_details['bank_ifsc_code4'], 
    "default bank flag 5"=>$fetch_details['default_bank4'],
    "CLIENT CHEQUENAME"=>$fetch_details['bank_account_holder_name'], 
    "CLIENT ADD1"=>$fetch_details['street_1'], 
    "CLIENT ADD2"=>$fetch_details['street_2'], 
    "CLIENT ADD3"=>$fetch_details['street_3'], 
    "CLIENT CITY"=>$fetch_details['city'], 
    "CLIENT STATE"=>$fetch_details['state'], //store code
    "CLIENT PINCODE"=>$fetch_details['pincode'], 
    "CLIENT COUNTRY"=>'INDIA', 
    "CLIENT RESIPHONE"=>$fetch_details['phone_residence'], 
    "CLIENT RESIFAX"=>'', 
    "CLIENT OFFICEPHONE"=>$fetch_details['phone_office'], 
    "CLIENT OFFICEFAX"=>'', 
    "CLIENT EMAIL"=>$fetch_details['email'], 
    "CLIENT COMMMODE"=>'E', 
    "CLIENT DIVPAYMODE"=>'04', 
    "CLIENT PAN2"=>$fetch_details['second_app_pan'], 
    "CLIENT PAN3"=>$fetch_details['third_app_pan'], 
    "MAPIN NO"=>'', 
    "CM_FORADD1"=>$fetch_details['nri_street_1'], 
    "CM_FORADD2"=>$fetch_details['nri_street_2'], 
    "CM_FORADD3"=>$fetch_details['nri_street_3'], 
    "CM_FORCITY"=>$fetch_details['nri_city'], 
    "CM_FORSTATE"=>$fetch_details['nri_state'], //store code
    "CM_FORPINCODE"=>$fetch_details['nri_pincode'], 
    "CM_FORCOUNTRY"=>$fetch_details['nri_country'],  //Code
    "CM_FORRESIPHONE"=>'',  //Code
    "CM_FORRESIFAX"=>'',  //Code
    "CM_FOROFFPHONE"=>'',  //Code
    "CM_FOROFFFAX"=>'',  //Code
    "CM_MOBILE"=>$fetch_details['mobile'],
 );

$piped_array = array();
foreach($user_data as $user=>$key)
{

    array_push($piped_array,$key);
}

$piped_string = implode("|",$piped_array);

$piped_string = str_replace('Maharashtra','MA',$piped_string);
//echo "<pre>"; print_r($piped_string); exit;


$this->load->helper("common_helper");


       // $this->load->helper("bharti_soap_helper");
$Passwordsetup=PasswordsetupUpload();
$Passwordsetup=str_replace('</getPasswordResult></getPasswordResponse></s:Body></s:Envelope>', '', $Passwordsetup);
  $islive=$this->config->item('is_live');


    $soap_url = $this->config->item('WSDL_UPLOAD_URL')[$islive];
        //$end_point_url = 'http://bsestarmfdemo.bseindia.com/MFOrderEntry/MFOrder.svc';
            $soap_method = "MFAPI";
            $soap_body_1 = '';


$soap_body_1 = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ns="'.$this->config->item('XMLNS_URL')[$islive].'">
   <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing"><wsa:Action>'.$this->config->item('ACTION_MFAPI_URL')[$islive].'</wsa:Action><wsa:To>'.$this->config->item('SVC_UPLOAD_URL')[$islive].'</wsa:To></soap:Header>
   <soap:Body>
      <ns:MFAPI>
         <!--Optional:-->
         <ns:Flag>02</ns:Flag>
         <!--Optional:-->
         <ns:UserId>'.$user_id.'</ns:UserId>
         <!--Optional:-->
         <ns:EncryptedPassword>'.$Passwordsetup.'</ns:EncryptedPassword>
         <!--Optional:-->
            <ns:param>'.$piped_string.'</ns:param>
      </ns:MFAPI>
   </soap:Body>
</soap:Envelope>';
$this->load->helper("soap_helper");

$data= soapCall($soap_url, $soap_method, $soap_body_1);
bse_logs($pan,$member_id,$soap_body_1,$data,"UCC");
$database_user_id=$fetch_details['id'];

$string_rep=str_replace('s:', '', $data);

$xml_output=simplexml_load_string($string_rep);
$xml_response=(array)$xml_output->Body->MFAPIResponse->MFAPIResult;
$expol_data=explode('|', $xml_response[0]);

$status=$expol_data[0];
$message=$expol_data[1];



if($status==100)
{

$fetch_customer_details = $this->Api_Model->fetch_customer_details($pan);
$database_user_id=$fetch_customer_details['id'];
if($fetch_customer_details['bank_account_type']==NULL){

    $type="SB";
}
else{

    $type=$fetch_customer_details['bank_account_type'];
}


$array_mandate_e=array(
    "CLIENT CODE"=>$fetch_customer_details['pan'],
    "AMOUNT"=>100000,
    "Mandate Type"=>'E' ,
    "ACCOUNT NO"=>$fetch_customer_details['bank_account_number'],
    "A/C TYPE"=>$type,
    "IFSC CODE"=>$fetch_customer_details['bank_ifsc_code'],
    "MICR CODE"=>'', 
    "START DATE"=>date('d/m/Y'),
    "END DATE"=>date('d/m/Y', strtotime('+10 years'))
                       );

$array_rep = array();

//$otm = Mandateapi($array_mandate_e,$Passwordsetup,"E",$database_user_id,$fetch_customer_details['pan']);

$array_rep['otmE'] = $otm;

$array_mandate_i=array(
    "CLIENT CODE"=>$fetch_customer_details['pan'],
    "AMOUNT"=>1000000,
    "Mandate Type"=>'I' ,
    "ACCOUNT NO"=>$fetch_customer_details['bank_account_number'],
    "A/C TYPE"=>$type,
    "IFSC CODE"=>$fetch_customer_details['bank_ifsc_code'],
    "MICR CODE"=>'', 
    "START DATE"=>date('d/m/Y'),
    "END DATE"=>date('d/m/Y', strtotime('+10 years'))
                       );

$otm1 = Mandateapi($array_mandate_i,$Passwordsetup,"I",$database_user_id,$fetch_customer_details['pan']);

$array_rep['otmI'] = $otm1;

$array_mandate_x=array(
    "CLIENT CODE"=>$fetch_customer_details['pan'],
    "AMOUNT"=>500000,
    "Mandate Type"=>'X' ,
    "ACCOUNT NO"=>$fetch_customer_details['bank_account_number'],
    "A/C TYPE"=>$type,
    "IFSC CODE"=>$fetch_customer_details['bank_ifsc_code'],
    "MICR CODE"=>'', 
    "START DATE"=>date('d/m/Y'),
    "END DATE"=>date('d/m/Y', strtotime('+10 years'))
                       );
$otm2 = Mandateapi($array_mandate_x,$Passwordsetup,"X",$database_user_id,$fetch_customer_details['pan']);

$array_rep['otmX'] = $otm2;
UploadFile($user_id,$pan,$fetch_customer_details['cheque_string'],$fetch_customer_details['cheque']);
        $otm_array=$array_rep;

  $user_id=$fetch_details['id'];

  $side_menu_bar = $this->Api_Model->getRowDataFromTableWithOject('user_type_previleges_resource', array('uid'=>$user_id));
  $json_encode_menu_bar=json_decode($side_menu_bar->resource); 

            $ids = array();
            foreach ($json_encode_menu_bar->parent as $id) {
                array_push($ids, $id);
            }
            foreach ($json_encode_menu_bar->children as $id1) {
                array_push($ids, $id1);
            }
            
            $side_menu_bar_array=implode(",", $ids);

            if(empty($side_menu_bar)){

            $sql="select * from resources  where resource_id  IN ($side_menu_bar_array)  ORDER BY `sorting_order`";
            }
            else
            {
            $sql="select * from resources where type=1  ORDER BY `sorting_order`";

            }
            $result = $this->db->query($sql)->result();

            $childs = array();
            foreach($result as $item)
                $childs[$item->parent_id][] = $item;
              
            foreach($result as $item) if (isset($childs[$item->resource_id]))
                $item->childs = $childs[$item->resource_id];





   $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => TRUE,
                'message' => 'Data updated successfully',
                'data' =>$otm_array,
                'user_id'=>$database_user_id,
                'side_menu_bar'=>$childs[0]
            ];




        

}

else
{

           $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => FALSE,
                'message' =>$message,
                'data' =>''
            ];



}



        }
        else
        {
            $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => FALSE,
                'message' => 'Please select mandatory data',
                'data' => array()
            ];
        }
        $this->api_response($customer_data,$http_status);
    }

			public function resendEmailPin_post()
			{
        
          $user_data = $this->input->post();
          $pan = $this->input->post('Pan'); 
          $email = $this->input->post('Email'); 
         $config_user_validation = [
            [
                'field' => 'Pan',
                'label' => 'Pan',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_data($user_data);
        $this->form_validation->set_rules($config_user_validation);
        if ($this->form_validation->run() != FALSE)
        {
        $fetch_customer_details = $this->Api_Model->fetch_customer_details($pan);
    
            $email_opt=substr(str_shuffle("0123456789"), 0, 4);
                     $data_array=array(
                            "email_authcode"=>$email_opt,
                            "steps_completed "=>1,
                            );
                            
            $this->Api_Model->update_user_data($pan,$data_array);

            $email_id = $fetch_customer_details['email'];

            $this->load->helper("common_helper");
            $name=$fetch_customer_details['first_name'];

             if($fetch_customer_details['email_verified'] ==0)
            {   
            send_mail_otp($name,$email_opt,$email);
            }

            $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => TRUE,
                'message' => 'Send successfully',
                'data' => array()
            ];
        }
        else
        {
            $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => FALSE,
                'message' => 'Please select mandatory data',
                'data' => array()
            ];
        }
        $this->api_response($customer_data,$http_status);

    }

			public function BankAddUpdate_post()
			{
          // echo "<pre>";
          // print_r($_POST);
          // die;

          $user_data = $this->input->post();
          $pan = $this->input->post('Pan'); 
          $type = $this->input->post('type'); 
          $account_holder_name = $this->input->post('account_holder_name'); 
          $change_accunt_type = $this->input->post('change_accunt_type'); 
          $change_account_no = $this->input->post('change_account_no'); 
          $change_ifsc_code = $this->input->post('change_ifsc_code'); 
          $change_branch_name = $this->input->post('change_branch_name'); 
          $change_bank_address = $this->input->post('change_bank_address'); 
          $change_bank_city = $this->input->post('change_bank_city'); 
          $change_bank_state = $this->input->post('change_bank_state'); 
          $bank_name = $this->input->post('bank_name'); 
          $bank_code = $this->input->post('bank_code'); 
          



         $config_user_validation = [
         
              [
                'field' => 'Pan',
                'label' => 'Pan',
                'rules' => 'required'
            ],
              [
                'field' => 'type',
                'label' => 'type',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_data($user_data);
        $this->form_validation->set_rules($config_user_validation);
        if ($this->form_validation->run() != FALSE)
        {
        $fetch_customer_details = $this->Api_Model->fetch_customer_details($pan);

        if(!empty($fetch_customer_details)){


                switch ($type) {
                  case 'bank1':
                  $data_update=array(
                    "bank_account_holder_name"=>$account_holder_name,
                    "bank_account_type"=>$change_accunt_type,
                    "bank_account_number"=>$change_account_no,
                    "bank_ifsc_code"=>$change_ifsc_code,
                    "bank_branch"=>$change_branch_name,
                    "bank_branch"=>$change_branch_name,
                    "bank_address"=>$change_bank_address,
                    "bank_city"=>$change_bank_city,
                    "bank_state"=>$change_bank_state,
                    "bank_name"=>$bank_name,
                    "bank_code"=>$bank_code
                  );

                    break;

                      case 'bank2':
                  $data_update=array(
                    "bank_account_holder_name1"=>$account_holder_name,
                    "bank_account_type1"=>$change_accunt_type,
                    "bank_account_number1"=>$change_account_no,
                    "bank_ifsc_code1"=>$change_ifsc_code,
                    "bank_branch1"=>$change_branch_name,
                    "bank_branch1"=>$change_branch_name,
                    "bank_address1"=>$change_bank_address,
                    "bank_name1"=>$bank_name,
                    "bank_code1"=>$bank_code


                  );

                    break;

                      case 'bank3':
                  $data_update=array(
                    "bank_account_holder_name2"=>$account_holder_name,
                    "bank_account_type2"=>$change_accunt_type,
                    "bank_account_number2"=>$change_account_no,
                    "bank_ifsc_code2"=>$change_ifsc_code,
                    "bank_branch2"=>$change_branch_name,
                    "bank_branch2"=>$change_branch_name,
                    "bank_address2"=>$change_bank_address,
                    "bank_name2"=>$bank_name,
                    "bank_code2"=>$bank_code
                  );

                    break;

                      case 'bank4':
                  $data_update=array(
                    "bank_account_holder_name3"=>$account_holder_name,
                    "bank_account_type3"=>$change_accunt_type,
                    "bank_account_number3"=>$change_account_no,
                    "bank_ifsc_code3"=>$change_ifsc_code,
                    "bank_branch3"=>$change_branch_name,
                    "bank_branch3"=>$change_branch_name,
                    "bank_address3"=>$change_bank_address,
                     "bank_name3"=>$bank_name,
                    "bank_code3"=>$bank_code
                  );
                    break;

                        case 'bank5':
                  $data_update=array(
                    "bank_account_holder_name4"=>$account_holder_name,
                    "bank_account_type4"=>$change_accunt_type,
                    "bank_account_number4"=>$change_account_no,
                    "bank_ifsc_code4"=>$change_ifsc_code,
                    "bank_branch4"=>$change_branch_name,
                    "bank_branch4"=>$change_branch_name,
                    "bank_address4"=>$change_bank_address,
                     "bank_name4"=>$bank_name,
                    "bank_code4"=>$bank_code
                  );
                    break;
                  
                    
                }
         $result_update= $this->Api_Model->updateTable("users",$data_update,array('pan'=>$pan));
         // echo $this->db->last_query();
        if($result_update)
        {
            $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
            'status' => TRUE,
            'message' => 'Data updated successfully'
            ];
        }else
        {
            $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
            'status' => FALSE,
            'data' =>''
        ];

        }

}

        }
        else
        {
            $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => FALSE,
                'message' => 'Please select mandatory data',
                'data' => array()
            ];
        }
        $this->api_response($customer_data,$http_status);

    }

			public function resendMobilePin_post()
			{
       
          $user_data = $this->input->post();
          $pan = $this->input->post('Pan'); 
          $mobile = $this->input->post('Mobile'); 

         $config_user_validation = [
         
              [
                'field' => 'Pan',
                'label' => 'Pan',
                'rules' => 'required'
            ]

        ];
        $this->form_validation->set_data($user_data);
        $this->form_validation->set_rules($config_user_validation);
        if ($this->form_validation->run() != FALSE)
        {
        $fetch_customer_details = $this->Api_Model->fetch_customer_details($pan);
      

      if(!empty($fetch_customer_details)){
            $mobile_otp=substr(str_shuffle("0123456789"), 0, 4);
            
            $Message="Hello $name, Your verification code is $mobile_otp. Any help? write to us heromf@herocorp.com";
            
                     $data_array=array(
                            "mobile_otp "=>$mobile_otp,
                            "steps_completed "=>1,
                            );
            $this->Api_Model->update_user_data($pan,$data_array);
            $email_id = $fetch_customer_details['mobile_number'];
            
            $this->load->helper("common_helper");

            if($fetch_customer_details['mobile_verified'] ==0)
            {
               sendSms($mobile, $Message);
            }

             $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => TRUE,
                'message' => 'OTP Send successfully',
                'data' => array()
            ];
}
else
{

   $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => FALSE,
                'message' => 'Data Not Match ',
                'data' => array()
            ];


}

        }
        else
        {
            $http_status = REST_Controller::HTTP_OK;
            $customer_data = [
                'status' => FALSE,
                'message' => 'Please select mandatory data',
                'data' => array()
            ];
        }
        $this->api_response($customer_data,$http_status);

    }

			function getProfile_get(){
        
    $Pan = $this->get('pan');      
    $check_data = $this->Api_Model->getProfile($Pan);
    if (!empty($check_data))
    {
                
        $http_status = REST_Controller::HTTP_OK;
        $agent_data = [
            'status' => TRUE,
            'message' => '',
            'data' => $check_data
        ];

    }else{
        $http_status = REST_Controller::HTTP_OK;
        $agent_data = [
            'status' => FALSE,
            'message' => '',
            'data' => array()
        ]; 

    }
     
    $this->api_response($agent_data,$http_status);

  }


			function VerifyPanuserData_get(){

      $Pan = $this->get('pan');      
      $this->load->helper("common_helper");
      $this->db->select('id,name,kyc,pan,mobile,email,is_active,bse_active');
      $check_data = $this->Api_Model->getRowDataFromTableWithOject('users', array('pan'=>$Pan));
      if (!empty($check_data))
      {
      if($check_data->bse_active ==1 && $check_data->kyc){  
      $mobile=$check_data->mobile;
      $mobile_otp=substr(str_shuffle("0123456789"), 0, 4);

      $name=$check_data->name;
      $data_array=array(
                        "mobile_otp "=>$mobile_otp,
                            );
      $this->Api_Model->update_user_data($Pan,$data_array);

      $Message="Hello $name, Your verification code is $mobile_otp. Any help? write to us heromf@herocorp.com";
      sendSms($mobile, $Message);

      $this->db->select('id,name,kyc,pan,mobile,email,is_active,bse_active,mobile_otp');
      $data_array = $this->Api_Model->getRowDataFromTableWithOject('users', array('pan'=>$Pan));

       $http_status = REST_Controller::HTTP_OK;
          $agent_data = [
              'status' => TRUE,
              'message' => 'OTP Send successfully',
              'data' => $data_array
          ];

      }
      else
      {
      
       $http_status = REST_Controller::HTTP_OK;
          $agent_data = [
              'status' => FALSE,
              'message' => '',
              'data' => "Pan card not kyc"
          ];


      } 

      }else{
          $http_status = REST_Controller::HTTP_OK;
          $agent_data = [
              'status' => FALSE,
              'message' => 'Pan number not register',
              'data' => array()
          ]; 

      }
     
      $this->api_response($agent_data,$http_status);
  }

		
    
}
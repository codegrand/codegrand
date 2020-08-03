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

Class Appupdate extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Api_Model');
    }

    private function api_response($data, $http_status = REST_Controller::HTTP_OK){
        header('Access-Control-Allow-Origin: *');
        $this->response($data, $http_status);
    }

 

    public function getLatestUpdateVersion_get(){
        /*Check pan number in database*/
       

        $fetch_appupdate_details = $this->Api_Model->fetch_app_update_details();
		//print_r($fetch_appupdate_details);
        if(!empty($fetch_appupdate_details)){
            $http_status = REST_Controller::HTTP_OK;
            $app_data = [
                'status' => TRUE,
                'message' => 'Fetched App Data Succesfully',
                'data' => $fetch_appupdate_details
            ];
        }
        else
        {   

            $http_status = REST_Controller::HTTP_OK;
            $app_data = [
                'status' => FALSE,
                'message' => 'No Data Found',
                'data' => ""
            ];
        }
        $this->api_response($app_data,$http_status);
    }
      
    
}
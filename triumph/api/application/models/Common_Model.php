<?php

class Common_Model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
      public function getActivePaymentMethodList($ic_id) {
        $session = $this->session->userdata();

        $special_business_partner = isset($_SESSION['special_business_partner'])?$_SESSION['special_business_partner']:0;
        
        $html = '';
         $payment_type = isset($_SESSION['access_control_dealer']['previlege_data']->dealer_details->payment_master->{$ic_id})?$_SESSION['access_control_dealer']['previlege_data']->dealer_details->payment_master->{$ic_id}:array();
         $is_muthoot = ($this->session->userdata("customer_business_name") == "Muthoot")?1:0;
         $ic_id_detail = $this->db->query("SELECT * from insurance_master_rewamp where id = $ic_id ")->row_array();
          if ($is_muthoot) {
            
            if($special_business_partner==0){
            $html .= <<<EOD
            <div class="row">
                <div class="col-md-12 text-left padLR30">
                 <a href="#cbs-popup" class="popup-with-move-anim" id="cbs_id"> <input type="radio" id="cbs_check"  name="net_bank" placeholder="0" value="cbs" class="mfp-close" style="left: 29px;">
                     </a><label style="margin-left: 2%;margin-top: 1%;">CBS</label>
                </div>
            </div>
EOD;
            }
        }

        if($ic_id_detail['is_cheque_enable']){
            if($is_muthoot && $payment_type && !in_array(2,$payment_type)){
                goto skip_check_mutjoot1;
            }
            if(isset($session['user_action_data']['product_type_id']) && ($ic_id == 6)){
                //hide for future
                 goto skip_check_mutjoot1; 
            }
            $html .= <<<EOD
                <div class="row">
                    <div class="col-md-12 text-left padLR30">
                        <input type="radio" name="net_bank" placeholder="0" value="cheque">
                        <label for="biofuel_kit" class="">Customer's Cheque</label>
                    </div>
                </div>
EOD;

        }
        skip_check_mutjoot1:

        //if($ic_id_detail['is_dealer_cheque_enable']){

        if(($ic_id == 26) && $special_business_partner==1) {
            if($is_muthoot && $payment_type && !in_array(5,$payment_type)){
                goto skip_check_mutjoot2;
            }
            if(isset($session['user_action_data']['product_type_id']) && ($session['user_action_data']['product_type_id']== 1) && ($ic_id == 6)){
                //hide for future
                 goto skip_check_mutjoot2; 
            }
            $html .= <<<EOD
                <div class="row">
                    <div class="col-md-12 text-left padLR30">
                        <input type="radio" name="net_bank" placeholder="0" value="dealer_cheque">
                        <label for="electrical" class="">Dealer's Cheque</label>
                    </div>
                </div>
EOD;

        }
        skip_check_mutjoot2:

        if($ic_id_detail['is_netbanking']){
            if($is_muthoot && $payment_type && !in_array(1,$payment_type)){
                goto skip_check_mutjoot3;
            }
            if((isset($session['user_action_data']['product_type_id'])) && ($session['user_action_data']['product_type_id']== 2) && ($ic_id == 6)){
                //hide for future
                 goto skip_check_mutjoot3; 
            }
            $html .= <<<EOD
                <div class="row">
                    <div class="col-md-12 text-left padLR30">
                          <input type="radio" name="net_bank" id="net_bankingradio" placeholder="0" value="net_banking">
                          <label for="nonelectrical" class="">Net Banking</label>
                    </div>
                </div>
EOD;

        }
        skip_check_mutjoot3:
    
        return $html;
    }

    public function ipCheck() {
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function getModelOptions($vehicle_id,$product_type_id) {
        $vehicle_result = $this->db->select('make')->where('id', $vehicle_id)->get('vehicle_master')->row_array();

        $query = "
            SELECT id,model
            FROM vehicle_master
            WHERE make = '" . $vehicle_result['make'] . "'
            AND `ex_showroom_price` IS  NOT NULL 
            AND `ex_showroom_price` >1000     
            AND product_type_id = $product_type_id 
            GROUP BY model
            ORDER BY model

        ";
        $models = $this->db->query($query)->result_array();
        $html = '<option value = "" >Select a Model</option>';
        foreach ($models as $model) {
            $html .= "<option value ='" . $model['id'] . "'>" . $model['model'] . "</option> ";
        }
        return $html;
    }

    public function getVariantOptions($vehicle_id,$product_type_id) {
        $vehicle_result = $this->db->select('make')->select('model')->where('id', $vehicle_id)->get('vehicle_master')->row_array();

         $query = "
            SELECT id,variant,cc
            FROM vehicle_master
            WHERE model = '" . $vehicle_result['model'] . "'
            AND `make`  = '" . $vehicle_result['make'] . "'
            AND `ex_showroom_price` IS  NOT NULL 
            AND `ex_showroom_price` >1000           
            AND product_type_id = $product_type_id 
            GROUP BY variant
            ORDER BY variant

        ";
        $variants = $this->db->query($query)->result_array();
        $html = '<option value = "" >Select a Variant</option>';
        foreach ($variants as $variant) {
            $html .= "<option value ='" . $variant['id'] . "'>" . $variant['variant'] . " (".$variant['cc']." cc)</option> ";
        }
        return $html;
    }

    public function getRto() {
        return $this->db->select('id')->select('label')->get('rto_master_rewamp')->result_array();
    }

    public function getProductTypeList() {
        $query = $this->db->query("SELECT * FROM product_type_rewamp ORDER BY id ASC");
        $result = $query->result_array();
        return $result;
    }
    
    public function getProductTypeHomeList() {
        $query = $this->db->query("SELECT * FROM product_type_rewamp where is_active=1 group by group_code ORDER BY id ASC");
        $result = $query->result_array();
        return $result;
    }
    
    public function getProductTypeQuotationList() {
        if(isset($_SESSION['access_control_agent']->agent_details->product_type) || isset($_SESSION['access_control_dealer']['previlege_data']->dealer_details->product_type)){
            $products_list = isset($_SESSION['access_control_agent']->agent_details->product_type) ? $_SESSION['access_control_agent']->agent_details->product_type : $_SESSION['access_control_dealer']['previlege_data']->dealer_details->product_type;
            $query = $this->db->query("SELECT * FROM product_type_rewamp where is_active=1 AND id IN ($products_list) group by group_code ORDER BY id ASC");
        
		} else{
            $query = $this->db->query("SELECT * FROM product_type_rewamp where is_active=1 group by group_code ORDER BY id ASC");
        }

        $result = $query->result_array();
        return $result;
    }

    public function getProductTypeById($id) {
        $query = $this->db->query("SELECT * FROM product_type_rewamp where id = $id");
        return $query->row();
    }

    public function getRtoLocationList() {
        $query = $this->db->query("SELECT id,code FROM rto_master_rewamp GROUP BY code ORDER BY code ASC");
        $result = $query->result();
        return $result;
    }

    public function getMakelist($product_type_id) {
        $query = $this->db->query("SELECT * FROM vehicle_make_rewamp where product_type_id = $product_type_id ORDER BY name");
        $result = $query->result_array();
        return $result;
    }
    public function getCityName($id){
       return $this->db->select('name')->from('city_master_rewamp')->where('id',$id)->get()->row_array();
    }
    public function getStateName($id){
       return $this->db->select('name')->from('state_rewamp')->where('id',$id)->get()->row_array();
    }
    public function getMakeModellist($product_type_id) {
        $query = $this->db->query("SELECT id,make_id,model_id,varient_id FROM vehicle_master_rewamp where product_type_id =$product_type_id GROUP BY model_id ORDER BY make_id");

        $result = $query->result();
        return $result;
    }

    public function vehicleInfo($make_id, $product_type_id) {

        $query = " SELECT vm.id AS vehicle_id,vm.*,vmk.`name` AS make,vml.`name` AS model,vv.`name` AS varient,vf.`name` AS fuel
                    FROM `vehicle_master_rewamp` vm 
                    LEFT JOIN `vehicle_make_rewamp` vmk ON vmk.`id` = vm.`make_id` and vmk.product_type_id = $product_type_id
                    LEFT JOIN `vehicle_model_rewamp` vml ON vml.`id` = vm.`model_id`  and vmk.product_type_id = $product_type_id
                    LEFT JOIN `vehicle_varient_rewamp` vv ON vv.`id` = vm.`varient_id` and vmk.product_type_id = $product_type_id
                    LEFT JOIN  `vehicle_fuel_rewamp` vf ON vf.`id` = vm.`fuel_id`
                    WHERE   vm.product_type_id = $product_type_id and vm.`make_id` = $make_id";
        $query = $this->db->query($query);
        $result = $query->result_array();
        return $result;
    }

    public function variantInfo() {
        $vehicle_id = $this->input->post("id");
        $product_type_id = $this->input->post("product_type_id");
        $query = "SELECT make_id,model_id FROM `vehicle_master_rewamp` WHERE `id`= $vehicle_id";
        $query = $this->db->query($query);
        $result = $query->row();
        $make = $result->make;
        $model = $result->model;
        $query = "  SELECT vehicle_master_rewamp.* , `vehicle_fuel_rewamp`.name as fuel 
                    FROM vehicle_master_rewamp
                    LEFT JOIN vehicle_fuel_rewamp ON `vehicle_master_rewamp`.fuel_id = `vehicle_fuel_rewamp`.id
                    WHERE `vehicle_master_rewamp`.make_id= '$make' 
                    and `vehicle_master_rewamp`.model_id='$model' 
                    and `vehicle_master_rewamp`.`product_type_id`=$product_type_id 
                    order by `vehicle_master_rewamp`.varient_id";

        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }

    public function getInsuranceCompanyDetails() {
        $this->db->order_by("InsuranceCompany_Name", "ASC");
        $query = $this->db->get("insurancecompanymaster");
        $result = $query->result();
        return $result;
    }

    



    

    public function getCountry() {
        $query = $this->db->get("country_master_rewamp");
        $result = $query->result();
        return $result;
    }

    public function getCountriesIn($country = null) {
        $query = $this->db->where_in('id', $country)
            ->get('country_master_rewamp')
            ->result();
        return $query;
    }

    public function get_bike_model() {
        $query = $this->db->query("SELECT * FROM vehicle_master_new where vehicle_type_code = 'MOT-PRD-002' GROUP BY vehicle_model ORDER BY vehicle_manufacturer ASC");
        $result = $query->result();
        return $result;
    }

    public function shriram_getRtos() {
        $query = $this->db->query("SELECT `RTO_Master_ID`, `RTO_Code`, `CityName` FROM `rto_master` order by `RTO_Code`");
        $result = $query->result();
        return $result;
    }

    public function gethometypevalue() {
        $proposal_list_rewamp = $this->db->query("SELECT * FROM home_type_plan");
        $proposal_list_rewamp_result = $proposal_list_rewamp->result();
        return $proposal_list_rewamp_result;
    }

    public function getpolicytenurevalue() {
        $home_tenure = $this->db->query("select DISTINCT (year)as year from home_long_term_perils_content order by year");
        $home_tenure_result = $home_tenure->result();
        return $home_tenure_result;
    }

    public function getVehicleDetails($vehicle_master_id) {
        if ($vehicle_master_id) {
            $this->db->where("id", $vehicle_master_id);
            $vehicle_details = $this->db->get("giib_vehicle_master");
            $vehicle_details = $vehicle_details->row();
            return $vehicle_details;
        } else {
            return false;
        }
    }

    public function getRtoDetails($rto_master_id) {
        if ($rto_master_id) {
            $this->db->where("RTO_Master_ID", $rto_master_id);
            $rto_details = $this->db->get("rto_master");
            $rto_details = $rto_details->row();
            return $rto_details;
        } else {
            return false;
        }
    }

    function get_vehicle_detail_from_vehicle_varient_name($vehicle_varient_name) {
        $query = "SELECT * FROM vehicle_master_new where vehicle_variant = '$vehicle_varient_name'";
        $result = $this->db->query($query);
        $result = $result->result();
        return $result;
    }

    public function get_single_rto_data($rto_code) {
        $query = "select * from rto_master WHERE RTO_Master_ID = $rto_code";
        $result = $this->db->query($query);
        $result = $result->row();
        return $result;
    }

    public function getMake() {
        $query = $this->db->query("SELECT * FROM giib_vehicle_master GROUP BY `giib_model` ORDER BY `manufacturer` ASC");
        $result = $query->result();
        return $result;
    }

    public function get_single_varient($ModelID, $varientID) {
        $query = "SELECT * FROM vehicle_master WHERE vehicle_manufacturer_code ='$ModelID' and id = '$varientID'";
        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }

    public function get_city() {
        $StateName = $this->input->post("StateName");
        $query = "SELECT StateCode FROM `state` WHERE `StateName`='$StateName'";
        $execute = $this->db->query($query);
        $result = $execute->row();
        $state_code = $result->StateCode;

        $query1 = "SELECT * from `city` WHERE `StateID`='$state_code' order by `CityName`";
        $execute1 = $this->db->query($query1);
        $result1 = $execute1->result();
        return $result1;
    }

    public function get_seating_capacity() {
        $varientID = $this->input->post("varientID");
        $query = "SELECT * FROM `vehicle_master` WHERE `id`='$varientID'";
        $execute = $this->db->query($query);
        $result = $execute->row();
        return $result;
    }

    public function fuel_info() {
        $varientID = $this->input->post("fuletextbox_id");
        $query = "SELECT * FROM `giib_vehicle_master` WHERE `id`=$varientID";
        $query = $this->db->query($query);
        $result = $query->row();
        return $result;
    }

    public function bike_fuel_info() {
        $varientID = $this->input->post("bikefuletextbox_id");

        $query = "SELECT * FROM `vehicle_master_new` WHERE `vehicle_code`='$varientID'";
        $query = $this->db->query($query);
        $result = $query->row();
        return $result;
    }

    public function get_pos_detail_with_breaking_detail($pos_id, $pos_product_id) {

        $query = "SELECT * FROM sriram_proposals_list
        inner join insurance_breakin_mng on (sriram_proposals_list.agent_id = insurance_breakin_mng.pos_mem_id)
        inner join breakin_category on (breakin_category.breakin_id = insurance_breakin_mng.id)
        where sriram_proposals_list.agent_id = '$pos_id' and agent_product_id = '$pos_product_id'";
        $result = $this->db->query($query);
        $result = $result->row();
        return $result;
    }

    public function get_gst_details($rtoid, $state, $ic_id) {
        $query = "SELECT * from rto_master where `RTO_Master_ID` = $rtoid";
        $result = $this->db->query($query);
        $result = $result->row();
        $query1 = "SELECT * from city where CityCode = " . $result->CityCode;
        $result1 = $this->db->query($query1);
        $result1 = $result1->row();
        $query2 = "SELECT * from state where `StateCode` = $result1->StateID";
        $result2 = $this->db->query($query2);
        $result2 = $result2->row();
        $query3 = 'SELECT * from seller_office where `ic_id` = "' . $ic_id . '" and `state_name` = "' . $result2->StateName . '"';
        $result3 = $this->db->query($query3);
        $result3 = $result3->row();

        return $result3;
    }

    public function get_insurance_state($state_code_id) {
        $query2 = "SELECT StateName from state where `State_Shortcode` = '$state_code_id'";
        $result2 = $this->db->query($query2);
        $result2 = $result2->row();
        return $result2;
    }

    public function get_insurance_city($city_code_id) {
        $query1 = "SELECT CityName from city where CityID = " . $city_code_id;
        $result1 = $this->db->query($query1);
        $result1 = $result1->row();
        return $result1;
    }

    public function get_pos_details($pos_id) {
        $query1 = "SELECT * from pos_members where reg_id = " . $pos_id;
        $result1 = $this->db->query($query1);
        $result1 = $result1->row();
        return $result1;
    }

    public function bike_gst_details($ic_id, $state_code) {
        $query3 = 'SELECT * from seller_office where `ic_id` = "' . $ic_id . '" and `state_short_code` = "' . $state_code . '"';
        $result3 = $this->db->query($query3);
        $result3 = $result3->row();
        return $result3;
    }

    public function get_sriram_proposals_list($proposal_id) {
        $query = "select policy_buyer_details.*,sriram_proposals_list.* from policy_buyer_details  INNER JOIN sriram_proposals_list ON sriram_proposals_list.policy_buyer_detail_id=policy_buyer_details.id WHERE sriram_proposals_list.id = '$proposal_id'";
        $result = $this->db->query($query);
        $result = $result->row();
        return $result;
    }

    public function get_sriram_proposals_list_bike($proposal_id) {
        $query = "select bike_policy_buyer_details.*,sriram_proposals_list.* from bike_policy_buyer_details  INNER JOIN sriram_proposals_list ON sriram_proposals_list.policy_buyer_detail_id= bike_policy_buyer_details.id WHERE sriram_proposals_list.id = '$proposal_id'";

        $result = $this->db->query($query);
        $result = $result->row();
        return $result;
    }

    public function get_future_proposals_list($proposal_id) {
        $query = "select policy_buyer_details.*,future_generali_proposals_list.* from policy_buyer_details  INNER JOIN future_generali_proposals_list ON future_generali_proposals_list.policy_buyer_detail_id=policy_buyer_details.id WHERE future_generali_proposals_list.id = '$proposal_id'";
        $result = $this->db->query($query);
        $result = $result->row();
        return $result;
    }

    public function get_hdfc_ergo_proposals_list($proposal_id) {
        $query = "select policy_buyer_details.*,hdfc_ergo_proposals_list.* from policy_buyer_details  INNER JOIN hdfc_ergo_proposals_list ON hdfc_ergo_proposals_list.policy_buyer_detail_id=policy_buyer_details.id WHERE hdfc_ergo_proposals_list.id = '$proposal_id'";
        $result = $this->db->query($query);
        $result = $result->row();
        return $result;
    }

    public function get_tata_aig_proposals_list($proposal_id) {
        $query = "select policy_buyer_details.*,tata_aig_proposals_list.* from policy_buyer_details  INNER JOIN tata_aig_proposals_list ON tata_aig_proposals_list.policy_buyer_detail_id=policy_buyer_details.id WHERE `tata_aig_proposals_list`.proposal_no = '$proposal_id'";

        $result = $this->db->query($query);
        $result = $result->row();
        return $result;
    }

    public function get_bharti_axa_proposal_list($proposal_id) {
        $query = "select policy_buyer_details.*,bharti_axa_proposals_list.* from policy_buyer_details  INNER JOIN bharti_axa_proposals_list ON bharti_axa_proposals_list.policy_buyer_detail_id=policy_buyer_details.id WHERE bharti_axa_proposals_list.id = '$proposal_id'";
        $result = $this->db->query($query);
        $result = $result->row();
        return $result;
    }

    public function get_bike_bharti_axa_proposal_list($proposal_id) {
        $query = "select bike_policy_buyer_details.*,bharti_axa_proposals_list.* from bike_policy_buyer_details  INNER JOIN bharti_axa_proposals_list ON bharti_axa_proposals_list.policy_buyer_detail_id=bike_policy_buyer_details.id WHERE bharti_axa_proposals_list.id = '$proposal_id'";
        $result = $this->db->query($query);
        $result = $result->row();
        return $result;
    }

    public function get_bike_hdfc_ergo_proposal_list($proposal_id) {
        $query = "select bike_policy_buyer_details.*,hdfc_ergo_proposals_list.* from bike_policy_buyer_details  INNER JOIN hdfc_ergo_proposals_list ON hdfc_ergo_proposals_list.policy_buyer_detail_id=hdfc_ergo_proposals_list.id WHERE hdfc_ergo_proposals_list.id = '$proposal_id'";
        $result = $this->db->query($query);
        $result = $result->row();
        return $result;
    }

    public function single_private_car_special_discounts($ic_code, $model, $varient, $state, $vehicle_code) {
        if ($ic_code == 23) {
            $query = "SELECT * FROM privatecar_special_discounts where ic_id='$ic_code' and model_variant ='$varient' and main_variant='$model' and state='$state' and vehicle_code='$vehicle_code'";
            $result = $this->db->query($query);
            $result = $result->row();
        } elseif ($ic_code == 25) {

            $query2 = "SELECT * from state where `State_Shortcode` = '$state'";
            $result2 = $this->db->query($query2);
            $result2 = $result2->row();

            $query3 = "SELECT * from vehicle_master_new where `vehicle_code` = '$vehicle_code'";


            $result3 = $this->db->query($query3);
            $result3 = $result3->row();

            $query5 = "SELECT * from tagic_discount_grid where `model` = '$result3->vehicle_model' and `state_name`= '$result2->StateName' and `fuel_type`= '$result3->vehicle_fuel_type'";
            $this->db->where("model", $result3->vehicle_model);
            $this->db->where("state_name", $result2->StateName);
            $this->db->where("fuel_type", $result3->vehicle_fuel_type);
            $counter = $this->db->count_all_results('tagic_discount_grid');


            if ($counter == 0) {
                $this->db->where("model", $result3->vehicle_model);
                $this->db->where("state_name", $result2->StateName);
                $this->db->where("fuel_type", "common");
                $discount_grid_query = $this->db->get("tagic_discount_grid");
                $this->db->where("model", $result3->vehicle_model);
                $this->db->where("state_name", $result2->StateName);
                $this->db->where("fuel_type", "common");

                $counter = $this->db->count_all_results('tagic_discount_grid');

                $discount_grid = $discount_grid_query->row();
            } elseif ($counter == 1) {
                $this->db->where("model", $result3->vehicle_model);
                $this->db->where("state_name", $result2->StateName);
                $this->db->where("fuel_type", $result3->vehicle_fuel_type);
                $discount_grid_query = $this->db->get("tagic_discount_grid");
                $this->db->where("model", $result3->vehicle_model);
                $this->db->where("state_name", $result2->StateName);
                $this->db->where("fuel_type", $result3->vehicle_fuel_type);

                $counter = $this->db->count_all_results('tagic_discount_grid');

                $discount_grid = $discount_grid_query->row();
            } elseif ($counter > 1) {
                $discount_rate = 0;
            }


            if ($counter == 1) {


                if ($year_difference <= 1) {
                    $discount_rate = $discount_grid->from_0_to_2;
                }
                if ($year_difference > 1 and $year_difference <= 5) {
                    $discount_rate = $discount_grid->from_3_to_5;
                }
                if ($year_difference > 5 and $year_difference <= 8) {
                    $discount_rate = $discount_grid->from_6_to_7;
                }
                if ($year_difference > 8) {
                    $discount_rate = $discount_grid->from_8_to_infinity;
                }



                if ($discount_rate > 0) {
                    $discount_rate = $discount_rate * 100;
                } else {
                    $discount_rate = 0;
                }
            }


            $result = $discount_rate;
        }
        return $result;
    }

    public function bike_selected_addon_list($selected_addon_list) {
        $implode = str_replace(",", "','", $selected_addon_list);
        $query = "SELECT * FROM `bike_addons_ic` WHERE `bike_addons_ic_id` IN ('$implode')";
        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }

    public function get_previous_insurer_detail($id) {
        $query = "SELECT * FROM insurancecompanymaster WHERE InsuranceCompanyID='$id'";
        $query = $this->db->query($query);
        $result = $query->result();
        return $result;
    }

    public function get_proposal_list($id) {
        $query = $this->db->query("SELECT * FROM proposal_list WHERE `id`='$id'");
        $result = $query->result();
        return $result;
    }

    public function bike_get_policy_buyer_details($id) {
        $query = $this->db->query("SELECT * FROM bike_policy_buyer_details WHERE `id`='$id'");
        $result = $query->result();
        return $result;
    }

    public function get_bike_policy_buyer_details($id) {
        $query = $this->db->query("SELECT * FROM bike_policy_buyer_details WHERE `id`='$id'");
        $result = $query->result();
        return $result;
    }

    public function get_makemodelvarient($vid) {
        $query = $this->db->query("SELECT * FROM vehicle_master WHERE `id`='$vid'");
        $result = $query->result();
        return $result;
    }

    public function get_giib_vehicle_master_varient($vid) {
        $query = $this->db->query("SELECT * FROM giib_vehicle_master WHERE `giib_varient`='$vid'");
        $result = $query->result();
        return $result;
    }

    public function get_rto_pdf($rtoid) {
        $query = $this->db->query("SELECT * FROM rto_master WHERE `RTO_Master_ID`='$rtoid'");
        $result = $query->result();
        return $result;
    }

    public function get_policy_details($id) {
        $query = $this->db->query("SELECT * FROM policy_details WHERE `policy_buyer_details_id`='$id'");
        $result = $query->result();
        return $result;
    }

    public function get_bike_policy_details($id) {
        $query = $this->db->query("SELECT * FROM bike_policy_details WHERE `policy_buyer_details_id`='$id'");
        $result = $query->result();
        return $result;
    }

    public function get_single_policy_details($id) {
        $query = $this->db->query("SELECT  * from proposal_list where id =$id");
        $result = $query->result();
        return $result;
    }

    public function bike_get_single_policy_details_for_pdf($id) {
        $query = $this->db->query("SELECT * FROM bike_policy_details WHERE policy_buyer_details_id='$id'");
        $result = $query->row();
        return $result;
    }

    public function get_single_policy_details_for_pdf($id) {
        $query = $this->db->query("SELECT * FROM policy_details WHERE policy_buyer_details_id='$id'");
        $result = $query->row();
        return $result;
    }

    public function get_bike_single_policy_details_for_pdf($id) {
        $query = $this->db->query("SELECT * FROM bike_policy_details WHERE policy_buyer_details_id='$id'");
        $result = $query->row();
        return $result;
    }

    public function bike_get_proposal_for_pdf($id, $ic_id) {
        if ($ic_id == 23) {
            $query = $this->db->query("SELECT * FROM sriram_proposals_list WHERE policy_buyer_detail_id='$id' and product_type='Two_Wheeler' and proposal_status='Saved'");
            $result = $query->row();
        } else if ($ic_id == 25) {
            $query = $this->db->query("SELECT * FROM tata_aig_proposals_list WHERE policy_buyer_detail_id='$id' and product_type='Two_Wheeler' and proposal_status='Saved'");
            $result = $query->row();
        } else if ($ic_id == 8) {
            $query = $this->db->query("SELECT * FROM hdfc_ergo_proposals_list WHERE policy_buyer_detail_id='$id' and product_type='Two_Wheeler' and proposal_status='Saved'");
            $result = $query->row();
        } else if ($ic_id == 3) {
            $query = $this->db->query("SELECT * FROM bharti_axa_proposals_list WHERE policy_buyer_detail_id='$id' and product_type='Two_Wheeler' and proposal_status='Saved'");
            $result = $query->row();
        }
        return $result;
    }

    public function bike_get_policy_for_pdf($id, $ic_id) {
        if ($ic_id == 23) {
            $query = $this->db->query("SELECT * FROM sriram_proposals_list WHERE policy_buyer_detail_id='$id' and product_type='Two_Wheeler' and proposal_status='Saved'");
            $result = $query->row();
        } else if ($ic_id == 25) {
            $query = $this->db->query("SELECT * FROM tata_aig_proposals_list WHERE policy_buyer_detail_id='$id' and product_type='Two_Wheeler' and proposal_status='Saved'");
            $result = $query->row();
        } else if ($ic_id == 8) {
            $query = $this->db->query("SELECT * FROM hdfc_ergo_proposals_list WHERE policy_buyer_detail_id='$id' and product_type='Two_Wheeler' and proposal_status='Saved'");
            $result = $query->row();
        } else if ($ic_id == 3) {
            $query = $this->db->query("SELECT * FROM bharti_axa_proposals_list WHERE policy_buyer_detail_id='$id' and product_type='Two_Wheeler' and proposal_status='Saved'");
            $result = $query->row();
        }
        return $result;
    }

    public function bike_get_proposal_approved_for_pdf($id, $ic_id) {
        if ($ic_id == 23) {
            $query = $this->db->query("SELECT * FROM sriram_proposals_list WHERE policy_buyer_detail_id='$id' and product_type='Two_Wheeler' and proposal_status='Approved'");
            $result = $query->row();
        }
        if ($ic_id == 25) {
            $query = $this->db->query("SELECT * FROM tata_aig_proposals_list WHERE policy_buyer_detail_id='$id' and product_type='Two_Wheeler' and proposal_status='Approved'");
            $result = $query->row();
        }
        if ($ic_id == 3) {
            $query = $this->db->query("SELECT * FROM bharti_axa_proposals_list WHERE policy_buyer_detail_id='$id' and product_type='Two_Wheeler' and proposal_status='Approved'");
            $result = $query->row();
        }

        return $result;
    }

    public function get_proposal_for_pdf($id, $ic_id) {
        if ($ic_id == 23) {
            $query = $this->db->query("SELECT * FROM sriram_proposals_list WHERE policy_buyer_detail_id='$id'");
            $result = $query->row();
        }
        if ($ic_id == 25) {
            $query = $this->db->query("SELECT * FROM tata_aig_proposals_list WHERE policy_buyer_detail_id='$id'");
            $result = $query->row();
        }
        if ($ic_id == 3) {
            $query = $this->db->query("SELECT * FROM bharti_axa_proposals_list WHERE policy_buyer_detail_id='$id'");
            $result = $query->row();
        }
        if ($ic_id == 6) {
            $query = $this->db->query("SELECT * FROM future_generali_proposals_list WHERE policy_buyer_detail_id='$id'");
            $result = $query->row();
        }
        if ($ic_id == 8) {
            $query = $this->db->query("SELECT * FROM  hdfc_ergo_proposals_list WHERE policy_buyer_detail_id='$id'");
            $result = $query->row();
        }
        return $result;
    }

    public function buy_travel_policy_now() {
        print_r($_POST);
        exit;
    }

    public function download_csv($type, $ic_id) {
        if ($ic_id == 3) {
            $table = "bharti_axa_proposals_list";
        }
        if ($ic_id == 25) {
            $table = "tata_aig_axa_proposals_list";
        }

        $this->db->where("proposal_status", "Approved");
        $this->db->where_in("product_type", $type);
        $query = $this->db->get($table);
        $result = $query->result();
        return $result;
    }

    public function get_query() {
        $query = "SELECT * FROM `bharti_axa_proposals_list`";
        return $query;
    }

    public function getCommondata($select_what, $table_name, $where_condition = '') {

        if ($where_condition) {

            $result = $this->db->query("select $select_what from $table_name where $where_condition");
        } else {

            $result = $this->db->query("select $select_what from $table_name ");
        }
        return $result->result_array();
    }

    public function getNcbPercentage($ncb_text) {
        switch ($ncb_text) {
            case 0:
                $current_ncb = 20;
                break;

            case 20:
                $current_ncb = 25;
                break;

            case 25:
                $current_ncb = 35;
                break;

            case 35:
                $current_ncb = 45;
                break;

            case 45:
                $current_ncb = 50;
                break;

            case 50:
                $current_ncb = 50;
                break;
            default:
                $current_ncb = 0;
                break;
        }
        return $current_ncb;
    }

    public function getSingleVehicleIcDetails($ic_id, $vehicle_id) {
        $query = $this->db->query("SELECT * FROM `vehicle_ic_master_rewamp` WHERE ic_id = $ic_id AND vehicle_id = $vehicle_id");
        $result = $query->row_array();
        return $result;
    }

    public function getRtoDetail($rto_id) {

        $query = $this->db->query("SELECT rm.*,zt.`code` AS zone_name,zt.`description` AS zone_description,ct.`name` AS city_name,st.`code` AS state_code,st.`name` AS state_name, st.`region` FROM rto_master_rewamp rm  LEFT JOIN `rto_zone_type_rewamp` zt ON zt.`id`= rm.`zone_type_id` LEFT JOIN city_master_rewamp ct ON ct.`id`= rm.`city_id` LEFT JOIN `state_rewamp` st ON st.`id`= rm.`state_id` WHERE rm.id = '$rto_id' ");

        //$this->db->last_query();
        $result = $query->row_array();

        return $result;
    }

    public function getIdvFormulaDetail($product_type_id, $vehicle_total_months) {
        $query = $this->db->query("SELECT * FROM idv_calculator_rewamp where product_type_id = $product_type_id AND month_start <=$vehicle_total_months AND month_end >=$vehicle_total_months ORDER BY  month_end DESC LIMIT 1");
        //echo $this->db->last_query();
        $result = $query->row_array();
        return $result;
    }

    public function getBasicOdPercFormula($product_type_id, $vehicle_cc, $vehicle_zone_type_id,$tenure) {
        $query_capacity = $this->db->query("SELECT * FROM capacity_rewamp where product_type_id = $product_type_id and cc_lower<= $vehicle_cc  and  cc_higher>=$vehicle_cc  ORDER BY  cc_higher DESC LIMIT 1");
        $result_capacity = $query_capacity->row_array();
     
        $capacity_id = $result_capacity['id'];
//echo "SELECT * FROM basic_od_rate_calulator_rewamp where `product_type_id` = '$product_type_id' and `zone_type_id` = '$vehicle_zone_type_id'  and  `capacity_id` ='$capacity_id' AND `tenure` = '$tenure'";        
        $query = $this->db->query("SELECT * FROM basic_od_rate_calulator_rewamp where `product_type_id` = '$product_type_id' and `zone_type_id` = '$vehicle_zone_type_id'  and  `capacity_id` ='$capacity_id' AND `tenure` = '$tenure'");
            
        $result = $query->row_array();
        // echo $this->db->last_query();die();

        return $result;
    }



    public function getBasicOdPercFormulaBus($product_type_id, $vehicle_cc, $vehicle_zone_type_id,$tenure) {
        
        $query_capacity = $this->db->query("SELECT * FROM capacity_rewamp where product_type_id = $product_type_id and cc_lower<= $vehicle_cc  and  cc_higher>=$vehicle_cc  ORDER BY  cc_higher DESC LIMIT 1");
        $result_capacity = $query_capacity->row_array();
     
        $capacity_id = $result_capacity['id'];
        $query = $this->db->query("SELECT * FROM basic_od_rate_calulator_rewamp where `product_type_id` = '$product_type_id' and `zone_type_id` = '$vehicle_zone_type_id'  and  `capacity_id` ='$capacity_id' AND `tenure` = '$tenure'");
            
        $result = $query->result_array();
        //echo $this->db->last_query();die();

        return $result;
    }

    public function getBasicOdPercFormulaMisd($product_type_id, $vehicle_cc, $vehicle_zone_type_id,$tenure) {
        
        $query = $this->db->query("SELECT * FROM basic_od_rate_calulator_rewamp where `product_type_id` = '$product_type_id' and `zone_type_id` = '$vehicle_zone_type_id' AND `tenure` = '$tenure'");
            
        $result = $query->row_array();
        // echo $this->db->last_query();die();

        return $result;
    }

    public function getBasicOdPercFormulaThreeWheelerPCCV($product_type_id, $vehicle_cc, $vehicle_zone_type_id,$tenure) {
        
        $query_capacity = $this->db->query("SELECT * FROM capacity_rewamp where product_type_id = $product_type_id and cc_lower<= $vehicle_cc  and  cc_higher>=$vehicle_cc  ORDER BY  cc_higher DESC LIMIT 1");
        $result_capacity = $query_capacity->row_array();
     
        $capacity_id = $result_capacity['id'];
        $query = $this->db->query("SELECT * FROM basic_od_rate_calulator_rewamp where `product_type_id` = '$product_type_id' and `zone_type_id` = '$vehicle_zone_type_id'  and  `capacity_id` ='$capacity_id' AND `tenure` = '$tenure'");
            
        $result = $query->row_array();

        return $result;
    }

    public function getVehicleDetail($vehicle_id) {

        $query = "SELECT * FROM vehicle_master WHERE id = $vehicle_id";

        $query = $this->db->query($query);
        $result = $query->row_array();
        return $result;
    }

    public function getFilteredIcList($vehicle_id, $product_type_id) {
        $instance_type = $this->config->item('instance_type');
        $filtered_ic_list = array();

        if(isset($_SESSION['access_control_agent']->agent_details->ic_id) || isset($_SESSION['access_control_dealer']['previlege_data']->dealer_details->ic_id)){
                $ic_list = isset($_SESSION['access_control_agent']->agent_details->ic_id) ? $_SESSION['access_control_agent']->agent_details->ic_id : $_SESSION['access_control_dealer']['previlege_data']->dealer_details->ic_id;
                $privilege_query = ' AND ms.`ic_id` IN ('.$ic_list.')';
            } else{
                $privilege_query = '';
            }      

        if ($product_type_id > 0 && $product_type_id < 16) {
            
            $filtered_ic_list = $this->db->query('SELECT ms.`ic_id` FROM vehicle_master_source vms 
            LEFT JOIN master_source ms ON ms.id = vms.`master_source_id`
            WHERE vehicle_master_id = ' . $vehicle_id . ' AND ms.`product_type_id` = ' . $product_type_id . ' AND ms.`ic_id` IS NOT NULL '.$privilege_query.'')->result_array();
            $filtered_ic_list = array_column($filtered_ic_list, 'ic_id');
        } else {
            switch ($product_type_id) {
                case 16:
                case 17:
                case 18:
                case 19:
                    # code...
                    break;
                default:
                    # code...
                    break;
            }
        }
     
        $active_ic_list = $this->db->query('SELECT ic_id FROM instance_type_rewamp it  LEFT JOIN `insurance_instance_rewamp` ii ON ii.`instance_type_id` = it.`id` WHERE `code` = "' . $instance_type . '" AND ii.`product_type_id` = ' . $product_type_id . ' AND ii.`is_active` = 1
            ')->result_array();

        // $inactive_ic_list = $this->db->query('SELECT ic_id FROM instance_type_rewamp it  LEFT JOIN `insurance_instance_rewamp` ii ON ii.`instance_type_id` = it.`id` WHERE `code` = "'.$instance_type.'" AND ii.`product_type_id` = '.$product_type_id.' AND ii.`is_active` = 0
        //     ')->result_array();
       
        $active_ic_list = array_column($active_ic_list, 'ic_id');
        return array_intersect($active_ic_list, $filtered_ic_list);
    }

    public function setVehicleSessionParam($product_type_obj) {
        $current_ncb = 0;
        $is_ncb_available = false;
        $is_available_to_buy = true;
        $is_breakin = false;
        $is_new_vehicle = false;
        $tenure = 1;
        $mpn_data = array();
        $previous_policy_expiry_date_array = array();
        $current_datetime = getDateArray();
        $current_date = date_create($current_datetime['date_format2']);
		
        $policy_start_date = date('d/m/Y H:i:s');
        $breakin_date_diff = '';
        $user_action_data = $this->session->userdata('user_action_data');
        extract($user_action_data);
        $rto_detail = $this->getRtoDetail($rto);
        $vehicle_detail = $this->getVehicleDetail($variant);
        $vehicle_mfg_date = getDateArray($user_action_data['manufacturing_date']);
        if (($policy_type == "new")) {
            $is_new_vehicle = true;
            if (empty($user_action_data['manufacturing_date'])) {
                $vehicle_mfg_date = subXDays(180, $current_datetime["datetime_format2"]); // renew , add 1 day for future
            }
        }

        // break in case logic
        if ($is_new_vehicle == false) { // re-new case
            if (($user_action_data['is_previous_policy'] == 'true')) {
                $previous_policy_expiry_date_array = getDateArray($user_action_data['previous_policy_expiry_date'].' '.date("h:i:s"));
                //echo "<pre>";print_r($previous_policy_expiry_date_array);exit;
                $previous_policy_expiry_date_obj = date_create($user_action_data['previous_policy_expiry_date']);
                $breakin_date_diff = date_diff($previous_policy_expiry_date_obj, $current_date); //invert => negative days
                if ($breakin_date_diff->invert == 1) {  //future exppiry date, -days = 1
                    $date = addXDays(1, $previous_policy_expiry_date_array["datetime_format2"]);
                    //print_r($date);exit; // renew , add 1 day for future
                    $policy_start_date = $date->format('d/m/Y H:i:s');
                    if ($breakin_date_diff->days > 30) {
                        $is_available_to_buy = false;
                    }
                } else {
                    if ($breakin_date_diff->days > 0) {
                        $is_breakin = true;
                        $is_available_to_buy = false;
                    }
                }
                // NCB(no claim bonus) logic :  only for  renewal
                if (($user_action_data['is_claimed'] == 'false')) {
                    if ($breakin_date_diff->days < 90) {
                        $is_ncb_available = true;
                    }
                }
            } else {
                $is_breakin = true;
                $is_available_to_buy = false;
            }
        }

        if ($is_ncb_available) {
            $current_ncb_text = ($user_action_data['previous_policy_ncb']) ? $user_action_data['previous_policy_ncb'] : 0;
            $current_ncb = $this->getNcbPercentage($current_ncb_text);
        }




        //$depreciation_percentage : no of mponth
        // IDV Calculation : depends on zone,vehicl age,CC and if commercial then weight
        $manufacturing_date = date_create($vehicle_mfg_date['date_format2']);
        $vehicle_date_diff = date_diff($manufacturing_date, $current_date); //invert => negative days

        $vehicle_cc = $vehicle_detail['cc'];
        $vehicle_zone_type_id = $rto_detail['zone_type_id'];
        $vehicle_age_year = ceil($vehicle_date_diff->days / 365);
        $vehicle_age_month = ceil($vehicle_date_diff->days / 30);
                
         $idv_formula_detail = $this->getIdvFormulaDetail($product_type_id, $vehicle_age_month);
         $vehicle_idv = (float) $vehicle_detail['ex_showroom_price'] * (float) $idv_formula_detail['percentage'];
        //if  new => current date will be purchase date
        //if renew => minus vehicle age from current date  2018 vehicle age: 4 years ==> 2018-4= 2014

        if (empty($purchase_invoice_date)) {
            $purchase_invoice_date_array = ($is_new_vehicle == false) ? $vehicle_mfg_date : $current_date;
        } else {
            $purchase_invoice_date_array = getDateArray($purchase_invoice_date);
        }
        $purchase_invoice_date_obj = date_create($purchase_invoice_date_array['date_format2']);
        $purchase_vehicle_date_diff = date_diff($purchase_invoice_date_obj, $current_date); //invert => negative days



        $purchase_vehicle_age_year = ceil($purchase_vehicle_date_diff->days / 365);
        $purchase_vehicle_age_month = ceil($purchase_vehicle_date_diff->days / 30);

        $purchase_idv_formula_detail = $this->getIdvFormulaDetail($product_type_id, $purchase_vehicle_age_month);
        $purchase_vehicle_idv = (float) $vehicle_detail['ex_showroom_price'] * (float) $purchase_idv_formula_detail['percentage'];
        // $vehicle_detail['ex_showroom_price'] = 1000000;
        // $od_discount = (isset($user_action_data['od_discount']) && ($user_action_data['od_discount'] != 'max')) ? $user_action_data['od_discount'] : 'max';
        // $is_od_discount_max  echo "string";die();
        $basic_od_formula = $this->getBasicOdPercFormula($product_type_id, $vehicle_cc, $vehicle_zone_type_id,$tenure);
        // echo "<pre>"; print_r($basic_od_formula);die('basic_od_formula');

        $vehicle_age_index = 'age' . $vehicle_age_year;
        $basic_od = $vehicle_idv * $basic_od_formula[$vehicle_age_index];
        $purchase_reg_date_array = getDateArray($user_action_data['purchase_invoice_date']);

        //policy start and end date

        $policy_start_date_arr = getDateArray($policy_start_date);
       
        if($user_action_data['product_type_id']==2 and  ($is_breakin == true || $is_breakin==1)  ) 
        {       
           
             if(!isset($user_action_data['previous_policy_expiry_date']))
             {
                      
                    // $previous_policy_expiry_date_array = getDateArray($user_action_data['previous_policy_expiry_date']);
                      $date = addXDays(2,  date('Y-m-d H:i:s'));
                      $policy_start_date = $date->format('d/m/Y H:i:s');
                      $policy_start_date_arr = getDateArray($policy_start_date);
             }
              else

             { 
                   
                   
                    
                    //$previous_policy_expiry_date_array1 = getDateArray($user_action_data['previous_policy_expiry_date']);
                     $date = addXDays(2,  date('Y-m-d H:i:s'));
                     $policy_start_date = $date->format('d/m/Y H:i:s');
                     $policy_start_date_arr = getDateArray($policy_start_date);
             }

            
        }


       
        $policy_end_date = addXDays(364, $policy_start_date_arr["datetime_format2"]);
        $policy_end_date_arr = getDateArray($policy_end_date->format('Y-m-d H:i:s'));

       // echo "<pre>";print_r($policy_end_date_arr);exit;

        // $session = $this->session->userdata();
        // $mpn_data = $session['mpn_data'];
        $vehicle_age = $vehicle_age_year." year " . ($vehicle_age_year? $vehicle_age_month." months":'');
        if($vehicle_age_month < 6){
            $vehicle_age_year = 0;
        }
        $mpn_data['policy_type'] = "package_policy"; //package_policy : current mpn,tppolicy:only third party
        $mpn_data['product_type_id'] = $product_type_id; //product_type_id 
        $mpn_data['is_available_to_buy'] = $is_available_to_buy;
        $mpn_data['is_breakin'] = isset($is_breakin)?$is_breakin:'';
        $mpn_data['is_new_vehicle'] = $is_new_vehicle;
        $mpn_data['policy_holder_type'] = $policy_holder_type;
        $mpn_data['rto_detail'] = $rto_detail;
        $mpn_data['vehicle_detail'] = $vehicle_detail;
        $mpn_data['current_date'] = $current_date;
        $mpn_data['vehicle_mfg_date'] = $vehicle_mfg_date;
        $mpn_data['previous_policy_expiry_date_array'] = $previous_policy_expiry_date_array;
        $mpn_data['purchase_reg_date_array'] = $purchase_reg_date_array;
        $mpn_data['breakin_date_diff'] = $breakin_date_diff;
        $mpn_data['is_ncb_available'] = $is_ncb_available;
        $mpn_data['previous_ncb'] = $user_action_data['previous_policy_ncb'];
        $mpn_data['current_ncb'] = $current_ncb;
        $mpn_data['vehicle_date_diff'] = $vehicle_date_diff;
        $mpn_data['vehicle_age_year'] = $vehicle_age_year;
        $mpn_data['vehicle_age_month'] = $vehicle_age_month;
        $mpn_data['vehicle_age'] = $vehicle_age;
        $mpn_data['vehicle_idv'] = $vehicle_idv;
        $mpn_data['vehicle_idv_nia'] = $vehicle_idv;
        $mpn_data['vehicle_min_idv'] = $vehicle_idv * 0.80;
        $mpn_data['vehicle_max_idv'] = $vehicle_idv * 1.20;
        // $mpn_data['is_od_discount_max'] = $is_od_discount_max;
        $mpn_data['basic_od'] = $basic_od;
        $mpn_data['idv_formula_detail'] = $idv_formula_detail;
        $mpn_data['basic_od_formula'] = $basic_od_formula;
        $mpn_data['purchase_vehicle_age_year'] = $purchase_vehicle_age_year;
        $mpn_data['purchase_vehicle_age_month'] = $purchase_vehicle_age_month;
        $mpn_data['purchase_idv_formula_detail'] = $purchase_idv_formula_detail;
        $mpn_data['purchase_vehicle_idv'] = $purchase_vehicle_idv;
        $mpn_data['policy_start_date'] = $policy_start_date;
        $mpn_data['policy_start_date_arr'] = $policy_start_date_arr;
        $mpn_data['policy_end_date'] = $policy_end_date;
        $mpn_data['policy_end_date_arr'] = $policy_end_date_arr;
        $mpn_data['third_party'] = '';
        $mpn_data["nildep_previous_policy"] = '';
        $mpn_data["accessories"] = $this->getAccessories();
        $mpn_data["geographical_extention"] = $this->getGeographicalExtention();
        $mpn_data["deductibles"] = $this->getDeductibles();
        $mpn_data["pa_covers"] = $this->getPaCovers();
        $mpn_data['is_third_party'] = false;
        $mpn_data["is_previous_policy_nil_dep"] = false;
        $mpn_data["is_previous_policy"] = false;
        $mpn_data["is_quote_forward"] = false;
        $mpn_data["is_proposal_view"] = false;


        // echo '<pre>';        print_r($mpn_data);//exit;
        //$session['user_action_data'] = $user_action_data;
        $session = array();
        $proposal_insert_array = array(
            'agent_id' => $this->session->userdata('customer_id'),
            'created' => date("Y-m-d H:m:s"),
            'product_type_id' => $product_type_id,
            'user_action_data' => json_encode($user_action_data),
            'quote_data' => json_encode($session),
            'proposal_status_id' => 3
        );
        $proposal_list_id = $this->insertProposalList($proposal_insert_array);
        if (!empty($proposal_list_id)) {
            $mpn_data['proposal_data']['proposal_list_id'] = $proposal_list_id;
            $session['mpn_data'] = $mpn_data;
        }
        $this->session->set_userdata($session);
        return TRUE;
    }

    public function setVehicleBusSessionParam($product_type_obj) {
        $current_ncb = 0;
        $is_ncb_available = false;
        $is_available_to_buy = true;
        $is_breakin = false;
        $is_new_vehicle = false;
        $tenure = 1;
        $mpn_data = array();
        $previous_policy_expiry_date_array = array();
        $current_datetime = getDateArray();
        $current_date = date_create($current_datetime['date_format2']);
        $policy_start_date = date('d/m/Y H:i:s');
        $breakin_date_diff = '';
        $user_action_data = $this->session->userdata('user_action_data');
        extract($user_action_data);
        $rto_detail = $this->getRtoDetail($rto);
        $vehicle_detail = $this->getVehicleDetail($variant);
        $vehicle_mfg_date = getDateArray($user_action_data['manufacturing_date']);
        if (($policy_type == "new")) {
            $is_new_vehicle = true;
            if (empty($user_action_data['manufacturing_date'])) {
                $vehicle_mfg_date = subXDays(180, $current_datetime["datetime_format2"]); // renew , add 1 day for future
            }
        }

        // break in case logic
        if ($is_new_vehicle == false) { // re-new case
            if (($user_action_data['is_previous_policy'] == 'true')) {
                $previous_policy_expiry_date_array = getDateArray($user_action_data['previous_policy_expiry_date'].' '.date("h:i:s"));
                //echo "<pre>";print_r($previous_policy_expiry_date_array);exit;
                $previous_policy_expiry_date_obj = date_create($user_action_data['previous_policy_expiry_date']);
                $breakin_date_diff = date_diff($previous_policy_expiry_date_obj, $current_date); //invert => negative days
                if ($breakin_date_diff->invert == 1) {  //future exppiry date, -days = 1
                    $date = addXDays(1, $previous_policy_expiry_date_array["datetime_format2"]);
                    //print_r($date);exit; // renew , add 1 day for future
                    $policy_start_date = $date->format('d/m/Y H:i:s');
                    if ($breakin_date_diff->days > 30) {
                        $is_available_to_buy = false;
                    }
                } else {
                    if ($breakin_date_diff->days > 0) {
                        $is_breakin = true;
                        $is_available_to_buy = false;
                    }
                }
                // NCB(no claim bonus) logic :  only for  renewal
                if (($user_action_data['is_claimed'] == 'false')) {
                    if ($breakin_date_diff->days < 90) {
                        $is_ncb_available = true;
                    }
                }
            } else {
                $is_breakin = true;
                $is_available_to_buy = false;
            }
        }

        if ($is_ncb_available) {
            $current_ncb_text = ($user_action_data['previous_policy_ncb']) ? $user_action_data['previous_policy_ncb'] : 0;
            $current_ncb = $this->getNcbPercentage($current_ncb_text);
        }




        //$depreciation_percentage : no of mponth
        // IDV Calculation : depends on zone,vehicl age,CC and if commercial then weight
        $manufacturing_date = date_create($vehicle_mfg_date['date_format2']);
        $vehicle_date_diff = date_diff($manufacturing_date, $current_date); //invert => negative days

        $vehicle_seating_capacity = $vehicle_detail['seating_capacity'];
        $vehicle_zone_type_id = $rto_detail['zone_type_id'];
        $vehicle_age_year = ceil($vehicle_date_diff->days / 365);
        $vehicle_age_month = ceil($vehicle_date_diff->days / 30);
                
         $idv_formula_detail = $this->getIdvFormulaDetail($product_type_id, $vehicle_age_month);
         $vehicle_idv = (float) $vehicle_detail['ex_showroom_price'] * (float) $idv_formula_detail['percentage'];
        //if  new => current date will be purchase date
        //if renew => minus vehicle age from current date  2018 vehicle age: 4 years ==> 2018-4= 2014

        if (empty($purchase_invoice_date)) {
            $purchase_invoice_date_array = ($is_new_vehicle == false) ? $vehicle_mfg_date : $current_date;
        } else {
            $purchase_invoice_date_array = getDateArray($purchase_invoice_date);
        }
        $purchase_invoice_date_obj = date_create($purchase_invoice_date_array['date_format2']);
        $purchase_vehicle_date_diff = date_diff($purchase_invoice_date_obj, $current_date); //invert => negative days



        $purchase_vehicle_age_year = ceil($purchase_vehicle_date_diff->days / 365);
        $purchase_vehicle_age_month = ceil($purchase_vehicle_date_diff->days / 30);

        $purchase_idv_formula_detail = $this->getIdvFormulaDetail($product_type_id, $purchase_vehicle_age_month);
        $purchase_vehicle_idv = (float) $vehicle_detail['ex_showroom_price'] * (float) $purchase_idv_formula_detail['percentage'];
        // $vehicle_detail['ex_showroom_price'] = 1000000;
        // $od_discount = (isset($user_action_data['od_discount']) && ($user_action_data['od_discount'] != 'max')) ? $user_action_data['od_discount'] : 'max';
        // $is_od_discount_max  echo "string";die();
        $basic_od_formula_details = $this->getBasicOdPercFormulaBus($product_type_id, $vehicle_seating_capacity, $vehicle_zone_type_id,$tenure);


        foreach ($basic_od_formula_details as $key => $value) {
            if($value['is_percentage']){
                $basic_od_formula = $value;
            }else{
                $basic_od_formula_amt = $value;
            }
        }
        

        $vehicle_age_index = 'age' . $vehicle_age_year;
        // echo "<pre>"; print_r($basic_od_formula[$vehicle_age_index]);die('$basic_od_formula');
        // echo "<pre>"; print_r($basic_od_formula_amt[$vehicle_age_index]);die('$basic_od_formula');
        $basic_od = ($vehicle_idv * $basic_od_formula[$vehicle_age_index]) + $basic_od_formula_amt[$vehicle_age_index];
        // print_r($basic_od);die;
        $purchase_reg_date_array = getDateArray($user_action_data['purchase_invoice_date']);

        //policy start and end date

        $policy_start_date_arr = getDateArray($policy_start_date);
       
        if($user_action_data['product_type_id']==6 and  ($is_breakin == true || $is_breakin==1)  ) 
        {       
           
             if(!isset($user_action_data['previous_policy_expiry_date']))
             {
                      
                    // $previous_policy_expiry_date_array = getDateArray($user_action_data['previous_policy_expiry_date']);
                      $date = addXDays(2,  date('Y-m-d H:i:s'));
                      $policy_start_date = $date->format('d/m/Y H:i:s');
                      $policy_start_date_arr = getDateArray($policy_start_date);
             }else{ 
                    //$previous_policy_expiry_date_array1 = getDateArray($user_action_data['previous_policy_expiry_date']);
                     $date = addXDays(2,  date('Y-m-d H:i:s'));
                     $policy_start_date = $date->format('d/m/Y H:i:s');
                     $policy_start_date_arr = getDateArray($policy_start_date);
             }
            
        }
       
        $policy_end_date = addXDays(364, $policy_start_date_arr["datetime_format2"]);
        $policy_end_date_arr = getDateArray($policy_end_date->format('Y-m-d H:i:s'));

       // echo "<pre>";print_r($policy_end_date_arr);exit;

        // $session = $this->session->userdata();
        // $mpn_data = $session['mpn_data'];
        $vehicle_age = $vehicle_age_year." year " . ($vehicle_age_year? $vehicle_age_month." months":'');
        if($vehicle_age_month < 6){
            $vehicle_age_year = 0;
        }
        $mpn_data['policy_type'] = "package_policy"; //package_policy : current mpn,tppolicy:only third party
        $mpn_data['product_type_id'] = $product_type_id; //product_type_id 
        $mpn_data['is_available_to_buy'] = $is_available_to_buy;
        $mpn_data['is_breakin'] = isset($is_breakin)?$is_breakin:'';
        $mpn_data['is_new_vehicle'] = $is_new_vehicle;
        $mpn_data['policy_holder_type'] = $policy_holder_type;
        $mpn_data['rto_detail'] = $rto_detail;
        $mpn_data['vehicle_detail'] = $vehicle_detail;
        $mpn_data['current_date'] = $current_date;
        $mpn_data['vehicle_mfg_date'] = $vehicle_mfg_date;
        $mpn_data['previous_policy_expiry_date_array'] = $previous_policy_expiry_date_array;
        $mpn_data['purchase_reg_date_array'] = $purchase_reg_date_array;
        $mpn_data['breakin_date_diff'] = $breakin_date_diff;
        $mpn_data['is_ncb_available'] = $is_ncb_available;
        $mpn_data['previous_ncb'] = $user_action_data['previous_policy_ncb'];
        $mpn_data['current_ncb'] = $current_ncb;
        $mpn_data['vehicle_date_diff'] = $vehicle_date_diff;
        $mpn_data['vehicle_age_year'] = $vehicle_age_year;
        $mpn_data['vehicle_age_month'] = $vehicle_age_month;
        $mpn_data['vehicle_age'] = $vehicle_age;
        $mpn_data['vehicle_idv'] = $vehicle_idv;
        $mpn_data['vehicle_idv_nia'] = $vehicle_idv;
        $mpn_data['vehicle_min_idv'] = $vehicle_idv * 0.80;
        $mpn_data['vehicle_max_idv'] = $vehicle_idv * 1.20;
        // $mpn_data['is_od_discount_max'] = $is_od_discount_max;
        $mpn_data['basic_od'] = $basic_od;
        $mpn_data['idv_formula_detail'] = $idv_formula_detail;
        $mpn_data['basic_od_formula'] = $basic_od_formula;
        $mpn_data['purchase_vehicle_age_year'] = $purchase_vehicle_age_year;
        $mpn_data['purchase_vehicle_age_month'] = $purchase_vehicle_age_month;
        $mpn_data['purchase_idv_formula_detail'] = $purchase_idv_formula_detail;
        $mpn_data['purchase_vehicle_idv'] = $purchase_vehicle_idv;
        $mpn_data['policy_start_date'] = $policy_start_date;
        $mpn_data['policy_start_date_arr'] = $policy_start_date_arr;
        $mpn_data['policy_end_date'] = $policy_end_date;
        $mpn_data['policy_end_date_arr'] = $policy_end_date_arr;
        $mpn_data['third_party'] = '';
        $mpn_data["nildep_previous_policy"] = '';
        $mpn_data["accessories"] = $this->getAccessories();
        $mpn_data["geographical_extention"] = $this->getGeographicalExtention();
        $mpn_data["deductibles"] = $this->getDeductibles();
        $mpn_data["pa_covers"] = $this->getPaCovers();
        $mpn_data['is_third_party'] = false;
        $mpn_data["is_previous_policy_nil_dep"] = false;
        $mpn_data["is_previous_policy"] = false;
        $mpn_data["is_quote_forward"] = false;
        $mpn_data["is_proposal_view"] = false;


        //echo '<pre>';        print_r($mpn_data);//exit;
        //$session['user_action_data'] = $user_action_data;
        $session = array();
        $proposal_insert_array = array(
            'agent_id' => $this->session->userdata('customer_id'),
            'created' => date("Y-m-d H:m:s"),
            'product_type_id' => $product_type_id,
            'user_action_data' => json_encode($user_action_data),
            'quote_data' => json_encode($session),
            'proposal_status_id' => 3
        );
        $proposal_list_id = $this->insertProposalList($proposal_insert_array);
        if (!empty($proposal_list_id)) {
            $mpn_data['proposal_data']['proposal_list_id'] = $proposal_list_id;
            $session['mpn_data'] = $mpn_data;
        }
        $this->session->set_userdata($session);
        return TRUE;
    }    

    public function setVehicleTruckSessionParam($product_type_obj) {        
        $current_ncb = 0;
        $is_ncb_available = false;
        $is_available_to_buy = true;
        $is_breakin = false;
        $is_new_vehicle = false;
        $tenure = 1;
        $mpn_data = array();
        $previous_policy_expiry_date_array = array();
        $current_datetime = getDateArray();
        $current_date = date_create($current_datetime['date_format2']);
        $policy_start_date = date('d/m/Y H:i:s');
        $breakin_date_diff = '';
        $user_action_data = $this->session->userdata('user_action_data');
        extract($user_action_data);
        $product_type_id = $product_type_obj->id;
        // echo "<pre>"; print_r($product_type_obj->id);die('product_type_obj');
        $rto_detail = $this->getRtoDetail($rto);
        $vehicle_detail = $this->getVehicleDetail($variant);
        $vehicle_mfg_date = getDateArray($user_action_data['manufacturing_date']);
        if (($policy_type == "new")) {
            $is_new_vehicle = true;
            if (empty($user_action_data['manufacturing_date'])) {
                $vehicle_mfg_date = subXDays(180, $current_datetime["datetime_format2"]); // renew , add 1 day for future
            }
        }

        // break in case logic
        if ($is_new_vehicle == false) { // re-new case
            if (($user_action_data['is_previous_policy'] == 'true')) {
                $previous_policy_expiry_date_array = getDateArray($user_action_data['previous_policy_expiry_date'].' '.date("h:i:s"));
                //echo "<pre>";print_r($previous_policy_expiry_date_array);exit;
                $previous_policy_expiry_date_obj = date_create($user_action_data['previous_policy_expiry_date']);
                $breakin_date_diff = date_diff($previous_policy_expiry_date_obj, $current_date); //invert => negative days
                if ($breakin_date_diff->invert == 1) {  //future exppiry date, -days = 1
                    $date = addXDays(1, $previous_policy_expiry_date_array["datetime_format2"]);
                    //print_r($date);exit; // renew , add 1 day for future
                    $policy_start_date = $date->format('d/m/Y H:i:s');
                    if ($breakin_date_diff->days > 30) {
                        $is_available_to_buy = false;
                    }
                } else {
                    if ($breakin_date_diff->days > 0) {
                        $is_breakin = true;
                        $is_available_to_buy = false;
                    }
                }
                // NCB(no claim bonus) logic :  only for  renewal
                if (($user_action_data['is_claimed'] == 'false')) {
                    if ($breakin_date_diff->days < 90) {
                        $is_ncb_available = true;
                    }
                }
            } else {
                $is_breakin = true;
                $is_available_to_buy = false;
            }
        }

        if ($is_ncb_available) {
            $current_ncb_text = ($user_action_data['previous_policy_ncb']) ? $user_action_data['previous_policy_ncb'] : 0;
            $current_ncb = $this->getNcbPercentage($current_ncb_text);
        }




        //$depreciation_percentage : no of mponth
        // IDV Calculation : depends on zone,vehicl age,CC and if commercial then weight
        $manufacturing_date = date_create($vehicle_mfg_date['date_format2']);
        $vehicle_date_diff = date_diff($manufacturing_date, $current_date); //invert => negative days

         // echo "<pre>"; print_r($vehicle_detail);die('gccv');
        $vehicle_gvw = $vehicle_detail['gvw'];
        $vehicle_zone_type_id = $rto_detail['zone_type_id'];
        $vehicle_age_year = ceil($vehicle_date_diff->days / 365);
        $vehicle_age_month = ceil($vehicle_date_diff->days / 30);
                
         $idv_formula_detail = $this->getIdvFormulaDetail($product_type_id, $vehicle_age_month);
         $vehicle_idv = (float) $vehicle_detail['ex_showroom_price'] * (float) $idv_formula_detail['percentage'];
        //if  new => current date will be purchase date
        //if renew => minus vehicle age from current date  2018 vehicle age: 4 years ==> 2018-4= 2014

        if (empty($purchase_invoice_date)) {
            $purchase_invoice_date_array = ($is_new_vehicle == false) ? $vehicle_mfg_date : $current_date;
        } else {
            $purchase_invoice_date_array = getDateArray($purchase_invoice_date);
        }
        $purchase_invoice_date_obj = date_create($purchase_invoice_date_array['date_format2']);
        $purchase_vehicle_date_diff = date_diff($purchase_invoice_date_obj, $current_date); //invert => negative days



        $purchase_vehicle_age_year = ceil($purchase_vehicle_date_diff->days / 365);
        $purchase_vehicle_age_month = ceil($purchase_vehicle_date_diff->days / 30);

        $purchase_idv_formula_detail = $this->getIdvFormulaDetail($product_type_id, $purchase_vehicle_age_month);
        $purchase_vehicle_idv = (float) $vehicle_detail['ex_showroom_price'] * (float) $purchase_idv_formula_detail['percentage'];
        // $vehicle_detail['ex_showroom_price'] = 1000000;
        // $od_discount = (isset($user_action_data['od_discount']) && ($user_action_data['od_discount'] != 'max')) ? $user_action_data['od_discount'] : 'max';
        // $is_od_discount_max  echo "string";die();
        $basic_od_formula = $this->getBasicOdPercFormula($product_type_id, $vehicle_gvw, $vehicle_zone_type_id,$tenure);
        // echo "<pre>"; print_r($basic_od_formula_details);die('basic_od_formula_details');

        $vehicle_age_index = 'age' . $vehicle_age_year;
        $basic_od = $vehicle_idv * $basic_od_formula[$vehicle_age_index];

        if($vehicle_gvw > 12000) {  // for gross weight greater than 12000

             $basic_od =$basic_od + ( ($vehicle_gvw  - 12000) / 100 ) * 27 ;

         }
        // echo "<pre>"; print_r($basic_od);die('$basic_od');
        // echo "<pre>"; print_r($basic_od_formula_amt[$vehicle_age_index]);die('$basic_od_formula');
        // print_r($basic_od);die;
        $purchase_reg_date_array = getDateArray($user_action_data['purchase_invoice_date']);

        //policy start and end date

        $policy_start_date_arr = getDateArray($policy_start_date);
       
        if($user_action_data['product_type_id']==4 and  ($is_breakin == true || $is_breakin==1)  ) 
        {       
           
             if(!isset($user_action_data['previous_policy_expiry_date']))
             {
                      
                    // $previous_policy_expiry_date_array = getDateArray($user_action_data['previous_policy_expiry_date']);
                      $date = addXDays(2,  date('Y-m-d H:i:s'));
                      $policy_start_date = $date->format('d/m/Y H:i:s');
                      $policy_start_date_arr = getDateArray($policy_start_date);
             }else{ 
                    //$previous_policy_expiry_date_array1 = getDateArray($user_action_data['previous_policy_expiry_date']);
                     $date = addXDays(2,  date('Y-m-d H:i:s'));
                     $policy_start_date = $date->format('d/m/Y H:i:s');
                     $policy_start_date_arr = getDateArray($policy_start_date);
             }
            
        }
       
        $policy_end_date = addXDays(364, $policy_start_date_arr["datetime_format2"]);
        $policy_end_date_arr = getDateArray($policy_end_date->format('Y-m-d H:i:s'));

       // echo "<pre>";print_r($policy_end_date_arr);exit;

        // $session = $this->session->userdata();
        // $mpn_data = $session['mpn_data'];
        $vehicle_age = $vehicle_age_year." year " . ($vehicle_age_year? $vehicle_age_month." months":'');
        if($vehicle_age_month < 6){
            $vehicle_age_year = 0;
        }

        $commercial_idv = (isset($user_action_data['commercial_idv'])?$user_action_data['commercial_idv']:'');
        if(!empty($commercial_idv)){
            $vehicle_idv = $vehicle_idv + $commercial_idv;
        }

        $mpn_data['policy_type'] = "package_policy"; //package_policy : current mpn,tppolicy:only third party
        $mpn_data['product_type_id'] = $product_type_id; //product_type_id 
        $mpn_data['is_available_to_buy'] = $is_available_to_buy;
        $mpn_data['is_breakin'] = isset($is_breakin)?$is_breakin:'';
        $mpn_data['is_new_vehicle'] = $is_new_vehicle;
        $mpn_data['policy_holder_type'] = $policy_holder_type;
        $mpn_data['rto_detail'] = $rto_detail;
        $mpn_data['vehicle_detail'] = $vehicle_detail;
        $mpn_data['current_date'] = $current_date;
        $mpn_data['vehicle_mfg_date'] = $vehicle_mfg_date;
        $mpn_data['previous_policy_expiry_date_array'] = $previous_policy_expiry_date_array;
        $mpn_data['purchase_reg_date_array'] = $purchase_reg_date_array;
        $mpn_data['breakin_date_diff'] = $breakin_date_diff;
        $mpn_data['is_ncb_available'] = $is_ncb_available;
        $mpn_data['previous_ncb'] = $user_action_data['previous_policy_ncb'];
        $mpn_data['current_ncb'] = $current_ncb;
        $mpn_data['vehicle_date_diff'] = $vehicle_date_diff;
        $mpn_data['vehicle_age_year'] = $vehicle_age_year;
        $mpn_data['vehicle_age_month'] = $vehicle_age_month;
        $mpn_data['vehicle_age'] = $vehicle_age;
        $mpn_data['vehicle_idv'] = $vehicle_idv;
        $mpn_data['vehicle_idv_nia'] = $vehicle_idv;
        $mpn_data['vehicle_min_idv'] = $vehicle_idv * 0.80;
        $mpn_data['vehicle_max_idv'] = $vehicle_idv * 1.20;
        // $mpn_data['is_od_discount_max'] = $is_od_discount_max;
        $mpn_data['basic_od'] = $basic_od;
        $mpn_data['idv_formula_detail'] = $idv_formula_detail;
        $mpn_data['basic_od_formula'] = $basic_od_formula;
        $mpn_data['purchase_vehicle_age_year'] = $purchase_vehicle_age_year;
        $mpn_data['purchase_vehicle_age_month'] = $purchase_vehicle_age_month;
        $mpn_data['purchase_idv_formula_detail'] = $purchase_idv_formula_detail;
        $mpn_data['purchase_vehicle_idv'] = $purchase_vehicle_idv;
        $mpn_data['policy_start_date'] = $policy_start_date;
        $mpn_data['policy_start_date_arr'] = $policy_start_date_arr;
        $mpn_data['policy_end_date'] = $policy_end_date;
        $mpn_data['policy_end_date_arr'] = $policy_end_date_arr;
        $mpn_data['third_party'] = '';
        $mpn_data["nildep_previous_policy"] = '';
        $mpn_data["accessories"] = $this->getAccessories();
        $mpn_data["geographical_extention"] = $this->getGeographicalExtention();
        $mpn_data["deductibles"] = $this->getDeductibles();
        $mpn_data["pa_covers"] = $this->getPaCovers();
        $mpn_data['is_third_party'] = false;
        $mpn_data["is_previous_policy_nil_dep"] = false;
        $mpn_data["is_previous_policy"] = false;
        $mpn_data["is_quote_forward"] = false;
        $mpn_data["is_proposal_view"] = false;


        //echo '<pre>';        print_r($mpn_data);//exit;
        //$session['user_action_data'] = $user_action_data;
        $session = array();
        $proposal_insert_array = array(
            'agent_id' => $this->session->userdata('customer_id'),
            'created' => date("Y-m-d H:m:s"),
            'product_type_id' => $product_type_id,
            'user_action_data' => json_encode($user_action_data),
            'quote_data' => json_encode($session),
            'proposal_status_id' => 3
        );
        $proposal_list_id = $this->insertProposalList($proposal_insert_array);
        if (!empty($proposal_list_id)) {
            $mpn_data['proposal_data']['proposal_list_id'] = $proposal_list_id;
            $session['mpn_data'] = $mpn_data;
        }
        $this->session->set_userdata($session);
        return TRUE;
    }

    public function setVehicleTrailerSessionParam($product_type_obj) {
        // echo "<pre>"; print_r($product_type_obj);die('$product_type_obj');
        $current_ncb = 0;
        $is_ncb_available = false;
        $is_available_to_buy = true;
        $is_breakin = false;
        $is_new_vehicle = false;
        $tenure = 1;
        $mpn_data = array();
        $previous_policy_expiry_date_array = array();
        $current_datetime = getDateArray();
        $current_date = date_create($current_datetime['date_format2']);
        $policy_start_date = date('d/m/Y H:i:s');
        $breakin_date_diff = '';
        $user_action_data = $this->session->userdata('user_action_data');
        extract($user_action_data);
        $product_type_id = $product_type_obj->id;
        $rto_detail = $this->getRtoDetail($rto);
        $vehicle_detail = $this->getVehicleDetail($variant);
        $vehicle_mfg_date = getDateArray($user_action_data['manufacturing_date']);
        if (($policy_type == "new")) {
            $is_new_vehicle = true;
            if (empty($user_action_data['manufacturing_date'])) {
                $vehicle_mfg_date = subXDays(180, $current_datetime["datetime_format2"]); // renew , add 1 day for future
            }
        }
        // break in case logic
        if ($is_new_vehicle == false) { // re-new case
            if (($user_action_data['is_previous_policy'] == 'true')) {
                $previous_policy_expiry_date_array = getDateArray($user_action_data['previous_policy_expiry_date'].' '.date("h:i:s"));
                //echo "<pre>";print_r($previous_policy_expiry_date_array);exit;
                $previous_policy_expiry_date_obj = date_create($user_action_data['previous_policy_expiry_date']);
                $breakin_date_diff = date_diff($previous_policy_expiry_date_obj, $current_date); //invert => negative days
                if ($breakin_date_diff->invert == 1) {  //future exppiry date, -days = 1
                    $date = addXDays(1, $previous_policy_expiry_date_array["datetime_format2"]);
                    //print_r($date);exit; // renew , add 1 day for future
                    $policy_start_date = $date->format('d/m/Y H:i:s');
                    if ($breakin_date_diff->days > 30) {
                        $is_available_to_buy = false;
                    }
                } else {
                    if ($breakin_date_diff->days > 0) {
                        $is_breakin = true;
                        $is_available_to_buy = false;
                    }
                }
                // NCB(no claim bonus) logic :  only for  renewal
                if (($user_action_data['is_claimed'] == 'false')) {
                    if ($breakin_date_diff->days < 90) {
                        $is_ncb_available = true;
                    }
                }
            } else {
                $is_breakin = true;
                $is_available_to_buy = false;
            }
        }

        if ($is_ncb_available) {
            $current_ncb_text = ($user_action_data['previous_policy_ncb']) ? $user_action_data['previous_policy_ncb'] : 0;
            $current_ncb = $this->getNcbPercentage($current_ncb_text);
        }




        //$depreciation_percentage : no of mponth
        // IDV Calculation : depends on zone,vehicl age,CC and if commercial then weight
        $manufacturing_date = date_create($vehicle_mfg_date['date_format2']);
        $vehicle_date_diff = date_diff($manufacturing_date, $current_date); //invert => negative days

         // echo "<pre>"; print_r($vehicle_detail);die('gccv');
        $vehicle_gvw = $vehicle_detail['gvw'];
        $vehicle_zone_type_id = $rto_detail['zone_type_id'];
        $vehicle_age_year = ceil($vehicle_date_diff->days / 365);
        $vehicle_age_month = ceil($vehicle_date_diff->days / 30);
                
         $idv_formula_detail = $this->getIdvFormulaDetail($product_type_id, $vehicle_age_month);
         $vehicle_idv = (float) $vehicle_detail['ex_showroom_price'] * (float) $idv_formula_detail['percentage'];
        //if  new => current date will be purchase date
        //if renew => minus vehicle age from current date  2018 vehicle age: 4 years ==> 2018-4= 2014

        if (empty($purchase_invoice_date)) {
            $purchase_invoice_date_array = ($is_new_vehicle == false) ? $vehicle_mfg_date : $current_date;
        } else {
            $purchase_invoice_date_array = getDateArray($purchase_invoice_date);
        }
        $purchase_invoice_date_obj = date_create($purchase_invoice_date_array['date_format2']);
        $purchase_vehicle_date_diff = date_diff($purchase_invoice_date_obj, $current_date); //invert => negative days



        $purchase_vehicle_age_year = ceil($purchase_vehicle_date_diff->days / 365);
        $purchase_vehicle_age_month = ceil($purchase_vehicle_date_diff->days / 30);

        $purchase_idv_formula_detail = $this->getIdvFormulaDetail($product_type_id, $purchase_vehicle_age_month);
        $purchase_vehicle_idv = (float) $vehicle_detail['ex_showroom_price'] * (float) $purchase_idv_formula_detail['percentage'];
        // $vehicle_detail['ex_showroom_price'] = 1000000;
        // $od_discount = (isset($user_action_data['od_discount']) && ($user_action_data['od_discount'] != 'max')) ? $user_action_data['od_discount'] : 'max';
        // $is_od_discount_max  echo "string";die();
        $basic_od_formula = $this->getBasicOdPercFormula($product_type_id, $vehicle_gvw, $vehicle_zone_type_id,$tenure);
        // echo "<pre>"; print_r($basic_od_formula_details);die('basic_od_formula_details');

        $vehicle_age_index = 'age' . $vehicle_age_year;
        $basic_od = $vehicle_idv * $basic_od_formula[$vehicle_age_index];

        if($vehicle_gvw > 12000) {  // for gross weight greater than 12000

             $basic_od =$basic_od + ( ($vehicle_gvw  - 12000) / 100 ) * 27 ;

         }
        // echo "<pre>"; print_r($basic_od);die('$basic_od');
        // echo "<pre>"; print_r($basic_od_formula_amt[$vehicle_age_index]);die('$basic_od_formula');
        // print_r($basic_od);die;
        $purchase_reg_date_array = getDateArray($user_action_data['purchase_invoice_date']);

        //policy start and end date

        $policy_start_date_arr = getDateArray($policy_start_date);
       
//        if($user_action_data['product_type_id']==16 and  ($is_breakin == true || $is_breakin==1)  ) 
        if($user_action_data['product_type_id']==16 || $user_action_data['product_type_id']==17 and  ($is_breakin == true || $is_breakin==1)  ) 
        {       
           
             if(!isset($user_action_data['previous_policy_expiry_date']))
             {
                      
                    // $previous_policy_expiry_date_array = getDateArray($user_action_data['previous_policy_expiry_date']);
                      $date = addXDays(2,  date('Y-m-d H:i:s'));
                      $policy_start_date = $date->format('d/m/Y H:i:s');
                      $policy_start_date_arr = getDateArray($policy_start_date);
             }else{ 
                    //$previous_policy_expiry_date_array1 = getDateArray($user_action_data['previous_policy_expiry_date']);
                     $date = addXDays(2,  date('Y-m-d H:i:s'));
                     $policy_start_date = $date->format('d/m/Y H:i:s');
                     $policy_start_date_arr = getDateArray($policy_start_date);
             }
            
        }
       
        $policy_end_date = addXDays(364, $policy_start_date_arr["datetime_format2"]);
        $policy_end_date_arr = getDateArray($policy_end_date->format('Y-m-d H:i:s'));

       // echo "<pre>";print_r($policy_end_date_arr);exit;

        // $session = $this->session->userdata();
        // $mpn_data = $session['mpn_data'];
        $vehicle_age = $vehicle_age_year." year " . ($vehicle_age_year? $vehicle_age_month." months":'');
        if($vehicle_age_month < 6){
            $vehicle_age_year = 0;
        }

        $commercial_idv = (isset($user_action_data['commercial_idv'])?$user_action_data['commercial_idv']:'');
        if(!empty($commercial_idv)){
            $vehicle_idv = $vehicle_idv + $commercial_idv;
        }

        $mpn_data['policy_type'] = "package_policy"; //package_policy : current mpn,tppolicy:only third party
        $mpn_data['product_type_id'] = $product_type_id; //product_type_id 
        $mpn_data['is_available_to_buy'] = $is_available_to_buy;
        $mpn_data['is_breakin'] = isset($is_breakin)?$is_breakin:'';
        $mpn_data['is_new_vehicle'] = $is_new_vehicle;
        $mpn_data['policy_holder_type'] = $policy_holder_type;
        $mpn_data['rto_detail'] = $rto_detail;
        $mpn_data['vehicle_detail'] = $vehicle_detail;
        $mpn_data['current_date'] = $current_date;
        $mpn_data['vehicle_mfg_date'] = $vehicle_mfg_date;
        $mpn_data['previous_policy_expiry_date_array'] = $previous_policy_expiry_date_array;
        $mpn_data['purchase_reg_date_array'] = $purchase_reg_date_array;
        $mpn_data['breakin_date_diff'] = $breakin_date_diff;
        $mpn_data['is_ncb_available'] = $is_ncb_available;
        $mpn_data['previous_ncb'] = $user_action_data['previous_policy_ncb'];
        $mpn_data['current_ncb'] = $current_ncb;
        $mpn_data['vehicle_date_diff'] = $vehicle_date_diff;
        $mpn_data['vehicle_age_year'] = $vehicle_age_year;
        $mpn_data['vehicle_age_month'] = $vehicle_age_month;
        $mpn_data['vehicle_age'] = $vehicle_age;
        $mpn_data['vehicle_idv'] = $vehicle_idv;
        $mpn_data['vehicle_idv_nia'] = $vehicle_idv;
        $mpn_data['vehicle_min_idv'] = $vehicle_idv * 0.80;
        $mpn_data['vehicle_max_idv'] = $vehicle_idv * 1.20;
        // $mpn_data['is_od_discount_max'] = $is_od_discount_max;
        $mpn_data['basic_od'] = $basic_od;
        $mpn_data['idv_formula_detail'] = $idv_formula_detail;
        $mpn_data['basic_od_formula'] = $basic_od_formula;
        $mpn_data['purchase_vehicle_age_year'] = $purchase_vehicle_age_year;
        $mpn_data['purchase_vehicle_age_month'] = $purchase_vehicle_age_month;
        $mpn_data['purchase_idv_formula_detail'] = $purchase_idv_formula_detail;
        $mpn_data['purchase_vehicle_idv'] = $purchase_vehicle_idv;
        $mpn_data['policy_start_date'] = $policy_start_date;
        $mpn_data['policy_start_date_arr'] = $policy_start_date_arr;
        $mpn_data['policy_end_date'] = $policy_end_date;
        $mpn_data['policy_end_date_arr'] = $policy_end_date_arr;
        $mpn_data['third_party'] = '';
        $mpn_data["nildep_previous_policy"] = '';
        $mpn_data["accessories"] = $this->getAccessories();
        $mpn_data["geographical_extention"] = $this->getGeographicalExtention();
        $mpn_data["deductibles"] = $this->getDeductibles();
        $mpn_data["pa_covers"] = $this->getPaCovers();
        $mpn_data['is_third_party'] = false;
        $mpn_data["is_previous_policy_nil_dep"] = false;
        $mpn_data["is_previous_policy"] = false;
        $mpn_data["is_quote_forward"] = false;
        $mpn_data["is_proposal_view"] = false;


        // echo '<pre>';        print_r($mpn_data);//exit;
        // $session['user_action_data'] = $user_action_data;
        // //die("Jaya Sahu");
        $session = array();
        $proposal_insert_array = array(
            'agent_id' => $this->session->userdata('customer_id'),
            'created' => date("Y-m-d H:m:s"),
            'product_type_id' => $product_type_id,
            'user_action_data' => json_encode($user_action_data),
            'quote_data' => json_encode($session),
            'proposal_status_id' => 3
        );
        $proposal_list_id = $this->insertProposalList($proposal_insert_array);//die($proposal_list_id);
        if (!empty($proposal_list_id)) {
            $mpn_data['proposal_data']['proposal_list_id'] = $proposal_list_id;
            $session['mpn_data'] = $mpn_data;
        }
        $this->session->set_userdata($session);
        return TRUE;
    }
    public function setVehicleMisdSessionParam($product_type_obj) {
        $current_ncb = 0;
        $is_ncb_available = false;
        $is_available_to_buy = true;
        $is_breakin = false;
        $is_new_vehicle = false;
        $tenure = 1;
        $mpn_data = array();
        $previous_policy_expiry_date_array = array();
        $current_datetime = getDateArray();
        $current_date = date_create($current_datetime['date_format2']);
        $policy_start_date = date('d/m/Y H:i:s');
        $breakin_date_diff = '';
        $user_action_data = $this->session->userdata('user_action_data');
        extract($user_action_data);
        $rto_detail = $this->getRtoDetail($rto);
        $vehicle_detail = $this->getVehicleDetail($variant);
        $vehicle_mfg_date = getDateArray($user_action_data['manufacturing_date']);
        if (($policy_type == "new")) {
            $is_new_vehicle = true;
            if (empty($user_action_data['manufacturing_date'])) {
                $vehicle_mfg_date = subXDays(180, $current_datetime["datetime_format2"]); // renew , add 1 day for future
            }
        }

        // break in case logic
        if ($is_new_vehicle == false) { // re-new case
            if (($user_action_data['is_previous_policy'] == 'true')) {
                $previous_policy_expiry_date_array = getDateArray($user_action_data['previous_policy_expiry_date'].' '.date("h:i:s"));
                //echo "<pre>";print_r($previous_policy_expiry_date_array);exit;
                $previous_policy_expiry_date_obj = date_create($user_action_data['previous_policy_expiry_date']);
                $breakin_date_diff = date_diff($previous_policy_expiry_date_obj, $current_date); //invert => negative days
                if ($breakin_date_diff->invert == 1) {  //future exppiry date, -days = 1
                    $date = addXDays(1, $previous_policy_expiry_date_array["datetime_format2"]);
                    //print_r($date);exit; // renew , add 1 day for future
                    $policy_start_date = $date->format('d/m/Y H:i:s');
                    if ($breakin_date_diff->days > 30) {
                        $is_available_to_buy = false;
                    }
                } else {
                    if ($breakin_date_diff->days > 0) {
                        $is_breakin = true;
                        $is_available_to_buy = false;
                    }
                }
                // NCB(no claim bonus) logic :  only for  renewal
                if (($user_action_data['is_claimed'] == 'false')) {
                    if ($breakin_date_diff->days < 90) {
                        $is_ncb_available = true;
                    }
                }
            } else {
                $is_breakin = true;
                $is_available_to_buy = false;
            }
        }

        if ($is_ncb_available) {
            $current_ncb_text = ($user_action_data['previous_policy_ncb']) ? $user_action_data['previous_policy_ncb'] : 0;
            $current_ncb = $this->getNcbPercentage($current_ncb_text);
        }




        //$depreciation_percentage : no of mponth
        // IDV Calculation : depends on zone,vehicl age,CC and if commercial then weight
        $manufacturing_date = date_create($vehicle_mfg_date['date_format2']);
        $vehicle_date_diff = date_diff($manufacturing_date, $current_date); //invert => negative days

        $vehicle_cc = $vehicle_detail['cc'];
        $vehicle_zone_type_id = $rto_detail['zone_type_id'];
        $vehicle_age_year = ceil($vehicle_date_diff->days / 365);
        $vehicle_age_month = ceil($vehicle_date_diff->days / 30);
                
         $idv_formula_detail = $this->getIdvFormulaDetail($product_type_id, $vehicle_age_month);
         $vehicle_idv = (float) $vehicle_detail['ex_showroom_price'] * (float) $idv_formula_detail['percentage'];
        //if  new => current date will be purchase date
        //if renew => minus vehicle age from current date  2018 vehicle age: 4 years ==> 2018-4= 2014

        if (empty($purchase_invoice_date)) {
            $purchase_invoice_date_array = ($is_new_vehicle == false) ? $vehicle_mfg_date : $current_date;
        } else {
            $purchase_invoice_date_array = getDateArray($purchase_invoice_date);
        }
        $purchase_invoice_date_obj = date_create($purchase_invoice_date_array['date_format2']);
        $purchase_vehicle_date_diff = date_diff($purchase_invoice_date_obj, $current_date); //invert => negative days



        $purchase_vehicle_age_year = ceil($purchase_vehicle_date_diff->days / 365);
        $purchase_vehicle_age_month = ceil($purchase_vehicle_date_diff->days / 30);

        $purchase_idv_formula_detail = $this->getIdvFormulaDetail($product_type_id, $purchase_vehicle_age_month);
        $purchase_vehicle_idv = (float) $vehicle_detail['ex_showroom_price'] * (float) $purchase_idv_formula_detail['percentage'];
        // $vehicle_detail['ex_showroom_price'] = 1000000;
        // $od_discount = (isset($user_action_data['od_discount']) && ($user_action_data['od_discount'] != 'max')) ? $user_action_data['od_discount'] : 'max';
        // $is_od_discount_max  echo "string";die();
        $basic_od_formula = $this->getBasicOdPercFormulaMisd($product_type_id, $vehicle_cc, $vehicle_zone_type_id,$tenure);
        // echo "<pre>"; print_r($basic_od_formula);die('basic_od_formula');

        $vehicle_age_index = 'age' . $vehicle_age_year;
        $basic_od = $vehicle_idv * $basic_od_formula[$vehicle_age_index];
        $purchase_reg_date_array = getDateArray($user_action_data['purchase_invoice_date']);

        //policy start and end date

        $policy_start_date_arr = getDateArray($policy_start_date);
       
        if($user_action_data['product_type_id']==15 and  ($is_breakin == true || $is_breakin==1)  ) 
        {       
             if(!isset($user_action_data['previous_policy_expiry_date']))
             {
                    // $previous_policy_expiry_date_array = getDateArray($user_action_data['previous_policy_expiry_date']);
                      $date = addXDays(2,  date('Y-m-d H:i:s'));
                      $policy_start_date = $date->format('d/m/Y H:i:s');
                      $policy_start_date_arr = getDateArray($policy_start_date);
             }else{ 
                    //$previous_policy_expiry_date_array1 = getDateArray($user_action_data['previous_policy_expiry_date']);
                     $date = addXDays(2,  date('Y-m-d H:i:s'));
                     $policy_start_date = $date->format('d/m/Y H:i:s');
                     $policy_start_date_arr = getDateArray($policy_start_date);
             }
        }
       
        $policy_end_date = addXDays(364, $policy_start_date_arr["datetime_format2"]);
        $policy_end_date_arr = getDateArray($policy_end_date->format('Y-m-d H:i:s'));

       // echo "<pre>";print_r($policy_end_date_arr);exit;

        // $session = $this->session->userdata();
        // $mpn_data = $session['mpn_data'];
        $vehicle_age = $vehicle_age_year." year " . ($vehicle_age_year? $vehicle_age_month." months":'');
        if($vehicle_age_month < 6){
            $vehicle_age_year = 0;
        }
        $mpn_data['policy_type'] = "package_policy"; //package_policy : current mpn,tppolicy:only third party
        $mpn_data['product_type_id'] = $product_type_id; //product_type_id 
        $mpn_data['is_available_to_buy'] = $is_available_to_buy;
        $mpn_data['is_breakin'] = isset($is_breakin)?$is_breakin:'';
        $mpn_data['is_new_vehicle'] = $is_new_vehicle;
        $mpn_data['policy_holder_type'] = $policy_holder_type;
        $mpn_data['rto_detail'] = $rto_detail;
        $mpn_data['vehicle_detail'] = $vehicle_detail;
        $mpn_data['current_date'] = $current_date;
        $mpn_data['vehicle_mfg_date'] = $vehicle_mfg_date;
        $mpn_data['previous_policy_expiry_date_array'] = $previous_policy_expiry_date_array;
        $mpn_data['purchase_reg_date_array'] = $purchase_reg_date_array;
        $mpn_data['breakin_date_diff'] = $breakin_date_diff;
        $mpn_data['is_ncb_available'] = $is_ncb_available;
        $mpn_data['previous_ncb'] = $user_action_data['previous_policy_ncb'];
        $mpn_data['current_ncb'] = $current_ncb;
        $mpn_data['vehicle_date_diff'] = $vehicle_date_diff;
        $mpn_data['vehicle_age_year'] = $vehicle_age_year;
        $mpn_data['vehicle_age_month'] = $vehicle_age_month;
        $mpn_data['vehicle_age'] = $vehicle_age;
        $mpn_data['vehicle_idv'] = $vehicle_idv;
        $mpn_data['vehicle_idv_nia'] = $vehicle_idv;
        $mpn_data['vehicle_min_idv'] = $vehicle_idv * 0.80;
        $mpn_data['vehicle_max_idv'] = $vehicle_idv * 1.20;
        // $mpn_data['is_od_discount_max'] = $is_od_discount_max;
        $mpn_data['basic_od'] = $basic_od;
        $mpn_data['idv_formula_detail'] = $idv_formula_detail;
        $mpn_data['basic_od_formula'] = $basic_od_formula;
        $mpn_data['purchase_vehicle_age_year'] = $purchase_vehicle_age_year;
        $mpn_data['purchase_vehicle_age_month'] = $purchase_vehicle_age_month;
        $mpn_data['purchase_idv_formula_detail'] = $purchase_idv_formula_detail;
        $mpn_data['purchase_vehicle_idv'] = $purchase_vehicle_idv;
        $mpn_data['policy_start_date'] = $policy_start_date;
        $mpn_data['policy_start_date_arr'] = $policy_start_date_arr;
        $mpn_data['policy_end_date'] = $policy_end_date;
        $mpn_data['policy_end_date_arr'] = $policy_end_date_arr;
        $mpn_data['third_party'] = '';
        $mpn_data["nildep_previous_policy"] = '';
        $mpn_data["accessories"] = $this->getAccessories();
        $mpn_data["geographical_extention"] = $this->getGeographicalExtention();
        $mpn_data["deductibles"] = $this->getDeductibles();
        $mpn_data["pa_covers"] = $this->getPaCovers();
        $mpn_data['is_third_party'] = false;
        $mpn_data["is_previous_policy_nil_dep"] = false;
        $mpn_data["is_previous_policy"] = false;
        $mpn_data["is_quote_forward"] = false;
        $mpn_data["is_proposal_view"] = false;


        // echo '<pre>';        print_r($mpn_data);//exit;
        //$session['user_action_data'] = $user_action_data;
        $session = array();
        $proposal_insert_array = array(
            'agent_id' => $this->session->userdata('customer_id'),
            'created' => date("Y-m-d H:m:s"),
            'product_type_id' => $product_type_id,
            'user_action_data' => json_encode($user_action_data),
            'quote_data' => json_encode($session),
            'proposal_status_id' => 3
        );
        $proposal_list_id = $this->insertProposalList($proposal_insert_array);
        if (!empty($proposal_list_id)) {
            $mpn_data['proposal_data']['proposal_list_id'] = $proposal_list_id;
            $session['mpn_data'] = $mpn_data;
        }
        $this->session->set_userdata($session);
        return TRUE;
    }

    public function setVehicleThreeWheelerPCCVSessionParam($product_type_obj) {
        $current_ncb = 0;
        $is_ncb_available = false;
        $is_available_to_buy = true;
        $is_breakin = false;
        $is_new_vehicle = false;
        $tenure = 1;
        $mpn_data = array();
        $previous_policy_expiry_date_array = array();
        $current_datetime = getDateArray();
        $current_date = date_create($current_datetime['date_format2']);
        $policy_start_date = date('d/m/Y H:i:s');
        $breakin_date_diff = '';
        $user_action_data = $this->session->userdata('user_action_data');
        extract($user_action_data);
        $rto_detail = $this->getRtoDetail($rto);
        $vehicle_detail = $this->getVehicleDetail($variant);
        $vehicle_mfg_date = getDateArray($user_action_data['manufacturing_date']);
        if (($policy_type == "new")) {
            $is_new_vehicle = true;
            if (empty($user_action_data['manufacturing_date'])) {
                $vehicle_mfg_date = subXDays(180, $current_datetime["datetime_format2"]); // renew , add 1 day for future
            }
        }

        // break in case logic
        if ($is_new_vehicle == false) { // re-new case
            if (($user_action_data['is_previous_policy'] == 'true')) {
                $previous_policy_expiry_date_array = getDateArray($user_action_data['previous_policy_expiry_date'].' '.date("h:i:s"));
                //echo "<pre>";print_r($previous_policy_expiry_date_array);exit;
                $previous_policy_expiry_date_obj = date_create($user_action_data['previous_policy_expiry_date']);
                $breakin_date_diff = date_diff($previous_policy_expiry_date_obj, $current_date); //invert => negative days
                if ($breakin_date_diff->invert == 1) {  //future exppiry date, -days = 1
                    $date = addXDays(1, $previous_policy_expiry_date_array["datetime_format2"]);
                    //print_r($date);exit; // renew , add 1 day for future
                    $policy_start_date = $date->format('d/m/Y H:i:s');
                    if ($breakin_date_diff->days > 30) {
                        $is_available_to_buy = false;
                    }
                } else {
                    if ($breakin_date_diff->days > 0) {
                        $is_breakin = true;
                        $is_available_to_buy = false;
                    }
                }
                // NCB(no claim bonus) logic :  only for  renewal
                if (($user_action_data['is_claimed'] == 'false')) {
                    if ($breakin_date_diff->days < 90) {
                        $is_ncb_available = true;
                    }
                }
            } else {
                $is_breakin = true;
                $is_available_to_buy = false;
            }
        }

        if ($is_ncb_available) {
            $current_ncb_text = ($user_action_data['previous_policy_ncb']) ? $user_action_data['previous_policy_ncb'] : 0;
            $current_ncb = $this->getNcbPercentage($current_ncb_text);
        }




        //$depreciation_percentage : no of mponth
        // IDV Calculation : depends on zone,vehicl age,CC and if commercial then weight
        $manufacturing_date = date_create($vehicle_mfg_date['date_format2']);
        $vehicle_date_diff = date_diff($manufacturing_date, $current_date); //invert => negative days

        $vehicle_seating_capacity = $vehicle_detail['seating_capacity'];
        $vehicle_zone_type_id = $rto_detail['zone_type_id'];
        $vehicle_age_year = ceil($vehicle_date_diff->days / 365);
        $vehicle_age_month = ceil($vehicle_date_diff->days / 30);
                
         $idv_formula_detail = $this->getIdvFormulaDetail($product_type_id, $vehicle_age_month);
         $vehicle_idv = (float) $vehicle_detail['ex_showroom_price'] * (float) $idv_formula_detail['percentage'];
        //if  new => current date will be purchase date
        //if renew => minus vehicle age from current date  2018 vehicle age: 4 years ==> 2018-4= 2014

        if (empty($purchase_invoice_date)) {
            $purchase_invoice_date_array = ($is_new_vehicle == false) ? $vehicle_mfg_date : $current_date;
        } else {
            $purchase_invoice_date_array = getDateArray($purchase_invoice_date);
        }
        $purchase_invoice_date_obj = date_create($purchase_invoice_date_array['date_format2']);
        $purchase_vehicle_date_diff = date_diff($purchase_invoice_date_obj, $current_date); //invert => negative days



        $purchase_vehicle_age_year = ceil($purchase_vehicle_date_diff->days / 365);
        $purchase_vehicle_age_month = ceil($purchase_vehicle_date_diff->days / 30);

        $purchase_idv_formula_detail = $this->getIdvFormulaDetail($product_type_id, $purchase_vehicle_age_month);
        $purchase_vehicle_idv = (float) $vehicle_detail['ex_showroom_price'] * (float) $purchase_idv_formula_detail['percentage'];
        // $vehicle_detail['ex_showroom_price'] = 1000000;
        // $od_discount = (isset($user_action_data['od_discount']) && ($user_action_data['od_discount'] != 'max')) ? $user_action_data['od_discount'] : 'max';
        // $is_od_discount_max  echo "string";die();
        

        $basic_od_formula = $this->getBasicOdPercFormula($product_type_id, $vehicle_seating_capacity, $vehicle_zone_type_id,$tenure);
        // echo "<pre>"; print_r($basic_od_formula);die('basic_od_formula');

        $vehicle_age_index = 'age' . $vehicle_age_year;
        $basic_od = $vehicle_idv * $basic_od_formula[$vehicle_age_index];

        // print_r($basic_od);die;
        $purchase_reg_date_array = getDateArray($user_action_data['purchase_invoice_date']);

        //policy start and end date

        $policy_start_date_arr = getDateArray($policy_start_date);
       
        if($user_action_data['product_type_id']==6 and  ($is_breakin == true || $is_breakin==1)  ) 
        {       
           
             if(!isset($user_action_data['previous_policy_expiry_date']))
             {
                      
                    // $previous_policy_expiry_date_array = getDateArray($user_action_data['previous_policy_expiry_date']);
                      $date = addXDays(2,  date('Y-m-d H:i:s'));
                      $policy_start_date = $date->format('d/m/Y H:i:s');
                      $policy_start_date_arr = getDateArray($policy_start_date);
             }else{ 
                    //$previous_policy_expiry_date_array1 = getDateArray($user_action_data['previous_policy_expiry_date']);
                     $date = addXDays(2,  date('Y-m-d H:i:s'));
                     $policy_start_date = $date->format('d/m/Y H:i:s');
                     $policy_start_date_arr = getDateArray($policy_start_date);
             }
            
        }
       
        $policy_end_date = addXDays(364, $policy_start_date_arr["datetime_format2"]);
        $policy_end_date_arr = getDateArray($policy_end_date->format('Y-m-d H:i:s'));

       // echo "<pre>";print_r($policy_end_date_arr);exit;

        // $session = $this->session->userdata();
        // $mpn_data = $session['mpn_data'];
        $vehicle_age = $vehicle_age_year." year " . ($vehicle_age_year? $vehicle_age_month." months":'');
        if($vehicle_age_month < 6){
            $vehicle_age_year = 0;
        }
        $mpn_data['policy_type'] = "package_policy"; //package_policy : current mpn,tppolicy:only third party
        $mpn_data['product_type_id'] = $product_type_id; //product_type_id 
        $mpn_data['is_available_to_buy'] = $is_available_to_buy;
        $mpn_data['is_breakin'] = isset($is_breakin)?$is_breakin:'';
        $mpn_data['is_new_vehicle'] = $is_new_vehicle;
        $mpn_data['policy_holder_type'] = $policy_holder_type;
        $mpn_data['rto_detail'] = $rto_detail;
        $mpn_data['vehicle_detail'] = $vehicle_detail;
        $mpn_data['current_date'] = $current_date;
        $mpn_data['vehicle_mfg_date'] = $vehicle_mfg_date;
        $mpn_data['previous_policy_expiry_date_array'] = $previous_policy_expiry_date_array;
        $mpn_data['purchase_reg_date_array'] = $purchase_reg_date_array;
        $mpn_data['breakin_date_diff'] = $breakin_date_diff;
        $mpn_data['is_ncb_available'] = $is_ncb_available;
        $mpn_data['previous_ncb'] = $user_action_data['previous_policy_ncb'];
        $mpn_data['current_ncb'] = $current_ncb;
        $mpn_data['vehicle_date_diff'] = $vehicle_date_diff;
        $mpn_data['vehicle_age_year'] = $vehicle_age_year;
        $mpn_data['vehicle_age_month'] = $vehicle_age_month;
        $mpn_data['vehicle_age'] = $vehicle_age;
        $mpn_data['vehicle_idv'] = $vehicle_idv;
        $mpn_data['vehicle_idv_nia'] = $vehicle_idv;
        $mpn_data['vehicle_min_idv'] = $vehicle_idv * 0.80;
        $mpn_data['vehicle_max_idv'] = $vehicle_idv * 1.20;
        // $mpn_data['is_od_discount_max'] = $is_od_discount_max;
        $mpn_data['basic_od'] = $basic_od;
        $mpn_data['idv_formula_detail'] = $idv_formula_detail;
        $mpn_data['basic_od_formula'] = $basic_od_formula;
        $mpn_data['purchase_vehicle_age_year'] = $purchase_vehicle_age_year;
        $mpn_data['purchase_vehicle_age_month'] = $purchase_vehicle_age_month;
        $mpn_data['purchase_idv_formula_detail'] = $purchase_idv_formula_detail;
        $mpn_data['purchase_vehicle_idv'] = $purchase_vehicle_idv;
        $mpn_data['policy_start_date'] = $policy_start_date;
        $mpn_data['policy_start_date_arr'] = $policy_start_date_arr;
        $mpn_data['policy_end_date'] = $policy_end_date;
        $mpn_data['policy_end_date_arr'] = $policy_end_date_arr;
        $mpn_data['third_party'] = '';
        $mpn_data["nildep_previous_policy"] = '';
        $mpn_data["accessories"] = $this->getAccessories();
        $mpn_data["geographical_extention"] = $this->getGeographicalExtention();
        $mpn_data["deductibles"] = $this->getDeductibles();
        $mpn_data["pa_covers"] = $this->getPaCovers();
        $mpn_data['is_third_party'] = false;
        $mpn_data["is_previous_policy_nil_dep"] = false;
        $mpn_data["is_previous_policy"] = false;
        $mpn_data["is_quote_forward"] = false;
        $mpn_data["is_proposal_view"] = false;


        //echo '<pre>';        print_r($mpn_data);//exit;
        //$session['user_action_data'] = $user_action_data;
        $session = array();
        $proposal_insert_array = array(
            'agent_id' => $this->session->userdata('customer_id'),
            'created' => date("Y-m-d H:m:s"),
            'product_type_id' => $product_type_id,
            'user_action_data' => json_encode($user_action_data),
            'quote_data' => json_encode($session),
            'proposal_status_id' => 3
        );
        $proposal_list_id = $this->insertProposalList($proposal_insert_array);
        if (!empty($proposal_list_id)) {
            $mpn_data['proposal_data']['proposal_list_id'] = $proposal_list_id;
            $session['mpn_data'] = $mpn_data;
        }
        $this->session->set_userdata($session);
        return TRUE;
    }    


    public function insertProposalList($proposal_insert_array) {
        $this->db->insert('proposal_list_rewamp', $proposal_insert_array);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function updateProposalList($id, $data) {
        $table_name = "proposal_list_rewamp";
        $update_id_column_name = "id";
        return $this->update_data($table_name, $data, $id, $update_id_column_name);
    }

    public function setHealthSessionParam($product_type_obj) {

        $date = date_default_timezone_set('Asia/Kolkata');
        $start = date("d-m-Y");
        $day = explode('-', $start);
        $date = $day[0] - 1;
        $year = $day[2] + 1;
        $end = $date . '-' . $day[1] . '-' . $year;
        $_SESSION["mpn_data"]["start"] = $start;
        $_SESSION["mpn_data"]["end"] = $end;
        $_SESSION['mpn_data']['cover_value'] = 300000;


        return json_encode(array('status' => true));
    }

    public function getPaCovers() {
        $result = array();
        $session = $this->session->userdata();
        $product_type_id = (int) $session['user_action_data']['product_type_id'];

        switch ($product_type_id) {
            case 1:
             $result = array(
                    "pa_paid_driver_number" => 0,
                    "pa_paid_driver_value" => '',
                    "pa_unnamed_persons_number" => 0,
                    "pa_unnamed_persons_value" => '',
                    "ll_paid_driver_value" => 0,
                    "ll_unnamed_persons_value" => 0
                    
                );
                break;
                case 2: 
                 $result = array(
                    
                    "pa_unnamed_persons_number" => 0,
                    "pa_unnamed_persons_value" => 0
                   
                );
                 break;
            default:
               
                break;
        }
        return $result;
    }

    public function getDeductibles() {
        $result = array();
        $session = $this->session->userdata();
        $product_type_id = (int) $session['user_action_data']['product_type_id'];
        switch ($product_type_id) {
            case 1111:
                break;
            default:
                $result = array("anti_theft" => 'false', "automobile_association" => 'false');
                break;
        }
        return $result;
    }

    public function getGeographicalExtention() {
        $result = array();
        $session = $this->session->userdata();
        $product_type_id = (int) $session['user_action_data']['product_type_id'];
        switch ($product_type_id) {
            case 111:
                break;
            default:
                $result = array("value_od" => 0, "value_tp" => 0, "country_selected" => '', "country_list" => array("bangladesh" => "bangladesh", "maldives" => "maldives", "bhutan" => "bhutan", "nepal" => "nepal", "pakistan" => "pakistan", "srilanka" => "sri lanka"));
                break;
        }
        return $result;
    }

    public function getAccessories() {
        $result = array();
        $session = $this->session->userdata();
        $product_type_id = (int) $session['user_action_data']['product_type_id'];

        switch ($product_type_id) {
            case 1:
                $result["elec_value"] = 0;
                $result["non_elec_value"] = 0;
                if (isset($session['mpn_data']['vehicle_detail']['fuel_cleaned']) && strtolower($session['mpn_data']['vehicle_detail']['fuel_cleaned']) == "p") {
                    $result['cng_value'] = 0;
                }
                break;
            case 2:
                $result["elec_value"] = 0;
                $result["non_elec_value"] = 0;
                if (isset($session['mpn_data']['vehicle_detail']['fuel_cleaned']) && $session['mpn_data']['vehicle_detail']['fuel_cleaned'] == "p") {
                    $result['cng_value'] = 0;
                }
                break;
        }
        return $result;
    }

    public function insertCustomerActivityLog() {
        $user_id = $_SESSION['userid'];
        $dataSession = json_encode($_SESSION);

        $data = array(
            'user_id' => $user_id,
            'data' => $dataSession
        );

        return $this->db->insert('customer_activity_log', $data);
    }

    public function updateCustomerActivityLog() {

        $user_id = $_SESSION['userid'];
        $dataSession = json_encode($_SESSION);

        $query = $this->db->query("SELECT id FROM customer_activity_log where `user_id`='" . $user_id . "' ORDER BY id DESC");
        $result = $query->row();

        $this->db->set('data', $dataSession);
        $this->db->where('id', $result->id);
        return $this->db->update('customer_activity_log');
    }

    public function getCustomerActivityLog() {

        $user_id = $_SESSION['userid'];

        $query = $this->db->query("SELECT * FROM customer_activity_log where `user_id`='" . $user_id . "' ORDER BY id DESC");
        $result = $query->row();

        return $result;
    }

    public function getActiveIcIdList($product_type_id, $instance_type_id) {
        $query = $this->db->query("SELECT im.id FROM insurance_master_rewamp im LEFT JOIN insurance_instance_rewamp ii ON im.id = ii.ic_id WHERE im.`is_active` = 1 AND ii.product_type_id = $product_type_id and ii.instance_type_id=$instance_type_id  ORDER BY im.id DESC");

        // echo $this->db->last_query(); die;
        return $result = $query->result();
    }

    public function setTravelSessionParam() {
        $this->load->model("Travel_Model");
        $user_action_data = $this->session->userdata('user_action_data');

        $birthdate = array();
        $family_members_details = array();
        $no_of_days = 0;
        $family_code = array('bharti' => "S", 'reliance' => "", 'hdfc' => "");
        $country_name = array();

        $product_type_id = $user_action_data['product_type_id'];
        $country = $user_action_data['travel_country'];
        $daterange = $user_action_data['travel_journey_date'];

        $daterange = str_replace("/", "-", $daterange);
        $medical_cover = $user_action_data['medical_cover'];
        $passenger_age = $user_action_data['passenger_age'];
        $relations = $user_action_data['passenger_relation'];
        if (isset($user_action_data['multitripCheckbox']) && !empty($user_action_data['multitripCheckbox'])) {
            $multitripCheckbox = $user_action_data['multitripCheckbox'];
        }
        $plan_code = "";
        $child = "";
        $family_type = "Family";
        $travel_multi_plan = "";

        // Check For Multitrip & set Variable
        if (isset($user_action_data['travel_days_details']) && !empty($user_action_data['travel_days_details'])) {
            $start_date_multi = $user_action_data['multitrip_start_date'];
            $end_date = $user_action_data['multitrip_end_date'];
            $travel_multi_plan = $user_action_data['travel_days_details'];
            $start_date_array = getDateArray($start_date_multi);
            $end_date_array = getDateArray($end_date);
            $start_date = $start_date_array['date'];
            $end_date = $end_date_array['date'];
        } else {
            //Get Start Date and End Date and Find difference
            $date_range_array = explode(" - ", $daterange);
            $start_date_array = getDateArray($date_range_array[0]);
            $end_date_array = getDateArray($date_range_array[1]);
            $start_date = $start_date_array['date'];
            $end_date = $end_date_array['date'];
        }


        $dateDiffer = getDiffDays($start_date, $end_date);
        $duration_trip = $dateDiffer->days + 1;

        //get country name & ids

        $countries_visiting = $this->getCountriesIn($country);
        foreach ($countries_visiting as $country_details) {
            $country_names[] = $country_details->name;
        }
        $country_name = implode(',', $country_names);

        //Get Self DOB
        $i = 0;
        foreach ($passenger_age as $key=>$dob_of_traveller) {
            $birthdate[] = getDobByAge($dob_of_traveller);
            //Make array of family members with age, relation and dob
            $family_members_details[] = array(
                'age' => $passenger_age[$i],
                'birthdate' => getDobByAge($passenger_age[$i])."/".$key,
                'relations' => $relations[$i]
            );
            $i++;
        }
        $selfdob = date("d/m/Y", strtotime($birthdate[0]));

        //Make relation Code
        foreach ($relations as $relation) {
            if (($relation == "Self")) {
                $plan_code = "S";
            }

            if (($relation == "spouse")) {
                $plan_code = $plan_code . "S";
            }

            if ($child <= 1) {
                if (($relation == "Child")) {
                    $child = $child + 1;
                    $plan_code = $plan_code . "C";
                }
            }
        }

        switch ($plan_code) {
            case 'S':
                $family_type = "Individual";
                break;
            case 'SS':
                $family_code = array('bharti' => 'SS', 'reliance' => 314, 'hdfc' => '2A');
                break;
            case 'SCC':
                $family_code = array('bharti' => 'S2C', 'reliance' => 318, 'hdfc' => '1A2C');
                break;
            case 'SSCC':
            case 'SCSC':
            case 'SCCS':
                $family_code = array('bharti' => 'SS2C', 'reliance' => 316, 'hdfc' => '2A2C');
                break;
            case 'SSC':
            case 'SCS':
                $family_code = array('bharti' => 'SSC', 'reliance' => 315, 'hdfc' => '2A1C');
                break;
            case 'SC':
                $family_code = array('bharti' => 'SC', 'reliance' => 317, 'hdfc' => '1A1C');
                break;
            default:
                $familyCode = array();
                break;
        }

        //Setting Session data
        $travelDetails = array(
            'family_details' => $family_members_details,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'self_dob' => $selfdob,
            'country_name' => $country_name,
            'duration_trip' => $duration_trip,
            'country_id' => $country,
            'family_code' => $family_code,
            'family_type' => $family_type,
            'medical_cover' => $medical_cover,
            'multi_trip_details' => $travel_multi_plan,
            'product_type_id' => $product_type_id,
            'product_type' => 'travel'
        );


        $this->session->set_userdata('mpn_data', $travelDetails);
        return json_encode(array('status' => true));
    }

    public function getOwnerDetailHtml() {
        $customer_quote = $this->data['customer_quote'];
        $active_form = $_SESSION['user_action_data']['active_customer_detail_form'];
        $commonInfo = $this->contentInfo;

        /* Get salutaion options */
        $salutaionSelected = $customer_quote['owner_details']['salutaion'];
        $salutaionArr = array('Mr', 'Ms', 'Mrs');
        $salutainOption = '';
        $i = 1;
        foreach ($salutaionArr as $salutaionData) {
            $isSelected = ($salutaionSelected == $salutaionData) ? "selected" : "";
            $salutaionName = 'salutaion' . $i;
            $salutainOption .= '<option ' . $isSelected . ' value="' . $salutaionData . '">' . $commonInfo[$salutaionName] . '</option>';
            $i++;
        }

        /* Check Marrital status */
        $married_statusChecked = $customer_quote['owner_details']['married_status'];
        $single = ($married_statusChecked == 'single') ? 'checked' : '';
        $married = ($married_statusChecked == 'married') ? 'checked' : '';

        /* Check gender status */
        $gender_statusChecked = $customer_quote['owner_details']['gender'];
        $genderMale = ($gender_statusChecked == 'male') ? 'checked' : '';
        $genderFemale = ($gender_statusChecked == 'female') ? 'checked' : '';

        /* Is corporate On */
        $is_corporate = $customer_quote['owner_details']['is_corporate'];
        $is_corporateChecked = ($is_corporate == 'on') ? 'checked' : '';
        $corporateSectionShowHide = ($is_corporate == 'on') ? 'block"' : 'none';
        $ownerdetailSectionShowHide = ($is_corporate == 'on') ? 'none"' : 'block';

        $html = <<<START_QUOTE
              <form class="section is-active" name="owner_form" id="owner_form_id">
                                        <fieldset class="section is-active" id="owner_fieldset_id">
                                        <h3 style="font-weight: 600;"> {$commonInfo['ownerDetails']}</h3>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="icon-addon addon-lg">
                                                    <input type="text" required pattern="[a-zA-Z0-9._%+-]+[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$" placeholder="{$commonInfo['emailId']}" value="{$customer_quote['owner_details']['email']}" class="form-control email" id="email" name="email">
                                                    <label class="glyphicon glyphicon-envelope"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="icon-addon addon-lg">
                                                    <input type="text" placeholder=" {$commonInfo['phoneNo']}" class="form-control" id="phone" maxlength="10" name="mobile_no" value="{$customer_quote['owner_details']['mobile_no']}">
                                                    <label class="glyphicon glyphicon-phone"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-6">
                                                 <div class="checkbox check_private_corporate">
                                                    <label style="color: #136059;">
                                                        <input {$is_corporateChecked} type="checkbox" name="is_corporate" id="is_corporate">
                                                        <span class="cr"><i class="cr-icon fa fa-check"></i></span>
{$commonInfo['checkBox']}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                               
                                            </div>
                                        </div>
										
										
										
										<div class="row" id="coporate_form_section" style="display: {$corporateSectionShowHide};">
                                            <div class="col-md-6">
                                                <div class="icon-addon addon-lg">
                                                    <input type="text" placeholder="Company Name" value="{$customer_quote['owner_details']['company_name']}" name="company_name" class="form-control" id="company_name">
                                                    <i class="fa fa-building" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="icon-addon addon-lg">
                                                    <input type="text" placeholder="GST NO." maxlength="15" name="gst_no_corporate" value="{$customer_quote['owner_details']['gst_no_corporate']}" class="form-control" id="gst_no" style="text-transform:uppercase;">
                                                    <label class="glyphicon glyphicon-copy"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="icon-addon addon-lg">
                                                    <input type="text" placeholder="PAN NO." maxlength="15" name="pan_no_corporate" value="{$customer_quote['owner_details']['pan_no_corporate']}" class="form-control" id="pan_no_corporate" style="text-transform:uppercase;">
                                                    <label class="glyphicon glyphicon-copy"></label>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="col-md-12">
                                                <h5 align="left">Contact Persons Details</h5>
                                                <div class="col-md-6">
                                                    <div class="icon-addon addon-lg">
                                                        <input type="text" placeholder="First Name" maxlength="15" name="corporate_contact_first_name" value="{$customer_quote['owner_details']['pan_no_corporate']}" class="form-control" id="corporate_contact_first_name" style="text-transform:uppercase;">
                                                        <label class="glyphicon glyphicon-user"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="icon-addon addon-lg">
                                                        <input type="text" placeholder="Last Name" maxlength="15" name="corporate_contact_last_name" value="{$customer_quote['owner_details']['pan_no_corporate']}" class="form-control" id="corporate_contact_last_name" style="text-transform:uppercase;">
                                                        <label class="glyphicon glyphicon-user"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

									<div id="ownerdetailSection" style="display: {$ownerdetailSectionShowHide};">
                                        <div class="row" id="private_form_section1">
                                            <div class="col-md-3">
                                                <div class="icon-addon addon-lg">
                                                    <div class="form-group">
                                                        <select class="form-control" id="salutaion" name="salutaion" style="margin-top: 10px;color: #999;border-bottom: 1px solid #c1ece9;font-size: 15px;    margin-bottom: 10px;">
                                                            <option value=""> {$commonInfo['salutaion']}</option>
																{$salutainOption}
                                                        </select>
                                                    </div>
                                                    <label class="glyphicon glyphicon-user"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="icon-addon addon-lg">
                                                    <input type="text" placeholder="{$commonInfo['fName']}" class="form-control" name="first_name" id="first_name" value="{$customer_quote['owner_details']['first_name']}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="icon-addon addon-lg">
                                                    <input type="text" placeholder="{$commonInfo['lName']}" class="form-control" name="last_name" id="last_name" value="{$customer_quote['owner_details']['last_name']}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="private_form_section2">
                                            <div>
                                                <div class="col-md-3">

                                                    <div class="icon-addon addon-lg">
                                                        <input id="dob" type="text" placeholder="{$commonInfo['DOB']}" name="dob" class="form-control" style="background: transparent;" value="{$customer_quote['owner_details']['dob']}">
                                                        <label class="glyphicon glyphicon-calendar"></label>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="icon-addon addon-lg">
                                                        <input id="pancard" MaxLength="10" pattern="[a-zA-Z]{3}[p|P]{1}[a-zA-Z]{1}[\0-9]{4}[a-zA-Z]{1}" alt="PanCard" type="text" placeholder="Pan Card No" name="pancard" class="form-control" style="background: transparent; text-transform: uppercase;" value="{$customer_quote['owner_details']['pancard']}">
                                                        <label class="glyphicon glyphicon-credit-card"></label>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="icon-addon addon-lg">
                                                        <input id="adharcard" type="text" MaxLength="12" pattern="\d{12}" placeholder="Aadhar Card No" name="adharcard" class="form-control" style="background: transparent;" value="{$customer_quote['owner_details']['adharcard']}">
                                                        <label class="glyphicon glyphicon-credit-card"></label>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div id="personal_detail">
                                            <div class="col-md-4 radio-cont">
                                                <div class="icon-addon addon-lg">
                                                    <div class="buying-selling-group married_status_btn" id="buying-selling-group" data-toggle="buttons">

                                                        Marital Status
                                                        <br>
                                                        <label>
                                                            <input {$single} type="radio" name="married_status" id="single" value="single" autocomplete="off">
                                                            <span class="buying-selling-word" style="font-size: 15px;" id="span_single">{$commonInfo['married']}</span>
                                                        </label>
                                                        <br>
                                                        <label>
                                                            <input {$married} type="radio" name="married_status" value="married" id="married" autocomplete="off">

                                                            <span class="buying-selling-word" id="span_married" style="font-size: 15px;">{$commonInfo['single']}</span>
                                                        </label>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-md-4 radio-cont">
                                                <div class="icon-addon addon-lg">
                                                    <div class="form-group buying-selling-group married_status_btn" id="buying-selling-group" data-toggle="buttons">
                                                        Gender
                                                        <br>
                                                        <label>
                                                            <input {$genderMale} type="radio" name="gender" id="male" value="male" autocomplete="off">
                                                            <span class="buying-selling-word" style="font-size: 15px;" id="span_male">Male</span>
                                                        </label>
                                                        <br>
                                                        <label>
                                                            <input {$genderFemale} type="radio" name="gender" value="female" id="female" autocomplete="off">
                                                            <span class="buying-selling-word" style="font-size: 15px;" id="span_female">Female</span>   
                                                        </label>                
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
										</div>
										
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="button">{$commonInfo['continue']} <i class="fa fa-angle-double-right"></i></div>
                                            </div>
                                        </div>
                                    </fieldset>
                                        <button type="button" onclick="customerDetailStepAction('next','owner_form_id');">NEXT</button>

                                    </form>
START_QUOTE;
        return $html;
    }

    public function getNomineeDetailHtml() {
        $active_form = $_SESSION['user_action_data']['active_customer_detail_form'];
        $customer_quote = $this->data['customer_quote'];
        $commonInfo = $this->contentInfo;
        /* Get relation options */

        $nomineeRelationshipSelected = $customer_quote['nominee_details']['nominee_relationship'];
        $nomineeRelationArr = array('father', 'mother', 'spouse', 'child', 'sibling');
        $nomineeOption = '';
        $j = 1;
        foreach ($nomineeRelationArr as $RelationOptionData) {
            $isSelected = ($nomineeRelationshipSelected == $RelationOptionData) ? "selected" : "";
            $relationName = 'relation' . $j;
            $nomineeOption .= '<option ' . $isSelected . ' value="' . $RelationOptionData . '">' . ucfirst($RelationOptionData) . '</option>';
            $j++;
        }

        //print_r($customer_quote);

        $html = <<<START_QUOTE
              <form class="section is-active" name="nominee_form" id="nominee_form_id">
                    <fieldset class="section" id="nominee_fieldset_id">
                                        <h3>{$commonInfo["nomineeDetails"]}</h3>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="icon-addon addon-lg">
                                                    <input type="text" placeholder="{$commonInfo['nomiName']}" class="form-control" name="nominee_name" id="nominee_name" value="{$customer_quote['nominee_details']['nominee_name']}">
                                                    <label class="glyphicon glyphicon-user"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="icon-addon addon-lg">
                                                    <select class="form-control" id="nomniee_relationship" name="nominee_relationship" style="margin-top: 10px;margin-bottom: 10px;color: #999;border-bottom: 1px solid #c1ece9;font-size: 15px;">
                                                        <option value="">{$commonInfo['relation']}</option>
															{$nomineeOption}
                                                        
                                                    </select>
                                                    <i class="fa fa-users" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="icon-addon addon-lg">
                                                    <input type="text" placeholder="{$commonInfo['nomiAge']}" class="form-control" name="nominee_age" id="nominee_age" maxlength="2" value="{$customer_quote['nominee_details']['nominee_age']}">
                                                    <i class="fa fa-life-ring" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="appointee_sect" style="display:none;">
                                            <div class="col-md-4">
                                                <div class="icon-addon addon-lg">
                                                    <input type="text" placeholder="Appointee Name" class="form-control" name="appointee_name" id="appointee_name" value="{$customer_quote['nominee_details']['appointee_name']}">
                                                    <label class="glyphicon glyphicon-user"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="icon-addon addon-lg">
                                                    <select class="form-control" id="appointee_relationship" name="appointee_relationship" style="margin-top: 10px;margin-bottom: 10px;color: #999;border-bottom: 1px solid #c1ece9;font-size: 15px;">
                                                        <option value="">{$commonInfo['relation']}</option>
                                                        <option value="father">{$commonInfo['relation1']}</option>
                                                        <option value="mother">{$commonInfo['relation2']}</option>
                                                        <option value="spouse">{$commonInfo['relation3']}</option>
                                                        <option value="child">{$commonInfo['relation4']}</option>
                                                        <option value="sibling">Sibling</option>
                                                    </select>
                                                    <i class="fa fa-users" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="icon-addon addon-lg">
                                                    <input type="text" placeholder="Appointee Age" class="form-control" name="appointee_age" maxlength="2" id="appointee_age" value="{$customer_quote['nominee_details']['appointee_age']}">
                                                    <i class="fa fa-life-ring" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        <button type="button" onclick="customerDetailStepAction('previous','nominee_form_id');">Previous</button>
                                        <button type="button" onclick="customerDetailStepAction('next','nominee_form_id');">NEXT</button>
                                    </fieldset>
                                    </form>
START_QUOTE;
        return $html;
    }

    public function getAddressDetailHtml() {

        $customer_quote = $this->data['customer_quote'];
        /* Get state options */
        $addressStateSelected = $customer_quote['address_detail']['state'];
        $stateArr = $this->getStateList();
        if (count($stateArr)) {
            foreach ($stateArr as $stateObj) {
                $isStateSelected = ($addressStateSelected == $stateObj->id) ? "selected" : "";
                $StateOption .= '<option ' . $isStateSelected . ' value="' . $stateObj->id . '">' . ucfirst($stateObj->name) . '</option>';
            }
        }
        /* Get city options */
        $addressCitySelected = $customer_quote['address_detail']['city'];
        $cityArr = $this->getCityList($addressStateSelected);
        if (count($cityArr)) {
            foreach ($cityArr as $cityObj) {

                $isCitySelected = ($addressCitySelected == $cityObj->id) ? "selected" : "";
                $CityOption .= '<option ' . $isCitySelected . ' value="' . $cityObj->id . '">' . ucfirst($cityObj->name) . '</option>';
            }
        }

        $active_form = $_SESSION['user_action_data']['active_customer_detail_form'];
        $commonInfo = $this->contentInfo;
        $html = <<<START_QUOTE
            <form class="section is-active" id="address_form_id">
            <fieldset class="section" id="address_fieldset_id">
                                        <h3>{$commonInfo['addressInfo']}</h3>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="icon-addon addon-lg">
                                                    <input type="text" placeholder="{$commonInfo['addressInfo1']}" class="form-control" name="add_1" id="address1" value="{$customer_quote['address_detail']['add_1']}">
                                                    <label class="glyphicon glyphicon-home"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="icon-addon addon-lg">
                                                    <div class="icon-addon addon-lg">
                                                        <input type="text" placeholder="{$commonInfo['addressInfo2']}" class="form-control" name="add_2" id="address2" value="{$customer_quote['address_detail']['add_2']}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="icon-addon addon-lg">
                                                    <div class="icon-addon addon-lg">
                                                        <select class="form-control" id="state" name="state" style="margin-top: 10px;margin-bottom:10px;color: #999;border-bottom: 1px solid #c1ece9;font-size: 15px;">
                                                            <option value="">Select State</option>
                                                               {$StateOption}
                                                        </select>
                                                        <i class="fa fa-anchor" aria-hidden="true"></i>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="icon-addon addon-lg" id="citySectiondata">
                                                    <select class="form-control" id="city" name="city"  style="margin-top: 10px;margin-bottom:10px;color: #999;border-bottom: 1px solid #c1ece9;font-size: 15px;">
                                                        <option value="">Select City</option>
														{$CityOption}
                                                    </select>
                                                    <i class="fa fa-street-view" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="icon-addon addon-lg">
                                                    <input maxlength="6" type="text" placeholder="{$commonInfo['pincode']}" class="form-control" value="{$customer_quote['address_detail']['pincode']}" name="pincode" id="pincode">
                                                    <label class="glyphicon glyphicon-map-marker"></label>
                                                </div>
                                            </div>

                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="back_button" onclick="">{$commonInfo['back']}</div>
                                            <div class="button">
                                                {$commonInfo['continue']}
                                                <i class="fa fa-angle-double-right">
                                </i></div>
                        </div>
                    </div>
                </fieldset>
                <button type="button" onclick="customerDetailStepAction('previous','address_form_id');">Previous</button>
                <button type="button" onclick="customerDetailStepAction('next','address_form_id');">NEXT</button>        
            </form>
START_QUOTE;
        return $html;
    }

    public function getVehicleDetailHtml() {
        $rtodetail_quote = $this->data['rto_detail']['code'];
        $rto_arr = explode('-', $rtodetail_quote);
        $customer_quote = $this->data['customer_quote'];
        $active_form = $_SESSION['user_action_data']['active_customer_detail_form'];
        $commonInfo = $this->contentInfo;

        /* Aggrement Type */
        $agreementtypeSelected = $customer_quote['vehicle_detail']['agreement_type'];
        $agreementtypeArr = array('hypothecation' => 'Hypothecation', 'hire_purchase' => 'Hire Purchase', 'lease_agreement' => 'Lease Agreement');
        $agreementtypeOption = '';
        foreach ($agreementtypeArr as $key => $agreementtypeData) {
            $isSelected = ($agreementtypeSelected == $key) ? "selected" : "";
            $agreementtypeOption .= '<option ' . $isSelected . ' value="' . $key . '">' . ucfirst($agreementtypeData) . '</option>';
        }

        /* Financer data */
        $financerListArr = $this->getFinancerList();
        $financerOption = '';
        if (count($financerListArr)) {
            $agreementtypeSelected = $customer_quote['vehicle_detail']['bank_type'];
            foreach ($financerListArr as $financerObj) {
                $isFinaSelected = ($agreementtypeSelected == $financerObj->FinanciarID) ? "selected" : "";

                $financerOption .= '<option ' . $isFinaSelected . ' value="' . $financerObj->FinanciarID . '">' . ucfirst($financerObj->FinanciarName) . '</option>';
            }
        }
        $currentData = date("Y-m-d");


        $html = <<<START_QUOTE
            <form class="section is-active" id="vehicle_form_id">
            <fieldset class="section" id="vehicle_fieldset_id">
                                        <h3>Vehicle Details</h3>

                                        <div class="row">

                                            <div class="col-md-3">

                                                <input type="text" readonly="readonly" name="car_state" style="text-transform:uppercase;" class="form-control car_state" id="car_reg_no" value="{$rto_arr[0]}">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" value="{$rto_arr[1]}" class="form-control car_rto" name="car_rto" id="car_reg_no" readonly="readonly">
                                            </div>

                                            <div class="col-md-3">
                                                <input type="text" placeholder="Eg: ABC" style="text-transform:uppercase" MaxLength="3" pattern="[A-Za-z]{1,3}" class="form-control car_letters" name="car_letters" value="{$customer_quote['vehicle_detail']['car_letters']}" id="car_letters" style="padding:0;padding-top:22px;padding-bottom:22px;padding-left:10px;">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" placeholder="Eg: XXXX" style="text-transform:uppercase" MaxLength="4" pattern="\d{4}" class="form-control car_numbers" name="car_numbers" id="car_numbers" style="padding:0;padding-top:22px;padding-bottom:22px;padding-left:10px;" value="{$customer_quote['vehicle_detail']['car_numbers']}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="icon-addon addon-lg">
                                                    <input type="text" placeholder="Engine Number" class="form-control" style="text-transform:uppercase;"  name="engine_number" id="car_engin_num" value="{$customer_quote['vehicle_detail']['engine_number']}" style="text-transform:uppercase">
                                                    <i class="fa fa-wrench" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="icon-addon addon-lg">
                                                    <div class="icon-addon addon-lg">
                                                        <input type="text" placeholder="Chassis Number" class="form-control" name="chassis_number" style="text-transform:uppercase;" id="car_chassis_num" value="{$customer_quote['vehicle_detail']['chassis_number']}" style="text-transform:uppercase">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="icon-addon addon-lg">
                                                    <div class="icon-addon addon-lg">
                                                        <input type="text" placeholder="Vehicle Color" style="text-transform:uppercase;" class="form-control" name="car_color" id="car_color" value="{$customer_quote['vehicle_detail']['car_color']}">
                                                        <i class="fa fa-paint-brush" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="icon-addon addon-lg">
                                                    <input id="registration_date" type="text" placeholder="{$commonInfo['regDate']}" name="reg_date" class="form-control" style="background: transparent;" value="{$currentData}">
                                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="icon-addon addon-lg">
                                                    <div class="form-group">
                                                        <select class="form-control" id="agreement_type" name="agreement_type" style="margin-top: 10px;color: #999;border-bottom: 1px solid #c1ece9;font-size: 15px; text-transform: uppercase;">
                                                            <option value="">Select Agreement Type</option>
                                                            {$agreementtypeOption}
                                                        </select>
                                                    </div>
                                                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="icon-addon addon-lg">
                                                    <div class="form-group">
                                                        <select class="form-control" id="bank_type" name="bank_type" style="margin-top: 10px;color: #999;border-bottom: 1px solid #c1ece9;font-size: 15px;">
                                                            <option value="">Select Bank </option>
															{$financerOption} 
																
                                                        </select>
                                                    </div>
                                                    <i class="fa fa-university" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="back_button" onclick="">{$commonInfo['back']}</div>
                                                <div class="button">{$commonInfo['continue']} <i class="fa fa-angle-double-right"></i></div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <button type="button" onclick="customerDetailStepAction('previous','vehicle_form_id');">Previous</button>
                <button type="button" onclick="customerDetailStepAction('next','vehicle_form_id');">NEXT</button>     
            </form>
START_QUOTE;
        return $html;
    }

    public function getPreviousPolicyDetailHtml() {
        $commonInfo = $this->contentInfo;
        $active_form = $_SESSION['user_action_data']['active_customer_detail_form'];
        $customer_quote = $this->data['customer_quote']['previous_policy_details'];
        $expiry_Date = $this->data['user_action_data']['expiry_Date'];

        /* Insurance Company list */
        $icArr = $this->getInsuranceList($this->data['ic_quote']['ic']['id']);
        if (count($icArr) > 0) {
            $pre_insuranceSelected = $customer_quote['pre_insurance'];
            $insurenaceList = '';
            foreach ($icArr as $icData) {
                $pre_insSelected = ($pre_insuranceSelected == $icData->id) ? "selected" : "";
                $insurenaceList .= '<option ' . $pre_insSelected . ' value="' . $icData->id . '">' . ucfirst($icData->code) . '</option>';
            }
        }


        $html = <<<START_QUOTE
            <form class="section is-active" id="previous_policy_form_id">
                <fieldset class="section" id="previous_policy_fieldset_id"">
                    <h3>Previous Policy Details </h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="icon-addon addon-lg">
                                <input type="text" placeholder="Previous Policy Number" pattern="[A-Za-z0-9./]{1,20}" value="{$customer_quote['pre_policy_num']}" class="form-control" name="pre_policy_num" id="pre_policy_num">
                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="icon-addon addon-lg">
                                <select class="form-control" id="pre_insurance" name="pre_insurance" style="margin-top: 10px;margin-bottom:10px;color: #999;border-bottom: 1px solid #c1ece9;font-size: 15px;">
                                    <option value="">Select Previous Insurance</option>
                                    {$insurenaceList}
                                </select>
                                <i class="fa fa-globe" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="icon-addon addon-lg">
                                <input id="pre_end_date" type="text" readonly="" placeholder="Previous Policy End Date" name="pre_end_date" class="form-control" style="background: transparent;" value="{$expiry_Date}" >
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="back_button" onclick="form5_back();">{$commonInfo['back']}</div>
                            <div class="button">{$commonInfo['continue']}</div>
                        </div>
                    </div>
                </fieldset>
                <button type="button" onclick="customerDetailStepAction('previous','previous_policy_form_id');">Previous</button>
                <button type="button" onclick="customerDetailStepAction('next','previous_policy_form_id');">NEXT</button>        
            </form>
START_QUOTE;
        return $html;
    }

    public function getProposalSummaryHtml() {

        $ic_quote = $this->data['ic_quote'];
        $active_form = $_SESSION['user_action_data']['active_customer_detail_form'];
        $commonInfo = $this->contentInfo;
        $owner_details = $this->data['customer_quote']['owner_details'];
        $nominee_details = $this->data['customer_quote']['nominee_details'];
        $vehicle_details = $this->data['customer_quote']['vehicle_detail'];
        $address_details = $this->data['customer_quote']['address_detail'];
        $previous_policy_details = $this->data['customer_quote']['previous_policy_details'];
        $policy_start_date = $this->data['policy_start_date_arr']['date'];
        $policy_end_date = $this->data['policy_end_date_arr']['date'];
        //$vehicle_idv=round($this->data['vehicle_idv']);
        $vehicle_idv = number_format($this->data['vehicle_idv'], 2);
        $addArr = $this->getStateCityByCityID($address_details['city']);
        if ($previous_policy_details['pre_insurance']) {
            $insuArr = $this->getInsuranceSingleByID($previous_policy_details['pre_insurance']);
        }




        $html = <<<START_QUOTE
                <fieldset class="section" id="proposal_summary_id">
                                            <div class="container" style="border-top:1px solid #e8ebf0;border-left:1px solid #e8ebf0;border-right:1px solid #e8ebf0;width:100%;padding: 13px;background-image: url("assets/img/banner.png"),linear-gradient(to right, #ffffff 0, #ffffff);color: #026b63;">
                                                <div class="row">
                                                    <div class="col-md-2 col-md-offset-2">
                                                        <img src="logo/{$ic_quote['ic']['logo']}" class="img-thumbnail premium_img">
                                                    </div>
                                                    <div class="col-md-8" style="text-align:left;">
                                                        <h4 style="margin-top:0;"><b>{$ic_quote['ic']['code']}</b></h4>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <p>Policy Start : <b>{$policy_start_date}</b></p>
                                                                <p>Policy End : <b>{$policy_end_date}</b></p>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <p><b>IDV (Cover Amount)</b></p>
                                                                <p><b>₹</b><b> {$vehicle_idv}</b></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="container" style="border:1px solid #e8ebf0;width:100%;padding: 13px;background-color: rgba(234, 248, 247, 0.78);">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="row text-left">
                                                            <div class="col-md-1 col-md-offset-3">
                                                                <p><label class="glyphicon glyphicon-user"></label></p>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <div class="col-md-8">
                                                                <p class="you_are_text"><p class="custom_text"><b>Name : </b>{$owner_details['salutaion']} {$owner_details['first_name']} {$owner_details['last_name']}<br> <b>Email :</b> {$owner_details['email']} <br><b>Phone :</b> {$owner_details['mobile_no']}</p></p>

                                                                <p id="nominee_text"><p class="custom_text"><b>Nominee : </b> {$nominee_details['nominee_name']}<br><b>Age : </b> {$nominee_details['nominee_age']}<br><b>Relation : </b>{$nominee_details['nominee_relationship']}</p></p>
                                                            </div>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="row text-left">
                                                            <div class="col-md-1 col-md-offset-3">
                                                                <p><label class="glyphicon glyphicon-envelope"></label></p>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <p><b>Address :</b></p><p id="add_show">{$address_details['add_1']}  {$address_details['add_2']},<br>{$addArr['cname']},<br>{$addArr['sname']}-{$address_details['pincode']} </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" style="border-left:0px solid #e8ebf0;">
                                                        <div class="row text-left">
                                                            <div class="col-md-1 col-md-offset-1">
                                                                <p><label class="glyphicon glyphicon-road"></label></p>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <p id="reg_number"></p>
                                                                <p><b>Registration Date :</b> <span class="vehRegDate">{$vehicle_details['reg_date']}</span><br/>
                                                                    <b>Engine No. :</b> <span class="vehEngNo">{$vehicle_details['engine_number']}</span><br/>
                                                                    <b>Chassis No. :</b> <span class="vehChaNo">{$vehicle_details['chassis_number']}</span></p>
                                                            </div>
                                                        </div>
                                                        <div class="row text-left">
                                                            <div class="col-md-1 col-md-offset-1">
                                                                <p><label class="glyphicon glyphicon-heart"></label></p>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <p><b>Previous Insurer :</b> <span class="preInsurer">{$insuArr['code']}</span></b></p>
                                                                <p><b>Policy No. :</b> <span class="prePolicyNo">{$previous_policy_details['pre_policy_num']}</span></p>
                                                            </div>
                                                        </div>
                                                    </div>  
                                                    <div class="row" style="margin-top:20px;">
                                                        <div class="col-md-6 col-md-offset-3">
                                                            <p>By clicking 'Generate Proposal' you agree to <a href="#" style="background-color:transparent;">Terms of Use</a></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                <button type="button" onclick="customerDetailStepAction('previous','previous_policy_form_id');">Previous</button>
                <button type="button" id='generate_proposal_btn'>Generate Proposal</button>        
START_QUOTE;
        return $html;
    }

    public function evalFormPost($data, $form_id) {
        // $result['status'] = false;
        switch ($form_id) {
            case 'owner_form_id':
                if ($data) {
                    // print_r($data);die();
                }
                $_SESSION['mpn_data']['customer_quote']['owner_details'] = $data;
                break;
            case 'nominee_form_id':
                $_SESSION['mpn_data']['customer_quote']['nominee_details'] = $data;
                break;
            case 'address_form_id':
                if ($data['state']) {
                    $state_data = $this->getStateSingleRecords($data['state']);
                    $data['state_data'] = $state_data;
                }
                if ($data['city']) {
                    $city_data = $this->getCitySingleRecords($data['city']);
                    $data['city_data'] = $city_data;
                }
                $_SESSION['mpn_data']['customer_quote']['address_detail'] = $data;
                break;
            case 'vehicle_form_id':
                if ($data['bank_type']) {
                    $data['bank_type_data'] = $this->getFinancerSingleByID($data['bank_type']);
                }
                $_SESSION['mpn_data']['customer_quote']['vehicle_detail'] = $data;
                break;
            case 'previous_policy_form_id':
                if ($data['pre_insurance']) {
                    $data['pre_insurance_data'] = $this->getInsuranceSingleByID($data['pre_insurance']);
                }
                $_SESSION['mpn_data']['customer_quote']['previous_policy_details'] = $data;
                break;
            default:
                break;
        }
    }

//    function fetchOdDiscountBike($ic_id,$mpn_data,$ic_code='') {
//        $exploded_rto_code = explode('-', $mpn_data['rto_detail']['label']); //need to replace with state_id
//        $exploded_rto_code[0]=strtolower(trim($exploded_rto_code[0]));
//        //or/od
//        // $year_difference = $mpn_data['vehicle_age_year'];
//        if($ic_id == 3)
//        { 
//            $discount_array = $this->db->where('ic_id',$ic_id)->where('rto_state_code', $exploded_rto_code[0])->where('make_cleaned',$mpn_data['vehicle_detail']['make_cleaned'])->get('od_discount')->result();
//                $counter = count($discount_array);
//                $i = 0;
//                $model_avail = false;
//                $variant_avail = false;
//                $city_avail = false;
//                while ($i < $counter) {
//                   
//                    if ($discount_array[$i]->model_cleaned!="" && $discount_array[$i]->model_cleaned != $mpn_data['vehicle_detail']['model_cleaned']){
//                            unset($discount_array[$i]);
//                        }
//                    // if (strtolower($discount_array[$i]->rto_state) != strtolower($_SESSION['session_data_arr_car']['rto_details']->CityName)) {
//                    //     unset($discount_array[$i]);
//                    // }
//                    $i++;
//                }
//            $discount_array_formatted = array_values($discount_array);
//            $discount_array_formatted = $discount_array_formatted[0];
//        }
//        if($ic_id == 23){
//            $discount_array = $this->db->where('rto_state_code',$exploded_rto_code)->where('ic_code',$ic_code)->where('ic_id',$ic_id)->get('od_discount')->result();
//            $discount_array_formatted = array_values($discount_array);
//            $discount_array_formatted = $discount_array_formatted[0];
//        }
//        // $discount = array_shift($discount_array_formatted);
//        echo $this->db->last_query()."/n";
//        
//        if($discount_array_formatted){
//            $discount_id = $discount_array_formatted->id;
//            $this->db->where('od_discount_id', $discount_id);
//            return $this->db->get('od_discount_vehicle_age')->row_array();
//        }else{
//            return 0;
//        }
//        
//    }


    function fetchOdDiscount($ic_id, $mpn_data, $product_type) {
        //error_reporting(0);
        //print_r($this->session->userdata('access_control_dealer')['business_id']);die;
        $exploded_rto_code = explode('-', $mpn_data['rto_detail']['label']); //need to replace with state_id

        if (in_array(strtoupper($mpn_data['rto_detail']['label']), array('UP-16', 'UP-14', 'HR-26', 'HR-29', 'HR-51', 'HR-72'))) {
            $location = 'NCR';
            $column_to_check = 'rto_city';
        }

        if (in_array(strtoupper($mpn_data['rto_detail']['label']), array('MH-01', 'MH-02', 'MH-03', 'MH-04', 'MH-05', 'MH-43', 'MH-46', 'MH-47', 'MH-48'))) {
            $location = 'MUMBAI';
            $column_to_check = 'rto_city';
        }

        /*if($ic_id==25){
            if (in_array($mpn_data['rto_detail']->RTO_Code, array('MH-04'))) {
                $location = 'THANE';
                $column_to_check = 'rto_city';
            }

            if (in_array($mpn_data['rto_detail']->RTO_Code, array('MH-43'))) {
                $location = 'NAVI MUMBAI';
                $column_to_check = 'rto_city';
            }
        }*/

        if (in_array(strtoupper($mpn_data['rto_detail']['label']), array('KA-01', 'KA-02', 'KA-03', 'KA-04', 'KA-05'))) {
            $location = 'BANGALORE';
            $column_to_check = 'rto_city';
        }
        if (in_array(strtoupper($mpn_data['rto_detail']['label']), array('WB-01', 'WB-02', 'WB-03', 'WB-04', 'WB-05', 'WB-06', 'WB-07', 'WB-08', 'WB-09', 'WB-10'))) {
            $location = 'KOLKATA';
            $column_to_check = 'rto_city';
        }
        if (in_array(strtoupper($mpn_data['rto_detail']['label']), array('AP-09', 'AP-10', 'AP-11', 'AP-12', 'AP-13'))) {
            $location = 'HYDERABAD';
            $column_to_check = 'rto_city';
        }
        
        
       

        $query = " SELECT * FROM od_discount  WHERE ic_id = $ic_id  AND product_type = '$product_type' 
                          AND ( LOWER(make_cleaned) = LOWER('" . $mpn_data['vehicle_detail']['make_cleaned'] . "') )
                          AND ( LOWER(model_cleaned) = LOWER('" . $mpn_data['vehicle_detail']['model_cleaned'] . "') OR model_cleaned IS NULL)
                          AND ( LOWER(rto_state_code) = '' OR LOWER(rto_state_code )= LOWER('" . $exploded_rto_code[0] . "') OR rto_state_code IS NULL)
                          AND ( LOWER(variant_cleaned) = '' OR LOWER(variant_cleaned) = LOWER('" . $mpn_data['vehicle_detail']['variant_cleaned'] . "') OR variant_cleaned IS NULL)";
        $query = $this->db->query($query);
     //   echo "<pre>";
       //echo " SELECT * FROM od_discount  WHERE ic_id = $ic_id  AND product_type = '$product_type' 
         //                 AND ( LOWER(make_cleaned) = LOWER('" . $mpn_data['vehicle_detail']['make_cleaned'] . "') )
           //               AND ( LOWER(model_cleaned) = LOWER('" . $mpn_data['vehicle_detail']['model_cleaned'] . "') OR model_cleaned IS NULL)
             //             AND ( LOWER(rto_state_code) = '' OR LOWER(rto_state_code )= LOWER('" . $exploded_rto_code[0] . "') OR rto_state_code IS NULL)
               //           AND ( LOWER(variant_cleaned) = '' OR LOWER(variant_cleaned) = LOWER('" . $mpn_data['vehicle_detail']['variant_cleaned'] . "'//) OR variant_cleaned IS NULL)";
       //die;
        $discount_array = $query->result_array();
        if(empty($discount_array)){
            $query = " SELECT * FROM od_discount  WHERE ic_id = $ic_id  AND product_type = '$product_type' 
                          AND  ( LOWER(make_cleaned) = LOWER('" . $mpn_data['vehicle_detail']['make_cleaned'] . "') )
                          AND  ( LOWER(model_cleaned) = '' OR  LOWER(model_cleaned) = LOWER('" . $mpn_data['vehicle_detail']['model_cleaned'] . "') OR model_cleaned IS NULL)
                          AND  ( LOWER(rto_state_code) = '' OR LOWER(rto_state_code )= LOWER('" . $exploded_rto_code[0] . "') OR rto_state_code IS NULL)
                          #AND ( LOWER(variant_cleaned) = '' OR LOWER(variant_cleaned) = LOWER('" . $mpn_data['vehicle_detail']['variant_cleaned'] . "') OR variant_cleaned IS NULL)";
            $query = $this->db->query($query);
            $discount_array = $query->result_array();
        }
       

        $counter = count($discount_array);
        $i = 0;
        $model_avail = false;
        $variant_avail = false;
        $city_avail = false;
        
//        function bypassdiscount1($discount_array){
//            $counter = count($discount_array);
//            if($counter==1){
//                break;
//            }
//        }
        while ($i < $counter) {
            if($counter ==1){
                goto bypassodiscount1;
            }
            
            if ($discount_array[$i]['model_cleaned'] && $discount_array[$i]['model_cleaned'] != $mpn_data['vehicle_detail']['model_cleaned']) {
                unset($discount_array[$i]);
                $counter = count($discount_array);
                if($counter ==1){
                    goto bypassodiscount1;
                }
            }
            if ($discount_array[$i]['model_cleaned'] == $mpn_data['vehicle_detail']['model_cleaned']) {
                $model_avail = true;
            }
            if ($discount_array[$i]['variant_cleaned'] && $discount_array[$i]['variant_cleaned'] != $mpn_data['vehicle_detail']['variant_cleaned']) {
                unset($discount_array[$i]);
                $counter = count($discount_array);
                if($counter ==1){
                    goto bypassodiscount1;
                }
            }
            if ($discount_array[$i]['variant_cleaned'] && $discount_array[$i]['variant_cleaned'] == $mpn_data['vehicle_detail']['variant_cleaned']) {

                $variant_avail = true;
            }
            if ($discount_array[$i]['fuel_cleaned'] && strtolower($discount_array[$i]['fuel_cleaned']) != strtolower($mpn_data['vehicle_detail']['fuel_cleaned'])) {
                unset($discount_array[$i]);
                $counter = count($discount_array);
                if($counter ==1){
                    goto bypassodiscount1;
                }
            }
            if ($mpn_data['current_ncb'] > 0 && $discount_array[$i]['ncb'] == '0') {
                unset($discount_array[$i]);
                $counter = count($discount_array);
                if($counter ==1){
                    goto bypassodiscount1;
                }
            }
            if (strtolower($discount_array[$i]['rto_state']) != $exploded_rto_code[0]) {
                unset($discount_array[$i]);
                $counter = count($discount_array);
                if($counter ==1){
                    goto bypassodiscount1;
                }
            }
            $i++;
        }
        bypassodiscount1:
        $discount_array_formatted = array_values($discount_array);
        $j = 0;
        $counter = count($discount_array_formatted);
        if ($counter > 1) {
            while ($counter > $j) {
                if ($location && strtolower($location) == strtolower($discount_array_formatted[$j]['rto_city'])) {
                    $city_avail = true;
                }
                $j++;
            }
            $discount_array_formatted = array_values($discount_array_formatted);
            $k = 0;

            $counter = count($discount_array_formatted);
            if($counter ==1){
                goto bypassodiscount2;
            }
            if ($counter > 1) {
                while ($counter > $k) {
                    if ($city_avail && empty($discount_array_formatted[$k]['rto_city'])) {
                        unset($discount_array_formatted[$k]);$counter = count($discount_array_formatted);
                        $counter = count($discount_array_formatted);
                        if($counter ==1){
                            goto bypassodiscount2;
                        }
                    } elseif (!$city_avail && !empty($discount_array_formatted[$k]['rto_city'])) {
                        unset($discount_array_formatted[$k]);
                        $counter = count($discount_array_formatted);
                        if($counter ==1){
                            goto bypassodiscount2;
                        }
                    }
                    if ($model_avail && $discount_array_formatted[$k]['model_cleaned'] != $mpn_data['vehicle_detail']['model_cleaned']) {
                        unset($discount_array_formatted[$k]);
                        $counter = count($discount_array_formatted);
                        if($counter ==1){
                            goto bypassodiscount2;
                        }
                    }
                    $k++;
                }
            }
        }
        bypassodiscount2:

        if(count($discount_array_formatted)>1 && $mpn_data['current_ncb']==0 && empty($mpn_data['is_new_vehicle'])  && empty($mpn_data['is_claimed'])){
            foreach($discount_array_formatted as $key=>$value){
                if($value['ncb']==""){
                    unset($discount_array_formatted[$key]);
                    $counter = count($discount_array_formatted);
                    if($counter ==1){
                        goto bypassodiscount3;
                    }
                }
            }
        }
        bypassodiscount3:
        // echo "<pre>";
        // print_r($discount_array_formatted);
        // die();
        $discount = array_shift($discount_array_formatted);
        $discount_id = $discount['id'];
        $result = array();
        if($this->session->userdata('access_control_dealer')['business_id'] != '')
        {
            $this->db->where('od_discount_id', $discount_id);
            $this->db->where('biz_id',$this->session->userdata('access_control_dealer')['business_id']);
            $result = $this->db->get('od_discount_vehicle_age_biz_partner')->row_array();

        }

        if(empty($result))
        {
             $this->db->where('od_discount_id', $discount_id);
             $result = $this->db->get('od_discount_vehicle_age')->row_array();
            
        }   
       
         return $result;


    }

    // function fetchOdDiscountBusiness($ic_id, $mpn_data, $product_type,$bus_id) {
    //     //error_reporting(0);
    //     //print_r($this->session->userdata('access_control_dealer')['business_id']);die;
    //     echo $query = " SELECT * FROM od_discount_vehicle_age_business_partner  WHERE ic_id = $ic_id  AND product_type = '$product_type' 
    //                       AND ( LOWER(make_cleaned) = LOWER('" . $mpn_data['vehicle_detail']['make_cleaned'] . "') )
    //                       AND ( LOWER(model_cleaned) = LOWER('" . $mpn_data['vehicle_detail']['model_cleaned'] . "') OR model_cleaned IS NULL)
    //                       AND ( LOWER(rto_state_code) = '' OR LOWER(rto_state_code )= LOWER('" . $exploded_rto_code[0] . "') OR rto_state_code IS NULL)
    //                       AND ( LOWER(variant_cleaned) = '' OR LOWER(variant_cleaned) = LOWER('" . $mpn_data['vehicle_detail']['variant_cleaned'] . "') OR variant_cleaned IS NULL) and bussiness_id = $bus_id";
    //     $query = $this->db->query($query);
       
    //     $discount_array = $query->result_array();
    //     if(empty($discount_array)){
    //         echo $query = " SELECT * FROM od_discount_vehicle_age_business_partner  WHERE ic_id = $ic_id  AND product_type = '$product_type' 
    //                       AND  ( LOWER(make_cleaned) = LOWER('" . $mpn_data['vehicle_detail']['make_cleaned'] . "') )
    //                       AND  ( LOWER(model_cleaned) = '' OR  LOWER(model_cleaned) = LOWER('" . $mpn_data['vehicle_detail']['model_cleaned'] . "') OR model_cleaned IS NULL)
    //                       AND  ( LOWER(rto_state_code) = '' OR LOWER(rto_state_code )= LOWER('" . $exploded_rto_code[0] . "') OR rto_state_code IS NULL)
    //                       #AND ( LOWER(variant_cleaned) = '' OR LOWER(variant_cleaned) = LOWER('" . $mpn_data['vehicle_detail']['variant_cleaned'] . "') OR variant_cleaned IS NULL) and bussiness_id = $bus_id";
    //         $query = $this->db->query($query);
    //         $discount_array = $query->result_array();
    //     }
    //     die;

    //     return $discount_array;


    // }

    function getIcOdPercentageCommercial($ic_id, $mpn_data,$product_type,$type){

        $exploded_rto_code = explode('-', $mpn_data['rto_detail']['label']); //need to replace with state_id
        $rto_state_name = $mpn_data['rto_detail']['state_name'];
        $seating_capacity = $mpn_data['vehicle_detail']['seating_capacity'];
        $gvw = $mpn_data['vehicle_detail']['gvw'];
        $$seat_query ='';
        if($type == 'pccv'){
            $seat_query = "OR (min_seating < ".$seating_capacity." AND max_seating < ".$seating_capacity." )";
        }elseif($type == 'gccv'){
            $seat_query = "OR (min_gvw < ".$gvw." AND max_gvw < ".$gvw." )";
        }

        $query = " SELECT * FROM od_discount  WHERE ic_id = $ic_id  AND product_type = '$product_type' 
                          AND ( LOWER(make_cleaned) = LOWER('" . $mpn_data['vehicle_detail']['make_cleaned'] . "') )
                          AND ( LOWER(model_cleaned) = LOWER('" . $mpn_data['vehicle_detail']['model_cleaned'] . "') OR model_cleaned IS NULL)
                          AND ( LOWER(rto_state_code) = '' OR LOWER(rto_state_code )= LOWER('" . $exploded_rto_code[0] . "') OR rto_state_code IS NULL)
                          AND ( LOWER(variant_cleaned) = '' OR LOWER(variant_cleaned) = LOWER('" . $mpn_data['vehicle_detail']['variant_cleaned'] . "') OR variant_cleaned IS NULL)".$seat_query;
        $query = $this->db->query($query);
       
        $discount_array = $query->result_array();
        if(empty($discount_array)){
            $query = " SELECT * FROM od_discount  WHERE ic_id = $ic_id  AND product_type = '$product_type' 
                          AND  ( LOWER(make_cleaned) = LOWER('" . $mpn_data['vehicle_detail']['make_cleaned'] . "') )
                          AND  ( LOWER(model_cleaned) = LOWER('" . $mpn_data['vehicle_detail']['model_cleaned'] . "') OR model_cleaned IS NULL)
                          AND  ( LOWER(rto_state_code) = '' OR LOWER(rto_state_code )= LOWER('" . $exploded_rto_code[0] . "') OR rto_state_code IS NULL)
                          #AND ( LOWER(variant_cleaned) = '' OR LOWER(variant_cleaned) = LOWER('" . $mpn_data['vehicle_detail']['variant_cleaned'] . "') OR variant_cleaned IS NULL)".$seat_query;
            $query = $this->db->query($query);
            $discount_array = $query->result_array();
        }


        // echo $this->db->last_query();die();
        $discount_array = $query->result_array();
        // echo "<pre>"; print_r($discount_array);die('yoyo 1');
        $counter = count($discount_array);
        $i = 0;
        $model_avail = false;
        $variant_avail = false;
        $city_avail = false;
        
//        function bypassdiscount1($discount_array){
//            $counter = count($discount_array);
//            if($counter==1){
//                break;
//            }
//        }
        while ($i < $counter) {
            if($counter ==1){
                goto bypassodiscount1;
            }
            
            if ($discount_array[$i]['model_cleaned'] && $discount_array[$i]['model_cleaned'] != $mpn_data['vehicle_detail']['model_cleaned']) {
                unset($discount_array[$i]);
                $counter = count($discount_array);
                if($counter ==1){
                    goto bypassodiscount1;
                }
            }
            if ($discount_array[$i]['model_cleaned'] == $mpn_data['vehicle_detail']['model_cleaned']) {
                $model_avail = true;
            }
            if ($discount_array[$i]['variant_cleaned'] && $discount_array[$i]['variant_cleaned'] != $mpn_data['vehicle_detail']['variant_cleaned']) {
                unset($discount_array[$i]);
                $counter = count($discount_array);
                if($counter ==1){
                    goto bypassodiscount1;
                }
            }
            if ($discount_array[$i]['variant_cleaned'] && $discount_array[$i]['variant_cleaned'] == $mpn_data['vehicle_detail']['variant_cleaned']) {

                $variant_avail = true;
            }
            if ($discount_array[$i]['fuel_cleaned'] && strtolower($discount_array[$i]['fuel_cleaned']) != strtolower($mpn_data['vehicle_detail']['fuel_cleaned'])) {
                unset($discount_array[$i]);
                $counter = count($discount_array);
                if($counter ==1){
                    goto bypassodiscount1;
                }
            }
            if ($mpn_data['current_ncb'] > 0 && $discount_array[$i]['ncb'] == '0') {
                unset($discount_array[$i]);
                $counter = count($discount_array);
                if($counter ==1){
                    goto bypassodiscount1;
                }
            }
            if (strtolower($discount_array[$i]['rto_state']) != $exploded_rto_code[0]) {
                unset($discount_array[$i]);
                $counter = count($discount_array);
                if($counter ==1){
                    goto bypassodiscount1;
                }
            }
            $i++;
        }
        bypassodiscount1:
        $discount_array_formatted = array_values($discount_array);
        $j = 0;
        $counter = count($discount_array_formatted);
        if ($counter > 1) {
            while ($counter > $j) {
                if ($location && strtolower($location) == strtolower($discount_array_formatted[$j]['rto_city'])) {
                    $city_avail = true;
                }
                $j++;
            }
            $discount_array_formatted = array_values($discount_array_formatted);
            $k = 0;

            $counter = count($discount_array_formatted);
            if($counter ==1){
                goto bypassodiscount2;
            }
            if ($counter > 1) {
                while ($counter > $k) {
                    if ($city_avail && empty($discount_array_formatted[$k]['rto_city'])) {
                        unset($discount_array_formatted[$k]);$counter = count($discount_array_formatted);
                        $counter = count($discount_array_formatted);
                        if($counter ==1){
                            goto bypassodiscount2;
                        }
                    } elseif (!$city_avail && !empty($discount_array_formatted[$k]['rto_city'])) {
                        unset($discount_array_formatted[$k]);
                        $counter = count($discount_array_formatted);
                        if($counter ==1){
                            goto bypassodiscount2;
                        }
                    }
                    if ($model_avail && $discount_array_formatted[$k]['model_cleaned'] != $mpn_data['vehicle_detail']['model_cleaned']) {
                        unset($discount_array_formatted[$k]);
                        $counter = count($discount_array_formatted);
                        if($counter ==1){
                            goto bypassodiscount2;
                        }
                    }
                    $k++;
                }
            }
        }
        bypassodiscount2:

        if(count($discount_array_formatted)>1 && $mpn_data['current_ncb']==0 && empty($mpn_data['is_new_vehicle'])  && empty($mpn_data['is_claimed'])){
            foreach($discount_array_formatted as $key=>$value){
                if($value['ncb']==""){
                    unset($discount_array_formatted[$key]);
                    $counter = count($discount_array_formatted);
                    if($counter ==1){
                        goto bypassodiscount3;
                    }
                }
            }
        }
        bypassodiscount3:
        // echo "<pre>";
        // print_r($discount_array_formatted);
        // die();
        $discount = array_shift($discount_array_formatted);
        $discount_id = $discount['id'];
        $this->db->where('od_discount_id', $discount_id);
        $od_discount_percentage_array = $this->db->get('od_discount_vehicle_age')->row_array();

        $negative_multiplier = 1;

        switch ($mpn_data['vehicle_age_year']) {
            case '0':
                $od_discount_percentage = $od_discount_percentage_array['0_1'] * $negative_multiplier;
                break;
            case '1':
                $od_discount_percentage = $od_discount_percentage_array['1_2'] * $negative_multiplier;
                break;
            case '2':
                $od_discount_percentage = $od_discount_percentage_array['2_3'] * $negative_multiplier;
                break;
            case '3':

                $od_discount_percentage = $od_discount_percentage_array['3_4'] * $negative_multiplier;
                break;
            case '4':
                $od_discount_percentage = $od_discount_percentage_array['4_5'] * $negative_multiplier;
                break;
            case '5':
                $od_discount_percentage = $od_discount_percentage_array['5_6'] * $negative_multiplier;
                break;
            case '6':
                $od_discount_percentage = $od_discount_percentage_array['6_7'] * $negative_multiplier;
                break;
            case '7':
                $od_discount_percentage = $od_discount_percentage_array['7_8'] * $negative_multiplier;
                break;
            case '8':
                $od_discount_percentage = $od_discount_percentage_array['8_9'] * $negative_multiplier;
                break;
            case '9':
                $od_discount_percentage = $od_discount_percentage_array['9_10'] * $negative_multiplier;
                break;
            case '10':
                $od_discount_percentage = $od_discount_percentage_array['10_11'] * $negative_multiplier;
                break;
            case '11':
                $od_discount_percentage = $od_discount_percentage_array['11_12'] * $negative_multiplier;
                break;
            default:
                $od_discount_percentage = 0;
                break;
        }
        if($od_discount_percentage < 0){
            $od_discount_percentage *= -1;
        }
        return $od_discount_percentage;
    }

    function getIcOdPercentage($ic_id, $data, $product_type,$ic_code='') {

        //error_reporting(0);
//        if($product_type == 'bike'){
//            $od_discount_percentage_array = $this->fetchOdDiscountBike($ic_id, $data,$ic_code);
//
//
//        }else{
//            $od_discount_percentage_array = $this->fetchOdDiscount($ic_id, $data, $product_type);
//        }
        $od_discount_percentage_array = $this->fetchOdDiscount($ic_id, $data, $product_type);

       
        if($ic_id != 6){
            $negative_multiplier = -1;
        }else{
            $negative_multiplier = 1;
        }

        switch ($data['vehicle_age_year']) {
            case '0':
                $od_discount_percentage = $od_discount_percentage_array['0_1'] * $negative_multiplier;
                break;
            case '1':
                $od_discount_percentage = $od_discount_percentage_array['1_2'] * $negative_multiplier;
                break;
            case '2':
                $od_discount_percentage = $od_discount_percentage_array['2_3'] * $negative_multiplier;
                break;
            case '3':

                $od_discount_percentage = $od_discount_percentage_array['3_4'] * $negative_multiplier;
                break;
            case '4':
                $od_discount_percentage = $od_discount_percentage_array['4_5'] * $negative_multiplier;
                break;
            case '5':
                $od_discount_percentage = $od_discount_percentage_array['5_6'] * $negative_multiplier;
                break;
            case '6':
                $od_discount_percentage = $od_discount_percentage_array['6_7'] * $negative_multiplier;
                break;
            case '7':
                $od_discount_percentage = $od_discount_percentage_array['7_8'] * $negative_multiplier;
                break;
            case '8':
                $od_discount_percentage = $od_discount_percentage_array['8_9'] * $negative_multiplier;
                break;
            case '9':
                $od_discount_percentage = $od_discount_percentage_array['9_10'] * $negative_multiplier;
                break;
            case '10':
                $od_discount_percentage = $od_discount_percentage_array['10_11'] * $negative_multiplier;
                break;
            case '11':
                $od_discount_percentage = $od_discount_percentage_array['11_12'] * $negative_multiplier;
                break;
            default:
                $od_discount_percentage = 0;
                break;
        }
        if($od_discount_percentage < 0){
            $od_discount_percentage *= -1;
        } 
        return $od_discount_percentage;
    }

    public function getStateList() {
        $result = $this->db->query("SELECT * FROM `state_rewamp`")->result_object();
        return $result;
    }
    
    public function getAgreementBank() {
        $result = $this->db->query("SELECT * FROM `bankmaster`")->result_object();
        return $result;
    }
     public function getInsurenceComList() {

        $mpn_data =$this->session->userdata("mpn_data");
        
        if($mpn_data['ic_quote']['ic']['id'] == 14) {

        $result = $this->db->query("SELECT a1.id,a2.pre_insurance_company as code FROM `insurance_master_rewamp` a1 INNER JOIN ic_videocon_previous_insurance_company a2 ON a1.id = a2.insurance_master_id")->result_object();
        }else{
            $result = $this->db->query("SELECT * FROM `insurance_master_rewamp`")->result_object();
        }
        return $result;
    }
    public function getVehicleColorList()
    {
        $result = $this->db->query("SELECT * FROM `vehicle_color`")->result_object();
        return $result;   
    }
    public function getCityAllList() {
        $result = $this->db->query("SELECT * FROM `city_master_rewamp`")->result_object();
        return $result;
    }

      public function getPincodeListForCity($pincode) {
        $result = $this->db->query("SELECT * FROM `reliance_city_details` WHERE `Pin_code` LIKE '$pincode' ORDER BY `Pin_code` ASC")->result_object();
        return $result;
    }

    public function getCityList($stateID) {
        if ($stateID) {
            $result = $this->db->query("SELECT * FROM `city_master_rewamp` WHERE state_id=$stateID")->result_object();
        }
        return $result;
    }

    public function getFinancerList() {
        $result = $this->db->query("SELECT * FROM `financiar`")->result_object();
        return $result;
    }

    public function getFinancerSingleByID($financiarID) {
        $result = $this->db->query("SELECT * FROM `financiar` WHERE FinanciarID = $financiarID")->row_array();
        return $result;
    }

    public function getInsuranceList($ic_id) {
        if ($ic_id == 8) {
            $result = $this->db->query("SELECT * FROM `insurance_master_rewamp` where is_hdfc_active=1")->result_object();
        } elseif ($ic_id == 14) {
            $result = $this->db->query("SELECT * FROM `insurance_master_rewamp` where is_liberty_active=1")->result_object();
        } else {
            $result = $this->db->query("SELECT * FROM `insurance_master_rewamp` where is_active=1")->result_object();
        }
        return $result;
    }

    public function getInsuranceSingleByID($icId) {
        $result = $this->db->query("SELECT * FROM `insurance_master_rewamp` where is_active=1 and id=$icId")->row_array();
        return $result;
    }

    public function getCitySingleRecords($cityID) {
        $result = $this->db->query("SELECT * FROM `city_master_rewamp` where id='$cityID'")->row_array();
        return $result;
    }

    public function getStateSingleRecords($stateID) {
        $result = $this->db->query("SELECT * FROM `state_rewamp` where id='$stateID'")->row_array();

        return $result;
    }

    public function getBankSingleRecords($bankID) {
        $result = $this->db->query("SELECT * FROM `bankmaster` where BankID='$bankID'")->row_array();
        return $result;
    }

    public function getBankRecords() {
        $result = $this->db->query("SELECT * FROM `financiar`")->result_object();
        return $result;
    }

    public function getStateCityByCityID($cityID) {
        $result = $this->db->query("SELECT c.id AS cid, UPPER(c.name) AS cname, s.id AS sid, UPPER(s.name) AS sname FROM city_rewamp AS c INNER JOIN state_rewamp AS s ON c.state_id=s.id WHERE c.id='$cityID'")->row_array();
        return $result;
    }

    public function getSellerOffice($ic_id, $state_short_code) {
        $result = $this->db->query("SELECT * FROM `seller_office` WHERE `ic_id` = $ic_id AND `state_short_code` = '$state_short_code'")->row_array();
        return $result;
    }

    function update_data($table_name, $update_array, $last_id, $id_field_name) {

        $this->db->where($id_field_name, $last_id);

        $this->db->update($table_name, $update_array);


        return $last_id;
    }

    public function updateProposalStatus($user_action, $is_break_in) {
        $result = array(
            'status' => false
        );
        $breakin_status_id = 10; //noAction
        switch ($user_action) {
            case 'btn_save_proposal':
                $proposal_status_id = 3; //save
                $message = 'Your information has been successfully saved.';
                break;
            case 'btn_quote_forward':
                $proposal_status_id = 7; //save - Quote_forward
                $message = 'Your information has been created and sent successfully to your references.';
                break;
            case 'btn_inspection_proposal':
                $proposal_status_id = 1; //save
                $message = 'Your proposal is in break in case & initiated successfully. our team will get to you.';
                // $this->Giib_Model->breaking_mail_to_pos_and_customer_and_giiib($_SESSION['session_data_arr_car']['proposal_data']['proposal_list_id']);
                $breakin_status_id = 7; //initiate
                break;
            case 'btn_cancel_proposal':
                $proposal_status_id = 8; //closed
                $message = 'Your information has been successfully cancelled.';
                break;
        }
        $result['message'] = $message;
        $result['status_id'] = $proposal_status_id;
        $updateProposalStatusArray = array(
            'proposal_status_id' => $proposal_status_id,
            'breakin_status_id' => $breakin_status_id,
            'is_breakin' => $is_break_in
        );
        $table_name = "proposal_list_rewamp";
        $update_id = $this->data['proposal_data']['proposal_list_id'];
        $update_id_column_name = "id";
        $proposal_list_op = $this->update_data($table_name, $updateProposalStatusArray, $update_id, $update_id_column_name);
        $result['status'] = true;
        $result['status_op'] = $proposal_list_op;
        $_SESSION['session_data_arr_car']['proposal_data']['proposal_list_id'] = $proposal_list_op;
        return $result;
    }

    function getposdetails($id){
        $data = $this->db->where('id',$id)->get('customer_rewamp')->row();
        return $data;
    }

    function getposdetailsextra($id){
        return $this->db->where("customer_id",$id)->get("customer_detail_rewamp")->row();
    }



        public function quoteForward($id) {

      //  $this->data['result']['status'] = false;

        //Check Condition if id exist or not in proposal_list




            $data = $this->db->where('id',$id)->get('proposal_list_rewamp')->result();
	        
//            $ic_id = 6;
            $result = $data[0];
            $ic_id = $data[0]->ic_id;
            $prop_number = $data[0]->proposal_no;
            $issue_date = $data[0]->created;
            $product_type = $data[0]->product_type_id;

            $query = $this->db->query("SELECT * FROM insurance_master_rewamp  where id='$ic_id'");
            $result = $query->result();

            

            $quote_data = json_decode($data[0]->quote_data);


            if ($product_type == '1') {
                if (!empty($quote_data->vehicle_detail->body_desc)) {
                    $body_desc = $quote_data->vehicle_detail->body_desc;
                } else {
                    $body_desc = '--';
                }
            }
            if ($product_type == '2') {
                if (!empty($quote_data->vehicle_detail->vehicle_body)) {
                    $body_desc = $quote_data->vehicle_detail->vehicle_body;
                } else {
                    $body_desc = '--';
                }
            }

             $company_name_data = $quote_data->ic_quote->ic->code;

            $json_data_customer = json_decode($data[0]->quote_data);
//            echo '<pre>';print_r($json_data_customer);exit;
//            $ic_id = $json_data_customer->seller_office->ic_id;

            ///insurance buyer details 
        $elec_value =  $json_data_customer->accessories->elec_value;
        $non_elec_value = $json_data_customer->accessories->non_elec_value;
        $cng_val = $json_data_customer->accessories->cng_value;

            $salutaion = $json_data_customer->customer_quote->vehicle_owner->salutaion;
            $engine_number = $json_data_customer->customer_quote->vehicle_detail->engine_number;
            
            $first_name = $json_data_customer->customer_quote->vehicle_owner->first_name;
            $last_name = $json_data_customer->customer_quote->vehicle_owner->last_name;
            $email = $json_data_customer->customer_quote->vehicle_owner->email;
            $mobile_no = $json_data_customer->customer_quote->vehicle_owner->mobile_no;
            $vehicle_fuel_type = $json_data_customer->vehicle_detail->fuel;
            $rto_state = $json_data_customer->rto_detail->state_name;

            //END
            //insurance buyer Address Details
         $address= $json_data_customer->customer_quote->address_detail->address1.'&nbsp;'.$json_data_customer->customer_quote->address_detail->address2.'&nbsp;'.$json_data_customer->customer_quote->address_detail->state->name.'&nbsp;'.$json_data_customer->customer_quote->address_detail->city->name.'&nbsp;'.$json_data_customer->customer_quote->address_detail->pincode;

            $insurance_buyer_personal_address = $address;
            $reg_date = $json_data_customer->customer_quote->vehicle_detail->reg_date;
            $reg_add = $json_data_customer->seller_office->reg_add;
            $gstin = $json_data_customer->seller_office->gstin;
        if(empty($gstin)){
        $result =  $this->db->select('gstin')->from('seller_office')->where('ic_id',$ic_id)->where('state_name',$rto_state)->get()->result_array();
        $gstin = isset($result[0]['gstin'])?$result[0]['gstin']:'';
        }
            // //POS DEtails
            // $pos_name = $_SESSION['pos_name'];
            // $pos_email = $_SESSION['email'];
            // $pos_mobile = $_SESSION['mobile'];
            // $pos_id = $_SESSION['pos_userid'];
            // $data_pos = $this->First_model->get_pos_details($pos_id);
            // $pos_aadhar_no = $data_pos->aadhar_no;

            ///select car color details
            $car_information = $json_data_customer->customer_quote->vehicle_detail;
            $color = $car_information->car_color;
            $registration_date = date('d-m-Y', strtotime($customer_quote->reg_date));
            $engine_number = $car_information->engine_number;
            $car_numbers = $car_information->car_numbers;
            $car_letters = $car_information->car_letters;
            $chassis_number = $car_information->chassis_number;
            $agreement_type = $car_information->agreement_type;
            $bank_type = $car_information->bank_type;

            ///accessories car

            $car_accessories = $json_data_customer->accessories; //array
            $elec_value = $car_accessories->elec_value;
            $non_elec_value = $car_accessories->non_elec_value;
            $bi_fuel_val = $car_accessories->bi_fuel_val;
            $cng_val = $car_accessories->cng_value;

            $address_1 = $insurance_buyer_personal_address->add_1;
            $address_2 = $insurance_buyer_personal_address->add_2;
            $city_name = ucwords(strtolower($city_details_fetch_query->CityName));
            $state_name = ucwords(strtolower($state_details_fetch_query->StateName));
            $pincode = $insurance_buyer_personal_address->pincode;
            //End
            //IC Details
            $company = $json_data_customer->ic_detail->InsuranceCompany_Name;
            //
            //insurance buyer Vehicle Details
            $insurance_buyer_vehicle_details = $json_data_customer->vehicle_detail;
            $manufacturer = $insurance_buyer_vehicle_details->manufacturer;
            $model = $insurance_buyer_vehicle_details->model;
            $varient = $insurance_buyer_vehicle_details->varient;
            $vehicle_cc = $insurance_buyer_vehicle_details->vehicle_cc;
            $seating_capacity = $insurance_buyer_vehicle_details->seating_capacity;
            $fuel_desc = $insurance_buyer_vehicle_details->fuel_desc;


            $RTO_Code = $json_data_customer->rto_details->RTO_Code;
            $idv = $json_data_customer->ic_quote->vehicle_idv;

            // if($json_data_customer->product_type_id == 1 || $json_data_customer->product_type_id == 2)
            // {
            // $bodytype = ($json_data_customer->product_type_id == 1)?"PRIVATE CAR":"BIKE";    
            // }
            // else 
            // {
            // $bodytype = $json_data_customer->vehicle_detail->body_type;
            // }

            $bodytype = $json_data_customer->vehicle_detail->body_type;


            

            $posdetails = $json_data_customer->proposal_data;
            $posdetails = $this->getposdetails($data[0]->agent_id);
            $posdetailsextra = $this->getposdetailsextra($data[0]->agent_id);
            //print_r($data);exit;

             
            //print_r($posdetails);exit;
            ///Logo company Wise 

            if($json_data_customer->policy_holder_type != 'corporate')
            {
            $insured_name =  strtoupper($salutaion).'&nbsp;'.strtoupper($first_name).'&nbsp;'.strtoupper($last_name);
            } else {
            $insured_name = $json_data_customer->customer_quote->vehicle_owner->company_name;
            }

            $site_url = base_url();

            //print_r(json_decode($data[0]->user_action_data)->product_type);exit;
            //if(json_decode($data[0]->user_action_data)->product_type )
            // if(strpos(preg_match('_',' ',json_decode($data[0]->user_action_data)->product_type),'commercial') == true)
            if(preg_match("/commercial/i",json_decode($data[0]->user_action_data)->product_type))
             {
                 $carrier_type = "Commercial";
             } else {
                 $carrier_type = "Private";
             }

            

           
            if ($ic_id == 3) {
                $logo = "<img src='" . base_url() . "assets/images/basic_logo.jpg'  width='130' height='80'/>";
            }
            if ($ic_id == 8) {
                $logo = "<img src='" . base_url() . "assets/images/hdfc_ergo1.jpg'  width='130' height='80'/>";
            }
            if ($ic_id == 25) {
                $logo = "<img src='" . base_url() . "assets/images/tata.png'  width='130' height='80'/>";
            }
            if ($ic_id == 6) {
                $logo = "<img src='" . base_url() . "assets/images/futuregenerali.jpg'  width='130' height='80'/>";
            }
            if ($ic_id == 14) {
                $logo = "<img src='" . base_url() . "assets/images/liberty_logo.jpg'  width='130' height='80'/>";
            }
            if ($ic_id == 23) {
                $logo = "<img src='" . base_url() . "assets/images/shriram_genralinsurance.png'  width='130' height='80'/>";
            }
			 if ($ic_id == 26) {
                $logo = "<img src='" . base_url() . "assets/images/nia-logo.jpg'  width='130' height='80'/>";
            }
            if ($ic_id == 16) {
                $logo = "<img src='" . base_url() . "assets/images/magma.jpg'  width='130' height='80'/>";
            }
            if ($result->new_policy_type == "yes") {
                $pol_heading_type = "PRIVATE VEHICLE-PACKAGE POLICY-CERTIFICATE CUM POLICY SCHEDULE";
            } elseif ($result->new_policy_type == "no") {
                $pol_heading_type = "MOTOR SECURE - PRIVATE CAR INSURANCE - LIABILITY ONLY POLICY";
            }

            /*  if ($ic_id == 3) {
              $comname = "Bharti Axa General Insurance";
              $prop_number = $proposal_table->proposal_no;
              } 


            /*  $colour = strtoupper($result->car_colour); */

            $our_company_logo = "<img src='" . base_url() . "assets/images/logo.png'  width='230' height='80'/>";





            

                $html_content =  '<br><br><br><div style="max-width: 980px; margin: auto;" class="email-container">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 980px; font-family: arial, sans-serif;border:0px;">
                <tbody><tr>
                    <td style="padding: 20px 0; text-align: left">
                  '.$logo.'
                    </td>
                    <td colspan="3" style="padding: 20px 0; text-align: center">
                    <h2>'.$company_name_data.'</h2>
                      <h3 style="margin: 0;">(FORM 51 OF THE CENTRAL MOTOR VEHICLE RULES,1989)</h3>
                      <h4>GSTIN&nbsp;:'.$gstin.'</h4>
                    </td>
                    <td style="padding: 20px 0; text-align: right">
                        '.$our_company_logo.'
                         </td>
                </tr>
            </tbody></table>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 980px; font-family: arial, sans-serif;border:0px;">
                <tbody><tr>
                    <td style="padding: 10px 0; text-align: center; background-color: #1a4e70; font-weight: bold;text-transform: uppercase; color: #fff;">
                        Insurance Details
                    </td>
                </tr>
            </tbody></table>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 980px; font-family: arial, sans-serif;border:0px;">
                <tbody><tr>
                    <td valign="top" style="padding: 0px 0;text-align: center">
                      <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 980px; font-family: arial, sans-serif;font-size: 13px;">
                            <tbody><tr>
                                <td valign="top" style="padding: 10px 12px;text-align: left; font-weight: bold; background-color: #f9f9f9">
                                    Insured Name
                                </td>
                                <td valign="top" style="padding: 10px 12px; text-align: left">
                                    '.$insured_name.'
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" style="padding: 10px 12px;text-align: left; font-weight: bold; background-color: #f9f9f9">
                                    Insured Add.
                                </td>
                                <td valign="top" style="padding: 12px 12px; text-align: left">
                          '.ucwords($address).'
                                </td>
                            </tr>
                        </tbody></table>
                    </td>
                    <td style="padding: 0px 0; text-align: center">
                       <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 980px; font-family: arial, sans-serif;font-size: 13px;">
                            <tbody><tr>
                                <td valign="top" style="padding: 10px 12px;text-align: left; font-weight: bold; background-color: #f9f9f9">
                                    Proposal No. &amp; Date
                                </td>
                                <td valign="top" style="padding: 10px 12px; text-align: left">
                                   ' . $prop_number . '&nbsp;' . $issue_date . '
                              
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" style="padding: 10px 12px;text-align: left; font-weight: bold; background-color: #f9f9f9">
                                    Insured Mobile
                                </td>
                                <td valign="top" style="padding: 10px 12px; text-align: left">
                                  '.$mobile_no.'
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" style="padding: 10px 12px;text-align: left; font-weight: bold; background-color: #f9f9f9">
                                  Insured Email
                                </td>
                                <td valign="top" style="padding: 10px 12px; text-align: left">
                                  '.$email.'
                                </td>
                            </tr>
                        </tbody></table>
                    </td>
                </tr>
            </tbody></table>
  <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 980px; font-family: arial, sans-serif;border:0px;">
                <tbody><tr>
                    <td style="padding: 10px 0; text-align: center; background-color: #1a4e70; font-weight: bold;text-transform: uppercase; color: #fff;">
                        Vehicle Details
                    </td>
                </tr>
            </tbody></table>
  <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 980px; font-family: arial, sans-serif;border:0px;">
                <tbody><tr>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                  Make
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                Model
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                               Variant
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                Cubic Capacity/GVW
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                 Color
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                 Seating Capacity
                                </td>
                </tr>

                <tr>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                  '.$json_data_customer->vehicle_detail->make.'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                '.$json_data_customer->vehicle_detail->model.'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                               '.$json_data_customer->vehicle_detail->variant.'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                '.$json_data_customer->vehicle_detail->cc.'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                 '.strtoupper($json_data_customer->customer_quote->vehicle_detail->car_color).'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                '.$json_data_customer->vehicle_detail->seating_capacity.'
                                </td>
                </tr>
  <tr>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                  Body Type
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                Registration Date
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                               RTO
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                             Registration No
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                 Fuel Type
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                Chassis No.
                                </td>
                </tr>

                <tr>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                           '.$bodytype.'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                '.$json_data_customer->customer_quote->vehicle_detail->reg_date.'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                              '.strtoupper($json_data_customer->rto_detail->label).'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                '.strtoupper($json_data_customer->customer_quote->vehicle_detail->veh1).'&nbsp;'.strtoupper($json_data_customer->customer_quote->vehicle_detail->veh2).'&nbsp;'.strtoupper($json_data_customer->customer_quote->vehicle_detail->veh3).'&nbsp;'.strtoupper($json_data_customer->customer_quote->vehicle_detail->veh4).'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                 '.$json_data_customer->vehicle_detail->fuel.'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                  '.strtoupper($chassis_number).'
                                </td>
                </tr>
   

      <tr>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                  Elec.Accessories
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                Non-Elec.Accessories
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                               Vehicle Sub Class
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                             Carrier Type
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                CNG/LPG Kit
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                Engine No.
                                </td>
                </tr>

                <tr>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                 '.$elec_value.'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                  '.$non_elec_value.'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                               --
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                               '.$carrier_type.'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                -
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                    '.strtoupper($engine_number).'                                    
                                </td>
                </tr>
    
                <tr>
                     <td  valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                Trailer
                                </td>
                     <td colspan="2" valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                Hypothecation/Lease/HP*
                                </td>
                     <td colspan="2" valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                 Vehicle IDV
                                </td>
                                <td  valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                 Cng.Accessories
                                </td>
                </tr>
                <tr>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                               --
                                </td>
                     <td colspan="2" valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                '.ucwords($json_data_customer->customer_quote->vehicle_detail->agreement_type).' / '.$json_data_customer->customer_quote->vehicle_detail->agreement_bank_array->BankName.'
                                </td>
                     <td colspan="2" valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                '.round($quote_data->vehicle_idv).'
                                </td>
                                <td  valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                '.round($cng_val).'
                                </td>
                </tr>

               
  

            </tbody></table>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 980px; font-family: arial, sans-serif;border:0px;">
                <tbody><tr>
                    <td colspan="4" style="padding: 10px 0; text-align: center; background-color: #1a4e70; font-weight: bold;text-transform: uppercase; color: #fff;">
                        POS Details
                    </td>
                </tr>  
                <tr>
                    <td valign="top" style="padding: 10px 5px;text-align: center; font-size:13px; font-weight: lighter; background-color: #fff">
                              <b>POS Name: </b> '.$posdetails->username.'
                    </td>
                    <td valign="top" style="padding: 10px 5px;text-align: center; font-size:13px; font-weight: lighter; background-color: #fff">
                               <b> POS AadharNO:</b> '.$posdetailsextra->aadhar_card_no.'
                    </td>
                    <td valign="top" style="padding: 10px 5px;text-align: center; font-size:13px; font-weight: lighter; background-color: #fff">
                              <b>  POS Email: </b>'.$posdetails->email.'
                    </td>
                    <td valign="top" style="padding: 10px 5px;text-align: center; font-size:13px; font-weight: lighter; background-color: #fff">
                              <b>  POS Mobile:</b> '.$posdetails->mobile_number.'
                    </td>
                </tr>
                  <tr>
                    <td colspan="4" style="padding: 10px 5px; text-align: center; background-color: #fff; font-weight: lighter;text-transform: uppercase; color: #000;font-size: 12px;">
                       <b>Brokers Name &amp; Add :</b> <br> Global-India Insurance Brokers Private Limited, Flat No.302, F/P- III, F 92, Front Portion, <br> Krishnanagar, Dist. Hauz Khas, New Delhi - 110029, India.


                    </td>
                </tr>
            </tbody></table>
            <table><tr><a href="' . base_url() . 'proposalview/' . $data[0]->quote_forward_link . '"><button type="submit">Buy This Policy</button></a></tr></table>
  
<br>
                    </div>';

                    //echo $html_content;exit;

				
        
         
          if($result[0]->is_netbanking=="1" ){

        $link="<a href='" . base_url() . "quote_forward_link_page/" . $policy_buyer_detail_id . "'>Click Here</a>";
                    }
          else 
          { 
        $link="";
          }
           
            $data['middle'] = "error";
            // $data_ = $query->result();
            // $ic_id = $data_[0]->ic_id;
            // $json_data_customer = json_decode($data_[0]->quote_data);
           // $email = $this->session->userdata('mpn_data')['customer_quote']['vehicle_owner']['email'];
            $to = $email;
            $subject = "My Policy Now Proposed Quotation";
            $headers = "From: " . strip_tags('info@mypolicynow.com') . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $message = "Dear Sir/ Madam, <br/> Greetings from <strong>Global-India Insurance Brokers Pvt. Ltd.!!!</strong><br/>";
            $message .= "Thanks for selecting us as your insurance brokers. Please click on below link to check quotation offered for renewal of your motor insurance policy.<br />";
            $message .= "'. $link.' <br/>We will be happy to hear your suggestions about your experience on mypolicynow.com. At Global-India Insurance Brokers Pvt. Ltd. we are committed to being your insurance partner for all insurance requirements and have a lot of personal lines of insurance products that suits to your needs. We have customized and special insurance productsb  too, and you can buy that if you wish to. But in case you would like to explore and buy the other insurance products, please do check it out www.mypolicynow.com";
                                             
            if (mail($to, $subject, $html_content, $headers)) {

                //$data['class']= 'success';
                //echo $data['message']= 'Your information has been successfully sent. Thank you.';
                $mail = $to;
                $data['message']['class'] = "success";
                $_SESSION['session_data_arr_car']['proposal_action']['message'] = "Your information has been successfully sent to " . strtolower($email) . ". Thank you";
                $data['message']['type'] = "quote_forward";
                $result_status['status'] = true;
                //die();
            } else {

                $mail = $to;
                // $data['class']= 'error';
                $data['message']['class'] = "error";
                $_SESSION['session_data_arr_car']['proposal_action']['message'] = "Error in sending mail to " . strtolower($email) . "Please try again later";
                $result_status['status'] = false;
                $data['message']['type'] = "quote_forward";
                //echo $data['message']= 'Error in sending mail. Please try again later';
                ///die();
            }
            // $data['commonInfo'] = $this->contentInfo;
            // $result['status'] = $status;
            // $this->load->view('success/success', $data);
     
            unset($_SESSION['mpn_data']);
            unset($_SESSION['user_action_data']);
			
        //echo json_encode($result_status);

        return $result_status;
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

    

    function checkDuplicateEntry($table, $where, $select) {
        $result = $this->db->select($select)
            ->from($table)
            ->where($where)
            ->get()
            ->row();

        if (count($result) > 0) {
            return $result->id;
        } else {
            return false;
        }
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
        $this->db->where($where);
        $this->db->set($data);
        if ($this->db->update($table)) {
            return true;
        } else {
            return false;
        }
    }

    function insertIntoTable($table_name, $data) {
        if ($this->db->insert($table_name, $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    function sendEmail($from, $to, $subject, $message, $name) {
        $this->load->library('email');
        $this->email->from($from, $name);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        if ($this->email->send())
            return true;
        else
            return false;
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

       function getDataFromTablebasesipscheme()
    {
        $sql="SELECT * FROM `bse_sip_schemes` WHERE `sip_status` = '1' AND `sip_frequency` = 'MONTHLY' AND `sip_transaction_mode` = 'DP' AND `sip_minimum_installment_amount` >= '500' AND `sip_maximum_installment_amount` <= '200000'and  is_robo ='Y'";

       return $result=$this->db->query($sql)->result_array();

    }

    


    function getDataFromTablebasescheme()
    {
        $sql="SELECT * FROM `bse_schemes` WHERE `scheme_plan` = 'NORMAL' AND `dividend_reinvestment_flag` = 'Z' AND `purchase_transaction_mode` = 'DP' AND `purchase_allowed` = 'Y' AND `minimum_purchase_amount` >= '500' AND `minimum_purchase_amount` <= '200000' AND updatetime!=CURRENT_DATE limit 100 ";

       return $result=$this->db->query($sql)->result_array();

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
    
    public function getImts($ic_quote) {
        $customer_quote =  $this->session->userdata('mpn_data')["customer_quote"];
        $imts = array();
        if ($ic_quote['geographical_value_od'] > 0 || $ic_quote['geographical_value_tp'] > 0) {
            $imts[] = "1";
        }
        switch ($customer_quote['vehicle_detail']['agreement_type']) {
            case 'hire_purchase':
                $imts[] = "5";
                break;
            case 'lease_agreement':
                $imts[] = "6";
                break;
            case 'hypothecation':
                $imts[] = "7";
                break;
        }
        if ($ic_quote['aa_value'] > 0) {
            $imts[] = "8";
        }
        if ($ic_quote['antitheft_value'] > 0) {
            $imts[] = "10";
        }
        if ($ic_quote['pa_unnamed_persons_premium'] > 0) {
            $imts[] = "16";
        }
        if ($ic_quote['pa_paid_driver_premium'] > 0) {
            $imts[] = "17";
        }
        if ($ic_quote['electrical_premium'] > 0) {
            $imts[] = "24";
        }
        if ($ic_quote['non_electrical_premium'] > 0) {
            $imts[] = "25";
        }
        if ($ic_quote['ll_paid_driver_premium'] > 0) {
            $imts[] = "28";
        }
        if ($ic_quote['ll_unnamed_persons_premium'] > 0) {
            $imts[] = "29";
        }
        return implode(", ", $imts);
    }

    function getVehicleActiveIcList() {
        $vehiclecleaned = $this->session->userdata('mpn_data')["vehicle_detail"]["vehicle_cleaned"];
        $query = $this->db->query("SELECT vehicle_cleaned,GROUP_CONCAT(distinct master_source_id) mastersourceid,GROUP_CONCAT(distinct reference_master_id) refrencemasterid FROM vehicle_master_source WHERE vehicle_cleaned='" . $vehiclecleaned . "' GROUP BY vehicle_cleaned");
        $result = $query->row();
        // print_r($result);die;
        $vehicle_cleaned_data = array(
            "vehicle_cleaned_name" => $result->vehicle_cleaned,
            "master_source_id" => $result->mastersourceid,
            "refrence_master_id" => $result->refrencemasterid
        );
        $mpn_data = $this->session->userdata('mpn_data');
        $mpn_data["vehicle_master_source_array"] = $vehicle_cleaned_data;
        $this->session->set_userdata('mpn_data', $mpn_data);

        $ic_id_array = $this->db->query("SELECT GROUP_CONCAT(ic_id) ic_id_array  FROM master_source WHERE id IN (" . $result->mastersourceid . ") AND IFNULL(ic_id,'')!='' ;");
        $ic_id_array = $ic_id_array->row();

        // print_r($ic_id_array); die;
        $ic_id_array_data = $ic_id_array->ic_id_array;

        $mpn_data = $this->session->userdata('mpn_data');
        $mpn_data["ic_id_array"] = $ic_id_array_data;
        $this->session->set_userdata('mpn_data', $mpn_data);
        $result = $this->session->userdata('mpn_data')["ic_id_array"];

        return $result;
    }

    

    public function getQuoteHtml($quote_result) {
        if (isset($quote_result['is_active']) && $quote_result['is_active']) {
            $logo = base_url() . "assets/images/client-logos/200x120/" . $quote_result['ic']['logo'];
            $vehicle_idv = ceil($quote_result['vehicle_idv']);
            $current_ncb = $quote_result['ncbpercentage'];
            $gross_premium = ceil($quote_result['gross_premium']);
            $ic_id = $quote_result['ic']['id'];
            $ic_id_name = $quote_result['ic']['code'];
            $ex_showroom_price = $this->session->userdata('mpn_data')['vehicle_detail']['ex_showroom_price'];
            $available_od_discount = isset($quote_result['available_od_discount'])?$quote_result['available_od_discount']:0;
           $maxdisc = '';
                  if($this->config->item('is_live')==false){
                        $maxdisc = '<li>MaxODDisc: ('.$available_od_discount.')%</li>';
                  }    
            $html = <<<START_QUOTE
               
          
            <div class="col-md-4 margin-two no-margin-top">
        <div class="border quotationlistbox">
                        <div class="col-md-12 border-bottom bg-gray padding-one">
                            <h3 class="quotation-h3 brand-text margin-one text-capitalize">{$ic_id_name}</h3>
                        </div>
                    <div class=" col-md-12 no-padding margin-two">
                        <div class="col-md-4">
                            <img src="{$logo}" class="img-responsive" alt="company name">
                        </div>
                        <div class="col-md-4 col-xs-6">
                            <ul class="text-small">
                                <li>IDV: &#x20b9;{$vehicle_idv}</li>
                                <li>ExSP.: &#x20b9;{$ex_showroom_price}</li>
                                <li>NCB: ({$current_ncb})%</li>
                                {$maxdisc}
                            </ul>
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="rwo padding-two">
                                <a href="javascript(0)" data-toggle="modal" data-target="#addon-popup-{$ic_id}" class="highlight-button btn btn-very-small button no-margin btn-light-black">Addon</a>
                            </div>
                            <div class="rwo padding-two">
                                <a href="javascript(0)" data-toggle="modal" data-target="#brekup-popup-{$ic_id}" class="highlight-button btn btn-very-small button no-margin btn-light-black">Breakup</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 padding-two margin-one">
                        <div class="col-md-6 no-padding"><h3 class="margin-four brand-text">Rs. {$gross_premium}</h3></div>
                        <div class="col-md-6 no-padding text-right">
                            <button class="highlight-button-dark btn btn-small button no-margin"  onclick='buyButtonClick({$ic_id})'>Buy Policy</button>
                        </div>
                    </div>
                    <div class="clear-both"></div>
                </div>
            </div>
START_QUOTE;

            

            return $html;
        }
    }

    public function getAddonHtml($quote_result) {
        if($quote_result && isset($quote_result['addon_list'])){

            $ic_id = $quote_result['ic']['id'];
            $addon_html = "";
            $stylevel = "";
            $session = $this->session->userdata();
            $mpn_data = $session['mpn_data'];
            // echo "<pre>"; print_r($mpn_data['is_third_party']);die('$mpn_data');
            $user_action_data = $session['user_action_data'];
            $nil_dep_error_block_html ='';
            // echo "<pre>"; print_r($quote_result['addon_list']);die('addon_list');
            $style = 'style="display:none"';

            if(isset($quote_result['addon_list'])){
                 $style = '';
                if ($mpn_data['is_previous_policy_nil_dep'] == 1) {
                    $mpn_data['dep'][$ic_id] = 'no';
                    $style = 'style="display:none"';
                } elseif ($mpn_data['dep'][$ic_id] == false) {
                    $style = 'style="display:none"';
                }
                $nil_dep_error_block_html = '<center id="nil-dep-div-' . $ic_id . '" ' . $style . '>
                    <div class="">
                    <div class="content">
                    <div class="row">
                    <div class="alert alert-warning">
                    <p><b>Note:</b>Inspection Needed..... You have selected "Nil Depreciation addon. Since in your previous policy you have not opted for depreciation cover."</p>
                    </div>
                    </div>
                    </div>
                    </div>
                    </center>';




                foreach ($quote_result['addon_list'] as $addon) {

                     if ($addon['is_nil_dep'] == 1 && !$mpn_data['is_new_vehicle'] && !$mpn_data['is_breakin']) {
                        $javascript_onclick = "onclick='nildepCar(" . $ic_id . ",".$addon['addon_id'].",0)'";
                    } else {
                        $javascript_onclick = "";
                    }

                    $is_selected = '';
                    if (isset($mpn_data['addons']) && isset($mpn_data['addons'][$ic_id]['list'][$addon['addon_id']]['addon_id']) === isset($addon['addon_id']) && isset($mpn_data['addons'][$ic_id]['list'][$addon['addon_id']]['ic_id']) === isset($ic_id)) {
                       $is_selected = 'checked';
                    }
                    
                    $class_id = $addon['addon_id'];
                    if(isset($addon['addon_premium']) && !empty($addon['addon_premium'])){
                        if($ic_id==25){
                            $addon_html .= "<div class='col-md-12 text-left'><label title='".$addon['description']."'><input name='addon_ic_id_radio' class='get_value ".$class."' " . $is_selected . "  id='addon_li_element_checkbox_".$ic_id."' data-addon-id='" . $addon['addon_id'] . "'   data-list-id='" . $addon['addon_id'] . "_{$ic_id}'   type='radio'   data-text='" . $addon['addon_name'] . "' value='" . round($addon['addon_premium']) . "' adons_name=" . $addon['addon_name'] . " ".$javascript_onclick."/> " .$addon['addon_name'] . " (Rs " . round($addon['addon_premium']) . ")</label></div>";
                        }else{

                            $addon_html .= "<div class='col-md-12 text-left'><label><input class='get_value' " . $is_selected . "  id='addon_li_element_checkbox_".$ic_id."' data-addon-id='" . $addon['addon_id'] . "'   data-list-id='" . $addon['addon_id'] . "_{$ic_id}'   type='checkbox' data-text='" . $addon['addon_name'] . "' value='" . round($addon['addon_premium']) . "' adons_name=" . $addon['addon_name'] . " ".$javascript_onclick." /> " .$addon['addon_name'] . " (Rs " . round($addon['addon_premium']) . ")</label></div>";

                        }
                    }
                }    
            }
            
            if(isset($mpn_data['is_third_party']) && $mpn_data['is_third_party']){
                $addon_html ='No Addons For Third Party';   
            }
             
        

        $html = <<<START_QUOTE
     
<div id="addon-popup-{$ic_id}" class="zoom-anim-dialog col-lg-3 col-md-6 col-sm-7 col-xs-11 center-col  bg-white text-center modal-popup-main modal fade breakupmodal" data-keyboard="false" data-backdrop="static">    
    <div class="row">
        <div class="col-md-12 center-col login-box">
            <a href="javascript(0)" class="btn-small-black-background btn btn-small button no-margin f-right" data-dismiss="modal">&times;</a>
            <!-- form title  -->
            <h2 class="text-capitalize text-center">Available Addons</h2>
            <!-- end form title  -->
            <div class=" bg-black no-margin-lr margin-three center-col"></div>
                {$nil_dep_error_block_html}
                
                    {$addon_html}
                 <!-- button  -->
                 <div class="col-md-6 margin-two text-center">
                    <button data-icid = "{$ic_id}" class="btn btn-black addons_submit no-margin-bottom btn-small btn-round no-margin" type="button" id="addons-submit">Submit</button>
                    <!-- end button  -->
                </div>
                <!-- button  -->
                 <div class="col-md-6 margin-two text-center">
                    <button data-icid = "{$ic_id}" class="btn btn-black addons_reset no-margin-bottom btn-small btn-round no-margin" type="button" id="addons-reset" data-dismiss="modal">Reset</button>
                    <!-- end button  -->
                </div>
            </div>
        </div>
</div>
START_QUOTE;
        

        }else{
            $html =  ""; 
        }
        return $html;
    }

    public function getBreakupHtml($quote_result) { 
        if (isset($quote_result['is_active']) && $quote_result['is_active']) {
            // $header = $this->getBreakupHeaderHtml($quote_result);
            // $vehicle_detail = $this->getBreakupVehicleDetailHtml($quote_result);
            // $od_breakup = $this->getBreakupOdHtml($quote_result);
            // $addon = $this->getBreakupAddonHtml($quote_result);
            // $third_party = $this->getBreakupThirdPartyHtml($quote_result);
            // $deductibles = $this->getBreakupDeductibleHtml($quote_result);
            // $premium_summary = $this->getBreakupPremiumSummaryHtml($quote_result);
            $ic_id = $quote_result['ic']['id'];
            $ic_name = $quote_result['ic']['code'];
            extract($quote_result);
            //echo "<pre>"; print_r($quote_result);die();
            $mpn_data = $this->session->userdata('mpn_data');
            $product_type_id = $mpn_data['product_type_id'];    
            $make = $mpn_data['vehicle_detail']['make'];
            $model = $mpn_data['vehicle_detail']['model'];
            $variant = $mpn_data['vehicle_detail']['variant'];
            $fuel = $mpn_data['vehicle_detail']['fuel'];
            $cc = $mpn_data['vehicle_detail']['cc'];
            $vehicle_age_year = $mpn_data['vehicle_age_year'];
            $manufacturing_year = $mpn_data['vehicle_mfg_date']['year'];
            $vehicle_idv = roundTotal($mpn_data['vehicle_idv']);
            $rto_city = strtoupper($mpn_data['rto_detail']["label"]).", ".strtoupper($mpn_data['rto_detail']["rto_city"]);
            $basic_od = roundValue($basic_od);
            $electrical_premium = roundValue($electrical_premium);
            $non_electrical_premium = roundValue($non_electrical_premium);
            $bifuel_premium_tr = roundValue($bifuel_premium_tr);
            $geographical_value_od = roundValue($geographical_value_od);
             //print_r($basic_od);die('basic_od');
            $elec_value = emptyCheck($mpn_data['accessories']["elec_value"]) ? 0 : $mpn_data['accessories']["elec_value"];
            $non_elec_value = emptyCheck($mpn_data['accessories']["non_elec_value"]) ? 0 : $mpn_data['accessories']["non_elec_value"];
			

            // $cng_value = emptyCheck($mpn_data['accessories']["non_elec_value"]) ? 0 : $mpn_data['accessories']["cng_value"];
            
            if($mpn_data['product_type_id']==1)
            {
                $cng_value = isset($mpn_data['accessories']["cng_value"]) ? $mpn_data['accessories']["cng_value"] : 0;
                // $cng_value = emptyCheck($mpn_data['accessories']["cng_value"]) ? 0 : $mpn_data['accessories']["cng_value"];
                $ll_paid_driver_premium = roundValue($ll_paid_driver_premium);
                $ll_unnamed_persons_premium = roundValue($ll_unnamed_persons_premium);
                $tp_bifuel_premium = roundValue($tp_bifuel_premium);
                $pa_paid_driver_premium = roundValue($pa_paid_driver_premium);
            }

            
            
            $addon_total = 0;
            $addon_html = '';
            if(isset($mpn_data['addons'])){
                if(isset($mpn_data['addons'][$ic_id]['addons_sum'])){
                    $addon_total = (emptyCheck($mpn_data['addons']) && emptyCheck($mpn_data['addons'][$ic_id]['addons_sum'])) ? 0 : $mpn_data['addons'][$ic_id]['addons_sum'];

                    if(!emptyCheck($mpn_data['addons'][$ic_id])){
                            if(!emptyCheck($mpn_data['addons'])){
                            
                            $addon_html .= '<thead><tr>
                                                <th width="70%">Selected Addons </th>
                                                <th>Total Addons Value</th>
                                              </tr>';
                            foreach($mpn_data['addons'][$ic_id]['list'] as  $addon){
                                $addon_html .= '<tr>
                                                <td width="70%">'.$addon['name'].'</td>
                                                <td>'.$addon['amount'].'</td>
                                              </tr>';
                            }
                            $addon_html .= '</thead><tbody><tr>
                                                <td width="70%">Total Amount</td>
                                                <td>'.$mpn_data['addons'][$ic_id]['addons_sum'].'</td>
                                              </tr><tbody>';
                            }else{
                                $addon_html .= '<tr>
                                                <td width="100%">No Addon Selected</td>
                                              </tr>';
                        }
                    }        
                }   
            }


            $basic_ttpd = roundValue($basic_ttpd);
            $pa_owner_driver_premium = roundValue($pa_owner_driver_premium);
            
            $aa_value = (isset($mpn_data['deductibles']['automobile_association'])&& ($mpn_data['deductibles']['automobile_association'] == 'true'))?roundValue($aa_value):0;
            if($product_type_id==1)
            {
                $bifuel_premium_tr = '<tr>
                    <td width="70%"><strong>Bifuel Premium</strong></td>
                    <td>'.$bifuel_premium.'</td>
                    </tr>';
                
                $tp_bifuel_premium_tr = ' <tr>
                                    <td width="70%">CNG/LPG</td>
                                    <td>'.roundValue($tp_bifuel_premium).'</td>
                                  </tr>';  

                $aa_association_value_tr = '<tr>
                                    <td width="70%">Automobile Association</td>
                                    <td>'.roundValue($aa_value).'</td>
                                  </tr>';  
                $pa_paid_driver_premium_tr = '<tr>
                                       <td width="70%"><strong>PA Cover To Paid-Driver</strong></td>
                                       <td>'.roundValue($pa_paid_driver_premium).' </td>
                                     </tr>';      

                $ll_paid_driver_premium_tr  = ' <tr>
                                        <td width="70%"><strong>Legal Liability to Paid Driver</strong></td>
                                        <td>'.roundValue($ll_paid_driver_premium).'</td>
                                      </tr>';    
                $ll_unnamed_persons_premium_tr = ' <tr>
                                                <td width="70%"><strong>Legal Liability to Unknown Person</strong></td>
                                                <td>'.roundValue($ll_unnamed_persons_premium).'</td>
                                              </tr>';                                                 

            }
            else
            {
                $bifuel_premium_tr = "";
                $tp_bifuel_premium_tr = "";
                $aa_association_value_tr = "";
                $pa_paid_driver_premium_tr = "";
                $ll_paid_driver_premium_tr = "";
                $ll_unnamed_persons_premium_tr = '';
            }
            
           $total_discount = roundValue($total_discount);
           $addon_total = roundValue($addon_total);
           $total_premium_without_tax = roundTotal($total_premium_without_tax);
           $total_thirdparty_premium = roundTotal($total_thirdparty_premium);
           $gst = roundValue($gst);
           $net_od = roundTotal($net_od);
           $gross_premium = roundTotal($gross_premium);
            $geographical_value_tp = roundValue($geographical_value_tp);
            $pa_unnamed_persons_premium = roundValue($pa_unnamed_persons_premium);
            $antitheft_value = roundValue($antitheft_value);
            $ncb_value = roundValue($ncb_value);
            $total_discount = roundTotal($total_discount);

            $html = <<<START_QUOTE
                <div id="brekup-popup-{$ic_id}" class="zoom-anim-dialog  col-lg-9 col-md-9 center-col bg-white text-center modal-popup-main modal fade breakupmodal" data-keyboard="false" data-backdrop="static">    
    <div class="row">
        <div class="col-md-12 center-col login-box">
            <a href="javascript(0)" class="btn-small-black-background btn btn-small button no-margin f-right" data-dismiss="modal">&times;</a>
            <!-- form title  -->
            <h2 class="text-capitalize text-left">Premium Breakup for {$ic_name}</h2>
            <!-- end form title  -->
            <div class=" bg-black no-margin-lr margin-three center-col"></div>
            <!-- tab -->
                        <div class="tab-style2">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <!-- tab navigation -->
                                    <ul class="nav nav-tabs nav-tabs-light text-left">
                                        <li class="active"><a href="#vehicle-detail-tab-{$ic_id}" data-toggle="tab">Vehicle Details</a></li>
                                        <li><a href="#od-break-up-tab-{$ic_id}" data-toggle="tab">OD Break Up</a></li>
                                        <li><a href="#addons-tab-{$ic_id}" data-toggle="tab">Addons</a></li>
                                        <li><a href="#third-party-tab-{$ic_id}" data-toggle="tab">Third Party Breakup</a></li>
                                        <li><a href="#deductubles-tab-{$ic_id}" data-toggle="tab">Deductibles</a></li>
                                        <li><a href="#premium-summary-tab-{$ic_id}" data-toggle="tab">Premium Summary</a></li>
                                    </ul>
                                    <!-- end tab navigation -->
                                </div>
                            </div>
                            <!-- tab content section -->
                            <div class="tab-content">
                                <!-- tab content -->
                                <div class="tab-pane med-text fade in active" id="vehicle-detail-tab-{$ic_id}">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 no-padding">
                                           <table class="table table-responsive table-sm table-striped text-left mob-dis-block" data-tablesaw-mode="stack">
                                                <thead>
                                                   <tr>
                                                    <th data-tablesaw-sortable-col="" data-tablesaw-sortable-default-col="" data-tablesaw-priority="persist">make model variant</th>
                                                    <th data-tablesaw-sortable-col="" data-tablesaw-priority="3">IDV</th>
                                                    <th data-tablesaw-sortable-col="" data-tablesaw-priority="1">Age</th><th data-tablesaw-sortable-col="" data-tablesaw-priority="4">CC</th>
                                                    <th data-tablesaw-sortable-col="" data-tablesaw-priority="5">Fuel</th>
                                                    <th data-tablesaw-sortable-col="" data-tablesaw-priority="2">Year</th>
                                                    <th data-tablesaw-sortable-col="" data-tablesaw-priority="7">RTO</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                   <tr>
                                                    <td>{$make} {$model} {$variant} </td>
                                                    <td>{$vehicle_idv}</td>
                                                    <td>{$vehicle_age_year}</td>
                                                    <td>{$cc}</td>
                                                    <td>{$fuel}</td>
                                                    <td>{$manufacturing_year}</td>
                                                    <td>{$rto_city}</td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- end tab content -->
                                <!-- tab content -->
                                <div class="tab-pane fade in" id="od-break-up-tab-{$ic_id}">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 no-padding-bottom">
                                            <h3 class="margin-one">Basic OD Premium (+)</h3>
                                            <table class="table table-responsive table-sm table-striped text-left">
                                                <thead>
                                                    <tr>
                                                    <td width="70%"><strong>Basic OD</strong></td>
                                                    <td>{$basic_od}</td>
                                                        </tr>
                                                  <tr>
                                                    <td width="70%"><strong>Electrical Accessory Premium</strong></td>
                                                    <td>{$electrical_premium}</td>
                                                  </tr>
                                                           <tr>
                                                              <td width="70%"><strong>Non Electrical Accessory Premium</strong></td>
                                                              <td>{$non_electrical_premium} </td>
                                                            </tr>
                                                  
                                                  {$bifuel_premium_tr}

                                                    <tr>
                                                    <td width="70%"><strong>Total Geographical Extension</strong></td>
                                                    <td>{$geographical_value_od}</td>

                                                  </tr>

                                                </thead>
                                                <tbody>
                                           <tr>
                                                    <th width="70%"><b>(A) Total OD Premium</b></th>
                                                    <th>{$net_od}</th>
                                                  </tr>
                                                </tbody>
                                              </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- end tab content -->
                                                    
                                                    
                                <!-- tab content -->
                                <div class="tab-pane fade in" id="addons-tab-{$ic_id}">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 no-padding-bottom">
                                            <h3 class="margin-one">Applicable Add-On (+)</h3>
                                            <table class="table table-responsive table-sm table-striped text-left">
                                                {$addon_html}
                                              </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- end tab content -->
                                <!-- tab content -->
                                <div class="tab-pane fade in" id="third-party-tab-{$ic_id}">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 no-padding-bottom">
                                            <h3 class="margin-one">TP Premium Calculation (+)</h3>
                                            <table class="table table-responsive table-sm table-striped text-left">
                                                <thead>
                                                    <tr>
                                                    <td width="70%"><strong>Basic Third Party Liability</strong></td>
                                                    <td>{$basic_ttpd}</td>
                                                        </tr>
                                                    {$tp_bifuel_premium_tr}
                                                  <tr>
                                                    <td width="70%">Compulsary PA to Owner-Driver</td>
                                                    <td>{$pa_owner_driver_premium}</td>
                                                  </tr>
                                                    {$pa_paid_driver_premium_tr}
                                                   <tr>
                                                    <td width="70%"><strong>PA Cover To persons other than Owner-Driver</strong></td>
                                                    <td>{$pa_unnamed_persons_premium}</td>
                                                    </tr>

                                                    {$ll_paid_driver_premium_tr}
                                                    {$ll_unnamed_persons_premium_tr}
                                                    <tr>
                                                    <td width="70%"><strong>Total Geographical Extension TP</strong></td>
                                                    <td>{$geographical_value_tp}</td>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                           <tr>
                                                    <th width="70%"><b>(D) Total TP Premium</b></th>
                                                    <th >{$total_thirdparty_premium}</th>
                                                  </tr>
                                                </tbody>
                                              </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- end tab content -->
                                <!-- tab content -->
                                <div class="tab-pane fade in" id="deductubles-tab-{$ic_id}">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 no-padding-bottom">
                                            <h3 class="margin-one">Deductibles (-)</h3>
                                            <table class="table table-responsive table-sm table-striped text-left">
                                                <thead>
                                                    <tr>
                                                    <td width="70%"><strong>Anti-Theft Discount</strong></td>
                                                    <td>{$antitheft_value}</td>
                                                        </tr>
                                                   {$aa_association_value_tr}
                                                   <tr>
                                                      <td width="70%"><strong>No Claim Bonus ({$ncbpercentage})%</strong></td>
                                                      <td>{$ncb_value} </td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                           <tr>
                                                    <th width="70%"><b>(B) Total Discount</b></th>
                                                    <th >{$total_discount}</th>
                                                  </tr>
                                                </tbody>
                                              </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- end tab content -->
                                                    
                                                    
                                <!-- tab content -->
                                <div class="tab-pane fade in" id="premium-summary-tab-{$ic_id}">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 no-padding-bottom">
                                            <h3 class="margin-one">Premium Summary</h3>
                                            <table class="table table-responsive table-sm table-striped text-left">
                                                <thead>
                                                    <tr>
                                                    <td width="70%"><strong>(A) Total OD Premium</strong></td>
                                                    <td>{$net_od}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="70%">(B) Total Discount</td>
                                                        <td>{$total_discount}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="70%"><strong>(C) Total Add On Premium</strong></td>
                                                        <td>{$addon_total} </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="70%"><strong>(D) Total TP Premium</strong></td>
                                                        <td>{$total_thirdparty_premium}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="70%"><strong>Total Premium</strong></td>
                                                        <td>{$total_premium_without_tax}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="70%"><strong>GST</strong></td>
                                                        <td>{$gst}</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th width="70%"><b>Total Premium Payable</b></th>
                                                        <th>{$gross_premium}</th>
                                                  </tr>
                                                </tbody>
                                              </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- end tab content -->
                            </div>
                            <!-- end tab content section -->
                        </div>
                        <!-- end tab -->
        </div>
    </div>
</div>
<!-- end brekup popup -->
START_QUOTE;
            return $html;
        }
    }

    function field_value_exist($val, $column_name, $table_name) {

        //$query = $this->db->where($column_name,$val)-> $this->db->get($table_name) -> result();
        $query = $this->db->get_where($table_name, array($column_name => $val))->result();
        if ($query) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    public function getDiseaseList($ic_id) {
        $result = $this->db->query("SELECT * FROM `disease_master` where ic_id='$ic_id'")->result_object();
        return $result;
        return $result;
    }

    public function getNomineeList($ic_id) {
        $result = $this->db->query("SELECT * FROM `nominee_relation_master` where ic_id='$ic_id'")->result_object();
        return $result;
    }

    public function occupationList($ic_id) {
        $result = $this->db->query("SELECT * FROM `occupation_master`")->result_object();
        return $result;
    }

    function insert_transaction_online_data($insert_id) {
        $proposal_list_rewamp = $this->db->where('id', $insert_id)
            ->get('proposal_list_rewamp')
            ->row_array();

        $quote_data = $proposal_list_rewamp['pg_responded_data'];
        $ic_id = $proposal_list_rewamp['ic_id'];
        $ic_short_code = $proposal_list_rewamp['ic_quote']['ic']['short_code'];
        $policy_number = $proposal_list_rewamp['policy_no'];
        $final_amount = $proposal_list_rewamp['final_amount'];

        if ($ic_id != '26') {
            $receipt_no = date('Ymdhms') . '' . (rand(10, 100));
            $result_data = $this->db->select('receipt_no')
                ->from('policy_transaction_receipt_rewamp')
                ->where('receipt_no', $receipt_no)
                ->get()
                ->row_array();



            $db_check_counter = $this->db->count_all_results('policy_transaction_receipt_rewamp');
            while ($db_check_counter > 0) {
                /*
                  $quote_forward_link = generateQuoteForwardLink($quote_forward_link);
                 */
                $receipt_no = date('Ymdsmh') . '' . (rand(10, 100));
                $this->db->where('receipt_no', $receipt_no);
                $db_check_counter = $this->db->count_all_results('policy_transaction_receipt_rewamp');
            }
        }

        switch ($ic_id) {

            case 3:
                $pg_responded_data = $proposal_list_rewamp['pg_responded_data'];
                $array_pg_transaction = (explode("|", $pg_responded_data));
                $transaction_number = $array_pg_transaction['2'];
                /* YYYY-MM-DD HH:MM:SS */
                $transaction_date = date('Y-m-d h:m:s', strtotime($array_pg_transaction['13']));
                break;
            case 8:
                $pg_responded_data = $proposal_list_rewamp['pg_responded_data'];
                $array_json_data = json_decode($pg_responded_data);
                $transaction_number = $array_json_data->ProposalNo;
                $transaction_date = date('Y-m-d h:m:s');
                break;

            case 23:
                $transaction_number = "DUMMY";
                $transaction_date = "DUMMY";
                break;



            case 25:
                $receipt_no = date('Ymdsmh') . '' . (rand(10, 100));
                $transaction_number = "DUMMY";
                $transaction_date = "DUMMY";
                $receipt_no = $receipt_no;
                break;

            case 14:
                $pg_responded_data = json_decode($proposal_list_rewamp['pg_responded_data']);
                $transaction_number = $pg_responded_data->txnid;
                $transaction_date = $pg_responded_data->addedon;
                $final_amount = $pg_responded_data->net_amount_debit;
                break;

            case 26:
                $pg_responded_data = json_decode($proposal_list_rewamp['pg_responded_data']);

                $array_pg = $pg_responded_data->after_payment->msg;
                $array_pg_transaction = (explode("|", $array_pg));
                $receipt_no = $array_pg_transaction['1'];
                $transaction_number = $array_pg_transaction['2'];
                $transaction_date = date('Y-m-d h:m:s', strtotime($array_pg_transaction['13']));
                break;
            case 6:
                $pg_responded_data = json_decode($proposal_list_rewamp['pg_responded_data'], true);
                // $pg_responded_data = json_decode($pg_responded_data, true);
                $transaction_number = $pg_responded_data['TID'];
                $transaction_date = date('Y-m-d h:m:s', strtotime($transaction_date->created));
                break;
        }

         $policy_transaction_receipt_array=array(
          'policy_id' =>$insert_id,
          'ic_id' =>$ic_id,
          'policy_no' =>$policy_number,
          'total_premium' =>$final_amount,
          'payment_date' =>date('Y-m-d h:i:s'),
          'transaction_type_id' =>'1',
          'transaction_no' =>$transaction_number,
          'references_no' =>getReferenceNumber($proposal_list_rewamp['ic_quote']['ic']['short_code'],$insert_id),
          'receipt_no' =>$receipt_no,

          );
        $this->db->insert_ignore('policy_transaction_receipt_rewamp',$policy_transaction_receipt_array); 
        return $receipt_no;
    }




      function get_data($select_what, $table_name, $where_condition = '') {

        if ($where_condition) {
            $query = $this->db->query("select $select_what from $table_name where $where_condition")->result();
        } else {
            $query = $this->db->query("select $select_what from $table_name ")->result();
        }

        
        return $query;
    }

      public function getNomineeRelationList($id) {
        if ($id) {
            $result = $this->db->query("SELECT * FROM `nominee_relation_master` WHERE `id` = $id")->result_object();
        }
        return $result[0];
    }



   public function sendMailToPosCustomerGib($id) {
      $data = $this->db->where('id',$id)->get('proposal_list_rewamp')->result();
            
//            $ic_id = 6;
            $result = $data[0];
            $ic_id = $data[0]->ic_id;
            $prop_number = $data[0]->proposal_no;
            $issue_date = $data[0]->created;
            $product_type = $data[0]->product_type_id;

            $quote_data = json_decode($data[0]->quote_data);


            if ($product_type == '1') {
                if (!empty($quote_data->vehicle_detail->body_desc)) {
                    $body_desc = $quote_data->vehicle_detail->body_desc;
                } else {
                    $body_desc = '--';
                }
            }
            if ($product_type == '2') {
                if (!empty($quote_data->vehicle_detail->vehicle_body)) {
                    $body_desc = $quote_data->vehicle_detail->vehicle_body;
                } else {
                    $body_desc = '--';
                }
            }

             $company_name_data = $quote_data->ic_quote->ic->code;

            $json_data_customer = json_decode($data[0]->quote_data);
//            echo '<pre>';print_r($json_data_customer);exit;
//            $ic_id = $json_data_customer->seller_office->ic_id;

            ///insurance buyer details 
        $elec_value =  $json_data_customer->accessories->elec_value;
        $non_elec_value = $json_data_customer->accessories->non_elec_value;

            $salutaion = $json_data_customer->customer_quote->vehicle_owner->salutaion;
            $engine_number = $json_data_customer->customer_quote->vehicle_detail->engine_number;
            
            $first_name = $json_data_customer->customer_quote->vehicle_owner->first_name;
            $last_name = $json_data_customer->customer_quote->vehicle_owner->last_name;
            $email = $json_data_customer->customer_quote->vehicle_owner->email;
            $mobile_no = $json_data_customer->customer_quote->vehicle_owner->mobile_no;
            $vehicle_fuel_type = $json_data_customer->vehicle_detail->fuel;
            $rto_state = $json_data_customer->rto_detail->state_name;

            //END
            //insurance buyer Address Details
         $address= $json_data_customer->customer_quote->address_detail->address1.'&nbsp;'.$json_data_customer->customer_quote->address_detail->address2.'&nbsp;'.$json_data_customer->customer_quote->address_detail->state->name.'&nbsp;'.$json_data_customer->customer_quote->address_detail->city->name.'&nbsp;'.$json_data_customer->customer_quote->address_detail->pincode;

            $insurance_buyer_personal_address = $address;
            $reg_date = $json_data_customer->customer_quote->vehicle_detail->reg_date;
            $reg_add = $json_data_customer->seller_office->reg_add;
            $gstin = $json_data_customer->seller_office->gstin;
        if(empty($gstin)){
        $result =  $this->db->select('gstin')->from('seller_office')->where('ic_id',$ic_id)->where('state_name',$rto_state)->get()->result_array();
        $gstin = isset($result[0]['gstin'])?$result[0]['gstin']:'';
        }
            // //POS DEtails
            // $pos_name = $_SESSION['pos_name'];
            // $pos_email = $_SESSION['email'];
            // $pos_mobile = $_SESSION['mobile'];
            // $pos_id = $_SESSION['pos_userid'];
            // $data_pos = $this->First_model->get_pos_details($pos_id);
            // $pos_aadhar_no = $data_pos->aadhar_no;

            ///select car color details
            $car_information = $json_data_customer->customer_quote->vehicle_detail;
            $color = $car_information->car_color;
            $registration_date = date('d-m-Y', strtotime($customer_quote->reg_date));
            $engine_number = $car_information->engine_number;
            $car_numbers = $car_information->car_numbers;
            $car_letters = $car_information->car_letters;
            $chassis_number = $car_information->chassis_number;
            $agreement_type = $car_information->agreement_type;
            $bank_type = $car_information->bank_type;

            ///accessories car

            $car_accessories = $json_data_customer->accessories; //array
            $elec_value = $car_accessories->elec_value;
            $non_elec_value = $car_accessories->non_elec_value;
            $bi_fuel_val = $car_accessories->bi_fuel_val;
            $cng_val = $car_accessories->cng_val;

            $address_1 = $insurance_buyer_personal_address->add_1;
            $address_2 = $insurance_buyer_personal_address->add_2;
            $city_name = ucwords(strtolower($city_details_fetch_query->CityName));
            $state_name = ucwords(strtolower($state_details_fetch_query->StateName));
            $pincode = $insurance_buyer_personal_address->pincode;
            //End
            //IC Details
            $company = $json_data_customer->ic_detail->InsuranceCompany_Name;
            //
            //insurance buyer Vehicle Details
            $insurance_buyer_vehicle_details = $json_data_customer->vehicle_detail;
            $manufacturer = $insurance_buyer_vehicle_details->manufacturer;
            $model = $insurance_buyer_vehicle_details->model;
            $varient = $insurance_buyer_vehicle_details->varient;
            $vehicle_cc = $insurance_buyer_vehicle_details->vehicle_cc;
            $seating_capacity = $insurance_buyer_vehicle_details->seating_capacity;
            $fuel_desc = $insurance_buyer_vehicle_details->fuel_desc;


            $RTO_Code = $json_data_customer->rto_details->RTO_Code;
            $idv = $json_data_customer->ic_quote->vehicle_idv;

            $bodytype = $json_data_customer->vehicle_detail->body_type;

            //print_r($bodytype);exit;

            $posdetails = $json_data_customer->proposal_data;
            $posdetails = $this->getposdetails($data[0]->agent_id);
            $posdetailsextra = $this->getposdetailsextra($data[0]->agent_id);
            //print_r($data);exit;

             
            //print_r($posdetails);exit;
            ///Logo company Wise 

            $site_url = base_url();

            //print_r(json_decode($data[0]->user_action_data));exit;

            if(preg_match("/commercial/i",json_decode($data[0]->user_action_data)->product_type))
             {
                 $carrier_type = "Commercial";
             } else {
                 $carrier_type = "Private";
             }

             //print_r($carrier_type);exit;



           
            if ($ic_id == 3) {
                $logo = "<img src='" . base_url() . "assets/images/basic_logo.jpg'  width='130' height='80'/>";
            }
            if ($ic_id == 8) {
                $logo = "<img src='" . base_url() . "assets/images/hdfc_ergo1.jpg'  width='130' height='80'/>";
            }
            if ($ic_id == 25) {
                $logo = "<img src='" . base_url() . "assets/images/tata.png'  width='130' height='80'/>";
            }
            if ($ic_id == 6) {
                $logo = "<img src='" . base_url() . "assets/images/futuregenerali.jpg'  width='130' height='80'/>";
            }
            if ($ic_id == 14) {
                $logo = "<img src='" . base_url() . "assets/images/liberty_logo.jpg'  width='130' height='80'/>";
            }
            if ($ic_id == 23) {
                $logo = "<img src='" . base_url() . "assets/images/shriram_genralinsurance.png'  width='130' height='80'/>";
            }
             if ($ic_id == 26) {
                $logo = "<img src='" . base_url() . "assets/images/nia-logo.jpg'  width='130' height='80'/>";
            }
            if ($ic_id == 16) {
                $logo = "<img src='" . base_url() . "assets/images/magma.jpg'  width='130' height='80'/>";
            }
            if ($result->new_policy_type == "yes") {
                $pol_heading_type = "PRIVATE VEHICLE-PACKAGE POLICY-CERTIFICATE CUM POLICY SCHEDULE";
            } elseif ($result->new_policy_type == "no") {
                $pol_heading_type = "MOTOR SECURE - PRIVATE CAR INSURANCE - LIABILITY ONLY POLICY";
            }

            /*  if ($ic_id == 3) {
              $comname = "Bharti Axa General Insurance";
              $prop_number = $proposal_table->proposal_no;
              } 


            /*  $colour = strtoupper($result->car_colour); */

            $our_company_logo = "<img src='" . base_url() . "assets/images/logo.png'  width='230' height='80'/>";







                $html_content =  '<br><br><br><div style="max-width: 980px; margin: auto;" class="email-container">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 980px; font-family: arial, sans-serif;border:0px;">
                <tbody><tr>
                    <td style="padding: 20px 0; text-align: left">
                  '.$logo.'
                    </td>
                    <td colspan="3" style="padding: 20px 0; text-align: center">
                    <h2>'.$company_name_data.'</h2>
                      <h3 style="margin: 0;">(Request for surveyor allotment)</h3>
                      <h4>GSTIN&nbsp;:'.$gstin.'</h4>
                    </td>
                    <td style="padding: 20px 0; text-align: right">
                        '.$our_company_logo.'
                         </td>
                </tr>
            </tbody></table>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 980px; font-family: arial, sans-serif;border:0px;">
                <tbody><tr>
                    <td style="padding: 10px 0; text-align: center; background-color: #1a4e70; font-weight: bold;text-transform: uppercase; color: #fff;">
                        Insurance Details
                    </td>
                </tr>
            </tbody></table>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 980px; font-family: arial, sans-serif;border:0px;">
                <tbody><tr>
                    <td valign="top" style="padding: 0px 0;text-align: center">
                      <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 980px; font-family: arial, sans-serif;font-size: 13px;">
                            <tbody><tr>
                                <td valign="top" style="padding: 10px 12px;text-align: left; font-weight: bold; background-color: #f9f9f9">
                                    Insured Name
                                </td>
                                <td valign="top" style="padding: 10px 12px; text-align: left">
                                    '.strtoupper($salutaion).'&nbsp;'.strtoupper($first_name).'&nbsp;'.strtoupper($last_name).'
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" style="padding: 10px 12px;text-align: left; font-weight: bold; background-color: #f9f9f9">
                                    Insured Add.
                                </td>
                                <td valign="top" style="padding: 12px 12px; text-align: left">
                          '.$address.'
                                </td>
                            </tr>
                        </tbody></table>
                    </td>
                    <td style="padding: 0px 0; text-align: center">
                       <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 980px; font-family: arial, sans-serif;font-size: 13px;">
                            <tbody><tr>
                                <td valign="top" style="padding: 10px 12px;text-align: left; font-weight: bold; background-color: #f9f9f9">
                                    Proposal No. &amp; Date
                                </td>
                                <td valign="top" style="padding: 10px 12px; text-align: left">
                                   ' . $prop_number . '&nbsp;' . $issue_date . '
                              
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" style="padding: 10px 12px;text-align: left; font-weight: bold; background-color: #f9f9f9">
                                    Insured Mobile
                                </td>
                                <td valign="top" style="padding: 10px 12px; text-align: left">
                                  '.$mobile_no.'
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" style="padding: 10px 12px;text-align: left; font-weight: bold; background-color: #f9f9f9">
                                  Insured Email
                                </td>
                                <td valign="top" style="padding: 10px 12px; text-align: left">
                                  '.$email.'
                                </td>
                            </tr>
                        </tbody></table>
                    </td>
                </tr>
            </tbody></table>
  <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 980px; font-family: arial, sans-serif;border:0px;">
                <tbody><tr>
                    <td style="padding: 10px 0; text-align: center; background-color: #1a4e70; font-weight: bold;text-transform: uppercase; color: #fff;">
                        Vehicle Details
                    </td>
                </tr>
            </tbody></table>
  <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 980px; font-family: arial, sans-serif;border:0px;">
                <tbody><tr>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                  Make
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                Model
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                               Variant
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                Cubic Capacity/GVW
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                 Color
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                 Seating Capacity
                                </td>
                </tr>

                <tr>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                  '.$json_data_customer->vehicle_detail->make.'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                '.$json_data_customer->vehicle_detail->model.'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                               '.$json_data_customer->vehicle_detail->variant.'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                '.$json_data_customer->vehicle_detail->cc.'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                 '.strtoupper($json_data_customer->customer_quote->vehicle_detail->car_color).'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                '.$json_data_customer->vehicle_detail->seating_capacity.'
                                </td>
                </tr>
  <tr>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                  Body Type
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                Registration Date
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                               RTO
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                             Registration No
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                 Fuel Type
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                Chassis No.
                                </td>
                </tr>

                <tr>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                           '.$bodytype.'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                '.$json_data_customer->customer_quote->vehicle_detail->reg_date.'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                              '.strtoupper($json_data_customer->rto_detail->label).'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                '.strtoupper($json_data_customer->customer_quote->vehicle_detail->veh1).'&nbsp;'.strtoupper($json_data_customer->customer_quote->vehicle_detail->veh2).'&nbsp;'.strtoupper($json_data_customer->customer_quote->vehicle_detail->veh3).'&nbsp;'.strtoupper($json_data_customer->customer_quote->vehicle_detail->veh4).'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                 '.$json_data_customer->vehicle_detail->fuel.'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                  '.strtoupper($chassis_number).'
                                </td>
                </tr>
   

      <tr>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                  Elec.Accessories
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                Non-Elec.Accessories
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                               Vehicle Sub Class
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                             Carrier Type
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                CNG/LPG Kit
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                Engine No.
                                </td>
                </tr>

                <tr>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                 '.$elec_value.'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                  '.$non_elec_value.'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                               --
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                               '.$carrier_type.'
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                -
                                </td>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px;background-color: #ffffff">
                                    '.strtoupper($engine_number).'                                    
                                </td>
                </tr>


                <tr>
                     <td  valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                Trailer
                                </td>
                     <td colspan="2" valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                Hypothecation/Lease/HP*
                                </td>
                     <td colspan="2" valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                 Vehicle IDV
                                </td>
                                <td  valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                 Cng.Accessories
                                </td>
                </tr>
                <tr>
                     <td valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                               --
                                </td>
                     <td colspan="2" valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                '.ucwords($json_data_customer->customer_quote->vehicle_detail->agreement_type).' / '.$json_data_customer->customer_quote->vehicle_detail->agreement_bank_array->BankName.'
                                </td>
                     <td colspan="2" valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                '.round($quote_data->vehicle_idv).'
                                </td>
                                <td  valign="top" style="padding: 10px 12px;text-align: left; font-size:13px; font-weight: bold; background-color: #f9f9f9">
                                '.round($cng_val).'
                                </td>
                </tr>

               
  

            </tbody></table>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 980px; font-family: arial, sans-serif;border:0px;">
                <tbody><tr>
                    <td colspan="4" style="padding: 10px 0; text-align: center; background-color: #1a4e70; font-weight: bold;text-transform: uppercase; color: #fff;">
                        POS Details
                    </td>
                </tr>  
                <tr>
                    <td valign="top" style="padding: 10px 5px;text-align: center; font-size:13px; font-weight: lighter; background-color: #fff">
                              <b>POS Name: </b> '.$posdetails->username.'
                    </td>
                    <td valign="top" style="padding: 10px 5px;text-align: center; font-size:13px; font-weight: lighter; background-color: #fff">
                               <b> POS AadharNO:</b> '.$posdetailsextra->aadhar_card_no.'
                    </td>
                    <td valign="top" style="padding: 10px 5px;text-align: center; font-size:13px; font-weight: lighter; background-color: #fff">
                              <b>  POS Email: </b>'.$posdetails->email.'
                    </td>
                    <td valign="top" style="padding: 10px 5px;text-align: center; font-size:13px; font-weight: lighter; background-color: #fff">
                              <b>  POS Mobile:</b> '.$posdetails->mobile_number.'
                    </td>
                </tr>
                  <tr>
                    <td colspan="4" style="padding: 10px 5px; text-align: center; background-color: #fff; font-weight: lighter;text-transform: uppercase; color: #000;font-size: 12px;">
                       <b>Brokers Name &amp; Add :</b> <br> Global-India Insurance Brokers Private Limited, Flat No.302, F/P- III, F 92, Front Portion, <br> Krishnanagar, Dist. Hauz Khas, New Delhi - 110029, India.


                    </td>
                </tr>
            </tbody></table>
            
  
<br>
                    </div>';

         //print_r($html_content);die();

        
          // echo  $html_content;exit;
            $update_proposal_list = array(
                "proposal_status_id" =>1,
                "breakin_status_id" => 8,
            );
           
            $data['middle'] = "error";
            // $data_ = $query->result();
            // $ic_id = $data_[0]->ic_id;
            // $json_data_customer = json_decode($data_[0]->quote_data);
           // $email = $this->session->userdata('mpn_data')['customer_quote']['vehicle_owner']['email'];
            $to = $email;
            $subject = "Breakin-In Case Initited By Pos";
            $headers = "From: " . strip_tags('info@mypolicynow.com') . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $message = "";
           
            //print_r($html_content);die();
                                             
            if (mail($to, $subject, $html_content, $headers)) {
                      $this->db->where("id", $id);
                $this->db->update("proposal_list_rewamp", $update_proposal_list);
                //$data['class']= 'success';
                //echo $data['message']= 'Your information has been successfully sent. Thank you.';
                $mail = $to;
                $data['message']['class'] = "success";
                $_SESSION['session_data_arr_car']['proposal_action']['message'] = "Your information has been successfully sent to " . strtolower($email) . ". Thank you";
                $result_status['status'] = true;
                //die();
            } else {

                $mail = $to;
                // $data['class']= 'error';
                $data['message']['class'] = "error";
                $_SESSION['session_data_arr_car']['proposal_action']['message'] = "Error in sending mail to " . strtolower($email) . "Please try again later";
                $result_status['status'] = false;
                //echo $data['message']= 'Error in sending mail. Please try again later';
                ///die();
            }

    }
     /*Add one query*/

       public function getSellerOfficedata($ic_id) {
        $result = $this->db->query("SELECT * FROM `seller_office` WHERE `ic_id` = $ic_id limit 1")->row_array();
        return $result;
    }

    public function getValidPincodeCheck($pincode) {
        $query = $this->db->query("SELECT * FROM country_master_with_pincode where Pin_code='$pincode' ");
        $result = $query->result_array();
        return $result;
    }

      public function getDiseaseSingleRecords($id) {
        $result = $this->db->query("SELECT * FROM `disease_master` where id='$id'")->row_array();
        return $result;
    }

    
     function getCommercialBreakupHtml($quote_result) {
        if (isset($quote_result['is_active']) && $quote_result['is_active']) {
            // $header = $this->getBreakupHeaderHtml($quote_result);
            // $vehicle_detail = $this->getBreakupVehicleDetailHtml($quote_result);
            // $od_breakup = $this->getBreakupOdHtml($quote_result);
            // $addon = $this->getBreakupAddonHtml($quote_result);
            // $third_party = $this->getBreakupThirdPartyHtml($quote_result);
            // $deductibles = $this->getBreakupDeductibleHtml($quote_result);
            // $premium_summary = $this->getBreakupPremiumSummaryHtml($quote_result);
            $ic_id = $quote_result['ic']['id'];
            $ic_name = $quote_result['ic']['code'];
            extract($quote_result);//print_r($quote_result);exit;
            $mpn_data = $this->session->userdata('mpn_data');
            $product_type_id = $mpn_data['product_type_id'];    
            $make = $mpn_data['vehicle_detail']['make'];
            $model = $mpn_data['vehicle_detail']['model'];
            $variant = $mpn_data['vehicle_detail']['variant'];
            $fuel = $mpn_data['vehicle_detail']['fuel'];
            $cc = $mpn_data['vehicle_detail']['cc'];
            $vehicle_age_year = $mpn_data['vehicle_age_year'];
            $manufacturing_year = $mpn_data['vehicle_mfg_date']['year'];
            $vehicle_idv = roundTotal($mpn_data['vehicle_idv']);
            $rto_city = strtoupper($mpn_data['rto_detail']["label"]).", ".strtoupper($mpn_data['rto_detail']["city_name"]);
            $basic_od = round($basic_od,2);
            $bifuel_premium_tr = roundValue($bifuel_premium_tr);
            
            if($mpn_data['third_party']!="third_party"){            

                $default_imt_23 = round($basic_od*15/100);
                $default_imt_34 = round($basic_od*25/100);  
             }
                

            $elec_value = emptyCheck($mpn_data['accessories']["elec_value"]) ? 0 : $mpn_data['accessories']["elec_value"];
            $non_elec_value = emptyCheck($mpn_data['accessories']["non_elec_value"]) ? 0 : $mpn_data['accessories']["non_elec_value"];
            //echo $non_elec_value;exit;

            $ll_pa_cover_cleaner_conductor_coolie_premium = $ll_pa_cover_cleaner_conductor_coolie_premium;

           // $pa_paid_driver_premium = (($mpn_data['pa_covers']['pa_paid_driver_value']/100000)*50)*($mpn_data['pa_covers']['pa_paid_driver_number']); 

            $totalcleaner_conductor_coolie = $totalcleaner_conductor_coolie; 


                /*if ($mpn_data['pa_covers']['ll_unnamed_persons_value'] > 0) {//seating
                    $ll_unnamed_persons_premium=100 * $mpn_data['vehicle_detail']['seating_capacity'];
                }
                */

            $cng_value = isset($mpn_data['accessories']["cng_value"]) ? $mpn_data['accessories']["cng_value"] : 0;
            // $cng_value = emptyCheck($mpn_data['accessories']["cng_value"]) ? 0 : $mpn_data['accessories']["cng_value"];
            $ll_paid_driver_premium = roundValue($ll_paid_driver_premium);
            $ll_unnamed_persons_premium = roundValue($ll_unnamed_persons_premium);
            $tp_bifuel_premium = roundValue($tp_bifuel_premium);
            $pa_paid_driver_premium = roundValue($pa_paid_driver_premium);
            
            $ll_owner_driver_value = $ll_owner_driver_premium;
            
            $addon_total = 0;
            $addon_html = '';
            if(isset($mpn_data['addons'])){
                if(isset($mpn_data['addons'][$ic_id]['addons_sum'])){
                    $addon_total = (emptyCheck($mpn_data['addons']) && emptyCheck($mpn_data['addons'][$ic_id]['addons_sum'])) ? 0 : $mpn_data['addons'][$ic_id]['addons_sum'];

                    if(!emptyCheck($mpn_data['addons'][$ic_id])){
                            if(!emptyCheck($mpn_data['addons'])){
                            
                            $addon_html .= '<thead><tr>
                                                <th width="70%">Selected Addons </th>
                                                <th>Total Addons Value</th>
                                              </tr>';
                            foreach($mpn_data['addons'][$ic_id]['list'] as  $addon){
                                $addon_html .= '<tr>
                                                <td width="70%">'.$addon['name'].'</td>
                                                <td>'.$addon['amount'].'</td>
                                              </tr>';
                            }
                            $addon_html .= '</thead><tbody><tr>
                                                <td width="70%">Total Amount</td>
                                                <td>'.$mpn_data['addons'][$ic_id]['addons_sum'].'</td>
                                              </tr><tbody>';
                            }else{
                                $addon_html .= '<tr>
                                                <td width="100%">No Addon Selected</td>
                                              </tr>';
                        }
                    }        
                }   
            }

            if($product_type_id != 3){
                $coolie_html = '<tr>
                                <td width="70%"><strong>Legal Liability to Cleaner/Conductor/Coolie/Paid Driver</strong></td>
                                <td>'.$ll_pa_cover_cleaner_conductor_coolie_premium.'</td>
                                </tr>


                               <tr>
                                <td width="70%"><strong>PA Cover to Coolie/Conductor/Cleaner</strong></td>
                                <td>'.$totalcleaner_conductor_coolie.'</td>
                                </tr>';
            }

            if($product_type_id != 2)
            {
                $bifuel_premium_tr = '<tr>
                    <td width="70%"><strong>Bifuel Premium</strong></td>
                    <td>'.$bifuel_premium.'</td>
                    </tr>';
                
                $tp_bifuel_premium_tr = ' <tr>
                                    <td width="70%">CNG/LPG</td>
                                    <td>'.roundValue($tp_bifuel_premium).'</td>
                                  </tr>';  
                 $aa_association_value_tr = '<tr>
                                    <td width="70%">Automobile Association</td>
                                    <td>'.roundValue($aa_value).'</td>
                                  </tr>';  

            }else{
                $bifuel_premium_tr = "";
                $tp_bifuel_premium_tr = "";
                $aa_association_value_tr = "";
            }
            
            $aa_value = (isset($mpn_data['deductibles']['automobile_association'])&& ($mpn_data['deductibles']['automobile_association'] == 'true'))?roundValue($aa_value):0;

            
           $total_discount = roundValue($total_discount);
           $addon_total = roundValue($addon_total);
           $total_premium_without_tax = roundValue($total_premium_without_tax);
           $total_thirdparty_premium = roundTotal($total_thirdparty_premium);
           $gst = roundValue($gst);
           $net_od = roundTotal($net_od);
           $gross_premium = roundTotal($gross_premium);
            $geographical_value_tp = roundValue($geographical_value_tp);
            $pa_unnamed_persons_premium = roundValue($pa_unnamed_persons_premium);
            $antitheft_value = roundValue($antitheft_value);
            $ncb_value = roundValue($ncb_value);
            $total_discount = roundTotal($total_discount);

 
            $html = <<<START_QUOTE
                <div id="brekup-popup-{$ic_id}" class="zoom-anim-dialog  col-lg-9 col-md-9 center-col bg-white text-center modal-popup-main modal fade breakupmodal" data-keyboard="false" data-backdrop="static">    
    <div class="row">
        <div class="col-md-12 center-col login-box">
            <a href="javascript(0)" class="btn-small-black-background btn btn-small button no-margin f-right" data-dismiss="modal">&times;</a>
            <!-- form title  -->
            <h2 class="text-capitalize text-left">Premium Breakup for {$ic_name}</h2>
            <!-- end form title  -->
            <div class=" bg-black no-margin-lr margin-three center-col"></div>
            <!-- tab -->
                        <div class="tab-style2">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <!-- tab navigation -->
                                    <ul class="nav nav-tabs nav-tabs-light text-left">
                                        <li class="active"><a href="#vehicle-detail-tab-{$ic_id}" data-toggle="tab">Vehicle Details</a></li>
                                        <li><a href="#od-break-up-tab-{$ic_id}" data-toggle="tab">OD Break Up</a></li>
                                        <li><a href="#addons-tab-{$ic_id}" data-toggle="tab">Addons</a></li>
                                        <li><a href="#third-party-tab-{$ic_id}" data-toggle="tab">Third Party Breakup</a></li>
                                        <li><a href="#deductubles-tab-{$ic_id}" data-toggle="tab">Deductibles</a></li>
                                        <li><a href="#premium-summary-tab-{$ic_id}" data-toggle="tab">Premium Summary</a></li>
                                    </ul>
                                    <!-- end tab navigation -->
                                </div>
                            </div>
                            <!-- tab content section -->
                            <div class="tab-content">
                                <!-- tab content -->
                                <div class="tab-pane med-text fade in active" id="vehicle-detail-tab-{$ic_id}">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 no-padding">
                                           <table class="table table-responsive table-sm table-striped text-left mob-dis-block" data-tablesaw-mode="stack">
                                                <thead>
                                                   <tr>
                                                    <th data-tablesaw-sortable-col="" data-tablesaw-sortable-default-col="" data-tablesaw-priority="persist">make model variant</th>
                                                    <th data-tablesaw-sortable-col="" data-tablesaw-priority="3">IDV</th>
                                                    <th data-tablesaw-sortable-col="" data-tablesaw-priority="1">Age</th><th data-tablesaw-sortable-col="" data-tablesaw-priority="4">CC</th>
                                                    <th data-tablesaw-sortable-col="" data-tablesaw-priority="5">Fuel</th>
                                                    <th data-tablesaw-sortable-col="" data-tablesaw-priority="2">Year</th>
                                                    <th data-tablesaw-sortable-col="" data-tablesaw-priority="7">RTO</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                                   <tr>
                                                    <td>{$make} {$model} {$variant} </td>
                                                    <td>{$vehicle_idv}</td>
                                                    <td>{$vehicle_age_year}</td>
                                                    <td>{$cc}</td>
                                                    <td>{$fuel}</td>
                                                    <td>{$manufacturing_year}</td>
                                                    <td>{$rto_city}</td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- end tab content -->
                                <!-- tab content -->
                                <div class="tab-pane fade in" id="od-break-up-tab-{$ic_id}">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 no-padding-bottom">
                                            <h3 class="margin-one">Basic OD Premium (+)</h3>
                                            <table class="table table-responsive table-sm table-striped text-left">
                                                <thead>
                                                    <tr>
                                                    <td width="70%"><strong>Basic OD</strong></td>
                                                    <td>{$basic_od}</td>
                                                        </tr>
                                                  <tr>
                                                    <td width="70%"><strong>Electrical Accessory Premium</strong></td>
                                                    <td>{$electrical_premium}</td>
                                                  </tr>
                                                   <tr>
                                                      <td width="70%"><strong>Non Electrical Accessory Premium</strong></td>
                                                      <td>{$non_electrical_premium} </td>
                                                    </tr>
                                                  
                                                  {$bifuel_premium_tr}

                                                    <tr>
                                                    <td width="70%"><strong>Total Geographical Extension</strong></td>
                                                    <td>{$geographical_value_od}</td>

                                                  </tr>

                                                </thead>
                                                <tbody>
                                           <tr>
                                                    <th width="70%"><b>(A) Total OD Premium</b></th>
                                                    <th>{$net_od}</th>
                                                  </tr>
                                                </tbody>
                                              </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- end tab content -->
                                                    
                                                    
                                <!-- tab content -->
                                <div class="tab-pane fade in" id="addons-tab-{$ic_id}">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 no-padding-bottom">
                                            <h3 class="margin-one">Applicable Add-On (+)</h3>
                                            <table class="table table-responsive table-sm table-striped text-left">
                                                {$addon_html}
                                              </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- end tab content -->
                                <!-- tab content -->
                                <div class="tab-pane fade in" id="third-party-tab-{$ic_id}">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 no-padding-bottom">
                                            <h3 class="margin-one">TP Premium Calculation (+)</h3>
                                            <table class="table table-responsive table-sm table-striped text-left">
                                                <thead>
                                                    <tr>
                                                    <td width="70%"><strong>Basic Third Party Liability</strong></td>
                                                    <td>{$basic_ttpd}</td>
                                                        </tr>
                                                    {$tp_bifuel_premium_tr}
                                                  <tr>
                                                    <td width="70%">Compulsary PA to Owner-Driver</td>
                                                    <td>{$pa_owner_driver_premium}</td>
                                                  </tr>
                                                   <tr>
                                                    <td width="70%"><strong>PA Cover For Paid Driver</strong></td>
                                                    <td>{$pa_paid_driver_premium}</td>
                                                    </tr>

                                                    {$ll_owner_driver_premium_tr}
                                                   <tr>
                                                    <td width="70%"><strong>LL Owner-Driver</strong></td>
                                                    <td>{$ll_owner_driver_value}</td>
                                                    </tr>


                                                   {$coolie_html}
                                                    <tr>
                                                    <td width="70%"><strong>Total Geographical Extension TP</strong></td>
                                                    <td>{$geographical_value_tp}</td>
                                                  </tr>
                                                </thead>
                                                <tbody>
                                           <tr>
                                                    <th width="70%"><b>(D) Total TP Premium</b></th>
                                                    <th >{$total_thirdparty_premium}</th>
                                                  </tr>
                                                </tbody>
                                              </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- end tab content -->
                                <!-- tab content -->
                                <div class="tab-pane fade in" id="deductubles-tab-{$ic_id}">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 no-padding-bottom">
                                            <h3 class="margin-one">Deductibles (-)</h3>
                                            <table class="table table-responsive table-sm table-striped text-left">
                                                <thead>
                                                    <tr>
                                                    <td width="70%"><strong>Anti-Theft Discount</strong></td>
                                                    <td>{$antitheft_value}</td>
                                                        </tr>
                                                   {$aa_association_value_tr}
                                                   <tr>
                                                      <td width="70%"><strong>No Claim Bonus ({$ncbpercentage})%</strong></td>
                                                      <td>{$ncb_value} </td>
                                                    </tr>
                                                        </thead>
                                                        <tbody>
                                                   <tr>
                                                    <th width="70%"><b>(B) Total Discount</b></th>
                                                    <th >{$total_discount}</th>
                                                  </tr>
                                                </tbody>
                                              </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- end tab content -->
                                                    
                                                    
                                <!-- tab content -->
                                <div class="tab-pane fade in" id="premium-summary-tab-{$ic_id}">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 no-padding-bottom">
                                            <h3 class="margin-one">Premium Summary</h3>
                                            <table class="table table-responsive table-sm table-striped text-left">
                                                <thead>
                                                    <tr>
                                                    <td width="70%"><strong>(A) Total OD Premium</strong></td>
                                                    <td>{$net_od}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="70%">(B) Total Discount</td>
                                                        <td>{$total_discount}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="70%"><strong>(C) Total Add On Premium</strong></td>
                                                        <td>{$addon_total} </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="70%"><strong>(D) Total TP Premium</strong></td>
                                                        <td>{$total_thirdparty_premium}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="70%"><strong>Total Premium</strong></td>
                                                        <td>{$total_premium_without_tax}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="70%"><strong>GST</strong></td>
                                                        <td>{$gst}</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th width="70%"><b>Total Premium Payable</b></th>
                                                        <th>{$gross_premium}</th>
                                                  </tr>
                                                </tbody>
                                              </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- end tab content -->
                            </div>
                            <!-- end tab content section -->
                        </div>
                        <!-- end tab -->
        </div>
    </div>
</div>
<!-- end brekup popup -->
START_QUOTE;
            return $html;
        }
    }  
    

}

?>

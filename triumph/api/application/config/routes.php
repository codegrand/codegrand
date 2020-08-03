<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = "front/home";

$route['translate_uri_dashes'] = FALSE;
//$route['home'] = '';
$route['404_override'] = '';

$route['buypolicy'] = 'front/CustomerDetail/buypolicy';

$route['global_login'] = 'front/login/global_login';
$route['sendsignupotp'] = 'front/Login/sendSignupOtp';
$route['coming_soon'] = 'front/Login/coming_soon';

$route['completesignup'] = 'front/Login/completeSignup';

$route['loadquotation/(:num)'] = 'front/Quotation/loadQuotation/$1';
$route['getmodel'] = 'front/Quotation/getModel';
$route['getvariant'] = 'front/Quotation/getVariant';
$route['setnildepstatus'] = 'front/Quotation/setNilDepStatus';

$route['health_sumInsured'] = 'front/Quotation/sumInsured';

$route['state_city'] = 'front/Muthoot/state_city';
$route['set_dep_status'] = 'front/Quotation/setDepStatus';



//for muthoot POSP
$route['muthoot'] = 'front/Muthoot/pos_index';
$route['muthoot/pos'] = 'front/Muthoot/pos_index';
$route['muthoot/muthoot_pos_signup'] = 'front/Muthoot/muthoot_pos_signup';
$route['muthoot/assign_access'] = 'front/Muthoot/assign_access';
$route['muthoot/remote_pos'] = 'front/Muthoot/muthoot_index';
$route['muthoot_get_branch'] = 'front/Muthoot/get_branch';
$route['muthoot/success'] = 'front/Muthoot/success';


//for Mitsubishi
$route['mitsubishi/login'] = 'front/Mitsubishi/pos_index';

//for myaccount
$route['myaccount'] = 'front/myaccount/dashboard';
$route['myaccount/dashboard'] = 'front/myaccount/dashboard';
$route['myaccount/breakin-case'] = 'front/myaccount/BreakinCase';
$route['myaccount/sold-policy'] = 'front/myaccount/SoldPolicy';
$route['myaccount/saved-proposal'] = 'front/myaccount/SavedProposal';
$route['myaccount/endorsement'] = 'front/myaccount/Endorsement';
$route['myaccount/renewal-policy'] = 'front/myaccount/RenewalPolicy';
$route['myaccount/cancel-policy'] = 'front/myaccount/CancelPolicy';
$route['myaccount/add_cancel_policydata'] = 'front/myaccount/CancelPolicy/add_cancel_policydata';


$route['myaccount/pay-slip'] = 'front/myaccount/PaySlip';
$route['myaccount/pay-slip-report'] = 'front/myaccount/PaySlip/Payslip_download';
$route['myaccount/pay-slip-download/(:num)'] = 'front/myaccount/PaySlip/download_paying_slip_pdf/$1';
$route['myaccount/download-proposal/(:num)'] = 'front/myaccount/SavedProposal/downloadproposal/$1';

$route['myaccount/non-nil-endorse/(:any)'] ='front/myaccount/Endorsement/non_nil_status/$1';
$route['myaccount/cancel-policy/(:any)'] = 'front/myaccount//SavedProposal/fn_cancel_policy/$1';

$route['myaccount/download-policy/(:num)'] = 'front/myaccount/SoldPolicy/downloadtestpolicy/$1';
$route['myaccount/download-old-policy/(:num)'] = 'front/myaccount/SoldPolicy/downloadtestpolicyold/$1';

$route['myaccount/download-policy-muthoot/(:num)'] = 'front/myaccount/SoldPolicy/muthootpolicy/$1';
$route['myaccount/download-proposal/(:num)'] = 'front/myaccount/SoldPolicy/downloadtestproposal/$1';
$route['myaccount/download-old-proposal/(:num)'] = 'front/myaccount/SoldPolicy/downloadtestproposalold/$1';



$route['myaccount/quote_forword/(:num)'] = 'front/proposal/quote_forwards/$1';
$route['quoteforward'] = 'front/proposal/quoteForwardAction/';

$route['download-policy/(:num)'] = 'front/Policy/downloadPolicy/$1';

$route['myaccount/view-endorse/(:any)'] = 'front/myaccount/Endorsement/view_endorse/$1';

$route['myaccount/endorsementpdf/(:num)'] = 'front/myaccount/Endorsement/endorsementpdf/$1';
$route['myaccount/endrosment-action/(:any)'] = 'front/myaccount/Endorsement/endrosment_action/$1';
$route['myaccount/getBankList'] = 'front/myaccount/Endorsement/getBankListAjax';

//for Breakin Case
$route['myaccount/uploadbreakincase/(:num)'] = 'front/myaccount/BreakinCase/uploadbreakincase/$1';
$route['myaccount/inner_breaking_case_edit_post'] = 'front/myaccount/BreakinCase/fn_inner_breaking_case_edit_post';
$route['myaccount/no_self_inspection_form_post'] = 'front/myaccount/BreakinCase/fn_no_self_inspection_form_post';



//$route['quotation'] = 'Privatecar/fn_quotation';
//$route['quotation'] = 'Quotation/fn_quotation_car';
//$route['quotation_ajax_load'] = 'Quotation/quotation_ajax_load';
//$route['get_city'] = 'Privatecar/get_city';
//$route['get_city'] = 'Quotation/get_city';
//$route['get_edited_varient'] = 'Quotation/get_edited_varient';
//$route['get_edited_varient_bike'] = 'Quotation/get_edited_varient_bike';
$route['set_edited_car_name'] = 'Privatecar/fn_set_edited_car_name';

$route['private-car-shriram'] = 'private_car_shriram/data';

$route['update_quotation'] = 'quotation_rewamp/updateQuote';

$route['corporate_dashboard'] = 'Corp_dashboard_users/fn_myaccount_dashboard';


$route['oddiscount'] = 'Od_discount/od_discountt';
$route['bike_quotation'] = 'Bike/fn_quotation_page';

$route['customer_detail'] = 'Quotation/fn_customer_detail';
$route['endorsement_search_data'] = 'Myaccount/endorsement_search_data';


$route['quote_detail_pagee'] = 'Home/quote_detail_pagee';


/* * ************Payment**************** */


$route['bharti/privatecar/transaction_status'] = 'front/Payment/payment_response';
$route['hdfc/privatecar/transaction_status'] = 'front/Payment/payment_response';
$route['videocon/privatecar/transaction_status'] = 'front/Payment/payment_response';
$route['royal/privatecar/transaction_status'] = 'front/Payment/payment_response';



$route['future/privatecar/transaction_status'] = 'front/Payment/payment_response';
$route['shriram/privatecar/transaction_status'] = 'front/Payment/payment_response';
$route['tata/privatecar/transaction_status'] = 'front/Payment/payment_response';
$route['tata-aig/privatecar/transaction_status'] = 'front/Payment/payment_response';
$route['nai/privatecar/transaction_status'] = 'front/Payment/payment_response';
$route['nai/bike/transaction_status'] = 'front/Payment/payment_response';
$route['Reliance/reliance_payment_response'] = 'front/Payment/payment_response';
$route['hdfc/hdfc_payment_response'] = 'front/Payment/payment_response';
$route['updatepaymentinfo'] = 'front/Payment/updatePaymentInfo';
$route['shriram/commercial/transaction_status'] = 'front/Payment/payment_response';


/* * ************Payment**************** */
$route['proposalaction'] = 'front/proposal/proposalAction';
$route['myaccount/proposalaction'] = 'front/proposal/proposalAction';
$route['signin'] = 'front/login/signin';
$route['logout'] = 'front/login/logout';
$route['admin/logout'] = 'admin/login/logout';

$route['about'] = 'StaticContent/aboutUs';
$route['contact'] = 'StaticContent/contactUs';
$route['learning'] = 'StaticContent/learning';
$route['claim'] = 'StaticContent/claim';
$route['career'] = 'StaticContent/career';
//Reserved for Admin Panel
$route['admin'] = 'admin/login';
$route['admin/login'] = 'admin/login';
$route['admin/pos_details'] = 'admin/Customermaster/pos_details';
$route['admin/pos_details_ajax'] = 'admin/Customermaster/pos_details_ajax';
$route['admin/pos_certificate'] = 'admin/Customermaster/pos_certificate';
$route['admin/download_certificate/(:num)'] = 'admin/Customermaster/download_certificate/$1';
$route['admin/get_pos_details_data/(:num)'] = 'admin/Customermaster/get_pos_details_data/$1';
$route['admin/misp_report'] = 'admin/MispReport/misp_report';
$route['admin/export-misp-report'] = 'admin/MispReport/export_misp_report';


//     		-------------------  Payment --------------------------------------     //


$route['admin/policy_payments'] = 'admin/payment/policy_payments';
$route['admin/policypayments_ajax'] = 'admin/payment/policypayments_ajax';
$route['admin/get_policypayments_ajax/(:any)/(:any)/(:any)'] = 'admin/payment/get_policypayments_ajax/$1/$2/$3';
$route['admin/count_amount_ajax'] = 'admin/payment/count_amount_ajax';
$route['admin/count_amount_ajax_new'] = 'admin/payment/count_amount_ajax_new';
$route['admin/payslip_insert_data'] = 'admin/payment/payslip_insert_data';




$route['admin/download_payslip'] = 'admin/payment/download_payslip';
$route['admin/policypayments_ajax'] = 'admin/payment/policypayments_ajax';
$route['admin/get_paysliplist_ajax/(:any)/(:any)/(:any)'] = 'admin/payment/get_paysliplist_ajax/$1/$2/$3';

$route['admin/download_paying_slip_pdf/(:any)'] = 'admin/payment/download_paying_slip_pdf/$1';
$route['admin/payslip_insert_data'] = 'admin/payment/payslip_insert_data';





//     		--------------------- End Payment ----------------------------------     // 

$route['admin/submit-admin-login'] = 'admin/login/submitLogin';
$route['muthoot-login'] = 'admin/login/submitMuthootLogin';
$route['admin/muthoot-login'] = 'admin/login/submitMuthootLogin';
$route['admin/dashboard'] = 'admin/Login/dashboard';
$route['admin/fuel_type'] = 'admin/Vehiclemaster/fuel_type';
$route['admin/delete_record/(:num)/(:any)'] = 'Common/delete_record/$1/$2';
$route['admin/instance_type'] = 'admin/icmaster/instance_type';
$route['admin/instance_type_ajax'] = 'admin/icmaster/instance_type_ajax';
$route['admin/insurance_business'] = 'admin/icmaster/insurance_business';
$route['admin/insurance_business_ajax'] = 'admin/icmaster/insurance_business_ajax';
$route['admin/get_insurance_business_form/(:num)'] = 'admin/Icmaster/get_insurance_business_form/$1';
$route['admin/insurance_instance'] = 'admin/icmaster/insurance_instance';
$route['admin/insurance_instance_ajax'] = 'admin/icmaster/insurance_instance_ajax';
$route['admin/instance_type_form'] = 'admin/icmaster/instance_type_form';
$route['admin/instance_type_edit'] = 'admin/icmaster/instance_type_edit';
$route['admin/update_instance_form/(:num)'] = 'admin/icmaster/update_instance_form/$1';
$route['admin/edit_insurance_business/(:num)'] = 'admin/icmaster/edit_insurance_business/$1';
$route['admin/instance_bus_form'] = 'admin/icmaster/instance_bus_form';
$route['admin/update_instance_bus_form'] = 'admin/icmaster/update_instance_bus_form';
$route['admin/vehicle_make'] = 'admin/Vehiclemaster/make';
$route['post_make'] = 'admin/Vehiclemaster/add_make_form';
$route['post_segment'] = 'admin/Vehiclemaster/add_segment_form';
$route['post_model'] = 'admin/Vehiclemaster/add_model_form';
$route['admin/edit_make'] = 'admin/Vehiclemaster/edit_make_form';
$route['admin/get_make/(:any)'] = 'admin/Vehiclemaster/get_make/$1/';
$route['admin/get_fuel/(:any)'] = 'admin/Vehiclemaster/get_fuel/$1/';
$route['admin/get_masters/(:any)'] = 'admin/Vehiclemaster/get_masters/$1/';
$route['admin/segment_edit/(:any)'] = 'admin/Vehiclemaster/get_segment/$1/';
$route['admin/model_edit/(:any)'] = 'admin/Vehiclemaster/model_edit/$1/';
$route['admin/model_update'] = 'admin/Vehiclemaster/model_update';
$route['admin/vehicle_varient'] = 'admin/Vehiclemaster/varient';
$route['admin/vehicle_masters'] = 'admin/Vehiclemaster/varientmaster';
$route['admin/vehicle_model'] = 'admin/Vehiclemaster/model';
$route['admin/varient_master_ajax'] = 'admin/Vehiclemaster/varient_master_ajax';
$route['admin/add_varient_form'] = 'admin/Vehiclemaster/add_varient_form';
$route['admin/add_master_form'] = 'admin/Vehiclemaster/add_master_form';
$route['admin/add_fuel_form'] = 'admin/Vehiclemaster/add_fuel_form';
$route['admin/vehicle_masters_make/(:any)'] = 'admin/Vehiclemaster/vehicle_masters_make/$1/';
$route['admin/vehicle_masters_model/(:any)'] = 'admin/Vehiclemaster/vehicle_masters_model/$1/';
$route['admin/assign_resource/(:any)'] = 'admin/AdminMaster/assign_resource/$1/';
$route['admin/role_resources_submitted'] = 'admin/AdminMaster/role_resources_submitted';
$route['admin/make_master_ajax'] = 'admin/Vehiclemaster/vehicle_make_ajax';
$route['admin/fuel_ajax'] = 'admin/Vehiclemaster/fuel_ajax';
$route['admin/master_ajax'] = 'admin/Vehiclemaster/master_ajax';
$route['admin/deletevarient/(:num)'] = 'admin/Vehiclemaster/deletevarient/$1';
$route['admin/vehicle_segment_type'] = 'admin/Vehiclemaster/vehicle_segment_type';
$route['admin/vehicle_segment_type_ajax'] = 'admin/Vehiclemaster/vehicle_segment_type_ajax';
$route['admin/edit_varient/(:num)'] = 'admin/Vehiclemaster/edit_varient_form/$1';
$route['admin/add_vehicle_segment_form'] = 'admin/Vehiclemaster/add_vehicle_segment_form';
$route['admin/edit_segment_type'] = 'admin/Vehiclemaster/edit_segment_type';
$route['admin/edit_segment'] = 'admin/Vehiclemaster/edit_segment';
$route['admin/update_segment'] = 'admin/Vehiclemaster/update_segment';
$route['admin/fuel_update'] = 'admin/Vehiclemaster/fuel_update';
$route['admin/edit_varient_post'] = 'admin/Vehiclemaster/edit_varient_post';
$route['admin/deletevehiclesegmenttype/(:num)'] = 'admin/Vehiclemaster/deletevehiclesegmenttype/$1';
$route['admin/edit_vehile_segment_type'] = 'admin/Vehiclemaster/edit_vehile_segment_type';
$route['admin/vehicle_master_two_wheeler'] = 'admin/Vehiclemaster/vehicle_master_two_wheeler';
$route['admin/two_wheeler_master_ajax'] = 'admin/Vehiclemaster/two_wheeler_master_ajax';
$route['admin/add_two_wheeler_master_form'] = 'admin/Vehiclemaster/add_two_wheeler_master_form';
$route['admin/edit_two_wheeler/(:num)'] = 'admin/Vehiclemaster/edit_two_wheeler/$1';
$route['admin/edit_two_wheeler_post'] = 'admin/Vehiclemaster/edit_two_wheeler_post';
$route['admin/deletetwowheeler/(:num)'] = 'admin/Vehiclemaster/deletetwowheeler/$1';
$route['admin/vehicle_master_truck'] = 'admin/Vehiclemaster/vehicle_master_truck';
$route['admin/truck_master_ajax'] = 'admin/Vehiclemaster/truck_master_ajax';
$route['admin/add_truck_master_form'] = 'admin/Vehiclemaster/add_truck_master_form';
$route['admin/edit_truck_post'] = 'admin/Vehiclemaster/edit_truck_post';
$route['admin/edit_truck/(:num)'] = 'admin/Vehiclemaster/edit_truck/$1';
$route['admin/deletetruck/(:num)'] = 'admin/Vehiclemaster/deletetruck/$1';
$route['admin/vehicle_master_car'] = 'admin/Vehiclemaster/vehicle_master_car';
$route['admin/vehicle_master_bus'] = 'admin/Vehiclemaster/vehicle_master_bus';
$route['admin/car_master_ajax'] = 'admin/Vehiclemaster/car_master_ajax';
$route['admin/bus_master_ajax'] = 'admin/Vehiclemaster/bus_master_ajax';
$route['admin/add_car_master_form'] = 'admin/Vehiclemaster/add_car_master_form';
$route['admin/add_bus_master_form'] = 'admin/Vehiclemaster/add_bus_master_form';
$route['admin/add_insurancemaster_form'] = 'admin/IcStateMaster/add_insurancemaster_form';
$route['admin/ic_state_master'] = 'admin/IcStateMaster/ic_state_master';
$route['admin/edit_car/(:num)'] = 'admin/Vehiclemaster/edit_car/$1';
$route['admin/edit_bus/(:num)'] = 'admin/Vehiclemaster/edit_bus/$1';
$route['admin/edit_car_post'] = 'admin/Vehiclemaster/edit_car_post';
$route['admin/edit_bus_post'] = 'admin/Vehiclemaster/edit_bus_post';
$route['admin/edit_insurancemaster_post'] = 'admin/IcStateMaster/edit_insurancemaster_post';
$route['admin/deletecar/(:num)'] = 'admin/Vehiclemaster/deletecar/$1';
$route['admin/deletebus/(:num)'] = 'admin/Vehiclemaster/deletebus/$1';
$route['admin/vehicle_master_body_type'] = 'admin/Vehiclemaster/vehicle_master_body_type';
$route['admin/car_master_ajax'] = 'admin/Vehiclemaster/car_master_ajax';
$route['admin/edit_insurancemaster/(:num)'] = 'admin/IcStateMaster/edit_insurancemaster/$1';
//body type master
$route['admin/body_type_master_ajax'] = 'admin/Vehiclemaster/body_type_master_ajax';
$route['admin/add_body_type_master_form'] = 'admin/Vehiclemaster/add_body_type_master_form';
$route['admin/edit_body_type/(:num)'] = 'admin/Vehiclemaster/edit_body_type/$1';
$route['admin/edit_body_type_post'] = 'admin/Vehiclemaster/edit_body_type_post';
$route['admin/deletebodytype/(:num)'] = 'admin/Vehiclemaster/deletebodytype/$1';
//user master
$route['admin/user_master'] = 'admin/Vehiclemaster/user_master';
$route['admin/user_master_ajax'] = 'admin/Vehiclemaster/user_master_ajax';
$route['admin/edit_user_master/(:num)'] = 'admin/Vehiclemaster/edit_user_master/$1';
$route['admin/add_user_master_form'] = 'admin/Vehiclemaster/add_user_master_form';
$route['admin/edit_user_post'] = 'admin/Vehiclemaster/edit_user_post';
//state master
$route['admin/state_master'] = 'admin/Vehiclemaster/state_master';
$route['admin/state_master_ajax'] = 'admin/Vehiclemaster/state_master_ajax';
$route['admin/add_state_form'] = 'admin/Vehiclemaster/add_state_form';
$route['admin/edit_state_master/(:num)'] = 'admin/Vehiclemaster/edit_state_master/$1';
$route['admin/edit_state_post'] = 'admin/Vehiclemaster/edit_state_post';
//rto Zone Master
$route['admin/rto_zone_master'] = 'admin/Vehiclemaster/rto_zone_master';
$route['admin/rto_zone_master_ajax'] = 'admin/Vehiclemaster/rto_zone_master_ajax';
$route['admin/add_rto_zone_form'] = 'admin/Vehiclemaster/add_rto_zone_form';
$route['admin/edit_rto_zone_master/(:num)'] = 'admin/Vehiclemaster/edit_rto_zone_master/$1';
$route['admin/edit_rto_zone_post'] = 'admin/Vehiclemaster/edit_rto_zone_post';
//Rto Master
$route['admin/rto_master'] = 'admin/Vehiclemaster/rto_master';
$route['admin/rto_master_ajax'] = 'admin/Vehiclemaster/rto_master_ajax';
$route['admin/get_city/(:num)'] = 'admin/Vehiclemaster/get_city/$1';
$route['admin/add_rto_form'] = 'admin/Vehiclemaster/add_rto_form';
$route['admin/edit_rto_master/(:num)'] = 'admin/Vehiclemaster/edit_rto_master/$1';
$route['admin/edit_rto_post'] = 'admin/Vehiclemaster/edit_rto_post';

//IC Masters
$route['admin/ic_master'] = 'admin/IcMaster/ic_master';
$route['admin/ic_master_ajax'] = 'admin/IcMaster/ic_master_ajax';
$route['admin/edit_ic/(:num)'] = 'admin/IcMaster/edit_ic/$1';
$route['admin/assign_ic_submit'] = 'admin/IcMaster/assign_ic_submit';
/*
  //state master
  $route['admin/state_master'] = 'admin/Vehiclemaster/state_master';
  $route['admin/state_master_ajax'] = 'admin/Vehiclemaster/state_master_ajax';
  $route['admin/add_state_form'] = 'admin/Vehiclemaster/add_state_form';
  $route['admin/edit_state_master/(:num)'] = 'admin/Vehiclemaster/edit_state_master/$1';
  $route['admin/edit_state_post'] = 'admin/Vehiclemaster/edit_state_post';
 */
//nominee master
$route['admin/nominee_master'] = 'admin/Vehiclemaster/nominee_master';
// $route['admin/nominee_master_ajax'] = 'admin/Vehiclemaster/nominee_master_ajax';
// $route['admin/add_nominee_form'] = 'admin/Vehiclemaster/add_nominee_form';
// $route['admin/edit_nominee_master/(:num)'] = 'admin/Vehiclemaster/edit_nominee_master/$1';
// $route['admin/edit_nominee_post'] = 'admin/Vehiclemaster/edit_nominee_post';
//RTO IC Wise
$route['admin/rto_ic_master'] = 'admin/Vehiclemaster/rto_ic_master';
$route['admin/rto_ic_master_ajax'] = 'admin/Vehiclemaster/rto_ic_master_ajax';
$route['admin/changerto/(:num)'] = 'admin/Vehiclemaster/changerto/$1';
$route['admin/add_rto_ic_form'] = 'admin/Vehiclemaster/add_rto_ic_form';
$route['admin/edit_rto_ic_master/(:num)'] = 'admin/Vehiclemaster/edit_rto_ic_master/$1';
$route['admin/edit_rto_ic_post'] = 'admin/Vehiclemaster/edit_rto_ic_post';
//Product Type
$route['admin/product_type'] = 'admin/Vehiclemaster/product_type';
$route['admin/product_master_ajax'] = 'admin/Vehiclemaster/product_master_ajax';
$route['admin/add_product_form'] = 'admin/Vehiclemaster/add_product_form';
$route['admin/edit_product_master/(:num)'] = 'admin/Vehiclemaster/edit_product_master/$1';
$route['admin/edit_producttype_post'] = 'admin/Vehiclemaster/edit_producttype_post';
//Policy Type
$route['admin/policy_type'] = 'admin/Policy/policy_type';
$route['admin/policy_type_master_ajax'] = 'admin/Policy/policy_type_master_ajax';
$route['admin/add_policy_type_form'] = 'admin/Policy/add_policy_type_form';
$route['admin/edit_policy_type_master/(:num)'] = 'admin/Policy/edit_policy_type_master/$1';
$route['admin/edit_policytype_post'] = 'admin/Policy/edit_policytype_post';
//Policy Customer Details
$route['admin/policy_customer_details'] = 'admin/Policy/policy_customer_details';
$route['admin/add_policy_customer_details'] = 'admin/Policy/add_policy_customer_details';
$route['admin/policy_customer_ajax'] = 'admin/Policy/policy_customer_ajax';
//Policy Transaction Receipt
$route['admin/policy_transaction_receipt'] = 'admin/Policy/policy_transaction_receipt';
$route['admin/policy_transaction_receipt_ajax'] = 'admin/Policy/policy_transaction_receipt_ajax';
$route['admin/add_transaction_receipt_form'] = 'admin/Policy/add_transaction_receipt_form';
$route['admin/edit_policy_transaction_receipt_master/(:num)'] = 'admin/Policy/edit_policy_transaction_receipt_master/$1';
$route['admin/edit_policytransactionreceipt_post'] = 'admin/Policy/edit_policytransactionreceipt_post';
//Policy Payment
$route['admin/policy_payment'] = 'admin/Policy/policy_payment';
$route['admin/policy_payment_ajax'] = 'admin/Policy/policy_payment_ajax';  //problm in loading
$route['admin/add_policy_payment_form'] = 'admin/Policy/add_policy_payment_form';
$route['admin/edit_policy_payment_master/(:num)'] = 'admin/Policy/edit_policy_payment_master/$1';
$route['admin/edit_policypayment_post'] = 'admin/Policy/edit_policypayment_post';
$route['admin/edit_policyquotedata_post'] = 'admin/Policy/edit_policyquotedata_post';
//Policy Quote Data
$route['admin/policy_quote_data'] = 'admin/Policy/policy_quote_data';
$route['admin/policy_quote_data_ajax'] = 'admin/Policy/policy_quote_data_ajax';
$route['admin/add_policy_quote_form'] = 'admin/Policy/add_policy_quote_form';
$route['admin/edit_policy_quote_data_master/(:num)'] = 'admin/Policy/edit_policy_quote_data_master/$1';
//Duplicate Log Master
$route['admin/duplicate_log'] = 'admin/Log/duplicate_log';
$route['admin/duplicate_log_ajax'] = 'admin/Log/duplicate_log_ajax';
$route['admin/add_duplicate_log_form'] = 'admin/Log/add_duplicate_log_form';
$route['admin/edit_duplicate_log_master/(:num)'] = 'admin/Log/edit_duplicate_log_master/$1';
$route['admin/edit_duplicatelog_post'] = 'admin/Log/edit_duplicatelog_post';
//Policy Rewamp Master
$route['admin/policy'] = 'admin/Policy/policy';
$route['admin/policy_ajax'] = 'admin/Policy/policy_ajax';
$route['admin/add_policy_form'] = 'admin/Policy/add_policy_form';
$route['admin/edit_policy_master/(:num)'] = 'admin/Policy/edit_policy_master/$1';
$route['admin/edit_policy_post'] = 'admin/Policy/edit_policy_post';
//$route['manage_make'] = 'admin/Login/manage_make';
$route['admin/segment_icid_ajax'] = 'admin/Vehiclemaster/vehicle_segment_ajax';
$route['admin/model_ajax'] = 'admin/Vehiclemaster/model_ajax';
$route['admin/vehicle_segment'] = 'admin/Vehiclemaster/vehicle_segment';
// start Addonage route
$route['admin/addon-age'] = 'admin/Addonage';
$route['admin/addon-age/getrecord'] = 'admin/Addonage/ajaxResult';
// end addonage route
// start addon ic route
$route['admin/addon-ic'] = 'admin/Addonic';
$route['admin/addon-ic/getrecord'] = 'admin/Addonic/ajaxResult';
$route['admin/addon-ic/checkduplicate'] = 'admin/Addonmaster/checkDuplicate';
// end addon ic route
// start addon master route
$route['admin/addon-master'] = 'admin/Addonmaster';
$route['admin/addon-master/getrecord'] = 'admin/Addonmaster/ajaxResult';
$route['admin/addon-master/checkduplicate'] = 'admin/Addonmaster/checkDuplicate';
// end addon master route
// end addonage route
/* admin ic master */
/* Videocon */
$route['admin/lvgi_rto'] = 'admin/icmaster/lvgi_rto';
$route['admin/lvgi_rto_ajax'] = 'admin/icmaster/lvgi_rto_ajax';
$route['admin/add_lvrto_form'] = 'admin/icmaster/add_lv_rto_form';
$route['admin/edit_lv_rto/(:num)'] = 'admin/icmaster/ajax_lv_rto_form/$1';
$route['admin/remove_lv_rto/(:num)'] = 'admin/icmaster/remove_lv_rto_form/$1';
$route['admin/lvgi_state'] = 'admin/icmaster/lvgi_state';
$route['admin/lvgi_state_ajax'] = 'admin/icmaster/lvgi_state_ajax';
$route['admin/add_lvstate_form'] = 'admin/icmaster/add_lv_state_form';
$route['admin/edit_lv_state/(:num)'] = 'admin/icmaster/ajax_lv_state_form/$1';
$route['admin/remove_lv_state/(:num)'] = 'admin/icmaster/remove_lv_state_form/$1';
/* NIC */
$route['admin/nia_bank'] = 'admin/icmaster/nia_bank';
$route['admin/nia_state'] = 'admin/icmaster/nia_state';
$route['admin/nia_state_add'] = 'admin/icmaster/nia_state_add';
$route['admin/nia_state_update'] = 'admin/icmaster/nia_state_update';
$route['admin/nia_state_edit/(:num)'] = 'admin/Policy/nia_state_edit/$1';
$route['admin/nia_state_ajax'] = 'admin/icmaster/nia_state_ajax';
$route['admin/nia_bank_ajax'] = 'admin/icmaster/nia_bank_ajax';
$route['admin/add_nia_bank_form'] = 'admin/icmaster/add_nia_bank_form';
$route['admin/edit_nia_bank/(:num)'] = 'admin/icmaster/ajax_nia_bank_form/$1';
$route['admin/remove_nia_bank/(:num)'] = 'admin/icmaster/remove_nia_bank_form/$1';
$route['admin/nia_financiar'] = 'admin/icmaster/nia_financiar';
$route['admin/nia_financiar_ajax'] = 'admin/icmaster/nia_financiar_ajax';
$route['admin/add_nia_financiar_form'] = 'admin/icmaster/add_nia_financiar_form';
$route['admin/edit_nia_financiar/(:num)'] = 'admin/icmaster/ajax_nia_financiar_form/$1';
$route['admin/remove_nia_financiar/(:num)'] = 'admin/icmaster/remove_nia_financiar_form/$1';
//future Generali State Master
$route['admin/ic_state_master'] = 'admin/IcStateMaster/ic_state_master';
//future Genarali Vehicle Master
$route['admin/fg_vehicle_master'] = 'admin/icmaster/fg_vehicle_master';
$route['admin/fg_vehicle_ajax'] = 'admin/icmaster/fg_vehicle_ajax';
$route['admin/add_fg_vehicle_form'] = 'admin/icmaster/add_fg_vehicle_form';
$route['admin/edit_fg_vehicle_master/(:num)'] = 'admin/icmaster/edit_fg_vehicle_master/$1';
$route['admin/edit_fg_vehicle_post'] = 'admin/icmaster/edit_fg_vehicle_post';
$route['admin/insurance_master'] = 'admin/IcStateMaster/insurance_master';
$route['admin/insurance_master_ajax'] = 'admin/IcStateMaster/insurance_master_ajax';
$route['admin/idv_calculator'] = 'admin/IcStateMaster/idv_calculator';
$route['admin/idvcalculator_ajax'] = 'admin/IcStateMaster/idvcalculator_ajax';
$route['admin/edit_idvcalculator/(:num)'] = 'admin/IcStateMaster/edit_idvcalculator/$1';
$route['admin/edit_idvcalculator_post'] = 'admin/IcStateMaster/edit_idvcalculator_post';
$route['admin/add_idvcalculator_form'] = 'admin/IcStateMaster/add_idvcalculator_form';
$route['admin/hdfc_rto_master'] = 'admin/Vehiclemaster/hdfc_rto_master';
$route['admin/hdfc_rtomaster_ajax'] = 'admin/Vehiclemaster/hdfc_rtomaster_ajax';
$route['admin/edit_hdfcrtomaster/(:num)'] = 'admin/Vehiclemaster/edit_hdfcrtomaster/$1';
$route['admin/edit_hdfcrtomaster_post'] = 'admin/Vehiclemaster/edit_hdfcrtomaster_post';
$route['admin/add_hdfcrtomaster_form'] = 'admin/Vehiclemaster/add_hdfcrtomaster_form';
$route['admin/godigit_pincode'] = 'admin/goDigitMaster/godigit_pincode';
$route['admin/godigit_previous_ic'] = 'admin/goDigitMaster/godigit_previous_ic';
$route['admin/godigit_vehicle'] = 'admin/goDigitMaster/godigit_vehicle';
$route['admin/godigit_rto'] = 'admin/goDigitMaster/godigit_rto';
$route['admin/hdfc_break_in_location'] = 'admin/goDigitMaster/hdfc_break_in_location';
$route['admin/hdfc_city'] = 'admin/goDigitMaster/hdfc_city';
$route['admin/customer_master'] = 'admin/Customermaster/customer_master';
$route['admin/ic_populate'] = 'admin/Customermaster/ic_populate';
$route['admin/add_dealer_submit'] = 'admin/Customermaster/add_dealer_submit';
$route['admin/customer_master_ajax'] = 'admin/Customermaster/customer_master_ajax';
$route['admin/edit_customer_master/(:num)'] = 'admin/Customermaster/edit_customer_master/$1';
$route['admin/edit_customermaster_post'] = 'admin/Customermaster/edit_customermaster_post';
$route['admin/edit_dealer_user_form'] = 'admin/Customermaster/edit_dealer_user_form';
$route['admin/add_customermaster_form'] = 'admin/Customermaster/add_customermaster_form';
$route['admin/get_customer_detail/(:num)'] = 'admin/Customermaster/get_customer_detail/$1';
$route['admin/editcustomer_detail'] = 'admin/Customermaster/editcustomer_detail';
$route['admin/add_customer_detail'] = 'admin/Customermaster/add_customer_detail';
$route['admin/customer_type'] = 'admin/Customermaster/customer_type';
$route['admin/customer_type_ajax'] = 'admin/Customermaster/customer_type_ajax';
$route['admin/edit_customer_type/(:num)'] = 'admin/Customermaster/edit_customer_type/$1';
$route['admin/edit_customertype_post'] = 'admin/Customermaster/edit_customertype_post';
$route['admin/add_customertype_form'] = 'admin/Customermaster/add_customertype_form';
$route['admin/customer_detail'] = 'admin/Customermaster/customer_detail';
$route['admin/customer_detail_ajax'] = 'admin/Customermaster/customer_detail_ajax';
$route['admin/edit_customer_detail/(:num)'] = 'admin/Customermaster/edit_customer_detail/$1';
$route['admin/edit_customerdetail_post'] = 'admin/Customermaster/edit_customerdetail_post';
$route['admin/add_customerdetail_form'] = 'admin/Customermaster/add_customerdetail_form';
$route['admin/country_master'] = 'admin/IcStateMaster/country_master';
$route['admin/country_master_ajax'] = 'admin/IcStateMaster/country_master_ajax';
$route['admin/edit_countrymaster/(:num)'] = 'admin/IcStateMaster/edit_countrymaster/$1';
$route['admin/edit_countrymaster_post'] = 'admin/IcStateMaster/edit_countrymaster_post';
$route['admin/add_countrymaster_form'] = 'admin/IcStateMaster/add_countrymaster_form';
$route['admin/addonic'] = 'admin/Addonage/addonic';
$route['admin/addonic_ajax'] = 'admin/Addonage/addonic_ajax';
$route['admin/edit_addonic/(:num)'] = 'admin/Addonage/edit_addonic/$1';
$route['admin/edit_addonic_post'] = 'admin/Addonage/edit_addonic_post';
$route['admin/add_addonic_form'] = 'admin/Addonage/add_addonic_form';
$route['admin/addonage'] = 'admin/Addonage/addonage';
$route['admin/addonage_ajax'] = 'admin/Addonage/addonage_ajax';
$route['admin/edit_addonage/(:num)'] = 'admin/Addonage/edit_addonage/$1';
$route['admin/edit_addonage_post'] = 'admin/Addonage/edit_addonage_post';
$route['admin/add_addonage_form'] = 'admin/Addonage/add_addonage_form';
$route['admin/getcitybystate_ajax/(:num)'] = 'admin/Addonage/getcitybystate_ajax/$1';
$route['admin/addonmaster'] = 'admin/Addonage/addonmaster';
$route['admin/addonmaster_ajax'] = 'admin/Addonage/addonmaster_ajax';
$route['admin/edit_addonmaster/(:num)'] = 'admin/Addonage/edit_addonmaster/$1';
$route['admin/edit_addonmaster_post'] = 'admin/Addonage/edit_addonmaster_post';
$route['admin/add_addonmaster_form'] = 'admin/Addonage/add_addonmaster_form';
$route['admin/capacity_master'] = 'admin/IcStateMaster/capacitymaster';
$route['admin/capacitymaster_ajax'] = 'admin/IcStateMaster/capacitymaster_ajax';
$route['admin/edit_capacitymaster/(:num)'] = 'admin/IcStateMaster/edit_capacitymaster/$1';
$route['admin/edit_capacitymaster_post'] = 'admin/IcStateMaster/edit_capacitymaster_post';
$route['admin/add_capacitymaster_form'] = 'admin/IcStateMaster/add_capacitymaster_form';
$route['admin/city_master'] = 'admin/IcStateMaster/citymaster';
$route['admin/citymaster_ajax'] = 'admin/IcStateMaster/citymaster_ajax';
$route['admin/edit_citymaster/(:num)'] = 'admin/IcStateMaster/edit_citymaster/$1';
$route['admin/edit_citymaster_post'] = 'admin/IcStateMaster/edit_citymaster_post';
$route['admin/add_citymaster_form'] = 'admin/IcStateMaster/add_citymaster_form';
$route['admin/hdfc_vehicle'] = 'admin/goDigitMaster/hdfcvehicle';
$route['admin/hdfcvehicle_ajax'] = 'admin/goDigitMaster/hdfcvehicle_ajax';
$route['admin/edit_hdfcvehicle/(:num)'] = 'admin/goDigitMaster/edit_hdfcvehicle/$1';
$route['admin/edit_hdfcvehicle_post'] = 'admin/goDigitMaster/edit_hdfcvehicle_post';
$route['admin/add_hdfcvehicle_form'] = 'admin/goDigitMaster/add_hdfcvehicle_form';
$route['admin/libertyvideoconmaster'] = 'admin/Videoconmaster/libertyvideoconmaster';
$route['admin/libertyvideoconmaster_ajax'] = 'admin/Videoconmaster/libertyvideoconmaster_ajax';
$route['admin/edit_libertyvideoconmaster/(:num)'] = 'admin/Videoconmaster/edit_libertyvideoconmaster/$1';
$route['admin/edit_libertyvideoconmaster_post'] = 'admin/Videoconmaster/edit_libertyvideoconmaster_post';
$route['admin/add_libertyvideoconmaster_form'] = 'admin/Videoconmaster/add_libertyvideoconmaster_form';
//Liberty Videoco City Master
$route['admin/liberty_city_master'] = 'admin/Videoconmaster/liberty_city_master';
$route['admin/libertycitymaster_ajax'] = 'admin/Videoconmaster/libertycitymaster_ajax';
$route['admin/add_liberty_city_form'] = 'admin/Videoconmaster/add_liberty_city_form';
$route['admin/edit_liberty_city_master/(:num)'] = 'admin/Viodeoconmaster/edit_liberty_city_master/$1';
$route['admin/edit_liberty_city_post'] = 'admin/Videoconmaster/edit_liberty_city_post';
//Policy Details Vehicle Master
$route['admin/payment_type_master'] = 'admin/Policy/payment_type';
$route['admin/payment_type_edit/(:num)'] = 'admin/Policy/payment_type_edit/$1';
$route['admin/payment_type_update'] = 'admin/Policy/payment_type_update';
$route['admin/payment_type_add'] = 'admin/Policy/payment_type_add';
$route['admin/payment_type_ajax'] = 'admin/Policy/payment_type_ajax';
$route['admin/policy_vehicle_master'] = 'admin/Policy/policy_vehicle_master';
$route['admin/policy_vehicle_ajax'] = 'admin/Policy/policy_vehicle_ajax';
$route['admin/add_policy_vehicle_details_form'] = 'admin/Policy/add_policy_vehicle_details_form';
$route['admin/edit_policy_vehicle_details_master/(:num)'] = 'admin/Policy/edit_policy_vehicle_details_master/$1';
$route['admin/edit_policy_vehicle_post'] = 'admin/Policy/edit_policy_vehicle_post';
$route['admin/bhartiavehicle'] = 'admin/Bhartiaxa/bhartiavehicle';
$route['admin/bhartiavehicle_ajax'] = 'admin/Bhartiaxa/bhartiavehicle_ajax';
$route['admin/edit_bhartiavehicle/(:num)'] = 'admin/Bhartiaxa/edit_bhartiavehicle/$1';
$route['admin/edit_bhartiavehicle_post'] = 'admin/Bhartiaxa/edit_bhartiavehicle_post';
$route['admin/add_bhartiavehicle_form'] = 'admin/Bhartiaxa/add_bhartiavehicle_form';
$route['admin/futurerto'] = 'admin/IcStateMaster/futurerto';
$route['admin/futurerto_ajax'] = 'admin/IcStateMaster/futurerto_ajax';
$route['admin/edit_futurerto/(:num)'] = 'admin/IcStateMaster/edit_futurerto/$1';
$route['admin/edit_futurerto_post'] = 'admin/IcStateMaster/edit_futurerto_post';
$route['admin/add_futurerto_form'] = 'admin/IcStateMaster/add_futurerto_form';
$route['admin/add_IcStateMaster_form'] = 'admin/IcStateMaster/add_IcStateMaster_form';
$route['admin/giibmaster'] = 'admin/IcStateMaster/giibmaster';
$route['admin/giibmaster_ajax'] = 'admin/IcStateMaster/giibmaster_ajax';
$route['admin/edit_giibmaster/(:num)'] = 'admin/IcStateMaster/edit_giibmaster/$1';
$route['admin/edit_giibmaster_post'] = 'admin/IcStateMaster/edit_giibmaster_post';
$route['admin/add_giibmaster_form'] = 'admin/IcStateMaster/add_giibmaster_form';
/* admin user control */
$route['admin/add_business_partner'] = 'admin/Customermaster/addBusinessPartner';
$route['admin/business_partner'] = 'admin/Customermaster/businessPartner';
$route['admin/edit_dealer/(:num)'] = 'admin/Customermaster/edit_dealer/$1';
$route['admin/business_partner_ajax'] = 'admin/Customermaster/businessPartnerAjax';
$route['admin/business_users'] = 'admin/BusinessPartner/businessUsers';
$route['admin/get_partners'] = 'admin/BusinessPartner/get_partners';
$route['admin/get_higher_id'] = 'admin/Customermaster/get_higher_id';
$route['admin/business_role'] = 'admin/Customermaster/business_role';
$route['admin/addBusinessUserMaster'] = 'admin/Customermaster/addBusinessUserMaster';
$route['admin/business_users_ajax'] = 'admin/Customermaster/businessUsersAjax';
$route['BusinessPartner/add_user_form'] = 'admin/BusinessPartner/add_user_form';
$route['EmployeeMaster/add_user_form'] = 'admin/EmployeeMaster/add_user_form';
$route['admin/business_agents'] = 'admin/Customermaster/businessAgents';
$route['admin/business_agents_ajax'] = 'admin/Customermaster/businessAgentsAjax';
$route['admin/giib_users'] = 'admin/EmployeeMaster/Users';

$route['admin/ic_users'] = 'admin/EmployeeMaster/ic_users';
$route['admin/ic_users_ajax'] = 'admin/EmployeeMaster/ic_users_ajax';
$route['admin/add_ic_form'] = 'admin/EmployeeMaster/add_ic_form';

$route['admin/add_pos'] = 'admin/Customermaster/add_pos';
$route['admin/add_pos_submit'] = 'admin/Customermaster/add_pos_submit';
/* admin user control */
$route['admin/libertyrtomaster'] = 'admin/Videoconmaster/libertyrtomaster';
$route['admin/libertyrtomaster_ajax'] = 'admin/Videoconmaster/libertyrtomaster_ajax';
$route['admin/edit_libertyrtomaster/(:num)'] = 'admin/Videoconmaster/edit_libertyrtomaster/$1';
$route['admin/edit_libertyrtomaster_post'] = 'admin/Videoconmaster/edit_libertyrtomaster_post';
$route['admin/add_libertyrtomaster_form'] = 'admin/Videoconmaster/add_libertyrtomaster_form';
$route['admin/libertystatemaster'] = 'admin/Videoconmaster/libertystatemaster';
$route['admin/libertystatemaster_ajax'] = 'admin/Videoconmaster/libertystatemaster_ajax';
$route['admin/edit_libertystatemaster/(:num)'] = 'admin/Videoconmaster/edit_libertystatemaster/$1';
$route['admin/edit_libertystatemaster_post'] = 'admin/Videoconmaster/edit_libertystatemaster_post';
$route['admin/add_libertystatemaster_form'] = 'admin/Videoconmaster/add_libertystatemaster_form';


$route['admin/shriramvehicle'] = 'admin/Shriram/shriramvehicle';




$route['admin/motorduplicatelog'] = 'admin/Log/motorduplicatelog';
$route['admin/motorduplicatelog_ajax'] = 'admin/Log/motorduplicatelog_ajax';
$route['admin/edit_motorduplicatelog/(:num)'] = 'admin/Log/edit_motorduplicatelog/$1';
$route['admin/edit_motorduplicatelog_post'] = 'admin/Log/edit_motorduplicatelog_post';
$route['admin/add_motorduplicatelog_form'] = 'admin/Log/add_motorduplicatelog_form';
//working API's
$route['admin/private-car'] = 'admin/Api/private_car';
$route['admin/bike'] = 'admin/Api/bike';
$route['admin/travel'] = 'admin/Api/travel';
$route['admin/soap'] = 'admin/Api/soap';
/* admin user control */
$route['geticlist'] = 'front/quotation/geticlist'; 
$route['quotation'] = 'front/quotation/privatecarinsurance';

//for testing
$route['quotationtest'] = 'front/quotationtest/privatecarinsurance';
$route['quotationtest/privatecar'] = 'front/quotationtest/privatecarinsurance';
$route['quotationtest/bike'] = 'front/quotationtest/bikeinsurance';


$route['quotation/privatecar'] = 'front/quotation/privatecarinsurance';
$route['quotation/bike'] = 'front/quotation/bikeinsurance';
$route['quotation/healthinsurance'] = 'front/quotation/healthinsurance';


//commercial
$route['quotation/commercial-taxi'] = 'front/quotation/CommercialTaxiInsurance';
$route['quotation/commercial-bus'] = 'front/quotation/CommercialBusinsurance';
$route['quotation/commercial-miscd'] = 'front/quotation/CommercialMiscdinsurance';
$route['quotation/commercial-trailer'] = 'front/quotation/CommercialTrailerinsurance';
$route['quotation/commercial-truck'] = 'front/quotation/CommercialTruckInsurance';
$route['quotation/commercial-3PCCV'] = 'front/quotation/CommercialThreewheelerPCCV';
$route['quotation/commercial-3GCCV'] = 'front/quotation/CommercialThreewheelerGCCV';
$route['quotation/commercial-rickshaw'] = 'front/quotation/CommercialThreewheelerRickshaw';
$route['quotation/commercial-ecart-pccv'] = 'front/quotation/CommercialThreewheelerEcartPCCV';
$route['quotation/commercial-ecart-gccv'] = 'front/quotation/CommercialThreewheelerEcartGCCV';




//$route['quotation/commercial'] = 'front/quotation/commercialinsurance';
$route['quotation/travel'] = 'front/quotation/travelinsurance';
$route['quotation/life'] = 'front/quotation/lifeinsurance';
$route['quotation/health'] = 'front/quotation/healthinsurance';
$route['quotation/home'] = 'front/quotation/homeinsurance';
$route['quotation/policydata'] = 'front/quotation/policydatawebservice';
$route['quotation/cbsdata'] = 'front/quotation/cbswebservice';
$route['support/about'] = 'front/support/about';
$route['support/about'] = 'front/support/about';
$route['support/learning'] = 'front/support/learning';
$route['support/career'] = 'front/support/career';
$route['support/claim'] = 'front/support/claim';
$route['support/faq'] = 'front/support/faq';
$route['support/support'] = 'front/support/support';
$route['support/contactus'] = 'front/support/contactus';
$route['customerdetail'] = "front/CustomerDetail";
$route['health_customerdetail'] = "front/HealthCustomerDetail";
$route['customerdetailTravel'] = "front/CustomerDetailTravel";
$route['setSessionFieldsTo'] = "front/CustomerDetail/setSessionFieldsTo";
$route['setSessionFieldsToTravel'] = "front/CustomerDetailTravel/setSessionFieldsToTravel";
$route['getCityName'] = "front/CustomerDetail/getCityName";
$route['getStateName'] = "front/CustomerDetail/getStateName";
$route['proposal'] = "front/proposal";
//view health_proposal //
$route['heatlh_proposal'] = "front/proposal/healthproposal";

$route['myhospicash'] = "front/Hospicash";
$route['hospicash/hospiTestSoap'] = "front/Hospicash/hospiTestSoap";
$route['hospicash/searchForHomePage'] = "front/Hospicash/searchForHomePage";
$route['hospicash/setCustomerData'] = "front/Hospicash/setCustomerData";
$route['hospicash/plan_list'] = "front/Hospicash/fetchPlanDetails";
$route['hospicash/getCities'] = "front/Hospicash/getCities";
$route['add_customer_and_products'] = 'front/Hospicash/add_customer_product';

$route['quotation/get_rto'] = "front/quotation/get_rto";
$route['quotation/generateproposaldata'] = "front/quotation/generateProposalData";




$route['Quotation/callTata'] = 'test/callTata';
$route['Quotation/callBharti'] = 'test/callBharti';
$route['Quotation/callShriram'] = 'test/callShriram';
$route['Quotation/callHdfc'] = 'test/callHdfc';
$route['Quotation/callRelince'] = 'test/callRelince';

$route['Quotation/callPrivateCarReliance'] = 'test/callPrivateCarReliance';


$route['admin/adminType'] = 'admin/AdminMaster/adminType';
$route['admin/roleResource'] = 'admin/AdminMaster/roleResource';
$route['admin/adminRole'] = 'admin/AdminMaster/adminRole';

//reset password master
//$route['admin/nominee_master'] = 'admin/Vehiclemaster/nominee_master';
$route['admin/resetPassword'] = 'admin/Vehiclemaster/reset_password_master';
$route['admin/reset_password_master_ajax'] = 'admin/Vehiclemaster/reset_password_master_ajax';


$route['admin/resetAdminPassword'] = 'admin/Vehiclemaster/reset_admin_user_password_master';
$route['admin/reset_admin_user_password_master_ajax'] = 'admin/Vehiclemaster/reset_admin_user_password_master_ajax';

$route['admin/resetCustomerPassword'] = 'admin/Vehiclemaster/reset_customer_user_password_master';
$route['admin/reset_customer_user_password_master_ajax'] = 'admin/Vehiclemaster/reset_customer_user_password_master_ajax';

$route['admin/field_value_exist/(:any)/(:any)/(:any)'] = 'Common/field_value_exist/$1/$2/$3';
//$route['admin/feed-file'] = 'admin/Feedfile/tata_aig_feed_file';
$route['admin/feed-file'] = 'admin/Feedfile/feed_file';

$route['admin/magma_download_feed_file'] = 'admin/MagmaFeedFile/magma_feed_file';


$route['admin/feed-file-download'] = 'admin/Feedfile/feed_file';
$route['admin/tata_feed_file_ajax'] = 'admin/Feedfile/tata_feed_file_ajax';
$route['admin/tata_feed_file_ajax_post/(:any)/(:any)/(:any)/(:any)'] = 'admin/Feedfile/tata_feed_file_ajax_post/$1/$2/$3/$4';
$route['admin/download_feed_file/(:any)/(:any)/(:any)/(:any)'] = 'admin/Feedfile/download_feed_file/$1/$2/$3/$4';
$route['admin/downloadfeedfile/(:any)/(:any)/(:any)/(:any)'] = 'admin/Feedfile/downloadfeedfile/$1/$2/$3/$4';

$route['admin/download_payslip_csv/(:any)/(:any)/(:any)'] = 'admin/payment/download_payslip_csv/$1/$2/$3';



$route['admin/vehicleMasterAdmin'] = 'admin/Vehiclemaster/vehicle_master_admin';
//$route['admin/vehicle_master_admin_ajax'] = 'admin/Vehiclemaster/vehicle_master_admin_ajax';
$route['admin/vehicle_master_table_ajax'] = 'admin/vehicleMaster/vehicle_master_table_ajax';



$route['myaccount/breaking_mail_to_pos_and_customer_and_giiib/(:num)'] = 'front/proposal/breaking_mail_to_pos_and_customer_and_giiib/$1';

$route['proposalview/(:any)'] = 'front/quotation/loadQuoteView/$1/';



$route['myaccount/load-success'] = 'front/quotation/loadsuccess';
$route['success'] = 'front/success';
$route['forward_success'] = 'front/success/forwardSuccess';



//$route['admin/vehicleMasterAdmin'] = 'admin/Vehiclemaster/vehicle_master_admin';

//$route['admin/vehicle_master_table_ajax'] = 'admin/vehicleMaster/vehicle_master_table_ajax';


  $route['admin/lvgi_rto_ajax'] = 'admin/icmaster/lvgi_rto_ajax';
  $route['admin/add_lvrto_form'] = 'admin/icmaster/add_lv_rto_form';
  $route['admin/edit_lv_rto/(:num)'] = 'admin/icmaster/ajax_lv_rto_form/$1';
  $route['admin/remove_lv_rto/(:num)'] = 'admin/icmaster/remove_lv_rto_form/$1';




$route['travel_proposal'] = "front/proposal";
$route['getPraposal'] = "front/proposal/getPraposal";

$route['admin/pendingendorsement']      = "admin/Endorsement/pendingendorsement";
$route['admin/pendingendorsement_ajax'] = "admin/Endorsement/pendingendorsement_ajax";

$route['admin/approvedendorsement']      = "admin/Endorsement/approvedendorsement";
$route['admin/approvedendorsement_ajax'] = "admin/Endorsement/approvedendorsement_ajax";

$route['admin/referbackendorsement']      = "admin/Endorsement/referbackendorsement";
$route['admin/referbackendorsement_ajax'] = "admin/Endorsement/referbackendorsement_ajax";


$route['admin/rejectendorsement']      = "admin/Endorsement/rejectendorsement";
$route['admin/rejectendorsement_ajax'] = "admin/Endorsement/rejectendorsement_ajax";

//  TOP UP
$route['demo'] = 'SavedProposal/demo';


/*$route['admin/rejectendorsement']      = "admin/Endorsement/rejectendorsement";
$route['admin/rejectendorsement_ajax'] = "admin/Endorsement/rejectendorsement_ajax";
 */
$route['admin/endosment-details']      = "admin/Endorsement/endosment_details";
$route['admin/endorsement_action']      = "admin/Endorsement/endorsement_action_post";

 $route['admin/endosment-list/(:any)/(:any)'] = 'admin/endorsement/endosment_list/$1/$2';
//   --REPORT--   // 

$route['admin/ic_revenue_report'] = 'admin/ReportMaster/ic_revenue_report';
$route['admin/pos_revenue_report'] = 'admin/ReportMaster/pos_revenue_report';
$route['admin/product_revenue_report'] = 'admin/ReportMaster/product_revenue_report';
$route['admin/dealer_revenue_report'] = 'admin/ReportMaster/dealer_revenue_report';
$route['admin/rm_report'] = 'admin/ReportMaster/rm_report';

$route['admin/mothoot_policy_summary_report'] = 'admin/ReportMaster/mothoot_policy_summary_report';
$route['mothoot_policy_summary_ajax'] = 'admin/ReportMaster/mothoot_policy_summary_ajax';
$route['admin/download_mothoot_policy_summary_report/(:any)/(:any)/(:any)/(:any)'] = 'admin/ReportMaster/download_mothoot_policy_summary_report/$1/$2/$3/$4';
$route['admin/sixtyfour_vb_report'] = 'admin/ReportMaster/sixtyfour_vb_report';
$route['sixty_four_report_ajax'] = 'admin/ReportMaster/sixty_four_report_ajax';
$route['admin/download_sixtyfour_report/(:any)/(:any)/(:any)/(:any)'] = 'admin/ReportMaster/download_sixtyfour_report/$1/$2/$3/$4';
$route['admin/upload'] = 'admin/ReportMaster/sixtyfour_vb_upload';
$route['admin/upload_64vb_docemnt'] = 'admin/ReportMaster/upload_64vb_docemnt';
$route['admin/download-64-vb-complete-data'] = 'admin/ReportMaster/download_64_vb_excel_file';


$route['admin/breaking_report'] = 'admin/ReportMaster/breaking_report';
$route['breaking_report_ajax'] = 'admin/ReportMaster/breaking_report_ajax';
$route['admin/download_breaking_report/(:any)/(:any)/(:any)/(:any)'] = 'admin/ReportMaster/download_breaking_report/$1/$2/$3/$4';




$route['admin/pending_case'] = 'admin/ReportMaster/pending_case';
$route['pending_breaking_report_ajax'] = 'admin/ReportMaster/pending_breaking_report_ajax';
$route['admin/download_breaking_report/(:any)/(:any)/(:any)/(:any)'] = 'admin/ReportMaster/download_breaking_report/$1/$2/$3/$4';
$route['admin/break-in-approvel/(:any)'] = 'admin/ReportMaster/approvelbreakinpendingcase/$1';
$route['admin/break-in-view/(:any)'] = 'admin/ReportMaster/approvelbreakinview/$1';
$route['admin/breaking_case_pdf'] = 'admin/ReportMaster/report_pdf';
$route['upload_break_pdf'] = 'admin/ReportMaster/break_in_case_upload';






$route['admin/approved_case'] = 'admin/ReportMaster/approved_case';
$route['approved_breaking_report_ajax'] = 'admin/ReportMaster/approved_breaking_report_ajax';
$route['admin/download_approved_breaking_report/(:any)/(:any)/(:any)/(:any)'] = 'admin/ReportMaster/download_approved_breaking_report/$1/$2/$3/$4';



$route['admin/referback_case'] = 'admin/ReportMaster/referback_case';
$route['referback_case_ajax'] = 'admin/ReportMaster/referback_case_ajax';
$route['admin/download_referback_case_ajax/(:any)/(:any)/(:any)/(:any)'] = 'admin/ReportMaster/download_referback_case_ajax/$1/$2/$3/$4';
$route['admin/view-break-in-pdf/(:any)'] = 'admin/ReportMaster/view_break_in_case_pdf/$1';



$route['admin/reject_case'] = 'admin/ReportMaster/reject_case';
$route['reject_case_ajax'] = 'admin/ReportMaster/reject_case_ajax';
$route['admin/download_reject_case_ajax/(:any)/(:any)/(:any)/(:any)'] = 'admin/ReportMaster/download_reject_case_ajax/$1/$2/$3/$4';

//GIIB ACCOUNTS
$route['admin/offline_policies'] = 'admin/ReportMaster/offline_policies';



$route['admin/cancellation_report'] = 'admin/ReportMaster/cancellation_report';
$route['cancellation_report_ajax'] = 'admin/ReportMaster/cancellation_report_ajax';
$route['admin/download_cancellation_report/(:any)/(:any)/(:any)/(:any)'] = 'admin/ReportMaster/download_cancellation_report/$1/$2/$3/$4';

$route['admin/endorsement_report'] = 'admin/ReportMaster/endorsement_report';
$route['admin/endosmentlist_ajax/(:num)'] = "admin/endorsement/endosmentlist_ajax/$1";
$route['Reliance/reliance_payment_response'] = 'front/Payment/payment_response';
$route['Travel/bharti_payment_response']     = 'front/Payment/payment_response';
$route['getPlanTravel']     = 'front/CustomerDetailTravel/getSpecificPlan';
$route['get_banks_list'] = 'front/myaccount/PaySlip/get_bank_details';
$route['get_cities_list'] = 'front/myaccount/PaySlip/get_cities_list';
$route['deler_check_action'] = 'front/myaccount/PaySlip/add_delaer_check_details';
$route['myaccount/dealer_check_details'] = 'front/myaccount/PaySlip/dealer_check_details';
$route['myaccount/get_paysliplist_ajax/(:any)/(:any)/(:any)'] = 'front/myaccount/PaySlip/get_paysliplist_ajax/$1/$2/$3';
$route['front/myaccount/BreakinCase/breakincase_privatecar_ajax'] = 'front/myaccount/BreakinCase/breakincase_privatecar_ajax';
$route['checknia/(:any)'] = 'Checknia/checkniapolicy/$1';
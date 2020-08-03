<?php
require APPPATH . '../vendor/autoload.php';
use \Firebase\JWT\JWT;
class Admin_Model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

  
    function generateToken($id,$user_name,$pan_no){
       

        $jwt_key = $this->config->item('jwt_key');
        $token['id'] = $id; 
        $token['username'] = $user_name;
        $token['pan_no'] = $pan_no;
        $date = new DateTime();
        $token['iat'] = $date->getTimestamp();
        $token['exp'] = $date->getTimestamp() + 60*60*5; 
        $jwt_generate = JWT::encode($token,$jwt_key ); 
        return $jwt_generate;

    }

 public function update_user_data($email,$data){
        $this->db->where('email', $email);
        $this->db->update('users', $data);


    } 
    function isValidToken(){
        //HS256
        $jwt_key = $this->config->item('jwt_key');
        $token = $this->input->request_headers();

        $token = $token['Authorization'];
        try {
           $token_decode = JWT::decode($token, $jwt_key, array('HS256'));
        } catch (\Exception $exception) {
             $token_decode = False;
        }
      
        return $token_decode;
   
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


     public function checkRedptionStatus($pan_no,$nav_value,$amount,$unit){
            $sql="select * from users_portfolio_daily_total  where pan='$pan_no' and status='P' and type='Redemptions' ";
            $result_portfolio=$this->db->query($sql)->result_array();
            $result = array();
            foreach ($result_portfolio as $key => $value) {
                if($value['invested_unit'] == '0'){
                    $invested_amount   = $value['invested_amount'];
                    $cal_unit = $invested_amount/$nav_value;
                    $unit = $unit-$cal_unit;
                }else if($value['invested_amount'] == '0'){
                     $cal_unit   = $value['invested_unit'] ;
                     $invested_amount = $cal_unit*$nav_value;
                     $amount = $amount-$invested_amount; 

                }
            }
            $result['unit'] = $unit;
            $result['amount'] = $amount;
            return $result;
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

    function insertIntoTableBatch($table_name, $data) {
        if ($this->db->insert_batch($table_name, $data)) {
            return true;
        } else {
            return false;
        }
    }

    function getdatawithoutid($table,$id,$colum,$value)
    {
        $sql="select $colum from $table where id!='$id' and $colum='$value'";
        $result=$this->db->query($sql)->row();    
        return $result;

    }

     function getdatawithid($table,$id,$colum,$value)
    {
        $sql="select $colum from $table where id='$id' and $colum='$value'";
        $result=$this->db->query($sql)->row();    
        return $result;

    }

    

    function uploadFile($image_file,$directory){
        if ($image_file && $directory) {
            $filename = $_FILES[$image_file]["name"];
            $_FILES[$image_file]["name"] = time().$filename;
            $config = array(
                'upload_path' => './'.$directory
                //'allowed_types' => 'jpg|jpeg|gif|png',
             );
            $this ->load->library("upload", $config);
            if ($this->upload->do_upload($image_file)) {
                $image_data = $this->upload->data();
                return $newimagename = $image_data["file_name"];
            }else{
                return false;
            
            }
        }
    }
}

<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class User_model extends Model {

    public function __construct() {
        parent::__construct();
        $this->call->database();
    }

    public function get_user_by_email($email) {
        return $this->db->table('users')->where('email', $email)->get();
    }

    public function insert($name, $password, $email, $verification, $is_verify) {
        $data = array(
            'name' => $name,
            'password' => $password,
            'email' => $email,
            'verification_code' => $verification,
            'is_verified' => !empty($is_verify) ? (int)$is_verify : 0,  // Ensure it's either 0 or 1
        );
        return $this->db->table('users')->insert($data);
    }
    
    public function updateVerificationCode($email, $verificationCode) {
        $data = array(
            'verification_code' => $verificationCode,
        );
    
      return  $this->db->table('users')->where('email',$email)->update($data);
    }

    
public function verifyUser($email, $verificationCode) {
    $user = $this->db->table('users')->where('email', $email)->get();
  
    if ($user && $user['verification_code'] === $verificationCode) {
  
        $data = array('is_verified' => true);
        $this->db->table('users')->where('email', $email)->update( $data);

        return true;
    }else{
        return false;
    }


}

}
?>
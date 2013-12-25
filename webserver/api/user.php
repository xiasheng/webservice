
<?php

require_once 'lib/mysql.php';

class User
{

    public function __construct() {
        
        
    }
    
    private function genRandomStr($len) {
      $chars= "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
      $res = "";
    
      for ($i = 0; $i < $len; $i++) {
        $pos = mt_rand(0, strlen($chars));
        $res .= substr($chars, $pos, 1);
      }
      return $res;
    }
      
    private function verifyAccountInfo($args) {
      $ret = Array("status"=>0, "info"=>"success");
      
      if (!isset($args['username'])) {
        $ret["status"] = 200;
        $ret["info"] = "invalid username";
        return $ret;
      }
      
      if (!isset($args['email']) || !filter_var($args['email'], FILTER_VALIDATE_EMAIL)) {
        $ret["status"] = 400;
        $ret["info"] = "invalid email";
        return $ret;
      }
      
      if (!isset($args['pwd'])) {
        $ret["status"] = 400;
        $ret["info"] = "invalid pwd";
        return $ret;
      }
      
      if (mysql::getInstance()->is_user_exist($args['username'])) {
        $ret["status"] = 400;
        $ret["info"] = "Sorry, this user already existed, please choose another one";
        return $ret;        
      }
      
      return $ret;
      
    }

    public function register($args) {
        $ret = $this->verifyAccountInfo($args);
        if ($ret['status'] != 200) {
          return $ret;  
        }
        
        $ret = Array("status"=>0, "info"=>"success");
        $r = mysql::getInstance()->register_user($args['username'], $args['pwd'], $args['email']);
        if (false == $r){
          $ret['status'] = 400;
          $ret['info'] = "register account failed";
        }
        return $ret;
     }

    public function login($args) {
      
      $mysql = mysql::getInstance();
      $uid = $mysql->check_user($args['username'], $args['pwd']);
      if (-1 == $uid) {
        return Array("status"=>401, "info"=>"invalid username or password");
      }
    
      if ($mysql->is_user_online($uid)) {
        $mysql->update_user_online($uid);
        return Array("status"=>200, "info"=>"This account is already logged in");;
      }else {
        $access_token = $this->genRandomStr(32);
        $refresh_token = $this->genRandomStr(32);
        $mysql->add_user_online($uid, $access_token, $refresh_token);
        return Array("status"=>200, "info"=>"login success", "access_token"=>$access_token, "refresh_token"=>$refresh_token);
      }
    }  
    
    public function logout($args) {
      $mysql = mysql::getInstance();
      $mysql->delete_user_online($args['access_token']);
      return Array("status"=>200, "info"=>"logout success");
    } 
       
 }


?>


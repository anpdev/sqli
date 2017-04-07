<?php

/*
****** Library Information *******
#Library name 'sqli protection'.
#Purpose - To avoid SQL injection.
#Developer Anoop Patidar.
#Version beta
#Licence
#Created date 07-April-17.
#Last modified date 07-April-17.
*************************************

This class is for common mysqli function, it supports only mysqli with prepare statement.

All Functions will return an array with status.

****** Response example of insert, delete, update:******


***** Response of Auth: *******
 It will return a mysqli instance  instead of data array. 
 you can loop this anywhere using any of this method mysqli->fetch_object(),
  mysqli->fetch_assoc(), mysqli->fetch_array(), mysqli->fetch_array(ASSOC), mysqli->fetch_array(MYSQLI_NUM)
, mysqli->fetch_array(BOTH);  

***** Response of Select: *******
  This will be array of object for example..
   Array
      (
          [0] => stdClass Object
              (
                  [column] => val
                  [column] => val
                   ...
                   ...
              )
          [1] => stdClass Object
              (
                  [column] => val
                  [column] => val
                   ...
                   ...
              )  
          ...
          ...    
      )



***** Response of SetRecord: *******
  It will be an intger i.e. table id, the id of last inserted record

***** Response of update: *******
   It will be an integer i.e.  >= 1 , effected nuw row, 


***** Response of delete: *******
    It will be an integer i.e. >= 1, effected nuw row, 


-----------------------------------------------------------
*/


class Connection  {
    
 function __construct($host,$user,$pass,$db) {
       $this->host = $host;
       $this->user = $user;
       $this->pass = $pass;
       $this->db = $db;
       $this->_connect();
    
  
    }
function _connect(){
      $this->mysqli = new mysqli($this->host, $this->user, $this->pass, $this->db);
        if ($this->mysqli->connect_error) {
            die("Connection failed: " . $this->mysqli->connect_error);
        }
        return $this->mysqli;
}
function close() {
       	$this->mysqli->close();
    }
}

class DB extends Connection {

    
function auth($sql, $param = array()) {
        
       $stmt = $this->mysqli->stmt_init();
       $stmt->prepare($sql);
      
       if(! empty($param) || ! is_null($param))
         call_user_func_array([$stmt,'bind_param'], $this->refValues($param));
       
       $stmt->execute();
       $result  = $stmt->get_result();
       
       if(! $result->num_rows > 0) : 
           return false;  
       endif;

         $stmt->close();
         $this->close();

        return $result;

    }

    function select($sql, $param = array()) {
        
       $stmt = $this->mysqli->stmt_init();
       $stmt->prepare($sql);
      
       if(! empty($param) || ! is_null($param))
         call_user_func_array([$stmt,'bind_param'], $this->refValues($param));
       
       $stmt->execute();
       $result  = $stmt->get_result();
       
       if($result->num_rows > 0) : 

          while($row = $result->fetch_object()) :
             $data[] = $row;
            endwhile;
          else :
           return false;  
       endif;

          $stmt->close();
         $this->close();

        return $data;

    }

    function setRecord($sql, $param=[]) {

       $stmt = $this->mysqli->stmt_init();
       $stmt->prepare($sql);
      
       call_user_func_array([$stmt,'bind_param'],$this->refValues($param));
       
       if(!$stmt->execute()) :
          die('Error : ('. $this->mysqli->errno.')'. $this->mysqli->error);
       endif;


       $last_inserted_id = $stmt->insert_id;
       $stmt->close();
       $this->close();

      return $last_inserted_id;

    }
  function update ( $sql, $param=[]) {


       
       $stmt = $this->mysqli->stmt_init();
       $stmt->prepare($sql);
      
       call_user_func_array([$stmt,'bind_param'],$this->refValues($param));

       if(! $stmt->execute()) :
        die('Error : ('. $this->mysqli->errno.')'. $this->mysqli->error);
       endif;

       $affected_rows = $stmt->affected_rows; 
       $stmt->close();
       $this->close();
      return $affected_rows;

  }

  function delete( $sql, $param=[])  {

       $stmt = $this->mysqli->stmt_init();
       $stmt->prepare($sql);
      
       call_user_func_array([$stmt,'bind_param'],$this->refValues($param));
       
       if(!$stmt->execute()) :
          die('Error : ('. $this->mysqli->errno.')'. $this->mysqli->error);
       endif;
      
       $affected_rows = $stmt->affected_rows;
       $stmt->close();
       $this->close();
      
      return $affected_rows;  
  }
   function refValues($arr) {
     if(strnatcmp(phpversion(), '5.3') >= 0) // Reference is required for PHP 5.3+
        { 
        $refs = [];
         foreach ($arr as $key => $value) 
                 $refs[$key] = &$arr[$key];
         
         return $refs;   
       }
       return $arr;
     } 


 function isValidEmail($email) {

        if(filter_var($email, FILTER_VALIDATE_EMAIL)  && preg_match('/@.+\./', $email)) :
         return $email;
     else :
         $arr = ['re_fun'=>'','arg'=>[],'class'=>'errMsg', 'msg'=>'Email address is not a valid email !', 'data'=>''];
         echo json_encode($arr); exit;
     endif;   
} 

function isValidNumber( $contact) {
     
    $contact = (int ) trim(strtolower( $contact)); 

    if(filter_var($contact, FILTER_VALIDATE_INT)) :         
          return $contact;
         else :
             $arr = ['re_fun'=>'','arg'=>[],'class'=>'errMsg', 'msg'=>'Contact number is not a valid number !', 'data'=>''];
             echo json_encode($arr); exit;
         endif; 

}

function isvalidPassword ($password) {

   if(filter_var($password, FILTER_VALIDATE_REGEXP,array( "options"=> array( "regexp" => "/.{6,25}/")))) :
    $options = [
                 'cost' => 11,
                 'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
               ];

       return password_hash($password, PASSWORD_BCRYPT, $options);
    else :
       $arr = ['re_fun'=>'','arg'=>[],'class'=>'errMsg', 'msg'=>'Password is invalid !', 'data'=>''];
      echo json_encode($arr); exit;
    endif; 
   }
}
   

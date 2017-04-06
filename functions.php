<?php

/*
****** Library Information *******
#Library name 'sqli protection'.
#Purpose - To avoid SQL injection.
#Developer Anoop Patidar | Start date 16-May-16 , 09-May-16.
#Version beta
#Licence
#Created date 05-April-17.
#Last modified date 05-April-17.
*************************************

This class is for common mysqli function, it supports only mysqli with prepare statement.

All Functions will return an array with status.

****** Response example of insert, delete, update:******


***** Response of Select: *******
 It will be an array  fo record 
Array
(
    [0] => Array
        (
            [field] => val
            [field] => val
            ...
            ...
            ...
        )
    [1] => Array
        (
            [field] => val
            [field] => val
            ...
            ...
            ...
        )   
   ...
   ...
   ...
)
***** Response of SetRecord: *******
  It will be an intger i.e. table id, the id of last inserted record

***** Response of update: *******
   It will be an integer i.e. 1, effected nuw row, 


***** Response of delete: *******
    It will be an integer i.e. 1, effected nuw row, 


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
      $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        return $this->conn;
}
function close() {
       	$this->conn->close();
    }
}

class DB extends Connection {


    
    function select($sql, $param = array()) {
     
       $stmt = $this->conn->stmt_init();
       $stmt->prepare($sql);
       
       // $stmt->bind_param($param);
       if(! empty($param))
         call_user_func_array([$stmt,'bind_param'], $this->refValues($param));
       
       $stmt->execute();
       $res  = $stmt->get_result();
       if($res->num_rows > 0) {
        while($row = $res->fetch_array(MYSQLI_ASSOC))
         {
            $data[] = $row;
         }

        
       }
          $stmt->close();
         $this->close();

        return $data;

    }

    function setRecord($sql, $param=[]) {

       

       $stmt = $this->conn->stmt_init();
       $stmt->prepare($sql);
      



       call_user_func_array([$stmt,'bind_param'],$this->refValues($param));
       if($stmt->execute()) {
         return $stmt->insert_id;
      } else {
        die('Error : ('. $this->conn->errno.')'. $this->conn->error);
      }
          $stmt->close();
        $this->close();
    }
  function update ( $sql, $param=[]) {


       
       $stmt = $this->conn->stmt_init();
       $stmt->prepare($sql);
      
       call_user_func_array([$stmt,'bind_param'],$this->refValues($param));
       if(! $stmt->execute()) {
         
        $arr = 0;
      } else {

         $arr = $stmt->affected_rows;
      }

          $stmt->close();
        $this->close();
       return $arr;

  }

  function delete( $sql, $param=[])  {

       $stmt = $this->conn->stmt_init();
       $stmt->prepare($sql);
      
       call_user_func_array([$stmt,'bind_param'],$this->refValues($param));
       if(!$stmt->execute()) {
        $arr = 0;
      } else {

         $arr = $stmt->affected_rows;
      }
         $stmt->close();
        $this->close();
      return $arr; 
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
   }
   
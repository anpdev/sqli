<?php 
include 'config.php';  
 $arr = array('re_fun'=>'','arg'=>array(),'class'=>'errMsg','msg'=>'Server error occured.','data'=>'');
 extract($_REQUEST);
 $arr = ['re_fun'=>'','arg'=>[],'class'=>'errMsg', 'msg'=>'server error occured !', 'data'=>''];

 
if($mode==='select') {
     $sql = "SELECT * FROM tbl_admin where email = ?";
     $param = ["s", 'amw.anooppatidar@gmail.com'];
       
     $data = $conn->select($sql,$param); 
     
     echo '<pre>';
     print_r($data);
     exit;

    if($data )
     {
       $_SESSION['username'] = $name; 
       
       $arr['class']='sucMsg';
       $arr['msg']='Login successfully';
       $arr['redirect']= 'index.php';   
    } else { 
      $arr['class']='errMsg';
      $arr['msg']='Username OR password not matched';
    }
     echo json_encode($arr); 
 }


if($mode==='insert') { 

     $uid = mysqli_real_escape_string($conn->conn, 11);
     $uname = mysqli_real_escape_string($conn->conn, 'Test');
     $email = mysqli_real_escape_string($conn->conn, 'amw.anooppatidar@gmail.com');
     
     $password = md5(mysqli_real_escape_string($conn->conn, 'Test#12345'));
     $sql = "INSERT INTO tbl_admin (`uid`, `uname`, `email`,`password`) VALUES (?, ?, ?,?) ";
     $param = ["isss", $uid,$uname,$email,$password]; 
      
      $result= $conn->setRecord($sql, $param);

      if($result)  {
         $arr['class']='sucMsg';
         $arr['msg']='added successfully';
         
          } else  {
         $arr['class']='errMsg';
         $arr['msg'] = '!Error, something went wrong.'; 
         }
         echo json_encode($arr); exit;
  }

if($mode ==='update') { 
       $sql = "UPDATE tbl_admin SET email = ?  WHERE uname = ?";
       $param = ['ss','test54321@gmail.com','testtyrtru']; 
       $result = $conn->update($sql,$param);
       if($result === 1) {
       $arr['class']='sucMsg';
       $arr['msg']='Record updated successfully';
        } else {
       $arr['msg'] = '!Error, something went wrong.'; 
      }       
     echo json_encode($arr); 
   }


if($mode ==='delete') { 
   $sql = "DELETE FROM tbl_admin WHERE id = ?";
   $param = ['i',4]; 

   $delete = $conn->delete($sql, $param);
       
    if($delete === 1) {
                $arr['class']='sucMsg';
                $arr['msg']='Record deleted successfully';
       } else {  
     $arr['msg'] = '!Error, something went wrong.'; 
      }    
    echo json_encode($arr); 
     
}
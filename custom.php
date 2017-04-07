<?php 

include 'config.php';  
 
 extract($_REQUEST);
 
 $arr = ['re_fun'=>'','arg'=>[],'class'=>'errMsg', 'msg'=>'server error occured !', 'data'=>''];

 

if(isset($mode) && $mode==='login') {

   		$hash = $mysqli->isvalidPassword($password);


   		$sql = "SELECT * FROM tbl_admin where uname = ?";
   		$param = ["s", $uname];
   		
   		$data = $mysqli->auth($sql,$param); 


		if(! is_bool($data)) :   
		    while($row =  $data->fetch_object()) :
		     
			    if(password_verify($password,$row->password)) :
			       $_SESSION['uname'] = $row->uname;	
			       $arr['class']='sucMsg';
			       $arr['msg']='Login has been successfully';
			       
			    else : 
			      
			       $arr['msg']='Password not matched';
			    endif; 

		    endwhile;
		   else :
		     $arr['msg']='Username not matched';
		endif; 
    
    echo json_encode($arr); 
  }

if(isset($mode) && $mode==='logout') {
   
   session_destroy();
 }

if(isset($mode) && $mode==='select') {

  		$email = $mysqli->isValidEmail($email);
  
	    $sql = "SELECT * FROM tbl_admin where email = ?";
	    $param = ["s", $email];
	    $data = $mysqli->select($sql,$param); 
     
        if($data ) :
	       $arr['class']='sucMsg';
	       $arr['msg']='record fetched successfully';
	       $arr['data']= $data;   
	    else : 
	       $arr['msg']='No matching record found';
	    endif;

      echo json_encode($arr); 
 }



if(isset($mode) && $mode==='insert') { 

	   $email = $mysqli->isValidEmail($email);
	   $password = $mysqli->isvalidPassword($password); 
	   $contact = $mysqli->isValidNumber($contact);
	  

	   $sql = "INSERT INTO tbl_admin (`uid`, `uname`, `email`,`password`,`contact`) VALUES (?, ?, ?, ?, ?) ";
	   $param = ["isssi", $uid,$uname,$email,$password,$contact]; 
	      
	   $result= $mysqli->setRecord($sql, $param);

	    if($result) :
	         $arr['class']='sucMsg';
	         $arr['msg']='added successfully';
	         
	    else :
	         
	         $arr['msg'] = '!Error, something went wrong.'; 
	    endif;

	    echo json_encode($arr); exit;
  }

if(isset($mode) && $mode ==='update') { 
       
	   $sql = "UPDATE tbl_admin SET email = ?  WHERE uid = ?";
	   $param = ['si',$email,$uid]; 

	   $update = $mysqli->update($sql,$param);
	       
	    if($update >= 1) :

	       $arr['class']='sucMsg';
	       $arr['msg']='Record updated successfully';
	       
	    else :
	       
	       $arr['msg'] = '!Error, something went wrong.'; 
	    
	    endif;      
	    
	    echo json_encode($arr); 
   }


if(isset($mode) && $mode ==='delete') { 
   
	   $id = mysqli_real_escape_string($mysqli->mysqli,$id);
	   $sql = "DELETE FROM tbl_admin WHERE id = ?";

	   $param = ['i',$id]; 

	   $delete = $mysqli->delete($sql, $param);
	       
	    if($delete >= 1) :
	     
	        $arr['class']='sucMsg';
	        $arr['msg']='Record deleted successfully';
	     
	    else :

	        $arr['msg'] = '!Error, something went wrong.'; 
	     
	    endif;    
	   
	    echo json_encode($arr); 
	     
}


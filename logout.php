<?php
    include("config.php");
	include("functions.php");
	
	if ($conn->connect_error) {
		die('{"status":{"login_status":false,"action_status":false,"error_message":"Server Error"},"dataset":{"user_hash":""}}');
	} 
   
    $passkey=$_POST['passkey'];
    $lt=date("Y-m-d h:i:sa");

    $sql= "UPDATE `tbl_session` SET `logout_time`='".$lt."',`isactive`=0 WHERE `passkey`='".$passkey."'";
    $result = $conn->query($sql);
    if($result === TRUE)
         echo "logout successful";
    else
        echo "failed to logout".$conn->error;

?>
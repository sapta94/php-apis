<?php
	include("config.php");
	
	if ($conn->connect_error) {
		die('{"status":{"login_status":false,"action_status":false,"error_message":"Server Error"},"dataset":{"user_hash":""}}');
	} 
	
	$email = "";
	if(isset($_POST['email'])){
		$email = $_POST['email'];
	}
	
	$phone = "";
	if(isset($_POST['phone'])){
		$phone = $_POST['phone'];
	}
	$emailAvailable = false;
	$phoneAvailable = false;
	$response = array("status"=>array(),"dataset"=>array());
	
	
	$user_hash = "";
	$response["status"] = array("login_status"=>false,"action_status"=>false,"error_message"=>"");
	if(strlen($email)==0 || strlen($phone)==0){
		$error_message = "Please provide email or Phone No.";
	} else {
		$sql = "SELECT user_hash FROM tbl_user where email='".$email."' or phone_no='".$phone."'";
		//echo $sql;
		$result = $conn->query($sql);
		
		
		//var_dump($result,mysqli_error($conn));
		
		
		
		if ($result && $result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				//echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
				$emailAvailable = false;
				$phoneAvailable = false;
				$error_message = "Email or Phone Already Exists, do you want to login instead";
				$response["status"] = array("login_status"=>false,"action_status"=>true);
			}
		} else {
			$ai_user_id = "";
			$user_hash = "";
			$error_message = "Email and Phone Not Exists";
			$emailAvailable = true;
			$phoneAvailable = true;
		}
	}
	
	
	$response["dataset"] = array("emailAvailable"=>$emailAvailable,"phoneAvailable"=>$phoneAvailable);
	$response["status"]["error_message"] = $error_message;
	echo json_encode($response);
?>
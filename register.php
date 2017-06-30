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
	
	$country = "";
	if(isset($_POST['country'])){
		$country = $_POST['country'];
	}
	
	$state = "";
	if(isset($_POST['state'])){
		$state = $_POST['state'];
	}
	
	$fname = "";
	if(isset($_POST['fname'])){
		$fname = $_POST['fname'];
	}
	
	$lname = "";
	if(isset($_POST['lname'])){
		$lname = $_POST['lname'];
	}
	
	$password = "";
	if(isset($_POST['password'])){
		sha1($password = $_POST['password']);
	} else {
		$error_message = "User Id or Password Blank";
	}
	
	$latitude = "";
	if(isset($_POST['latitude'])){
		$latitude = $_POST['latitude'];
	}
	
	$longitude = "";
	if(isset($_POST['longitude'])){
		$longitude = $_POST['longitude'];
	}
	
	
	
	
	$response = array("status"=>array(),"dataset"=>array());
	
	
	$user_hash = "";
	$response["status"] = array("login_status"=>false,"action_status"=>false,"error_message"=>"");
	if(strlen($email)==0 || strlen($phone)==0 || strlen($password)==0){
		$error_message = "Please provide all details";
	} else {
		$sql = "SELECT user_hash FROM tbl_user where email='".$email."' or phone_no='".$phone."'";
		//echo $sql;
		$result = $conn->query($sql);
		
		
		//var_dump($result,mysqli_error($conn));
		
		
		
		if ($result && $result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				//echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
				$error_message = "Email or Phone Already Exists, do you want to login instead";
				$response["status"] = array("login_status"=>false,"action_status"=>false);
			}
		} else {
			$user_hash = sha1($email.$phone);
			$error_message = "";
			//var_dump($phone);
			
			$insert_sql = "INSERT INTO tbl_user(`first_name`,`last_name`,`phone_no`,`password`,`email`,`country`,`state`,`latitude`,`longitude`,`user_hash`)
			VALUES('".$fname."','".$lname."','".$phone."','".$password."','".$email."','".$country."','".$state."','".$latitude."','".$longitude."','".$user_hash."')";
			//echo $insert_sql;
			$insert_result = $conn->query($insert_sql);
			
			if($insert_result === TRUE) {
				$response["status"] = array("login_status"=>true,"action_status"=>true);
				$error_message = "Successfully Registered";
			} else {
				$error_message = mysqli_error($conn);
			}
		}
	}
	
	
	$response["dataset"] = array("user_hash"=>$user_hash,"phone"=>$phone);
	$response["status"]["error_message"] = $error_message;
	echo json_encode($response);
?>

<!DOCTYPE html>
<html>
   <head>
     <title>Login redirect</title>
   </head>
   <body>
       <form method="POST" action="logout.php">
	      <input type="text" name="passkey">
		  <input type="submit" value="Logout">
	   </form>
   </body>
</html>
<?php
	include("config.php");
	include("functions.php");
	
	if ($conn->connect_error) {
		die('{"status":{"login_status":false,"action_status":false,"error_message":"Server Error"},"dataset":{"user_hash":""}}');
	} 
	
	$userid = "";
	if(isset($_POST['userid'])){
		$userid = $_POST['userid'];
	} else {
		$error_message = "User Id or Password Blank";
	}
	
	$password = "";
	if(isset($_POST['password'])){
		sha1($password = $_POST['password']);
	} else {
		$error_message = "User Id or Password Blank";
	}
	
	$sql = "SELECT ai_user_id,user_hash FROM tbl_user where (email='".$userid."' and password='".$password."') or (phone_no='".$userid."' and password='".$password."')";
	//echo $sql;
	$result = $conn->query($sql);
	$response = array("status"=>array(),"dataset"=>array());
	
	$ai_user_id = "";
	$user_hash = "";
	$passkey = "";
	$response["status"] = array("login_status"=>false,"action_status"=>false,"error_message"=>"");
    $allfunc=new allfuncs();
	
	
	//var_dump($result,mysqli_error($conn));
	
	if ($result && $result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			//echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
			$ai_user_id = $row["ai_user_id"];
			$user_hash = $row["user_hash"];
			$error_message = "Login Successfull";
			$response["status"] = array("login_status"=>true,"action_status"=>true,"error_message"=>"");
			$passkey=$allfunc->generatePasskey();
			$lt=date("Y-m-d h:i:sa");
			$qry="INSERT INTO tbl_session(`user_hash`,`passkey`,`login_time`,`isactive`) VALUES('".$user_hash."','".$passkey."','".$lt."','1')";
		    $res=$conn->query($qry);
		}
	} else {
		$ai_user_id = "";
		$user_hash = "";
		$error_message = "Invalid Credentials";
	}
	$response["dataset"] = array("user_hash"=>$user_hash,"passkey"=>$passkey);
	$response["status"]["error_message"] = $error_message;
	echo json_encode($response);
?>

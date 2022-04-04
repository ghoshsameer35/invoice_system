<?php 
session_start(); 
$conn = new mysqli('localhost','root','','cms_db');

if (isset($_POST['coupon'])) {

	function validate($data){
       $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}

	$uname = validate($_POST['coupon']);
	if (empty($uname)) {
		header("Location: index.php?error=User Name is required");
	    exit();
	}else{
		$sql = "SELECT * FROM coupon WHERE coupon='$uname'";

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) === 1) {
			$row = mysqli_fetch_assoc($result);
            if ($row['coupon'] === $uname) {
            	$_SESSION['coupon'] = $row['coupon'];
            	//$_SESSION['name'] = $row['name'];
            	//$_SESSION['id'] = $row['id'];

				// echo 'discount';


				
	$output = array();
	$sql = "SELECT * FROM coupon";
	$query=$conn->query($sql);
	while($row=$query->fetch_array()){
		$output[] = $row;
	}

	echo json_encode($output);
            	// header("Location: ../index.php");
		        exit();
            }else{
				header("Location: index.php?error=Incorect User name or password");
		        exit();
			}
		}else{
			header("Location: index.php?error=Incorect User name or password");
	        exit();
		}
	}
	
}else{
	header("Location: index.php");
	exit();
}
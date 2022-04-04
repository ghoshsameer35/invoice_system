<?php
	$conn = new mysqli('localhost','root','','cms_db');
	$coupon = $_POST['coupon'];
	$discount = $_POST['discount'];

	// Database connection
	if($conn->connect_error){
		echo "$conn->connect_error";
		die("Connection Failed : ". $conn->connect_error);
	} else {
		$stmt = $conn->prepare("insert into coupon(coupon,discount) values(?, ?)");
		$stmt->bind_param("si", $coupon, $discount);
		$execval = $stmt->execute();
		echo $execval;
		header("Location: ../index.php");
		// echo "coupon code genereted 💥💥💥";
		$stmt->close();
		$conn->close();
	}
?>
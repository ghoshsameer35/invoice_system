<?php
	require_once'conn.php';
	if(ISSET($_POST['submit'])){
		$coupon=$_POST['coupon'];
		
		mysqli_query($conn, "INSERT INTO `coupon` VALUES('', '$coupon', '')") or die(mysqli_error());
		
		header("location: index.php");
	}
?>
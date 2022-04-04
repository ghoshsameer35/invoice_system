<?php
	require_once'conn.php';
	if(ISSET($_POST['use'])){
		$coupon=$_POST['coupon'];
		
		$query=mysqli_query($conn, "SELECT * FROM `coupon` WHERE `coupon_code`='$coupon'") or die(mysqli_error());
		$row=mysqli_num_rows($query);
		$fetch=mysqli_fetch_array($query);
		if($row > 0){
			if($fetch['status']==''){
				echo "<div class='alert alert-info'>Coupon code activated!</div>";
				mysqli_query($conn, "UPDATE `coupon` SET `status`='used' WHERE `coupon_id`='$fetch[coupon_id]'") or die(mysqli_error());
			}else{
				echo "<div class='alert alert-danger'>Your coupon code has been used!</div>";
			}
		}else{
			echo "<div class='alert alert-danger'>Your coupon code is not available!</div>";
		}
	}
?>
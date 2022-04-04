<?php
include 'conn.php';
$coupon_code=$_POST['coupon'];
$query=mysqli_query($conn,"select * from coupon where coupon='$coupon_code' and status=1");
$row=mysqli_fetch_array($query);
if (mysqli_num_rows($query)>0){
	echo json_encode(array(
				"statusCode"=>200,
				"value"=>$row['value']
			));
}
else{
	echo json_encode(array("statusCode"=>201));
}

?>
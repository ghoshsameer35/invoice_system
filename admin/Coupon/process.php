<?php
$conn = new mysqli('localhost','root','','cms_db');
$coupon=$_POST['coupon'];
$query=mysqli_query($conn,"select * from coupon where coupon='$coupon' and status=1");
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
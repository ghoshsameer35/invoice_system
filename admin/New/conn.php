<?php
	$conn=mysqli_connect("localhost", "root", "", "cms_db");
	
	if(!$conn){
		die("Error: Failed to connect to database!");
	}
?>
<!DOCTYPE html>
<?php require'conn.php'?>
<html lang="en">
	<head>
		<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
	</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<a class="navbar-brand" href="https://sourcecodester.com">Sourcecodester</a>
		</div>
	</nav>
	<div class="col-md-3"></div>
	<div class="col-md-6 well">
		<h3 class="text-primary">PHP - Coupon Code Generator</h3>
		<hr style="border-top:1px dotted #ccc;"/>
		<div class="col-md-4">
			<form method="POST" action="submit.php">
				<div class="form-group">
					<label>Get coupon here</label>
					<input type="text" class="form-control" name="coupon" id="coupon" required="required" readonly="readonly"/>
				</div>
				<center><button type="button" class="btn btn-primary" id="generate">Generate Coupon</button>
				<br /><br />
				<button class="btn btn-success" name="submit" style="display:none;" id="submit">Submit</button></center>
			</form>
		</div>
		<div class="col-md-2"></div>
		<div class="col-md-6">
			<form method="POST" action="">
				<div class="form-group">
					<label>Use coupon code here</label>
					<input type="text" class="form-control" name="coupon"required="required"/>
				</div>
				<center><button class="btn btn-primary" name="use">Use Coupon</button></center>
			</form>
			<br />
			<?php include"use.php"?>
		</div>
	</div>
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#generate').on('click', function(){
			$.get("generate.php", function(data){
				$('#coupon').val(data);
			});
			$(this).attr("disabled", "disabled");
			$('#submit').show();
		});
	});
</script>	
</body>
</html>
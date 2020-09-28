<?php
include 'rss/config/config.php';
page_protect();
$id = $_GET['id'];
if(!empty($id))
{
	$query_renew = mysqli_query($link, "SELECT instance_id, user_id, DATE_FORMAT(date,'%d/%m/%Y'), school_name, domain, DATE_FORMAT(expiry_date,'%d/%m/%Y') FROM `instances` WHERE `user_id` = '$_SESSION[username]' AND `instance_id` = '$id'");
	if(mysqli_num_rows($query_renew) == 0)
	{
		echo "Invalid Request";
	}
	else
	{
		list($instance_id, $user_id, $date, $school_name, $domain, $expiry_date) = mysqli_fetch_array($query_renew);
		$udf1 = $instance_id;
		$udf2 = $school_name;
		$udf3 = $domain;
		$amount = 0;
		$firstname = $_SESSION['user_name'];
		$email = $_SESSION['user_email'];
		$phone = $_SESSION['user_phone'];
		$productinfo = "app_renewal";
		
		echo '
		<!DOCTYPE html>
		<html>
		<head>
			<title>Renew Instance ' . $instance_id . ' | SwiftCampus</title>
			<style>
				#wrapper
				{
					border: 1px solid #000000;
					width: 75%;
					margin-left: 200px;
					padding-left: 30px;
					padding-bottom: 30px;
					padding-top: 30px;
				}
				#wrapper input[type="text"]
				{
					width: 250px;
					border-radius: 5px;
					font-size: 20px;
					color: #a89574;
				}
				#wrapper input:focus
				{
					outline: none;
					box-shadow: 2px 2px 2px 2px #e6e6e6;
				}
				#wrapper input[type="submit"]
				{
					text-align: center;
					text-decoration: none;
					font-size: 22px;
					margin: 4px 2px;
					-webkit-transition-duration: 0.4s; /* Safari */
					transition-duration: 0.4s;
					cursor: pointer;
					background-color: #555555;
					color: #a89574;
					border: 2px solid #555555;
					width: 150px;
				}
				#wrapper input[type="submit"]:hover
				{
					background-color: #FFFFFF;
					color: #000000;
				}
			</style>
		';
		get_head();
		echo '
		</head>
		<body>
		';
		get_header();
		echo '
		<h2 style="text-align: center">Renew Instance ' . $instance_id . '</h2>
		<p style="text-align: center">
			** Please note:<br>If your instance has already expired, it will be renewed from today\'s date.<br>
			If your instance is currently active, it will be renewed from the current expiry date.<br>
			For example, if your license expired on 31-08-2017 and you renew it for 1 year, it will be valid for 1 year from today, i.e. till ' . date('d-m-Y', time() + 31536000) . '.<br>
			If your license expires on 31-12-' . date(Y, time() + 31536000) . ', and you renew for 1 year, it will be valid till ' . date('d-m-Y', strtotime('31 December ' . date(Y, time() + 31536000)) + 31536000) . '
		</p>
		<form action="/payment/process.php" method="post" id="wrapper">
			<input type="hidden" name="productinfo" value="' . $productinfo . '">
			<input type="hidden" name="udf1" value="' . $udf1 . '">
			<input type="text" name="udf1_d" value="' . $udf1 . '" disabled><br><br>
			<input type="hidden" name="udf2" value="' . $udf2 . '">
			<input type="hidden" name="udf3" value="' . $udf3 . '">
			<input type="text" name="udf2_d" disabled value="' . $udf2 . '"><br><br>
			<input type="text" name="udf3_d" disabled value="' . $udf3 . '"><br><br>
			<input type="hidden" name="firstname" value="' . $firstname . '">
			<input type="text" name="firstname_d" value="' . $firstname . '" disabled><br><br>
			<input type="hidden" name="email" value="' . $email . '">
			<input type="text" name="email_d" value="' . $email . '" disabled><br><br>
			<input type="hidden" name="phone" value="' . $phone . '">
			<input type="text" name="phone_d" value="' . $phone . '" disabled><br><br>
			<h3>Select Plan:</h3>
			<select name="amount" required>
				<option value="499">1 Month</option>
				<option value="1399">3 Months</option>
				<option value="2695">6 Months</option>
				<option value="4990">1 Year</option>
			</select><br><br>
			<input type="submit" value="Make Payment"><br><br>
		</form>
		<div style="margin-bottom: 50px;"></div>
		';
		get_footer();
		echo '
		</body>
		</html>
		';
	}
}
else
{
	echo "Invalid Request";
}

?>
<?php
	include '../rss/config/config.php';
	page_protect();
	if(!isset($_POST['status']))
	{
		header("Location: /");
	}
	$txnid = $_POST['txnid'];
	$payu_txnid = $_POST['mihpayid'];
	$amount = $_POST['amount'];
	$status = $_POST['status'];
	
	if(!empty($_POST['udf1']))
	{
		$instance_id = $_POST['udf1'];
	}
	else if(empty($_POST['udf1']) || !isset($_POST['udf1']))
	{
		$instance_id="N/A";
	}
	$user_id = $_SESSION['username'];
	$package = '';
	switch($amount)
	{
		case 499: $package = '1 Month'; break;
		case 1399: $package = '3 Months'; break;
		case 2695: $package = '6 Months'; break;
		case 4990: $package = '12 Months'; break;
		default: $package = 'InvalidTxn'; break;
	}
	$school_name = mysqli_real_escape_string($link, $_POST['udf2']);
	$domain = $_POST['udf3'];
	$date = date("Y-m-d");
	switch($package)
	{
		case '1 Month': $timestamp = time() + 2628000; break;
		case '3 Months': $timestamp = time() + 7884000; break;
		case '6 Months': $timestamp = time() + 15768000; break;
		case '12 Months': $timestamp = time() + 31536000; break;
		default: $timestamp = time(); break;
	}
	$expiry_date = date('Y-m-d', $timestamp);

	mysqli_query($link, "INSERT INTO `orders` VALUES ('$instance_id', '$txnid', '$payu_txnid', '$status', '$amount', '$user_id', '$date', '$school_name', '$domain', '$package', '$expiry_date');"); 

?>

<!DOCTYPE html>
<html>
<head>
	<title>Payment Failure | SwiftCampus</title>
	<?php get_head(); ?>
	<style>
		table
		{
			border-collapse: collapse;
			padding: 10px 10px 10px 10px;
		}
		th, td
		{
			font-size: 22px;
			padding: 10px 10px 10px 10px;
		}
	</style>
</head>
<body>
	<?php get_header(); ?>
	<h2 style="text-align: center">Apologies!</h2><br><br>
	<h3 style="margin-left: 10px;">Your payment was unsuccessful.</h3><br>
	<h4 style="margin-left: 10px;">Your card will not be charged. If the amount was deducted from your account, your bank will refund it soon. (Refund times vary for different banks)</h4><br>
	<h4 style="margin-left: 10px;">Order details are as follows:</h4><br>
	<div align="center">
		<table border="1">
			<tr>
				<th>Instance ID</th>
				<th>Transaction ID</th>
				<th>Payment Gateway Transaction ID</th>
				<th>Status</th>
				<th>Amount</th>
				<th>Package</th>
				<th>School Name</th>
				<th>Domain</th>
			</tr>
			<tr>
				<td><?php echo $instance_id; ?></td>
				<td><?php echo $txnid; ?></td>
				<td><?php echo $payu_txnid; ?></td>
				<td><?php echo $status; ?></td>
				<td><?php echo $amount; ?></td>
				<td><?php echo $package; ?></td>
				<td><?php echo $school_name; ?></td>
				<td><?php echo $domain; ?></td>
			</tr>
		</table>
	</div><br><br>
	<p style="margin-left: 10px;">You can retry the payment by <a href="/purchase.php">placing a new order</a>.</p>
	<div style="margin-bottom: 100px;"></div>
	<?php get_footer(); ?>
</body>
</html>
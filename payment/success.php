<?php
include '../rss/config/config.php';
page_protect();
if (!isset($_POST['status'])) {
     header('Location: /');
}
$status=$_POST["status"];
$firstname=$_POST["firstname"];
$amount=$_POST["amount"];
$txnid=$_POST["txnid"];
$payu_txnid = $_POST['mihpayid'];
$posted_hash=$_POST["hash"];
$key=$_POST["key"];
$productinfo=$_POST["productinfo"];
$email=$_POST["email"];
$salt='JqMMSyVd3U';
$udf1 = $_POST['udf1'];
$udf2 = $_POST['udf2'];
$udf3 = $_POST['udf3'];
$date = date('Y-m-d');
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
$school_name = mysqli_real_escape_string($link, $udf2);
$domain = $udf3;

$retHashSeq = $salt.'|'.$status.'||||||||'.$udf3.'|'.$udf2.'|'.$udf1.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
$hash = hash("sha512", $retHashSeq);

if ($hash !== $posted_hash) {
    echo "Something went wrong with this transaction. Invalid Parameters. If you have been charged, inform us.";
}
else
{
	if(empty($udf1))
	{
		/** New Order **/
		
		/** Check if the script has already been executed once **/
		$query_validate = mysqli_query($link, "SELECT `transaction_id` FROM `orders` WHERE `transaction_id` = '$txnid'");
		if(mysqli_num_rows($query_validate) == 0)
		{
			switch($package)
			{
				case '1 Month': $timestamp = time() + 2628000; break;
				case '3 Months': $timestamp = time() + 7884000; break;
				case '6 Months': $timestamp = time() + 15768000; break;
				case '12 Months': $timestamp = time() + 31536000; break;
				default: $timestamp = time(); break;
			}
			$expiry_date = date('Y-m-d', $timestamp);
			$expiry_date_dmy = date('d-m-Y', $timestamp);
			
			$success_msg = "Your license has been activated and is valid till <strong>" . $expiry_date_dmy . "</strong>.";
			
			mysqli_query($link, "INSERT INTO `instances`(`user_id`, `date`, `school_name`, `domain`, `expiry_date`) VALUES('$user_id', '$date', '$school_name', '$domain', '$expiry_date');");
			$instance_id = mysqli_insert_id($link);
			mysqli_query($link, "INSERT INTO `orders` VALUES('$instance_id', '$txnid', '$payu_txnid', '$status', '$amount', '$user_id', '$date', '$school_name', '$domain', '$package', '$expiry_date');");
			
			$dest = "../instances/" . $domain;
			mkdir($dest);
			$source = "../app_source";
			recurse_copy($source, $dest); /** Copy source code in user directory **/
			
			/** Send Mail **/
			
			$to = $email;
			$subject = "SwiftCampus purchase of instance " . $instance_id . " successful!";
			$message = "
			Dear $firstname, <br><br>
			
			Thank you for purchasing your app license for $school_name. Your app has been activated and is valid till $expiry_date_dmy.<br><br>
			<table>
				<tr>
					<td><strong>Instance ID:</strong></td>
					<td>$instance_id</td>
				</tr>
				<tr>
					<td><strong>Transaction ID:</strong></td>
					<td>$txnid</td>
				</tr>
				<tr>
					<td><strong>Payment Gateway ID:</strong></td>
					<td>$payu_txnid</td>
				</tr>
				<tr>
					<td><strong>Amount:</strong></td>
					<td>$amount</td>
				</tr>
			</table>
			<br><br>
			If you have any questions regarding your app, feel free to contact us at info@" . domain . "<br><br>
			THANK YOU <br>
			Administrator, <br>
			SwiftCampus<br>
			______________________________________________________ <br>
			THIS IS AN AUTOMATED EMAIL. <br> 
			***PLEASE DO NOT REPLY TO THIS EMAIL**** <br>
			";
			include '../rss/mailer/mailer.php';
		}
		else
		{
			header("Location: /");
		}
	}
	else
	{
		/** Renewal **/
		$instance_id = $udf1;
		
		/** Check if the script has already been executed once **/
		$query_validate = mysqli_query($link, "SELECT `transaction_id` FROM `orders` WHERE `transaction_id` = '$txnid'");
		if(mysqli_num_rows($query_validate) == 0)
		{
			$query_calc_newexp = mysqli_query($link, "SELECT `expiry_date` FROM `instances` WHERE `instance_id` = '$instance_id' AND `expiry_date` > now();");
			if(mysqli_num_rows($query_calc_newexp) == 0)
			{
				/** Instance Expired **/
				$timestamp_old = time();
			}
			else
			{
				/** Instance Still Active **/
				list($expiry_date_old) = mysqli_fetch_array($query_calc_newexp);
				$timestamp_old = strtotime($expiry_date_old);
			}
			switch($package)
			{
				case '1 Month': $timestamp_new = $timestamp_old + 2628000; break;
				case '3 Months': $timestamp_new = $timestamp_old + 7884000; break;
				case '6 Months': $timestamp_new = $timestamp_old + 15768000; break;
				case '12 Months': $timestamp_new = $timestamp_old + 31536000; break;
				default: $timestamp_new = time(); break;
			}			
			$expiry_date = date('Y-m-d', $timestamp_new);
			$expiry_date_dmy = date('d-m-Y', $timestamp_new);
		
			/** Insert Order & Update Expiry Date **/
			mysqli_query($link, "UPDATE `instances` SET `expiry_date`='$expiry_date' WHERE `instance_id` = '$instance_id';");
			mysqli_query($link, "INSERT INTO `orders` VALUES('$instance_id', '$txnid', '$payu_txnid', '$status', '$amount', '$user_id', '$date', '$school_name', '$domain', '$package', '$expiry_date');");
			/** Send Mail **/
			$to = $email;
			$subject = "SwiftCampus instance " . $instance_id . " renewal successful!";
			$message = "
			Dear $firstname, <br><br>
			
			Your SwiftCampus app has been renewed successfully and is valid till $expiry_date_dmy.<br><br>
			<table>
				<tr>
					<td><strong>Instance ID:</strong></td>
					<td>$instance_id</td>
				</tr>
				<tr>
					<td><strong>Transaction ID:</strong></td>
					<td>$txnid</td>
				</tr>
				<tr>
					<td><strong>Payment Gateway ID:</strong></td>
					<td>$payu_txnid</td>
				</tr>
				<tr>
					<td><strong>Amount:</strong></td>
					<td>$amount</td>
				</tr>
			</table>
			<br><br>
			If you have any questions regarding your app, feel free to contact us at info@" . domain . "<br><br>
			THANK YOU <br>
			Administrator, <br>
			SwiftCampus<br>
			______________________________________________________ <br>
			THIS IS AN AUTOMATED EMAIL. <br> 
			***PLEASE DO NOT REPLY TO THIS EMAIL**** <br>
			";
			include '../rss/mailer/mailer.php';
		}
		else
		{
			/** Get the expiry date already updated **/
			$query_get_newexp = mysqli_query($link, "SELECT `expiry_date` FROM `instances` WHERE `instance_id` = '$instance_id'");
			list($expiry_date) = mysqli_fetch_row($query_get_newexp);
			$expiry_date_dmy = date('d-m-Y', strtotime($expiry_date));
		}
		$success_msg = "Your license for Instance " . $udf1 . " has been renewed and is valid till <strong>" . $expiry_date_dmy . "</strong>.";
	}
	?>
<!DOCTYPE html>
<html>
<head>
	<title>Transaction Successful | SwiftCampus</title>
	<?php get_head(); ?>
	<style>
		 h1.hdr
		 {
			 text-align: center;
		 }
		 p
		 {
			 font-size: 22px;
			 margin-left: 10px;
		 }
		 h2
		 {
			 margin-left: 10px;
		 }
	</style>
</head>
<body>
	<?php get_header(); ?>
	<h1 class="hdr">Thank You! Your payment was successful!</h1><br>
	
	<p>
		<?php echo $success_msg ?> Please note your transaction ID for future reference: <strong><?php echo $txnid; ?></strong>.<br>
		Payment Gateway Transaction ID: <strong><?php echo $payu_txnid; ?> </strong>.<br>
	</p><br>
	<?php if(empty($udf1)) { ?>
	<h2>What's next?</h2>
	<p>
		Please point your domain <strong><?php echo $domain; ?></strong> to our server's IP address: <b><?php echo server_ip ?></b>.
		As soon as your domain is pointing to our server, your app will be accessible within a few minutes.
	</p><br>
	
	<p>You can manage your orders on the <a href="/auth/orders.php">My Orders</a> page or place another order <a href="purchase.php">here</a>.</p><br>
	
	<h2>How to install?</h2>
	<p>
	* Point your domain to our server <b><?php echo server_ip ?></b>.<br><br>
	* Go to <a href="//<?php echo $domain ?>/install.php" target="_blank"><?php echo $domain ?>/install.php</a>.<br><br>
	* On the installation page, upload your school's logo.<br><br>
	* Create an admin account.<br><br>
	* That's it! Your app is ready for use. Get started by creating accounts for staff members and students using the admin panel in the app.<br><br>
	</p>
	<?php }
	else
	{
		echo "<h2>You can now continue using the app uninterrupted.</h2><br><br>";
	}
	?>
	<h1 class="hdr">Thank you for using SwiftCampus. We hope you enjoy our service.</h1><br><br>
	<?php get_footer(); ?>
</body>
</html>
<?php } ?>
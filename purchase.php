<?php
include 'rss/config/config.php';
page_protect();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Buy Now | SwiftCampus</title>
	<?php get_head(); ?>
	<style>
		#plans
		{
			border-collapse: collapse;
			width: 1000px;
			text-align: center;
			border: 1px solid;
			background-image: url('/rss/img/bg-plans.jpg');
			background-repeat: no-repeat;
			margin-top: 150px;
			margin-bottom: 150px;
		}
		#plans td
		{
			padding: 10px 10px 10px 10px;
			font-size: 80px;
			font-style: italic
		}
		#plans button {
			background-color: #4CAF50;
			border: none;
			color: white;
			padding: 16px 32px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 20px;
			font-style: normal;
			margin: 4px 2px;
			-webkit-transition-duration: 0.4s; /* Safari */
			transition-duration: 0.4s;
			cursor: pointer;
		}
		#plans button:hover
		{
			background-color: #FFFFFF;
			color: #4CAF50;
		}
		tbody
		{
			display: inline;
		}
		tr { border: none; }
		td {
		  border-right: solid 1px #000000; 
		  border-left: solid 1px #000000;
		}
		th
		{
			text-align: center;
			font-size: 1.5em;
		}
		input
		{
			width: 300px;
			height: 50px;
		}
	</style>
</head>
<body>
<?php get_header(); ?>
<div id="purchase_main" align="center">
	<form action="/payment/process.php" method="post" name="payment_form">
		<input type="hidden" name="amount" value="0" id="amount_field">
		<input type="hidden" name="productinfo" value="app_license">
		<input type="hidden" name="firstname" value="<?php echo $_SESSION['user_name'] ?>">
		<input type="hidden" name="email" value="<?php echo $_SESSION['user_email'] ?>">
		<input type="hidden" name="phone" value="<?php echo $_SESSION['user_phone'] ?>">
		<p>Please provide us with the required details to continue...</p>
		<input type="text" name="udf2" required placeholder="School Name..." id="school_name"><br>
		<input type="text" name="udf3" required placeholder="Domain..." id="domain"><br>
		(Domain which you'll be using for our app. Please note that the domain cannot be used for any other purpose)
	</form>
	<table id="plans">
		<tbody>
			<tr>
				<th>Pay for 1 month</th>
			</tr>
			<tr>
				<td>&#x20B9; 499</td>
			</tr>
			<tr>
				<td><div align="center"><button onclick="$('#amount_field').val('499'); payment_form.submit();">Purchase Now</button></div></td>
			</tr>
		</tbody>
		<tbody>
			<tr><th>Pay for 3 months (Save 6.5%)</th><tr>
			<tr><td>&#x20B9; 1399</td></tr>
			<tr><td><div align="center"><button onclick="$('#amount_field').val('1399'); payment_form.submit();">Purchase Now</button></div></td></tr>
		</tbody>
		<tbody>
			<tr><th>Pay for 6 months (Save 10%)</th></tr>
			<tr><td>&#x20B9; 2695</td></tr>
			<tr><td><div align="center"><button onclick="$('#amount_field').val('2695'); payment_form.submit();">Purchase Now</button></div></td></tr>
		</tbody>
		<tbody>
			<tr><th>Annual Plan (Save 16.6%)</th></tr>
			<tr><td>&#x20B9; 4990</td></tr>
			<tr><td><div align="center"><button onclick="$('#amount_field').val('4990'); payment_form.submit();">Purchase Now</button></div></td></tr>
		</tbody>
	</table>
</div>
<?php get_footer(); ?>
</body>
</html>
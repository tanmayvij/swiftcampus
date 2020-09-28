<?php 
include '../rss/config/config.php';
page_protect();
$query_orders = mysqli_query($link, "SELECT instance_id, user_id, transaction_id, status, gateway_txnid, DATE_FORMAT(date,'%d/%m/%Y'), school_name, domain, DATE_FORMAT(expiry_date,'%d/%m/%Y'), amount, package FROM `orders` WHERE `user_id` = '$_SESSION[username]'");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Order History | SwiftCampus</title>
	<?php get_head(); ?>
	<style>
		table
		{
			width: 90%;
		}
		th, td
		{
			padding: 1em 1em 1em 1em;
			font-size: 18px
		}
	</style>
	</head>
<body>
	<?php get_header(); ?>
	<h2 style="text-align: center">Order History</h2><br><br>
	<div align="center">
	<?php
	if(mysqli_num_rows($query_orders) == 0) { echo "You have not placed any order yet. <a href='/purchase.php'>Purchase</a> a package and get started within minutes!"; }
		else {
			?>
		<table border="1">
			<tbody>
				<tr>
					<th>Order ID</th>
					<th>Transaction ID</th>
					<th>Payment Gateway Transaction ID</th>
					<th>Status</th>
					<th>Date of Purchase</th>
					<th>License valid till</th>
					<th>Amount</th>
					<th>Package</th>
					<th>School Name</th>
					<th>Domain</th>
				</tr>
				<?php
				while($row = mysqli_fetch_array($query_orders))
				{
					?>
				<tr>
					<td><?php echo $row['instance_id']; ?></td>
					<td><?php echo $row["transaction_id"]; ?></td>
					<td><?php echo $row["gateway_txnid"]; ?></td>
					<td><?php echo $row["status"]; ?></td>
					<td><?php echo $row["DATE_FORMAT(date,'%d/%m/%Y')"]; ?></td>
					<td><?php echo $row["DATE_FORMAT(expiry_date,'%d/%m/%Y')"]; ?></td>
					<td><?php echo $row['amount']; ?></td>
					<td><?php echo $row['package']; ?></td>
					<td><?php echo $row['school_name']; ?></td>
					<td><?php echo $row['domain']; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php
		}
		?>
	</div>
	<div style="margin-bottom: 200px"></div>
	<?php get_footer(); ?>
</body>
</html>
<?php 
include '../rss/config/config.php';
page_protect();
$query_dashboard_active = mysqli_query($link, "SELECT instance_id, user_id, DATE_FORMAT(date,'%d/%m/%Y'), school_name, domain, DATE_FORMAT(expiry_date,'%d/%m/%Y') FROM `instances` WHERE `user_id` = '$_SESSION[username]' AND expiry_date > now()");
$query_dashboard_expired = mysqli_query($link, "SELECT instance_id, user_id, DATE_FORMAT(date,'%d/%m/%Y'), school_name, domain, DATE_FORMAT(expiry_date,'%d/%m/%Y') FROM `instances` WHERE `user_id` = '$_SESSION[username]' AND expiry_date < now()");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Dashboard | SwiftCampus</title>
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
		#edit_iframe
		{
			width: 100%;
			border: none;
			height: 300px;
		}
		.modal {
			display: none;
			position: fixed;
			z-index: 1;
			padding-top: 100px;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			overflow: auto;
			background-color: rgb(0,0,0);
			background-color: rgba(0,0,0,0.4);
		}
		.modal-content {
			background-color: #fefefe;
			margin: auto;
			padding: 20px;
			border: 1px solid #888;
			width: 80%;
		}
		.close {
			color: #aaaaaa;
			float: right;
			font-size: 40px;
			font-weight: bold;
		}
		.close:hover,
		.close:focus {
			color: #000;
			text-decoration: none;
			cursor: pointer;
		}
	</style>
</head>
<body>
	<?php get_header(); ?>
	<h2 style="text-align: center">My Dashboard</h2>
	<div align="center">
		<table border="0"><tr><td><h3 style="float: left">Active Instances</h3></td></tr></table>
		<?php
		if(mysqli_num_rows($query_dashboard_active) == 0) { echo "You don't have any active instances. Why not get started by <a href='/purchase.php'>purchasing</a> one today?"; }
		else {
			?>
		<table border="1">
			<tbody>
				<tr>
					<th>Instance ID</th>
					<th>Active Since</th>
					<th>Expires On</th>
					<th>School Name</th>
					<th>Go to App</th>
					<th>Install</th>
					<th>Renew</th>
					<th>Actions</th>
				</tr>
				<?php
				while($row = mysqli_fetch_array($query_dashboard_active))
				{
					?>
				<tr>
					<td><?php echo $row['instance_id']; ?></td>
					<td><?php echo $row["DATE_FORMAT(date,'%d/%m/%Y')"]; ?></td>
					<td><?php echo $row["DATE_FORMAT(expiry_date,'%d/%m/%Y')"]; ?></td>
					<td><?php echo $row['school_name']; ?></td>
					<td><a href="http://<?php echo $row['domain']; ?>" target="_blank"><img src="/rss/img/extlink.png"></a></td>
					<td><a href="http://<?php echo $row['domain']; ?>/install.php" target="_blank">Installer Page</a></td>
					<td><a href="/renew.php?id=<?php echo $row['instance_id']; ?>">Renew</a></td>
					<td>
						<button style="cursor: pointer;" class="popup_link">
							<a onclick="document.getElementById('edit_iframe').setAttribute('src', '/auth/edit.php?id=<?php echo $row['instance_id']; ?>')">
								<img src="/rss/img/edit.png">
							</a>
						</button>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php
		}
		?>
		<br><br><!----------->
		<table border="0"><tr><td><h3 style="float: left">Expired Instances</h3></td></tr></table>
		<?php
		if(mysqli_num_rows($query_dashboard_expired) == 0) { echo "You don't have any expired instances."; }
		else {
			?>
		<table border="1">
			<tbody>
				<tr>
					<th>Instance ID</th>
					<th>Activated On</th>
					<th>Expired On</th>
					<th>School Name</th>
					<th>Renew</th>
					<th>Actions</th>
				</tr>
				<?php
				while($row = mysqli_fetch_array($query_dashboard_expired))
				{
					?>
				<tr>
					<td><?php echo $row['instance_id']; ?></td>
					<td><?php echo $row["DATE_FORMAT(date,'%d/%m/%Y')"]; ?></td>
					<td><?php echo $row["DATE_FORMAT(expiry_date,'%d/%m/%Y')"]; ?></td>
					<td><?php echo $row['school_name']; ?></td>
					<td><a href="/renew.php?id=<?php echo $row['instance_id']; ?>">Renew</a></td>
					<td>
						<button style="cursor: pointer;" class="popup_link">
							<a onclick="document.getElementById('edit_iframe').setAttribute('src', '/auth/edit.php?id=<?php echo $row['instance_id']; ?>')">
								<img src="/rss/img/edit.png">
							</a>
						</button>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php
		}
		?>
	</div>
	<div style="margin-bottom: 200px"></div>
	<div id="popup_box" class="modal">
		<div class="modal-content">
			<span class="close">&times;</span>
			<iframe src="/auth/edit.php" id="edit_iframe"></iframe>
		</div>
	</div>
	<?php get_footer(); ?>
	<script>
		var modal = document.getElementById('popup_box');
		var btn = document.getElementsByClassName('popup_link');
		var span = document.getElementsByClassName("close")[0];
		var i;
		for(i=0;i<btn.length;i++)
		{
			btn[i].onclick = function() {
				modal.style.display = "block";
			}
		}
		span.onclick = function() {
			modal.style.display = "none";
			location.reload();
		}
		window.onclick = function(event) {
			if (event.target == modal) {
				modal.style.display = "none";
				location.reload();
			}
		}
	</script>
</body>
</html>
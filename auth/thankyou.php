<?php
include '../rss/config/config.php';
verify_session();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Registration Successful | SwiftCampus</title>
	<?php get_head(); ?>
	<style>
		h3
		{
			margin: 20px 20px 20px 20px;
		}
	</style>
</head>
<body>
<?php get_header(); ?>

<h3 style="text-align: center">Registration Successful</h3>
<h3>Thank you for registering at SwiftCampus. Your account has been successfully created and a confirmation email has been sent to your email ID. Please click on
the activation link sent to you in order to verify your email address and activate your account.</h3><br><br>
<div align="center"><div style="margin-bottom: 200px; font-size: 25px; height: 40px; width: 150px; background: #cccccc;"><a href="login.php">Login</a></div></div>
<?php get_footer(); ?>
</body>
</html>
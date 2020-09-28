<?php
include 'rss/config/config.php';
$msg = "";
if(isset($_POST['submit']) && $_POST['email'] !== 'E-Mail' && $_POST['name'] !== 'Name')
{
	$to = "info@" . domain;
	$subject = "Contact Us Page Query";
	$from_add = $_POST['email'];
	$message = "***** CONTACT US PAGE QUERY *****<br><br>Name: $_POST[name]<br><br>Subject: $_POST[subject]<br><br>Email: $_POST[email]<br><br>Message: $_POST[message]";
	include 'rss/mailer/mailer.php';
	$msg = "Your message has been sent successfully. We'll revert back to you within 24 hours.";
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Contact Us | SwiftCampus</title>
	<?php get_head(); ?>
</head>
<body>
<?php get_header(); ?>
<div class="contact">
	<div class="container">
		<div class="contact-main">
			<div class="contact-top">
				<h3>Demo</h3>
				<p>Test and experience SwiftCampus without paying a single penny!</p>
			</div>
			<a href="//demo.<?=domain?>" target="_blank"><img src="rss/img/extlink.png"> Open Demo</a><br><br>
			<h3>Login Credentials:</h3>
			<br><br>
			<h4><strong>Admin:</strong></h4>
			Username - admin | Password - demo123<br><br>
			<h4><strong>Staff:</strong></h4>
			Username - demo | Password - demo123<br><br>
			<h4><strong>Students:</strong></h4>
			Username - demo | Password - demo123<br><br>
			* You may create new staff and student accounts via the admin panel for testing purposes, but, since this is a publicly accessible demo, they will be deleted by the end of the month to avoid misuse.
		</div>
	</div>
</div>
<?php get_footer();?>
</body>
</html>
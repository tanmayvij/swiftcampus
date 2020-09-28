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
				<h3>Contact</h3>
				<p>Write to us for any queries, complaints or feedback...</p>
			</div>
			<h3><?php echo $msg; ?></h3><br><br>
			<div class="contact-bottom">
			<form action="" method="post">
				<div class="col-md-4 con-name">
					<input type="text" value="<?php if(isset($_SESSION['user_name'])) echo $_SESSION['user_name']; else echo 'Name'; ?>" onfocus="this.value='';" onblur="if (this.value == '') {this.value ='<?php if(isset($_SESSION['user_name'])) echo $_SESSION['user_name']; else echo 'Name'; ?>';}" name="name">
				</div>
				<div class="col-md-4 con-name">
				    <input type="text" value="<?php if(isset($_SESSION['user_email'])) echo $_SESSION['user_email']; else echo 'E-Mail'; ?>" onfocus="this.value='';" onblur="if (this.value == '') {this.value ='<?php if(isset($_SESSION['user_email'])) echo $_SESSION['user_email']; else echo 'E-Mail'; ?>';}" name="email">
				</div>
				<div class="col-md-4 con-name">
				    <input type="text" value="Subject" class="no-mar" onfocus="this.value='';" onblur="if (this.value == '') {this.value ='Subject';}" name="subject">
				</div>
				<textarea onfocus="this.value='';" onblur="if (this.value == '') {this.value ='Message';}" name="message">Message</textarea>
				<input type="submit" value="Send Message" name="submit">
			</form>
			</div>
			<h4>Or mail us directly at <a href="mailto:info@<?php echo domain;?>">info@<?php echo domain;?></a></h4>
		</div>
	</div>
</div>
<?php get_footer();?>
</body>
</html>
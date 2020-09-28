<?php 
include '../rss/config/config.php';
verify_session();
$link = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);



/******************* ACTIVATION BY FORM**************************/
if (isset($_POST['doReset'])=='Reset')
{
	$err = array();
	$msg = array();

	foreach($_POST as $key => $value) {
		$data[$key] = filter($value);
	}
	if(!isEmail($data['user_email'])) {
	$err[] = "ERROR - Please enter a valid email"; 
	}

	$user_email = $data['user_email'];

	$rs_check = mysqli_query($link,"select `id`, `full_name` from `users` where `user_email`='$user_email';") or die (mysqli_error($link)); 
	$num = mysqli_num_rows($rs_check);
	 
		if ( $num <= 0 ) { 
		$err[] = "Error - Sorry no such account exists or registered.";
		}

	list($user_id, $user_name) = mysqli_fetch_array($rs_check);
	if(empty($err)) {

	$new_pwd = GenPwd();
	$pwd_reset = PwdHash($new_pwd);

	$rs_activ = mysqli_query($link, "update users set pwd='$pwd_reset' WHERE 
							 user_email='$user_email'") or die(mysqli_error($link));
							 
	$host  = $_SERVER['HTTP_HOST'];
	$host_upper = strtoupper($host);						 

	$message = 
	"
	Dear $user_name, <br><br>
	As per your request, your SwiftCampus password has been reset.<br>
	<br>
	New Password: <strong>$new_pwd</strong> <br><br>

	Thank You for using SwiftCampus <br> <br>

	Administrator,<br>
	SwiftCampus<br>
	______________________________________________________<br>
	THIS IS AN AUTOMATED EMAIL. <br>
	***PLEASE DO NOT REPLY TO THIS EMAIL****<br>
	";

	$to = $user_email;
	$subject = "SwiftCampus Password Recovery";

	include '../rss/mailer/mailer.php';

	$msg[] = "Your account password has been reset and a new password has been sent to your email address.";						 
							 
 }
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Reset Password | SwiftCampus</title>
	<?php get_head(); ?>
	<style>
		#pwdForm
		{
			border: 1px solid #AAAAAA;
			padding: 20px 20px 20px 20px;
			width: 600px;
			margin-top: 150px;
			margin-bottom: 150px;
			margin-left: 10%;
		}
		#pwdForm input[type='text']
		{
			width: 400px;
			height: 40px;
			border-radius: 4px;
			font-size: 20px;
			color: #a89574;
		}
		#pwdForm input:focus
		{
			outline: none;
			box-shadow: 2px 2px 2px 2px #e6e6e6;
		}
		#pwdForm input[type="submit"]
		{
			padding: 16px 32px;
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
		#pwdForm input[type="submit"]:hover
		{
			background-color: #FFFFFF;
			color: #000000;
		}
	</style>
</head>
<body>
<?php get_header(); ?>
<script language="JavaScript" type="text/javascript" src="js/jquery.validate.js"></script>
  <script>
  $(document).ready(function(){
    $("#actForm").validate();
  });
  </script>
      <p> 
        <?php
	  /******************** ERROR MESSAGES*************************************************
	  This code is to show error messages 
	  **************************************************************************/
	if(!empty($err))  {
	   echo "<div class=\"msg\">";
	  foreach ($err as $e) {
	    echo "* $e <br>";
	    }
	  echo "</div>";	
	   }
	   if(!empty($msg))  {
	    echo "<div class=\"msg\">" . $msg[0] . "</div>";

	   }
	  /******************************* END ********************************/	  
	  ?>
      </p><div align="center">
	  <form action="" method="post" id="pwdForm">
	  <h3>Forgot Password?<br>Enter your email and we'll send you a new one!</h3><br>
		<input name="user_email" type="text" class="required email" id="txtboxn" size="25"><br><br>
        <input name="doReset" type="submit" id="doLogin3" value="Reset">
       </form>      
	   </div>
	<?php get_footer(); ?>
</body>
</html>
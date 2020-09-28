<?php 
include '../rss/config/config.php';
verify_session();
$link = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
foreach($_GET as $key => $value) {
	$get[$key] = filter($value);
}

/******** EMAIL ACTIVATION LINK**********************/
if(isset($get['user']) && !empty($get['activ_code']) && !empty($get['user']) && is_numeric($get['activ_code']) ) {

$err = array();
$msg = array();

$user = mysqli_real_escape_string($link,$get['user']);
$activ = mysqli_real_escape_string($link,$get['activ_code']);

//check if activ code and user is valid
$rs_check = mysqli_query($link,"select id from users where md5_id='$user' and activation_code='$activ'") or die (mysqli_error($link)); 
$num = mysqli_num_rows($rs_check);
  // Match row found with more than 1 results  - the user is authenticated. 
    if ( $num <= 0 ) { 
	$err[] = "Sorry no such account exists or activation code invalid.";
	//header("Location: activate.php?msg=$msg");
	//exit();
	}

if(empty($err)) {
// set the approved field to 1 to activate the account
$rs_activ = mysqli_query($link,"update users set approved='1' WHERE 
						 md5_id='$user' AND activation_code = '$activ' ") or die(mysqli_error($link));
$msg[] = "Thank you. Your account has been activated.";
//header("Location: activate.php?done=1&msg=$msg");						 
//exit();
 }
}

/******************* ACTIVATION BY FORM**************************/
if (isset($_POST['doActivate'])=='Activate')
{
$err = array();
$msg = array();

$user_email = mysqli_real_escape_string($link,$_POST['user_email']);
$activ = mysqli_real_escape_string($link,$_POST['activ_code']);
//check if activ code and user is valid as precaution
$rs_check = mysqli_query($link,"select id from users where user_email='$user_email' and activation_code='$activ'") or die (mysqli_error($link)); 
$num = mysqli_num_rows($rs_check);
  // Match row found with more than 1 results  - the user is authenticated. 
    if ( $num <= 0 ) { 
	$err[] = "Sorry no such account exists or activation code invalid.";
	//header("Location: activate.php?msg=$msg");
	//exit();
	}
//set approved field to 1 to activate the user
if(empty($err)) {
	$rs_activ = mysqli_query($link,"update users set approved='1' WHERE 
						 user_email='$user_email' AND activation_code = '$activ' ") or die(mysqli_error($link));
	$msg[] = "Thank you. Your account has been activated.";
 }
//header("Location: activate.php?msg=$msg");						 
//exit();
}

	

?>
<!DOCTYPE html>
<html>
<head>
<title>User Account Activation | SwiftCampus</title>
  <script>
  $(document).ready(function(){
    $("#actForm").validate();
  });
  </script>
  <?php get_head(); ?>
  <style>
		h3
		{
			margin: 20px 20px 20px 20px;
			text-align: center
		}
		#actForm
		{
			border: 1px solid #AAAAAA;
			padding: 20px 20px 20px 20px;
			width: 600px;
			margin-top: 150px;
			margin-bottom: 150px;
		}
		#actForm input[type='text'], #actForm input[type='password']
		{
			width: 400px;
			height: 40px;
			border-radius: 4px;
			font-size: 20px;
			color: #a89574;
		}
		#actForm input:focus
		{
			outline: none;
			box-shadow: 2px 2px 2px 2px #e6e6e6;
		}
		#actForm input[type="submit"]
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
		#actForm input[type="submit"]:hover
		{
			background-color: #FFFFFF;
			color: #000000;
		}
	</style>
</head>

<body>
<?php get_header(); ?>
<h3 class="titlehdr">Account Activation</h3>

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
      </p>
      <p style="font-size: 20px">Please enter your email and activation code sent to you to your email 
        address to activate your account. Once your account is activated you can 
        <a href="login.php">login here</a>.</p>
	<div align="center">
	<form method="post" action="" id="actForm">
		<input name="user_email" type="text" class="required email" id="txtboxn" size="25" placeholder="Email Address..."><br><br>
		<input name="activ_code" type="password" class="required" id="txtboxn" size="25" placeholder="Activation Code..."><br><br>
		<input type="submit" name="doActivate" value="Activate"><br><br>
	</form>
	</div>
		<?php get_footer(); ?>
</body>
</html>
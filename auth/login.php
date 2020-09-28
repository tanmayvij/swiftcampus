<?php 
include '../rss/config/config.php';
verify_session();
$link = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
$err = array();

foreach($_GET as $key => $value) {
	$get[$key] = filter($value); //get variables are filtered.
}

if (isset($_POST['doLogin'])=='Login')
{

foreach($_POST as $key => $value) {
	$data[$key] = filter($value); // post variables are filtered
}


$user_email = $data['usr_email'];
$pass = $data['pwd'];


if (strpos($user_email,'@') === false) {
    $user_cond = "user_name='$user_email'";
} else {
      $user_cond = "user_email='$user_email'";
    
}

	
$result = mysqli_query($link,"SELECT `id`,`pwd`,`full_name`, `user_name`,`approved`,`user_level`,`user_email`,`tel` FROM users WHERE 
           $user_cond
			AND `banned` = '0'
			") or die (mysqli_error($link)); 
$num = mysqli_num_rows($result);

  // Match row found with more than 1 results  - the user is authenticated. 
    if ( $num > 0 ) { 
	
	list($id,$pwd,$full_name,$username,$approved,$user_level,$user_email,$tel) = mysqli_fetch_row($result);
	
	if(!$approved) {
	//$msg = urlencode("Account not activated. Please check your email for activation code");
	$err[] = "Account not activated. Please check your email for activation code";
	
	//header("Location: login.php?msg=$msg");
	 //exit();
	 }
	 
		//check against salt
	if ($pwd === PwdHash($pass,substr($pwd,0,9))) { 
	if(empty($err)){			

     // this sets session and logs user in  
       session_start();
	   session_regenerate_id (true); //prevent against session fixation attacks.

	   // this sets variables in the session 
		$_SESSION['user_id']= $id;  
		$_SESSION['user_name'] = $full_name;
		$_SESSION['user_level'] = $user_level;
		$_SESSION['user_email'] = $user_email;
		$_SESSION['user_phone'] = $tel;
		$_SESSION['username'] = $username;
		$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
		
		//update the timestamp and key for cookie
		$stamp = time();
		$ckey = GenKey();
		mysqli_query($link,"update users set `ctime`='$stamp', `ckey` = '$ckey' where id='$id'") or die(mysqli_error($link));
		
		//set a cookie 
		
	   if(isset($_POST['remember'])){
				  setcookie("user_id", $_SESSION['user_id'], time()+60*60*24*COOKIE_TIME_OUT, "/");
				  setcookie("user_key", sha1($ckey), time()+60*60*24*COOKIE_TIME_OUT, "/");
				  setcookie("user_name",$_SESSION['user_name'], time()+60*60*24*COOKIE_TIME_OUT, "/");
				   }
		  header("Location: /auth/dashboard.php");
		 }
		}
		else
		{
		//$msg = urlencode("Invalid Login. Please try again with correct user email and password. ");
		$err[] = "Invalid Login. Please try again with correct user email and password.";
		//header("Location: login.php?msg=$msg");
		}
	} else {
		$err[] = "Error - Invalid login. No such user exists";
	  }		
}
					 
					 

?>
<!DOCTYPE html>
<html>
<head>
	<title>Login | SwiftCampus</title>
	<style>
		#logForm
		{
			border: 1px solid #AAAAAA;
			padding: 20px 20px 20px 20px;
			width: 600px;
			margin-top: 150px;
			margin-bottom: 150px;
		}
		#logForm input[type='text'], #logForm input[type='password']
		{
			width: 400px;
			height: 40px;
			border-radius: 4px;
			font-size: 20px;
			color: #a89574;
		}
		#logForm input:focus
		{
			outline: none;
			box-shadow: 2px 2px 2px 2px #e6e6e6;
		}
		#logForm input[type="submit"]
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
		#logForm input[type="submit"]:hover
		{
			background-color: #FFFFFF;
			color: #000000;
		}
		#logForm input[type="checkbox"]
		{
			height: 20px;
			width: 20px
		}
		#logForm input[type="checkbox"]:focus
		{
			box-shadow: none;
		}
	</style>
	<?php get_head(); ?>
</head>
<body>
<?php get_header(); ?>
  <script>
  $(document).ready(function(){
    $("#logForm").validate();
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
	    echo "$e <br>";
	    }
	  echo "</div>";	
	   }
	  /******************************* END ********************************/	  
	  ?></p>
      <div align="center">
		  <form action="login.php" method="post" name="logForm" id="logForm">
			<input name="usr_email" type="text" class="required" id="txtbox" placeholder="Username/Email..."><br><br>
			<input name="pwd" type="password" class="required password" id="txtbox" placeholder="Password..."><br><br>
			<div align="center">
					<input name="remember" type="checkbox" id="remember" value="1">
					Keep me logged in
			</div><br>
			<input name="doLogin" type="submit" id="doLogin3" value="Login"><br><br>
			<a href="/auth/register.php">Create account</a> | <a href="/auth/forgot.php">Forgot Password</a> | <a href="/auth/activate.php">Activate your Account</a>
		</form>
	</div>
		<?php get_footer(); ?>
</body>
</html>
<?php 

include '../rss/config/config.php';
page_protect();

$err = array();
$msg = array();

if(isset($_POST['doUpdate']) == 'Update')  
{


$rs_pwd = mysqli_query($link,"select pwd from users where id='$_SESSION[user_id]'");
list($old) = mysqli_fetch_row($rs_pwd);
$old_salt = substr($old,0,9);

//check for old password in md5 format
	if($old === PwdHash($_POST['pwd_old'],$old_salt))
	{
		if(!checkPwd($_POST['pwd_new'], $_POST['pwd_new']))
		{
			$err[] = "New Password does not satisfy the requirements. Please enter at least 5 characters.";
		}
		else
		{
			$newsha1 = PwdHash($_POST['pwd_new']);
			mysqli_query($link,"update users set pwd='$newsha1' where id='$_SESSION[user_id]'");
			$msg[] = "Your new password is updated.";
		}
	}
	else
	{
	 $err[] = "Your old password is invalid.";
	}

}

if(isset($_POST['doSave']) == 'Save')  
{
// Filter POST data for harmful code (sanitize)
foreach($_POST as $key => $value) {
	$data[$key] = filter($value);
}


mysqli_query($link,"UPDATE users SET
			`full_name` = '$data[name]',
			`address` = '$data[address]',
			`tel` = '$data[tel]',
			`fax` = '$data[fax]',
			`country` = '$data[country]',
			`website` = '$data[web]'
			 WHERE id='$_SESSION[user_id]'
			") or die(mysqli_error($link));
$msg[] = "Profile sucessfully saved.";
 }
 
$rs_settings = mysqli_query($link,"select * from users where id='$_SESSION[user_id]'"); 
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $_SESSION['user_name'];?> | SwiftCampus</title>
	<?php get_head(); ?>
  <script>
  $(document).ready(function(){
    $("#myform").validate();
	 $("#pform").validate();
  });
  </script>
  <style>
	#wrapper
	{
		border: 1px solid #000000;
		width: 75%;
		margin-left: 200px;
		padding-left: 30px;
		padding-bottom: 30px;
		padding-top: 30px;
	}
	#wrapper input[type='text'], #wrapper input[type='password']
	{
		width: 250px;
		border-radius: 5px;
		font-size: 20px;
		color: #a89574;
	}
	#wrapper input:focus
	{
		outline: none;
		box-shadow: 2px 2px 2px 2px #e6e6e6;
	}
	#wrapper input[type="submit"]
	{
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
	#wrapper input[type="submit"]:hover
	{
		background-color: #FFFFFF;
		color: #000000;
	}
	.msg
	{
		margin-left: 30px;
		font-size: 25px;
	}
  </style>
  </head>
<body>
<?php get_header(); ?>
      <p> 
        <?php	
	if(!empty($err))  {
	   echo "<div class=\"msg\">";
	  foreach ($err as $e) {
	    echo "* Error - $e <br>";
	    }
	  echo "</div>";	
	   }
	   if(!empty($msg))  {
	    echo "<div class=\"msg\">" . $msg[0] . "</div>";

	   }
	  ?>
      </p>
	  <?php while ($row_settings = mysqli_fetch_array($rs_settings)) {?>
	<div id="wrapper">
		  <form action="" method="post" name="myform" id="myform">
		  <h3 style="text-align: center">Profile Settings</h3>
			User Name<br><input name="user_name" type="text" id="web2" value="<? echo $row_settings['user_name']; ?>" disabled><br><br>
			Email<br><input name="user_email" type="text" id="web3"  value="<? echo $row_settings['user_email']; ?>" disabled><br><br>
			Full Name<br><input name="name" type="text" id="name"  class="required" value="<? echo $row_settings['full_name']; ?>"><br><br>
			Address <span class="example">(full address with ZIP)</span><br>
			<textarea name="address" cols="40" rows="4" class="required" id="address"><? echo $row_settings['address']; ?></textarea><br><br>
			Country<br><input name="country" type="text" id="country" value="<? echo $row_settings['country']; ?>" ><br><br>
			Phone<br><input name="tel" type="text" id="tel" class="required" value="<? echo $row_settings['tel']; ?>"><br><br>
			Fax<br><input name="fax" type="text" id="fax" value="<? echo $row_settings['fax']; ?>"><br><br>
			School Website<br><input name="web" type="text" id="web" class="optional defaultInvalid url" value="<? echo $row_settings['website']; ?>"> 
			<span class="example">Please type in the format: http://www.example.com or www.example.com</span><br><br>
			<input name="doSave" type="submit" id="doSave" value="Save"><br><br>
		</form>
		  <?php } ?>
		  <h3>Change Password</h3>
		  <form name="pform" id="pform" method="post" action="">
			Old Password<br><input name="pwd_old" type="password" class="required password"  id="pwd_old"><br><br>
			New Password<br><input name="pwd_new" type="password" id="pwd_new" class="required password"><br><br>
			<input name="doUpdate" type="submit" id="doUpdate" value="Update">
		  </form>
	</div><br><br>
<?php get_footer(); ?>
</body>
</html>
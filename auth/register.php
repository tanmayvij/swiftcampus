<?php 

include '../rss/config/config.php';
verify_session();
$link = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
$err = array();
			
if(isset($_POST['doRegister']) == 'Register') 
{ 
/******************* Filtering/Sanitizing Input *****************************
This code filters harmful script code and escapes data of all POST data
from the user submitted form.
*****************************************************************/
foreach($_POST as $key => $value) {
	$data[$key] = filter($value);
}

/********************* RECAPTCHA CHECK *******************************
This code checks and validates recaptcha
****************************************************************/
if(isset($_POST['g-recaptcha-response'])){
          $captcha=$_POST['g-recaptcha-response'];
        }
        if(!$captcha){
          $err[] = "ERROR - Invalid Captcha.";
          exit;
        }
        $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$privatekey&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
		 if($response.success==false)
        {
          header("Location: /register.php");
        }
/************************ SERVER SIDE VALIDATION **************************************/
/********** This validation is useful if javascript is disabled in the browswer ***/

if(empty($data['full_name']) || strlen($data['full_name']) < 4)
{
$err[] = "ERROR - Invalid name. Please enter atleast 3 or more characters for your name";
//header("Location: register.php?msg=$err");
//exit();
}

// Validate User Name
if (!isUserID($data['user_name'])) {
$err[] = "ERROR - Invalid user name. It can contain alphabet, number and underscore.";
//header("Location: register.php?msg=$err");
//exit();
}

// Validate Email
if(!isEmail($data['usr_email'])) {
$err[] = "ERROR - Invalid email address.";
//header("Location: register.php?msg=$err");
//exit();
}
// Check User Passwords
if (!checkPwd($data['pwd'],$data['pwd2'])) {
$err[] = "ERROR - Invalid Password or mismatch. Enter 5 chars or more";
//header("Location: register.php?msg=$err");
//exit();
}
	  
$user_ip = $_SERVER['REMOTE_ADDR'];

// stores sha1 of password
$sha1pass = PwdHash($data['pwd']);

// Automatically collects the hostname or domain  like example.com) 
$host  = $_SERVER['HTTP_HOST'];
$host_upper = strtoupper($host);
$path   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

// Generates activation code simple 4 digit number
$activ_code = rand(1000,9999);

$usr_email = $data['usr_email'];
$user_name = $data['user_name'];

/************ USER EMAIL CHECK ************************************
This code does a second check on the server side if the email already exists. It 
queries the database and if it has any existing email it throws user email already exists
*******************************************************************/

$rs_duplicate = mysqli_query($link,"select count(*) as total from users where user_email='$usr_email' OR user_name='$user_name'") or die(mysqli_error($link));
list($total) = mysqli_fetch_row($rs_duplicate);

if ($total > 0)
{
$err[] = "ERROR - The username/email already exists. Please try again with different username and email.";
//header("Location: register.php?msg=$err");
//exit();
}
/***************************************************************************/

if(empty($err)) {

$sql_insert = "INSERT into `users`
  			(`full_name`,`user_email`,`pwd`,`address`,`tel`,`fax`,`website`,`date`,`users_ip`,`activation_code`,`country`,`user_name`
			)
		    VALUES
		    ('$data[full_name]','$usr_email','$sha1pass','$data[address]','$data[tel]','$data[fax]','$data[web]'
			,now(),'$user_ip','$activ_code','$data[country]','$user_name'
			)
			";
			
mysqli_query($link,$sql_insert) or die("Insertion Failed:" . mysqli_error($link));
$user_id = mysqli_insert_id($link);  
$md5_id = md5($user_id);
mysqli_query($link,"update users set md5_id='$md5_id' where id='$user_id'");

$a_link = "
Please visit <a href='//www." . domain . "/auth/activate.php'>http://www." . domain . "/auth/activate.php</a> and enter your activation code to verify your email account.
"; 

$message = 
"Hello $data[full_name]!<br><br>
Thank you for registering with us. Your account has been successfully created. Your login details are as follows:<br><br>

User ID: $user_name <br>
Email: $usr_email <br> 
Password: $data[pwd] <br><br>

Your Activation code is: <strong>$activ_code</strong> <br><br>

$a_link
<br><br>
You can now get started by <a href='//www." . domain . "/purchase.php'>purchasing</a> a license for your school. We hope you enjoy using SwiftCampus!
<br><br>
THANK YOU <br>
Administrator, <br>
SwiftCampus<br>
______________________________________________________ <br>
THIS IS AN AUTOMATED EMAIL. <br>
***PLEASE DO NOT REPLY TO THIS EMAIL**** <br>
";
$to = $usr_email;
$subject = "SwiftCampus Account created successfully";

include '../rss/mailer/mailer.php';

  header("Location: thankyou.php");  
  exit();
	 
	 } 
 }					 

?>

<!DOCTYPE html>
<html>
<head>
	<title>Create an account | SwiftCampus</title>
	<style>
		#regForm
		{
			border: 1px solid #AAAAAA;
			padding: 20px 20px 20px 20px;
			width: 600px;
			margin-left: 10%;
		}
		#regForm input[type='text'], #regForm input[type='password']
		{
			width: 400px;
			height: 40px;
			border-radius: 4px;
			font-size: 20px;
			color: #a89574;
		}
		#regForm textarea
		{
			border-radius: 4px;
			font-size: 20px;
			color: #a89574;
		}
		#regForm input:focus
		{
			outline: none;
			box-shadow: 2px 2px 2px 2px #e6e6e6;
		}
		#regForm input[type="submit"]
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
		#regForm input[type="submit"]:hover
		{
			background-color: #FFFFFF;
			color: #000000;
		}
		h3
		{
			text-align: center;
		}
	</style>
<script src='https://www.google.com/recaptcha/api.js'></script>
  <script>
  $(document).ready(function(){
    $.validator.addMethod("username", function(value, element) {
        return this.optional(element) || /^[a-z0-9\_]+$/i.test(value);
    }, "Username must contain only letters, numbers, or underscore.");

    $("#regForm").validate();
  });
  </script>
  <?php get_head(); ?>
</head>
<body>
<?php get_header(); ?>

	 <?php	
	 if(!empty($err))  {
	   echo "<div class=\"msg\">";
	  foreach ($err as $e) {
	    echo "* $e <br>";
	    }
	  echo "</div>";	
	   }
	 ?> 
	 
	  <br>
      <form action="register.php" method="post" name="regForm" id="regForm">
	  <h3>Create a new account</h3>
		<input name="full_name" type="text" id="full_name" size="40" class="required" placeholder="Your Name..."><br><br>
		<textarea name="address" cols="40" rows="4" id="address" class="required" onfocus="if (this.value == 'Address...') {this.value=''}" onblur="if (this.value == '') {this.value ='Address...';}">Address...</textarea><br><br>
		<select name="country" class="required" id="select8">
			<option value="Country..." disabled selected>Country...</option>
			<option value="Afghanistan">Afghanistan</option>
			<option value="Albania">Albania</option>
			<option value="Algeria">Algeria</option>
			<option value="Andorra">Andorra</option>
			<option value="Anguila">Anguila</option>
			<option value="Antarctica">Antarctica</option>
			<option value="Antigua and Barbuda">Antigua and Barbuda</option>
			<option value="Argentina">Argentina</option>
			<option value="Armenia ">Armenia </option>
			<option value="Aruba">Aruba</option>
			<option value="Australia">Australia</option>
			<option value="Austria">Austria</option>
			<option value="Azerbaidjan">Azerbaidjan</option>
			<option value="Bahamas">Bahamas</option>
			<option value="Bahrain">Bahrain</option>
			<option value="Bangladesh">Bangladesh</option>
			<option value="Barbados">Barbados</option>
			<option value="Belarus">Belarus</option>
			<option value="Belgium">Belgium</option>
			<option value="Belize">Belize</option>
			<option value="Bermuda">Bermuda</option>
			<option value="Bhutan">Bhutan</option>
			<option value="Bolivia">Bolivia</option>
			<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
			<option value="Brazil">Brazil</option>
			<option value="Brunei">Brunei</option>
			<option value="Bulgaria">Bulgaria</option>
			<option value="Cambodia">Cambodia</option>
			<option value="Canada">Canada</option>
			<option value="Cape Verde">Cape Verde</option>
			<option value="Cayman Islands">Cayman Islands</option>
			<option value="Chile">Chile</option>
			<option value="China">China</option>
			<option value="Christmans Islands">Christmans Islands</option>
			<option value="Cocos Island">Cocos Island</option>
			<option value="Colombia">Colombia</option>
			<option value="Cook Islands">Cook Islands</option>
			<option value="Costa Rica">Costa Rica</option>
			<option value="Croatia">Croatia</option>
			<option value="Cuba">Cuba</option>
			<option value="Cyprus">Cyprus</option>
			<option value="Czech Republic">Czech Republic</option>
			<option value="Denmark">Denmark</option>
			<option value="Dominica">Dominica</option>
			<option value="Dominican Republic">Dominican Republic</option>
			<option value="Ecuador">Ecuador</option>
			<option value="Egypt">Egypt</option>
			<option value="El Salvador">El Salvador</option>
			<option value="Estonia">Estonia</option>
			<option value="Falkland Islands">Falkland Islands</option>
			<option value="Faroe Islands">Faroe Islands</option>
			<option value="Fiji">Fiji</option>
			<option value="Finland">Finland</option>
			<option value="France">France</option>
			<option value="French Guyana">French Guyana</option>
			<option value="French Polynesia">French Polynesia</option>
			<option value="Gabon">Gabon</option>
			<option value="Germany">Germany</option>
			<option value="Gibraltar">Gibraltar</option>
			<option value="Georgia">Georgia</option>
			<option value="Greece">Greece</option>
			<option value="Greenland">Greenland</option>
			<option value="Grenada">Grenada</option>
			<option value="Guadeloupe">Guadeloupe</option>
			<option value="Guatemala">Guatemala</option>
			<option value="Guinea-Bissau">Guinea-Bissau</option>
			<option value="Guinea">Guinea</option>
			<option value="Haiti">Haiti</option>
			<option value="Honduras">Honduras</option>
			<option value="Hong Kong">Hong Kong</option>
			<option value="Hungary">Hungary</option>
			<option value="Iceland">Iceland</option>
			<option value="India">India</option>
			<option value="Indonesia">Indonesia</option>
			<option value="Ireland">Ireland</option>
			<option value="Israel">Israel</option>
			<option value="Italy">Italy</option>
			<option value="Jamaica">Jamaica</option>
			<option value="Japan">Japan</option>
			<option value="Jordan">Jordan</option>
			<option value="Kazakhstan">Kazakhstan</option>
			<option value="Kenya">Kenya</option>
			<option value="Kiribati ">Kiribati </option>
			<option value="Kuwait">Kuwait</option>
			<option value="Kyrgyzstan">Kyrgyzstan</option>
			<option value="Lao People's Democratic Republic">Lao People's 
			Democratic Republic</option>
			<option value="Latvia">Latvia</option>
			<option value="Lebanon">Lebanon</option>
			<option value="Liechtenstein">Liechtenstein</option>
			<option value="Lithuania">Lithuania</option>
			<option value="Luxembourg">Luxembourg</option>
			<option value="Macedonia">Macedonia</option>
			<option value="Madagascar">Madagascar</option>
			<option value="Malawi">Malawi</option>
			<option value="Malaysia ">Malaysia </option>
			<option value="Maldives">Maldives</option>
			<option value="Mali">Mali</option>
			<option value="Malta">Malta</option>
			<option value="Marocco">Marocco</option>
			<option value="Marshall Islands">Marshall Islands</option>
			<option value="Mauritania">Mauritania</option>
			<option value="Mauritius">Mauritius</option>
			<option value="Mexico">Mexico</option>
			<option value="Micronesia">Micronesia</option>
			<option value="Moldavia">Moldavia</option>
			<option value="Monaco">Monaco</option>
			<option value="Mongolia">Mongolia</option>
			<option value="Myanmar">Myanmar</option>
			<option value="Nauru">Nauru</option>
			<option value="Nepal">Nepal</option>
			<option value="Netherlands Antilles">Netherlands Antilles</option>
			<option value="Netherlands">Netherlands</option>
			<option value="New Zealand">New Zealand</option>
			<option value="Niue">Niue</option>
			<option value="North Korea">North Korea</option>
			<option value="Norway">Norway</option>
			<option value="Oman">Oman</option>
			<option value="Pakistan">Pakistan</option>
			<option value="Palau">Palau</option>
			<option value="Panama">Panama</option>
			<option value="Papua New Guinea">Papua New Guinea</option>
			<option value="Paraguay">Paraguay</option>
			<option value="Peru ">Peru </option>
			<option value="Philippines">Philippines</option>
			<option value="Poland">Poland</option>
			<option value="Portugal ">Portugal </option>
			<option value="Puerto Rico">Puerto Rico</option>
			<option value="Qatar">Qatar</option>
			<option value="Republic of Korea Reunion">Republic of Korea Reunion</option>
			<option value="Romania">Romania</option>
			<option value="Russia">Russia</option>
			<option value="Saint Helena">Saint Helena</option>
			<option value="Saint kitts and nevis">Saint kitts and nevis</option>
			<option value="Saint Lucia">Saint Lucia</option>
			<option value="Samoa">Samoa</option>
			<option value="San Marino">San Marino</option>
			<option value="Saudi Arabia">Saudi Arabia</option>
			<option value="Seychelles">Seychelles</option>
			<option value="Singapore">Singapore</option>
			<option value="Slovakia">Slovakia</option>
			<option value="Slovenia">Slovenia</option>
			<option value="Solomon Islands">Solomon Islands</option>
			<option value="South Africa">South Africa</option>
			<option value="Spain">Spain</option>
			<option value="Sri Lanka">Sri Lanka</option>
			<option value="St.Pierre and Miquelon">St.Pierre and Miquelon</option>
			<option value="St.Vincent and the Grenadines">St.Vincent and the 
			Grenadines</option>
			<option value="Sweden">Sweden</option>
			<option value="Switzerland">Switzerland</option>
			<option value="Syria">Syria</option>
			<option value="Taiwan ">Taiwan </option>
			<option value="Tajikistan">Tajikistan</option>
			<option value="Thailand">Thailand</option>
			<option value="Trinidad and Tobago">Trinidad and Tobago</option>
			<option value="Turkey">Turkey</option>
			<option value="Turkmenistan">Turkmenistan</option>
			<option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
			<option value="Ukraine">Ukraine</option>
			<option value="UAE">UAE</option>
			<option value="UK">UK</option>
			<option value="USA">USA</option>
			<option value="Uruguay">Uruguay</option>
			<option value="Uzbekistan">Uzbekistan</option>
			<option value="Vanuatu">Vanuatu</option>
			<option value="Vatican City">Vatican City</option>
			<option value="Vietnam">Vietnam</option>
			<option value="Virgin Islands (GB)">Virgin Islands (GB)</option>
			<option value="Virgin Islands (U.S.) ">Virgin Islands (U.S.) </option>
			<option value="Wallis and Futuna Islands">Wallis and Futuna Islands</option>
			<option value="Yemen">Yemen</option>
			<option value="Yugoslavia">Yugoslavia</option>
		</select>
		<br><br>
		<input name="tel" type="text" id="tel" class="required" placeholder="Mobile..."><br><br>
		<input name="fax" type="text" id="fax" placeholder="Fax..."><br><br>
		<input name="web" type="text" id="web" class="optional defaultInvalid url" placeholder="Website..."><br><br>
		<input name="user_name" type="text" id="user_name" class="required username" minlength="5" placeholder="Username...">
		<input name="btnAvailable" type="button" id="btnAvailable" onclick='$("#checkid").html("Please wait..."); $.get("checkuser.php",{ cmd: "check", user: $("#user_name").val() } ,function(data){  $("#checkid").html(data); });' value="Check Availability"> 
		<span style="color:red; font: bold 12px verdana; " id="checkid" ></span><br><br>
		<input name="usr_email" type="text" id="usr_email3" class="required email" placeholder="Email..."><br><br>
		<input name="pwd" type="password" class="required password" minlength="5" id="pwd" placeholder="Password...">
		<span class="example">* Min. 5 characters</span><br><br>
		<input name="pwd2"  id="pwd2" class="required password" type="password" minlength="5" equalto="#pwd" placeholder="Confirm Password..."><br><br>
		<strong>ReCaptcha Verification </strong>
		<div class="g-recaptcha" data-sitekey="===<?php echo $publickey;?>==="></div><br><br>
		<input name="doRegister" type="submit" id="doRegister" value="Register">
      </form>
<br><br>
	  <?php get_footer() ?>
</body>
</html>

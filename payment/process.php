<?php
include '../rss/config/config.php';

$MERCHANT_KEY = "4Cu49gwt";

$SALT = "JqMMSyVd3U";

$PAYU_BASE_URL = "https://sandboxsecure.payu.in";

$action = '';

$surl = "http://www." . domain . "/payment/success.php";
$furl = "//www." . domain . "/payment/failure.php";
$curl = "//www." . domain . "/payment/failure.php";

$posted = array();
if(!empty($_POST)) {
  foreach($_POST as $key => $value)
  {    
    $posted[$key] = $value; 
  }
}

$formError = 0;

if(empty($posted['txnid'])) {
  // Generate random transaction id
  $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
} else {
  $txnid = $posted['txnid'];
}
$hash = '';
// Hash Sequence
$hashSequence = "amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
	if(
		  empty($posted['amount'])
          || empty($posted['firstname'])
          || empty($posted['email'])
          || empty($posted['phone'])
          || empty($posted['productinfo'])
		  || empty($posted['udf2'])
		|| empty($posted['udf3'])
	)
	{
		$formError = 1;
	}
	
	else
	{
		/** Check whether new domain already exists **/
		$query_verifydom = mysqli_query($link, "SELECT `instance_id` FROM `instances` WHERE `domain` = '$_POST[udf3]' AND `instance_id` != '$_POST[udf1]'");
		if(mysqli_num_rows($query_verifydom) > 0)
		{
			echo "Sorry, this domain is already in use. If you own this domain, please contact us and we'll sort this issue for you.";
		}
		else
		{
			$hashVarsSeq = explode('|', $hashSequence);
			$hash_string = $MERCHANT_KEY . '|' . $txnid . '|';	
			foreach($hashVarsSeq as $hash_var) {
				$hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
				$hash_string .= '|';
			}
			$hash_string .= $SALT;
			$hash = strtolower(hash('sha512', $hash_string));
			$action = $PAYU_BASE_URL . '/_payment';
		}
	}

if($formError == 1)
{
	echo "Invalid Request. Some required details are missing. You can try again <a href='/purchase.php'>purchasing</a> or <a href='/auth/dashboard.php'>renewal</a>.";
}
else {
?>
<html>
  <head>
  <script>
    var hash = '<?php echo $hash ?>';
    function submitPayuForm() {
      if(hash == '') {
        return;
      }
      var payuForm = document.forms.payuForm;
      payuForm.submit();
    }
  </script>
  </head>
  <body onload="submitPayuForm()">

    <form action="<?php echo $action; ?>" method="post" name="payuForm">
		<input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ?>" />
		<input type="hidden" name="hash" value="<?php echo $hash ?>"/>
		<input type="hidden" name="txnid" value="<?php echo $txnid ?>" />
		<input type="hidden" name="surl" value="<?php echo $surl ?>" size="64" />
		<input type="hidden" name="furl" value="<?php echo $furl ?>" size="64" />
		<input type="hidden" name="service_provider" value="payu_paisa" size="64" />
		<input type="hidden" name="curl" value="<?php echo $curl ?>" />
		<input type="hidden" name="amount" value="<?php echo $posted['amount'] ?>" />
		<input type="hidden" name="firstname" id="firstname" value="<?php echo $posted['firstname']; ?>" />
		<input type="hidden" name="email" id="email" value="<?php echo $posted['email']; ?>" />
		<input type="hidden" name="phone" value="<?php echo $posted['phone']; ?>" />
		<input type="hidden" name="productinfo" value="<?php echo $posted['productinfo'] ?>">
		<input type="hidden" name="udf1" value="<?php echo $posted['udf1'] ?>">
		<input type="hidden" name="udf2" value="<?php echo $posted['udf2'] ?>">
		<input type="hidden" name="udf3" value="<?php echo $posted['udf3'] ?>">
    </form>

  </body>
</html>

<?php } ?>

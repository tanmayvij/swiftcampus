<!--footer start here-->
<div class="footer">
  <div class="container">
	  <div class="footer-main">
		<div class="footer-top">
			<div class="col-md-3 footer-news">
			<h5>Drop us a quick message...</h5>
			</div>
			<div class="col-md-9 ftr-email">
				<form action="/contact_us.php" method="post">
					<input required id="email_input" type="text" value="ENTER EMAIL" onfocus="this.value='';" onblur="if (this.value == '') {this.value ='ENTER EMAIL';}" name="email"><br><br>
					<input required type="text" value="ENTER MESSAGE" onfocus="this.value='';" onblur="if (this.value == '') {this.value ='ENTER MESSAGE';}" name="message">
					<input type="hidden" name="subject" value="Quick Mail">
					<input type="hidden" name="name" value="Quick Mail">
					<input type="submit" value="SEND" name="submit">
				</form>
			</div>
			<div class="clearfix"> </div>
		</div>
		<div class="foter-bottom">
			<br>
			<center><span class="glyphicon glyphicon-envelope" style="color: #FFFFFF; font-size: 20px;">&nbsp;info@<?php echo domain ?></span></center>
			<br>
			<p class="footer-copyrts">Copyright &copy; | All Rights Reserved by <a href="mailto:webmaster@<?php echo domain ?>">SwiftCampus Administrator</a></p><br>
			<p class="footer-copyrts" id="footer-copyrts-2">Designed by <a href="http://w3layouts.com/" target="_blank">W3layouts</a> </p>
		</div>
	  <div class="clearfix"> </div>
		</div>
	</div>
</div>
<!--footer end here-->
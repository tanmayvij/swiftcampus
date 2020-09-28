<?php

$pg = substr($_SERVER['PHP_SELF'], 1);

get_session();
if(isset($_SESSION['user_name']) && !empty($_SESSION['user_name']))
{
	$name = $_SESSION['user_name'];
}
else
{
	$name = "Welcome Guest";
}

?>
<script>
	function slideLogMenu()
	{
		$("#logmenu").slideToggle(300, function() {
								 // Animation complete.
								  });
	}
</script>
<div class="header">
	<div class="container">
		<div class="header-main">
			 <div class="logo">
			 	<h1><a href="/">Swift<span class="logo-clr">Campus</span></a></h1>
			 </div>
			 <div class="head-right">
			   <div class="top-nav">
			   		<span class="menu"> <img src="/rss/img/icon.png" alt=""/></span>
					<ul class="res">
					<?php
					
					switch($pg)
					{
						case "index.php":
						echo '
						<li><a class="active" href="/"> Home</a></li>
						<li><a href="/about_us.php"><span data-hover="About Us">About Us</span></a></li>
						<li><a href="/features.php"><span data-hover="Features">Features</span></a></li>
						<li><a href="/purchase.php"><span data-hover="Buy Now">Buy Now</span></a></li>
						<li><a href="/demo.php"><span data-hover="Demo">Demo</span></a></li>
						<li><a href="/contact_us.php"><span data-hover="Contact Us">Contact Us</span></a></li>
						';
						break;
						
						case 'about_us.php':
						echo '
						<li><a href="/"><span data-hover="Home">Home</span></a></li>
						<li><a class="active" href="/about_us.php">About Us</a></li>
						<li><a href="/features.php"><span data-hover="Features">Features</span></a></li>
						<li><a href="/purchase.php"><span data-hover="Buy Now">Buy Now</span></a></li>
						<li><a href="/demo.php"><span data-hover="Demo">Demo</span></a></li>
						<li><a href="/contact_us.php"><span data-hover="Contact Us">Contact Us</span></a></li>
						';
						break;
						
						case 'features.php':
						echo '
						<li><a href="/"><span data-hover="Home">Home</span></a></li>
						<li><a href="/about_us.php"><span data-hover="About Us">About Us</span></a></li>
						<li><a class="active" href="/features.php">Features</a></li>
						<li><a href="/purchase.php"><span data-hover="Buy Now">Buy Now</span></a></li>
						<li><a href="/demo.php"><span data-hover="Demo">Demo</span></a></li>
						<li><a href="/contact_us.php"><span data-hover="Contact Us">Contact Us</span></a></li>
						';
						break;
						
						case 'purchase.php':
						echo '
						<li><a href="/"><span data-hover="Home">Home</span></a></li>
						<li><a href="/about_us.php"><span data-hover="About Us">About Us</span></a></li>
						<li><a href="/features.php"><span data-hover="Features">Features</span></a></li>
						<li><a class="active" href="/purchase.php">Buy Now</a></li>
						<li><a href="/demo.php"><span data-hover="Demo">Demo</span></a></li>
						<li><a href="/contact_us.php"><span data-hover="Contact Us">Contact Us</span></a></li>
						';
						break;
						
						case 'demo.php':
						echo '
						<li><a href="/"><span data-hover="Home">Home</span></a></li>
						<li><a href="/about_us.php"><span data-hover="About Us">About Us</span></a></li>
						<li><a href="/features.php"><span data-hover="Features">Features</span></a></li>
						<li><a href="/purchase.php"><span data-hover="Buy Now">Buy Now</span></a></li>
						<li><a class="active" href="/demo.php">Demo</span></a></li>
						<li><a href="/contact_us.php"><span data-hover="Contact Us">Contact Us</span></a></li>
						';
						break;
						
						case 'contact_us.php':
						echo '
						<li><a href="/"><span data-hover="Home">Home</span></a></li>
						<li><a href="/about_us.php"><span data-hover="About Us">About Us</span></a></li>
						<li><a href="/features.php"><span data-hover="Features">Features</span></a></li>
						<li><a href="/purchase.php"><span data-hover="Buy Now">Buy Now</span></a></li>
						<li><a href="/demo.php"><span data-hover="Demo">Demo</span></a></li>
						<li><a class="active" href="/contact_us.php">Contact Us</a></li>
						';
						break;
						
						default:
						echo '
						<li><a href="/"><span data-hover="Home">Home</span></a></li>
						<li><a href="/about_us.php"><span data-hover="About Us">About Us</span></a></li>
						<li><a href="/features.php"><span data-hover="Features">Features</span></a></li>
						<li><a href="/purchase.php"><span data-hover="Buy Now">Buy Now</span></a></li>
						<li><a href="/demo.php"><span data-hover="Demo">Demo</span></a></li>
						<li><a href="/contact_us.php"><span data-hover="Contact Us">Contact Us</span></a></li>
						';
						break;
					}
					?>
					<div class="clearfix"> </div>
					</ul>

				<!-- script-for-menu -->
							 <script>
							   $( "span.menu" ).click(function() {
								 $( "ul.res" ).slideToggle( 300, function() {
								 // Animation complete.
								  });
								 });
							</script>
			<!-- /script-for-menu -->
			  </div><br><br>
			 <div class="auth">
				<button onclick="slideLogMenu()">
					<img src="/rss/img/guest-128.ico" height="32" width="32">
					<?php echo $name ?>
					<img src="/rss/img/down-arrow.ico" height="16" width="16">
				</button>
				<?php
				if(isset($_SESSION['user_name']) && !empty($_SESSION['user_name']))
				{
					echo '
					<div id="logmenu">
					<ul>
						<li><a href="/auth/dashboard.php">Dashboard</a></li><hr>
						<li><a href="/auth/profile.php">Profile Settings</a></li><hr>
						<li><a href="/auth/orders.php">Order History</a></li><hr>
						<li><a href="/auth/logout.php">Logout</a></li>
					</ul>
					</div>
					';
				}
				
				else
				{
					echo '
					<div id="logmenu">
					<ul>
						<li><a href="/auth/login.php">Login</a></li><hr>
						<li><a href="/auth/register.php">Register</a></li>
					</ul>
					</div>
					';
				}
				?>
			</div>
			<div class="clearfix"> </div>
		   </div>
		   <div class="clearfix"> </div>
		</div>
	</div>
</div>
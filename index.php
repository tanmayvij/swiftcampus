<?php
include 'rss/config/config.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home | SwiftCampus - Campus Management App | SaaS</title>
	<?php get_head(); ?>
</head>
<body>
<?php get_header(); ?>
<div id="wrapper">
	<div class="banner" style="position: relative; z-index: 0">
		<div class="container">
			<div class="banner-main">
				<h2>Welcome to SwiftCampus</h2>
				<p>The next gen School Management App</p>
			</div>
		</div>
	</div>
	<div class="recent-posts">
		<div class="container">
			<div class="recent-bottom">
				<div class="col-md-6 recent-left">
					<h3>What we offer</h3>
					<p>SwiftCampus is a web-based school management app developed keeping in mind the growing needs of modern day schools. Manage all the day-to-day tasks of your school all within one single app, anywhere and anytime, even on the go. From assignments to attendance, library management, fee management, and much more, it is a complete all-in-one solution for teachers, students & parents to collaborate.</p>
				</div>
				<div class="col-md-6 recent-right">
					<img src="/rss/img/b-w4.jpg" style="height: 330px; width: 400px;" class="img-responsive">
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
   </div>
   <div class="facts">
	  <div class="container">
	  	<h3>Why choose us?</h3>
	  	<div class="facts-main">
	  		<div class="col-md-4 fact-grid">
	  			<div class="fact-top">
		  				<h4>Free Hosting</h4>
		  				<p>We host the app for you at no extra cost. With our super-efficient servers running 24/7, you never have to worry about your site going down.</p>
	  			 </div>	
	  			 <div class="fact-top">
		  				<h4>Cheap Prices</h4>
		  				<p>With our introductory offer, it is impossible to find better deals with any other campus management software.</p>
	  			 </div>	
	  		</div>
	  		<div class="col-md-4 fact-grid">
	  			<div class="fact-top">
		  				<h4>User-friendly layout</h4>
		  				<p>SwiftCampus has been designed keeping in mind that ease of use is the user's priority when it comes to school management software.</p>
	  			 </div>	
	  			 <div class="fact-top">
		  				<h4>Easy to setup</h4>
		  				<p>Just create an account and have a completely up and running school management app running within 10 minutes!</p>
	  			 </div>	
	  		</div>
	  		<div class="col-md-4 fact-grid">
	  			<div class="fact-top">
		  				<h4>Super-Quick Support</h4>
		  				<p>Got queries? Facing Problems? Want to give feedback? We're available all day, any day! Write to us and get a reply within 1 day, INCLUDING HOLIDAYS!</p>
	  			   </div>
	  			 <div class="fact-top">
		  				<h4>No data limit</h4>
		  				<p>Got a big school with numerous teachers and students? We got you covered! We don't put any restrictions on the space used by your app on our servers or the bandwidth consumed. Never worry about running out of resources.</p>
	  			   </div>
	  			 </div>	
	  		</div>
	  	<div class="clearfix"> </div>
	  </div>
   </div>
	<div class="grid-main">
	<div class="container">
			<div class="process">
				<h3>How to get started?</h3>
				<p>Just three quick steps and you'll have a completely up and running web app ready for use.</p>
			    <div class="process-bottom">
			    	<div class="col-md-4 process-grid">
			    		<span class="glyphicon glyphicon-user" aria-hidden="true"> </span>
			    		<h4>1. Create an account</h4>
			    		<p><a href="/auth/register.php">Register</a> for a new account or <a href="/auth/login.php">login</a> to your existing account.</p>
			    	</div>
			    	<div class="col-md-4 process-grid">
			    		<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"> </span>
			    		<h4>2. Place the order</h4>
			    		<p>Fill up a quick order form and make the payment. Your app will be instantly activated upon successful transaction.</p>
			    	</div>
			    	<div class="col-md-4 process-grid">
			    		<span class="glyphicon glyphicon-wrench" aria-hidden="true"> </span>
			    		<h4>3. Set up</h4>
			    		<p>At this stage your app is installed and accessible via your domain. All you need to do is upload your school's logo on your app and create an admin account. Voila! Your app is ready for use!</p>
			    	</div>
			      <div class="clearfix"> </div>
			   </div>
	     </div>	
      </div>
   </div>
   <div class="work">
      	<div class="container">
				<div class="work-top">
					<h3>Pricing</h3>
					<p>Ultra Cheap prices are what make us special compared to other providers</p>
				</div>
				<div class="work-bottom">
					<div class="col-md-3 portfolio-wrapper">		
							<img src="/rss/img/p1.jpg" alt="Monthly Plan">
                         <div class="work-details">
					   	  <h3>Pay Monthly</h3>
					   	 <p>@ an unbelievable price of INR 499</p>
					   </div>
					</div>
					<div class="col-md-3 portfolio-wrapper">		
							<img src="/rss/img/p2.jpg" alt="3 Month Plan">
                         <div class="work-details">
					   	  <h3>Pay for 3 Months</h3>
					   	 <p>Just pay INR 1399 for 3 months</p>
					   </div>
					</div>
					<div class="col-md-3 portfolio-wrapper">		
							<img src="/rss/img/p3.jpg" alt="6 Month Plan">
                         <div class="work-details">
					   	  <h3>Pay for 6 Months</h3>
					   	 <p>Just pay INR 2695 for 6 months</p>
					   </div>
					</div>
					<div class="col-md-3 portfolio-wrapper">		
							<img src="/rss/img/p4.jpg" alt="Yearly Plan">
                         <div class="work-details">
					   	  <h3>Pay Yearly</h3>
					   	 <p>Available for INR 4990/Year</p>
					   </div>
					</div>
				<div class="clearfix"> </div>
			 </div>
		  </div>
	</div>
</div>
	<?php get_footer(); ?>
</body>
</html>
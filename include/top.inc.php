<body>
<div id='container'>
	<div id="head">
		<div id='logo'>
		<a href='/'><img src='/resources/images/apoc-banner.jpg' alt='From Ashes' /></a>
		</div>
	
		<div id='login'>
	<?php
	
	// Display the log in form if user is not logged in.
	//
	if (!isset($_SESSION['online'])){
	?>
		<form action='/action/signin/' method='post'>
		<div class='login-input'>
			<label for='username'>
				Survivor: 
			</label>
			<input type='text' name='username' class='login-text'>
		</div>
		<br />
		<div class='login-input'>
			<label for='password'>
				Password: 
			</label>
			<input type='password' name='password' class='login-text'>
		</div>
		<br />
		<div class='login-input'>
			<input type='submit' value='Log In' id='login-submit'>
		</div>
		</form>
	
	<?php
	} // END IF
	// Log out button if player is logged in
	//
	else{
		echo "<a href='/action/logout/'>Log Out</a>";
	}
	?>
		</div>
	
		<div class='clear'></div>
		<div id='nav'>
			<ul>
		<?php	
			// "New Survivor" link if user not logged in.
			//
			if (!isset($_SESSION['online'])){
		
				echo "<li class='nav-link'><a href='/page/create/'>New Survivor</a></li>";
			}
			else {
				echo '';
			}
		?>
				<li class='nav-link'>
					<a href='http://fromashes.boards.net/board/10/announcements' target='_blank'>News</a>
				</li>
				<li class='nav-link'>
					<a href='http://fromashes.boards.net/thread/5/faqs-answers/' target='_blank'>FAQs</a>
				</li>
				<li class='nav-link'>
					<a href='http://fromashes.boards.net/board/11/help' target='_blank'>Help</a>
				</li>
				<li class='nav-link'>
					<a href='http://fromashes.boards.net/' target='_blank'>Community</a>
				</li>
			</ul>
		</div>
	</div>
	<div class='clear'></div>
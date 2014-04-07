<?php
session_start();
include('../include/init.inc.php');
?>
<!DOCTYPE html>

<?php include('../include/head.inc.php'); ?>
	<title>From Ashes | Welcome</title>
</head>

<?php include('../include/top.inc.php'); ?>

	<hr />
	<div id='content'>
		<br /><br />
		<div id='leftCol'>
			<h2>Create your Survivor.</h2>
			
			<span class='smalltext'>* required field</span>
			<br />
			<form id='create-survivor' method='post' action='/action/survivor/create/'>
				<label for='username' class='create-label'>
					Survivor's Name*:
				</label>
				<input type='text' name='username' maxlength=48 ><br />

				<label for='password' class='create-label'>
					Password*:
				</label>
				<input type='password' name='password' maxlength=48 ><br />
				
				<label for='re-password' class='create-label'>
					Confirm Password*:
				</label>
				<input type='password' name='repassword' maxlength=48><br />
				
				<label for='email' class='create-label'>
					Email Address:
				</label>
				<input type='text' name='email' maxlength=48 ><br />
				<span class='smalltext'>An email address is not required to play, but can remove your survivor from quarantine.</span><br />
				<input type='submit' value='Join From Ashes' name='submit' id='create-submit'>
			</form>
			<br /><br />
			
			<p><strong>Important Security Notice - READ THIS!</strong>
			<br /><br />
			From Ashes is still in <em>very early</em> stages of development. It is <strong>highly recommended</strong> that you <em><strong>do not</strong></em> use a password you use anywhere else, or your real email address. While a few steps have been taken to secure stored information, there is no guarantee that your information is completely safe. Contact Mr Williams on the From Ashes forum if you need to change any information or if you have any questions.
			</p>
		</div>
		<div id='rightCol'>
			<h2>Welcome to From Ashes</h2>
			<p>From Ashes is a post-apocalyptic game that puts you in the place of your own survivor. You're given the choice for how to live your life in this new, torn world. Will you band together with others to help build a community? Or will your group obtain resources by raiding other camps?</p>
			
			<p>If you verify your account using an email address, you can explore all of the features of From Ashes. Keep in mind that each player is only allowed to control <em><strong>one verified survivor at a time</strong></em> to prevent single users from building an army on their own. Unverified survivors are placed in quarantine mode and will not be able to enjoy as many features, such as trading with other players. Your email will never be used for any other reason than for verification purposes.</p>
		</div>
<?php include('../include/footer.inc.php'); ?>

</body>
</html>
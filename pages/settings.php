<?php
session_start();
include('../include/init.inc.php');
include('../include/functions.php');

if (!isset($_SESSION['online'])){
	header("Location:/home/");
}
else{}

$surv_qry = "SELECT surv_desc
				FROM survivor
				WHERE surv_id = '$player_id'";
$surv_result = $db->query($surv_qry);
$surv_row = $surv_result->fetch_assoc();

$surv_desc = trim(br2nl($surv_row['surv_desc']),'\t');
$surv_desc = stripslashes_deep($surv_desc);

?>
<!DOCTYPE html>

<?php include('../include/head.inc.php'); ?>
	<title>From Ashes | Settings</title>
</head>

<?php include('../include/top.inc.php'); ?>

	<hr />
	<div id='content'>
		<?php include('../include/control.inc.php'); ?>
		
		<div id='page-content'>
			<h2 id='surv_name'><?php echo $pl_s_name; ?></h2>

			
			<h3>Update your biography.</h3>
			<form id='update-bio' method='post' action='/action/survivor/bio/'>
			
			<?php
				if ($surv_desc){
			?>
				<textarea name='bio' maxlength=512 cols=70 rows=7 required><?php echo $surv_desc; ?></textarea>
			<?php
			}
				else{
			?>
				<textarea name='bio' maxlength=512 cols=70 rows=7 placeholder='Limit 512 characters...' required></textarea>
			<?php
			}
			?>
				<br />
				<input type='submit' value='Update Bio' name='update' />
				
			</form>
			
		<br />
		<hr />
		<br />
		
			<form id='update-password' method='post' action='/action/survivor/password/'>
			
			<label for='curr-pass' class='create-label'>
				Current Password: 
			</label>
				<input type='password' id='curr-pass' name='curr' required />
				<br /><br />
				
			<label for='new-pass' class='create-label'>
				New Password: 
			</label>
				<input type='password' id='new-pass' name='new' required />
				<br />
			<label for='con-pass' class='create-label'>
				Confirm Password: 
			</label>
				<input type='password' id='con-pass' name='con' required />
				<br />
				<input type='submit' value='Update Password' name='post' />
			</form>
		
		<br />
		<hr />
		<br />
		
			<form id='update-email' method='post' action='/action/survivor/email/'>
			
			<label for='email' class='create-label'>
				Verify email: 
			</label>
				<input type='email' name='email' id='email' required />
				<input type='submit' value='Verify' disabled />
			</form>
		</div>
		
<?php include('../include/footer.inc.php'); ?>

</body>
</html>
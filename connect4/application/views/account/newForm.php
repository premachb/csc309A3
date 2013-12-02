
<!DOCTYPE html>

<html>
	<head>
		<style>
			input {
				display: block;
			}
		</style>
		<script src="http://code.jquery.com/jquery-latest.js"></script>
		<script>
			function checkPassword() {
				var p1 = $("#pass1"); 
				var p2 = $("#pass2");
				
				if (p1.val() == p2.val()) {
					p1.get(0).setCustomValidity("");  // All is well, clear error message
					return true;
				}	
				else	 {
					p1.get(0).setCustomValidity("Passwords do not match");
					return false;
				}
			}
		</script>
	</head> 
<body>  
	<h1>New Account</h1>

<?php 
	echo form_open('account/createNew');
	echo form_label('Username'); 
	echo form_error('username');
	echo form_input('username',set_value('username'),"required");
	echo form_label('Password'); 
	echo form_error('password');
	echo form_password('password','',"id='pass1' required");
	echo form_label('Password Confirmation'); 
	echo form_error('passconf');
	echo form_password('passconf','',"id='pass2' required oninput='checkPassword();'");
	echo form_label('First');
	echo form_error('first');
	echo form_input('first',set_value('first'),"required");
	echo form_label('Last');
	echo form_error('last');
	echo form_input('last',set_value('last'),"required");
	echo form_label('Email');
	echo form_error('email');
	echo form_input('email',set_value('email'),"required");
	
?>	

	<br>
	<img id="captcha" src="/connect4/securimage/securimage_show.php" alt="CAPTCHA Image" />
	
	<br>
	<input type="text" name="captcha_code" size="10" maxlength="6" />

	<a href="#" onclick="document.getElementById('captcha').src = '/connect4/securimage/securimage_show.php?' + Math.random(); return false">[ Different Image ]</a>
	
	<script>
	if ($securimage->check($_POST['captcha_code']) == false) {
	  // the code was incorrect
	  // you should handle the error so that the form processor doesn't continue
	
	  // or you can use the following code if there is no validation or you do not know how
	  echo "The security code entered was incorrect.<br /><br />";
	  echo "Please go <a href='javascript:history.go(-1)'>back</a> and try again.";
	  exit;
	}
	</script>
	
	<?php
		include_once $_SERVER['DOCUMENT_ROOT'] . '/connect4/securimage/securimage.php';

		$securimage = new Securimage();
		
		echo form_submit('submit', 'Register');
		echo form_close();
	?>
</body>

</html>


<div class="col-md-9">
	<small><ol class="breadcrumb">
			<li><a href="<?php echo base_url(); ?>">Feedback</a></li>
			<li class="active"><?php echo $lang['label_registration']; ?></li>
	  </ol></small>
	<h2><?php echo $lang['label_registration_form']; ?></h2>
	
  <?php if ($error == "recaptcha") {?>
    <p class="bg-danger" style="width:100%;height:30px;padding-left:10px;padding-top:5px;"><?php echo $lang['error_recaptcha']; ?></p>
  <?php } elseif ($error == "name") {?>
    <p class="bg-danger" style="width:100%;height:30px;padding-left:10px;padding-top:5px;"><?php echo $lang['error_name']; ?></p>
  <?php } elseif ($error == "email") {?>
    <p class="bg-danger" style="width:100%;height:30px;padding-left:10px;padding-top:5px;"><?php echo $lang['error_email']; ?></p>
  <?php } elseif ($error == "pass") {?>
    <p class="bg-danger" style="width:100%;height:30px;padding-left:10px;padding-top:5px;"><?php echo $lang['error_password']; ?></p>
  <?php } elseif ($error == "pass2") {?>
    <p class="bg-danger" style="width:100%;height:30px;padding-left:10px;padding-top:5px;"><?php echo $lang['error_passwords']; ?></p>
  <?php } elseif ($error == "exists") {?>
    <p class="bg-danger" style="width:100%;height:30px;padding-left:10px;padding-top:5px;"><?php echo $lang['error_email_exists']; ?></p>
  <?php } ?>

  <form name="registration-form" action="<?php echo base_url() . 'action/register'; ?>" method="POST" onsubmit="return validateForm()">
   	<div class="form-group">
      <label for="InputEmail"><?php echo $lang['form_email']; ?></label>
      <input type="email" class="form-control" id="InputEmail" placeholder="<?php echo $lang['form_email']; ?>" name="email" required>
    </div>

    <div class="form-group">
      <label for="InputName"><?php echo $lang['form_full_name']; ?></label>
      <input type="text" class="form-control" id="InputName" placeholder="<?php echo $lang['form_full_name']; ?>" name="name" required>
    </div>
	
	
	
    <div class="form-group">
      <label for="InputPassword"><?php echo $lang['form_password']; ?></label>
	  <div id="password-error-show" style="color:red"></div>
      <input type="password" class="form-control" id="InputPassword" placeholder="<?php echo $lang['form_password']; ?>" name="password" minlength="6" required>
	</div>
    <div class="form-group">
      <label for="InputPassword2"><?php echo $lang['form_repeat_password']; ?></label>
      <input type="password" class="form-control" id="InputPassword2" placeholder="<?php echo $lang['form_repeat_password']; ?>" name="password2" required>
    </div>
  <?php if ($recaptchapublic != ""): ?>
    <script type="text/javascript">
	 var RecaptchaOptions = {
	    theme : 'clean'
	 };
 	</script>
    <script type="text/javascript"
     src="https://www.google.com/recaptcha/api/challenge?k=<?php echo $recaptchapublic; ?>">
  </script>
  <noscript>
     <iframe src="https://www.google.com/recaptcha/api/noscript?k=<?php echo $recaptchapublic; ?>"
         height="300" width="500" frameborder="0"></iframe><br>
     <textarea name="recaptcha_challenge_field" rows="3" cols="40">
     </textarea>
     <input type="hidden" name="recaptcha_response_field"
         value="manual_challenge">
  </noscript>
<?php endif;?>
    <div style="margin-top:10px"><button type="submit" class="btn btn-primary"><?php echo $lang['label_registration']; ?></button></div>
  </form>
</div>

<script>
function validateForm() {
    var pass = document.forms["registration-form"]["password"].value;
	var passVerify = document.forms["registration-form"]["password2"].value;
	
	var errorShowDiv = document.getElementById("password-error-show");
	
	var passMatchError = "<?php echo $lang['error_passwords']?>";
	
    if (pass != passVerify) {
		errorShowDiv.innerHTML = passMatchError;
        return false;
    }
	
}
</script>

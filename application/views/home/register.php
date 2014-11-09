<div class="contentdiv pull-left" style="padding-left:40px;padding-right:50px;width:70%">
<small><ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>">Feedback</a></li>
        <li class="active">User Registration</li>
  </ol></small>
<h2>Registration Form</h2>
  <?php if($error == "recaptcha"){ ?>
    <p class="bg-danger" style="width:100%;height:30px;padding-left:10px;padding-top:5px;">Invalid Recaptcha code</p>
  <?php } elseif($error == "name"){ ?>
    <p class="bg-danger" style="width:100%;height:30px;padding-left:10px;padding-top:5px;">Invalid Name</p>
  <?php } elseif($error == "email"){ ?>
    <p class="bg-danger" style="width:100%;height:30px;padding-left:10px;padding-top:5px;">Invalid Email</p>
  <?php } elseif($error == "pass"){ ?>
    <p class="bg-danger" style="width:100%;height:30px;padding-left:10px;padding-top:5px;">Password must have at least 6 characters</p>
  <?php } elseif($error == "pass2"){ ?>
    <p class="bg-danger" style="width:100%;height:30px;padding-left:10px;padding-top:5px;">Passwords don't match</p>
  <?php } elseif($error == "exits"){ ?>
    <p class="bg-danger" style="width:100%;height:30px;padding-left:10px;padding-top:5px;">Email already exists</p>
  <?php } ?>
  
  <form role="form" action="<?php echo base_url() . 'action/register'; ?>" method="POST">
   	<div class="form-group">
      <label for="InputEmail">Email address</label>
      <input type="email" class="form-control" id="InputEmail" placeholder="Enter email" name="email">
    </div>

    <div class="form-group">
      <label for="InputName">Full Name</label>
      <input type="text" class="form-control" id="InputName" placeholder="Full Name" name="name">
    </div>
    <div class="form-group">
      <label for="InputPassword">Password</label>
      <input type="password" class="form-control" id="InputPassword" placeholder="Password" name="password">
    </div>
    <div class="form-group">
      <label for="InputPassword2">Repeat Password</label>
      <input type="password" class="form-control" id="InputPassword2" placeholder="Repeat Password" name="password2">
    </div>
  <?php if($recaptchapublic != ""):?>
    <script type="text/javascript">
	 var RecaptchaOptions = {
	    theme : 'clean'
	 };
 	</script>
    <script type="text/javascript"
     src="http://www.google.com/recaptcha/api/challenge?k=<?php echo $recaptchapublic; ?>">
  </script>
  <noscript>
     <iframe src="http://www.google.com/recaptcha/api/noscript?k=<?php echo $recaptchapublic; ?>"
         height="300" width="500" frameborder="0"></iframe><br>
     <textarea name="recaptcha_challenge_field" rows="3" cols="40">
     </textarea>
     <input type="hidden" name="recaptcha_response_field"
         value="manual_challenge">
  </noscript>
<?php endif;?>
    <div style="margin-top:10px"><button type="submit" class="btn btn-primary">Registration</button></div>
  </form>
</div>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta charset="utf-8">
  <link rel="icon" type="image/x-icon" href="../favicon.ico" sizes="16x16">
        
  <title>PHPBack Installation</title>

    <link href="../public/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../public/bootstrap/css/prettify.css" rel="stylesheet">
    <link href="../public/css/flat-ui.css" rel="stylesheet">
    <link href="../public/css/demo.css" rel="stylesheet">
    <script src="../public/js/jquery-2.0.3.min.js"></script>
    <script src="../public/js/bootstrap.min.js"></script>

  <style type="text/css">
  a:hover{
    text-decoration: none;
  }
  body{
        background-color: #1ABC9C;
  }
  .phpback_logo{
	text-align: center;
	margin-bottom: 15px;
	}
  .configfile{
  	text-align: center;
  	background-color: #d3dfe0;
  	border-radius: 5px;
  	border-style: solid;
  	border-color: #e66161;
  	border-width: 2px;
  	padding: 5px 5px 5px 5px;
  	margin-top: 20px;
  }
  .phpcode{
  	text-align: left;
  	background-color: #f9f2f4;
  	border-radius: 5px;
  }
  </style>
</head>
<body>
	<div class="login-screen">
		<div class="login-form">
		<form action="install1.php" method="POST" name="install-form" onsubmit="return validateForm()">
			<div class="phpback_logo">
				<img src="../public/img/logo_free.png" />
			</div>
			<h5>PHPBack installation</h5>
		<?php if(isset($_POST['error'])): ?>
			<div style="color:#C0392B;font-size:20px"><?php echo $_POST['error']; ?></div>
			<div style="color:#3C6E8F;font-size:15px;margin-bottom: 15px;">
                Need help? Please, visit our
                <a href="https://github.com/ivandiazwm/phpback/wiki/How-To-install#frequent-installation-issues" target="_blank">
                    frequent issues documentation.
                </a>
            </div>
		<?php endif; ?>
      <div class="form-group">
              <input type="text" class="form-control login-field" value="" placeholder="MySQL hostname" id="hostname" name="hostname" required/>
              <label class="login-field-icon" for="hostname"></label>
            </div>
            <div class="form-group">
              <input type="text" class="form-control login-field" value="" placeholder="MySQL username" id="username" name="username" required/>
              <label class="login-field-icon" for="username"></label>
            </div>
            <div class="form-group">
              <input type="password" class="form-control login-field" value="" placeholder="MySQL password" id="password" name="password" />
              <label class="login-field-icon fui-lock" for="password"></label>
            </div>
            <div class="form-group">
              <input type="text" class="form-control login-field" value="" placeholder="MySQL database*" id="database" name="database" />
              <label class="login-field-icon" for="database"></label>
            </div>
            <small>*Leave blank for automatic database creation</small>
            <hr>

			<h6>Create Admin account </h6>
            <div class="form-group">
              <input type="text" class="form-control login-field" value="" placeholder="Admin Name" id="adminname" name="adminname" required/>
              <label class="login-field-icon" for="adminname"></label>
            </div>
			   <div class="form-group">
              <input type="text" class="form-control login-field" value="" placeholder="Admin Email" id="adminemail" name="adminemail" required/>
              <label class="login-field-icon" for="adminemail"></label>
            </div>
			<div id="password-error-show" style="color:red"></div>
				<div class="form-group">
			   
              <input type="password" class="form-control login-field" value="" placeholder="Admin password" id="adminpass" name="adminpass" minlength="6" required/>
              <label class="login-field-icon fui-lock" for="adminpass"></label>
            </div>
            <div class="form-group">
              <input type="password" class="form-control login-field" value="" placeholder="Repeat admin password" id="adminrpass" name="adminrpass" required/>
              <label class="login-field-icon fui-lock" for="adminrpass"></label>
            </div>


            <input type="submit" class="btn btn-primary btn-lg btn-block" value="Proceed">
        </form>
        </div>
        </div>
</div>

<script>
function validateForm() {
    var pass = document.forms["install-form"]["adminpass"].value;
	var passVerify = document.forms["install-form"]["adminrpass"].value;
	
	var errorShowDiv = document.getElementById("password-error-show");
	
    if (pass != passVerify) {
		errorShowDiv.innerHTML = "Passwords do not match";
        return false;
    }
	
}

</script>

</body>
</html>

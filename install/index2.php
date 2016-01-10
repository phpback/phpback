<!DOCTYPE html>
<html lang="en">
<head>
  <title>PHPBack Installation</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta charset="utf-8">
  <link rel="icon" type="image/x-icon" href="../favicon.ico" sizes="16x16">
        
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
  </style>
</head>
<body>
	<div class="login-screen">
		<div class="login-form">
		<form action="install2.php" method="POST" name="install2-form">
			<div class="phpback_logo">
				<img src="../public/img/logo_free.png" />
			</div>
			<h5>PHPBack installation</h5>
            <div class="form-group">
              <input type="text" class="form-control login-field" value="" placeholder="Feedback Title" id="title" name="title" />
              <label class="login-field-icon" for="title"></label>
            </div>
            <div class="form-group">
              <input type="text" class="form-control login-field" value="" placeholder="Mail email" id="mainmail" name="mainmail" />
            </div>
            <div class="form-group">
              <input type="text" class="form-control login-field" value="" placeholder="Recaptcha Public key*" id="rpublic" name="rpublic" />
            </div>
            <div class="form-group">
              <input type="text" class="form-control login-field" value="" placeholder="Recaptcha Private key*" id="rprivate" name="rprivate" />
            </div>
            <small>*Left in blank if you do not want to use recaptcha</small>
            <div class="form-group">
              <input type="text" class="form-control login-field" value="" placeholder="SMTP Host" id="smtp-host" name="smtp-host" />
              <label class="login-field-icon" for="smtp-host">SMTP Hostname</label>
            </div>
            <div class="form-group">
              <input type="text" class="form-control login-field" value="" placeholder="SMTP Username" id="smtp-user" name="smtp-user" />
              <label class="login-field-icon" for="smtp-user">SMTP User</label>
            </div>
            <div class="form-group">
              <input type="password" class="form-control login-field" value="" placeholder="SMTP Password" id="smtp-password" name="smtp-password" />
              <label class="login-field-icon fui-lock" for="smtp-password">SMTP Password</label>
            </div>
            <div class="form-group">
              <input type="text" class="form-control login-field" value="25" placeholder="SMTP Port" id="smtp-port" name="smtp-port" />
              <label class="login-field-icon" for="smtp-port">SMTP Port</label>
            </div>

            <input type="submit" class="btn btn-primary btn-lg btn-block" value="Proceed">
        </form>
    </div>
  </div>
</body>
</html>

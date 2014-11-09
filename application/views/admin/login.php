<div class="login-screen">
		<div class="login-form">
		<form action="<?php echo base_url() . 'adminaction/login'?>" method="POST">
			<div class="loginlogo">
				<img src="<?php echo base_url() . 'public/img/logo_free.png'?>" />
			</div>
		<?php if(!isset($error)): ?>
            <div class="form-group">
              <input type="text" class="form-control login-field" value="" placeholder="Enter your email" id="login-name" name="email" />
              <label class="login-field-icon fui-user" for="login-name"></label>
            </div>

            <div class="form-group">
              <input type="password" class="form-control login-field" value="" placeholder="Password" id="login-pass" name="password"/>
              <label class="login-field-icon fui-lock" for="login-pass"></label>
            </div>
            <input type="submit" class="btn btn-primary btn-lg btn-block" value="Log In">
          </div>
      <?php elseif($error == 'noadmin'):?>
      	<div style="color:#C0392B;font-size:20px">You are not an admin</div>
      <?php else: ?>
      	<div style="color:#C0392B;font-size:15px">Email or password are incorrect</div>
      	<div class="form-group">
              <input type="text" class="form-control login-field" value="" placeholder="Enter your email" id="login-name" name="email" />
              <label class="login-field-icon fui-user" for="login-name"></label>
            </div>

            <div class="form-group">
              <input type="password" class="form-control login-field" value="" placeholder="Password" id="login-pass" name="password"/>
              <label class="login-field-icon fui-lock" for="login-pass"></label>
            </div>
            <input type="submit" class="btn btn-primary btn-lg btn-block" value="Log In">
          </div>
      <?php endif; ?>
        </form>
        </div>
</div>
</body>
</html>
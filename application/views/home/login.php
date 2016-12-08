<div class="col-md-7">
  <small><ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>">Feedback</a></li>
        <li class="active"><?php echo $lang['label_log_in']; ?></li>
  </ol></small>
  <?php if($error == "errorlogin"): ?>
    <p class="bg-danger" style="width:100%;height:35px;padding-left:10px;padding-top:5px;"><?php echo $lang['error_login']; ?></p>
  <?php elseif ($error == "register") :?>
    <p class="bg-success" style="width:100%;height:35px;padding-left:10px;padding-top:5px;"><?php echo $lang['text_registration_success']; ?></p>
  <?php elseif($error == "banned"): ?>
    <p class="bg-danger" style="width:100%;height:35px;padding-left:10px;padding-top:5px;"><?php echo ($ban == -1) ? $lang['error_banned_inf'] : str_replace('%s', $ban, $lang['error_banned']); ?></p>
  <?php endif; ?>
  <form name="login-form" action="<?php echo base_url() . 'action/login'; ?>" method="POST">
    <div class="form-group">
      <label for="InputEmail"><?php echo $lang['form_email']; ?></label>
      <input type="email" class="form-control" id="InputEmail" placeholder="<?php echo $lang['form_email']; ?>" name="email" required>
    </div>
    <div class="form-group">
      <label for="InputPassword"><?php echo $lang['form_password']; ?></label>
      <input type="password" class="form-control" id="InputPassword" placeholder="<?php echo $lang['form_password']; ?>" name="password" required>
    </div>
    <label class="checkbox" for="checkbox1">
      <input type="checkbox" value="" id="checkbox1" name="rememberme" data-toggle="checkbox">
        <?php echo $lang['form_remember']; ?>
    </label>
    <button type="submit" class="btn btn-primary"><?php echo $lang['label_log_in']; ?></button> <a href="<?php echo base_url() . 'home/register';?>"><span style="padding-left:10px"><?php echo $lang['text_create_an_account']; ?></span></a>
  </form>
</div>
<div class="col-md-2"></div>
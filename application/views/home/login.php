<div class="contentdiv pull-left" style="padding-left:40px;padding-right:50px;width:70%">
  <small><ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>">Feedback</a></li>
        <li class="active">Log in</li>
  </ol></small>
  <?php if($error == "errorlogin"): ?>
    <p class="bg-danger" style="width:100%;height:35px;padding-left:10px;padding-top:5px;">Invalid Password or Email</p>
  <?php elseif ($error == "register") :?>
    <p class="bg-success" style="width:100%;height:35px;padding-left:10px;padding-top:5px;">Registration successful.</p>
  <?php elseif($error == "banned"): ?>
    <p class="bg-danger" style="width:100%;height:35px;padding-left:10px;padding-top:5px;">You have been banned <?php echo ($ban == -1) ? "indefinitely." : " for $ban days." ; ?></p>
  <?php endif; ?>
  <form role="form" action="<?php echo base_url() . 'action/login'; ?>" method="POST">
    <div class="form-group">
      <label for="InputEmail">Email address</label>
      <input type="email" class="form-control" id="InputEmail" placeholder="Enter email" name="email">
    </div>
    <div class="form-group">
      <label for="InputPassword">Password</label>
      <input type="password" class="form-control" id="InputPassword" placeholder="Password" name="password">
    </div>
    <label class="checkbox" for="checkbox1">
      <input type="checkbox" value="" id="checkbox1" name="rememberme" data-toggle="checkbox">
      Remember me
    </label>
    <button type="submit" class="btn btn-primary">Log in</button> <a href="<?php echo base_url() . 'home/register';?>"><span style="padding-left:10px">Or Create an account</span></a>
  </form>
</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1 dashboard-center">
    <nav class="navbar navbar-inverse" role="navigation">
      <div class="navbar-header">
        <div class="logosmall">
          <img src="<?php echo base_url() . 'public/img/logo_small_free.png'?>">
        </div>
      </div>
      <div class="collapse navbar-collapse" id="navbar-collapse-01">
        <ul class="nav navbar-nav">
          <li><a href="<?php echo base_url() . 'admin'; ?>">Dashboard</a></li>
          <li><a href="<?php echo base_url() . 'admin/ideas'; ?>">Ideas and Comments</a></li>
          <li><a href="<?php echo base_url() . 'admin/users'; ?>">Users Management</a></li>
          <li class="active"><a href="<?php echo base_url() . 'admin/system'; ?>">System Settings</a></li>
        </ul>
          <p class="navbar-text navbar-right">Signed in as <span style="color:#27AE60"><?php echo $_SESSION['phpback_username']; ?></span><a href="<?php echo base_url() . 'action/logout'; ?>"><button type="button" class="btn btn-danger btn-xs" style="margin-left:10px;">Log out</button></a></p>
      </div><!-- /.navbar-collapse -->
    </nav><!-- /navbar -->
  <div>
      <h5>System Settings</h5>
      <ul class="nav nav-tabs">
        <li id="table1" class="active"><a onclick="showtable3('generaltable','table1');">General Settings</a></li>
        <li id="table2"><a onclick="showtable3('admintable','table2');">Create Admin</a></li>
        <li id="table3"><a onclick="showtable3('categorytable','table3');">Categories</a></li>
        <li id="table4"><a onclick="showtable3('upgradetable','table4');">Upgrade Version</a></li>
      </ul>
      <div id="generaltable">
          <form role="form" method="post" action="<?php echo base_url() . 'adminaction/editsettings'?>">
            <?php foreach ($settings as $setting): ?>
            <div class="form-group">
              <label><?php echo $setting->name ?></label>
              <input type="text" class="form-control" name="setting-<?php echo $setting->id; ?>" value="<?php echo $setting->value; ?>" style="width:300px">
            </div>
          <?php endforeach; ?>
            <div class="form-group">
              <button name="submit-changes" type="submit" class="btn btn-primary">Submit changes</button>
            </div>
          </form>
      </div>
      <div id="admintable" style="display:none">
      <table class="table table-condensed" style="">
          <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Admin Level</th>
                </tr>
            </thead>
              <tbody>
            <?php foreach ($adminusers as $user): ?>
              <?php $freename = str_replace(" ", "-", $user->name); ?>
            <tr>
              <td>
                <a href="<?php echo base_url() . 'home/profile/' . $user->id . '/' . $freename; ?>" target="_blank">#<?php echo $user->id;?></a>
              </td>
              <td>
                <?php echo $user->name; ?>
              </td>
              <td>
                <?php echo $user->email; ?>
              </td>
              <td>
                <?php echo $user->isadmin; ?>
              </td>
            </tr>
              <?php endforeach; ?>
          </tbody>
        </table>
        <form role="form" method="post" action="<?php echo base_url() . 'adminaction/editadmin'?>">
            <div class="form-group">
              <label>User ID</label>
              <input type="text" class="form-control" name="id" style="width:300px">
            </div>
            <div class="form-group">
              <label>Admin Level</label>
              <select class="form-control" name="level" style="width:300px">
                <option value="0">No Administration Rights</option>
                <option value="1">Ideas and Comments (Level 1)</option>
                <option value="2">Level 1 + User Management (Level 2)</option>
                <option value="3">Full Administration Rights (Level 3)</option>
              </select>
            </div>
            <div class="form-group">
              <button name="submit-create-admin" type="submit" class="btn btn-primary">Submit changes</button>
            </div>
          </form>
      </div>
      <div id="upgradetable" style="display:none">
         <div class="alert alert-<?php echo ($isLastVersion) ? 'success' : 'warning'; ?>" role="alert">You are running PHPBack v<?php echo $version; ?><br />
         <?php if($isLastVersion): ?>
             You're running the latest version of PHPBack.
         <?php else: ?>
            Please, upgrade to PHPBack v<?php echo $lastVersion; ?><br /><br />
            <a href="<?php echo base_url(); ?>adminaction/upgrade" class="btn btn-primary">Upgrade</a><br />
             <span style="font-size:13px;">(it may take a moment)</span>
         <?php endif;?>
             <br /><br />
             Please, visit <a href="http://www.phpback.org/" target="_blank">PHPBack.org</a> for more details.
         </div>
      </div>
      <div id="categorytable" style="display:none">
        <h4>Add a new Category</h4>
        <form role="form" method="post" action="<?php echo base_url() . 'adminaction/addcategory'?>">
            <div class="form-group">
              <label>Category name</label>
              <input type="text" class="form-control" name="name" style="width:300px">
              <small>(put an existing category name to change its description)</small>
            </div>
            <div class="form-group">
              <label>Category description</label>
              <textarea class="form-control" name="description" style="width:300px"></textarea>
            </div>
            <div class="form-group">
              <button name="add-category" type="submit" class="btn btn-primary">Add Category</button>
            </div>
        </form>
        <h4>Delete a category</h4>
        <form role="form" method="post" action="<?php echo base_url() . 'adminaction/deletecategory'?>">
          <div class="form-group">
              <label>Select category to delete</label>
              <select class="form-control" name="catid" style="width:300px">
                <?php foreach ($categories as $cat): ?>
                  <option value="<?php echo $cat->id; ?>"><?php echo $cat->name; ?></option>
                <?php endforeach; ?>
              </select>
              <label name="delete-ideas" class="checkbox" for="checkbox1">
                <input type="checkbox" value="" id="checkbox1" name="ideas" data-toggle="checkbox">
                Delete category's ideas
              </label>
          </div>
          <div class="form-group">
              <button name="delete-category" type="submit" class="btn btn-primary">Delete category</button>
          </div>
        </form>
        <h4>Change names</h4>
        <form role="form" name="update-form" method="post" action="<?php echo base_url() . 'adminaction/updatecategories'?>">
            <?php foreach ($categories as $cat): ?>
              <div class="form-group">
                <input type="text" class="form-control" name="category-<?php echo $cat->id;?>" style="width:300px" value="<?php echo $cat->name;?>">
              </div>
            <?php endforeach; ?>
            <div class="form-group">
              <button name="update-names" type="submit" class="btn btn-primary">Update names</button>
            </div>
        </form>
      </div>
  </div>
</div>
</div>
</body>
</html>

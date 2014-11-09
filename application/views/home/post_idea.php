<div class="contentdiv pull-left" style="padding-left:40px;padding-right:50px;width:70%">
	<small><ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>">Feedback</a></li>
        <li class="active">Post a new idea</li>
  </ol></small>
	<?php if(@!isset($_SESSION['userid'])): ?>
	<p class="bg-danger" style="padding-left:20px;padding-top:5px;padding-bottom:5px;">You must <a href="<?php echo base_url() . 'home/login'; ?>">log in</a> to post a new idea</p>
	<?php else: ?>
	<?php if($error != "none"): ?>
	<p class="bg-danger" style="padding-left:20px;padding-top:5px;padding-bottom:5px;"><?php
	switch ($error) {
		case 'errortitle':
			echo 'Title is too short';
			break;
		case 'errorcat':
			echo 'Please, select a category';
			break;
		case 'errordesc':
			echo 'Description is too short';
			break;
	}?></p>
	<?php endif; ?>
	<form role="form" method="post" action="<?php echo base_url() . 'action/newidea'?>">
	  <div class="form-group">
	    <label for="exampleInputEmail1">Idea Title</label>
	    <input type="text" class="form-control" name="title" value="<?php if(@isset($POST['title'])) echo $POST['title'];?>">
	  </div>
	  <div class="form-group">
	  <label>Category</label>
	    <select class="form-control" name="category">
		  <option value="0">Select category...</option>
		  <?php foreach ($categories as $cat):?>
		  <option value="<?php echo $cat->id;?>" <?php if(@isset($POST['catid']) && $POST['catid'] == $cat->id) echo 'selected="selected"';?>><?php echo $cat->name;?></option>
		  <?php endforeach; ?>
		</select>
	  </div>
	  <div class="form-group">
	  <label>Description</label>
	    <textarea class="form-control" rows="4" name="description"><?php if(@isset($POST['desc'])) echo $POST['desc'];?></textarea>
	  </div>
	  <button type="submit" class="btn btn-primary">Submit Idea</button>
	</form>
	<?php endif; ?>
</div>
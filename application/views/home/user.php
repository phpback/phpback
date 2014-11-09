<div class="contentdiv pull-left" style="padding-left:40px;padding-right:50px;width:70%">	
	<small><ol class="breadcrumb">
			  <li><a href="<?php echo base_url();?>">Feedback</a></li>
			  <li>Profiles</li>
			  <li class="active"><?php echo $user->name; ?></li>
	</ol></small>
	<h5><?php echo $user->name; ?><?php if($user->isadmin): ?><span class="label label-danger" style="margin-left:7px;">Admin</span> <?php endif; ?></h5>
	<?php if(isset($_SESSION['userid']) && $user->id == $_SESSION['userid']):?>
	<div><?php echo $user->votes?> Votes left.</div>
	<?php elseif(isset($_SESSION['isadmin']) && $_SESSION['isadmin'] > 1): ?>
	<a href="<?php echo base_url() . 'admin/users/' . $user->id; ?>" target="_blank"><button type="submit" class="btn btn-danger btn-sm" style="width:130px">Ban User</button></a>
	<?php endif; ?>
	<hr>
	<?php if(isset($_SESSION['userid']) && $user->id == $_SESSION['userid']):?>
	<div id="account settings">
	<?php if($error > 0): ?>
		<p class="bg-danger" style="padding-left:20px;padding-top:5px;padding-bottom:5px;">
			<?php if($error == 1) echo "Passwords do not match";?>
			<?php if($error == 2) echo "Old password is wrong";?>
			<?php if($error == 3) echo "Password is too short";?>
		</p>
	<?php endif;?>
	<small>
		<ul class="nav nav-tabs">
	  		<li id="table4" class="active"><a onclick="showtable4('resetvotestable','table4');">Reset votes</a></li>
	  		<li id="table5"><a onclick="showtable4('changepasswordtable','table5');">Change Password</a></li>
		</ul>
		<table id="resetvotestable" class="table table-striped">
			<thead>
				<th>Idea</th>
				<th>Votes</th>
				<th></th>
			</thead>
			<tbody>
			<?php foreach($votes as $vote): ?>
				<?php $freename = str_replace(" ", "-", $vote['idea']); ?>
				<tr>
					<td><a href="<?php echo base_url() . 'home/idea/' . $vote['ideaid'] . "/$freename"; ?>" target="_blank"><?php echo $vote['idea']; ?></a></td>
					<td><?php echo $vote['number']; ?></td>
					<td><a href="<?php echo base_url() . 'action/unvote/' . $vote['id'];?>"><button type="submit" class="btn btn-warning btn-sm" style="width:130px">Delete votes</button></a></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<div id="changepasswordtable" style="display:none">
			<form role="form" method="post" action="<?php echo base_url() . 'action/changepassword'?>">
	            <div class="form-group">
	              <label>Old password</label>
	              <input type="password" class="form-control" name="old" style="width:150px">
	            </div>
	            <div class="form-group">
	              <label>New password</label>
	              <input type="password" class="form-control" name="new" style="width:150px;">
	            </div>
	            <div class="form-group">
	              <label>Repeat new password</label>
	              <input type="password" class="form-control" name="rnew" style="width:150px">
	            </div>
	            <div class="form-group">
	              <button type="submit" class="btn btn-primary">Change Password</button>
	            </div>
        	</form>
		</div>
	</small>
	</div>
	<?php endif;?>
	<small>
	<ul class="nav nav-tabs">
  		<li id="table1" class="active"><a onclick="showtable('activitytable','table1');">Activity</a></li>
  		<li id="table2"><a onclick="showtable('ideastable','table2');">Ideas <span class="badge"><?php echo count($ideas);?></span></a></li>
  		<li id="table3"><a onclick="showtable('commentstable','table3');">Comments</a></li>
	</ul>
	<div id="listing">
		<table id="activitytable" class="table table-striped">
			<thead>
		        <tr>
		          <th>Log</th>
		          <th>Date</th>
		        </tr>
		    </thead>
      		<tbody>
      			<?php foreach ($logs as $log): ?>
				<tr>
					<td>
						<?php echo $log->content; ?>
					</td>
					<td>
						<?php echo $log->date; ?>
					</td>
				</tr>
			    <?php endforeach; ?>
			</tbody>
		</table>
		<table id="ideastable" class="table table-condensed" style="display:none">
			<thead>
		        <tr>
		          <th>Idea</th>
		          <th>Category</th>
		          <th>Comments</th>
		          <th>Votes</th>
		          <th>Date</th>
		        </tr>
		    </thead>
      		<tbody>
      			<?php foreach ($ideas as $idea): ?>
      			<?php $freename = str_replace(" ", "-", $idea->title); ?>
				<tr class="<?php
				switch ($idea->status) {
					case 'considered':
						echo 'active';
						break;
					case 'declined':
						echo 'danger';
						break;
					case 'started':
						echo 'success';
						break;
					case 'planned':
						echo 'warning';
						break;
					case 'completed':
						echo 'info';
						break;
				}
				?>">
					<td>
						<a href="<?php echo base_url() . 'home/idea/' . $idea->id . "/" . $freename;?>"><?php echo $idea->title; ?></a>
					</td>
					<td>
						<?php echo $categories[$idea->categoryid]->name; ?>
					</td>
					<td>
						<?php echo $idea->comments; ?> Comments
					</td>
					<td>
						<?php echo $idea->votes; ?> Votes
					</td>
					<td>
						<?php echo $idea->date; ?>
					</td>
				</tr>
			    <?php endforeach; ?>
			</tbody>
		</table>
		<table id="commentstable" class="table table-striped" style="display:none">
			<thead>
		        <tr>
		          <th>Commented in</th>
		          <th>Date</th>
		        </tr>
		    </thead>
      		<tbody>
      			<?php foreach ($comments as $comment): ?>
      			<?php $freename = str_replace(" ", "-", $comment['idea']); ?>
				<tr>
					<td>
						<a href="<?php echo base_url() . 'home/idea/' . $comment['ideaid'] . "/" . $freename;?>"><?php echo $comment['idea']; ?></a>
					</td>
					<td>
						<?php echo $comment['date']; ?>
					</td>
				</tr>
			    <?php endforeach; ?>
			</tbody>
		</table>
	</div>
	</small>
</div>
<div class="col-md-9">
			<div class="breadcrumb-wrapper"><ol class="breadcrumb">
			  <li><a href="<?php echo base_url();?>">Feedback</a></li>
			  <li><a href="<?php echo base_url() . 'home/category/' . $idea->categoryid . "/" . str_replace(" ", "-", $categories[$idea->categoryid]->name); ?>"><?php echo $categories[$idea->categoryid]->name;?></a></li>
			  <li class="active"><?php echo $idea->title; ?></li>
			</ol></div>
			
			<div class="row">
				<div class="col-xs-12 col-sm-2">
					<div class="vote-count-box view-idea-vote">
						<span style="color:#3498DB;margin-top:-10px">
						<b><?php if($idea->votes <= 99999) {
								if($idea->votes < 1000) echo $idea->votes;
								else echo number_format($idea->votes);
							} elseif($idea->votes < 1000000){
								echo (int) ($idea->votes / 1000); echo "k";
							} else {
							echo (int) ($idea->votes / 1000000);
							$t = (int) ($idea->votes / 1000000);
							if((int) ($idea->votes / 100000) - $t*10 > 0)
								echo "," . (((int) ($idea->votes / 100000)) - $t*10);
							echo "m";
							}?>
							</b></span><br>
						<div style="margin-top:-10px"><small><?php echo $lang['label_votes']; ?></small></div>
					</div>
					<div class="dropdown view-idea-vote">
					  <button name="Vote" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" style="width:100%"><?php echo $lang['label_vote']; ?></button>
					  <span class="dropdown-arrow dropdown-arrow-inverse"></span>
					  <ul class="dropdown-menu dropdown-inverse">
					    <li><a href="<?php echo base_url() . "action/vote/1/" . $idea->id;?>">1 <?php echo $lang['label_votes']; ?></a></li>
					    <li><a href="<?php echo base_url() . "action/vote/2/" . $idea->id;?>">2 <?php echo $lang['label_votes']; ?></a></li>
					    <li><a href="<?php echo base_url() . "action/vote/3/" . $idea->id;?>">3 <?php echo $lang['label_votes']; ?></a></li>
					  </ul>
					</div>
				</div>
				<div class="col-xs-12 col-sm-10">
					<h6><?php echo $idea->title; ?></h6>
					<span style="color:#34495E"><small><?php echo $idea->content; ?></small></span>
					<div>
					<ul class="nav-pills" style="list-style:none;margin-left:-40px">
					<li style="padding-right:10px"><span class="label label-<?php
				switch ($idea->status) {
					case 'considered':
						echo 'default';
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
					case 'new':
						echo 'default';
						break;
				}
				?>"><small><?php
				switch ($idea->status) {
					case 'considered':
						echo $lang['idea_considered'];
						break;
					case 'declined':
                        echo $lang['idea_declined'];
						break;
					case 'started':
                        echo $lang['idea_started'];
						break;
					case 'planned':
                        echo $lang['idea_planned'];
						break;
					case 'completed':
                        echo $lang['idea_completed'];
						break;
					case 'new':
						echo $lang['idea_new'];
						break;
				}
				?></small></span></li>
					<li style="padding-right:10px"><small><?php echo $idea->comments;?> <?php echo $lang['label_comments']; ?></small></li>
					<li style="padding-right:10px"><a href="<?php echo base_url() . 'home/category/' . $idea->categoryid . '/' . str_replace(" ", "-", $categories[$idea->categoryid]->name); ?>"><small><?php echo $categories[$idea->categoryid]->name;?></small></a></li>
					</ul><br><br>
					<small><span class="glyphicon glyphicon-user"></span> <a href="<?php echo base_url() . 'home/profile/' . $idea->authorid . '/' . str_replace(" ", "-", $idea->user); ?>"><?php echo $idea->user; ?></a> <i><?php echo $lang['text_shared_this_idea']; ?></i> <span style='color:#555;margin-left:30px;'><?php echo $idea->date; ?></span></small>
					</div>
				</div>
			</div>
			
			<?php if(isset($_SESSION['phpback_isadmin']) && $_SESSION['phpback_isadmin']): ?>
			<div class="row">
				<div class="col-md-10 col-md-offset-2">
				<ul class="nav-pills" style="list-style:none;margin-left:-40px;">
				<li>
					<?php if($idea->status == 'new'): ?>
						<a href="<?php echo base_url() . 'adminaction/approveidea/' . $idea->id; ?>"><button type="submit" class="btn btn-success btn-sm" style="width:130px"><?php echo $lang['label_idea_approve']; ?></button></a>
					<?php elseif($idea->status != 'completed' && $idea->status != 'declined'): ?>
						<div class="dropdown">
						  <button class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" style="width:130px"><?php echo $lang['label_change_status']; ?></button>
						  <span class="dropdown-arrow dropdown-arrow-inverse"></span>
						  <ul class="dropdown-menu dropdown-inverse">
						    <li><a href="<?php echo base_url() . "adminaction/ideastatus/declined/" . $idea->id;?>"><?php echo $lang['idea_declined']; ?></a></li>
						    <li><a href="<?php echo base_url() . "adminaction/ideastatus/considered/" . $idea->id;?>"><?php echo $lang['idea_considered']; ?></a></li>
						    <li><a href="<?php echo base_url() . "adminaction/ideastatus/planned/" . $idea->id;?>"><?php echo $lang['idea_planned']; ?></a></li>
						    <li><a href="<?php echo base_url() . "adminaction/ideastatus/started/" . $idea->id;?>"><?php echo $lang['idea_started']; ?></a></li>
						    <li><a href="<?php echo base_url() . "adminaction/ideastatus/completed/" . $idea->id;?>"><?php echo $lang['idea_completed']; ?></a></li>
						  </ul>
						</div>
					<?php endif; ?>
				</li>
				<li>
					<button type="submit" class="btn btn-danger btn-sm" style="width:130px" <?php $temp = base_url() . 'adminaction/deleteidea/' . $idea->id;?> onclick="popup_sure('<?php echo $lang['text_sure_delete_idea']; ?>','<?php echo $temp; ?>');"><?php echo $lang['label_delete_idea']; ?></button>
				</li>
				<li>
					<a href="<?php echo base_url() . 'admin/users/' . $idea->authorid; ?>" target="_blank"><button type="submit" class="btn btn-danger btn-sm" style="width:130px"><?php echo $lang['label_ban_user']; ?></button></a>
				</li>

				</ul>
				</div>
			</div>
			<?php endif; ?>
			
			<?php if(isset($_SESSION['phpback_userid'])): ?>
			<div class="row">
				<div class="col-md-10 col-md-offset-2" style="margin-top:10px">
					<form role="form" method="post" action="<?php echo base_url() . 'action/comment/' . $idea->id; ?>">
						<div class="form-group">
						  <label>Comment</label>
						    <textarea class="form-control" rows="4" name="content"></textarea>
						  </div>
						  <input type="hidden" name="ideaname" value="<?php echo str_replace(" ", "-", $idea->title); ?>">
						  <button type="submit" name="commentbutton" class="btn btn-default"><?php echo $lang['label_submit']; ?></button>
					</form>
				</div>
			</div>
			<?php endif; ?>
			
			<?php foreach ($comments as $comment) : ?>
			<div class="row">
				<div class="col-md-10 col-md-offset-2">
					<div class="comment-box">
						<span class="glyphicon glyphicon-comment" style="margin-right:5px"></span>
						<a href="<?php echo base_url() . 'home/profile/' . $comment->userid . '/' . str_replace(" ", "-", $comment->user); ?>"><?php echo $comment->user; ?></a>
						<span style="margin-left:15px;color:#555"><?php echo $comment->date; ?></span>
							<span style="margin-left:15px;margin-right:5px">
							<?php if(isset($_SESSION['phpback_isadmin']) && $_SESSION['phpback_isadmin']): ?>
							<?php $temp = base_url() . 'adminaction/deletecomment/' . $comment->id; ?>
								<a style="color:#E25F5F" href="#" onclick="popup_sure('<?php echo $lang['text_sure_delete_comment']; ?>','<?php echo $temp; ?>');"><i><small><?php echo $lang['label_delete_comment']; ?></small></i></a>
							<?php else: ?>
								<a style="color:#E25F5F" href="<?php echo base_url() . 'action/flag/'. $comment->id . '/' . $idea->id . '/' . str_replace(" ", "-", $idea->title);?>"><i><small><?php echo $lang['text_flag_comment']; ?></small></i></a>
							<?php endif;?>
							</span>
						<div class="comment-text">
							<?php echo $comment->content;?>
						</div>
					 </div>
				</div>
			</div>
			<?php endforeach; ?>
</div>

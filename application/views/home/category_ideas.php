	<div class="col-md-9">
	
		<div class="breadcrumb-wrapper"><ol class="breadcrumb">
			<li><a href="<?php echo base_url();?>">Feedback</a></li>
			<li class="active"><?php echo $category->name; ?></li>
		</ol></div>
		<div>
			<h5 style="color:#2C3E50;"><?php echo $category->name; ?></h5>
			<span style="color:#34495E"><small><?php echo $category->description; ?></small></span>
		</div>
		<table id="ideastable" class="table table-condensed">
		<thead>
			<tr>
			  <th><small><?php echo $lang['label_idea']; ?> <a id="order-by--idea" href="<?= $category->url .'/title/'; echo ($type == 'desc') ? 'asc' : 'desc';?>"><span class="glyphicon glyphicon-chevron-<?php echo ($type == 'desc') ? 'down' : 'up'; ?>" style="margin-left:4px"></span></a></small></th>
			  <th><small><?php echo $lang['label_votes']; ?> <a id="order-by--votes" href="<?= $category->url .'/votes/'; echo ($type == 'desc') ? 'asc' : 'desc';?>"><span class="glyphicon glyphicon-chevron-<?php echo ($type == 'desc') ? 'down' : 'up'; ?>" style="margin-left:4px"></span></a></small></th>
			  <th><small><?php echo $lang['label_comments']; ?></small></th>
			  <th><small><?php echo $lang['label_date']; ?> <a id="order-by--date" href="<?= $category->url .'/id/'; echo ($type == 'desc') ? 'asc' : 'desc';?>"><span class="glyphicon glyphicon-chevron-<?php echo ($type == 'desc') ? 'down' : 'up'; ?>" style="margin-left:4px"></span></a></small></th>
			</tr>
		</thead>
		</table>
		
		<?php foreach ($ideas as $idea): ?>
			<div class="row">
				<div class="col-xs-4 col-sm-2">
					<div class="vote-count-box">
						<span style="color:#3498DB;"><b class="result-idea--votes">
						<?php if($idea->votes <= 99999) {
								if($idea->votes < 1000) echo $idea->votes;
								else echo number_format($idea->votes);
							} elseif($idea->votes < 1000000){
								echo (int) ($idea->votes / 1000); echo "K";
							} else {
							$t = (int) ($idea->votes / 1000000);
							echo $t;
							if((int) ($idea->votes / 100000) - $t*10 > 0)
								echo "," . (((int) ($idea->votes / 100000)) - $t*10);
							echo "M";
							}?>
						</b></span>
						<br><div style="margin-top:-10px;font-size:14px"><?php echo $lang['label_votes']; ?></div>
					</div>
					<div class="vote-label">	
						<span class="label label-<?php
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
						}
						?> result-idea--status" style="font-size:12px"><?php
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
						}
						?></span>
					</div>
				</div>
				<div class="col-xs-8 col-sm-10">
					<a class="result-idea--title" href="<?= $idea->url;?>"><?= $idea->title; ?></a>
					<div style="margin-top:-10px">
					<small class="result-idea--description">
						<?php
						if(strlen($idea->content) > 200){
							echo substr($idea->content, 0, 200);
							echo "...";
						}
						else{
							echo $idea->content;
						}
						?>
					</small></span>
					</div>
					<div style="margin-top:-10px">
						<ul class="nav-pills" style="list-style:none;margin-left:-40px">
							<li><small class="result-idea--comments"><?php echo $idea->comments; ?> <?php echo $lang['label_comments']; ?></small></li>
						</ul><br><br>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
			<ul class="pagination">
			  <li><a class="pagination--previous" href="<?php if($page > 1) echo $category->url . "/$order/$type/" . ($page-1); else echo '#'; ?>">&laquo;</a></li>
			  <?php for($i=1;$i<=$pages;$i++){ ?>
			  	 <?php if($i == $page): ?>
			  	 	<li class="active"><a class="pagination--current" href=""><?php echo $i;?></a></li>
			  	 <?php else:?>
			  	 	<li><a class="pagination--page" href='<?php echo $category->url . "/$order/$type/$i"; ?>'><?php echo $i;?></a></li>
			  	 <?php endif;?>

			  <?php } ?>
			  <li><a class="pagination--next" href="<?php  if($page < $pages) echo $category->url . "/$order/$type/" . ($page+1); else echo '#'; ?>">&raquo;</a></li>
			</ul>
</div>

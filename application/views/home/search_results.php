<div class="contentdiv pull-left" style="padding-left:40px;padding-right:50px;width:70%">
<small><ol class="breadcrumb" style="margin-bottom:20px">
			  <li><a href="<?php echo base_url();?>">Feedback</a></li>
			  <li class="active"><?php echo $lang['label_search']; ?></li>
</ol></small>
<?php
if(!count($ideas)) echo "<h3><small>" . $lang['text_nothing_found'] . "</small></h3>";
?>
<?php foreach ($ideas as $idea): ?>
			<?php $freename = str_replace(" ", "-", $idea->title); ?>
			<div class="row" style="margin-bottom:10px">
				<div class="pull-left" style="margin-right:25px">
					<div style="width:60px;height:50px;text-align:center;border-style:solid;border-width:1px;border-color:#3498DB;border-radius:5px;padding-top:4px;margin-bottom:2px">
						<span style="color:#3498DB;"><b>
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
							}?></b></span><br><div style="margin-top:-10px;font-size:14px"><?php echo $lang['label_votes']; ?></div>
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
				?>" style="font-size:12px"><?php
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
				<div style="margin-top:-10px;margin-left:70px">
					<a href="<?php echo base_url() . 'home/idea/' . $idea->id . "/" . $freename;?>"><?php echo $idea->title; ?></a>
					<div style="margin-top:-10px">
					<small>
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
					<li style="padding-right:5px"><small><?php echo $idea->comments; ?> <?php echo $lang['label_comments']; ?></small></li>
					</ul><br><br>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
</div>
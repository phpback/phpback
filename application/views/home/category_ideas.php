<div class="contentdiv pull-left" style="padding-left:40px;padding-right:50px;width:70%">
			<small><ol class="breadcrumb">
			  <li><a href="<?php echo base_url();?>">Feedback</a></li>
			  <li class="active"><?php echo $categories[$id]->name; ?></li>
			</ol></small>
			<div class="row" style="margibottomn-:20px;">
				<h5 style="color:#2C3E50;"><?php echo $categories[$id]->name; ?></h5>
				<span style="color:#34495E"><small><?php echo $categories[$id]->description; ?></small></span>
				<?php $freename = str_replace(" ", "-", $categories[$id]->name); ?>
			</div>
			<table id="ideastable" class="table table-condensed">
			<thead>
		        <tr>
		          <th><small>Idea <a href="<?php echo base_url() . 'home/category/'. ($id) .'/'. $freename .'/title/'; echo ($type == 'desc') ? 'asc' : 'desc';?>"><span class="glyphicon glyphicon-chevron-<?php echo ($type == 'desc') ? 'down' : 'up'; ?>" style="margin-left:4px"></span></a></small></th>
		          <th><small>Votes <a href="<?php echo base_url() . 'home/category/'. ($id) .'/'. $freename .'/votes/'; echo ($type == 'desc') ? 'asc' : 'desc';?>"><span class="glyphicon glyphicon-chevron-<?php echo ($type == 'desc') ? 'down' : 'up'; ?>" style="margin-left:4px"></span></a></small></th>
		          <th><small>Comments</small></th>
		          <th><small>Date <a href="<?php echo base_url() . 'home/category/'. ($id) .'/'. $freename .'/id/'; echo ($type == 'desc') ? 'asc' : 'desc';?>"><span class="glyphicon glyphicon-chevron-<?php echo ($type == 'desc') ? 'down' : 'up'; ?>" style="margin-left:4px"></span></a></small></th>
		        </tr>
		    </thead>
		    </table>
		<?php foreach ($ideas as $idea): ?>
			<?php $freename = str_replace(" ", "-", $idea->title); ?>
			<div class="row" style="margin-bottom:10px">
				<div class="pull-left" style="margin-right:25px;">
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
							}?>
						</b></span><br><div style="margin-top:-10px;font-size:14px">Votes</div>
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
						echo 'Considered';
						break;
					case 'declined':
						echo 'Declined';
						break;
					case 'started':
						echo 'Started';
						break;
					case 'planned':
						echo 'Planned';
						break;
					case 'completed':
						echo 'Completed';
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
					<ul class="nav-pills" style="list-style:none;margin-left:-30px">
					<li><small><?php echo $idea->comments; ?> Comments</small></li>
					</ul><br><br>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		<?php $freename = str_replace(" ", "-", $categories[$id]->name); ?>
			<ul class="pagination">
			  <li><a href="<?php  if($page > 1) echo base_url() . "home/category/$id/$freename/$order/$type/" . ($page-1); else echo '#'; ?>">&laquo;</a></li>
			  <?php for($i=1;$i<=$pages;$i++){ ?>
			  	 <?php if($i == $page): ?>
			  	 	<li class="active"><a href=""><?php echo $i;?></a></li>
			  	 <?php else:?>
			  	 	<li><a href='<?php echo base_url() . "home/category/$id/$freename/$order/$type/$i"; ?>'><?php echo $i;?></a></li>
			  	 <?php endif;?>
			  	 
			  <?php } ?>
			  <li><a href="<?php  if($page < $pages) echo base_url() . "home/category/$id/$freename/$order/$type/" . ($page+1); else echo '#'; ?>">&raquo;</a></li>
			</ul>
</div>
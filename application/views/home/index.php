		<div class="contentdiv pull-left" style="padding-left:40px;padding-right:50px;width:70%">
			<small>
			<ol class="breadcrumb">
			  <li class="active">Feedback</li>
			</ol>
			</small>
			<div class="row" style="margin-top:10px;">
			<h4>Welcome to our FeedBack</h4>
				<div>Here you can suggest ideas to improve our services or vote ideas from other people.</div>
			</div>
			<div class="row" style="margin-bottom:20px; margin-top:20px;">
				<div class="col-md-5" style="border-style:solid;border-width:1px;border-radius:5px;border-color:#3498DB;">
					<h6><?php echo $lang['last_completed_ideas']; ?></h6>
					<small>
					<table class="table table-hover">
						<?php foreach ($ideas['completed'] as $idea): ?>
						<?php $freename = str_replace(" ", "-", $idea->title); ?>
							<tr>
								<td>
									<span class="label label-info" style="margin-right:5px"><?php echo $lang['idea_completed']; ?></span><a href="<?php echo base_url() . 'home/idea/' . $idea->id . "/" . $freename;?>"><?php echo $idea->title; ?></a>
								</td>
							</tr>
						<?php endforeach; ?>
					</table>
					</small>
				</div>
				<div class="col-md-5 col-md-offset-1" style="border-style:solid;border-width:1px;border-radius:5px;border-color:#2ECC71;">
					<h6><?php echo $lang['last_started_ideas']; ?></h6>
					<small>
						<table class="table table-hover">
							<?php foreach ($ideas['started'] as $idea): ?>
							<?php $freename = str_replace(" ", "-", $idea->title); ?>
								<tr>
									<td>
										<span class="label label-success" style="margin-right:5px"><?php echo $lang['idea_started']; ?></span><a href="<?php echo base_url() . 'home/idea/' . $idea->id . "/" . $freename;?>"><?php echo $idea->title; ?></a>
									</td>
								</tr>
							<?php endforeach; ?>
						</table>
					</small>
				</div>
			</div>
			<div class="row">
				<div class="col-md-5" style="border-style:solid;border-width:1px;border-radius:5px;border-color:#F1C40F;">
					<h6><?php echo $lang['last_planned_ideas']; ?></h6>
					<small>
					<table class="table table-hover">
						<?php foreach ($ideas['planned'] as $idea): ?>
						<?php $freename = str_replace(" ", "-", $idea->title); ?>
							<tr>
								<td>
									<span class="label label-warning" style="margin-right:5px"><?php echo $lang['idea_planned']; ?></span><a href="<?php echo base_url() . 'home/idea/' . $idea->id . "/" . $freename;?>"><?php echo $idea->title; ?></a>
								</td>
							</tr>
						<?php endforeach; ?>
					</table>
					</small>
				</div>
				<div class="col-md-5 col-md-offset-1" style="border-style:solid;border-width:1px;border-radius:5px;border-color:#95A5A6;" >
					<h6><?php echo $lang['last_considered_ideas']; ?></h6>
					<small>
					<table class="table table-hover">
						<?php foreach ($ideas['considered'] as $idea): ?>
						<?php $freename = str_replace(" ", "-", $idea->title); ?>
							<tr>
								<td>
									<span class="label label-default" style="margin-right:5px"><?php echo $lang['idea_considered']; ?></span><a href="<?php echo base_url() . 'home/idea/' . $idea->id . "/" . $freename;?>"><?php echo $idea->title; ?></a>
								</td>
							</tr>
						<?php endforeach; ?>
					</table>
					</small>
				</div>
			</div>
		</div>


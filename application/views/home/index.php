	<div class="col-md-9">
	
		<small>
		<ol class="breadcrumb">
		  <li class="active">Feedback</li>
		</ol>
		</small>
		<div>
			<h4 id="welcome-mesagge--title"><?= $welcomeTitle; ?></h4>
			<div id="welcome-mesagge--text"><?= $welcomeDescription; ?></div>
		</div>
		
		<br/>
		
		<div class="col-md-6">
			<div class="ideas-completed">
				<h6><?= $lang['last_completed_ideas']; ?></h6>
				<small>
				<table class="table table-hover">
					<?php foreach ($ideas['completed'] as $idea): ?>
						<tr>
							<td>
								<span class="label label-info completed-idea--tag" style="margin-right:5px">
									<?= $lang['idea_completed']; ?>
								</span>
								<a href="<?= $idea->url; ?>">
									<?= $idea->title; ?>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
				</small>
			</div>
			<div class="ideas-planned">
				<h6><?= $lang['last_planned_ideas']; ?></h6>
				<small>
				<table class="table table-hover">
					<?php foreach ($ideas['planned'] as $idea): ?>
						<tr>
							<td>
								<span class="label label-warning planned-idea--tag" style="margin-right:5px">
									<?= $lang['idea_planned']; ?>
								</span>
								<a href="<?= $idea->url; ?>">
									<?= $idea->title; ?>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
				</small>
			</div>
		</div>
		
		<div class="col-md-6">
			<div class="ideas-started">
				<h6><?= $lang['last_started_ideas']; ?></h6>
				<small>
					<table class="table table-hover">
						<?php foreach ($ideas['started'] as $idea): ?>
							<tr>
								<td>
									<span class="label label-success started-idea--tag" style="margin-right:5px">
										<?= $lang['idea_started']; ?>
									</span>
									<a href="<?= $idea->url; ?>">
										<?= $idea->title; ?>
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					</table>
				</small>
			</div>
			<div class="ideas-considered">
				<h6><?= $lang['last_considered_ideas']; ?></h6>
				<small>
				<table class="table table-hover">
					<?php foreach ($ideas['considered'] as $idea): ?>
						<tr>
							<td>
								<span class="label label-default considered-idea--tag" style="margin-right:5px">
									<?= $lang['idea_considered']; ?>
								</span>
								<a href="<?= $idea->url; ?>">
									<?= $idea->title; ?>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
				</small>
			</div>
		</div>
	</div>

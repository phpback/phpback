	<div class="pull-left col-md-7 col-md-offset-1">
		<div class="col-md-11">
			<small>
			<ol class="breadcrumb">
			  <li class="active">Feedback</li>
			</ol>
			</small>
			<div>
				<h4 id="welcome-mesagge--title">Welcome to our FeedBack</h4>
				<div id="welcome-mesagge--text">Here you can suggest ideas to improve our services or vote ideas from other people.</div>
			</div>
			<div>
				<div class="col-md-5" style="border-style:solid;border-width:1px;border-radius:5px;border-color:#3498DB;">
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
				<div class="col-md-5 col-md-offset-1" style="border-style:solid;border-width:1px;border-radius:5px;border-color:#2ECC71;">
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
			</div>
			<div>
				<div class="col-md-5" style="border-style:solid;border-width:1px;border-radius:5px;border-color:#F1C40F;">
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
				<div class="col-md-5 col-md-offset-1" style="border-style:solid;border-width:1px;border-radius:5px;border-color:#95A5A6;" >
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
	</div>

<div class="pull-right" style="border-style:solid;border-width:1px;border-color:#C0C0C0;padding-left:10px;padding-top:8px;width:350px;margin-right:20px;" >
			<div class="pull-left">
			<div id="search">
	   			<form class="navbar-form navbar-right" action="<?php echo base_url() . 'home/search'; ?>" method="POST">
			      <div class="form-group">
			        <div class="input-group">
			          <input class="form-control" name="query" id="navbarInput-01" type="search" placeholder="Search">
			          <span class="input-group-btn">
			            <button type="submit" class="btn"><span class="fui-search"></span></button>
			          </span>            
			        </div>
			      </div>               
			    </form>

			</div>
			<div id="postidea" style="margin-top:10px;">
				<a href="<?php echo base_url() . 'home/postidea'; ?>"><button type="button" class="btn btn-primary btn-xs">Post a new idea 
					<span class="glyphicon glyphicon-plus" style="padding-left:20px"></span>
				</button></a>
			</div>
			<div id="categories">
				<h6>Categories</h6>
				<ul class="nav nav-pills nav-stacked">
				 <?php foreach($categories as $cat){ 
				 	 $freename = str_replace(" ", "-", $cat->name);
				 ?>
				 	<li <?php if(!$cat->ideas) echo 'class="disabled"';?>><a href="<?php echo base_url() . 'home/category/' . $cat->id . '/' . $freename ; ?>"><?php echo $cat->name; ?><span class="badge"><?php echo $cat->ideas; ?></span></a></li>
				 <?php } ?>
				</ul>
				<br>
			</div>
			</div>
</div>

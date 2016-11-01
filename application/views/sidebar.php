<div class="col-md-4 visible-lg">
	<div class="white-content">
	
	<?php
	if(is_admin()) {
		echo '<div class="white-content">';
		print '<a href="/admin" class="btn btn-large btn-danger">Admin Panel</a>';
		echo '</div>';
	}
	?>
		
	<?php
	if(is_user_logged_in()) :
	?>	
	<h3><b class="icon-user" style="margin-top:7px;"></b> <?=_('My Account') ?></h3>
	<ul class="nav nav-list">
	<li><?php echo anchor(base_url() . 'users', '<i class="glyphicon glyphicon-chevron-right"></i>'._('My Profile')); ?></li>
	<li><?php echo anchor(base_url() . 'users/mylistings', '<i class="glyphicon glyphicon-chevron-right"></i>'._('My Listings')); ?></li>
	<li><?php echo anchor(base_url() . 'users/inbox', '<i class="glyphicon glyphicon-chevron-right"></i>'._('Messages')); ?></li>
	<li><?php echo anchor(base_url() . 'users/bids', '<i class="glyphicon glyphicon-chevron-right"></i>'._('Bids Made')); ?></li>
	<li><?php echo anchor(base_url() . 'users/offers', '<i class="glyphicon glyphicon-chevron-right"></i>'._('Offers Received')); ?></li>
	<li><?php echo anchor(base_url() . 'users/logout', '<i class="glyphicon glyphicon-chevron-right"></i>'._('Logout')); ?></li>
	</ul>
	
	<br />
	<center>
	<a href="/users/profile/<?php echo UsersModel::current_username($this->session->userdata('loggedIn')) ?>" class="btn btn-xs btn-default">View My Profile</a>
	</center>
	<?php else: ?>	
		
	<h3><b class="icon-user" style="margin-top:7px;"></b> Login/Join</h3>
	<?php if(isset($login_message)) echo $login_message; ?>
	<form method="post" action="/users/login" class="form">
		<input type="text" name="uname" placeholder="username" class="form-control" /><br/>
		<input type="password" name="upwd" placeholder="****" class="form-control" /><br/>
		<input type="submit" name="sbLogin" value="<?=_('Login') ?>" class="btn btn-inverse"/>
		<input type="submit" name="sbJoin" id="sbJoin" value="<?=_('Join Now') ?>" class="btn"/>
	</form>

	
	<?php endif; ?>
	
	<hr/>
	
	<div style="clear:both;"></div>
	</div>
</div>

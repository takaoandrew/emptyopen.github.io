<?php require_once ('header.php'); ?>


<div class="page-header white-content">
	<h1>Admin -> Payment Settings</h1>
	<?php require_once 'admin-menu.php'; ?>
</div>

<div class="white-content">

	<?php if(isset($form_message)) print $form_message; ?>
	
	<form method="post" action="" class="form form-horizontal">
	<div class="row">
	<div class="col-xs-12 col-md-6">
		
		<dl>
			<dt><label>Listing Fee:</label></dt><dd> <input type="text" name="listing_fee" value="<?=get_option('listing_fee')?>" class="form-control"/></dd>
			<dt><label>Featured Fee:</label></dt><dd> <input type="text" name="featured_fee" value="<?=get_option('featured_fee')?>" class="form-control"/></dd>
			<dt><label>PayPal Email:</label></dt><dd> <input type="text" name="paypal_email" value="<?=get_option('paypal_email')?>" class="form-control"/></dd>
			<dt><label>Enable Paypal:</label></dt>
			<dd> 
				<input type="radio" name="paypal_enable" value="Yes" <?=get_option('paypal_enable') == 'Yes' ? 'checked' : ''; ?>/> Yes 
				<input type="radio" name="paypal_enable" value="No" <?=get_option('paypal_enable') == 'No' ? 'checked' : ''; ?>/> No	
			</dd>
			
		</dl>
		
	</div><!-- left side form -->
	<div class="col-xs-12 col-md-6">
		<dl>
			<dt><label>Stripe Private Key:</label></dt><dd> <input type="text" name="stripe_private" value="<?=get_option('stripe_private')?>" class="form-control"/></dd>
			<dt><label>Stripe Public Key:</label></dt><dd> <input type="text" name="stripe_public" value="<?=get_option('stripe_public')?>" class="form-control"/></dd>
			<dt><label>Enable Stripe:</label></dt>
				<dd>
				<input type="radio" name="stripe_enable" value="Yes" <?=get_option('stripe_enable') == 'Yes' ? 'checked' : ''; ?>/> Yes 
				<input type="radio" name="stripe_enable" value="No" <?=get_option('stripe_enable') == 'No' ? 'checked' : ''; ?>/> No
				</dd>
			<dt>&nbsp;</dt><dd><input type="submit" name="sb" value="Update Payment Settings" class="btn btn-green"/></dd>
		</dl>

	</div><!-- right side form -->
	</div><!-- ./row -->
	</form><!-- ./form -->
</div>


<?php require_once ('footer.php'); ?>
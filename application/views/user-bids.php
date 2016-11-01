<?php require_once ('header.php'); ?>

<div class="col-xs-12 col-md-8">
	
<div class="page-header white-content">
	<h3><?= _('Bids you made') ;?></h3>
</div>

<div class="white-content">
	<?php if(!isset($msg)) { ?>
	<div class="table-responsive">
	<table class="table table-bordered table-hover table-striped">
	    <thead>
	        <tr>
	            <th><?=('TO LISTING')?></th>
	            <th><?=('OWNER')?></th>
	            <th><?=('DATE')?></th>
	            <th><?=('AMOUNT')?></th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php
	        foreach($bids as $b) {
	            
	            echo '<tr>
	                       <td><a href="/listings/'.$b->listingID.'/'.url_title($b->listing_title).'">'.$b->listing_url.'</a></td>
	                       <td><a href="/users/profile/'.url_title($b->username).'">'.$b->username.'</a></td>
	                       <td>'.date("jS F Y", $b->bid_date).'</td>
	                       <td>$'.$b->amount.'</td>
	                   </tr>';
	        }
	        ?>
	    </tbody>
	</table>
	</div>
	<?php }else{ echo $msg; } ?>
	
</div>

</div>

<?php require_once 'sidebar.php'; ?>

<?php require_once ('footer.php'); ?>
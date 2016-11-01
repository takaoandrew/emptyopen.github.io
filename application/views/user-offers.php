<?php require_once ('header.php'); ?>

<div class="col-xs-12 col-md-8">
	
<div class="page-header white-content">
	<h3><?= _('Offers Received') ;?> 
	<a href="#" id="sold-popover" class="btn btn-mini btn-danger" data-toggle="popover" title="WARNING" data-content="<?=_('Before Setting a transaction as SOLD make sure actually you have received the payment and all related 
	transactions like moving the domain/website are complete. You will be unable to change the status later.'); ?>" data-original-title="WARNING">Info</a></h3>
</div>

<div class="white-content">

	<?php if(!isset($msg)) { ?>
	<div class="table-responsive">
	<table class="table table-bordered table-hover table-striped">
	    <thead>
	        <tr>
	            <th><?=('TO LISTING')?></th>
	            <th><?=('FROM')?></th>
	            <th><?=('DATE')?></th>
	            <th><?=('AMOUNT')?></th>
	            <th>&nbsp;</th>
	        </tr>
	    </thead>
	    <tbody>
	        <?php
	        foreach($bids as $b) {
	            if(!isset($complete[$b->listingID])) {
	               $complete[$b->listingID] = false;
                }
                
	            echo '<tr>
	                       <td><a href="/listings/'.$b->listingID.'/'.url_title($b->listing_title).'">'.$b->listing_url.'</a></td>
	                       <td><a href="/users/profile/'.url_title($b->username).'">'.$b->username.'</a></td>
	                       <td>'.date("jS F Y", $b->bid_date).'</td>
	                       <td>$'.$b->amount.'</td>
	                       <td>';
	            
                if($b->sold == 'N') {
                    echo '<a href="/users/offers/reject/'.$b->bidID.'" class="btn btn-xs btn-danger" onclick="return confirm(\'Are you sure you want to REJECT this bid?\')">Reject</a><br/>
                               <a href="/users/offers/sold/'.$b->listingID.'" class="btn btn-xs btn-success" onclick="return confirm(\'Are you sure you want to set this listing as SOLD?\')">SOLD</a>';
                }else{
                    if(!$complete[$b->listingID]) {
                    echo 'COMPLETE<br/>
                          ' . date("jS F Y", $b->sold_date);
                    $complete[$b->listingID] = true;      
                    }
                }           
                	                           
	            echo  '</td>
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
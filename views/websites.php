<?php require_once ('header.php'); ?>

<?php require_once 'sidebar-websites.php'; ?>

<div class="col-xs-12 col-md-9">
	
	<div class="white-content">
	<h4 class="text-yellow" style="margin:0;padding:0;"><?php echo $filter_title; ?></h4>
    </div>
	
	<div class="listings-all">
	<?php if(count($listings)): ?>
	    
        <div class="white-content">
	    <?php
	    foreach($listings as $l):
        ?>    
        
        <div class="row">
	    	<div class="col-xs-8">
	            <?php echo '<a href="/listings/' . $l->listingID . '/' . url_title($l->listing_title) . '" class="url-listing-title"><i class="icon icon-tag"></i> ' . $l->listing_url . '</a>';?>
	            <br /><i class="icon icon-calendar"></i> <span class="muted">Expires in <?=(now() > $l->list_expires) ? 'Closed' : timespan(now(),$l->list_expires)?></span><br/><br/>
	            <?=$l->listing_title;?>
	            <br/><br/>
	            <div class="bottom-info">
	                <span class="text-muted"><?=_('Estabilished') ?></span> <?=date("M Y", $l->site_age);?>
	                <span class="text-muted"><?=_('Google PR') ?></span> <?= $l->pagerank;?>
	                <span class="text-muted"><?=_('Revenue Avg.') ?></span> <?=$l->rev_avg;?>
	                <span class="text-muted"><?=_('Traffic Avg.') ?></span> <?=$l->traffic_avg_visits;?>  
	            </div>
	        </div>
        
	        <div class="col-xs-4">
	            <?php 
	        	//get latest bid
	            $last_bid = $this->db->query("(SELECT amount FROM bids WHERE bid_listing = " . $l->listingID . " 
	            								ORDER BY bidID DESC LIMIT 1)");
	            $last_bid = $last_bid->row();
	            ?>
	            
	            <div class="sidebar-item-title text-green"><?php echo _('Bidding Starts') ?></div>
		        <div class="sidebar-item">$<?= $l->starting_bid ?></div>
		    
		        <div class="sidebar-item-title text-green"><?php echo _('Current Bid') ?></div>
		        <div class="sidebar-item"><?= ($last_bid) ? '$' . $last_bid->amount : 'None'; ?></div>

		        <div class="sidebar-item-title text-green"><?php echo _('Buy It Now') ?></div>
		        <div class="sidebar-item">$<?= $l->bin ?></div>
	        </div>
	    </div>
        
        <div style="clear:both;height:20px;"></div>
        <hr/>
        <?php    
        endforeach;
	    ?>
	    </div>   
        
	    <?php
	    if($total_pages != 0) {
	        echo '<ul class="pagination">';
  
	        for($i = 0; $i <= $total_pages; $i++) {
                echo '<li><a href="/'.$list_type.'/view/'.$uri_param.'/page/'.($i+1).'">'.($i+1).'</a></li>'; 
	        }
            
            echo '</ul>';
	    }
	    ?>
	</div>
	    
	<?php else: ?>
	    
	    <div class="text-warning white-content"><?=_('No listings to show.') ?></div>
	    
	<?php endif; ?>	

</div>

</div>
<div style="clear:both"></div>

<?php require_once ('footer.php'); ?>
<?php require_once ('header.php'); ?>

<div class="col-xs-12 col-md-8">
	
<div class="page-header white-content">
	<?php if(isset($error)) 
	{
		echo $error;
	}else{
	if(!count($user)) {
		echo _('-User details could not be fetched or user does not exist');
	}else{
	?>
	
	<div class="pull-left">
	<?php if(!empty($user->photo)) : ?>
		<br />
        <img src="/uploads/<?=$user->photo?>" alt="" width="48" height="48" style="margin:0 10px 0 0"/>
    <?php else: ?>
        <img src="/img/nophoto.jpg" alt="" width="48" height="48" style="float:left;padding:5px 10px 5px 0px;" />
    <?php endif; ?>
    </div>
    <div class="pull-left">
	<h1><?php echo $user->username ?> <a href="/users/message/<?=$user->userID?>" style="font-size:16px;">(<?=_('Contact User') ?>)</a></h1> 
	</div>
	<div class="pull-right">
	<br />
	<h4><?php echo _("Bids made: ") . $tbids . _('<br/>Listings Started: ') . $tl; ?></h4>
	</div>
	<div style="clear:both;"></div>
	<?php echo ($user->about == '') ? '' : '<br/><div class="well">' . $user->about . '</div>'; ?>

</div>

<div class="white-content">
	<h2>User Listings</h2>
</div>

<div class="white-content">
	<?php
	if(count($listings)) {
		?>
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
                <span class="muted"><?=_('Estabilished') ?></span> <?=date("M Y", $l->site_age);?>
                <span class="muted"><?=_('Google PR') ?></span> <?= $l->pagerank;?>
                <span class="muted"><?=_('Revenue Avg.') ?></span> <?=$l->rev_avg;?>
                <span class="muted"><?=_('Traffic Avg.') ?></span> <?=$l->traffic_avg_visits;?>  
            </div>
        </div>
        
        <div class="col-xs-4">
            <?php 
        	//get latest bid
            $last_bid = $this->db->query("(SELECT amount FROM bids WHERE bid_listing = " . $l->listingID . " 
            								ORDER BY bidID DESC LIMIT 1)");
            $last_bid = $last_bid->row();
            
            printf("<h5><i class='icon-chevron-right'></i> Starting BID: $%.2f</h5>", $l->starting_bid);

			if(!$last_bid) 
				echo 'No Bids<br/>';
			else
				printf("<h5><i class='icon-chevron-right'></i> Current BID: $%.2f</h5>", $last_bid->amount);

			printf("<h5><i class='icon-chevron-right'></i> BIN: $%.2f</h5>", $l->bin);

            ?>
        </div>
        </div><!--row -->

        <div style="clear:both;height:20px;"></div>
        <hr/>
        <?php    
        endforeach;
	    ?>
		<?php 
	}else{
		print _('This user did not create any listings');
	}
	}
	}
	?>
</div>

</div>

<?php require_once 'sidebar.php'; ?>

<?php require_once ('footer.php'); ?>
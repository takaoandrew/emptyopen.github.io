<?php require_once 'header.php'; ?>

</div>
<div class="homepage-img">
<div class="container">
<div class="home-intro">
    <h2><?=_('"This is the right place for selling<br/> Websites &amp; Web Domains"') ?></h2>
    <br/>
    
    <a href="/websites/view/featured" class="btn btn-lg btn-primary"><?=_('Buy Websites') ?></a>
    <a href="/users/newlisting" class="btn btn-lg btn-primary"><?=_('Sell Websites') ?></a>
    
</div>
</div>
</div><!--homepage img-->

<center><h2>Featured Listings</h2></center>

<div class="container">
<div class="listings-all">
<div class="white-content">
    <?php if(count($listings)): ?>
    <div class="row" style="padding: 20px;">
    <?php
    $i = 0;
    foreach($listings as $l):
    $i++;
    ?>    
    <div class="col-xs-12 col-md-6">
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
    </div><!-- span -->
    <?php    
    if($i%2==0) echo '<div style="clear:both;height:20px;"></div>';
    endforeach;
    ?>
    </div><!-- row -->
<?php endif ?>

<center>
    <a class="btn btn-primary" href="/websites/view/featured">Featured Websites</a>
    <a class="btn btn-primary" href="/websites/view/new-listings">Latest Websites</a>
    <a class="btn btn-primary" href="/domains/view/featured">Featured Domains</a>
    <a class="btn btn-primary" href="/domains/view/new-listings">Latest Domains</a>
</center>
</div>
</div>  
</div>


<?php require_once 'footer.php'; ?>
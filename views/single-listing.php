<?php require_once ('header.php'); ?>

<div class="col-xs-12 col-md-9">

<div style="margin:20px 0 0 0;background:white;padding:20px;border:1px solid #E2E2E2;box-shadow: 0 0 3px 0 rgba(0,0,0,0.075)">
    <div class="row">
        <div class="col-xs-12">
            <h2><?php if($l) { echo !empty($l->listing_title) ? $l->listing_title : _('Title not added yet'); } ?></h2>
            <p style="font-size:16px;"><?php if($l) echo '<a href="/websites/gotoadd/'.$l->listingID.'" target="_blank"><i class="glyphicon glyphicon-share"></i> '.$l->listing_url.'</a>'; ?></p>
            <hr />
            
            <center>
            <img src="http://free.pagepeeker.com/v2/thumbs.php?size=x&url=<?php if($l) echo $l->listing_url; ?>" class="img-responsive"/>
            </center>
            <hr />
        </div>
    
    
        <hr />
        
        <div class="col-xs-6">
        <span class="label label-primary">
            <?php echo _('<i class="glyphicon glyphicon-flag"></i>Auction Status : ') .$lstatus; ?>
        </span>
        <span class="label label-primary">
            <?php echo _('<i class="glyphicon glyphicon-calendar"></i> Listing Expires : ') . date("jS F Y", $l->list_expires); ?>
        </span>
        </div>
        <div class="col-xs-6">
        <div class="pull-right">
        <span class="label label-primary">
        <i class="glyphicon glyphicon-tag"></i><?= ucfirst($l->list_type) ?> for sale
        </span>
        </div>
        </div>
        <div style="clear:both"></div>
        
    </div>

</div>

<div style="margin:20px 0 0 0;background:white;padding:20px;border:1px solid #E2E2E2;box-shadow: 0 0 3px 0 rgba(0,0,0,0.075)">
    
    <h3><?php echo _('Overview'); ?></h3>
    
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <div class="sidebar-item-title text-primary"><?php echo _('Site Estabilished') ?></div>
            <div class="sidebar-item"><?= date("jS F Y", $l->site_age) ?></div>
        </div>
    
        <div class="col-md-6 col-xs-12">
            <div class="sidebar-item-title text-primary"><?php echo _('Site Uniqueness') ?></div>
            <div class="sidebar-item"><?= ucwords($l->unique_) ?></div>
        </div>
        
    </div>
      
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <div class="sidebar-item-title text-primary"><?php echo _('Alexa Rank') ?></div>
            <div class="sidebar-item"><?= @number_format($l->alexa,0) . _(' Alexa'); ?></div>
        </div>

        <div class="col-md-6 col-xs-12">
            <div class="sidebar-item-title text-primary"><?php echo _('Google PR') ?></div>
            <div class="sidebar-item"><?= @number_format($l->pagerank,0) . _(' Pagerank'); ?></div>
        </div>
    </div>

</div><!-- overview -->
    
<div class="span7" style="margin:20px 0 0 0;background:white;padding:20px;border:1px solid #E2E2E2;box-shadow: 0 0 3px 0 rgba(0,0,0,0.075)">
    <h3><?php echo _('Revenue'); ?></h3>
    
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <div class="sidebar-item-title text-primary"><?php echo _('Revenue Avg.') ?></div>
            <div class="sidebar-item"><?= '$' . number_format($l->rev_avg,0) . _(' Month'); ?></div>
        </div>
        <div class="col-md-6 col-xs-12">
            <div class="sidebar-item-title text-primary"><?php echo _('Monetization Methods') ?></div>
            <div class="sidebar-item"><?php if(!empty($l->monetization)) { foreach(unserialize($l->monetization) as $monet) { echo ucwords($monet) ."<br/>";}} ?></div>
        </div>
    </div>
    
    <div class="sidebar-item-title text-primary">Details</div>
    <?php if($l) { echo !empty($l->revenue_details) ? nl2br($l->revenue_details) : _('Revenue details not added yet.'); } ?>

</div><!-- revenue -->

<div style="margin:20px 0 0 0;background:white;padding:20px;border:1px solid #E2E2E2;box-shadow: 0 0 3px 0 rgba(0,0,0,0.075)">
    <h3><?php echo _('Traffic'); ?></h3>

    <div class="sidebar-item-title text-primary"><?php echo _('Claimed Traffic') ?></div>
    <div class="sidebar-item col-xs-6" style="margin-left:0;padding-left:0;"><?= number_format($l->traffic_avg_visits,0) . _(' Visitors/Month'); ?></div>
    <div class="sidebar-item col-xs-6"><?= number_format($l->traffic_avg_views,0) . _(' Views/Month'); ?></div>
    
    <div style="clear:both;"></div>

    <div class="sidebar-item text-primary"><strong>Details</strong></div>
    <?php if($l) { echo !empty($l->traffic_details) ? nl2br($l->traffic_details) : _('Traffic details not added yet.'); } ?>

</div><!-- traffic-->

<div style="margin:20px 0 0 0;background:white;padding:20px;border:1px solid #E2E2E2;box-shadow: 0 0 3px 0 rgba(0,0,0,0.075)">
    
    <h3><?php echo _('Description'); ?></h3>
    <?php if($l) { echo !empty($l->listing_description) ? nl2br($l->listing_description) : _('Description not added yet'); } ?>

</div><!-- description -->

<div style="margin:20px 0 0 0;background:white;padding:20px;border:1px solid #E2E2E2;box-shadow: 0 0 3px 0 rgba(0,0,0,0.075)">    
    <h3><?php echo _('Comments'); ?></h3>
    <div id="movID" style="display:none;"><?=$l->listingID; ?></div>
    <?php if(is_user_logged_in()) : ?>
        <?php
        echo form_open('/listings/ajax_comment', array('class' => 'form', "id" => 'comment-form'), array('listID' => $l->listingID));
        echo form_textarea(array('name' => 'comment', 'cols' => 24, 'rows' => 6, 'class' => 'form-control required'));
        echo '<br/>';
        echo form_submit('sbComment', _('Submit comment'), 'class="btn btn-info"');
        echo form_close();
        ?>
        <div id="comment_output"></div>
        
        <hr/>
   
   <?php else: ?>
        <div class="alert alert-info"><?= _('Please login to comment') ?></div>
    <?php endif; ?>     
        
        <?php if(isset($comments) AND count($comments)) :?>
        <ul class="user_comments">
        <?php 
        $i = 0;
        foreach($comments->result() as $c) : 
        $i++;
        $border = ($i%2==1) ? '' : 'orange';
        ?>
        
        <li data-lastID="<?php echo $c->commID; ?>" class="<?=$border?>">
            <a href="/users/profile/<?php echo url_title($l->username); ?>">
            <?php if(!empty($l->photo) and file_exists('/uploads/' . $l->photo)) : ?>
                <img src="/uploads/<?=$l->photo?>" alt="" width="48" height="48" style="float:left;padding:5px 10px 5px 0px;"/>
            <?php else: ?>
                <img src="/img/nophoto.jpg" alt="" width="48" height="48" style="float:left;padding:5px 10px 5px 0px;" />
            <?php endif; ?>
            </a>
            <span class="comment_author"><b class="icon-user"></b> <?php echo '<a href="/users/profile/'.url_title($c->username).'">'.$c->username.'</a>'; ?> - <b class="icon-calendar"></b><em><?php echo date("jS F Y H:ia", $c->comm_date); ?></em></span>
            <div class="comment_content"><?php echo nl2br(wordwrap($c->comment, 80, '<br/>', TRUE)); ?></div>
            <div style="clear:both;"></div>
            <?php if($owns_listing == 'yes') { ?>
                <a href="javascript:void(0);" class="remove_c btn btn-danger btn-mini" id="rem_<?=$c->commID;?>">Remove Comment</a>
            <?php } ?>
        </li>
        <hr/>
        
        <?php endforeach; ?>
        <?php endif; ?>
    
    
</div>
</div>

<?php require_once 'sidebar-single.php'; ?>

<?php require_once ('footer.php'); ?>
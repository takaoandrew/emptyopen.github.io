<div class="col-xs-12 col-md-3 websites-sidebar" style="float:right;">
	
	<?php
	if(is_admin()) {
		echo '<div class="white-content">';
		print '<a href="/admin" class="btn btn-lg btn-danger">Admin Panel</a>';
		echo '</div>';
	}
    
	?>
	
	<div style="border:1px solid #E2E2E2;box-shadow: 0 0 3px 0 rgba(0,0,0,0.075); background: white;padding:20px;margin:20px 0 0 0;">
	<table width="100%">
	    <tr><td style="text-align:left;"><h3 class="text-error"><?=$last_bid;?></h3></td>
	        <td style="text-align:right;"><h3 class="text-info"><?=$bid_count . _(' bids');?></h3></td></tr>
	</table>
	
	<?php if(isset($hide_bid) AND $hide_bid === 'true') { ?>
	      <strong><?=_('Bidding closed')?></strong>
	      <br/><br/>
	<?php }else{ ?> 
	<form method="post" action="/listings/bid/<?=$l->listingID; ?>" class="form-horizontal">
	<?php echo _("Enter") . ' ' . $last_bid_plus . ' ' . _('or more'); ?>
	<input type="number" value="" class="input-medium" name="bid_amount"/>
	<br />
	<input type="submit" name="sb_bid" value="<?php echo _('Place your Bid'); ?>" class="btn btn-xlarge btn-primary" style="width:80%;margin: 5px 0 5px 0;"/>
	<br />
	<input type="submit" name="sb_bin" value="<?php echo _('Buy it now for : ') . '$'. number_format($l->bin,0); ?>" class="btn btn-xlarge btn-default" style="width:80%;">    
	</form>
	<?php } ?>
	</div>
	
	<div style="border:1px solid #E2E2E2;box-shadow: 0 0 3px 0 rgba(0,0,0,0.075); background: white;padding:20px;margin:20px 0 0 0;">
	<div class="sidebar-item-title text-error"><?php echo _('Seller') ?></div>
	<div class="sidebar-item">
	    <a href="/users/profile/<?php echo url_title($l->username); ?>">
	    <?php if(!empty($l->photo)) : ?>
	        <img src="/uploads/<?=$l->photo?>" alt="" width="48" height="48" style="float:left;padding:5px 10px 5px 0px;"/>
	    <?php else: ?>
	        <img src="/img/nophoto.jpg" alt="" width="48" height="48" style="float:left;padding:5px 10px 5px 0px;" />
	    <?php endif; ?>
	    </a>
	    <a href="/users/profile/<?php echo url_title($l->username); ?>"><?=$l->username; ?></a>
	    <br/>
	    <a href="/users/message/<?=$l->userID ?>" style="color:#286090;text-decoration: underline;"><?=_('Contact User')?></a>
	    <br/>
	    <span class="text-info" style="font-size:12px;"><?=_('Ownership verified')?></span>    
	    <div style="clear:both;"></div>
	</div>
	</div>
	
	<div style="border:1px solid #E2E2E2;box-shadow: 0 0 3px 0 rgba(0,0,0,0.075); background: white;padding:20px;margin:20px 0 0 0;">
	<div class="sidebar-item-title text-error"><?php echo _('Attachments') ?></div>
	<?php 
    if(count($att)) {
       foreach($att as $a) {
           echo '<a href="/uploads/'.$a->att_file.'"><img src="/uploads/small-'.$a->att_file.'" alt="" /></a> ';
           echo '<a href="/uploads/'.$a->att_file.'">' . $a->att_title . '</a>';
           echo '<br/><hr/>';
       }    
    }else{
        echo _('No attachments added');
    }
    ?>
	</div>
<div style="border:1px solid #E2E2E2;box-shadow: 0 0 3px 0 rgba(0,0,0,0.075); background: white;padding:20px;margin:20px 0 0 0;">
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- artist -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-6226012631321881"
     data-ad-slot="5504553255"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
    </div>
	<div style="clear:both;"></div>
</div>
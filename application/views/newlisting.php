<?php require_once ('header.php'); ?>

<div class="white-content">
<div id="ajax-div" title="Update">
    <div id="ajax-l" style="display:none;"><img src="/img/ajax-loader.gif" /> <?=_('Please wait') ?>...</div>
    <iframe id="frame" src="" width="100%" height="400" frameborder="0" allowtransparency="true" scrolling="auto"></iframe>
</div>
	
	<div class="col-xs-12 col-md-8">
	<div class="pull-left"><h2><?=_('Listing details')?></h2></div>
	<div class="pull-left"><br/><span class="muted"><small>&nbsp;&nbsp;All Listings have a duration of 30 Days</small></span></div>
	<div style="clear:both;"></div>
	<div class="progress">
	  <div class="progress-bar" style="width: <?php echo $percent; ?>%;"><?php echo $percent; ?>%</div>
	</div>
	</div>
	<div class="col-xs-12 col-md-4">
		<br /><br />
		<?php if(get_option('listing_fee') == 0) : ?>
			<a href="/payments" class="btn btn-success">Publish Listing</a>
		<?php else: ?>
		<div class="btn-group">
			<a href="javascript:void(0);" class="btn btn-medium btn-green dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=_('<span class="caret"></span> Publish listing for $' . get_option('listing_fee'))?></a>
			<ul class="dropdown-menu">
				<?php if(get_option('paypal_enable') == "Yes" ) : ?>
			    <li>
			    	<a href="/payments" class="btn btn-warning btn-md" style="margin-left:10px;width: 120px;">Pay with Payal</a>
			    </li>
				<?php endif; ?>
			    <?php if(get_option('stripe_enable') == "Yes") : ?>
			    <li class="divider"></li>
			    <li>
			    	<form action="/payments/stripe" method="POST" style="margin-left:10px;">
					  <script
					    src="https://checkout.stripe.com/v2/checkout.js" class="stripe-button"
					    data-key="<?= get_option('stripe_public') ?>"
					    data-amount="<?= get_option('listing_fee')*100 ?>"
					    data-name="Payment"
					    data-description="Listing Fee">
					  </script>
					  <input type="hidden" name="listingID" value="<?php echo @$listing->listingID ?>" />
					</form>
			    </li>
				<?php endif; ?>
			  </ul>
		</div><!-- ./ btn group -->
		<?php endif; ?>
		<a style="margin-bottom:5px" href="/users/clearlisting" class="btn btn-medium btn-default" onclick="return confirm('Are you sure you want to start a new listing?');"><?=_('New listing')?></a>
	</div>

	<hr style="clear:both;"/>

	<?php
	$ci =& get_instance();
    $listingID = $ci->session->userdata("listingID");
    
	if(!isset($step) AND !$listingID) {
	    
    if(isset($err_msg)) echo div_class($err_msg);    
	?>
	
	<form method="post" action="" class="form-horizontal">
		<label>Enter website/domain URL you wish to sell:</label>
		<div class="input-group">
	    <input type="text" name="listing_url" value="" placeholder="example.com" class="form-control"/>
	    <span class="input-group-btn">
	    <input type="submit" name="sbStep1" value="<?=_('Start') ?>" id="sbStep1" class="btn btn-default">
	    </span>
	    </div>
	</form>
	
	<?php    
	}else{
	
    if(!$listingID) {
        echo '<meta http-equiv="refresh" content="0;url=/users/clearlisting"/>';
        exit;
    }     
	?>
	
    
    
	<hr style="clear:both;"/>

	<div class="tabbable"> <!-- Only required for left/right tabs -->
	<div class="col-xs-12 col-md-3">
	<ul class="nav nav-tabs tabs-left">
		<li class="active">
			<a href="#basic" data-toggle="tab"><b class="<?=$basic_icon?>" id="basic-icon"></b> <?=_('Basic Details') ?></a>
		</li>
		<li>
			<a href="#description" data-toggle="tab"><b class="<?=$desc_icon?>" id="desc-icon"></b> <?=_('Description') ?></a>
		</li>
		<li>
			<a href="#age" data-toggle="tab"><b class="<?=$siteage_icon?>" id="siteage-icon"></b> <?=_('Age') ?></a>
		</li>
		<li>
			<a href="#revenue" data-toggle="tab"><b class="<?=$revenue_icon?>" id="revenue-icon"></b> <?=_('Revenue') ?></a>
		</li>
		<li>
			<a href="#traffictab" data-toggle="tab"><b class="<?=$traffic_icon?>" id="traffic-icon"></b> <?=_('Traffic') ?></a>
		</li>
		<li>
			<a href="#unique" data-toggle="tab"><b class="<?=$unique_icon?>" id="unique-icon"></b> <?=_('Uniqueness') ?></a>
		</li>
		<li>
			<a href="#payment" data-toggle="tab"><b class="<?=$payments_icon?>" id="payments-icon"></b> <?=_('Accepted Pay Methods') ?></a>
		</li>
		<li>
			<a href="#tags" data-toggle="tab"><b class="<?=$tags_icon?>" id="tags-icon"></b> <?=_('Tags') ?></a>
		</li>
		<li>
			<a href="#ownership" data-toggle="tab"><b class="<?=$verify_icon?>" id="verify-icon"></b> <?=_('Ownership') ?></a>
		</li>
		<li>
			<a href="#attachments" data-toggle="tab"><b class="glyphicon glyphicon-chevron-right" id="attachments-icon"></b> <?=_('Attachments') ?></a>
		</li>
	</ul>
	</div><!--span 3-->
	<div class="col-xs-12 col-md-9">
	<div class="tab-content">
	    <div class="tab-pane active" id="basic">
	    <h3>Basic Details</h3>

	      <form method="post" action="/users/updatelistings/<?php echo $id ?>" class="frm-basic">
              <label><?=_('Listing Type')?>:</label>
              <br />
              <input type="radio" name="list_type" value="domain" <?php if($listing && $listing->list_type == 'domain') echo 'checked=""'; ?>/> Domain Only
              <input type="radio" name="list_type" value="website" <?php if($listing && $listing->list_type == 'website') echo 'checked=""'; ?>/> Website
              <br /><br />

              <label><?=_('Listing Title')?>:</label>
              <input type="text" name="listing_title" value="<?php if($listing) echo $listing->listing_title; ?>" class="form-control required"/><br/>
              
              <label><?=_('Starting Price')?>:</label>
              <input type="number" name="starting_" value="<?php if($listing) echo $listing->starting_; ?>" class="form-control required"/><br/>
              
              <label><?=_('Reserve Price')?>:</label>
              <input type="number" name="reserve" value="<?php if($listing) echo $listing->reserve; ?>" class="form-control required"/><br/>
              
              <label><?=_('BIN Price')?>:</label>
              <input type="number" name="bin" value="<?php if($listing) echo $listing->bin; ?>" class="form-control required"/><br/>
              
              <input type="submit" name="sb" value="<?=_('Update')?>" class="update-sb btn btn-warning" />
          </form>
          
	      </div><!-- basic tab -->

	    <div class="tab-pane" id="description">
	    <h3>Listing description</h3>

	      <form method="post" action="/users/updatelistings/<?php echo $id ?>" class="frm-description">
	          <textarea name="listing_description" id="listing_description" rows="12" class="form-control required"><?php echo $listing->listing_description; ?></textarea>
	          <br/>    
	          <input type="submit" name="sb" value="<?=_('Update') ?>" class="update-sb btn btn-warning" />
          </form>

	    </div><!-- description tab -->

	    <div class="tab-pane" id="age">
	    <h3>Site/Domain Age</h3>

		<?php $months = array(1 => 'Jan.', 2 => 'Feb.', 3 => 'Mar.', 4 => 'Apr.', 5 => 'May', 6 => 'Jun.', 7 => 'Jul.', 8 => 'Aug.', 9 => 'Sep.', 10 => 'Oct.', 11 => 'Nov.', 12 => 'Dec.');?>
              <form method="post" action="/users/updatelistings/<?php echo $id ?>" class="frm-age form-horizontal">
              
              <div class="col-xs-3">
              <select name="month" class="form-control">
                  <?php foreach($months as $k=> $m) {
                        $m = str_replace(".", "", $m);
                        if($listing AND $listing->site_age != 0) {
                            if(date("M", $listing->site_age) == $m) {
                                echo '<option value="'.$k.'" selected="">'.$m.'</option>';
                            }else{
                                echo '<option value="'.$k.'">'.$m.'</option>';
                            }
                        }else{
                            echo '<option value="'.$k.'">'.$m.'</option>';   
                        }
                  }
                  ?>
              </select>
              </div>
              <div class="col-xs-3">
              <select name="day" class="form-control">
                  <?php
                  for($i = 1; $i<= 31; $i++) 
                  {
                  if($listing AND $listing->site_age != 0) {
                        if(date("j", $listing->site_age) == $i) {
                            echo '<option value="'.$i.'" selected="">'.$i.'</option>';
                        }else{
                            echo '<option value="'.$i.'">'.$i.'</option>';
                        }
                  }else{
                      echo '<option value="'.$i.'">'.$i.'</option>';
                  }
                  } 
                  ?>
              </select>
              </div>
              <div class="col-xs-3">
              <select name="year" class="form-control">
                  <?php
                  for($i = 1990; $i<= date("Y"); $i++) 
                  {
                      if($listing AND $listing->site_age != 0) {
                          if(date("Y", $listing->site_age) == $i) {
                             echo '<option value="'.$i.'" selected="">'.$i.'</option>';
                          }else{
                              echo '<option value="'.$i.'">'.$i.'</option>';
                          }
                      }else{
                          echo '<option value="'.$i.'">'.$i.'</option>';
                      }
                  }  
                  ?>
              </select>
              </div>
              <div class="col-xs-3">
              <input type="submit" name="sb" value="<?=_('Update') ?>" class="update-sb btn btn-warning" />
              </div>
              </form>
              
	    </div><!--age tab -->

	    <div class="tab-pane" id="revenue">
 
		<h3>Revenue details</h3>

		<form method="post" action="/users/updatelistings/<?php echo $id ?>" class="frm-monetization form-horizontal">
		<label><strong><?=_('Monetization Methods') ?>:</strong></label>
		<br/>
		<input type="checkbox" name="monetization[]" value="Sales of Products or Services" <?php if($listing AND preg_match('/Sales of Products or Services/i', $listing->monetization)) echo 'checked=""'; ?>/> <?=_('Sales of Products or Services') ?><br/>
		<input type="checkbox" name="monetization[]" value="Affiliate Income" <?php if($listing AND preg_match('/Affiliate Income/i', $listing->monetization)) echo 'checked=""'; ?>/> <?=_('Affiliate Income') ?><br/>
		<input type="checkbox" name="monetization[]" value="Advertising Sales" <?php if($listing AND preg_match('/Advertising Sales/i', $listing->monetization)) echo 'checked=""'; ?>/> <?=_('Advertising Sales') ?><br/>

		<br/>


		<label><strong><?=_('Last three months AVERAGE') ?>:</strong></label>
		<div class="input-group">
			<input type="text" name="rev_avg" value="<?php echo $listing->rev_avg; ?>" class="form-control"/>
			<span class="input-group-btn"><button type="submit" class="btn btn-default">per month</button></span>
		</div>
		<br/><br/>
		<label><strong><?=_('Describe revenue as much as possible') ?>:</strong></label>
		<textarea name="revenue_details" id="listing_description" rows="8" class="form-control required"><?php echo $listing->revenue_details; ?></textarea>
		<br/>    

		<input type="submit" name="sb" value="<?=_('Update') ?>" class="update-sb btn btn-warning" />
		</form>
                  
		</div><!-- revene tab -->

		<div class="tab-pane" id="traffictab">
			<h3>Traffic details</h3>
			<form method="post" action="/users/updatelistings/<?php echo $id ?>" class="frm-traffic">
			<label><strong><?=_('Last three months AVERAGE visits') ?>:</strong></label>
			<div class="input-group">
				<input type="text" name="traffic_avg_visits" value="<?php echo $listing->traffic_avg_visits; ?>" class="form-control"/>
				<span class="input-group-btn">
					<button type="submit" class="btn btn-default form-control">per month</button>
				</span>
			</div>
			<br/>

			<label><strong><?=_('Last three months AVERAGE views') ?>:</strong></label>
			<div class="input-group">
				<input type="text" name="traffic_avg_views" value="<?php echo $listing->traffic_avg_views; ?>" class="form-control"/>
				<span class="input-group-btn"><button type="submit" class="btn btn-default">per month</button></span>
			</div>

			<br/><br/>

			<label><strong><?=_('Traffic description') ?>:</strong></label>
			<textarea name="traffic_details" id="listing_description" rows="8" class="form-control required"><?php echo $listing->traffic_details; ?></textarea>

			<br/>    
			<input type="submit" name="sb" value="<?=_('Update') ?>" class="update-sb btn btn-warning" />
			</form>
			
		</div><!-- traffic-->


	    <div class="tab-pane" id="unique">
			<form method="post" action="/users/updatelistings/<?php echo $id ?>" class="frm-unique form-horizontal">
			<label><strong><?=_('Is your Design/Content Unique?') ?></strong></label>
			<br />

			<input type="radio" name="unique_" value="not unique" <?php if($listing AND $listing->unique_ == 'not unique') echo 'checked=""'; ?>/> <?=_('Not Unique') ?><br/>
			<input type="radio" name="unique_" value="design" <?php if($listing AND $listing->unique_ == 'design') echo 'checked=""'; ?>/> <?=_('Design is Unique') ?><br/>
			<input type="radio" name="unique_" value="content" <?php if($listing AND $listing->unique_ == 'content') echo 'checked=""'; ?>/> <?=_('Content is Unique') ?><br/>
			<input type="radio" name="unique_" value="design & content" <?php if($listing AND $listing->unique_ == 'design & content') echo 'checked=""'; ?>/> <?=_('Both Content &amp; Design are Unique') ?><br/>

			<br/>
			<input type="submit" name="sb" value="<?=_('Update') ?>" class="update-sb btn btn-warning" />
			</form>
	    </div><!-- uniqueness -->


	    <div class="tab-pane" id="payment">
			<form method="post" action="/users/updatelistings/<?php echo $id ?>" class="frm-payments form-horizontal">
			<label><strong><?=_('Accepted Payment Methods') ?>:</strong></label>
			<br />

			<input type="checkbox" name="payment_options[]" value="Escrow.com" <?php if($listing AND preg_match('/Escrow/i', $listing->payment_options)) echo 'checked=""'; ?>/> Escrow.com<br/>
			<input type="checkbox" name="payment_options[]" value="Credit Card" <?php if($listing AND preg_match('/Credit Card/i', $listing->payment_options)) echo 'checked=""'; ?>/> Credit Card<br/>
			<input type="checkbox" name="payment_options[]" value="Cheque" <?php if($listing AND preg_match('/Cheque/i', $listing->payment_options)) echo 'checked=""'; ?>/> Cheque<br/>
			<input type="checkbox" name="payment_options[]" value="PayPal" <?php if($listing AND preg_match('/PayPal/i', $listing->payment_options)) echo 'checked=""'; ?>/> PayPal<br/>

			<br/>
			<input type="submit" name="sb" value="<?=_('Update') ?>" class="update-sb btn btn-warning" />
			</form>
	    </div><!-- payments accepted -->

	    <div class="tab-pane" id="tags">
			<form method="post" action="/users/updatelistings/<?php echo $id ?>" class="frm-tags">
			<p class="alert alert-warning"><?=_('Only one keyword per tag is allowed.') ?></p>

			<label><strong><?=_('Niche') ?>:</strong><?=_('(health, sports, etc.)') ?></label>
			<input type="text" name="tag_niche" value="<?php echo $listing->tag_niche; ?>" class="form-control"/>
			<br/>

			<label><strong><?=_('Type') ?>:</strong><?=_('(forum, blog, etc.)') ?></label>
			<input type="text" name="tag_type" value="<?php echo $listing->tag_type; ?>" class="form-control"/>
			<br/>

			<label><strong><?=_('Implementation') ?>:</strong><?=_('(custom, wordpress, etc.)') ?></label>
			<input type="text" name="tag_implementation" value="<?php echo $listing->tag_implementation; ?>" class="form-control"/>
			<br/>

			<input type="submit" name="sb" value="<?=_('Update') ?>" class="update-sb btn btn-warning" />
			</form>
	    </div><!-- tags -->

	    <div class="tab-pane" id="attachments">

			    <h3 class="text-info"><?= _('Listing Attachments') ;?></h3>
			    
			    <form id="att" method="POST" enctype="multipart/form-data" action="/users/att">
			        <label><?=_('Title') ?></label>
			        <input type="text" name="att_title" class="form-control required" required />
			        <br/>
			        <label><?=_('Add Attachment (PNG/JPG ONLY)') ?></label>
			        <input type="file" name="file" class="form-control"/><br/>
			        <input type="submit" name="sb_att" value="<?=_('Upload') ?>" class="btn btn-default"/>
			    </form>
			    
			    <div class="att_rs"></div>
			    
			    <hr/>
			    
			    <?php 
			    if(count($att)) {
			       foreach($att as $a) {
			           echo '<a href="/uploads/'.$a->att_file.'"><img src="/uploads/small-'.$a->att_file.'" alt="" /></a> ';
		               echo '<a href="/uploads/'.$a->att_file.'">' . $a->att_title . '</a> ';
		               echo '<a href="/users/remove_att/'.$a->attachID.'" style="color:#cc0000;">[x]</a>';
			           echo '<br/><hr/>';
			       }    
			    }else{
			        echo _('No attachments added');
			    }
		        ?>
	    </div><!-- attachments tab -->

	    <div class="tab-pane" id="ownership">
	    	<h3 class="text-info"><?=_('Upload a file to your host') ?>:</h3>
            <span class="text-info"><?=_('Upload a file called ') ?><span class="text-warning">verify_<?php echo $id ?>.txt</span> 
            <?=_("so it's accessibile on this URL : ") ?><span class="text-warning">http://<?php echo $listing->listing_url; ?>/verify_<?php echo $id ?>.txt</span></span>
            
            <br/>
            
            <a href="/users/verify_file/<?php echo $id; ?>" target="_blank" style="font-weight:bold;color:#cc0000;font-size:16px;"><?=_('Download file') ?></a>
            
            <br/><br/>
            <form method="post" action="/users/updatelistings/<?php echo $id ?>" class="frm-verify">
              <br/>    
              <input type="hidden" name="verify_file" value="<?php echo $id; ?>" />
              <input type="submit" name="sb" value="<?=_('Update') ?>" class="update-sb btn btn-warning" />
              </form>
	    </div>

	</div><!-- tab content -->
	</div><!-- span 9 -->
	</div><!-- tabable-->


	<hr style="clear:both;" />
	<div class="ajax-modal-result"></div>
	
	<?php 
    }//if listing update 
    ?>
   

</div>
</div>

<?php require_once ('footer.php'); ?>
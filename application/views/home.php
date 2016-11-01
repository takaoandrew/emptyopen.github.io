<?php require_once 'header.php'; ?>

</div>
<div class="homepage-image">
  <div class="container-home">
    <div class="home-intro">
      <br/><br/><h2 class="ex1">NO FUSS</h2><br/><h3 class="ex1">The simplest marketplace to trade websites &amp; domains</h3>
      <!--
      <a href="/websites/view/featured" class="btn btn-lg btn-primary"><?=_('Websites') ?></a>
      <a href="/domains/view/featured" class="btn btn-lg btn-warning"><?=_('Domains') ?></a><br><br>
      -->

    </div>
  </div>
</div><!--homepage img-->

<!--
<br><center><img src="https://i.imgsafe.org/28c842b958.png" /></center><br>
<center><h2>Featured Listings</h2></center>
-->

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
                  <br /><i class="icon icon-calendar"></i> <span class="muted">Ending in <?=(now() > $l->list_expires) ? 'Closed' : timespan(now(),$l->list_expires)?></span><br/><br/>
                  <?=$l->listing_title;?>
                  <br/><br/>
                  <div class="bottom-info">
                    <span class="muted"><?=_('Established') ?></span> <?=date("M Y", $l->site_age);?>
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

      <!--
      <center>
        <a class="btn btn-primary" href="/websites/view/new-listings">Latest Websites</a>
        <a class="btn btn-warning" href="/domains/view/new-listings">Latest Domains</a>
      </center>
      -->
    </div>
  </div>
  <div class="container">
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
</div>


<?php require_once 'footer.php'; ?>

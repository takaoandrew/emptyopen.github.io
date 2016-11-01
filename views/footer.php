</div><!--container-->

<br/>
<div id="footer">
    <div class="container">
    <div class="row">

		<div class="col-xs-6 col-md-3">
		<h3>Links</h3>
		<ul class="footer-nav">
			<li><a href="<?php echo base_url(); ?>/contact"><?=_('Contact</a>') ?></li>
			<li><a href="<?php echo base_url(); ?>/home/tos"><?=_('Terms of Service') ?></a></li>
			<li><a href="<?= get_option('fb_url') ?>" target="_blank"><?=_('Facebook</a>') ?></li>
			<li><a href="<?= get_option('tw_url') ?>" target="_blank"><?=_('Twitter</a>') ?></li>
		</ul>
		</a>
		</div>
		
		<!--
		<div class="col-xs-6 col-md-3">
		<h3>Websites</h3>
		<ul class="footer-nav">
			<li><a href="<?php echo base_url(); ?>/websites/view/new-listings"><?=_('Latest') ?></a></li>
			<li><a href="<?php echo base_url(); ?>/websites/view/featured"><?=_('Featured</a>') ?></li>
			<li><a href="<?php echo base_url(); ?>/websites/view/most-active"><?=_('Most Active</a>') ?></li>
			<li><a href="<?php echo base_url(); ?>/websites/view/just-sold"><?=_('Sold</a>') ?></li>
		</ul>
		</a>
		</div>

		<div class="col-xs-6 col-md-3">
		<h3>Domains</h3>
		<ul class="footer-nav">
			<li><a href="<?php echo base_url(); ?>/domains/view/new-listings"><?=_('Latest') ?></a></li>
			<li><a href="<?php echo base_url(); ?>/domains/view/featured"><?=_('Featured</a>') ?></li>
			<li><a href="<?php echo base_url(); ?>/domains/view/most-active"><?=_('Most Active</a>') ?></li>
			<li><a href="<?php echo base_url(); ?>/domains/view/just-sold"><?=_('Sold</a>') ?></li>
		</ul>
		</a>
		</div>
		-->
		
		<div class="col-xs-6 col-md-3">
		<h3>Stats</h3>
		<?php $CI =& get_instance(); ?>
		<ul class="footer-nav">
			<li><?= $CI->Stats->listings_open(); ?> marketplace listings</li>
			<li><?= $CI->Stats->count_bids(); ?> total bids</li>
			<li><?= ($CI->Stats->sales_overall() === NULL) ? '$0' : $CI->Stats->sales_overall(); ?> in transactions</li>
			<li><?= $CI->Stats->members_count(); ?> community members</li>
		</ul>
		</a>
		</div>
<br></br><center><a href="http://flipkingz.com">
<!-- <img border="0" alt="Flip kingz" src="https://s18.postimg.org/87wtomg1l/tac.png" height="200" width="100"> -->
</a></center>
</br><center><p>COPYRIGHT matt &copy 2016 FLIPKINGZ.COM. ALL RIGHTS RESERVED.</p></center>


</div>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-85102754-1', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>
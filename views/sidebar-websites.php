<div class="col-xs-12 col-md-3 websites-sidebar">
	<?php
	if(is_admin()) {
		echo '<div class="white-content">';
		print '<a href="/admin" class="btn btn-large btn-danger">Admin Panel</a>';
		echo '</div>';
	}
	?>
	
	<div class="white-content">
	<h4 class="text-yellow"><?= _('Revenue') ?></h4>
	<hr/>
	<p>
  	<input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold; width: 90%;">
	</p>
	<div id="slider-range-max" style="width:90%;margin-left:5px;"></div>
	<br />
 	<a href="#" class="income-filter btn btn-xs btn-default">Filter by Revenue</a>
	</div>

	<div class="white-content">
	<h4 class="text-yellow"><?= _('Income Sources') ?></h4>
	<hr/>
	<a href="/<?= $list_type ?>/view/<?=$uri_param;?>?monetization=sales">Sales of Products or Services</a><br/>
	<a href="/<?= $list_type ?>/view/<?=$uri_param;?>?monetization=affiliate">Affiliate Income</a><br/>
	<a href="/<?= $list_type ?>/view/<?=$uri_param;?>?monetization=advertising">Advertising Sales</a><br/>
	</div>

	<div class="white-content">
	<h4 class="text-yellow"><?= _('Visitors') ?></h4>
    <hr/>
	<p>
  	<input type="text" id="visitors" readonly style="border:0; color:#f6931f; font-weight:bold; width: 90%;">
	</p>
	<div id="slider-visitors-min" style="width:90%;margin-left:5px;"></div>
	<br />
 	<a href="#" class="visitors-filter btn btn-xs btn-default">Filter by Visitors</a>

    </div>
    
    <div class="white-content">
    <h4 class="text-yellow"><?= _('Age') ?></h4>
    <hr/>
	<p>
  	<input type="text" id="age" readonly style="border:0; color:#f6931f; font-weight:bold; width: 90%;">
	</p>
	<div id="slider-age-min" style="width:90%;margin-left:5px;"></div>
	<br />
 	<a href="#" class="age-filter btn btn-xs btn-default">Filter by Age</a>
	</div>

	<div class="white-content">
	<h4 class="text-yellow"><?= _('Domain Extension') ?></h4>
	<hr/>
	<?php echo domain_extensions($list_type, substr($list_type, 0, strlen($list_type)-1), $uri_param); ?>
	</div>
	
	<div style="clear:both;"></div>
</div>
<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="icon" href="nophoto.jpg" type="image/jpg" sizes="48x48">
	<meta charset="utf-8">
	<title>
    <?php
    if(isset($seo_title)) 
        echo $seo_title;
    else
        echo get_option('seo_title');
    ?>   
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700' rel='stylesheet' type='text/css' />
    <link href='https://fonts.googleapis.com/css?family=Josefin+Sans:600' rel='stylesheet' type='text/css'>
    <link href="<?php echo base_url(); ?>bootstrap3/css/bootstrap.min.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/style.css" />
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css" />

    <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="http://code.jquery.com/ui/1.9.1/jquery-ui.js"></script>
    <script src="<?php echo base_url(); ?>bootstrap3/js/bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js//jquery.validate.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.form.js"></script>
    <script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>js/media/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/ajax.js"></script>    

	<!--[if gte IE 9]>
	  <style type="text/css">
	    .gradient {
	       filter: none;
	    }
	  </style>
	<![endif]-->

    <?php 
    if(isset($_GET['login'])) {
    ?>
    <script>
    $(function() {
        $("#login").modal('show');
    });
    </script>
    <?php 
    }
    ?>
    <?php 
    if(isset($_GET['signup'])) {
    ?>
    <script>
    $(function() {
        $("#join").modal('show');
    });
    </script>
    <?php 
    }
    ?>

    <?= get_option('analytics_code'); ?>


    <?php 
    if($img = get_option('header_image')) {
        echo '<style>.homepage-img { background-image: url(uploads/'.$img.') !important; }</style>';
    }
    ?>


</head>
<body>

<!-- modal login -->
<div id="login" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog white-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel"><i class="icon icon-user"></i> Login</h3>
  </div><!-- modal-header -->
  <div class="modal-body">
    <?php if(isset($login_message)) echo $login_message; ?>
    <form method="post" action="/users/login" class="form" id="login-form">
        <input type="text" name="uname" placeholder="username" class="form-control" /><br/>
        <input type="password" name="upwd" placeholder="****" class="form-control" /><br/>
        <input type="submit" name="sbLogin" value="<?=_('Login') ?>" class="btn btn-info"/>
        <a href="/home/lostpassword" class="btn btn-default">Lost Password</a>
        <br /><br />
        Don't have an Account? <a href="/?signup=yes">Create one</a>
    </form>
    <br />
    <div id="login_output_div"></div>
  </div>
  <div class="modal-footer">
    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div><!-- .modal dialog -->
</div><!-- .modal login -->

<!-- modal signup -->
<div id="join" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">\
<div class="modal-dialog white-content">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel"><i class="icon icon-lock"></i> Signup</h3>
  </div>
  <div class="modal-body">
    <form method="post" action="/users/ajax_join" id="signup-form" accept-charset="UTF-8">
        <label>
            <?=_('Username') ?>:
        </label>
        <input type="text" name="username" placeholder="username" class="required form-control"/>
        
        <br/>
        
        <label>
            <?=_('Email') ?>:
        </label>
        <input type="email" name="email" placeholder="email" class="required form-control" />
        
        <br/>
        
        <label>
            <?=_('Password') ?>:
        </label>
        <input type="password" name="password" placeholder="****" class="required form-control" />
        
        <br/>
        
        <input type="submit" name="sb_signup" value="<?=_('Join Now') ?>" class="btn btn-info"/>
    
    </form>

    <br /><br />
        Already have an Account? <a href="/?login=yes">Login</a>

    <br />
    <div id="signup_output_div"></div>
  </div>
  <div class="modal-footer">
    <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>
</div>

<div class="nav-gradient">
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="#" class="navbar-brand visible-xs"><a href="http://flipkingz.com">
<img border="0" alt="Flip kingz" src="https://s16.postimg.org/z4sqqmv5x/flipkingz.png" height="410" width="200">
</a></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-right">
         <li class="dropdown">
            <a href="/websites/view/high-end-price" class="dropdown-toggle" data-toggle="dropdown"><?=_('Websites')?><b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li>
                    <a href="/websites/view/new-listings"><?=_('Latest' ) ?></a>
                </li>
                <li>
                    <a href="/websites/view/most-active"><?=_('Most Active') ?></a>
                </li>
                <li>
                    <a href="/websites/view/ending-soon"><?=_('Ending Soon') ?></a>
                </li>
                <li>
                    <a href="/websites/view/just-sold"><?=_('Just Sold') ?></a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="/websites/view/featured"><?=_('Featured') ?></a>
                </li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="/domains/view/high-end-price" class="dropdown-toggle" data-toggle="dropdown"><?=_('Domains')?><b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li>
                    <a href="/domains/view/new-listings"><?=_('Latest' ) ?></a>
                </li>
                <li>
                    <a href="/domains/view/most-active"><?=_('Most Active') ?></a>
                </li>
                <li>
                    <a href="/domains/view/ending-soon"><?=_('Ending Soon') ?></a>
                </li>
                <li>
                    <a href="/domains/view/just-sold"><?=_('Just Sold') ?></a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="/domains/view/featured"><?=_('Featured') ?></a>
                </li>
            </ul>
        </li>
        <?php if(is_user_logged_in()): ?>
             <li>
              <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="glyphicon glyphicon-user"></i>
                My Account
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li><?php echo anchor(base_url() . 'users', _('<i class="glyphicon glyphicon-chevron-right"></i> My Profile')); ?></li>
                <li><?php echo anchor(base_url() . 'users/mylistings', _('<i class="glyphicon glyphicon-chevron-right"></i> My Listings')); ?></li>
                <li><?php echo anchor(base_url() . 'users/inbox', _('<i class="glyphicon glyphicon-chevron-right"></i> Messages')); ?></li>
                <li><?php echo anchor(base_url() . 'users/bids', _('<i class="glyphicon glyphicon-chevron-right"></i> Bids Made')); ?></li>
                <li><?php echo anchor(base_url() . 'users/offers', _('<i class="glyphicon glyphicon-chevron-right"></i> Offers Received')); ?></li>
                <li><?php echo anchor(base_url() . 'users/logout', _('<i class="glyphicon glyphicon-chevron-right"></i> Logout')); ?></li>
              </ul>
            </li>
        <?php else: ?>
            <li><a href="#login" role="button" data-toggle="modal"><i class="glyphicon glyphicon-user"></i> <?=_('Login') ?></a></li>
            <li><a href="#join" role="button" data-toggle="modal"><i class="glyphicon glyphicon-fire"></i> <?=_('Sign Up') ?></a></li>
        <?php endif; ?>
		<li class="active"><a href="/users/newlisting" class="btn btn-green"><b class="glyphicon glyphicon-usd"></b> <b>LIST A PROPERTY</b></a>
		</li>
        </ul>
        
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
</div>
	
<?php
$ci =& get_instance();
$home = $ci->router->fetch_class();
?>
<div class="container">
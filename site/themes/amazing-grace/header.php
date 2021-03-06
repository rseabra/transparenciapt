<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/local.css" type="text/css" media="screen" />
<style type="text/css">
  #portrait-bg { background:url(<?php bloginfo('stylesheet_directory'); ?>/images/bg-portrait<?php echo (rand()%3); ?>.jpg); }
</style>
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php wp_head(); ?>
</head>

<body>

<div id="wrap">
	<div id="menu">
		<ul>
			<li><a href="<?php echo get_settings('home'); ?>/" >In&iacute;cio</a></li>
			<?php wp_list_pages('sort_column=menu_order&hierarchical=0&title_li='); ?>
			<li><a href="http://ansol.org/contacto" class="smcf-link">Contacto</a></li>
		</ul>
	</div>
	
	<div id="header">
		<span class="btitle"><a href="<?php echo get_settings('home'); ?>/"><?php bloginfo('name'); ?></a></span>
		<p class="description">
			<a href="<?php 
			if (current_user_can('level_10')) 
				echo get_settings('home').'/wp-admin/">'; 
			else 
				echo get_settings('home').'/">'; 
			bloginfo('description'); ?> 
			</a>
		</p>
	</div>
	
	<div id="rss-big">
		<a href="<?php bloginfo('rss2_url'); ?>" title="<?php _e('Subscribe to this site with RSS'); ?>"></a>
	</div>
	
	<div id="portrait-bg"></div>
	<div id="catmenu">
			<!--		<ul>
 <?php wp_list_categories('orderby=count&order=DESC&show_count=0&hierarchical=1&title_li=&depth=1'); ?>
		</ul> -->
	</div>

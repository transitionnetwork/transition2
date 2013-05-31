<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
<head>
<title><?php print $head_title; ?></title>
<?php print $head; ?>
<?php print $styles; ?>
<?php print $scripts; ?>
<!--[if lte IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php print base_path() . path_to_theme() ?>/css/ie.css" />
<![endif]-->
<!--[if lte IE 6]>
	<script type="text/javascript" src="<?php print base_path() . path_to_theme() ?>/js/supersleight-min.js"></script>
<![endif]-->
</head>
<body class="<?php print $body_classes; ?>">
  <div id="body-container">
  <a name="top" id="navigation-top"></a>
     <div id="skip-nav"><a href="#page"><?php print t('Skip to Main Content'); ?></a></div>
  <div id="header-container">
  <div id="headwrap" class="fullspan">
  

       <div id="header" class="fullspan clear-block">
        <div id="branding" class="grid-12 clear-block">
        <?php if ($logo): ?>
        <div id="logo" class="grid-3">
          <a href="<?php print $base_path; ?>" title="<?php print t('Home'); ?>" rel="home"><img src="<?php print $logo; ?>" alt="Transition Network Logo - <?php print t('Home'); ?>" id="logo-image" /></a>
        </div>
        <?php endif; ?>
        <?php if ($site_name): ?>
        <div id="site-name" class="grid-9">
          <?php print $site_name ?>
        </div>
        <?php endif; ?>
            </div>
      <?php if ($top_links): ?>
        <div id="top-links" class="grid-9"><?php print $top_links; ?></div>
        <?php endif; ?>

      <?php print $search_box ?>
      </div>
      <?php if ($primary_links || $secondary_links): ?>
      <div id="nav" class="fullspan clear-block">
      <?php print _tt_nav(menu_tree_all_data(variable_get('menu_primary_links_source', 'primary-links'))); ?>
      <?php if ($is_admin): ?>
      <div class="edit"><?php print $edit_nav; ?></div>
      <?php // print theme('links', $secondary_links); ?>
      <?php endif; ?>
      </div>
      <?php endif; ?>
      </div>
      </div>

        <div id="page-container">
        <div id="page" class="container-12 clear-block">
		<div id="breadcrumb" class="clear-block"><?php print $breadcrumb; ?></div>
        <div id="main" class="<?php print $classes['main']; ?>">
        
        <?php print $messages; ?>
        <?php if ($content_top): ?>
        <div id="content-top-region" class="clear-block"><?php print $content_top; ?></div>
        <?php endif; ?>

        <?php if ($content_top_inline): ?>
        <div id="content-top-inline-region" class="clear-block"><?php print $content_top_inline; ?></div>
        <?php endif; ?>

        <?php if ($title): ?>
        <h1 class="title" id="page-title"><?php print $title; ?></h1>
        <?php endif; ?>

        <?php if ($tabs): ?>
          <div class="tabs"><?php print $tabs; ?></div>
        <?php endif; ?>
             
        <?php print $help; ?>

        <div id="main-content" class="clear-block">
        <?php print $content; ?>
        </div>

        <?php if ($content_bottom_inline): ?>
        <div id="content-bottom-inline-region" class="clear-block"><?php print $content_bottom_inline; ?></div>
        <?php endif; ?>

        <?php if ($content_bottom): ?>
        <div id="content-bottom-region" class="clear-block"><?php print $content_bottom; ?></div>
        <?php endif; ?>

        <?php print $feed_icons; ?>
      </div><!-- #main -->

      <?php if ($left): ?>
      <div id="sidebar-left" class="sidebar <?php print $classes['left']; ?>"><?php print $left; ?></div>
      <?php endif; ?>

      <?php if ($right): ?>
      <div id="sidebar-right" class="sidebar <?php print $classes['right']; ?>"><?php print $right?></div>
      <?php endif; ?>
  </div>
  </div><!-- #page -->

  <div id="footer-container">
    
    <div id="footer" class="container-12 clear-block">
      <?php if ($footer_inline): ?>
      <div id="footer-inline-region" class="grid-12 clear-block"><?php print $footer_inline; ?></div>
      <?php endif; ?>
      <?php if ($footer_usermenu_inline): ?>
      <div id="footer-usermenu-inline-region" class="grid-12 clear-block"><?php print $footer_usermenu_inline; ?></div>
      <?php endif; ?>
	    <?php if ($footer_banner): ?>
      <div id="footer-banner-region" class="grid-12 clear-block"><?php print $footer_banner; ?></div>
      <?php endif; ?>
      <?php if ($footer_message): ?>
      <div id="footer-message" class="grid-12 clear-block"><?php print $footer_message; ?></div>
      <?php endif; ?>
     </div>
    
    </div><!-- #footer -->
    
    </div><!-- #body-wrapper -->
  <?php print $closure; ?>
</body>
</html>

<?php
/**
 * @file
 * HTML wrapper code for the iframe'ed code at the external site.
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <?php print $head ?>
    <title><?php print $title ?></title>
    <meta name="description" content="Web Widget from the Transition Network www.transitionnetwork.org" />
    <link type="text/css" rel="stylesheet" media="all" href="http://www.transitionnetwork.org/sites/all/themes/transition2/widget/widgets.css" />
    <!--[if lte IE 8]>
      <script type="text/javascript">
        var curvyCornersVerbose = false;
      </script>
      <script type="text/javascript" src="http://www.transitionnetwork.org/sites/all/themes/transition2/widget/js/curvycorners.js"></script>
    <![endif]-->
  </head>
  <body id="widgetbox">
    <div id="boxwrap">
      <div id="sharebox">
        <?php if ($title): ?><h1 class="page-title"><?php print $title ?></h1><?php endif; ?>
        <?php print $content ?>
      </div>
      <div id="boxcredit">
        <div class="powered">
          <p>Powered by <a href="http://www.transitionnetwork.org" target="_blank" title="Visit the Transition Network website"><img src="http://www.transitionnetwork.org/sites/all/themes/transition2/widget/img/logo.gif" style="border-width:0" alt="Transition Network" height="41" width="112" /></a></p>
        </div>
      </div>
    </div>
   </body>
</html>
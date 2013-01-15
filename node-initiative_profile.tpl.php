<?php
/**
 * @file node.tpl.php
 *
 * Theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: Node body or teaser depending on $teaser flag.
 * - $picture: The authors picture of the node output from
 *   theme_user_picture().
 * - $date: Formatted creation date (use $created to reformat with
 *   format_date()).
 * - $links: Themed links like "Read more", "Add new comment", etc. output
 *   from theme_links().
 * - $name: Themed username of node author output from theme_user().
 * - $node_url: Direct url of the current node.
 * - $terms: the themed list of taxonomy term links output from theme_links().
 * - $submitted: themed submission information output from
 *   theme_node_submitted().
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type, i.e. story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $teaser: Flag for the teaser state.
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 */

// Get the primary point of contact's user account, see if we can contact them and build a nice button if so
if ($field_primarycontact[0]['uid']) {
  $primary_poc = user_load($field_primarycontact[0]['uid']);
  $contact_link = false;
  if ($primary_poc->contact) {
    $contact_link = l(
      '<span>' . t('Contact Initiative') . '<span>',
      'user/'. $primary_poc->uid .'/contact',
      array(
        'attributes' => array(
          'class' => 'button-contact'),
        'html' => true,
        )
    );
    $contact_link = '<div class="button-container">' . $contact_link . '</div>';
  }
}

// build simple location/address block, add publically viewable address
$location_block = '<div class="location-block"><div class="location">';
$location_block .= $contact_link;
if ($location['city']) $location_block .= '<span class="locality">' . $location['city'] . '</span>, ';
if ($location['region']) $location_block .= '<span class="region">' . $location['province_name'] . '</span>, ';
$location_block .= '<span class="country-name">' . $location['country_name'] . '</span></div>';

// if full node, check map coordinates to see if we need add a gmap
if (!$teaser) {
  if ((floatval($location['latitude']) == 0) && (floatval($location['longitude']) == 0)) { // no
    $location_block .=  t('(map unavailable)') . '<br/>';
  }
  else { // map ok, render using node title as bubble title (then zoom, size-x, size-y)
    $location_block .= gmap_simple_map($location['latitude'], $location['longitude'], '', '<h4>' . $title . '</h4>', 6, '250px', '200px');
  }
}
$location_block .= '</div>';
?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>"><div class="node-inner">

  <?php // NO PICS! print $picture; ?>

  <?php if (!$page): ?>
    <h2 class="title">
      <a href="<?php print $node_url; ?>" title="<?php print $title ?>"><?php print $title; ?></a>
    </h2>
    <?php if ($is_admin): ?>
      <div class="edit">
        <?php print l(theme_image(path_to_theme() .'/images/edit.png', 'Edit', 'Edit'), 'node/'. $node->nid .'/edit', array('query' => drupal_get_destination(), 'html' => TRUE)); ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
  <?php if ($unpublished): ?>
    <div class="unpublished"><?php print t('Unpublished'); ?></div>
  <?php endif; ?>

  <div class="content">
    <?php print $location_block; ?>
    <?php print $content; ?>
  </div>

  <?php if ($submitted or $terms): ?>
    <div class="meta">
      <?php if ($submitted): ?><div class="submitted"><?php print $submitted; ?></div><?php endif; ?>

      <?php if ($terms): ?>
        <div class="terms terms-inline"><?php print t(' in ') . $terms; ?></div>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <?php print $links; ?>

<?php if ($page && $node->comment && $node->comment_count){ ?>
<div class="commentshead"><h2>Comments</h2></div>
<?php }; ?>
<div class="clear-block">
</div>

</div></div> <!-- /node-inner, /node -->

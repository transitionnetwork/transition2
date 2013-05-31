<?php

function phptemplate_preprocess_page(&$vars, $hook) {
  $vars['classes'] = _classes($vars['layout']);

  if (user_access('administer menu')) { // Edit Nav Menu
    $vars['edit_nav'] = l(theme_image(path_to_theme() .'/images/edit.png', 'Edit Menu', 'Edit Menu'), 'admin/build/menu-customize/primary-links', array('query' => drupal_get_destination(), 'html' => TRUE));
  }
  // body classes
  $body_classes = array($vars['body_classes']);
  // node-edit class
  if (arg(0) == 'node' && (arg(2) == 'edit' || arg(1) == 'add')) {
    $body_classes[] = 'node-edit';
  }

  $vars['body_classes'] = implode(' ', $body_classes); // Concatenate with spaces

}


function phptemplate_preprocess_node(&$vars, $hook) {
  // Special classes for nodes
  $classes = array('node');
  if ($vars['sticky']) {
    $classes[] = 'sticky';
  }
  if (!$vars['status']) {
    $classes[] = 'node-unpublished';
    $vars['unpublished'] = TRUE;
  }
  else {
    $vars['unpublished'] = FALSE;
  }
  if ($vars['teaser']) {
    $classes[] = 'node-teaser';
  }
  // Class for node type: "node-type-page", "node-type-story", "node-type-my-custom-type", etc.
  $classes[] = 'node-type-' . $vars['type'];

  $vars['classes'] = implode(' ', $classes);

  // JK Override the standard content type template options for the
  // variations on some content types since they're all the same (for now)
   switch ($vars['node']->type) {
    // events
    case 'event':
    case 'imported_event':
    case 'initiative_event':
    case 'initiative_event_external':
      $vars['template_files'] = array('node-event');
      break;

    // news
    case 'story':
    case 'movement_news':
    case 'imported_news':
    case 'network_news':
    case 'imported_blogs':
      $vars['template_files'] = array('node-news');
      break;

    // OG/CMS
    case 'page_group':
      $vars['template_files'] = array('node');
      break;

    // initiatives & hubs
    case 'initiative_profile':
    case 'initiative_hub_profile':
      $vars['template_files'] = array('node-initiative_profile');
      break;

    case 'blog':
    case 'blog_social':
      $vars['template_files'] = array('node-blog');
      break;
  }

}

/* 960 page helper function for collapsing regions */
function _classes($layout) {
 switch ($layout) {
   case 'left':
     $classes['main'] = 'grid-9 push-3';
     $classes['left'] = 'grid-3 pull-9';
     $classes['content'] = 'grid-9 alpha omega';
     break;
   case 'right':
     $classes['main'] = 'grid-9';
     $classes['right'] = 'grid-3';
     break;
   case 'both':
     $classes['main'] = 'grid-6 push-3';
     $classes['left'] = 'grid-3 pull-6';
     $classes['right'] = 'grid-3';
     break;
   case 'none':
     $classes['main'] = 'grid-12';
     break;
  }
  return $classes;
}

function phptemplate_preprocess_block(&$vars, $hook) {
  $block = $vars['block'];
  $classes = array('block');
  $classes[] = $vars['block']->module;
  $classes[] = $vars['block_zebra'];
  $classes[] = 'block-' . $vars['block_id'];
  if ($vars['block']->subject) {
     $classes[] = 'with-title';
  }
  if ($vars['block']->region == 'footer_inline') {
     $classes[] = 'grid-3';
     if ($vars['block_id'] == 1) {
       $classes[] = 'alpha';
     }
     elseif ($vars['block_id'] == 4) {
       $classes[] = 'omega';
     }
  }
  if ($vars['block']->region == 'footer_usermenu_inline') {
     $classes[] = 'grid-2';
     if ($vars['block_id'] == 1) {
       $classes[] = 'alpha';
     }
     elseif ($vars['block_id'] == 6) {
       $classes[] = 'omega';
     }
  }
  if ($vars['block']->region == 'content_top_inline' || $vars['block']->region == 'content_bottom_inline') {
     if (theme("blocks", "left") && theme("blocks", "right")) {
       $blockclasses = 'grid-3'; // both
     }
     elseif (theme("blocks", "left") || theme("blocks", "right")) {
       $blockclasses = 'grid-4'; // left or right
     }
     else {
       $blockclasses = 'grid-6'; // none
     }
     $classes[] = 'alpha';
     $classes[] = $blockclasses;
  }
  $vars['classes'] = implode(' ', $classes);

  if (user_access('administer blocks')) { // Block Edit Link
	  $vars['edit_block'] = l(theme_image(path_to_theme() .'/images/edit.png', 'Edit Block', 'Edit Block'), 'admin/build/block/configure/'. $block->module .'/'. $block->delta, array('query' => drupal_get_destination(), 'html' => TRUE));
  }
}

/**
* function to overwrite links. removes the reply link per node type
*
* @param $links
* @param $attributes
* @return unknown_type
*/
function transition2_links($links, $attributes = array('class' => 'links')) {
  // array of node types of which comments should not get the reply link
  $link_remove = 'mollom_report';
  unset($links[$link_remove]);
  return theme_links($links, $attributes);
}


/* tt Nav */
function _tt_nav($tree) {
  $output = '';
  $items = array();
  // Pull out just the menu items we are going to render so that we
  // get an accurate count for the first/last classes.
  foreach ($tree as $data) {
    if (!$data['link']['hidden']) {
      $items[] = $data;
    }
  }
  $num_items = count($items);
  foreach ($items as $i => $data) {
    $extra_class = NULL;
    if ($i == 0) {
      $extra_class = 'first';
    }
    if ($i == $num_items - 1) {
      $extra_class = 'last';
    }
    $link = theme('menu_item_link', $data['link']);
    if ($data['below']) {
      $output .= theme('menu_item', $link, $data['link']['has_children'], menu_tree_output($data['below']), $data['link']['in_active_trail'], $extra_class);
    }
    else {
      $output .= theme('menu_item', $link, $data['link']['has_children'], '', $data['link']['in_active_trail'], $extra_class);
    }
  }
  return $output ? '<ul class="menu sf-menu">'. $output .'</ul>' : '';
}

/* Override the breadcrumb to allow for a theme delimiter setting */
function transition2_breadcrumb($breadcrumb) {
  if (!empty($breadcrumb)) {
    $breadcrumb[] = drupal_get_title();
    $delimiter = theme_get_setting('breadcrumb_delimiter');
    if (is_null($delimiter)) {
      $delimiter = ' » ';
    }
    return '<div class="breadcrumb">'. implode($delimiter, $breadcrumb) .'</div>';
  }
}

/* Returns the rendered local tasks.  */

function phptemplate_comment_submitted($comment) {
  return t('!datetime — !username',
    array(
      '!username' => theme('username', $comment),
      '!datetime' => format_date($comment->timestamp)
    ));
}

/*
 * Implementation of theme_node_submitted
 *
 * For events, profiles, projects and initiatives the 'posted' becomes a
 * 'last updated'
 */
function phptemplate_node_submitted($node) {
  switch ($node->type) {
    case 'event':
    case 'imported_event':
    case 'initiative_event':
    case 'profile':
    case 'initiative_profile':
    case 'project_profile':

      $time_unit = 86400; // number of seconds in 1 day => 24 hours * 60 minutes * 60 seconds
      $threshold = 1;

      if ($node->changed && (round(($node->changed - $node->created) / $time_unit) > $threshold)){ // difference between created and changed times > than threshold
        return t('Last updated @changed', array(
          '@changed' => format_date($node->changed, 'custom' , 'l, d F Y'),
    //      '!username' => theme('username', $node),
    //      '@created' => format_date($node->created, 'small'),
        ));
      }
      else {
        return t('Last updated @datetime', array(
    //        '!username' => theme('username', $node),
            '@datetime' => format_date($node->created, 'custom' , 'l, d F Y'),
          ));
      }
      break;

      default:
        return t('Published on @date by !username',
        array(
          '@date' => format_date($node->created, 'custom', 'F j, Y'),
          '!username' => theme('username', $node)
        ));
        break;
  }

}

/*
 * Implementation of theme_username
 *
 * Users profile node title and links.
 */
function transition2_username($object) {

  if ($object->uid && $object->name) {
    // Shorten the name when it is too long or it will break many tables.
    if (drupal_strlen($object->name) > 20) {
      $name = drupal_substr($object->name, 0, 15) .'...';
    }
    else {
      $name = $object->name;
    }

    if (user_access('access user profiles')) {
      // JK changes are here - if no CP module, assume user must go to main site
      if (module_exists('content_profile')) {
        $content_profile = content_profile_load('profile', $object->uid);
        $output = l($name, 'node/'. $content_profile->nid);
      }
      else {
        $output = l(
          $name,
          'user/'. $object->uid,
          array(
            'attributes' => array('title' => t('View user profile.')),
            'absolute' => TRUE
          )
        );
      }
    }
    else {
      $output = check_plain($name);
    }
  }
  else if ($object->name) {
    // Sometimes modules display content composed by people who are
    // not registered members of the site (e.g. mailing list or news
    // aggregator modules). This clause enables modules to display
    // the true author of the content.
    if (!empty($object->homepage)) {
      $output = l($object->name, $object->homepage, array('attributes' => array('rel' => 'nofollow')));
    }
    else {
      $output = check_plain($object->name);
    }

    $output .= ' ('. t('not verified') .')';
  }
  else {
    $output = check_plain(variable_get('anonymous', t('Anonymous')));
  }

  return $output;
}

/*
 * Implementation of hook_preprocess_user_picture
 *
 * Overridden to use content profile links.
 */
function transition2_preprocess_user_picture(&$variables) {
  $variables['picture'] = '';

  if (variable_get('user_pictures', 0)) {
    $account = $variables['account'];
    if (!empty($account->picture) && file_exists($account->picture)) {
      $picture = file_create_url($account->picture);
    }
    else if (variable_get('user_picture_default', '')) {
      $picture = variable_get('user_picture_default', '');
    }

    if (isset($picture)) {
      $alt = t("@user's picture", array('@user' => $account->name ? $account->name : variable_get('anonymous', t('Anonymous'))));
      $variables['picture'] = theme('image', $picture, $alt, $alt, '', FALSE);

      if (!empty($account->uid) && user_access('access user profiles')) {
        if (module_exists('content_profile')) {
          // have content profile, must be on the main site
          $attributes = array('attributes' => array('title' => t('View user profile.')), 'html' => TRUE);
          $content_profile = content_profile_load('profile', $account->uid);
          $variables['picture'] = l($variables['picture'], "node/$content_profile->nid", $attributes);
        }
        else {
          // use simplified version
          $attributes = array(
            'attributes' => array('title' => t('View user profile.')),
            'html' => TRUE,
            'absolute' => TRUE);
          $variables['picture'] = l($variables['picture'], "user/$account->uid", $attributes);
        }
      }
    }
  }
}


/*
 * Implementation of theme_advanced_forum_user_picture
 * (Author Pane)
 *
 * Overridden to use content profile links.
 */
function transition2_advanced_forum_user_picture($account) {
  // Get the imagecache preset to use, if any.
  $preset = variable_get('advanced_forum_user_picture_preset', '');

  // Only proceed if user pictures are enabled, and there is a preset set, and
  // imagecache is enabled. For performace reasons, we return nothing if these
  // critera aren't met because we only call this function when the non
  // imagecached version is already created. If you use this function elsewhere
  // you will need to provide a picture when imagecache is not used.
  if (variable_get('user_pictures', 0) && !empty($preset) && module_exists('imagecache')) {
    if ($account->picture && file_exists($account->picture)) {
      // User has picture, so use that
      $alt = t("@user's picture", array('@user' => $account->name ? $account->name : 'Visitor'));
      $picture = theme('imagecache', $preset, $account->picture);
    }
    else {
      // User doesn't have a picture so use the default, if any
      $default_picture = variable_get('user_picture_default', '');
      if ($default_picture) {
        $picture = theme('imagecache', $preset, $default_picture);
      }
    }

    if (!empty($picture)) {
      // If the user has access to view profiles, link the picture
      if (!empty($account->uid) && user_access('access user profiles') && module_exists('content_profile')) {
        $content_profile = content_profile_load('profile', $account->uid);
        $picture = l($picture, "node/$content_profile->nid", array('title' => t('View user profile.'), 'html' => TRUE));
      }
    }

    return '<div class="picture">' . $picture . '</div>';
  }
}

/**
 * Implementation of hook_theme().
 * This function provides a one-stop reference for all
 */
function transition2_status_messages($display = NULL) {
  $output = '';
  foreach (drupal_get_messages($display) as $type => $messages) {
    $output .= "<div class=\"messages $type\">\n";
    $output .= '<span class="icon">&nbsp;</span>';
    $output .= '<div class="inner">';
      if (count($messages) > 1) {
      $output .= " <ul>\n";
      foreach ($messages as $message) {
        $output .= '  <li>'. $message ."</li>\n";
      }
      $output .= " </ul>\n";
    }
    else {
      $output .= $messages[0];
    }
    $output .= "</div>\n";
    $output .= "</div>\n";
  }
  return $output;
}

function transition2_status_report($requirements) {
  $i = 0;
  $output = '<table class="system-status-report">';
  foreach ($requirements as $requirement) {
    if (empty($requirement['#type'])) {
      $class = ++$i % 2 == 0 ? 'even' : 'odd';

      $classes = array(
        REQUIREMENT_INFO => 'info',
        REQUIREMENT_OK => 'ok',
        REQUIREMENT_WARNING => 'warning',
        REQUIREMENT_ERROR => 'error',
      );
      $class = $classes[isset($requirement['severity']) ? (int)$requirement['severity'] : 0] .' '. $class;

      // Output table row(s)
      if (!empty($requirement['description'])) {
        $output .= '<tr class="'. $class .' merge-down"><th><span class="icon">&nbsp;</span>'. $requirement['title'] .'</th><td>'. $requirement['value'] .'</td></tr>';
        $output .= '<tr class="'. $class .' merge-up"><td colspan="2">'. $requirement['description'] .'</td></tr>';
      }
      else {
        $output .= '<tr class="'. $class .'"><th><span class="icon">&nbsp;</span>'. $requirement['title'] .'</th><td>'. $requirement['value'] .'</td></tr>';
      }
    }
  }

  $output .= '</table>';
  return $output;
}

function phptemplate_upload_attachments($files) {
  $rows = array();
  foreach ($files as $file) {
    $file = (object)$file;
    if ($file->list && empty($file->remove)) {
      $href = file_create_url($file->filepath);
      $text = "Download $file->description";
      $rows[] = array(l($text, $href));
    }
  }
  if (count($rows)) {
    return theme('item_list', $rows, NULL, 'ul', array('id' => 'attachments'));
  }
}


/** Form rendering overrides **/

/*
 * Implementation of theme_form_element
 *
 * This version is the same as default except puts the description under
 * the title rather than at the end.
 *
 */
function transition2_form_element($element, $value) {
  // This is also used in the installer, pre-database setup.
  $t = get_t();

  $output = '<div class="form-item"';
  if (!empty($element['#id'])) {
    $output .= ' id="'. $element['#id'] .'-wrapper"';
  }
  $output .= ">\n";
  $required = !empty($element['#required']) ? '<span class="form-required" title="'. $t('This field is required.') .'">*</span>' : '';

  if (!empty($element['#title'])) {
    $title = $element['#title'];
    if (!empty($element['#id'])) {
      $output .= ' <label for="'. $element['#id'] .'">'. $t('!title: !required', array('!title' => filter_xss_admin($title), '!required' => $required)) ."</label>\n";
    }
    else {
      $output .= ' <label>'. $t('!title: !required', array('!title' => filter_xss_admin($title), '!required' => $required)) ."</label>\n";
    }
  }

  if ($element['#type'] == 'radio') { // normal (Drupal Default) behaviour for radios and
    $output .= " $value\n";
    if (!empty($element['#description'])) {
      $output .= ' <div class="description">'. $element['#description'] ."</div>\n";
    }
  }
  else { // all other form controls, place description under title
    if (!empty($element['#description'])) {
      $output .= ' <div class="description">'. $element['#description'] ."</div>\n";
    }
    $output .= " $value\n";

  }

  $output .= "</div>\n";

  return $output;
}

/*
 * Implementation of theme_form_element (CCK: content.module)
 *
 * Combine multiple values into a table with drag-n-drop reordering.
 *
 * This version is the same as default except puts the description in a
 * cell under the title rather than at the end.
 *
 */
function transition2_content_multiple_values($element) {
  $field_name = $element['#field_name'];
  $field = content_fields($field_name);
  $output = '';

  if ($field['multiple'] >= 1) {
    $table_id = $element['#field_name'] .'_values';
    $order_class = $element['#field_name'] .'-delta-order';
    $required = !empty($element['#required']) ? '<span class="form-required" title="'. t('This field is required.') .'">*</span>' : '';

    $header = array(
      array(
        'data' => t('!title: !required', array('!title' => $element['#title'], '!required' => $required)),
        'colspan' => 2
      ),
      t('Order'),
    );
    $rows = array();

    if($element['#description']) {
      $rows[] = array(
        'cells' => array(
          'data' => '<div class="description">'. $element['#description'] .'</div>',
          'colspan' => 3
          )
      );
    }

    // Sort items according to '_weight' (needed when the form comes back after
    // preview or failed validation)
    $items = array();
    foreach (element_children($element) as $key) {
      if ($key !== $element['#field_name'] .'_add_more') {
        $items[] = &$element[$key];
      }
    }
    usort($items, '_content_sort_items_value_helper');

    // Add the items as table rows.
    foreach ($items as $key => $item) {
      $item['_weight']['#attributes']['class'] = $order_class;
      $delta_element = drupal_render($item['_weight']);
      $cells = array(
        array('data' => '', 'class' => 'content-multiple-drag'),
        drupal_render($item),
        array('data' => $delta_element, 'class' => 'delta-order'),
      );
      $rows[] = array(
        'data' => $cells,
        'class' => 'draggable',
      );
    }

    $output .= theme('table', $header, $rows, array('id' => $table_id, 'class' => 'content-multiple-table'));
    //$output .= $element['#description'] ? '<div class="description">'. $element['#description'] .'</div>' : '';
    $output .= drupal_render($element[$element['#field_name'] .'_add_more']);

    drupal_add_tabledrag($table_id, 'order', 'sibling', $order_class);
  }
  else {
    foreach (element_children($element) as $key) {
      $output .= drupal_render($element[$key]);
    }
  }

  return $output;
}


/*
 * Implementation of theme_button
 *
 * This version is the same as default except allows certain buttons to
 * be re-labeled
 *
 */
function transition2_button($element) {

  // JK change button titles for certain contexts
  switch ($element['#id']) {
    // Views filters on directories should say 'Search'
    case 'edit-submit-initiative-directory':
    case 'edit-submit-people-directory':
    case 'edit-submit-project-directory':
    case 'edit-submit-cms':
      $element['#value'] = 'Search';
      break;
  }

  // Make sure not to overwrite classes.
  if (isset($element['#attributes']['class'])) {
    $element['#attributes']['class'] = 'form-'. $element['#button_type'] .' '. $element['#attributes']['class'];
  }
  else {
    $element['#attributes']['class'] = 'form-'. $element['#button_type'];
  }

  return '<input type="submit" '. (empty($element['#name']) ? '' : 'name="'. $element['#name'] .'" ') .'id="'. $element['#id'] .'" value="'. check_plain($element['#value']) .'" '. drupal_attributes($element['#attributes']) ." />\n";
}

/**
 * Override the search box to add our pretty graphic instead of the button.
 */
function transition2_search_theme_form($form) {
  $form['submit']['#type'] = 'image_button';
  $form['submit']['#src'] = drupal_get_path('theme', 'transition2') . '/images/search.png';
  $form['submit']['#attributes']['class'] = 'btn';
  $form['submit']['#attributes']['alt'] = 'search';
  return '<div id="search" class="container-inline">' . drupal_render($form) . '</div>';
}


/**
 * Implementation of hook_views_query_alter().
 *
 * Sets a context with the display plugin is 'web_widgets.'
 */
function transition2_widgets_views_query_alter($view) {
  if (module_exists('context')) {
    if ($view->display_handler->display->display_plugin == 'web_widgets') {
      context_set('t_widgets', 'widget_active', TRUE);
    }
  }
}

/**
 * Implementation of hook_web_widgets_render_widget().
 *
 * Adds relevent stylesheets.
 */
function transition2_widgets_web_widgets_render_widget() {
  drupal_add_css(drupal_get_path('theme', 'transition2') . 'widget/widgets.css', 'widget');
}

/**
 * Implementation of hook_theme_registry_alter().
 */
function transition2_widgets_theme_registry_alter(&$theme_registry) {
  $path = drupal_get_path("theme", "transition2") . '/widget';
  $theme_registry['web_widgets_iframe']['path'] = $path;
  $theme_registry['web_widgets_iframe']['preprocess functions'][] = 'template_preprocess_widget';
}


/**
 * Add ids to menu items for image link change on home
 */
function transition2_menu_item_link($link) {
  if (empty($link['localized_options'])) {
    $link['localized_options'] = array();
  }
  if (isset($link['mlid'])) {
    if (empty($link['localized_options']['attributes']['class'])) {
      $link['localized_options']['attributes']['class'] = 'menu-'. $link['mlid'];
    }
    else {
      $link['localized_options']['attributes']['class'] .= ' menu-'. $link['mlid'];
    }
  }
  return l($link['title'], $link['href'], $link['localized_options']);
}

/**
 * Adds jquery for the What Why How Where menu
 */
function transition2_preprocess_page(&$vars) {
	jquery_ui_add('ui.accordion');
	$path_js = drupal_get_path('theme', 'transition2').'/js/';
  	drupal_add_js($path_js.'wwhw_menu.js');

}

function transition2_imagecache_formatter_featured_image_default($element) {
  // Inside a view $element may contain NULL data. In that case, just return.
  if (empty($element['#item']['fid'])) {
    return '';
  }

  // Extract the preset name from the formatter name.
  $presetname = substr($element['#formatter'], 0, strrpos($element['#formatter'], '_'));
  $style = 'linked';
  $style = 'default';

  $item = $element['#item'];
  $item['data']['alt'] = isset($item['data']['alt']) ? $item['data']['alt'] : '';
  $item['data']['title'] = isset($item['data']['title']) ? $item['data']['title'] : NULL;

  $class = "imagecache imagecache-$presetname imagecache-$style imagecache-{$element['#formatter']} caption";
  return theme('imagecache', $presetname, $item['filepath'], $item['data']['alt'], $item['data']['title'], array('class' => $class));
}


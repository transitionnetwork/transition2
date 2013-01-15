<?php

/**
 * Implementation of themehook_settings().
 */
function transition2_settings($saved_settings) {
  $defaults = array(
    'breadcrumb_delimiter' => ' » ',
  );
  $settings = array_merge($defaults, $saved_settings);
  
  $form['breadcrumb_delimiter'] = array(
    '#type' => 'textfield',
    '#title' => t('Breadcrumb delimiter'),
    '#size' => 4,
    '#default_value' => $settings['breadcrumb_delimiter'],
    '#description' => t("Don't forget spaces at either end... if you're into that sort of thing."),
  );
    
  return $form;
}
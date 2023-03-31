<?php

/*
  Check if debug mode is enabled
*/

if (!function_exists('is_debug_enabled')) {
  function is_debug_enabled()
  {
    if (defined('WP_DEBUG') && true === WP_DEBUG) {
      return true;
    }
    return false;
  }
}

/*
  Write logs to wp-content/debug.log

  Will only write logs if debug is enabled in WP config
*/

if (!function_exists('write_log')) {
  function write_log($log)
  {
    if (!is_debug_enabled()) {
      return;
    }
    if (is_array($log) || is_object($log)) {
      error_log(print_r($log, true));
    } else {
      error_log($log);
    }
  }
}

/*
  Print any type of variable nicely to the page, including object and arrays
*/

if (!function_exists('pretty_print')) {
  function pretty_print($arr)
  {
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
  }
}

<?php
/**
 * Plugin Name:       Next WP
 * Plugin URI:        https://github.com/Stikky-Media/next-wp-plugins
 * Description:       Base plugin for Next-WP configuration 
 * Author:            Cloak Labs
 * Author URI:        https://www.stikkymedia.com/
 * Version:           0.5.0
 * Requires at least: 5.5
 * Requires PHP:      7.0
 */

namespace Next_WP;

require_once __DIR__ . '/src/helpers.php';
require_once __DIR__ . '/admin/settings.php';
require_once __DIR__ . '/src/functions.php';
require_once __DIR__ . '/rest-api/rest-api.php';
require_once __DIR__ . '/seo/seo.php';



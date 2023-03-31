<?php

/*
  Register Menu Page and sub-pages
*/

add_action('admin_menu', 'nextwp_add_admin_menu');

function nextwp_add_admin_menu()
{
  add_menu_page(
    'NextWP',
    'NextWP',
    'manage_options',
    'next_wp',
    'nextwp_options_page',
    'data:image/svg+xml;base64,' . base64_encode('<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="m22.43.01-.73.07C14.88.69 8.5 4.37 4.45 10.02A23.75 23.75 0 0 0 .22 20.51a18.3 18.3 0 0 0-.22 3.5c0 1.78.02 2.17.22 3.49A24.1 24.1 0 0 0 21.7 47.94c.73.08 3.87.08 4.6 0a24.22 24.22 0 0 0 8.65-2.53c.4-.2.49-.27.43-.31-.03-.03-1.8-2.4-3.9-5.24l-3.84-5.19-4.81-7.11a688.2 688.2 0 0 0-4.84-7.12c-.02 0-.04 3.16-.05 7.02-.02 6.76-.02 7.04-.1 7.2a.85.85 0 0 1-.42.42c-.15.08-.28.1-.99.1h-.81l-.22-.15a.88.88 0 0 1-.31-.34l-.1-.2.01-9.42.02-9.4.14-.19c.08-.1.24-.22.35-.29.19-.09.27-.1 1.08-.1.95 0 1.11.04 1.36.31.07.08 2.68 4 5.8 8.72l9.46 14.34 3.8 5.76.2-.13c1.7-1.1 3.5-2.68 4.92-4.32a23.89 23.89 0 0 0 5.65-12.27c.2-1.32.22-1.7.22-3.5 0-1.78-.02-2.17-.22-3.49A24.1 24.1 0 0 0 26.37.07c-.45-.04-3.55-.1-3.94-.06zm9.82 14.52a.95.95 0 0 1 .48.55c.03.12.04 2.73.03 8.61v8.44l-1.5-2.28-1.49-2.28v-6.14c0-3.96.02-6.19.05-6.3a.96.96 0 0 1 .46-.59c.2-.1.26-.1 1-.1.7 0 .82 0 .97.09z" fill="#000"/></svg>')
  );
  add_submenu_page('next_wp', 'Posts/Pages', 'Posts/Pages', 'manage_options', 'next_wp_posts_settings', 'posts_settings_page');
  add_submenu_page('next_wp', 'API', 'API', 'manage_options', 'next_wp_rest_settings', 'rest_api_settings_page');
  add_submenu_page('next_wp', 'Yoast SEO', 'Yoast SEO', 'manage_options', 'next_wp_seo_settings', 'seo_settings_page');
}

/*
  Rendering functions for each menu and sub-menu page
*/

function nextwp_options_page()
{
?>
  <form action='options.php' method='post'>
    <h2>NextWP Plugin Settings</h2>
    <?php
    settings_fields('next_wp_settings');
    do_settings_sections('next_wp_settings');
    submit_button();
    ?>
  </form>
<?php
}

function posts_settings_page()
{
?>
  <form action='options.php' method='post'>
    <h2>NextWP Plugin Settings</h2>
    <?php
    settings_fields('next_wp_posts_settings');
    do_settings_sections('next_wp_posts_settings');
    submit_button();
    ?>
  </form>
<?php
}

function rest_api_settings_page()
{
?>
  <form action='options.php' method='post'>
    <h2>NextWP Plugin Settings</h2>
    <?php
    settings_fields('next_wp_rest_settings');
    do_settings_sections('next_wp_rest_settings');
    do_settings_sections('next_wp_rest_route_settings');
    submit_button();
    ?>
  </form>
<?php
}

function seo_settings_page()
{
?>
  <form action='options.php' method='post'>
    <h2>NextWP Plugin Settings</h2>
    <?php
    settings_fields('next_wp_seo_settings');
    do_settings_sections('next_wp_seo_settings');
    submit_button();
    ?>
  </form>
<?php
}


/*
  Initialize settings object, page sections, and fields
*/

add_action('admin_init', 'next_wp_settings_init');
function next_wp_settings_init()
{

  register_setting('next_wp_settings', 'next_wp_settings');
  register_setting('next_wp_posts_settings', 'next_wp_posts_settings');
  register_setting('next_wp_rest_settings', 'next_wp_rest_settings');
  register_setting('next_wp_seo_settings', 'next_wp_seo_settings');


  /*
    Configuration Section
  */

  add_settings_section(
    'config_section',
    __('Configuration', 'nextwp'),
    'nextwp_config_section_callback',
    'next_wp_settings'
  );

  add_settings_field(
    'next_frontend_url',
    __('Next Frontend URL', 'nextwp'),
    'next_frontend_url_render',
    'next_wp_settings',
    'config_section'
  );

  add_settings_field(
    'next_preview_secret',
    __('Next Preview Secret', 'nextwp'),
    'next_preview_secret_render',
    'next_wp_settings',
    'config_section'
  );

  add_settings_field(
    'enable_dev_mode',
    __('Enable Dev Mode?', 'nextwp'),
    'enable_dev_mode_render',
    'next_wp_settings',
    'config_section'
  );

  /*
    Page/Post Settings
  */

  add_settings_section(
    'post_settings_section',
    __('Page/Posts Settings', 'nextwp'),
    'nextwp_posts_section_callback',
    'next_wp_posts_settings'
  );

  add_settings_field(
    'enable_view_post',
    __("Override 'View Post' Links?", 'nextwp'),
    'enable_view_post_render',
    'next_wp_posts_settings',
    'post_settings_section'
  );

  add_settings_field(
    'enable_preview_post',
    __("Override 'Preview' Links?", 'nextwp'),
    'enable_preview_post_render',
    'next_wp_posts_settings',
    'post_settings_section'
  );

  add_settings_field(
    'enable_isr',
    __("Enable ISR on save & new page creation?", 'nextwp'),
    'enable_isr_render',
    'next_wp_posts_settings',
    'post_settings_section'
  );

  /*
    Rest API Settings
  */

  // WP API
  add_settings_section(
    'rest_api_settings_section',
    __('WP REST API Settings', 'nextwp'),
    'nextwp_rest_api_section_callback',
    'next_wp_rest_settings'
  );
 
  add_settings_field(
    'enable_favicon',
    __("Add favicon url to page/post requests?", 'nextwp'),
    'enable_favicon_render',
    'next_wp_rest_settings',
    'rest_api_settings_section'
  );

  add_settings_field(
    'enable_jwt_no_expiry',
    __("Set JWT to never expire?", 'nextwp'),
    'enable_jwt_no_expiry_render',
    'next_wp_rest_settings',
    'rest_api_settings_section'
  );
  
  // Next API
  add_settings_section(
    'next_api_settings_section',
    __('Next Front-End API Settings', 'nextwp'),
    'nextwp_next_api_section_callback',
    'next_wp_rest_settings'
  );

  add_settings_field(
    'preview_api_route',
    __('Preview API route <br> (default: "preview")', 'nextwp'),
    'preview_api_route_render',
    'next_wp_rest_settings',
    'next_api_settings_section'
  );
  
  add_settings_field(
    'revalidate_api_route',
    __('ISR Revalidate API route <br> (default: "revalidate")', 'nextwp'),
    'revalidate_api_route_render',
    'next_wp_rest_settings',
    'next_api_settings_section'
  );
  
  add_settings_field(
    'login_api_route',
    __('Login API route <br> (default: "login")', 'nextwp'),
    'login_api_route_render',
    'next_wp_rest_settings',
    'next_api_settings_section'
  );
  
  add_settings_field(
    'logout_api_route',
    __('Logout API route <br> (default: "logout")', 'nextwp'),
    'logout_api_route_render',
    'next_wp_rest_settings',
    'next_api_settings_section'
  );

  /*
    SEO Settings
  */

  add_settings_section(
    'seo_settings_section',
    __('Yoast SEO Settings', 'nextwp'),
    'nextwp_seo_section_callback',
    'next_wp_seo_settings'
  );

  add_settings_field(
    'enable_url_override',
    __("Change Metadata to Use Frontend URL?", 'nextwp'),
    'enable_url_override_render',
    'next_wp_seo_settings',
    'seo_settings_section'
  );
}

/*
  Section Rendering functions
*/

function nextwp_config_section_callback()
{
  echo __('Basic config settings', 'nextwp');
}

function nextwp_posts_section_callback()
{
  echo __('Configure URLs in Wordpress admin to point to Next frontend pages.', 'nextwp');
}

function nextwp_rest_api_section_callback()
{
  echo __('Add additional functionality to the WP REST API.', 'nextwp');
}

function nextwp_next_api_section_callback()
{
  echo __("Optional: if you deviate from the default next-wp API route naming conventions on your Next front-end, make sure to add your custom route naming below (otherwise WP can't communicate with your front-end). For example, if you nested your 'preview' API route under a folder (i.e. /api/wp/preview), you would enter 'wp/preview' below as your Preview API route (exclude initial & trailing slash).", 'nextwp');
}

function nextwp_seo_section_callback()
{
  echo __('Add additional functionality to Yoast SEO.', 'nextwp');
}

/*
  Field Rendering functions
*/

function next_frontend_url_render()
{
  $options = get_option('next_wp_settings'); ?>
  <input type='text' name='next_wp_settings[next_frontend_url]' value='<?php echo $options['next_frontend_url']; ?>'>
<?php
}


function next_preview_secret_render()
{
  $options = get_option('next_wp_settings'); ?>
  <input type='text' name='next_wp_settings[next_preview_secret]' value='<?php echo $options['next_preview_secret']; ?>'>
<?php
}


function enable_dev_mode_render()
{
  $options = get_option('next_wp_settings'); ?>
  <input type='checkbox' name='next_wp_settings[enable_dev_mode]' <?php checked($options['enable_dev_mode'], 1); ?> value='1'>
  <p class="description">
    <?php esc_html_e("Sets the 'Next Frontend URL' to http://localhost:3000", 'nextwp'); ?>
  </p>
<?php
}

function enable_view_post_render()
{
  $options = get_option('next_wp_posts_settings'); ?>
  <input type='checkbox' name='next_wp_posts_settings[enable_view_post]' <?php checked($options['enable_view_post'], 1); ?> value='1'>
<?php
}

function enable_preview_post_render()
{
  $options = get_option('next_wp_posts_settings'); ?>
  <input type='checkbox' name='next_wp_posts_settings[enable_preview_post]' <?php checked($options['enable_preview_post'], 1); ?> value='1'>
<?php
}

function enable_isr_render()
{
  $options = get_option('next_wp_posts_settings'); ?>
  <input type='checkbox' name='next_wp_posts_settings[enable_isr]' <?php checked($options['enable_isr'], 1); ?> value='1'>
<?php
}


function enable_favicon_render()
{
  $options = get_option('next_wp_rest_settings'); ?>
  <input type='checkbox' name='next_wp_rest_settings[enable_favicon]' <?php checked($options['enable_favicon'], 1); ?> value='1'>
<?php
}


function enable_jwt_no_expiry_render()
{
  $options = get_option('next_wp_rest_settings'); ?>
  <input type='checkbox' name='next_wp_rest_settings[enable_jwt_no_expiry]' <?php checked($options['enable_jwt_no_expiry'], 1); ?> value='1'>
<?php
}

function preview_api_route_render()
{
  $options = get_option('next_wp_rest_settings'); ?>
  <input type='text' name='next_wp_rest_settings[preview_api_route]' value='<?php echo $options['preview_api_route']; ?>'>
<?php
}

function revalidate_api_route_render()
{
  $options = get_option('next_wp_rest_settings'); ?>
  <input type='text' name='next_wp_rest_settings[revalidate_api_route]' value='<?php echo $options['revalidate_api_route']; ?>'>
<?php
}

function login_api_route_render()
{
  $options = get_option('next_wp_rest_settings'); ?>
  <input type='text' name='next_wp_rest_settings[login_api_route]' value='<?php echo $options['login_api_route']; ?>'>
<?php
}

function logout_api_route_render()
{
  $options = get_option('next_wp_rest_settings'); ?>
  <input type='text' name='next_wp_rest_settings[logout_api_route]' value='<?php echo $options['logout_api_route']; ?>'>
<?php
}

function enable_url_override_render()
{
  $options = get_option('next_wp_seo_settings'); ?>
  <input type='checkbox' name='next_wp_seo_settings[enable_url_override]' <?php checked($options['enable_url_override'], 1); ?> value='1'>
<?php
}

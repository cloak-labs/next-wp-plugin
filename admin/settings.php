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
}

/*
  Rendering functions for each menu and sub-menu page
*/

function nextwp_options_page()
{
?>
  <div>
    <h2>NextWP Configuration</h2>
    <?php
    settings_fields('next_wp_settings');
    do_settings_sections('next_wp_settings');
    ?>
  </div>
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
    __('Configuration Variables', 'nextwp'),
    'nextwp_config_section_callback',
    'next_wp_settings'
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


  // Next API
  add_settings_section(
    'next_api_settings_section',
    __('Next Front-End API Settings', 'nextwp'),
    'nextwp_next_api_section_callback',
    'next_wp_rest_settings'
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

}

/*
  Section Rendering functions
*/

function echo_variable($var)
{
  if (isset($var)) {
    if (gettype($var) === 'boolean') {
      if ($var === TRUE) {
        echo "<span>TRUE</span>";
      }
      if ($var === FALSE) {
        echo "<span>FALSE</span>";
      }
    }
    if (gettype($var) === 'string') {
      if (strlen($var) > 0) {
        echo "<span>'" . $var . "'</span>";
      }
      if (strlen($var) === 0) {
        echo "<span>''</span>";
      }
    }
    echo "<span> (" . gettype($var) . ")</span>";
  } else {
    echo "<span>Unset</span>";
  }
}


function nextwp_settings_row($name)
{
  if (defined($name)) {
    $var = constant($name);
  } else {
    $var = NULL;
  }
?>
  <tr>
    <th scope="row">
      <?php echo $name ?>
    </th>
    <td><?php echo_variable($var); ?></td>
  </tr>
<?php
}

function nextwp_config_section_callback()
{ ?>
  <table class="form-table" role="presentation">
    <tbody>
      <?php
      nextwp_settings_row('NEXT_WP_NEXT_FRONTEND_URL');
      nextwp_settings_row('NEXT_WP_PREVIEW_SECRET');
      nextwp_settings_row('NEXT_WP_ENABLE_DEV_MODE');
      nextwp_settings_row('NEXT_WP_ENABLE_ISR');
      nextwp_settings_row('NEXT_WP_OVERRIDE_VIEW_POST_LINK');
      nextwp_settings_row('NEXT_WP_OVERRIDE_PREVIEW_POST_LINK');
      nextwp_settings_row('NEXT_WP_YOAST_USE_FRONTEND_URL');
      nextwp_settings_row('NEXT_WP_ENABLE_FAVICON');
      nextwp_settings_row('NEXT_WP_JWT_NO_EXPIRY');
      nextwp_settings_row('NEXT_WP_ENABLE_PREVIEW_POST');
      nextwp_settings_row('NEXT_WP_PREVIEW_API_ROUTE');
      nextwp_settings_row('NEXT_WP_REVALIDATE_API_ROUTE');
      nextwp_settings_row('NEXT_WP_LOGIN_API_ROUTE');
      nextwp_settings_row('NEXT_WP_LOGOUT_API_ROUTE');
      ?>
    </tbody>
  </table>
<?php
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
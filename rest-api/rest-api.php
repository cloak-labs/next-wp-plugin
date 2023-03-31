<?php

/*
  Get Options from settings
*/

$options = get_option('next_wp_rest_settings');
$enable_favicon = $options['enable_favicon'] ?? NULL;
$enable_jwt_no_expiry = $options['enable_jwt_no_expiry'] ?? NULL;

/*
  Expose 32x32 favicon to post and page rest-api requests
*/

if ($enable_favicon) {
  add_action('rest_api_init', 'add_page_favicon', 10);
  function add_page_favicon()
  {
    register_rest_field(
      'page',
      'favicon_url',
      array(
        'get_callback'    => 'get_favicon',
        'update_callback' => null,
        'schema'          => null,
      )
    );
  }

  add_action('rest_api_init', 'add_post_favicon', 10);
  function add_post_favicon()
  {
    register_rest_field(
      'post',
      'favicon_url',
      array(
        'get_callback'    => 'get_favicon',
        'update_callback' => null,
        'schema'          => null,
      )
    );
  }

  function get_favicon($object, $field_name, $request)
  {
    return get_site_icon_url(32);
  }
}


/*
  Make sure revision data includes static ACF field values (doesn't be default) so that the next-wp preview feature works properly
*/

add_filter( 'rest_prepare_revision', function( $response, $post ) {
  $data = $response->get_data();
  $data['acf'] = get_fields( $post->post_parent );
  return rest_ensure_response( $data );
}, 10, 2 );


/*
  Set custom expiry of JWT Token used for REST API Authentication
*/

if ($enable_jwt_no_expiry) {
  add_filter('jwt_auth_expire', 'on_jwt_expire_token', 10, 1);
  function on_jwt_expire_token($exp)
  { // add custom expiry date to our JWT (hook provided by "JWT Authentication for WP-API" plugin)
    $days = 500000; // 500,000 days == expiry in the year 3391.. i.e. we don't want the JWT to expire because the front-end data fetching will break 
    $seconds_in_a_day = 86400;
    $exp = time() + ($seconds_in_a_day * $days);
    return $exp;
  }
}

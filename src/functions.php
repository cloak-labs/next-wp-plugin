<?php


/*
  Get Options from settings
*/

$main_options = get_option('next_wp_settings');
$next_frontend_url = $main_options['next_frontend_url'] ?? NULL;
$next_preview_secret = $main_options['next_preview_secret'] ?? NULL;
$enable_dev_mode = $main_options['enable_dev_mode'] ?? NULL;

$posts_options = get_option('next_wp_posts_settings');
$enable_view_post = $posts_options['enable_view_post'] ?? NULL;
$enable_preview_post = $posts_options['enable_preview_post'] ?? NULL;
$enable_isr = $posts_options['next_frontend_url'] ?? NULL;


/*
  Return frontend URL
*/

function get_frontend_url()
{
  $options = get_option('next_wp_settings');
  $next_frontend_url = $options['next_frontend_url'] ?? NULL;
  $enable_dev_mode = $options['enable_dev_mode'] ?? NULL;

  if ($enable_dev_mode) {
    return "http://localhost:3000";
  }

  if ($next_frontend_url) {
    return $next_frontend_url;
  }

  $var = true;
  return site_url();
}

/*
  Return Preview Secret
*/

function get_preview_secret()
{
  $options = get_option('next_wp_settings');
  $next_preview_secret = $options['next_preview_secret'] ?? NULL;

  if ($next_preview_secret) {
    return $next_preview_secret;
  }

  return "";
}

/*
  Modify 'View Post' links on posts, pages to point to frontend URL
*/

if ($enable_view_post) {
  add_filter('page_link', 'custom_view_page_url', 10, 2);
  add_filter('post_link', 'custom_view_page_url', 10, 2);
  add_filter('post_type_link', 'custom_view_page_url', 10, 2);
  function custom_view_page_url($permalink, $post)
  {
    $custom_permalink = get_frontend_url();
    if ($permalink) {
      $custom_permalink = str_replace(home_url(), $custom_permalink,  $permalink);
    }
    return $custom_permalink;
  };
}

/*
  Modify 'Preview' links on posts, pages to point to frontend URL
  Request to NextJS API Route generates the frontend page using preview data and redirects to it
  Requires a 'preview secret' as a query param to match on Wordpress and NextJS server
*/

add_filter('preview_post_link', 'preview_url', 10);
function preview_url()
{
  $posts_options = get_option('next_wp_posts_settings');
  $enable_preview_post = $posts_options['enable_preview_post'] ?? NULL;
  
  $api_options = get_option('next_wp_rest_settings');
  $preview_api_route = $api_options['preview_api_route'] ? $api_options['preview_api_route'] : 'preview';

  if ($enable_preview_post) {
    global $post;
    $revisionId = $post->ID; // the ID of the post revision, not the master post
    $postId = $post->post_parent; // the revision's parent == the post we're previewing
    $postType = get_post_type($postId); // the master/parent post's post type --> important for next-wp to retrieve the correct revision data  
    $secret = get_preview_secret();
    $front_end_url = get_frontend_url();
    return "{$front_end_url}/api/{$preview_api_route}?revisionId={$revisionId}&postId={$postId}&postType={$postType}&secret={$secret}";
  }
}

/*
  Redirect WP preview page to Next preview --> this is in addition to our 'preview_post_link' filter above that changes the preview link (which doesn't work all the time due to known bugs).
  If somehow our 'preview_post_link' filter doesn't work and the admin user ends up on the default WP preview URL, this redirects them to our Next preview API route
*/
add_action("template_redirect", function () {
	if (isset($_GET["preview"]) && $_GET["preview"] == true) {
    $front_end_url = get_frontend_url();
    $api_options = get_option('next_wp_rest_settings');
    $preview_api_route = $api_options['preview_api_route'] ? $api_options['preview_api_route'] : 'preview';
    $secret = get_preview_secret();
    $postId = $_GET["p"];
    $postType = get_post_type($postId); // the master/parent post's post type --> important for next-wp to retrieve the correct revision data  
		wp_redirect( "{$front_end_url}/api/{$preview_api_route}?postId={$postId}&postType={$postType}&secret={$secret}" );
    exit();
	}
});


/*
  Override the href for the site name & view site links (to use our Next front-end URL) in the WP admin toolbar, and open them in new tabs
*/
add_action( 'admin_bar_menu', 'customize_my_wp_admin_bar', 80 );
function customize_my_wp_admin_bar( $wp_admin_bar ) {

    // Get references to the 'view-site' and 'site-name' nodes to modify.
    $view_site_node = $wp_admin_bar->get_node('view-site');
    $site_name_node = $wp_admin_bar->get_node('site-name');

    // Change targets
    $view_site_node->meta['target'] = '_blank';
    $site_name_node->meta['target'] = '_blank';

    // Change hrefs to our Next front-end URL
    $url = get_frontend_url();
    $view_site_node->href = $url;
    $site_name_node->href = $url;

    // Update Nodes
    $wp_admin_bar->add_node($view_site_node);
    $wp_admin_bar->add_node($site_name_node);

}


/*
  On-demand Incremental Static Regeneration (ISR) --> rebuild static pages/posts immediately upon saving your changes in WP
*/

if ($enable_isr) {
  add_action('save_post_page', 'revalidate_on_save', 10, 2);
  function revalidate_on_save($post_ID, $post)
  {
    $front_end_url = get_frontend_url();
    $api_options = get_option('next_wp_rest_settings');
    $revalidate_api_route = $api_options['revalidate_api_route'] ? $api_options['revalidate_api_route'] : 'revalidate';

    // manually add environment URLs to this array if you wish to enable on-demand ISR for that environment (useful for testing one-off Vercel deployments or when running a production build locally) 
    $environments_to_revalidate = [$front_end_url, "http://localhost:3000"];
    $slug = $post->post_name;
    $type = $post->post_type;
    $secret = get_preview_secret();

    foreach ($environments_to_revalidate as $url) {
      try{
        wp_remote_get("{$url}/api/{$revalidate_api_route}/{$slug}?post_type={$type}&secret={$secret}");
      }catch(Exception $e){
        echo 'Error while regenerating static page for url "', $url, '" -- error message: ', $e->getMessage(), "\n";
      }  
    }
  }
}


/*
  Whenever you log in to WordPress, we redirect to an API endpoint on our Next front-end (/api/login) 
  which sets a cookie that tells next-wp you're logged in, and it redirects you back to the Admin dashboard.
  Next-wp will read this cookie to determine when to show the AdminBar component. We use a redirect rather than
  a GET request so that it also works while in local development (can't GET request localhost from WP) 
*/
function login_on_frontend() {
  $url = get_frontend_url();
  $secret = get_preview_secret();
  $api_options = get_option('next_wp_rest_settings');
  $login_api_route = $api_options['login_api_route'] ? $api_options['login_api_route'] : 'login';  

  try{
    $res = wp_redirect("{$url}/api/{$login_api_route}?secret={$secret}");
    exit();
    // write_log($res);
  }catch(Exception $e){
    echo "Error while logging in on front-end ({$url}). Error message: ", $e->getMessage(), "\n";
  }
}
add_action('wp_login', 'login_on_frontend');


/*
  Whenever you log out of WordPress, we redirect to an API endpoint on our Next front-end (/api/logout) 
  which sets a cookie that tells next-wp you're logged out, and it redirects you back to the WP login screen.
  Next-wp will read this cookie to determine when to show the AdminBar component. We use a redirect rather than
  a GET request so that it also works while in local development (can't GET request localhost from WP) 
*/
function logout_on_frontend() {
  $url = get_frontend_url();
  $secret = get_preview_secret();
  $api_options = get_option('next_wp_rest_settings');
  $logout_api_route = $api_options['logout_api_route'] ? $api_options['logout_api_route'] : 'logout';

  try{
    $res = wp_redirect("{$url}/api/{$logout_api_route}?secret={$secret}");
    exit();
    // write_log($res);
  }catch(Exception $e){
    write_log("Error while logging out on front-end ({$url}). Error message: ", $e->getMessage(), "\n");
  }
}
add_action('wp_logout', 'logout_on_frontend');
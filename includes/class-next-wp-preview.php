<?php

/**
 * Fired during plugin activation
 *
 * @link       https://https://github.com/cloak-labs
 * @since      1.0.0
 *
 * @package    Next_Wp
 * @subpackage Next_Wp/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run NextJS preview mode.
 *
 * @since      1.0.0
 * @package    Next_Wp
 * @subpackage Next_Wp/includes
 * @author     Cloak Labs <wade@stikkymedia.com>
 */
class Next_Wp_preview
{

  public function __construct()
  {
    $this->add_frontend_login();
    $this->add_frontend_logout();
    $this->add_frontend_preview_link();
  }

  /**
   * @param bool NEXT_WP_ENABLE_DEV_MODE
   * @param string NEXT_WP_NEXT_FRONTEND_URL
   * 
   * @return string
   * 
   * @since    1.0.0
   */
  public static function get_frontend_url()
  {
    if (defined(NEXT_WP_ENABLE_DEV_MODE)) {
      if (NEXT_WP_ENABLE_DEV_MODE === TRUE) {
        return "http://localhost:3000";
      }
    }

    if (defined(NEXT_WP_NEXT_FRONTEND_URL)) {
      return NEXT_WP_NEXT_FRONTEND_URL;
    }

    return site_url();
  }

  /**
   * 
   * @param string NEXT_WP_PREVIEW_SECRET
   * 
   * @return string
   * 
   * @since    1.0.0
   */
  public static function get_preview_secret()
  {
    if (defined(NEXT_WP_PREVIEW_SECRET)) {
      return NEXT_WP_PREVIEW_SECRET;
    }
    return "";
  }


  /** 
   * Whenever you log in to WordPress, we redirect to an API endpoint on our Next front-end (/api/login) 
   * which sets a cookie that tells next-wp you're logged in, and it redirects you back to the Admin dashboard.
   * Next-wp will read this cookie to determine when to show the AdminBar component. We use a redirect rather than
   * a GET request so that it also works while in local development (can't GET request localhost from WP) 
   *
   * @param string NEXT_WP_LOGIN_API_ROUTE
   * 
   * @return void 
   */
  public function login_on_frontend()
  {
    $url = $this->get_frontend_url();
    $secret = $this->get_preview_secret();
    $login_api_route = NEXT_WP_LOGIN_API_ROUTE ? NEXT_WP_LOGIN_API_ROUTE : 'login';

    try {
      $res = wp_redirect("{$url}/api/{$login_api_route}?secret={$secret}");
      exit();
    } catch (Exception $e) {
      echo "Error while logging in on front-end ({$url}). Error message: ", $e->getMessage(), "\n";
    }
  }


  /** 
   * Whenever you log out of WordPress, we redirect to an API endpoint on our Next front-end (/api/logout) 
   * which sets a cookie that tells next-wp you're logged out, and it redirects you back to the WP login screen.
   * Next-wp will read this cookie to determine when to show the AdminBar component. We use a redirect rather than
   * a GET request so that it also works while in local development (can't GET request localhost from WP) 
   *
   * @param string NEXT_WP_LOGOUT_API_ROUTE
   * 
   * @return void
   */
  public function logout_on_frontend()
  {
    $url = $this->get_frontend_url();
    $secret = $this->get_preview_secret();
    $logout_api_route = NEXT_WP_LOGOUT_API_ROUTE ? NEXT_WP_LOGOUT_API_ROUTE : 'logout';

    try {
      $res = wp_redirect("{$url}/api/{$logout_api_route}?secret={$secret}");
      exit();
    } catch (Exception $e) {
      write_log("Error while logging out on front-end ({$url}). Error message: ", $e->getMessage(), "\n");
    }
  }

  /** 
   * Modify 'Preview' links on posts, pages to point to frontend URL
   * Request to NextJS API Route generates the frontend page using preview data and redirects to it
   * Requires a 'preview secret' as a query param to match on Wordpress and NextJS server
   * 
   * @param bool NEXT_WP_PREVIEW_API_ROUTE
   * @param string $preview_link
   * @param object $post
   * 
   * @return string
   * 
   * @since    1.0.0
   */
  public function get_preview_url($preview_link, $post)
  {
    $preview_api_route = NEXT_WP_PREVIEW_API_ROUTE ? NEXT_WP_PREVIEW_API_ROUTE : 'preview';
    $revisionId = $post->ID; // the ID of the post revision, not the master post
    $postId = $post->post_parent; // the revision's parent == the post we're previewing
    $postType = get_post_type($postId); // the master/parent post's post type --> important for next-wp to retrieve the correct revision data  
    $secret = $this->get_preview_secret();
    $front_end_url = $this->get_frontend_url();
    return "{$front_end_url}/api/{$preview_api_route}?revisionId={$revisionId}&postId={$postId}&postType={$postType}&secret={$secret}";
  }

  /**
   * @return void
   * 
   * @since    1.0.0
   */
  private function add_frontend_login()
  {
    add_action('wp_login', array($this, 'logout_on_frontend'));
  }

  /**
   * @return void
   * 
   * @since    1.0.0
   */
  private function add_frontend_logout()
  {
    add_action('wp_logout', array($this, 'logout_on_frontend'));
  }

  /**
   * @param bool NEXT_WP_ENABLE_PREVIEW_POST
   * 
   * @return void
   * 
   * @since    1.0.0
   */
  private function add_frontend_preview_link()
  {
    if (defined(NEXT_WP_ENABLE_PREVIEW_POST)) {
      if (NEXT_WP_ENABLE_PREVIEW_POST === TRUE) {
        add_filter('preview_post_link', array($this, 'get_preview_url'), 10);
      }
    }
  }
}

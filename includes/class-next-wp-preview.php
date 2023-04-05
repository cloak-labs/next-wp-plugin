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
   * @return void
   * 
   * @since    1.0.0
   */
  private function add_frontend_login()
  {
    add_action('wp_login', 'login_on_frontend');
  }

  /**
   * @return void
   * 
   * @since    1.0.0
   */
  private function add_frontend_logout()
  {
    add_action('wp_logout', 'logout_on_frontend');
  }
}

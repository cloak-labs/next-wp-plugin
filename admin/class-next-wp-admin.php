<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://github.com/cloak-labs
 * @since      1.0.0
 *
 * @package    Next_Wp
 * @subpackage Next_Wp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Next_Wp
 * @subpackage Next_Wp/admin
 * @author     Cloak Labs <wade@stikkymedia.com>
 */
class Next_Wp_Admin
{

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string    $plugin_name       The name of this plugin.
   * @param      string    $version    The version of this plugin.
   */
  public function __construct($plugin_name, $version)
  {
    $this->plugin_name = $plugin_name;
    $this->version = $version;
    $this->add_frontend_links();
    $this->add_frontend_view_links();
  }


  /**
   * Override the href for the site name & view site links (to use our Next front-end URL) in the WP admin toolbar, and open them in new tabs
   *
   * @param object $wp_admin_bar
   * 
   * @return void
   * 
   * @since    1.0.0
   */
  public static function customize_admin_bar($wp_admin_bar)
  {
    // Get references to the 'view-site' and 'site-name' nodes to modify.
    $view_site_node = $wp_admin_bar->get_node('view-site');
    $site_name_node = $wp_admin_bar->get_node('site-name');

    // Change targets
    $view_site_node->meta['target'] = '_blank';
    $site_name_node->meta['target'] = '_blank';

    // Change hrefs to our Next front-end URL
    $url = Next_Wp::get_frontend_url();
    $view_site_node->href = $url;
    $site_name_node->href = $url;

    // Update Nodes
    $wp_admin_bar->add_node($view_site_node);
    $wp_admin_bar->add_node($site_name_node);
  }


  /**
   * Modify 'View Post' links on posts, pages to point to frontend URL
   * 
   * @return string
   *
   * @since    1.0.0
   */
  public static function custom_view_page_url($permalink, $post)
  {
    $custom_permalink = Next_Wp::get_frontend_url();
    if ($permalink) {
      $custom_permalink = str_replace(home_url(), $custom_permalink,  $permalink);
    }
    return $custom_permalink;
  }


  /**
   * Override the href for the site name & view site links
   * 
   * @return void
   *
   * @since    1.0.0
   */
  private function add_frontend_links()
  {
    add_action('admin_bar_menu', array($this, 'customize_admin_bar'), 80);
  }

  /**
   * Modify 'View Post' links on posts, pages to point to frontend URL
   * 
   * @return void
   *
   * @since    1.0.0
   */
  private function add_frontend_view_links()
  {
    add_filter('page_link', array($this, 'custom_view_page_url'), 10, 2);
    add_filter('post_link', array($this, 'custom_view_page_url'), 10, 2);
    add_filter('post_type_link', array($this, 'custom_view_page_url'), 10, 2);
  }


  /**
   * Register the stylesheets for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_styles()
  {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Next_Wp_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Next_Wp_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/next-wp-admin.css', array(), $this->version, 'all');
  }

  /**
   * Register the JavaScript for the admin area.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts()
  {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Next_Wp_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Next_Wp_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/next-wp-admin.js', array('jquery'), $this->version, false);
  }
}

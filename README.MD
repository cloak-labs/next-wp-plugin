# This repo is not functional, it is a work in progress

## NextWP Wordpress Plugin
This plugin is designed to integrate a Wordpress backend with a NextJS frontend. It has a required NPM package to be used in tandem with it.

## Features
- Configure frontend links in Wordpress to point to NextJS frontend
- Configure & enable Wordpress preview mode to work with NextJS frontend
- Enables ISR for new and updated pages to trigger NextJS page updates
- Configures Yoast to use NextJS frontend URL for meta tags
- Configures a longer JWT expiry date
- Integrates Wordpress login/logout with frontend components

## Next-WP NPM Package
- [npm](https://www.npmjs.com/package/next-wp)
- [github](https://github.com/cloak-labs/next-wp)

## Dependencies
- [Yoast SEO Wordpress Plugin](https://wordpress.org/plugins/wordpress-seo/)
- [JWT Authentication for WP-API Wordpress Plugin](https://wordpress.org/plugins/jwt-authentication-for-wp-rest-api/)
- [Headless WP Wordpress Plugin](https://github.com/cloak-labs/headless-wp-plugin)

## Installation
1. Install the latest release of `next-wp.zip` on the site
2. Create a file `next-wp-config.php` in the root of your Wordpress site (beside `wp-config.php`)
3. Add the following constants to your `next-wp-config.php` to configure the plugin
```php
/* 
Define your NextWP Plugin settings here
*/

# Main Settings
define('NEXT_WP_NEXT_FRONTEND_URL', '');
define('NEXT_WP_PREVIEW_SECRET', '');
define('NEXT_WP_ENABLE_DEV_MODE', TRUE);

# Pages & Previews
define('NEXT_WP_ENABLE_ISR', TRUE);
define('NEXT_WP_ENABLE_PREVIEW_POST', TRUE);
define('NEXT_WP_OVERRIDE_VIEW_POST_LINK', TRUE);
define('NEXT_WP_OVERRIDE_PREVIEW_POST_LINK', TRUE);

# Rest API
define('NEXT_WP_ENABLE_FAVICON', TRUE);
define('NEXT_WP_YOAST_USE_FRONTEND_URL', TRUE);

# Authentication
define('NEXT_WP_JWT_NO_EXPIRY', TRUE);

# API Routes
define('NEXT_WP_PREVIEW_API_ROUTE', '');
define('NEXT_WP_REVALIDATE_API_ROUTE', '');
define('NEXT_WP_LOGIN_API_ROUTE', '');
define('NEXT_WP_LOGOUT_API_ROUTE', '');
```
4. Profit
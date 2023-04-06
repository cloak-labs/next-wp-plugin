# NextWP Wordpress Plugin
This plugin is designed to integrate a Wordpress backend with a NextJS frontend. It has a required NPM package to be used in tandem with it.

## REST API
This plugin provides functionality listed below specific to NextJS, all generic features added to the REST API housed in the plugin HeadlessWP. This includes adding blocks data to the REST API, extending the JWT authorization time, including Yoast SEO data in REST API queries etc.

## Features
- Integrates post and page previews with NextJS frontend
- Integrates logging in and out of Wordpress with NextJS frontend
- Integrates Incremental Static Regeneration functionality with NextJS frontend. As posts & pages are edited & saved in Wordpress, regeneration is triggered for the frontend as opposed to a full build step.
- Uses Code as Configuration approach, all config for this plugin is through defining constants, no configuration data is saved to the database
- Adds a NextWP menu item to the backend for viewing state of config variables

## Next-WP NPM Package
- [npm](https://www.npmjs.com/package/next-wp)
- [github](https://github.com/cloak-labs/next-wp)

## Dependencies
- [Headless WP Wordpress Plugin](https://github.com/cloak-labs/headless-wp-plugin)

## Installation
1. Install the latest release of `next-wp.zip` on the site
2. Add the following constants to your `wp-config.php` to configure the plugin
```php
/* 
Define your NextWP Plugin settings here
*/

# Main Settings
define('NEXT_WP_NEXT_FRONTEND_URL', '');
define('NEXT_WP_PREVIEW_SECRET', '');
define('NEXT_WP_ENABLE_DEV_MODE', TRUE);

# API Routes
define('NEXT_WP_LOGIN_API_ROUTE', '');
define('NEXT_WP_LOGOUT_API_ROUTE', '');
define('NEXT_WP_PREVIEW_API_ROUTE', '');
define('NEXT_WP_REVALIDATE_API_ROUTE', '');
```
4. Profit
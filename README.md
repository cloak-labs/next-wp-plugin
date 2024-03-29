This plugin has been revamped and relaunched as the [CloakWP Plugin](https://github.com/cloak-labs/cloakwp-plugin). Go there.

# NextWP Wordpress Plugin
This WordPress plugin is designed to integrate a WordPress backend with a NextJS frontend -- it's part of a larger ecosystem of open-source tools designed to make Headless WordPress development simple & easy; it has a required "sister" [NPM package](https://github.com/cloak-labs/next-wp) to be installed on your Next front-end, and is intended to be used alongside the [HeadlessWP WordPress Plugin](https://github.com/cloak-labs/headless-wp-plugin), which extends the WordPress REST API to be more headless-friendly (eg. adds Gutenberg blocks data and Yoast SEO data to the REST API, expands ACF data in the REST API, extends JWT expiration dates, etc.).

## Features
- Integrates post and page previews with NextJS frontend
- Integrates logging in and out of WordPress with NextJS frontend (so you can conditionally display the NextWP AdminBar provided by the front-end NPM package)
- Integrates on-demand Incremental Static Regeneration (ISR) functionality with NextJS frontend. As posts/pages are edited and saved in WordPress, regeneration is triggered immediately (as opposed to waiting for the next regeneration interval) and for that particular frontend page only (as opposed to triggering a full sitewide build process). You get all the benefits of a fully static site, without the frustration of waiting for your saved changes to take effect.
- Uses Code as Configuration approach; all config for this plugin is done through defining PHP constants, no configuration data is saved to the database. This allows you to version-control your config and easily define different options per environment (particularly useful for those using a modern WordPress development approach, such as our [WP Backend Starter](https://github.com/cloak-labs/headless-wordpress-backend-starter)).
- Adds a NextWP menu item to the backend for viewing state of config variables

## NextWP NPM Package
- [npm](https://www.npmjs.com/package/next-wp)
- [github](https://github.com/cloak-labs/next-wp)

## Dependencies
- [HeadlessWP WordPress Plugin](https://github.com/cloak-labs/headless-wp-plugin)

## Installation
1. Install the latest release of `next-wp.zip` on your WordPress site (or install via Composer, pointing at the latest GitHub release -- our recommended approach)
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
3. Install & configure the [NextWP NPM package](https://www.npmjs.com/package/next-wp) on your NextJS front-end
4. Profit

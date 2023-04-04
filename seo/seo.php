<?php

/*
  Yoast SEO filters for changing how metadata is generated

  MetaData is generated and added to page/post REST API requests as "yoast_head"

*/

$override_frontend_url = NEXT_WP_YOAST_USE_FRONTEND_URL ?? NULL;


if ($override_frontend_url) :

  /**
   * Changes @type of Webpage Schema data.
   *
   * @param array $data Schema.org Webpage data array.
   *
   * @return array Schema.org Webpage data array.
   */

  add_filter('wpseo_schema_webpage', 'seo_webpage_filter');
  function seo_webpage_filter($data)
  {

    $data['isPartOf']['@id'] = get_frontend_url();

    return $data;
  }

  /**
   * Changes Website Schema data output, overwriting the name and alternateName.
   *
   * @param array $data Schema.org Website data array.
   *
   * @return array Schema.org Website data array.
   */

  add_filter('wpseo_schema_website', 'seo_website_filter');
  function seo_website_filter($data)
  {
    $new_data = str_replace(site_url(), get_frontend_url(), $data);
    $new_data['publisher'] = str_replace(site_url(), get_frontend_url(), $data['publisher']);
    $new_data['potentialAction'][0]['target']['urlTemplate'] = get_frontend_url() . '?query={search_term_string}';
    return $new_data;
  }

  /**
   * Add extra properties to the Yoast SEO Organization
   *
   * @param array             $data    The Schema Organization data.
   * @param Meta_Tags_Context $context Context value object.
   *
   * @return array $data The Schema Organization data.
   */

  add_filter('wpseo_schema_organization', 'seo_change_organization_schema', 11, 2);
  function seo_change_organization_schema($data, $context)
  {
    $new_data = str_replace(site_url(), get_frontend_url(), $data);
    return $new_data;
  }

  /**
   * Changes the Yoast SEO Person schema.
   *
   * @param array             $data    The Schema Person data.
   * @param Meta_Tags_Context $context Context value object.
   *
   * @return array $data The Schema Person data.
   */

  add_filter('wpseo_schema_person', 'seo_schema_change_person', 11, 2);
  function seo_schema_change_person($data, $context)
  {
    return NULL;
  }

endif;

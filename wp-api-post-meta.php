<?php
/*
Plugin Name:  WP REST API extra routes for 'job-posting' meta
Plugin URI:   https://github.com/qunoissue/WP-REST-post-meta
Description:  Extra routes for WP REST API2 to manage post meta data of post_type 'job-posting'
Version:      1.0.0
Author:       Tomone Akashi (original: Kadzuya Okamoto)
License:      MIT License
*/

namespace WPAPIJobPostingMeta;

function get_meta_val_list_init() {
  /**
   * Grab meta_val and post_id list.
   *
   * @param array $data Options for the function.
   * @return array of meta_val and post_id pair.
   */
  function get_meta_val_list($data) {
    $meta_val = 'job_id';
    $meta_val = $data['meta_val'];
    $numposts = $data['numposts'];
    $offset = $data['offset'];
    // configuration for `get_post`.
    $args = array(
      'numberposts' => $numposts,
      'post_status' => 'any',
      'offset' => $offset,
      'post_type' => 'job-posting'
    );
    return array_merge(array_filter(
      array_map(function($a) use ($meta_val) {
        $pid = $a->ID;
        $jids = \get_post_meta($pid, $meta_val, false);
        return array_reduce($jids, function($ls, $jid) use ($pid, $meta_val) {
          return array_merge(array(
            $meta_val => $jid,
            'post_id' => $pid,
          ), $ls);
        }, array());
      }, \get_posts($args)),
      function($a) {
        return $a !== array();
      }
    ));
  }

  \add_action('rest_api_init', function () {
    \register_rest_route('meta_val/v2', '/job-posting/(?P<meta_val>.+)/limit/(?P<numposts>[0-9\-]+)/offset/(?P<offset>[0-9]+)',
      array(
  		  'methods' => 'GET',
        'callback' => __NAMESPACE__ . '\\get_meta_val_list',
      )
    );
  });
}

add_action( 'init', __NAMESPACE__ . '\\get_meta_val_list_init' );

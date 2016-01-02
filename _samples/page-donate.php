<?php
/*
Template Name: Donate Page
*/
$slug = get_option('stripe_donate_options')[stripe_donate_default_page];
query_posts(array(
  'slug' => $slug,
  'posts_per_page' => 1,
  'post_type' => 'fundraiser'
));

include dirname( __FILE__ ) . '/single-fundraiser.php';
<?php
/*
Template Name: Single Fundraiser
*/

get_header(); ?>
</div>
<?php
// Start the loop.
while ( have_posts() ) : the_post();
$fundraiser = new WP_Stripe_Donate_Fundraiser(get_the_ID());
$thispod = pods('fundraiser', get_the_ID());
?>
<style>
  body:before{
    width: 0 !important;
  }
  .full-width-banner {
    width: 100%;
    height: 200px;
    overflow: hidden;
    background-position: top center;
    background-repeat: no-repeat;
    border-bottom: 1px solid #ddd;
    background-size: cover;
    box-shadow: 0 -2px 2px 0 #383838 inset;
    background-attachment: fixed;
  }
</style>
<!-- div class="full-width-banner" style="background-image:url('<?php echo get_post_meta(get_the_ID(), 'banner_image._src', true); ?>');">
  </div -->
<div class="container">
  <div id="primary" class="content-area">
    <main id="main" class="site-main container-fluid" role="main">
      <div class="row">

        <div id="donate-ask" class="col-xs-12 col-sm-12 col-md-6 col-lg-5 col-md-push-6 col-lg-push-7">
          <?php $fundraiser->get_form(); ?>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-7 col-md-pull-6 col-lg-pull-5">
          <h1><?php the_title(); ?></h1>
          <?php echo pods_image($thispod->field('banner_image')['ID'], $size = 'masthead'); ?>
          <?php echo $fundraiser->get_progressbar(); the_content(); ?>
        </div>

      </div>
      <?php
      // End the loop.
      endwhile;
      ?>

    </main><!-- .site-main -->
  </div><!-- .content-area -->

  <?php get_footer(); ?>

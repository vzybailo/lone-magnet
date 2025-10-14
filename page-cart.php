<?php
/**
 * Template Name: Custom Cart Page
 */

get_header();
?>

<main class="container">
    <?php echo do_shortcode('[woocommerce_cart]'); ?>
</main>

<?php get_footer(); ?>

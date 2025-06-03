<?php
/**
 * Template Name: Custom Cart Page
 */

get_header();
?>

<main class="container">
    <div class="woocommerce">
        <?php echo do_shortcode('[woocommerce_cart]'); ?>
    </div>
</main>

<?php get_footer(); ?>

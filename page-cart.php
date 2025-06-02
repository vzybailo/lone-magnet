<?php
/**
 * Template Name: Custom Cart Page
 */

get_header();
?>

<main class="container">
    <h1 class="text-3xl font-bold mb-6 mt-10">Your cart</h1>

    <div class="woocommerce">
        <?php echo do_shortcode('[woocommerce_cart]'); ?>
    </div>
</main>

<?php get_footer(); ?>

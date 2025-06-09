<?php
defined( 'ABSPATH' ) || exit;

wc_print_notices();

do_action( 'woocommerce_before_cart' );
?>

<div class="container mx-auto py-12 text-center flex flex-col justify-between h-full">
  <div class="flex flex-col items-center">
    <img src="<?php echo get_template_directory_uri() . '/assets/images/empty-cart.jpg'?>" alt="empty cart">
    <h2 class="text-xl font-semibold mb-2">Your cart is empty</h2>
    <p class="text-gray-600 mb-6">
      Looks like you haven’t added anything yet. Let’s fix that!
    </p>
    <a href="/products" class="bg-blue-700 text-white hover:bg-blue-800 py-2 px-4 transition-colors duration-300">Continue shopping</a>
  </div>

  <div class="mt-20 text-left max-w-6xl max-sm:px-4">
    <h3 class="text-xl font-semibold mb-8 border-b pb-4">You might also like</h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 4,
            'orderby' => 'rand',
        );
        $products = new WP_Query( $args );

        if ( $products->have_posts() ) :
            while ( $products->have_posts() ) : $products->the_post();
                wc_get_template_part( 'content', 'product' );
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </div>
  </div>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>

<?php
defined( 'ABSPATH' ) || exit;

wc_print_notices();

do_action( 'woocommerce_before_cart' );
?>

<div class="container mx-auto pt-12 text-center flex flex-col justify-between h-full">
  <div class="flex flex-col items-center">
    <img src="<?php echo get_template_directory_uri() . '/assets/images/empty-cart.jpg'?>" alt="empty cart">
    <h2 class="text-xl font-semibold mb-2">Your cart is empty</h2>
    <p class="text-gray-600 mb-6">
      Looks like you haven’t added anything yet. Let’s fix that!
    </p>
    <a href="/shop" class="bg-blue-700 text-white hover:bg-blue-800 py-2 px-4 transition-colors duration-300">Continue shopping</a>
  </div>

  <div class="mt-20 text-left max-w-6xl">
    <h3 class="text-xl font-semibold mb-8 border-b pb-4">You might also like</h3>

    <?php
    $args = array(
      'post_type' => 'product',
      'posts_per_page' => 4,
      'orderby' => 'rand',
      'post_status' => 'publish',
    );

    $loop = new WP_Query( $args );

    if ( $loop->have_posts() ) : ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
        <?php while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
          <a href="<?php the_permalink(); ?>" class="block border">
            <?php if (has_post_thumbnail()) : ?>
              <div>
                <?php the_post_thumbnail('medium', ['class' => 'w-full h-48 object-cover']); ?>
              </div>
            <?php endif; ?>
            <div class="p-4">
                <h4 class="text-lg font-medium mb-2 text-gray-900"><?php the_title(); ?></h4>
                <span class="text-blue-600 font-semibold"><?php echo $product->get_price_html(); ?></span>
            </div>
          </a>
        <?php endwhile; ?>
      </div>
      <?php wp_reset_postdata(); ?>
    <?php else : ?>
      <p>No products found</p>
    <?php endif; ?>
  </div>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>

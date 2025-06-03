<?php
defined( 'ABSPATH' ) || exit;

wc_print_notices();

do_action( 'woocommerce_before_cart' );
?>

<div class="container mx-auto px-4 py-16 text-center">
  <h2 class="text-4xl font-semibold mb-4">Your Cart is Currently Empty</h2>
  <p class="text-lg text-gray-600 mb-10">
    Looks like you haven't added any items to your cart yet.
  </p>

  <div class="mt-20 text-left max-w-6xl mx-auto">
    <h3 class="text-2xl font-semibold mb-8 border-b border-gray-300 pb-4">You might also like</h3>

    <?php
    // Получаем 4 случайных товара для блока рекомендаций
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
          <a href="<?php the_permalink(); ?>" class="block border shadow hover:shadow-lg transition p-4">
            <?php if (has_post_thumbnail()) : ?>
              <div class="mb-4">
                <?php the_post_thumbnail('medium', ['class' => 'w-full h-48 object-cover']); ?>
              </div>
            <?php endif; ?>
            <h4 class="text-lg font-medium mb-2 text-gray-900"><?php the_title(); ?></h4>
            <span class="text-blue-600 font-semibold"><?php echo $product->get_price_html(); ?></span>
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

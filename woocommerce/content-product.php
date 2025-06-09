<?php
defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}

$rating = $product->get_average_rating();
$review_count = $product->get_review_count();
?>

<a href="<?php the_permalink(); ?>" class="block">
    <div class="aspect-square bg-gray-100 overflow-hidden mb-3">
        <?php echo $product->get_image( 'woocommerce_thumbnail', [ 'class' => 'object-cover w-full h-full' ] ); ?>
    </div>

    <h2 class="text-lg font-semibold mb-1"><?php the_title(); ?></h2>

    <?php if ( $rating > 0 ) : ?>
        <div class="flex items-center text-gold text-sm mb-1">
            <?php
            for ( $i = 1; $i <= 5; $i++ ) {
                if ( $rating >= $i ) {
                    echo '<svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.488 6.91l6.564-.955L10 0l2.948 5.955 6.564.955-4.756 4.635 1.122 6.545z"/></svg>';
                } elseif ( $rating >= $i - 0.5 ) {
                    echo '<svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><defs><linearGradient id="half"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="transparent"/></linearGradient></defs><path fill="url(#half)" d="M10 15l-5.878 3.09 1.122-6.545L.488 6.91l6.564-.955L10 0l2.948 5.955 6.564.955-4.756 4.635 1.122 6.545z"/></svg>';
                } else {
                    echo '<svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.122-6.545L.488 6.91l6.564-.955L10 0l2.948 5.955 6.564.955-4.756 4.635 1.122 6.545z"/></svg>';
                }
            }
            ?>
            <span class="ml-2 text-gray-600">(<?php echo esc_html( $review_count ); ?>)</span>
        </div>
    <?php endif; ?>

    <span class="text-gray-800 font-bold"><?php echo $product->get_price_html(); ?></span>
</a>

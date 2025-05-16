<?php
defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

// Получаем объект продукта
$product = wc_get_product( get_the_ID() );
?>

<div class="container flex">
    <div class="w-3/4">
        <?php echo $product->get_image(); ?>
    </div>
    <div>
        <h1><?php the_title(); ?></h1>

        <?php echo $product->get_rating_html(); ?>

        <div class="mb-4">
            <?php echo $product->get_price_html(); ?>
        </div>

        <form class="cart mb-6" method="post" enctype="multipart/form-data">
            <div class="mb-6">
                <div class="mb-2 text-sm">Quantity</div>
                <?php woocommerce_quantity_input(); ?>
            </div>
            <div class="flex flex-col">
                <div class="mb-2">
                    <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
                </div>
                <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="bg-blue-700 text-white hover:bg-blue-800 p-2 text-white">
                    Add to cart
                </button>
            </div>
        </form>
        <div>
            <?php the_content(); ?>
        </div>
    </div>
</div>

<?php get_footer( 'shop' ); ?>

<?php
defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

$product = wc_get_product( get_the_ID() );
$main_image_id = $product->get_image_id();
$gallery_ids = $product->get_gallery_image_ids();
?>

<div class="container">
    <div class="flex pt-12 max-md:flex-col max-sm:px-4 mb-12">
        <div class="w-2/3 grid grid-cols-2 gap-4 h-[fit-content] mr-6 max-md:w-full max-md:grid-cols-1 max-md:mr-0 max-md:mb-4">
            <?php if ($main_image_id): 
                $full_url = wp_get_attachment_image_url($main_image_id, 'full');
                $thumb = wp_get_attachment_image($main_image_id, 'medium', false, ['class' => 'w-full h-full object-cover mag-pic']);
            ?>
                <a href="<?php echo esc_url($full_url); ?>"
                    class="glightbox aspect-square overflow-hidden"
                    data-gallery="product-gallery">
                    <?php echo $thumb; ?>
                </a>
            <?php endif; ?>

            <?php
            $index = 1;
            foreach ($gallery_ids as $id): 
                $full = wp_get_attachment_image_url($id, 'full');
                $thumb = wp_get_attachment_image($id, 'medium', false, ['class' => 'w-full h-full object-cover mag-pic']);
            ?>
                <a href="<?php echo esc_url($full); ?>"
                    class="glightbox aspect-square overflow-hidden <?php echo $index === 0 ? '' : 'max-md:hidden'; ?>"
                    data-gallery="product-gallery">
                    <?php echo $thumb; ?>
                </a>
            <?php $index++; endforeach; ?>
        </div>

        <div class="w-1/3 max-md:w-full">
            <h1 class="text-3xl font-bold mb-4"><?php the_title(); ?></h1>

            <div class="text-gold mb-4">
                <?php 
                    echo wc_get_rating_html( $product->get_average_rating() );
                ?>
            </div>

            <div class="mb-4">
                <?php 
                    echo $product->get_price_html(); 
                    
                    if ( $product->get_sale_price() ) {
                        do_action('woocommerce_template_single_price');
                    }
                ?>
            </div>

            <form class="cart mb-6" method="post" enctype="multipart/form-data">
                <div class="mb-6">
                    <div class="mb-2 text-sm font-light">Quantity</div>
                    <?php woocommerce_quantity_input(); ?>
                </div>
                <div class="flex flex-col max-md:w-full">
                    <div class="mb-2">
                        <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
                    </div>
                    <input type="hidden" name="magnet_photos_data" id="magnet_photos_data" value=""/>
                    <button id="lone-add-to-cart" type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="bg-blue-700 text-white hover:bg-blue-800 p-2 transition-colors duration-300">
                        Add to cart
                    </button>
                </div>
            </form>
            <div class="product">
                <?php the_content(); ?>
            </div>
        </div>
    </div>
    <div>
        <?php
            comments_template();
        ?>
    </div>
</div>

<?php get_footer( 'shop' ); ?>

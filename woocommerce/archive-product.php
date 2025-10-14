<?php
defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>

<div class="container max-sm:px-4 py-10 flex-1">

    <h1 class="text-3xl font-bold mb-8">
        <?php woocommerce_page_title(); ?>
    </h1>

    <?php if ( woocommerce_product_loop() ) : ?>
        <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php
            while ( have_posts() ) :
                the_post();
                wc_get_template_part( 'content', 'product' );
            endwhile;
            ?>
        </ul>

        <div class="mt-8">
            <?php do_action( 'woocommerce_after_shop_loop' ); ?>
        </div>

    <?php else : ?>
        <p class="text-gray-500 text-center">Пока нет товаров.</p>
    <?php endif; ?>

    <?php do_action( 'woocommerce_after_main_content' ); ?>
</div>

<?php
get_footer( 'shop' );

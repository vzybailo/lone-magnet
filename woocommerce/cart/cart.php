<?php defined( 'ABSPATH' ) || exit; ?>

<h1 class="text-3xl font-bold mb-6 mt-10 max-sm:px-4">Your cart</h1>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
    <?php do_action( 'woocommerce_before_cart_table' ); ?>

    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse w-full text-sm">
            <thead class="hidden md:table-header-group border-b">
                <tr class="font-sm font-light">
                    <th class="text-left py-3">Product</th>
                    <th class="text-left py-3">Quantity</th>
                    <th class="text-right py-3">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 border-b">
                <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                    $_product   = $cart_item['data'];
                    $product_id = $cart_item['product_id'];

                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) :
                        $product_permalink = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '';
                ?>
                <tr class="block md:table-row mb-4 p-4 md:p-0">

                    <td class="py-3 block md:table-cell">
                        <div class="flex items-center gap-4">
                            <div class="w-40 h-40 flex-shrink-0">
                                <?php
                                    $thumbnail = $_product->get_image( 'woocommerce_thumbnail', ['class' => 'w-full h-full object-cover'] );
                                    echo apply_filters( 'woocommerce_cart_item_thumbnail', $thumbnail, $cart_item, $cart_item_key );
                                ?>
                            </div>
                            <div>
                                <span class="block md:hidden text-xs text-gray-500 mb-1">Product</span>
                                <h3 class="font-semibold">
                                    <?php
                                        echo $product_permalink
                                            ? '<a href="' . esc_url( $product_permalink ) . '">' . $_product->get_name() . '</a>'
                                            : $_product->get_name();
                                    ?>
                                </h3>
                                <p class="text-gray-500 text-sm"><?php echo wc_price( $_product->get_price() ); ?></p>
                            </div>
                        </div>
                    </td>

                    <td class="py-3 block md:table-cell">
                        <span class="block md:hidden text-xs text-gray-500 mb-1">Quantity</span>
                        <div class="flex items-center gap-3">
                            <?php
                                echo apply_filters(
                                    'woocommerce_cart_item_quantity',
                                    woocommerce_quantity_input(
                                        array(
                                            'input_name'  => "cart[{$cart_item_key}][qty]",
                                            'input_value' => $cart_item['quantity'],
                                            'max_value'   => $_product->get_max_purchase_quantity(),
                                            'min_value'   => '0',
                                            'product_name'=> $_product->get_name(),
                                            'attributes'  => array(
                                                'data-cart_item_key' => $cart_item_key)
                                        ),
                                        $_product,
                                        false
                                    ),
                                    $cart_item_key,
                                    $cart_item
                                );
                            ?>

                            <a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>" class="text-red-500 text-xl hover:text-red-700" title="Remove item">
															<svg width="15px" height="15px" viewBox="0 0 1024 1024" fill="#000000" class="icon"  version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M32 241.6c-11.2 0-20-8.8-20-20s8.8-20 20-20l940 1.6c11.2 0 20 8.8 20 20s-8.8 20-20 20L32 241.6zM186.4 282.4c0-11.2 8.8-20 20-20s20 8.8 20 20v688.8l585.6-6.4V289.6c0-11.2 8.8-20 20-20s20 8.8 20 20v716.8l-666.4 7.2V282.4z" fill="" /><path d="M682.4 867.2c-11.2 0-20-8.8-20-20V372c0-11.2 8.8-20 20-20s20 8.8 20 20v475.2c0.8 11.2-8.8 20-20 20zM367.2 867.2c-11.2 0-20-8.8-20-20V372c0-11.2 8.8-20 20-20s20 8.8 20 20v475.2c0.8 11.2-8.8 20-20 20zM524.8 867.2c-11.2 0-20-8.8-20-20V372c0-11.2 8.8-20 20-20s20 8.8 20 20v475.2c0.8 11.2-8.8 20-20 20zM655.2 213.6v-48.8c0-17.6-14.4-32-32-32H418.4c-18.4 0-32 14.4-32 32.8V208h-40v-42.4c0-40 32.8-72.8 72.8-72.8H624c40 0 72.8 32.8 72.8 72.8v48.8h-41.6z" fill="" /></svg>
                            </a>
                        </div>
                    </td>

                    <td class="py-3 text-right block md:table-cell font-semibold">
                        <span class="block md:hidden text-xs text-gray-500 mb-1">Total</span>
                        <?php echo WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ); ?>
                    </td>
                </tr>
                <?php endif; endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
    <?php do_action( 'woocommerce_after_cart_table' ); ?>
    <button type="submit" class="hidden" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>" class="button bg-black text-white px-4 py-2 rounded">
        <?php esc_html_e( 'Update cart', 'woocommerce' ); ?>
    </button>
</form>

<?php do_action( 'woocommerce_cart_collaterals' ); ?>


<?php defined( 'ABSPATH' ) || exit; ?>

<div class="my-10 w-full max-md:max-w-full  max-w-md ml-auto max-md:ml-0 max-sm:px-4 max-sm:pb-12">
    <div class="space-y-4 text-sm text-gray-700" id="free-shipping-notice">

        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
            <div class="flex justify-between">
                <span><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
                <span><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
            </div>
        <?php endforeach; ?>

        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
            <div class="flex justify-between">
                <span><?php echo esc_html( $fee->name ); ?></span>
                <span><?php wc_cart_totals_fee_html( $fee ); ?></span>
            </div>
        <?php endforeach; ?>

        <div id="free-shipping-message">
            <?php
            $threshold = 40;
            $cart_total = WC()->cart->get_displayed_subtotal();
            $diff = $threshold - $cart_total;

            if ( $diff > 0 ) : ?>
                <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 p-3 text-sm">
                    Spend <?php echo wc_price( $diff ); ?> more to get <strong>free shipping</strong>!
                </div>
            <?php else : ?>
                <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 p-3 text-sm">
                    🎉 You qualify for <strong>free shipping</strong>!
                </div>
            <?php endif; ?>
        </div>
        <div class="flex justify-between text-lg pt-4">
            <div>
                <div class="font-semibold">Estimated total</div>
                <div class="text-sm">You’ll enter your address and payment method on the next step. Tax will be calculated during checkout based on your address.</div>
            </div>
            <span><?php wc_cart_totals_order_total_html(); ?></span>
        </div>
    </div>

    <div class="mt-6">
        <?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
    </div>
</div>

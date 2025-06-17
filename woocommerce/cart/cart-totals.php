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

        <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) :
            if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) :
                foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
                    <div class="flex justify-between">
                        <span><?php echo esc_html( $tax->label ); ?></span>
                        <span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
                    </div>
                <?php endforeach;
            else : ?>
                <div class="flex justify-between">
                    <span><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
                    <span><?php wc_cart_totals_taxes_total_html(); ?></span>
                </div>
            <?php endif;
        endif; ?>

        <!-- Free Shipping Notice -->
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

        <div class="flex justify-between font-semibold text-lg pt-4">
            <div>
                <div>Estimated total</div>
                <div class="text-sm font-light">You’ll enter your address and payment method on the next step.</div>
            </div>
            <span><?php wc_cart_totals_order_total_html(); ?></span>
        </div>
    </div>

    <div class="mt-6">
        <?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
    </div>
</div>


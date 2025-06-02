<?php defined( 'ABSPATH' ) || exit; ?>

<div class="mt-10 w-full max-w-md ml-auto">
    <div class="space-y-4 text-sm text-gray-700">

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

        <div class="flex justify-between font-semibold text-lg pt-4">
            <div>
                <div>Estimated total</div>
                <div class="text-sm font-light">Final shipping and tax amounts will be shown on the checkout page.</div>
            </div>
            <span><?php wc_cart_totals_order_total_html(); ?></span>
        </div>
    </div>

    <div class="mt-6">
        <?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
    </div>
</div>

<?php

$label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'woocommerce' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'woocommerce' );

?>
<div class="quantity grid grid-cols-3 items-center border w-1/3 max-sm:w-1/2">
	<?php

	do_action( 'woocommerce_before_quantity_input_field' );
	?>
    <button type="button" class="decrease-number cursor-pointer text-xl h-full" aria-label="Decrease quantity"> - </button>
    <input
        type="number"
        id="<?php echo esc_attr( $input_id ); ?>"
        name="<?php echo esc_attr( $input_name ); ?>"
        value="<?php echo esc_attr( $input_value ); ?>"
        class="qty mag-quantity no-spinner w-20 text-center py-2 focus:outline-none focus:ring-0 focus:border-none"
        min="1"
        step="1"
        aria-label="<?php echo esc_attr( $label ); ?>"
    />
    <button type="button" class="increase-number cursor-pointer text-xl h-full" aria-label="Increase quantity"> + </button>
	<?php

	do_action( 'woocommerce_after_quantity_input_field' );
	?>
</div>
<?php

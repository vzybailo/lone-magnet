<?php
defined( 'ABSPATH' ) || exit;

$page_title = ( 'billing' === $load_address ) ? 'Billing Address' : 'Shipping Address';

do_action( 'woocommerce_before_edit_account_address_form' );
?>

<div class="max-w-xl mx-auto py-8">
  <h2 class="text-2xl font-semibold text-gray-800 mb-6"><?php echo esc_html( $page_title ); ?></h2>

  <?php get_template_part( 'woocommerce/myaccount/account-nav' ); ?>

  <?php if ( ! $load_address ) : ?>
    <div class="grid grid-cols-1 gap-6">
      <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ) . 'billing' ); ?>" class="account-adress block border p-4 rounded hover:shadow">
        Edit Billing Address
      </a>
      <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ) . 'shipping' ); ?>" class="account-adress block border p-4 rounded hover:shadow">
        Edit Shipping Address
      </a>
    </div>
  <?php else : ?>

    <form method="post" class="woocommerce-EditAddressForm space-y-4">
      <?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>

      <div class="space-y-4">
        <?php foreach ( $address as $key => $field ) : ?>
          <?php woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) ); ?>
        <?php endforeach; ?>
      </div>

      <?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>

      <div class="pt-4">
        <button type="submit" class="btn">
          Save Address
        </button>
        <?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
        <input type="hidden" name="action" value="edit_address" />
      </div>
    </form>

  <?php endif; ?>
</div>

<?php do_action( 'woocommerce_after_edit_account_address_form' ); ?>

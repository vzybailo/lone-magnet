<?php
defined( 'ABSPATH' ) || exit;

$current_user = wp_get_current_user();
?>

<div class="max-w-xl mx-auto py-8">
  <h2 class="text-2xl font-semibold text-gray-800 mb-6">Account Info</h2>

  <?php get_template_part( 'woocommerce/myaccount/account-nav' ); ?>

  <form class="woocommerce-EditAccountForm" action="" method="post">
    <?php do_action( 'woocommerce_edit_account_form_start' ); ?>

    <p>
      <label>First Name</label>
      <input type="text" name="account_first_name" value="<?php echo esc_attr( $current_user->first_name ); ?>" class="border px-3 py-2 w-full rounded" />
    </p>

    <p>
      <label>Last Name</label>
      <input type="text" name="account_last_name" value="<?php echo esc_attr( $current_user->last_name ); ?>" class="border px-3 py-2 w-full rounded" />
    </p>

    <p>
      <label>Email</label>
      <input type="email" name="account_email" value="<?php echo esc_attr( $current_user->user_email ); ?>" class="border px-3 py-2 w-full rounded" />
    </p>

    <fieldset class="my-4">
      <label>New Password</label>
      <input type="password" name="password_1" class="border px-3 py-2 w-full rounded" />
    </fieldset>

    <fieldset>
      <label>Confirm Password</label>
      <input type="password" name="password_2" class="border px-3 py-2 w-full rounded" />
    </fieldset>

    <?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
    <input type="hidden" name="action" value="save_account_details" />

    <button type="submit" class="btn mt-2">Save</button>

    <?php do_action( 'woocommerce_edit_account_form_end' ); ?>
  </form>
</div>

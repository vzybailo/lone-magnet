<?php
defined( 'ABSPATH' ) || exit;
?>

<div class="max-w-md mx-auto py-12 px-4">
  <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Welcome Back</h2>

  <form method="post" class="woocommerce-form woocommerce-form-login login space-y-4" <?php do_action( 'woocommerce_login_form_tag' ); ?>>

    <?php do_action( 'woocommerce_login_form_start' ); ?>

    <p>
      <label for="username" class="block text-sm text-gray-600">Email or Username&nbsp;<span class="text-red-500">*</span></label>
      <input type="text" class="w-full px-3 py-2 border rounded focus:outline-none" name="username" id="username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr($_POST['username']) : ''; ?>" />
    </p>

    <p>
      <label for="password" class="block text-sm text-gray-600">Password&nbsp;<span class="text-red-500">*</span></label>
      <input class="w-full px-3 py-2 border rounded focus:outline-none" type="password" name="password" id="password" autocomplete="current-password" />
    </p>

    <?php do_action( 'woocommerce_login_form' ); ?>

    <p class="flex items-center gap-2">
      <input class="rounded" name="rememberme" type="checkbox" id="rememberme" value="forever" />
      <label for="rememberme" class="text-sm text-gray-700">Remember me</label>
    </p>

    <p>
      <button type="submit" class="w-full text-white rounded btn transition" name="login" value="<?php esc_attr_e( 'Login', 'woocommerce' ); ?>">
        <?php esc_html_e( 'Login', 'woocommerce' ); ?>
      </button>
    </p>

    <input type="hidden" name="redirect" value="<?php echo esc_url( apply_filters( 'woocommerce_login_redirect', wc_get_page_permalink( 'myaccount' ) ) ); ?>" />

    <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>

    <?php do_action( 'woocommerce_login_form_end' ); ?>
  </form>

  <p class="mt-4 text-sm text-center text-gray-600">
    New customers can create an account during checkout.
  </p>
</div>

<?php
defined( 'ABSPATH' ) || exit;

$current_user = wp_get_current_user();
?>

<div class="max-w-4xl mx-auto py-10">
  <h1 class="text-2xl font-semibold text-gray-800 mb-6">Welcome back, <?php echo esc_html( $current_user->display_name ); ?> 👋</h1>

  <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
    <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" class="group block bg-white border border-gray-200 p-6 rounded-2xl hover:shadow-md transition">
      <h2 class="text-lg font-medium text-gray-800 group-hover:text-blue-600 mb-2">My Orders</h2>
      <p class="text-sm text-gray-500">Track your recent orders, view details, or reorder items.</p>
    </a>

    <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-account' ) ); ?>" class="group block bg-white border border-gray-200 p-6 rounded-2xl hover:shadow-md transition">
      <h2 class="text-lg font-medium text-gray-800 group-hover:text-blue-600 mb-2">Account Info</h2>
      <p class="text-sm text-gray-500">Edit your name, email, or change your password.</p>
    </a>

    <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ) ); ?>" class="group block bg-white border border-gray-200 p-6 rounded-2xl hover:shadow-md transition">
      <h2 class="text-lg font-medium text-gray-800 group-hover:text-blue-600 mb-2">Addresses</h2>
      <p class="text-sm text-gray-500">Manage your shipping and billing addresses.</p>
    </a>

    <a href="<?php echo esc_url( wc_logout_url() ); ?>" class="group block bg-white border border-gray-200 p-6 rounded-2xl hover:shadow-md transition">
      <h2 class="text-lg font-medium text-gray-800 group-hover:text-red-600 mb-2">Log Out</h2>
      <p class="text-sm text-gray-500">Securely log out from your account.</p>
    </a>
  </div>
</div>

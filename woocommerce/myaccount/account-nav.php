<?php
$current = WC()->query->get_current_endpoint();
?>

<nav class="flex flex-wrap gap-3 mb-8 text-sm font-medium">
  <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>"
     class="uppercase text-sm <?php echo $current === '' ? 'text-wine underline' : 'text-gray-600 hover:text-wine'; ?>">
    Dashboard
  </a>

  <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>"
     class="uppercase text-sm <?php echo $current === 'orders' ? 'text-wine underline' : 'text-gray-600 hover:text-wine'; ?>">
    My Orders
  </a>

  <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-account' ) ); ?>"
     class="uppercase text-sm <?php echo $current === 'edit-account' ? 'text-wine underline' : 'text-gray-600 hover:text-wine'; ?>">
    Profile
  </a>

  <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ) ); ?>"
     class="uppercase text-sm <?php echo $current === 'edit-address' ? 'text-wine underline' : 'text-gray-600 hover:text-blue-600'; ?>">
    Addresses
  </a>

  <a href="<?php echo esc_url( wc_logout_url() ); ?>" class="text-red-600 hover:underline ml-auto">
    Log out
  </a>
</nav>

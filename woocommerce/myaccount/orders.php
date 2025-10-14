<div class="w-full py-8 px-4">
  <h2 class="text-2xl font-semibold text-gray-800 mb-6">My Orders</h2>

  <?php 
  $customer_orders = wc_get_orders([
    'customer' => $current_user->ID,
    'limit'    => -1,
    'status'   => 'any',
  ]);
  
  get_template_part( 'woocommerce/myaccount/account-nav' ); 
  ?>

  <?php if ( $customer_orders ) : ?>
    <div class="space-y-6">
      <?php foreach ( $customer_orders as $order ) : ?>
        <?php
          $order_id        = $order->get_id();
          $order_date      = $order->get_date_created()->format('M d, Y');
          $order_total     = $order->get_formatted_order_total();
          $order_status    = wc_get_order_status_name( $order->get_status() );
          $payment_method  = $order->get_payment_method_title();
          $shipping_address = $order->get_formatted_shipping_address();
          $items           = $order->get_items();
        ?>

      <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm w-full mb-4">
        <!-- Order header -->
        <div class="flex justify-between items-center mb-4">
          <span class="text-sm text-gray-500">Order #<?php echo esc_html( $order_id ); ?></span>
          <span class="text-sm text-gray-400"><?php echo esc_html( $order_date ); ?></span>
        </div>

        <!-- Status -->
        <div class="mb-4">
          <p class="text-sm text-gray-700">
            <span class="font-medium">Status:</span> <?php echo esc_html( $order_status ); ?>
          </p>
        </div>

        <!-- Payment and Shipping -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
          <div>
            <h4 class="text-sm font-semibold text-gray-800 mb-1">Payment Method</h4>
            <p class="text-sm text-gray-700"><?php echo esc_html( $payment_method ); ?></p>
          </div>

          <?php if ( $shipping_address ) : ?>
            <div>
              <h4 class="text-sm font-semibold text-gray-800 mb-1">Shipping Address</h4>
              <p class="text-sm text-gray-700"><?php echo wp_kses_post( nl2br( $shipping_address ) ); ?></p>
            </div>
          <?php endif; ?>
        </div>

        <!-- Items -->
        <div class="mb-4">
          <h4 class="text-sm font-semibold text-gray-800 mb-2">Items</h4>
          <ul class="text-sm text-gray-700 space-y-1 list-disc list-inside">
            <?php foreach ( $items as $item ) :
              $product_name = $item->get_name();
              $qty = $item->get_quantity();
            ?>
              <li><?php echo esc_html( $product_name ); ?> × <?php echo esc_html( $qty ); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>

        <!-- Total -->
        <div class="text-right mt-6 border-t pt-4">
          <span class="text-base font-semibold text-gray-800">Total: <?php echo wp_kses_post( $order_total ); ?></span>
        </div>
      </div>

      <?php endforeach; ?>
    </div>
  <?php else : ?>
    <p class="text-gray-500">You haven’t placed any orders yet.</p>
  <?php endif; ?>
</div>

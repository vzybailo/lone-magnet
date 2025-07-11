<?php
defined( 'ABSPATH' ) || exit;

$order = wc_get_order( get_query_var( 'order-received' ) );
if ( ! $order ) return;
?>

<div class="max-w-2xl mx-auto py-16 px-4 text-left text-gray-800 font-sans">

  <div class="flex items-center justify-center mb-6 w-full text-center">
    <svg class="h-8 w-8 text-green-600 mr-3" fill="none" stroke="currentColor" stroke-width="2"
          viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
    </svg>
    <h1 class="text-2xl font-semibold text-green">Thank you. Your order has been received.</h1>
  </div>

  <div class="bg-white px-6 py-8 border mb-10 bg-stone-50">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 text-sm text-gray-700 text-center mb-10">
      <div>
        <span class="block font-medium text-gray-500 mb-1">Order Number</span>
        <p><?php echo $order->get_order_number(); ?></p>
      </div>
      <div>
        <span class="block font-medium text-gray-500 mb-1">Date</span>
        <p><?php echo wc_format_datetime( $order->get_date_created() ); ?></p>
      </div>
      <div>
        <span class="block font-medium text-gray-500 mb-1">Total</span>
        <p><?php echo $order->get_formatted_order_total(); ?></p>
      </div>
      <div>
        <span class="block font-medium text-gray-500 mb-1">Payment Method</span>
        <p><?php echo esc_html( $order->get_payment_method_title() ); ?></p>
      </div>
    </div>
    <h2 class="text-lg font-semibold text-gray-800 mb-4 text-center">Order Details</h2>
    <div class="divide-y divide-gray-200 text-sm text-gray-700">
      
      <div class="flex justify-between py-4">
        <span class="text-gray-500">Customer Email</span>
        <span><?php echo esc_html( $order->get_billing_email() ); ?></span>
      </div>

      <div class="flex justify-between py-4">
        <span class="text-gray-500">Phone Number</span>
        <span><?php echo esc_html( $order->get_billing_phone() ?: '—' ); ?></span>
      </div>

      <div class="flex justify-between py-4">
        <span class="text-gray-500">Billing Address</span>
        <span class="text-right"><?php echo wp_kses_post( $order->get_formatted_billing_address() ?: '—' ); ?></span>
      </div>

      <div class="flex justify-between py-4">
        <span class="text-gray-500">Shipping Address</span>
        <span class="text-right"><?php echo wp_kses_post( $order->get_formatted_shipping_address() ?: '—' ); ?></span>
      </div>

      <div class="flex justify-between py-4">
        <span class="text-gray-500">Shipping Method</span>
        <span><?php echo esc_html( $order->get_shipping_method() ?: '—' ); ?></span>
      </div>

    </div>
  </div>

  <div class="text-sm text-gray-700 text-center">
    <p class="mb-1">Need help or have questions about your order?</p>
    <p>
      Email us at <a href="mailto:support@example.com" class="text-blue-600 hover:underline">support@example.com</a>
    </p>
  </div>

  <div class="text-center mt-10">
    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"
       class="inline-block bg-blue-600 text-white text-sm tracking-wide px-6 py-3 font-medium hover:bg-blue-700 transition">
      Continue Shopping
    </a>
  </div>

</div>

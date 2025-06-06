<?php
defined( 'ABSPATH' ) || exit;

$order = wc_get_order( get_query_var( 'order-received' ) );
if ( ! $order ) return;
?>

<div class="max-w-5xl mx-auto py-16 px-4 text-left text-gray-800 font-sans">

  <div class="border border-gray-300 bg-gray-50 p-8 mb-10">
    <div class="flex items-center mb-6">
      <svg class="h-8 w-8 text-green-600 mr-3" fill="none" stroke="currentColor" stroke-width="2"
           viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
      </svg>
      <h1 class="text-2xl font-semibold">Thank you. Your order has been received.</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6 text-sm leading-relaxed mb-8">
      <div>
        <span class="block font-medium text-gray-700 mb-1">Order Number</span>
        <?php echo $order->get_order_number(); ?>
      </div>
      <div>
        <span class="block font-medium text-gray-700 mb-1">Date</span>
        <?php echo wc_format_datetime( $order->get_date_created() ); ?>
      </div>
      <div>
        <span class="block font-medium text-gray-700 mb-1">Email</span>
        <?php echo esc_html( $order->get_billing_email() ); ?>
      </div>
      <div>
        <span class="block font-medium text-gray-700 mb-1">Total</span>
        <?php echo $order->get_formatted_order_total(); ?>
      </div>
      <div>
        <span class="block font-medium text-gray-700 mb-1">Payment Method</span>
        <?php echo esc_html( $order->get_payment_method_title() ); ?>
      </div>
    </div>

    <div class="pt-6 border-t border-gray-200 text-sm">
      <h2 class="text-base font-semibold mb-2">Shipping Address</h2>
      <div class="text-gray-700 whitespace-pre-line">
        <?php echo wp_kses_post( $order->get_formatted_shipping_address() ?: '—' ); ?>
      </div>
    </div>

    <!-- Предполагаемая дата доставки -->
    <?php
    $estimated_days = 5; // Заменить при необходимости
    $estimated_date = date_i18n( 'F j, Y', strtotime( '+' . $estimated_days . ' weekdays' ) );
    ?>
    <div class="mt-6 text-sm text-gray-700">
      <p><span class="font-medium">Estimated Delivery:</span> <?php echo esc_html( $estimated_date ); ?></p>
    </div>
  </div>

  <div class="text-sm text-gray-700 text-center">
    <p class="mb-1">Need help or have questions about your order?</p>
    <p>
      Email us at <a href="mailto:support@example.com" class="text-blue-600 hover:underline">support@example.com</a><br>
      or call <span class="font-medium">1-800-123-4567</span>
    </p>
  </div>

  <div class="text-center mt-10">
    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"
       class="inline-block bg-blue-600 text-white text-sm tracking-wide px-6 py-3 font-medium hover:bg-blue-700 transition">
      Continue Shopping
    </a>
  </div>

</div>

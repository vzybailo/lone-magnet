<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
  <header class="header border-b">
    <div class="bg-gray-900 text-white text-center text-xs py-2">🚚✈️ Enjoy FREE U.S. shipping on orders over $40</div>
    <div class="container mx-auto flex justify-between items-center py-8 max-sm:px-4 max-sm:py-4">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center">
        <?php 
          if (has_custom_logo()) {
            echo get_custom_logo();
          } else {
            echo '<span class="text-xl font-bold">' . get_bloginfo('name') . '</span>';
          }
        ?>
      </a>
      <nav class="max-md:hidden">
        <?php wp_nav_menu(['theme_location' => 'header-menu', 'menu_class' => 'flex gap-4 font-light']); ?>
      </nav>
      <?php if (class_exists('WooCommerce')) : ?>
        <div class="relative flex items-center">
          <a href="<?php echo wc_get_cart_url(); ?>" class="flex items-center gap-2 text-gray-800 hover:text-blue-600 max-sm:mr-2">
            <svg fill="#000000" width="20px" height="20px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg">
              <path d="M26.834 24.918c-1.402 0.002-2.573 0.993-2.852 2.313l-0.003 0.019h-15.922c-0.161-0.767-0.603-1.409-1.21-1.829l-0.010-0.006 0.734-2.665h20.39c0 0 0.001 0 0.001 0 0.376 0 0.687-0.277 0.741-0.639l0-0.004 2.039-14c0.005-0.033 0.008-0.070 0.008-0.109 0-0.394-0.304-0.717-0.691-0.747l-0.003-0-25.307-1.946v-1.305c0-0 0-0 0-0 0-0.207-0.084-0.395-0.22-0.53l-2-2c-0.135-0.131-0.32-0.212-0.523-0.212-0.414 0-0.75 0.336-0.75 0.75 0 0.203 0.081 0.388 0.213 0.523l1.78 1.78v1.689c0.004 0.030 0.010 0.056 0.017 0.081l-0.001-0.005c0.002 0.021-0.007 0.041-0.003 0.062l2.968 15.83-0.813 2.955c-0.078-0.006-0.154-0.024-0.234-0.024-0.005-0-0.010-0-0.016-0-1.62 0-2.934 1.313-2.934 2.934s1.313 2.934 2.934 2.934c1.297 0 2.397-0.842 2.785-2.009l0.006-0.021c0.020 0.002 0.037 0.012 0.058 0.012h15.983c0.030-0.004 0.056-0.010 0.082-0.017l-0.005 0.001c0.393 1.172 1.482 2.002 2.764 2.002 1.607 0 2.909-1.302 2.909-2.909s-1.302-2.909-2.909-2.909c-0.002 0-0.005 0-0.007 0h0zM4.917 6.822l24.226 1.863-1.83 12.564h-19.691zM5.184 29.268c-0 0-0.001 0-0.001 0-0.792 0-1.434-0.642-1.434-1.434s0.642-1.434 1.434-1.434c0.792 0 1.434 0.642 1.434 1.434v0c-0.001 0.791-0.642 1.432-1.432 1.434h-0zM26.834 29.248c-0.781-0.001-1.414-0.634-1.414-1.415s0.634-1.415 1.415-1.415 1.415 0.634 1.415 1.415c0 0 0 0.001 0 0.001v-0c-0.001 0.781-0.635 1.414-1.416 1.414v0z"></path>
            </svg>
            <span class="text-sm font-light">
              <?php
              $cart_count = WC()->cart->get_cart_contents_count();
              if ( $cart_count > 0 ) {
                  echo $cart_count;
              }
              ?>
            </span>
          </a>
          <div id="burger-btn" class="flex flex-col justify-center items-center w-8 h-8 cursor-pointer z-30 relative max-md:flex hidden">
            <span class="burger-line w-6 h-[2px] bg-black mb-1 transition-all duration-300"></span>
            <span class="burger-line w-6 h-[2px] bg-black mb-1 transition-all duration-300"></span>
            <span class="burger-line w-6 h-[2px] bg-black transition-all duration-300"></span>
          </div>

          <div id="burger-menu" class="fixed left-0 h-screen w-2/3 max-w-xs bg-white shadow-lg -translate-x-full transition-transform duration-300 ease-in-out z-20">
            <nav class="flex flex-col items-start gap-4 p-6 font-light">
              <?php wp_nav_menu(['theme_location' => 'header-menu', 'menu_class' => 'flex flex-col gap-4']); ?>
            </nav>
          </div>

        </div>
      <?php endif; ?>
    </div>
  </header>

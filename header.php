<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
  <header class="">
    <div class="bg-gray-800 text-white text-center text-sm py-2">🚚✈️ FREE SHIPPING on US orders over $40</div>
    <div class="container mx-auto flex justify-between items-center py-4">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center">
        <?php 
          if (has_custom_logo()) {
            echo get_custom_logo();
          } else {
            echo '<span class="text-xl font-bold">' . get_bloginfo('name') . '</span>';
          }
        ?>
      </a>
      <nav>
        <?php wp_nav_menu(['theme_location' => 'header-menu', 'menu_class' => 'flex gap-4']); ?>
      </nav>
      <?php if (class_exists('WooCommerce')) : ?>
        <div class="relative">
          <a href="<?php echo wc_get_cart_url(); ?>" class="flex items-center gap-2 text-gray-800 hover:text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9h14l-2-9M10 21a1 1 0 11-2 0 1 1 0 012 0zm8 0a1 1 0 11-2 0 1 1 0 012 0z"/>
            </svg>
            <span class="text-sm font-semibold">
              <?php echo WC()->cart->get_cart_contents_count(); ?>
            </span>
          </a>
        </div>
      <?php endif; ?>
    </div>
  </header>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
  <header class="header border-b">
    <div class="bg-blue-dark text-white text-center text-xs py-2">🚚✈️ Enjoy FREE U.S. shipping on orders over $40</div>
    <div class="header__main container mx-auto flex justify-between items-center py-8 max-sm:px-4 max-sm:py-4">
      <?php 
          if ( has_custom_logo() && is_front_page() ) {
            echo get_custom_logo();
          } elseif ( !is_front_page() ) {
            echo '<a href="/"><img src="' . get_stylesheet_directory_uri() . '/assets/images/logo-black.png" alt="' . get_bloginfo('name') . '" class="black-logo"></a>';
          } else {
            echo '<span class="text-xl font-bold">' . get_bloginfo('name') . '</span>';
          }
        ?>
      <div class="flex">
        <nav class="header__nav max-md:hidden">
          <?php wp_nav_menu(['theme_location' => 'header-menu', 'menu_class' => 'flex gap-4 font-light text-sm']); ?>
        </nav>
        <?php if (class_exists('WooCommerce')) : ?>
          <div class="relative flex items-center">
          <?php
            $fill = is_front_page() ? '#fff' : '#000';
            ?>

            <a href="<?php echo wc_get_cart_url(); ?>" class="flex items-center mr-2 <?php echo is_front_page() ? 'text-white' : 'text-gray-800'; ?> hover:text-blue-600 max-sm:mr-2">
              <svg fill="<?php echo $fill; ?>" width="20px" height="20px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg">
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
            <?php
            $current_user = wp_get_current_user();
            $is_logged_in = is_user_logged_in();
            $account_url = esc_url( wc_get_page_permalink( 'myaccount' ) );
            ?>
            <div>
              <a href="<?php echo $account_url; ?>" class="flex items-center">
                <svg style="<?php echo $is_logged_in ? 'margin-right: 5px;' : null ?>" width="22px" height="22px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M12.1992 12C14.9606 12 17.1992 9.76142 17.1992 7C17.1992 4.23858 14.9606 2 12.1992 2C9.43779 2 7.19922 4.23858 7.19922 7C7.19922 9.76142 9.43779 12 12.1992 12Z" stroke="<?php echo $fill;?>" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M3 22C3.57038 20.0332 4.74796 18.2971 6.3644 17.0399C7.98083 15.7827 9.95335 15.0687 12 15C16.12 15 19.63 17.91 21 22" stroke="<?php echo $fill;?>" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="text-sm px-2" style="color: <?php echo $fill;?>; <?php echo $is_logged_in ? 'margin-right: 5px;' : null ?>">
                  <?php if($is_logged_in) echo esc_html( $current_user->display_name );?>
                </span>
              </a>
            </div>
            <div id="burger-btn" class="flex flex-col justify-center items-center ml-2 w-8 h-8 cursor-pointer z-30 relative max-md:flex hidden">
              <span style="background: <?php echo $fill; ?>" class="burger-line w-6 h-[2px] mb-1 transition-all duration-300"></span>
              <span style="background: <?php echo $fill; ?>" class="burger-line w-6 h-[2px] mb-1 transition-all duration-300"></span>
              <span style="background: <?php echo $fill; ?>" class="burger-line w-6 h-[2px] transition-all duration-300"></span>
            </div>
            <div id="burger-menu" class="fixed top-0 left-0 w-full h-screen bg-white -translate-x-full transition-transform duration-300 ease-in-out z-20">
              <nav class="flex flex-col items-center justify-center h-full gap-6 text-black text-xl font-light">
                <?php wp_nav_menu([
                  'theme_location' => 'header-menu',
                  'menu_class' => 'flex flex-col gap-6 items-center',
                  'container' => false
                ]); ?>
              </nav>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </header>

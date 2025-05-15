<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
  <header class="bg-gray-800 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
      <?php get_custom_logo(); ?>
      <nav>
        <?php wp_nav_menu(['theme_location' => 'primary', 'menu_class' => 'flex gap-4']); ?>
      </nav>
    </div>
  </header>

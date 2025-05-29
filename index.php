<?php
/**
 * Template Name: Home
 */
get_header();
?>

<main class="home-page container">
  <?php while ( have_posts() ) : the_post(); ?>
    <?php the_content(); ?>
    <div id="burger-menu" class="">
      <nav>
        <?php wp_nav_menu(['theme_location' => 'header-menu', 'menu_class' => 'flex gap-4 font-light']); ?>
      </nav>
    </div>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>

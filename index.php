<?php
/**
 * Template Name: Home
 */
get_header();
?>

<main class="home-page container">
  <?php while ( have_posts() ) : the_post(); ?>
    <?php the_content(); ?>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>

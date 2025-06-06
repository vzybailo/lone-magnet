<?php get_header(); ?>

<main class="container mx-auto max-sm:px-4">
  <?php while ( have_posts() ) : the_post(); ?>
    <article class="prose max-w-full">
      <?php the_content(); ?>
    </article>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>

<?php get_header(); ?>

<div class="not-found container py-10 flex flex-col items-center max-sm:px-4">
  <img class="not-found-img" src="<?php echo get_template_directory_uri() . '/assets/images/404.png'?>" alt="404">
  <h1 class="mb-6 text-xl">Oops! Page Not Found!</h1>
  <div class="mb-2 not-found__descr">Sorry, we couldn’t find the page you’re looking for. Maybe it got lost like a fridge magnet in a Texas storm!</div>
  <div class="not-found__descr">But don’t worry — your next favorite photo magnet is just a click away.</div>

  <p class="flex items-center pt-6 not-found__btns">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn button-home mx-4">Go Back Home</a>
    <span class="px-4">OR</span>
    <a href="<?php echo esc_url(home_url('/custom-photo-magnets')); ?>" class="btn">Make your Magnets</a>
  </p>
</div>

<?php get_footer(); ?>
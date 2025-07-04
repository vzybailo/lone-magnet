<?php
/**
 * Template Name: Contact Page
 */
get_header();
?>

<section class="py-16 flex-1">
  <div class="container mx-auto px-4 max-w-3xl">
    <h1 class="text-3xl font-bold mb-6 text-center">Get in Touch</h1>
    <p class="font-light mb-10 text-center">
      Have a question or just want to say hi? Fill out the form below and we’ll get back to you as soon as possible.
    </p>

    <div class="p-6 sm:p-10">
      <?php echo do_shortcode('[contact-form-7 id="81fada5" title="Contact form 1"]'); ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>

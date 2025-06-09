<?php
/**
 * Template Name: Contact Page
 */
get_header();
?>

<section class="bg-gray-50 py-16">
  <div class="container mx-auto px-4 max-w-3xl">
    <h1 class="text-4xl font-bold mb-6 text-center text-gray-800">Get in Touch</h1>
    <p class="text-lg font-light text-gray-600 mb-10 text-center">
      Have a question or just want to say hi? Fill out the form below and we’ll get back to you as soon as possible.
    </p>

    <div class="bg-white shadow p-6 sm:p-10">
      <?php echo do_shortcode('[contact-form-7 id="81fada5" title="Contact form 1"]'); ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>

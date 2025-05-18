<?php
defined('ABSPATH') || exit;
global $product;

if (!comments_open()) {
  return;
}
?>

<div id="reviews" class="woocommerce-Reviews mt-12">

  <!-- Reviews Title -->
  <h2 class="text-2xl font-semibold text-center">
    <?php
    $count = $product->get_review_count();
    echo $count ? 'What Our Customers Are Saying' : 'No reviews yet';
    ?>
  </h2>
  <div class="font-light text-center mb-2">Real feedback from real buyers.</div>

  <!-- Reviews List -->
  <?php if (have_comments()) : ?>
    <ol class="mb-4">
      <?php
      wp_list_comments([
        'callback' => function ($comment, $args, $depth) {
          $rating = intval(get_comment_meta($comment->comment_ID, 'rating', true));
          ?>
          <li class="py-4 border-b">
            <div class="flex justify-between mb-2">
              <?php if ($rating) : ?>
                <div class="text-gold text-lg">
                  <?php for ($i = 1; $i <= 5; $i++) echo $i <= $rating ? '★' : '☆'; ?>
                </div>
              <?php endif; ?>
              <span class="text-sm text-gray-500"><?php echo get_comment_date(); ?></span>
            </div>
            <div class="flex items-center mb-2">
              <div class="flex-shrink-0">
                <?php echo get_avatar($comment, 30, '', '', ['class' => 'rounded-full mr-2']); ?>
              </div>
              <span class="font-semibold"><?php echo get_comment_author(); ?></span>
            </div>
            <div class="text-gray-700 font-light"><?php echo get_comment_text(); ?></div>
          </li>
          <?php
        },
        'style' => 'ol',
      ]);
      ?>
    </ol>
    <?php
        if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
          echo '<nav class="woocommerce-pagination">';
          paginate_comments_links(
            apply_filters(
              'woocommerce_comment_pagination_args',
              array(
                'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
                'next_text' => is_rtl() ? '&larr;' : '&rarr;',
                'type'      => 'list',
              )
            )
          );
          echo '</nav>';
        endif;
        ?>
    <?php endif; ?>

  <!-- Leave a Review -->
  <?php if (get_option('woocommerce_review_rating_verification_required') === 'no' || wc_customer_bought_product('', get_current_user_id(), $product->get_id())) : ?>
    <div id="review_form_wrapper" class="pt-6 text-center">
      <h3 class="text-xl font-semibold mb-4">Share Your Experience</h3>
      <div id="review_form" class="space-y-4">
        <?php
        $commenter = wp_get_current_commenter();
        $fields = [
          'author' => '<input id="author" name="author" type="text" placeholder="Your name" class="w-full border rounded px-3 py-2" required>',
          'email' => '<input id="email" name="email" type="email" placeholder="Your email" class="w-full border rounded px-3 py-2" required>',
        ];

        comment_form([
          'title_reply' => '',
          'class_form' => '',
          'label_submit' => 'Submit review',
          'comment_field' => '
            <div>
              <label for="rating" class="block mb-1 text-sm font-medium font-light">Your rating</label>
              <select name="rating" id="rating" required class="w-full border rounded px-3 py-2 mb-4">
                <option value="">Rate…</option>
                <option value="5">★★★★★ Perfect</option>
                <option value="4">★★★★☆ Good</option>
                <option value="3">★★★☆☆ Average</option>
                <option value="2">★★☆☆☆ Poor</option>
                <option value="1">★☆☆☆☆ Terrible</option>
              </select>
            </div>
            <textarea id="comment" name="comment" placeholder="Write your review…" class="w-full border border-gray-300 focus:outline-none focus:ring-1 focus:border-transparent px-3 py-2" rows="5" required></textarea>
          ',
          'fields' => $fields,
        ]);
        ?>
      </div>
    </div>
  <?php else : ?>
    <p class="text-gray-600 text-sm">Only verified customers who purchased this product can leave a review.</p>
  <?php endif; ?>
</div>

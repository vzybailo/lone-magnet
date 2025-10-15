<?php
/**
 * Template Name: Home
 */
get_header();
?>

<main class="home-page">
  <section class="hero max-sm:px-4">
    <div class="container flex justify-center flex-col h-full text-white">
      <div class="hero__block mb-6">
        <h1 class="hero__title flex flex-col"><span>Memories</span> That Last Forever</h1>
        <p class="hero__subtitle">Turn your favorite memories into custom photo fridge magnets made in Texas!</p>
        <a href="/custom-photo-magnets" class="hero__btn btn">Make Your magnets</a>
      </div>
      <div class="hero__socials flex space-x-4">
          <a href="https://www.instagram.com/lonemagnet/" class="socials-icon" target="blank" aria-label="Instagram">
            <svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 24 24" fill="none">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 18C15.3137 18 18 15.3137 18 12C18 8.68629 15.3137 6 12 6C8.68629 6 6 8.68629 6 12C6 15.3137 8.68629 18 12 18ZM12 16C14.2091 16 16 14.2091 16 12C16 9.79086 14.2091 8 12 8C9.79086 8 8 9.79086 8 12C8 14.2091 9.79086 16 12 16Z"/>
            <path d="M18 5C17.4477 5 17 5.44772 17 6C17 6.55228 17.4477 7 18 7C18.5523 7 19 6.55228 19 6C19 5.44772 18.5523 5 18 5Z"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M1.65396 4.27606C1 5.55953 1 7.23969 1 10.6V13.4C1 16.7603 1 18.4405 1.65396 19.7239C2.2292 20.8529 3.14708 21.7708 4.27606 22.346C5.55953 23 7.23969 23 10.6 23H13.4C16.7603 23 18.4405 23 19.7239 22.346C20.8529 21.7708 21.7708 20.8529 22.346 19.7239C23 18.4405 23 16.7603 23 13.4V10.6C23 7.23969 23 5.55953 22.346 4.27606C21.7708 3.14708 20.8529 2.2292 19.7239 1.65396C18.4405 1 16.7603 1 13.4 1H10.6C7.23969 1 5.55953 1 4.27606 1.65396C3.14708 2.2292 2.2292 3.14708 1.65396 4.27606ZM13.4 3H10.6C8.88684 3 7.72225 3.00156 6.82208 3.0751C5.94524 3.14674 5.49684 3.27659 5.18404 3.43597C4.43139 3.81947 3.81947 4.43139 3.43597 5.18404C3.27659 5.49684 3.14674 5.94524 3.0751 6.82208C3.00156 7.72225 3 8.88684 3 10.6V13.4C3 15.1132 3.00156 16.2777 3.0751 17.1779C3.14674 18.0548 3.27659 18.5032 3.43597 18.816C3.81947 19.5686 4.43139 20.1805 5.18404 20.564C5.49684 20.7234 5.94524 20.8533 6.82208 20.9249C7.72225 20.9984 8.88684 21 10.6 21H13.4C15.1132 21 16.2777 20.9984 17.1779 20.9249C18.0548 20.8533 18.5032 20.7234 18.816 20.564C19.5686 20.1805 20.1805 19.5686 20.564 18.816C20.7234 18.5032 20.8533 18.0548 20.9249 17.1779C20.9984 16.2777 21 15.1132 21 13.4V10.6C21 8.88684 20.9984 7.72225 20.9249 6.82208C20.8533 5.94524 20.7234 5.49684 20.564 5.18404C20.1805 4.43139 19.5686 3.81947 18.816 3.43597C18.5032 3.27659 18.0548 3.14674 17.1779 3.0751C16.2777 3.00156 15.1132 3 13.4 3Z"/>
            </svg>
          </a>
          <a href="https://www.tiktok.com/@lonemagnet" target="blank" class="socials-icon" aria-label="TikTok">
            <svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 24 24" id="Layer_1" data-name="Layer 1"><defs></defs><path class="cls-1" d="M12.94,1.61V15.78a2.83,2.83,0,0,1-2.83,2.83h0a2.83,2.83,0,0,1-2.83-2.83h0a2.84,2.84,0,0,1,2.83-2.84h0V9.17h0A6.61,6.61,0,0,0,3.5,15.78h0a6.61,6.61,0,0,0,6.61,6.61h0a6.61,6.61,0,0,0,6.61-6.61V9.17l.2.1a8.08,8.08,0,0,0,3.58.84h0V6.33l-.11,0a4.84,4.84,0,0,1-3.67-4.7H12.94Z"/></svg>
          </a>
          <a href="https://www.facebook.com/profile.php?id=61577335115457" class="socials-icon" target="blank" aria-label="Facebook">
            <svg xmlns="http://www.w3.org/2000/svg" fill="#000000" width="800px" height="800px" viewBox="0 0 1920 1920">
              <path d="M1168.737 487.897c44.672-41.401 113.824-36.889 118.9-36.663l289.354-.113 6.317-417.504L1539.65 22.9C1511.675 16.02 1426.053 0 1237.324 0 901.268 0 675.425 235.206 675.425 585.137v93.97H337v451.234h338.425V1920h451.234v-789.66h356.7l62.045-451.233H1126.66v-69.152c0-54.937 14.214-96.112 42.078-122.058" fill-rule="evenodd"/>
          </svg>
          </a>
          </div>
      </div>
  </section>
  <section id="how-it-works" class="max-sm:px-4">
    <div class="text-center py-16">
      <h2 class="title-line text-2xl font-bold mb-12">How We Make Your Custom Photo Fridge Magnets</h2>
      <ul class="container grid md:grid-cols-3 gap-10">
        <li class="flex items-center flex-col">
          <p class="how-it-works__num">1</p>
          <h3 class="text-xl font-semibold mb-2 uppercase">Upload Your Photo</h3>
          <p> Choose a photo from your phone or computer to start creating your <a href="/custom-photo-magnets/" class="underline">custom photo fridge magnet</a>. We recommend using a high-resolution image for the best results.</p>
        </li>
        <li class="flex items-center flex-col">
          <p class="how-it-works__num">2</p>
          <h3 class="text-xl font-semibold mb-2 uppercase">We Print It</h3>
          <p>Our team prints your photo using premium materials and vibrant colors. Each magnet is carefully made in our Texas-based studio.</p>
        </li>
        <li class="flex items-center flex-col">
          <p class="how-it-works__num">3</p>
          <h3 class="text-xl font-semibold mb-2 uppercase">Fast Delivery</h3>
          <p>We ship your magnets within 2–3 business days via <a href="https://www.usps.com/" target="_blank" rel="noopener">USPS</a>. Your order will arrive quickly — ready to decorate your fridge or be gifted to someone special!</p>
        </li>
      </ul>
    </div>
  </section>
  <section id="testimonials" class="max-sm:px-4">
    <div class="text-center py-16">
        <h2 class="title-line text-2xl font-bold mb-12">What Our Customers Say</h2>
        <div class="container swiper mySwiper my-review-swiper">
          <div class="swiper-wrapper">
            <?php
            $has_reviews = false;

            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => 3,
                'orderby'        => 'date',
                'order'          => 'DESC',
            );

            $query = new WP_Query( $args );
            while ( $query->have_posts() ) : $query->the_post();
                $product = wc_get_product( get_the_ID() );

                $reviews = get_comments( array(
                    'post_id' => $product->get_id(),
                    'status'  => 'approve'
                ) );

              foreach ( $reviews as $review ) :
                  $image_id  = get_comment_meta( $review->comment_ID, 'ivole_review_image2', true );
                  $image_url = $image_id ? wp_get_attachment_url( $image_id ) : ''; // если нет фото, пустая строка
                  $rating    = intval( get_comment_meta( $review->comment_ID, 'rating', true ) );

                  $has_reviews = true;
              ?>
                  <div class="swiper-slide">
                    <div class="swiper-slide__item bg-white p-6 shadow-lg mx-auto border border-gray-200 flex flex-col h-full relative">

                      <div class="review__quotes absolute top-4 left-4 pointer-events-none">
                        <svg width="30px" height="30px" viewBox="0 0 1200 1200" xmlns="http://www.w3.org/2000/svg">
                                      <path d="M518.474,105.344C305.831,120.286,0.168,154.236,0,570.687v523.97h474.504v-560.61H316.946C306.965,384.354,430.23,345.701,564.274,316.03L518.474,105.344z M1154.198,105.344c-212.643,14.942-518.306,48.893-518.473,465.343v523.97h474.505v-560.61H952.672C942.689,384.354,1065.956,345.701,1200,316.03L1154.198,105.344L1154.198,105.344z"/>
                                    </svg>
                      </div>

                      <div class="flex justify-center mb-3 mt-2 z-10">
                        <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                          <svg class="w-5 h-5 text-gold <?php echo $i > $rating ? 'opacity-30' : ''; ?>" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.222 3.775h3.974c.969 0 1.371 1.24.588 1.81l-3.214 2.334 1.222 3.775c.3.921-.755 1.688-1.54 1.118L10 13.347l-3.214 2.334c-.785.57-1.84-.197-1.54-1.118l1.222-3.775-3.214-2.334c-.783-.57-.38-1.81.588-1.81h3.974l1.222-3.775z"/>
                          </svg>
                        <?php endfor; ?>
                      </div>

                      <div class="flex justify-center mb-3 mt-2 z-10">
                        <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                          <svg class="w-5 h-5 text-gold <?php echo $i > $rating ? 'opacity-30' : ''; ?>" fill="currentColor" viewBox="0 0 20 20">
                            <path d="..."/>
                          </svg>
                        <?php endfor; ?>
                      </div>

                      <div class="h-full">
                        <div class="h-full flex flex-col justify-center">
                          <?php if ( $image_url ) : ?>
                          <div class="flex justify-center mb-2 z-10">
                            <a href="<?php echo esc_url( $image_url ); ?>" class="glightbox" data-gallery="review-<?php echo $review->comment_ID; ?>">
                              <img src="<?php echo esc_url( $image_url ); ?>" alt="Review image" class="w-40 h-40 object-cover rounded">
                            </a>
                          </div>
                          <?php endif; ?>

                          <p class="review__text text-gray-800 italic relative z-10 text-center px-4 line-clamp-4">
                            "<?php echo esc_html( $review->comment_content ); ?>"
                          </p>
                          <button class="show-more text-blue-500 text-sm mt-1 hidden underline">Show more</button>

                          <div class="mt-2 text-center text-sm z-10">
                            <p class="font-semibold"><?php echo esc_html( $review->comment_author ); ?></p>
                            <p class="text-gray-500"><?php echo get_comment_date( 'F j, Y', $review->comment_ID ); ?></p>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
              <?php
              endforeach;
            endwhile;
            wp_reset_postdata();
            ?>
          </div>

          <?php if ( $has_reviews ) : ?>
            <div class="swiper-pagination"></div>
          <?php else : ?>
            <div class="flex flex-col items-center justify-center py-12 px-4 text-center bg-white shadow-md border border-gray-200 rounded">
              <div class="mb-4">
                <svg width="50px" height="50px" viewBox="0 0 1200 1200" xmlns="http://www.w3.org/2000/svg" class="mx-auto text-gray-400 opacity-50">
                  <path d="M518.474,105.344C305.831,120.286,0.168,154.236,0,570.687v523.97h474.504v-560.61H316.946C306.965,384.354,430.23,345.701,564.274,316.03L518.474,105.344z M1154.198,105.344c-212.643,14.942-518.306,48.893-518.473,465.343v523.97h474.505v-560.61H952.672C942.689,384.354,1065.956,345.701,1200,316.03L1154.198,105.344L1154.198,105.344z"/>
                </svg>
              </div>
              <p class="p-light text-gray-600 mb-3">We’re proud to deliver high-quality <strong>custom photo magnets</strong> to happy customers across the USA. Be the first to leave a review and share your fridge story!</p>
              <a href="/product/custom-photo-magnets/#reply-title" class="btn mt-2 px-5 py-2">
                Leave a Review
              </a>
            </div>
          <?php endif; ?>
        </div>
  </section>
  <section id="cta" class="py-16 text-center px-6 max-sm:px-4">
    <div class="container text-blue">
      <h2 class="text-3xl font-bold mb-4 text-white">Ready to Create Your Custom Photo Fridge Magnet?</h2>
      <p class="p-light mb-6 text-white">Turn your favorite memory into a fridge photo magnet today. <br>Whether it's a family photo, a vacation moment, or a gift idea — we make it easy and fast.</p>
      <a href="/custom-photo-magnets" class="btn cta__btn py-3 px-6">Upload Your Photo</a>
    </div>
  </section>
  <section id="faq" class="max-sm:px-4">
    <div class="py-16">
      <h2 class="title-line text-2xl font-bold mb-12 text-center">Frequently Asked Questions</h2>
      <ul class="container space-y-6">
        <li class="border-b py-4">
          <h3 class="text-xl font-semibold mb-2">What is the delivery time?</h3>
          <p class="p-light">We ship your custom fridge magnets within 2–3 business days. Delivery usually takes 5–7 days depending on your location in the USA.</p>
        </li>
        <li class="border-b py-4">
          <h3 class="text-xl font-semibold mb-2">What photo formats do you accept?</h3>
          <p class="p-light">We accept JPG, PNG, and JPEG formats. High-resolution images will ensure the best print quality for your magnetic photo prints.</p>
        </li>
        <li class="border-b py-4">
          <h3 class="text-xl font-semibold mb-2">Can I order in bulk?</h3>
          <p class="p-light">Yes! We offer bulk discounts for parties, weddings, and business events. <a href="/contact" class="underline">Contact us</a> for custom pricing or visit the <a href="/product/bulk-order" class="underline">Bulk Order</a> page.</p>
        </li>
      </ul>
    </div>
  </section>
</main>

<?php get_footer(); ?>

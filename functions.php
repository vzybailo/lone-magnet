<?php
// styles and scripts
function lone_enqueue_assets() {
    wp_enqueue_style(
        'tailwind',
        get_template_directory_uri() . '/dist/style.css',
        [],
        filemtime(get_template_directory() . '/dist/style.css')
    );

    wp_enqueue_style(
        'custom',
        get_template_directory_uri() . '/dist/custom.css',
        ['tailwind'],
        filemtime(get_template_directory() . '/dist/custom.css')
    );

    wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css');
    wp_enqueue_style('glightbox', 'https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css');

    wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', [], null, true);
    wp_enqueue_script('glightbox', 'https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js', [], null, true);
    wp_enqueue_script('script', get_template_directory_uri() . '/assets/js/scripts.js', [], null, true);
    wp_enqueue_script('photo-upload', get_template_directory_uri() . '/assets/js/photo-upload.js', [], null, true);

    wp_localize_script('photo-upload', 'wpApiSettings', [
        'nonce' => wp_create_nonce('wp_rest')
    ]);

    wp_add_inline_script('glightbox', "
        document.addEventListener('DOMContentLoaded', function () {
            GLightbox({ selector: '.glightbox' });
        });
    ");
}
add_action('wp_enqueue_scripts', 'lone_enqueue_assets');

// setting for the theme
function lone_setup_theme() {
    add_theme_support('title-tag');
    add_theme_support('html5', ['search-form', 'comment-form', 'gallery', 'caption']);
    add_theme_support('editor-styles'); 
    add_editor_style('style.css'); 
    add_theme_support('custom-logo');
    add_theme_support('woocommerce');

    register_nav_menus([
        'header-menu' => __('Header Menu'),
        'footer-menu' => __('Footer Menu'),
    ]);
}
add_action('after_setup_theme', 'lone_setup_theme');

// add upload button to the product
function render_custom_upload_button() {
    global $product;
    $product_id = $product->get_id();
    $categories = wp_get_post_terms( get_the_ID(), 'product_cat', ['fields' => 'slugs'] );
    $mode = in_array('bulk', $categories) ? 'bulk' : 'set9';

    echo '<button type="button" class="bg-wine text-white hover:bg-wine-dark w-full p-2 transition-colors duration-300" id="custom-photo-upload">Add photos</button>';
    echo '<div id="custom-photo-modal-root" data-product-id="' . esc_attr($product_id) . '" data-mode="' . esc_attr($mode) . '"></div>';
    echo '<div class="lone-alert text-sm"></div>';
}
add_action('woocommerce_before_add_to_cart_button', 'render_custom_upload_button');

function render_custom_upload_button_custom6() {
    global $product;
    $product_id = $product->get_id();
    $categories = wp_get_post_terms(get_the_ID(), 'product_cat', ['fields' => 'slugs']);
    
    // Проверяем, что это товар категории custom6
    if (!in_array('custom6', $categories)) return;

    // Счётчик количества фото
    echo '<label for="photo-count" class="block mb-1 text-sm font-semibold">Select number of photos (1–6):</label>';
    echo '<input type="number" id="photo-count" class="w-16 p-1 border rounded" min="1" max="6" value="1">';

    // Кнопка загрузки
    echo '<button type="button" class="bg-wine text-white hover:bg-wine-dark w-full p-2 mt-2 transition-colors duration-300" id="custom-photo-upload">Add photos</button>';

    // Контейнер для фронтенд-логики
    echo '<div id="custom-photo-modal-root" data-product-id="' . esc_attr($product_id) . '" data-mode="custom6" data-max-photos="1"></div>';
    echo '<div class="lone-alert text-sm mt-1"></div>';
}
add_action('woocommerce_before_add_to_cart_button', 'render_custom_upload_button_custom6', 20);


// custom classes for price
function custom_woocommerce_price_html( $price, $product ) {
    if ( $product->is_on_sale() ) {
        return '<span class="lone-regular-price line-through text-grey mr-2">' . wc_price( $product->get_regular_price() ) . '</span>' .
               '<span class="lone-sale-price text-red font-bold">' . wc_price( $product->get_sale_price() ) . '</span>';
    }

    return '<span class="lone-regular-price text-black font-semibold">' . wc_price( $product->get_regular_price() ) . '</span>';
}
add_filter( 'woocommerce_get_price_html', 'custom_woocommerce_price_html', 100, 2 );

// add 'you save summ' to the product
function lone_save_summ() {
    echo '<div class="lone-save-summ text-green text-sm"></div>';
}
add_action('woocommerce_template_single_price', 'lone_save_summ');

// setting up adding photos
function upload_user_photo() {
    if (
        !isset($_FILES['photo']) ||
        !is_uploaded_file($_FILES['photo']['tmp_name'])
    ) {
        wp_send_json_error(['message' => 'Invalid file upload']);
        wp_die();
    }

    $file = $_FILES['photo'];
    $upload_dir = wp_upload_dir();
    $target_dir = trailingslashit($upload_dir['path']);
    $filename = wp_unique_filename($target_dir, basename($file['name']));
    $target_file = $target_dir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $target_file)) {
        wp_send_json_error(['message' => 'Failed to move uploaded file.']);
        wp_die();
    }

    $file_url = trailingslashit($upload_dir['url']) . $filename;

    wp_send_json_success([
        'url' => esc_url_raw($file_url),
        'filename' => $filename
    ]);

    wp_die();
}
add_action('wp_ajax_upload_user_photo', 'upload_user_photo');
add_action('wp_ajax_nopriv_upload_user_photo', 'upload_user_photo');

// save photos to the cart, allow to add more items with photos
function save_uploaded_photos_to_cart($cart_item_data, $product_id, $variation_id) {
    if (isset($_POST['magnet_photos_data'])) {
        $photos = json_decode(stripslashes($_POST['magnet_photos_data']), true);
        if (is_array($photos)) {
            $cart_item_data['magnet_photos'] = $photos;
            $cart_item_data['unique_key'] = md5(microtime().rand()); 
        }
    }
    return $cart_item_data;
}
add_filter('woocommerce_add_cart_item_data', 'save_uploaded_photos_to_cart', 10, 3);

// transfer photos data to order 
function save_photos_to_order($item, $cart_item_key, $values, $order) {
    if (isset($values['magnet_photos'])) {
        $item->add_meta_data('magnet_photos', $values['magnet_photos'], true);
    }
}
add_action('woocommerce_checkout_create_order_line_item', 'save_photos_to_order', 10, 4);

// thumnails uploaded photos at the order
function show_uploaded_photos_in_admin($item_id, $item, $product) {
    $photos = wc_get_order_item_meta($item_id, 'magnet_photos');
    if (is_array($photos) && !empty($photos)) {
        echo '<div><strong">Uploaded photos:</strong><br>';
        foreach ($photos as $photo) {
            if (isset($photo['url'])) {
                echo '<img src="' . esc_url($photo['url']) . '" style="max-width: 80px; margin: 5px 5px 0 0; border: 1px solid #ccc;" />';
            }
        }
        echo '</div>';
    }
}
add_action('woocommerce_after_order_itemmeta', 'show_uploaded_photos_in_admin', 10, 3);

// archive photos at the order
function generate_zip_for_order($order_id) {
    if (!$order_id) return;

    $order = wc_get_order($order_id);
    if (!$order) return;

    if ($order->get_meta('magnet_zip_url')) return;

    $upload_dir = wp_upload_dir();
    $zip_dir = $upload_dir['basedir'] . "/order_zips";
    $zip_path = "{$zip_dir}/order-{$order_id}.zip";

    if (!file_exists($zip_dir)) {
        wp_mkdir_p($zip_dir);
    }

    $zip = new ZipArchive();
    if ($zip->open($zip_path, ZipArchive::CREATE) !== true) return;

    $files_added = 0;

    foreach ($order->get_items() as $item_id => $item) {
        $photos = wc_get_order_item_meta($item_id, 'magnet_photos');
        if (!is_array($photos)) continue;

        foreach ($photos as $photo) {
            if (!isset($photo['url'])) continue;

            $photo_contents = @file_get_contents($photo['url']);
            if ($photo_contents === false) continue;

            $file_name = basename(parse_url($photo['url'], PHP_URL_PATH));
            $zip->addFromString("{$item_id}_{$file_name}", $photo_contents);
            $files_added++;
        }
    }

    $zip->close();

    if ($files_added > 0) {
        $zip_url = $upload_dir['baseurl'] . "/order_zips/order-{$order_id}.zip";
        $order->update_meta_data('magnet_zip_url', esc_url_raw($zip_url));
        $order->save();
    } else {
        @unlink($zip_path); // Удаляем пустой ZIP
    }
}
add_action('woocommerce_order_status_changed', 'generate_zip_for_order', 20);

// generation pdf
add_action('init', function () {
    if (isset($_GET['generate_order_pdf_tcpdf'])) {
        $order_id = intval($_GET['generate_order_pdf_tcpdf']);
        if (!$order_id) wp_die('Order ID missing.');

        $order = wc_get_order($order_id);
        if (!$order) wp_die('Order not found.');

        require_once get_template_directory() . '/tcpdf/tcpdf.php';

        // Получаем фото и определяем bulk количество
        $all_photos = [];
        $bulk_quantity = 0; // 0 - не bulk

        foreach ($order->get_items() as $item_id => $item) {
            $product = $item->get_product();
            if (!$product) continue;

            $categories = wp_get_post_terms($product->get_id(), 'product_cat', ['fields' => 'slugs']);
            $item_photos = wc_get_order_item_meta($item_id, 'magnet_photos');

            // Проверяем, bulk ли товар
            if (in_array('bulk', $categories)) {
                // Количество в заказе для этого товара
                $bulk_quantity = intval($item->get_quantity()); // предполагаем 50 или 100

                // Если есть фото — берем первое
                if (is_array($item_photos) && !empty($item_photos)) {
                    $photo_url = esc_url($item_photos[0]['url'] ?? '');
                    if ($photo_url) {
                        // Дублируем фото bulk_quantity раз
                        for ($i = 0; $i < $bulk_quantity; $i++) {
                            $all_photos[] = $photo_url;
                        }
                    }
                }
            } else {
                // Для обычного товара добавляем все фото как есть
                if (is_array($item_photos)) {
                    foreach ($item_photos as $photo) {
                        if (!empty($photo['url'])) $all_photos[] = esc_url($photo['url']);
                    }
                }
            }
        }

        if (empty($all_photos)) wp_die('No photos found in order.');

        $photo_pages = array_chunk($all_photos, 9); // по 9 фото на страницу

        // Инициализация TCPDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetFont('dejavusans', '', 6);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(false, 0);

        // Настройки страницы
        $cellWidth = 210 / 3;
        $cellHeight = 297 / 3;
        $photoSize = 50.8;

        $style = [
            'dash' => '1,2',
            'color' => [150, 150, 150],
            'width' => 0.1,
        ];

        $x1 = 70;
        $x2 = 140;
        $top = 5;
        $bottom = 297 - 5;

        foreach ($photo_pages as $photos) {
            $pdf->AddPage();
        
            // Страница и фото
            $pageWidth = 210;
            $pageHeight = 297;
            $photoSize = 50.8;
            $rows = 3;
            $cols = 3;
        
            // Расчёт размеров
            $cellWidth = $pageWidth / $cols;
            $spacingY = ($pageHeight - ($rows * $photoSize)) / ($rows + 1); // одинаковые отступы сверху, снизу и между
        
            // Стиль линий
            $style = [
                'dash' => '1,2',
                'color' => [150, 150, 150],
                'width' => 0.1,
            ];
            $x1 = $cellWidth;
            $x2 = $cellWidth * 2;
        
            // Вертикальные пунктирные линии
            $pdf->SetLineStyle($style);
            $pdf->Line($x1, 0, $x1, $pageHeight);
            $pdf->Line($x2, 0, $x2, $pageHeight);
        
            // Фото и надписи
            for ($i = 0; $i < 9; $i++) {
                $col = $i % 3;
                $row = floor($i / 3);
                $x = $col * $cellWidth;
                $y = $spacingY + $row * ($photoSize + $spacingY);
        
                // Фото
                if (isset($photos[$i])) {
                    $photoX = $x + ($cellWidth - $photoSize) / 2;
                    $photoY = $y;

                    $pdf->Image(
                        $photos[$i],
                        $photoX,
                        $photoY,
                        $photoSize,
                        $photoSize,
                        '',
                        '',
                        '',
                        false,
                        300,
                        '',
                        false,
                        false,
                        0,
                        false,
                        false,
                        false
                    );

                    // Линии-указатели по углам (внешние под углом 45°)
                    // $cornerLength = 20;

                    // // Верхний левый угол
                    // $pdf->Line(
                    //     $photoX, $photoY,
                    //     $photoX + $cornerLength, $photoY - $cornerLength
                    // );

                    // // Верхний правый угол
                    // $pdf->Line(
                    //     $photoX + $photoSize, $photoY,
                    //     $photoX + $photoSize - $cornerLength, $photoY - $cornerLength
                    // );

                    // // Нижний левый угол
                    // $pdf->Line(
                    //     $photoX, $photoY + $photoSize,
                    //     $photoX + $cornerLength, $photoY + $photoSize + $cornerLength
                    // );

                    // // Нижний правый угол
                    // $pdf->Line(
                    //     $photoX + $photoSize, $photoY + $photoSize,
                    //     $photoX + $photoSize - $cornerLength, $photoY + $photoSize + $cornerLength
                    // );


                    // Горизонтальные линии
                    $lineYTop = $photoY - 10;    // 10 мм вверх от верхнего края фото
                    $lineYBottom = $photoY + $photoSize + 10; // 10 мм вниз от нижнего края

                    $lineStartX = $photoX;
                    $lineEndX = $photoX + $photoSize;

                    $pdf->SetLineStyle($style);
                    $pdf->Line($lineStartX, $lineYTop, $lineEndX, $lineYTop);
                    $pdf->Line($lineStartX, $lineYBottom, $lineEndX, $lineYBottom);
                }
        
                // Верхняя надпись (lonemagnet.com)
                $centerX = $x + $cellWidth / 2;
                $centerYTop = $y - 5;
        
                $pdf->SetTextColor(0, 0, 0);
                $pdf->StartTransform();
                $pdf->Rotate(180, $centerX, $centerYTop);
                $pdf->SetXY($centerX - 10, $centerYTop - 3);
                $pdf->Write(0, 'lonemagnet.com');
                $pdf->StopTransform();
        
                // Нижняя надпись (#order_id)
                $centerYBottom = $y + $photoSize + 4;
        
                $pdf->SetTextColor(150, 150, 150);
                $pdf->StartTransform();
                $pdf->Rotate(180, $centerX, $centerYBottom);
                $pdf->SetXY($centerX - 5, $centerYBottom);
                $pdf->Write(0, '#' . $order_id);
                $pdf->StopTransform();
            }
        }

        $pdf->Output("order-{$order_id}.pdf", 'I');
        exit;
    }
});

// btns archive and print at the order
add_action('woocommerce_admin_order_data_after_order_details', function($order) {
    $order_id = $order->get_id();
    $pdf_url = site_url("?generate_order_pdf_tcpdf={$order_id}");
    $zip_url = $order->get_meta('magnet_zip_url');

    echo '<div class="form-field form-field-wide" style="margin-top:10px;">';
    echo '<div style="display:block; margin-bottom:5px;">Photos:</div>';
    echo '<div style="display: flex; gap: 10px;">';

    if ($zip_url) {
        echo '<a class="button" target="_blank" href="' . esc_url($zip_url) . '">🗃️ Download ZIP</a>';
    }

    echo '<a class="button" target="_blank" href="' . esc_url($pdf_url) . '">🖨️ Print</a>';

    echo '</div>';
    echo '</div>';
});

// notification about new order to telegram
function send_telegram_order_notification($order_id) {
    $order = wc_get_order($order_id);

    if (!$order) {
        return;
    }

    $token = TELEGRAM_BOT_TOKEN;
    $chat_id = TELEGRAM_CHAT_ID; 

    $admin_url = admin_url("post.php?post={$order_id}&action=edit");
    $pdf_url = site_url("?generate_order_pdf_tcpdf={$order_id}");

    $message = "🛒 <b> New Order #{$order_id}</b>\n\n";
    $message .= "Customer: " . $order->get_billing_first_name() . " " . $order->get_billing_last_name() . "\n";
    $message .= "Shipping Address: " . wp_strip_all_tags($order->get_formatted_shipping_address()) . "\n";
    $message .= "Email: " . $order->get_billing_email() . "\n";
    $message .= "Items:\n";

    foreach ($order->get_items() as $item) {
        $product_name = $item->get_name();
        $qty = $item->get_quantity();
        $message .= "- {$product_name} x{$qty}\n";
    }

    $message .= "\n💰 Total: " . wp_strip_all_tags($order->get_formatted_order_total());

    $buttons = [];

    if ($pdf_url) {
        $buttons[] = [
            ['text' => '🖨️ Print PDF', 'url' => $pdf_url],
        ];
    }

    if ($admin_url) {
        $buttons[] = [['text' => '🛠️ View in Admin', 'url' => $admin_url]];
    }

    $url = "https://api.telegram.org/bot{$token}/sendMessage";

    $args = array(
        'body' => json_encode([
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'HTML',
            'reply_markup' => [
                'inline_keyboard' => $buttons
            ]
        ]),
        'headers' => array(
            'Content-Type' => 'application/json',
        ),
        'timeout' => 15,
    );

    $response = wp_remote_post($url, $args);

    if (is_wp_error($response)) {
        error_log('[Telegram Error] ' . $response->get_error_message());
    } else {
        $body = wp_remote_retrieve_body($response);
        error_log('[Telegram Response] ' . $body);
    }
}
add_action('woocommerce_new_order', 'send_telegram_order_notification', 10, 1);

// turn off new cart woo
add_filter( 'woocommerce_use_block_template_cart', '__return_false' );

// replace thumbnail product at the cart
function replace_cart_thumbnail_with_uploaded_photos($thumbnail, $cart_item, $cart_item_key) {
    if (!empty($cart_item['magnet_photos'])) {
        $photos = $cart_item['magnet_photos'];
        $count = count($photos);

        if ($count === 1) {
            $photo = reset($photos);
            return '<div class="custom-thumbnail-grid" style="display: grid; grid-template-columns: 1fr; grid-template-rows: 1fr; gap: 2px; overflow: hidden;">
                <img src="' . esc_url($photo['url']) . '" style="width: 100%; height: 100%; object-fit: cover;" />
            </div>';
        } else {
            $photos = array_slice($photos, 0, 9);
            $grid = '<div class="custom-thumbnail-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2px; overflow: hidden;">';
            foreach ($photos as $photo) {
                $grid .= '<img src="' . esc_url($photo['url']) . '" style="width: 100%; height: auto; object-fit: cover;" />';
            }
            $grid .= '</div>';
            return $grid;
        }
    }
    return $thumbnail;
}
add_filter('woocommerce_cart_item_thumbnail', 'replace_cart_thumbnail_with_uploaded_photos', 10, 3);

// save photos at the browser session
function restore_uploaded_photos_from_session($cart_item, $values) {
    if (isset($values['magnet_photos'])) {
        $cart_item['magnet_photos'] = $values['magnet_photos'];
    }
    return $cart_item;
}
add_filter('woocommerce_get_cart_item_from_session', 'restore_uploaded_photos_from_session', 10, 2);

// turn off woo standart notifications
function tb_delete_remove_product_notice(){
	$notices = WC()->session->get( 'wc_notices', array() );
	if(isset($notices['success'])){
		for($i = 0; $i < count($notices['success']); $i++){
			if (strpos($notices['success'][$i], __('removed','woocommerce')) !== false) {
				array_splice($notices['success'],$i,1);
			}
		}
		WC()->session->set( 'wc_notices', $notices['success'] );
	}
}

add_filter( 'woocommerce_add_message', 'remove_cart_updated_message' );
function remove_cart_updated_message( $message ) {
    if ( strpos( $message, 'Cart updated.' ) !== false ) {
        return '';
    }
    return $message;
}

add_filter( 'wc_add_to_cart_message_html', '__return_false' );

add_action( 'woocommerce_before_shop_loop', 'tb_delete_remove_product_notice', 5 );
add_action( 'woocommerce_shortcode_before_product_cat_loop', 'tb_delete_remove_product_notice', 5 );
add_action( 'woocommerce_before_single_product', 'tb_delete_remove_product_notice', 5 );

remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );
remove_action( 'woocommerce_before_cart', 'woocommerce_output_all_notices', 10 );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10 );

add_filter( 'woocommerce_cart_item_removed_notice_type', '__return_false' );

// remove  tag p in the cf7
add_filter('wpcf7_autop_or_not', '__return_false');


// zip code optional
add_filter( 'woocommerce_checkout_fields', function( $fields ) {
    $fields['billing']['billing_postcode']['required'] = false;
    return $fields;
});

// style for stripe at the checkout page
add_filter( 'wc_stripe_upe_params', function ( $stripe_params ) {
	$stripe_params['blocksAppearance'] = (object) [ 'theme' => 'light' ];
	$stripe_params['appearance'] = (object) [ 'theme' => 'flat'	];
	
	return $stripe_params;
} );

// allow all formats images
function add_custom_upload_mimes($mimes) {
    $mimes['heic'] = 'image/heic';
    $mimes['heif'] = 'image/heif';
    return $mimes;
}
add_filter('upload_mimes', 'add_custom_upload_mimes');

// fb pixel
add_action('wp_head', function () {
  ?>
  <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1090749109868357');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=1090749109868357&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->
  <?php
});

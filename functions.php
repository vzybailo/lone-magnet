<?php
function lone_enqueue_styles() {
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

    wp_enqueue_style('glightbox', 'https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css');
    wp_enqueue_script('glightbox', 'https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js', [], null, true);
    wp_enqueue_script( 'script', get_template_directory_uri() . '/assets/js/scripts.js');

    wp_enqueue_script('mytheme-gallery', get_template_directory_uri() . '/assets/js/gallery.js', ['glightbox'], null, true);

    wp_add_inline_script('mytheme-gallery', "
        document.addEventListener('DOMContentLoaded', function () {
        GLightbox({ selector: '.glightbox' });
        });
    ");
}
add_action('wp_enqueue_scripts', 'lone_enqueue_styles');

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

// add upload button
function render_custom_upload_button() {
    global $product;
    $product_id = $product->get_id();

    echo '<button type="button" class="bg-teal-500 text-white hover:bg-teal-600 w-full p-2 transition-colors duration-300" id="custom-photo-upload">Add photos</button>';
    echo '<div id="custom-photo-modal-root" data-product-id="' . esc_attr($product_id) . '"></div>';
    echo '<div class="lone-alert text-sm"></div>';
}
add_action('woocommerce_before_add_to_cart_button', 'render_custom_upload_button');

function enqueue_custom_photo_upload_script() {
    wp_enqueue_script(
        'photo-upload',
        get_template_directory_uri() . '/assets/js/photo-upload.js',
        [],
        null,
        true
    );

    wp_localize_script('photo-upload', 'wpApiSettings', [
        'nonce' => wp_create_nonce('wp_rest')
    ]);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_photo_upload_script');


// custom classes for price
function custom_woocommerce_price_html( $price, $product ) {
    if ( $product->is_on_sale() ) {
        return '<span class="lone-regular-price line-through text-grey mr-2">' . wc_price( $product->get_regular_price() ) . '</span>' .
               '<span class="lone-sale-price text-red font-bold">' . wc_price( $product->get_sale_price() ) . '</span>';
    }

    return '<span class="lone-regular-price text-black font-semibold">' . wc_price( $product->get_regular_price() ) . '</span>';
}
add_filter( 'woocommerce_get_price_html', 'custom_woocommerce_price_html', 100, 2 );

function lone_save_summ() {
    echo '<div class="lone-save-summ text-green text-sm"></div>';
}
add_action('woocommerce_template_single_price', 'lone_save_summ');

// настройка загрузки фото
add_action('wp_ajax_upload_user_photo', 'upload_user_photo');
add_action('wp_ajax_nopriv_upload_user_photo', 'upload_user_photo');

function upload_user_photo() {
    if (!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }

    $file = $_FILES['photo'];
    $upload_overrides = ['test_form' => false];

    $movefile = wp_handle_upload($file, $upload_overrides);

    if ($movefile && !isset($movefile['error'])) {
        wp_send_json_success([
            'url' => $movefile['url'],
            'filename' => basename($movefile['file']),
        ]);
    } else {
        wp_send_json_error(['message' => $movefile['error'] ?? 'Upload failed']);
    }

    wp_die();
}

// Сохраняем фото в объект корзины
add_filter('woocommerce_add_cart_item_data', 'save_uploaded_photos_to_cart', 10, 3);
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

add_filter('woocommerce_get_item_data', 'display_photos_in_cart', 10, 2);
function display_photos_in_cart($item_data, $cart_item) {
    if (isset($cart_item['magnet_photos'])) {
        $photos = $cart_item['magnet_photos'];
        $item_data[] = array(
            'key' => __('Uploaded Photos', 'your-textdomain'),
            'value' => sprintf('%d photo(s) uploaded', count($photos))
        );
    }
    return $item_data;
}

add_action('woocommerce_checkout_create_order_line_item', 'save_photos_to_order', 10, 4);
function save_photos_to_order($item, $cart_item_key, $values, $order) {
    if (isset($values['magnet_photos'])) {
        $item->add_meta_data('magnet_photos', $values['magnet_photos'], true);
    }
}

add_action('woocommerce_after_order_itemmeta', 'show_uploaded_photos_in_admin', 10, 3);

// показываем миниатюры загруженных фото в заказе в админке
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

// формируем архив фото для скачивания в заказе
add_action('woocommerce_thankyou', 'generate_zip_for_order', 20);
function generate_zip_for_order($order_id) {
    if (!$order_id) return;

    $order = wc_get_order($order_id);

    // Пропустить, если ZIP уже создан
    if ($order->get_meta('magnet_zip_url')) {
        return;
    }

    $zip = new ZipArchive();
    $upload_dir = wp_upload_dir();
    $zip_path = $upload_dir['basedir'] . "/order_zips/order-{$order_id}.zip";

    if (!file_exists(dirname($zip_path))) {
        wp_mkdir_p(dirname($zip_path));
    }

    if ($zip->open($zip_path, ZipArchive::CREATE) === TRUE) {
        foreach ($order->get_items() as $item_id => $item) {
            $photos = wc_get_order_item_meta($item_id, 'magnet_photos');
            if (is_array($photos)) {
                foreach ($photos as $photo) {
                    $photo_url = $photo['url'];
                    if ($photo_contents = @file_get_contents($photo_url)) {
                        $file_name = basename(parse_url($photo_url, PHP_URL_PATH));
                        $zip->addFromString("{$item_id}_{$file_name}", $photo_contents);
                    }
                }
            }
        }
        $zip->close();

        $zip_url = $upload_dir['baseurl'] . "/order_zips/order-{$order_id}.zip";
        $order->update_meta_data('magnet_zip_url', esc_url_raw($zip_url));
        $order->save();
    }
}

// добавляем к заказу кнопку для скачивания фото 
add_action('woocommerce_admin_order_data_after_order_details', function($order) {
    $zip_url = $order->get_meta('magnet_zip_url');
    if ($zip_url) {
        echo '
            <div class="form-field form-field-wide">
                <div style="color: #777; padding: 0 0 3px;">Photos for printing:</div>
                <a href="' . esc_url($zip_url) . '" target="_blank" class="button">Download ZIP</a>
            </div>';
    }
});


function send_telegram_order_notification($order_id) {
    $order = wc_get_order($order_id);
    if (!$order) {
        error_log("❌ Order not found for ID: $order_id");
        return;
    }

    $token = TELEGRAM_BOT_TOKEN;
    $chat_id = TELEGRAM_CHAT_ID; 

    $message = "🛒 Новый заказ №{$order_id}\n";
    $message .= "Клиент: " . $order->get_billing_first_name() . " " . $order->get_billing_last_name() . "\n";
    $message .= "Телефон: " . $order->get_billing_phone() . "\n";
    $message .= "Email: " . $order->get_billing_email() . "\n";
    $message .= "Товары:\n";

    foreach ($order->get_items() as $item) {
        $product_name = $item->get_name();
        $qty = $item->get_quantity();
        $message .= "- {$product_name} x{$qty}\n";
    }

    $message .= "Итого: " . $order->get_formatted_order_total();

    $url = "https://api.telegram.org/bot{$token}/sendMessage";

    $args = array(
        'body' => json_encode([
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'HTML',
        ]),
        'headers' => array(
            'Content-Type' => 'application/json',
        ),
        'timeout' => 15,
    );

    // Логируем отправку
    error_log("📤 Sending Telegram message: $message");

    $response = wp_remote_post($url, $args);

    if (is_wp_error($response)) {
        error_log('❌ Telegram error: ' . $response->get_error_message());
    } else {
        error_log('✅ Telegram sent successfully: ' . wp_remote_retrieve_body($response));
    }
}
add_action('woocommerce_new_order', 'send_telegram_order_notification', 10, 1);

// turn of new cart woo
add_filter( 'woocommerce_use_block_template_cart', '__return_false' );


// change  product thumnail in the cart
add_filter('woocommerce_add_cart_item_data', 'save_uploaded_photos_to_cart_item', 10, 3);
function save_uploaded_photos_to_cart_item($cart_item_data, $product_id, $variation_id) {
    if (!empty($_POST['magnet_photos_data'])) {
        $photos = json_decode(stripslashes($_POST['magnet_photos_data']), true);
        if ($photos && is_array($photos)) {
            $cart_item_data['magnet_photos'] = $photos;
            $cart_item_data['unique_key'] = md5(microtime().rand());
        }
    }
    return $cart_item_data;
}

add_filter('woocommerce_cart_item_thumbnail', 'replace_cart_thumbnail_with_uploaded_photos', 10, 3);
function replace_cart_thumbnail_with_uploaded_photos($thumbnail, $cart_item, $cart_item_key) {
    if (!empty($cart_item['magnet_photos'])) {
        $photos = array_slice($cart_item['magnet_photos'], 0, 9);
        $grid = '<div class="custom-thumbnail-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2px; overflow: hidden;">';
        foreach ($photos as $photo) {
            $grid .= '<img src="' . esc_url($photo['url']) . '" style="width: 100%; height: auto; object-fit: cover;" />';
        }
        $grid .= '</div>';
        return $grid;
    }
    return $thumbnail;
}

add_filter('woocommerce_get_cart_item_from_session', 'restore_uploaded_photos_from_session', 10, 2);
function restore_uploaded_photos_from_session($cart_item, $values) {
    if (isset($values['magnet_photos'])) {
        $cart_item['magnet_photos'] = $values['magnet_photos'];
    }
    return $cart_item;
}

add_action('woocommerce_add_order_item_meta', function($item_id, $values) {
    if (!empty($values['magnet_photos'])) {
        wc_add_order_item_meta($item_id, 'magnet_photos', $values['magnet_photos']);
    }
}, 10, 2);


remove_action( 'woocommerce_before_single_product', 'wc_print_notices', 10 );
remove_action( 'woocommerce_before_cart', 'wc_print_notices', 10 );
remove_action( 'woocommerce_before_checkout_form', 'wc_print_notices', 10 );
remove_action( 'woocommerce_account_content', 'wc_print_notices', 10 );



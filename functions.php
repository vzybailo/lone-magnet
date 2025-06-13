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
add_action('woocommerce_order_status_changed', 'generate_zip_for_order', 20);
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

// Генерирование pdf для вывода на печать
add_action('init', function () {
    if (isset($_GET['generate_order_pdf_tcpdf'])) {
        $order_id = intval($_GET['generate_order_pdf_tcpdf']);
        if (!$order_id) {
            wp_die('Order ID missing.');
        }

        $order = wc_get_order($order_id);
        if (!$order) {
            wp_die('Order not found.');
        }

        require_once get_template_directory() . '/tcpdf/tcpdf.php';

        // Сбор фото
        $photos = [];
        foreach ($order->get_items() as $item_id => $item) {
            $item_photos = wc_get_order_item_meta($item_id, 'magnet_photos');
            if (is_array($item_photos)) {
                foreach ($item_photos as $photo) {
                    if (!empty($photo['url'])) {
                        $photos[] = esc_url($photo['url']);
                    }
                }
            }
        }

        $photos = array_slice($photos, 0, 9);

        // 👉 Загрузка HTML-шаблона
        ob_start();
        include get_template_directory() . '/pdf-template/template.php'; 
        $html = ob_get_clean();

        // Генерация PDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->AddPage();

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output("order-{$order_id}.pdf", 'I');
        exit;
    }
});

// кнопки архива и печати в заказе
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

add_action('woocommerce_new_order_item', function($item_id, $values) {
    if (!empty($values['magnet_photos'])) {
        wc_add_order_item_meta($item_id, 'magnet_photos', $values['magnet_photos']);
    }
}, 10, 2);

// turn off woo notifications
remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );
remove_action( 'woocommerce_before_cart', 'woocommerce_output_all_notices', 10 );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10 );

add_filter( 'woocommerce_cart_item_removed_notice_type', '__return_false' );

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

add_action( 'woocommerce_before_shop_loop', 'tb_delete_remove_product_notice', 5 );
add_action( 'woocommerce_shortcode_before_product_cat_loop', 'tb_delete_remove_product_notice', 5 );
add_action( 'woocommerce_before_single_product', 'tb_delete_remove_product_notice', 5 );


// remove  tag p in the cf7
add_filter('wpcf7_autop_or_not', '__return_false');


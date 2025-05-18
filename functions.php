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

// register new Gutenberg blocks
function magnets_shop_register_blocks() {
    register_block_type(__DIR__ . '/blocks/cta');
    register_block_type(__DIR__ . '/blocks/photo-upload');
}
add_action('init', 'magnets_shop_register_blocks');


function render_custom_upload_button() {
    echo '<button class="bg-teal-500 text-white hover:bg-teal-600 w-full p-2" id="custom-photo-upload">Add photos</button>';
}
add_action('woocommerce_before_add_to_cart_button', 'render_custom_upload_button');


add_action('wp_enqueue_scripts', 'enqueue_photo_upload_script');
function enqueue_photo_upload_script() {
    if (is_product()) {
        wp_enqueue_script(
            'photo-upload',
            get_template_directory_uri() . '/blocks/photo-upload/build/index.js',
            [ 'react', 'react-dom' ],
            null,
            true
        );

        // Если нужны стили
        wp_enqueue_style(
            'photo-upload-style',
            get_template_directory_uri() . '/blocks/photo-upload/style.css'
        );
    }
}

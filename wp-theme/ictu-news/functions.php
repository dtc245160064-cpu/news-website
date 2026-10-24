<?php
function ictu_news_enqueue_styles() {
    wp_enqueue_style(
        'ictu-news-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'ictu_news_enqueue_styles');

<?php

namespace ElementorPostNotFound;

function admin_notice_example_notice()
{
    printf(
        '<div class="notice notice-error is-dismissible"><p>%1$s</p></div>',
        esc_html__('This message will post every time we activate the plugin', 'post-not-found-for-elementor')
    );
}

function admin_notice_minimum_php_version()
{
    $message = sprintf(
        /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
        esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'post-not-found-for-elementor'),
        '<strong>' . esc_html__("Elementor Post Not Found", 'post-not-found-for-elementor') . '</strong>',
        '<strong>' . esc_html__('PHP', 'post-not-found-for-elementor') . '</strong>',
        FP_PLUGIN_MIN_PHP_VERSION
    );

    printf('<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', $message);
}

function admin_notice_wrong_elementor()
{
    $message = sprintf(
        /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
        esc_html__(' The "%1$s" plugin requires "%2$s" version %3$s or greater.', 'post-not-found-for-elementor'),
        '<strong>' . esc_html__("Elementor Post Not Found", 'post-not-found-for-elementor') . '</strong>',
        '<strong>' . esc_html__('Elementor Pro', 'post-not-found-for-elementor') . '</strong>',
        EL_PNF_EL_PRO_REQ_VERSION
    );

    printf('<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', $message);
}
function admin_notice_elementor_missing()
{
    $message = sprintf(
        /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
        esc_html__(' The "%1$s" plugin requires "%2$s" to be installed and activated.', 'post-not-found-for-elementor'),
        '<strong>' . esc_html__("Elementor Post Not Found", 'post-not-found-for-elementor') . '</strong>',
        '<strong>' . esc_html__('Elementor Pro', 'post-not-found-for-elementor') . '</strong>'
    );

    printf('<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', $message);
}

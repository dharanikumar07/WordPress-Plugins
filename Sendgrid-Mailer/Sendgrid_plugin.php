<?php
/*
Plugin Name: Sendgrid_Mailer
Description: This is a plugin to send a mail using SendGrid API.
Version: 1.0.1
Author: Dharanikumar
License: GPLv2 or Later
Text Domain: sendgridPlugin
*/

defined('ABSPATH') ?: exit;

if(!file_exists(plugin_dir_path(__FILE__) . 'vendor/autoload.php')){
    return;
}
require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

// Add SendGrid menu to admin
function customAddMenu() {
    add_menu_page(
        __('SendGrid', 'sendgridPlugin'),
        __('SendGrid', 'sendgridPlugin'),
        'manage_options',
        'sendGrid',
        'customMenuPage',
        'dashicons-email-alt',
        6
    );
}
add_action('admin_menu', 'customAddMenu');

function customMenuPage() {
    include(plugin_dir_path(__FILE__) . 'Frontpage.php');
}

// Enqueue JavaScript
function sendgridEnqueueScripts($slug) {
    if($slug != 'toplevel_page_sendGrid'){
        return;
    }
    wp_enqueue_script('sendgrid-mailer', plugin_dir_url(__FILE__) . 'Script.js', ['jquery'], '1.0.0', true);
    wp_localize_script('sendgrid-mailer', 'sendgrid_ajax', ['ajax_url' => admin_url('admin-ajax.php')]);
}
add_action('admin_enqueue_scripts', 'sendgridEnqueueScripts');

// Handle AJAX Request
function sendgridMailSender() {
    if (!isset($_POST['toEmail']) || !isset($_POST['subject']) || !isset($_POST['content'])) {
        wp_send_json_error(['message' => esc_html__('Required fields are missing.', 'sendgridPlugin')]);
    }

    $toEmail = sanitize_email($_POST['toEmail']);
    $subject = sanitize_text_field($_POST['subject']);
    $content = sanitize_textarea_field($_POST['content']);

    $email = new \SendGrid\Mail\Mail();
    $email->setFrom('your mail', esc_html__('your name', 'sendgridPlugin'));
    $email->setSubject($subject);
    $email->addTo($toEmail);
    $email->addContent('text/plain', $content);

    $sendgrid = new \SendGrid('your api key');

    try {
        $response = $sendgrid->send($email);
        if ($response->statusCode() == 200 || $response->statusCode() == 202 || $response->statusCode() == 250) {
            wp_send_json_success(['message' => esc_html__('Email sent successfully', 'sendgridPlugin')]);
        } else {
            wp_send_json_error(['message' => esc_html__('Error occurred: ', 'sendgridPlugin') . $response->statusCode()]);
        }
    } catch (Exception $e) {
        wp_send_json_error(['message' => esc_html__('Something went wrong: ', 'sendgridPlugin') . $e->getMessage()]);
    }
}
add_action('wp_ajax_sendgrid_send_mail', 'sendgridMailSender');

<?php

/*
 * Plugin Name: Auto Apply Coupon
 * Plugin URI: http://localhost:8085
 * Description: This plugin automatically generates and applies a custom coupon code in the cart.
 * Version: 1.0.1
 * Author: Dharanikumar
 * License: GPLv2 or Later
 * Text Domain: aacPlugin
 */

defined('ABSPATH') or die();

defined('AAC_PATH') or define('AAC_PATH', plugin_dir_path(__FILE__));
defined('AAC_URL') or define('AAC_URL', plugin_dir_url(__FILE__));

if (!file_exists(AAC_PATH . 'vendor/autoload.php')) {
    return;
}
require_once AAC_PATH . 'vendor/autoload.php';

if (class_exists('\AAC\App\Router')) {
    \AAC\App\Router::init();
}

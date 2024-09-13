<?php

/*
 * Plugin Name: Rest-Api
 * Plugin URI: http://localhost:8085
 * Description: This plugin is used for display our products through url.
 * Version: 1.0.1
 * Author: Dharanikumar
 * License: GPLv2 or Later
 * Text Domain: rpPlugin
 */

defined('ABSPATH') or die();

defined('RA_PATH') or define('RA_PATH', plugin_dir_path(__FILE__));

if (!file_exists(RA_PATH . 'vendor/autoload.php')) {
    return;
}
require_once RA_PATH . 'vendor/autoload.php';

if (class_exists('\RADP\App\Router')) {
    \RADP\App\Router::init();
}
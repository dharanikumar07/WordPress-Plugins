<?php
/*
Plugin Name: Frequently bougth together
Plugin URI: http://localhost:8085
Description: This is a plugin to show a frequent products.
Version: 1.0.0
Author: Dharanikumar
License: GPLv2 or Later
Text Domain: fbtPlugin
*/

defined("ABSPATH") or die();
defined("FBT_PATH") or define("FBT_PATH",plugin_dir_path(__FILE__));
defined("FBT_URL") or define("FBT_URL",plugin_dir_url(__FILE__));
if(!file_exists( FBT_PATH . 'vendor/autoload.php' )) {
    return;
}
require_once FBT_PATH . 'vendor/autoload.php';

if(class_exists('\Fbt\App\Router')){
    $router=new \Fbt\App\Router();
    $router->init();
}


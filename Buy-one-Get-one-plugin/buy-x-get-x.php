<?php

/*
 * Plugin Name: Buy-x Get-x
 * Plugin UrI: http://localhost:8085
 * Description: This plugin is used for buy one get one offer.
 * Version: 1.0.1
 * Author: Dharanikumar
 * License: GPLv2 or Later
 * Text Domain: BxgxPlugin
 */

defined("ABSPATH") or die();

defined('BXGX_PATH') or define('BXGX_PATH', plugin_dir_path(__FILE__));
defined('BXGX_URL') or define('BXGX_URL', plugin_dir_url(__FILE__));

if(!file_exists(BXGX_PATH.'vendor/autoload.php'))
{
    return;
}
require_once BXGX_PATH.'vendor/autoload.php';


if(class_exists('\BXGX\App\Router')){
    $router=new \BXGX\App\Router();
    $router->init();
}
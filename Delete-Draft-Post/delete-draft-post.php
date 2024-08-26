<?php

/*

* Plugin Name: Delete Draft Posts

* Description: Automatically deletes draft posts every day at 10 am.

* Version: 1.0.0

* Author: Dharanikumar

*/
require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

require_once plugin_dir_path(__FILE__) . 'App/Controller/DeleteDraftController.php';

require_once plugin_dir_path(__FILE__) . 'App/Model/DeleteDraftModel.php';

require_once plugin_dir_path(__FILE__) . 'App/Router.php';
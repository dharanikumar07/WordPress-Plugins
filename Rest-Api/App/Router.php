<?php

namespace RADP\App;
use  RADP\App\Controller\MainController;
class Router
{
    public static function init()
    {
        add_action('rest_api_init', [MainController::class, 'registerRoutes']);
    }
}
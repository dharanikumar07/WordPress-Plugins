<?php

namespace DeleteDraftPosts;

use DeleteDraftPosts\Controller\DeleteDraftController;
class Router
{
    public static function init()
    {
        add_action('init', [DeleteDraftController::class, 'scheduleDraftDeletion']);
        add_action('admin_menu', [DeleteDraftController::class, 'deleteDraftPostMenu']);
        add_action('delete_draft_posts_daily', [DeleteDraftController::class, 'deleteDraftPosts']);

    }

}
Router::init();

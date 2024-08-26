<?php

namespace DeleteDraftPosts\Controller;

use DeleteDraftPosts\Model\DeleteDraftModel;

class DeleteDraftController
{
    public static function deleteDraftPosts()
    {
        DeleteDraftModel::deleteAllDrafts();
    }

    public static function scheduleDraftDeletion()
    {
        if (!wp_next_scheduled('delete_draft_posts_daily')) {
            $current_time = current_time('timestamp');

            $next_10am = strtotime('10:00:00', $current_time);

            $timezone = new \DateTimeZone(wp_timezone_string());
            $log=wc_get_logger();
            $current_time = new \DateTime('now', $timezone);
            $log->add('wlr',json_encode($current_time));
            $next_10am = new \DateTime('10:00:00', $timezone);

            if ($next_10am <= $current_time) {

                $next_10am->modify('+1 day');
            }

            wp_schedule_event($next_10am->getTimestamp(), 'daily', 'delete_draft_posts_daily');
        }
    }

    public static function deleteDraftPostMenu()

    {

        add_menu_page(
            'Delete Draft Posts',
            'Delete Draft Posts',
            'manage_options',
            'delete-draft-posts',
            [self::class, 'DeleteDraftPostsPage'],
            'dashicons-trash',
            6
        );
    }

    public static function deleteDraftPostsPage()
    {
        $current_time = current_time('mysql');
        $current_timestamp = current_time('timestamp');
        $next_10am = strtotime('10:00:00', $current_timestamp);

        if ($next_10am <= $current_time) {
            $next_10am = strtotime('tomorrow 10:00:00', $current_timestamp);
        }

        $nextSchedule= date('Y-m-d H:i:s', $next_10am);

        require plugin_dir_path(__FILE__) . '../View/DeleteDraftView.php';
    }
}

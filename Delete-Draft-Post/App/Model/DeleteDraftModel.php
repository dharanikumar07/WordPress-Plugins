<?php
namespace DeleteDraftPosts\Model;

class DeleteDraftModel
{
    public static function deleteAllDrafts()
    {
        global $wpdb;
        $draftPosts = $wpdb->get_results("SELECT ID FROM {$wpdb->posts} WHERE post_status = 'draft'");

        $deletedDrafts =[];

        foreach ( $draftPosts as $post) {
            wp_delete_post($post->ID, false);
            $deletedDrafts[] = $post->post_title;
        }
    }
}

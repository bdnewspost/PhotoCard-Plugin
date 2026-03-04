<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

add_filter('manage_posts_columns', 'pcd_add_photocard_column');

function pcd_add_photocard_column($columns) {
    $new_columns = array();

    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;

        // Add photocard column after title
        if ($key === 'title') {
            $new_columns['photocard_download'] = 'ফটোকার্ড';
        }
    }

    return $new_columns;
}

add_action('manage_posts_custom_column', 'pcd_display_photocard_column', 10, 2);

function pcd_display_photocard_column($column, $post_id) {
    if ($column === 'photocard_download') {
        if (has_post_thumbnail($post_id)) {
            $editor_url = add_query_arg(array(
                'pcd_editor' => '1',
                'post_id' => $post_id
            ), home_url());

            echo '<a href="' . esc_url($editor_url) . '" class="button button-small" target="_blank" style="background: #22c55e; color: white; border-color: #22c55e;">
                <span class="dashicons dashicons-download" style="vertical-align: middle; margin-top: 3px;"></span> ডাউনলোড
            </a>';
        } else {
            echo '<span style="color: #999;">—</span>';
        }
    }
}

add_filter('manage_edit-post_sortable_columns', 'pcd_make_photocard_column_sortable');

function pcd_make_photocard_column_sortable($columns) {
    $columns['photocard_download'] = 'photocard_download';
    return $columns;
}

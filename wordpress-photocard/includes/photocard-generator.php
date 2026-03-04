<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// AJAX handler for generating photocard
add_action('wp_ajax_pcd_generate_photocard', 'pcd_generate_photocard_ajax');
add_action('wp_ajax_nopriv_pcd_generate_photocard', 'pcd_generate_photocard_ajax');

function pcd_generate_photocard_ajax() {
    check_ajax_referer('pcd_nonce', 'nonce');

    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if (!$post_id) {
        wp_send_json_error(array('message' => 'Invalid post ID'));
        return;
    }

    $post = get_post($post_id);
    if (!$post || !has_post_thumbnail($post_id)) {
        wp_send_json_error(array('message' => 'Post not found or no thumbnail'));
        return;
    }

    // FIX: Check permission before generating
    if (!pcd_can_user_download()) {
        wp_send_json_error(array('message' => 'আপনার এই ফটোকার্ড ডাউনলোড করার অনুমতি নেই'));
        return;
    }

    // Return success - actual generation happens on frontend with html2canvas
    wp_send_json_success(array(
        'message' => 'Photocard data ready',
        'post_id' => $post_id,
        'post_title' => get_the_title($post_id)
    ));
}

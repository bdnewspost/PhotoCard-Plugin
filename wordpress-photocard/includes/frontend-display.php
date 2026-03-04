<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Add download button to post content
add_filter('the_content', 'pcd_add_download_button');

function pcd_add_download_button($content) {
    if (!is_single() || !has_post_thumbnail()) {
        return $content;
    }

    if (!pcd_can_user_download()) {
        return $content;
    }

    $options = get_option('pcd_settings');
    $button_position = isset($options['button_position']) ? $options['button_position'] : 'below';
    $button_text = isset($options['button_text']) ? $options['button_text'] : 'ডাউনলোড ফটোকার্ড';

    $button_bg_color = isset($options['download_button_bg_color']) ? $options['download_button_bg_color'] : '#22c55e';
    $button_text_color = isset($options['download_button_text_color']) ? $options['download_button_text_color'] : '#ffffff';

    $post_id = get_the_ID();
    $editor_url = add_query_arg(array(
        'pcd_editor' => '1',
        'post_id' => $post_id
    ), home_url());

    $button_html = '<div class="pcd-download-button-wrapper">
        <a href="' . esc_url($editor_url) . '" class="pcd-download-button" style="background-color: ' . esc_attr($button_bg_color) . '; color: ' . esc_attr($button_text_color) . ';">' . esc_html($button_text) . '</a>
    </div>';

    if ($button_position === 'above') {
        $content = $button_html . $content;
    } else {
        // Find the first image in content and add button after it
        $pattern = '/<img[^>]+>/i';
        if (preg_match($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
            $offset = $matches[0][1] + strlen($matches[0][0]);
            $content = substr_replace($content, $button_html, $offset, 0);
        } else {
            $content = $content . $button_html;
        }
    }

    return $content;
}

function pcd_can_user_download() {
    $options = get_option('pcd_settings');
    $permission = isset($options['download_permission']) ? $options['download_permission'] : 'everyone';

    switch ($permission) {
        case 'everyone':
            return true;

        case 'logged_in':
            return is_user_logged_in();

        case 'editor':
            return current_user_can('edit_posts');

        case 'admin':
            return current_user_can('manage_options');

        default:
            return true;
    }
}

<?php
/**
 * Plugin Name: Photocard Generator
 * Plugin URI: https://hostercube.com
 * Description: এই প্লাগইন যা পোস্টের থাম্বনেইল থেকে ফটোকার্ড ডাউনলোড করার সুবিধা দেয়
 * Version: 1.0.2
 * Author: HosterCube Ltd.
 * Author URI: https://hostercube.com
 * License: GPL v2 or later
 * Text Domain: photocard-downloader
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PCD_VERSION', '1.0.2');
define('PCD_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PCD_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once PCD_PLUGIN_DIR . 'includes/class-license-manager.php';

$license_manager = PCD_License_Manager::get_instance();

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'pcd_add_plugin_action_links');

function pcd_add_plugin_action_links($links) {
    $license_manager = PCD_License_Manager::get_instance();

    if ($license_manager->is_license_valid()) {
        $settings_link = '<a href="' . admin_url('options-general.php?page=photocard-downloader') . '" style="color: #2271b1; font-weight: 600;">' . __('Settings', 'photocard-downloader') . '</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}

require_once PCD_PLUGIN_DIR . 'includes/admin-settings.php';

if ($license_manager->is_license_valid()) {
    require_once PCD_PLUGIN_DIR . 'includes/frontend-display.php';
    require_once PCD_PLUGIN_DIR . 'includes/photocard-generator.php';
    require_once PCD_PLUGIN_DIR . 'includes/photocard-editor.php';
    require_once PCD_PLUGIN_DIR . 'includes/admin-post-column.php';
} else {
    add_action('admin_notices', 'pcd_license_required_notice');

    function pcd_license_required_notice() {
        $screen = get_current_screen();
        if ($screen && $screen->id === 'toplevel_page_photocard-license') {
            return;
        }
        ?>
        <div class="notice notice-error is-dismissible">
            <p>
                <strong>Photocard Generator:</strong>
                আপনার লাইসেন্স সক্রিয় নেই। প্লাগিন ব্যবহার করতে
                <a href="<?php echo admin_url('admin.php?page=photocard-license'); ?>" class="button button-primary" style="margin-left: 10px; vertical-align: middle;">লাইসেন্স সক্রিয় করুন</a>
            </p>
        </div>
        <?php
    }
}

register_activation_hook(__FILE__, 'pcd_activate_plugin');

function pcd_activate_plugin() {
    $default_logo = plugins_url('assets/images/logo.png', __FILE__);
    $default_background = plugins_url('assets/images/border.png', __FILE__);

    $default_options = array(
        'button_position' => 'above',
        'button_text' => 'ডাউনলোড ফটোকার্ড',
        'watermark_text' => '',
        'watermark_logo' => $default_logo,
        'background_image' => $default_background,
        'frame_color' => '#c41e3a',
        'text_color' => '#000000',
        'background_color' => '#ffffff',
        'button_color' => '#22c55e',
        'button_text_color' => '#ffffff',
        'title_text_color' => '#000000',
        'title_background_color' => 'transparent',
        'title_font_family' => 'Noto Sans Bengali',
        'title_border_radius' => 6,
        'download_button_bg_color' => '#22c55e',
        'download_button_text_color' => '#ffffff',
        'download_permission' => 'everyone',
        'settings_access_role' => 'admin',
        'thumbnail_shadow' => 'medium',
        'card_padding' => 0,
        'image_quality' => 4,
        'thumbnail_border_radius_top_left' => 5,
        'thumbnail_border_radius_top_right' => 5,
        'thumbnail_border_radius_bottom_left' => 5,
        'thumbnail_border_radius_bottom_right' => 5,
        'thumbnail_border_width_top' => 3,
        'thumbnail_border_width_right' => 3,
        'thumbnail_border_width_bottom' => 3,
        'thumbnail_border_width_left' => 3,
        'thumbnail_border_color' => '#ffffff',
        'enable_date' => true,
        'enable_logo' => true,
        'show_details_button' => true,
        'details_button_text' => 'বিস্তারিত কমেন্ট',
        'title_top_offset' => 0,
        'details_bottom_offset' => 0,
        'default_font_size' => 42,
        'default_line_height' => 1.3,
        'show_border' => true,
        'enable_gradient' => false,
        'gradient_color_1' => '#ff6b6b',
        'gradient_color_2' => '#4ecdc4',
        'gradient_direction' => 'to right',
        'logo_position' => 'left',
        'photocard_template' => 'classic',
        'photocard_language' => 'bengali',
        'facebook_link' => 'https://facebook.com/hostercube',
        'facebook_text' => 'hostercube',
        'show_facebook' => false,
        'instagram_link' => '',
        'instagram_text' => '',
        'show_instagram' => false,
        'youtube_link' => '',
        'youtube_text' => '',
        'show_youtube' => false,
        'linkedin_link' => '',
        'linkedin_text' => '',
        'show_linkedin' => false,
        'website_link' => 'https://hostercube.com',
        'website_text' => 'hostercube.com',
        'show_website' => false,
        'ad_image_thumbnail_top' => '',
        'enable_ad_thumbnail_top' => false,
        'ad_image_thumbnail_bottom' => '',
        'enable_ad_thumbnail_bottom' => false,
        'ad_image_thumbnail_left' => '',
        'enable_ad_thumbnail_left' => false,
        'ad_image_thumbnail_right' => '',
        'enable_ad_thumbnail_right' => false,
        'ad_image_social_bottom' => '',
        'enable_ad_social_bottom' => false,
        'custom_css' => '',
        'custom_bg_image' => '',
        'domain_text' => '',
        'show_domain' => true,
    );

    if (!get_option('pcd_settings')) {
        add_option('pcd_settings', $default_options);
    }
}

register_deactivation_hook(__FILE__, 'pcd_deactivate_plugin');

function pcd_deactivate_plugin() {
    // Cleanup if needed
}

add_action('plugins_loaded', 'pcd_init_plugin');

function pcd_init_plugin() {
    load_plugin_textdomain('photocard-downloader', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

add_action('admin_enqueue_scripts', 'pcd_admin_enqueue_scripts');

function pcd_admin_enqueue_scripts($hook) {
    if ($hook !== 'settings_page_photocard-downloader') {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');

    wp_enqueue_style('pcd-admin-style', PCD_PLUGIN_URL . 'assets/css/admin-style.css', array(), PCD_VERSION);
    wp_enqueue_script('pcd-admin-script', PCD_PLUGIN_URL . 'assets/js/admin-script.js', array('jquery', 'wp-color-picker'), PCD_VERSION, true);
}

add_action('wp_enqueue_scripts', 'pcd_frontend_enqueue_scripts');

function pcd_frontend_enqueue_scripts() {
    if (is_single() || (isset($_GET['pcd_editor']) && $_GET['pcd_editor'] == '1')) {
        wp_enqueue_style('pcd-google-fonts', 'https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap', array(), PCD_VERSION);

        wp_enqueue_style('pcd-frontend-style', PCD_PLUGIN_URL . 'assets/css/frontend-style.css', array(), PCD_VERSION);
        wp_enqueue_script('html2canvas', 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js', array(), '1.4.1', true);
        wp_enqueue_script('pcd-frontend-script', PCD_PLUGIN_URL . 'assets/js/frontend-script.js', array('jquery', 'html2canvas'), PCD_VERSION, true);

        wp_localize_script('pcd-frontend-script', 'pcdData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('pcd_nonce')
        ));
    }
}

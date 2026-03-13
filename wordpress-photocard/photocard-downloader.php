<?php
/**
 * Plugin Name: Photocard Generator
 * Plugin URI: https://hostercube.com
 * Description: এই প্লাগইন যা পোস্টের থাম্বনেইল থেকে ফটোকার্ড ডাউনলোড করার সুবিধা দেয়
 * Version: 1.2.1
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
define('PCD_VERSION', '1.2.1');
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

    $default_options = array(
        'button_position' => 'above',
        'button_text' => 'ডাউনলোড ফটোকার্ড',
        'download_permission' => 'everyone',
        'settings_access_role' => 'admin',
        'download_button_bg_color' => '#22c55e',
        'download_button_text_color' => '#ffffff',
        'photocard_template' => 'news24',
        'photocard_language' => 'bengali',
        'watermark_logo' => $default_logo,
        'logo_position' => 'left',
        'enable_date' => true,
        'show_weekday' => true,
        'date_position' => 'right',
        'enable_logo' => true,
        'show_details_button' => true,
        'details_button_text' => 'বিস্তারিত কমেন্টে',
        'title_top_offset' => 0,
        'details_bottom_offset' => 0,
        'title_font_family' => 'Noto Sans Bengali',
        'default_font_size' => 48,
        'default_line_height' => 1.3,
        'image_quality' => 4,
        'title_text_color' => '#ffffff',
        'facebook_text' => '',
        'show_facebook' => false,
        'youtube_text' => '',
        'show_youtube' => false,
        'website_text' => '',
        'show_website' => false,
        'instagram_text' => '',
        'show_instagram' => false,
        'linkedin_text' => '',
        'show_linkedin' => false,
        'social_icon_font_size' => 14,
        'custom_bg_image' => '',
        'card_bg_color' => '',
        'card_bg_gradient_enable' => false,
        'card_bg_gradient_color1' => '#667eea',
        'card_bg_gradient_color2' => '#764ba2',
        'card_bg_gradient_direction' => '135deg',
        'fi_object_fit' => 'cover',
        'fi_object_position' => 'center top',
        'fi_zoom' => 100,
        'fi_padding_top' => 0,
        'fi_padding_right' => 0,
        'fi_padding_bottom' => 0,
        'fi_padding_left' => 0,
        'fi_border_top' => 0,
        'fi_border_right' => 0,
        'fi_border_bottom' => 0,
        'fi_border_left' => 0,
        'fi_border_color' => '#ffffff',
        'fi_radius_tl' => 0,
        'fi_radius_tr' => 0,
        'fi_radius_bl' => 0,
        'fi_radius_br' => 0,
        'card_border_width' => 0,
        'card_border_color' => '#ffffff',
        'card_border_radius' => 0,
        'card_border_image' => '',
        'card_padding' => 0,
        'kalbela_bg_color' => '#cc0000',
        'news24_bg_color' => '#FFD700',
        'news24_text_color' => '#000000',
        'news24_date_bg' => '#1a5fb4',
        'dailystar_navy_color' => '#003366',
        'dailystar_accent_color' => '#cc0000',
        'prothomalo_primary_color' => '#e42313',
        'jugantor_green_color' => '#006838',
        'jugantor_gold_color' => '#d4a827',
        'samakal_primary_color' => '#FF6600',
        'samakal_dark_color' => '#1a1a1a',
        'dailyshadhin_bg_color' => '#1a0a0a',
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
        wp_enqueue_style('pcd-google-fonts', 'https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700;800;900&family=Hind+Siliguri:wght@400;600;700&family=Tiro+Bangla&family=Baloo+Da+2:wght@400;500;600;700;800&family=Galada&family=Mina:wght@400;700&family=Atma:wght@400;500;600;700&family=Anek+Bangla:wght@400;500;600;700;800&family=Noto+Serif+Bengali:wght@400;500;600;700;800;900&family=Mukta:wght@200;300;400;500;600;700;800&family=Charukola+Ultra+Light&family=Poetsen+One&family=Blinker:wght@400;600;700&family=Josefin+Sans:wght@400;600;700&display=swap', array(), PCD_VERSION);

        wp_enqueue_style('pcd-frontend-style', PCD_PLUGIN_URL . 'assets/css/frontend-style.css', array(), PCD_VERSION);
        wp_enqueue_script('html2canvas', 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js', array(), '1.4.1', true);
        wp_enqueue_script('pcd-frontend-script', PCD_PLUGIN_URL . 'assets/js/frontend-script.js', array('jquery', 'html2canvas'), PCD_VERSION, true);

        wp_localize_script('pcd-frontend-script', 'pcdData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('pcd_nonce')
        ));
    }
}

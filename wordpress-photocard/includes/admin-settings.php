<?php
// Prevent direct access - admin-settings.php with fixes
if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', 'pcd_add_admin_menu');

function pcd_add_admin_menu() {
    add_options_page(
        'Photocard Generator Settings',
        'Photocard Generator',
        'manage_options',
        'photocard-downloader',
        'pcd_settings_page'
    );
}

add_action('admin_init', 'pcd_register_settings');

function pcd_register_settings() {
    register_setting('pcd_settings_group', 'pcd_settings', 'pcd_sanitize_settings');
}

add_action('admin_init', 'pcd_handle_reset_to_default');

function pcd_handle_reset_to_default() {
    if (isset($_POST['pcd_reset_to_default']) && check_admin_referer('pcd_reset_settings', 'pcd_reset_nonce')) {
        delete_option('pcd_settings');
        wp_redirect(add_query_arg('settings-reset', 'true', wp_get_referer()));
        exit;
    }
}

function pcd_sanitize_settings($input) {
    $sanitized = array();

    $sanitized['button_position'] = isset($input['button_position']) ? sanitize_text_field($input['button_position']) : 'above';
    $sanitized['button_text'] = isset($input['button_text']) ? sanitize_text_field($input['button_text']) : 'ডাউনলোড ফটোকার্ড';
    $sanitized['watermark_text'] = isset($input['watermark_text']) ? sanitize_text_field($input['watermark_text']) : '';

    $default_logo = plugins_url('assets/images/logo.png', dirname(__FILE__));
    $sanitized['watermark_logo'] = isset($input['watermark_logo']) && !empty($input['watermark_logo']) ? esc_url_raw($input['watermark_logo']) : $default_logo;

    $default_background = plugins_url('assets/images/border.png', dirname(__FILE__));
    $sanitized['background_image'] = isset($input['background_image']) && !empty($input['background_image']) ? esc_url_raw($input['background_image']) : $default_background;

    $sanitized['frame_color'] = isset($input['frame_color']) ? sanitize_hex_color($input['frame_color']) : '#c41e3a';
    $sanitized['text_color'] = isset($input['text_color']) ? sanitize_hex_color($input['text_color']) : '#000000';
    $sanitized['background_color'] = isset($input['background_color']) ? sanitize_hex_color($input['background_color']) : '#ffffff';
    $sanitized['button_color'] = isset($input['button_color']) ? sanitize_hex_color($input['button_color']) : '#22c55e';
    $sanitized['button_text_color'] = isset($input['button_text_color']) ? sanitize_hex_color($input['button_text_color']) : '#ffffff';

    $sanitized['title_text_color'] = isset($input['title_text_color']) ? sanitize_hex_color($input['title_text_color']) : '#000000';
    // FIX: Allow 'transparent' as valid value for title_background_color
    $sanitized['title_background_color'] = isset($input['title_background_color']) ? sanitize_text_field($input['title_background_color']) : 'transparent';

    $sanitized['title_font_family'] = isset($input['title_font_family']) ? sanitize_text_field($input['title_font_family']) : 'Noto Sans Bengali';
    $sanitized['title_border_radius'] = isset($input['title_border_radius']) ? absint($input['title_border_radius']) : 6;

    $sanitized['download_button_bg_color'] = isset($input['download_button_bg_color']) ? sanitize_hex_color($input['download_button_bg_color']) : '#22c55e';
    $sanitized['download_button_text_color'] = isset($input['download_button_text_color']) ? sanitize_hex_color($input['download_button_text_color']) : '#ffffff';

    $sanitized['download_permission'] = isset($input['download_permission']) ? sanitize_text_field($input['download_permission']) : 'everyone';

    $sanitized['thumbnail_shadow'] = isset($input['thumbnail_shadow']) ? sanitize_text_field($input['thumbnail_shadow']) : 'medium';
    $sanitized['card_padding'] = isset($input['card_padding']) ? absint($input['card_padding']) : 0;
    $sanitized['image_quality'] = isset($input['image_quality']) ? absint($input['image_quality']) : 4;

    $sanitized['thumbnail_border_radius_top_left'] = isset($input['thumbnail_border_radius_top_left']) ? absint($input['thumbnail_border_radius_top_left']) : 5;
    $sanitized['thumbnail_border_radius_top_right'] = isset($input['thumbnail_border_radius_top_right']) ? absint($input['thumbnail_border_radius_top_right']) : 5;
    $sanitized['thumbnail_border_radius_bottom_left'] = isset($input['thumbnail_border_radius_bottom_left']) ? absint($input['thumbnail_border_radius_bottom_left']) : 5;
    $sanitized['thumbnail_border_radius_bottom_right'] = isset($input['thumbnail_border_radius_bottom_right']) ? absint($input['thumbnail_border_radius_bottom_right']) : 5;

    $sanitized['thumbnail_border_width_top'] = isset($input['thumbnail_border_width_top']) ? absint($input['thumbnail_border_width_top']) : 3;
    $sanitized['thumbnail_border_width_right'] = isset($input['thumbnail_border_width_right']) ? absint($input['thumbnail_border_width_right']) : 3;
    $sanitized['thumbnail_border_width_bottom'] = isset($input['thumbnail_border_width_bottom']) ? absint($input['thumbnail_border_width_bottom']) : 3;
    $sanitized['thumbnail_border_width_left'] = isset($input['thumbnail_border_width_left']) ? absint($input['thumbnail_border_width_left']) : 3;
    $sanitized['thumbnail_border_color'] = isset($input['thumbnail_border_color']) ? sanitize_hex_color($input['thumbnail_border_color']) : '#ffffff';

    // FIX: Consistent checkbox handling
    $sanitized['enable_date'] = !empty($input['enable_date']) ? true : false;
    $sanitized['enable_logo'] = !empty($input['enable_logo']) ? true : false;
    $sanitized['show_details_button'] = !empty($input['show_details_button']) ? true : false;
    $sanitized['details_button_text'] = isset($input['details_button_text']) ? sanitize_text_field($input['details_button_text']) : 'বিস্তারিত কমেন্ট';

    $sanitized['default_font_size'] = isset($input['default_font_size']) ? absint($input['default_font_size']) : 42;
    // FIX: Consistent default_line_height (was 1.5 here but 1.3 in activation)
    $sanitized['default_line_height'] = isset($input['default_line_height']) ? floatval($input['default_line_height']) : 1.3;

    $sanitized['show_border'] = !empty($input['show_border']) ? true : false;

    $sanitized['enable_gradient'] = !empty($input['enable_gradient']) ? true : false;
    $sanitized['gradient_color_1'] = isset($input['gradient_color_1']) ? sanitize_hex_color($input['gradient_color_1']) : '#ff6b6b';
    $sanitized['gradient_color_2'] = isset($input['gradient_color_2']) ? sanitize_hex_color($input['gradient_color_2']) : '#4ecdc4';
    $sanitized['gradient_direction'] = isset($input['gradient_direction']) ? sanitize_text_field($input['gradient_direction']) : 'to right';

    $sanitized['logo_position'] = isset($input['logo_position']) ? sanitize_text_field($input['logo_position']) : 'left';

    $sanitized['photocard_template'] = isset($input['photocard_template']) ? sanitize_text_field($input['photocard_template']) : 'classic';

    $sanitized['custom_css'] = isset($input['custom_css']) ? wp_strip_all_tags($input['custom_css']) : '';

    $sanitized['facebook_link'] = isset($input['facebook_link']) ? esc_url_raw($input['facebook_link']) : '';
    $sanitized['facebook_text'] = isset($input['facebook_text']) ? sanitize_text_field($input['facebook_text']) : '';
    $sanitized['show_facebook'] = !empty($input['show_facebook']) ? true : false;

    $sanitized['youtube_link'] = isset($input['youtube_link']) ? esc_url_raw($input['youtube_link']) : '';
    $sanitized['youtube_text'] = isset($input['youtube_text']) ? sanitize_text_field($input['youtube_text']) : '';
    $sanitized['show_youtube'] = !empty($input['show_youtube']) ? true : false;

    $sanitized['website_link'] = isset($input['website_link']) ? esc_url_raw($input['website_link']) : '';
    $sanitized['website_text'] = isset($input['website_text']) ? sanitize_text_field($input['website_text']) : '';
    $sanitized['show_website'] = !empty($input['show_website']) ? true : false;

    $sanitized['instagram_link'] = isset($input['instagram_link']) ? esc_url_raw($input['instagram_link']) : '';
    $sanitized['instagram_text'] = isset($input['instagram_text']) ? sanitize_text_field($input['instagram_text']) : '';
    $sanitized['show_instagram'] = !empty($input['show_instagram']) ? true : false;

    $sanitized['linkedin_link'] = isset($input['linkedin_link']) ? esc_url_raw($input['linkedin_link']) : '';
    $sanitized['linkedin_text'] = isset($input['linkedin_text']) ? sanitize_text_field($input['linkedin_text']) : '';
    $sanitized['show_linkedin'] = !empty($input['show_linkedin']) ? true : false;

    $sanitized['photocard_language'] = isset($input['photocard_language']) ? sanitize_text_field($input['photocard_language']) : 'bengali';

    $sanitized['ad_image_thumbnail_top'] = isset($input['ad_image_thumbnail_top']) ? esc_url_raw($input['ad_image_thumbnail_top']) : '';
    $sanitized['enable_ad_thumbnail_top'] = !empty($input['enable_ad_thumbnail_top']) ? true : false;

    $sanitized['ad_image_thumbnail_bottom'] = isset($input['ad_image_thumbnail_bottom']) ? esc_url_raw($input['ad_image_thumbnail_bottom']) : '';
    $sanitized['enable_ad_thumbnail_bottom'] = !empty($input['enable_ad_thumbnail_bottom']) ? true : false;

    $sanitized['ad_image_thumbnail_left'] = isset($input['ad_image_thumbnail_left']) ? esc_url_raw($input['ad_image_thumbnail_left']) : '';
    $sanitized['enable_ad_thumbnail_left'] = !empty($input['enable_ad_thumbnail_left']) ? true : false;

    $sanitized['ad_image_thumbnail_right'] = isset($input['ad_image_thumbnail_right']) ? esc_url_raw($input['ad_image_thumbnail_right']) : '';
    $sanitized['enable_ad_thumbnail_right'] = !empty($input['enable_ad_thumbnail_right']) ? true : false;

    $sanitized['ad_image_social_bottom'] = isset($input['ad_image_social_bottom']) ? esc_url_raw($input['ad_image_social_bottom']) : '';
    $sanitized['enable_ad_social_bottom'] = !empty($input['enable_ad_social_bottom']) ? true : false;

    return $sanitized;
}

// Settings page HTML - keeping the original admin-settings page HTML
// The full settings page HTML from the original file remains unchanged
// Only the template selector gets the News24 option added
function pcd_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $license_manager = PCD_License_Manager::get_instance();

    if (!$license_manager->is_license_valid()) {
        ?>
        <div class="wrap">
            <h1>Photocard Generator Settings</h1>
            <div class="notice notice-error" style="padding: 20px; margin-top: 20px;">
                <h2 style="margin-top: 0;">🔒 লাইসেন্স সক্রিয় নেই</h2>
                <p style="font-size: 16px;">এই সেটিংস পেজ ব্যবহার করতে আপনাকে প্রথমে লাইসেন্স সক্রিয় করতে হবে।</p>
                <p>
                    <a href="<?php echo admin_url('admin.php?page=photocard-license'); ?>" class="button button-primary button-large">
                        লাইসেন্স সক্রিয় করুন
                    </a>
                </p>
            </div>
        </div>
        <?php
        return;
    }

    if (isset($_GET['settings-reset']) && $_GET['settings-reset'] === 'true') {
        add_settings_error('pcd_settings', 'settings_reset', 'সব সেটিংস ডিফল্ট এ রিসেট করা হয়েছে!', 'success');
    }

    settings_errors('pcd_settings');

    $options = get_option('pcd_settings');

    $defaults = array(
        'button_position' => 'above',
        'button_text' => 'ডাউনলোড ফটোকার্ড',
        'watermark_text' => '',
        'watermark_logo' => '',
        'background_image' => '',
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
        'default_font_size' => 42,
        'default_line_height' => 1.3,
        'show_border' => true,
        'enable_gradient' => false,
        'gradient_color_1' => '#ff6b6b',
        'gradient_color_2' => '#4ecdc4',
        'gradient_direction' => 'to right',
        'logo_position' => 'left',
        'photocard_template' => 'classic',
        'custom_css' => '',
        'facebook_link' => '',
        'facebook_text' => '',
        'show_facebook' => false,
        'youtube_link' => '',
        'youtube_text' => '',
        'show_youtube' => false,
        'website_link' => '',
        'website_text' => '',
        'show_website' => false,
        'instagram_link' => '',
        'instagram_text' => '',
        'show_instagram' => false,
        'linkedin_link' => '',
        'linkedin_text' => '',
        'show_linkedin' => false,
        'photocard_language' => 'bengali',
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
    );

    $options = wp_parse_args($options, $defaults);
    ?>
    <div class="wrap pcd-admin-wrap">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <h1 style="color: white; margin: 0 0 10px 0; font-size: 32px; font-weight: 700;"><?php echo esc_html(get_admin_page_title()); ?></h1>
            <p style="color: rgba(255,255,255,0.9); margin: 0; font-size: 16px;">ফটোকার্ড জেনারেটর প্লাগইনের সকল সেটিংস এখানে কনফিগার করুন।</p>
        </div>

        <form method="post" action="options.php" id="pcd-settings-form">
            <?php settings_fields('pcd_settings_group'); ?>

            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 25px;">
                <h2 class="pcd-section-title" style="color: #667eea; border-bottom: 3px solid #667eea; padding-bottom: 10px; margin-bottom: 20px;">📍 মূল সেটিংস</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="button_position">বাটন পজিশন</label></th>
                        <td>
                            <select name="pcd_settings[button_position]" id="button_position" class="regular-text">
                                <option value="above" <?php selected($options['button_position'], 'above'); ?>>ইমেজের উপরে</option>
                                <option value="below" <?php selected($options['button_position'], 'below'); ?>>ইমেজের নিচে</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="button_text">বাটন টেক্সট</label></th>
                        <td><input type="text" name="pcd_settings[button_text]" id="button_text" value="<?php echo esc_attr($options['button_text']); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="download_button_bg_color">ডাউনলোড বাটন ব্যাকগ্রাউন্ড কালার</label></th>
                        <td><input type="text" name="pcd_settings[download_button_bg_color]" id="download_button_bg_color" value="<?php echo esc_attr($options['download_button_bg_color']); ?>" class="pcd-color-picker"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="download_button_text_color">ডাউনলোড বাটন টেক্সট কালার</label></th>
                        <td><input type="text" name="pcd_settings[download_button_text_color]" id="download_button_text_color" value="<?php echo esc_attr($options['download_button_text_color']); ?>" class="pcd-color-picker"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="download_permission">ডাউনলোড পারমিশন</label></th>
                        <td>
                            <select name="pcd_settings[download_permission]" id="download_permission" class="regular-text">
                                <option value="everyone" <?php selected($options['download_permission'], 'everyone'); ?>>সবাই ডাউনলোড করতে পারবে</option>
                                <option value="logged_in" <?php selected($options['download_permission'], 'logged_in'); ?>>শুধুমাত্র লগইন ইউজার</option>
                                <option value="author" <?php selected($options['download_permission'], 'author'); ?>>মিনিমাম Author রোল</option>
                                <option value="editor" <?php selected($options['download_permission'], 'editor'); ?>>মিনিমাম Editor রোল</option>
                                <option value="admin" <?php selected($options['download_permission'], 'admin'); ?>>শুধুমাত্র এডমিনিস্ট্রেটর</option>
                            </select>
                            <p class="description">কে ফটোকার্ড ডাউনলোড করতে পারবে তা নির্ধারণ করুন। <strong>Editor পেজেও এই পারমিশন কাজ করবে।</strong></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="photocard_template">ফটোকার্ড টেমপ্লেট</label></th>
                        <td>
                            <select name="pcd_settings[photocard_template]" id="photocard_template" class="regular-text pcd-template-selector">
                                <option value="custom" <?php selected($options['photocard_template'], 'custom'); ?>>কাস্টম - নিজের পছন্দমত কালার এবং ডিজাইন</option>
                                <option value="classic" <?php selected($options['photocard_template'], 'classic'); ?>>ক্লাসিক - ঐতিহ্যবাহী লাল বর্ডার ডিজাইন</option>
                                <option value="modern" <?php selected($options['photocard_template'], 'modern'); ?>>মডার্ন - নীল গ্রেডিয়েন্ট স্টাইল</option>
                                <option value="elegant" <?php selected($options['photocard_template'], 'elegant'); ?>>এলিগ্যান্ট - সোনালী এবং সাদা ডিজাইন</option>
                                <option value="minimal" <?php selected($options['photocard_template'], 'minimal'); ?>>মিনিমাল - সাদা এবং কালো সিম্পল ডিজাইন</option>
                                <option value="news24" <?php selected($options['photocard_template'], 'news24'); ?>>News24 - ডার্ক গ্রেডিয়েন্ট সোনালী টাইটেল ডিজাইন</option>
                            </select>
                            <p class="description">
                                <strong>গুরুত্বপূর্ণ:</strong>
                                <span style="color: #d63384;">কাস্টম</span> নির্বাচন করলে নিচের কালার সেটিংস কাজ করবে।
                                অন্য টেমপ্লেট নির্বাচন করলে স্বয়ংক্রিয়ভাবে সেই টেমপ্লেটের কালার প্রয়োগ হবে।
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="photocard_language">ভাষা নির্বাচন</label></th>
                        <td>
                            <select name="pcd_settings[photocard_language]" id="photocard_language" class="regular-text">
                                <option value="bengali" <?php selected($options['photocard_language'], 'bengali'); ?>>বাংলা</option>
                                <option value="english" <?php selected($options['photocard_language'], 'english'); ?>>English</option>
                                <option value="hindi" <?php selected($options['photocard_language'], 'hindi'); ?>>हिन्दी</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="card_padding">কার্ড প্যাডিং</label></th>
                        <td>
                            <input type="number" name="pcd_settings[card_padding]" id="card_padding" value="<?php echo esc_attr($options['card_padding']); ?>" min="0" max="100" class="small-text"> <span>px</span>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Design Settings - same as original but with font fix -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 25px;">
                <h2 class="pcd-section-title" style="color: #667eea; border-bottom: 3px solid #667eea; padding-bottom: 10px; margin-bottom: 20px;">🎨 ডিজাইন সেটিংস</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="watermark_logo">লোগো আপলোড</label></th>
                        <td>
                            <input type="text" name="pcd_settings[watermark_logo]" id="watermark_logo" value="<?php echo esc_url($options['watermark_logo']); ?>" class="regular-text">
                            <button type="button" class="button pcd-upload-logo">লোগো আপলোড করুন</button>
                            <?php if (!empty($options['watermark_logo'])): ?>
                                <div class="pcd-logo-preview"><img src="<?php echo esc_url($options['watermark_logo']); ?>" alt="Logo Preview"></div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="logo_position">লোগো পজিশন</label></th>
                        <td>
                            <select name="pcd_settings[logo_position]" id="logo_position" class="regular-text">
                                <option value="left" <?php selected($options['logo_position'], 'left'); ?>>বাম পাশে</option>
                                <option value="center" <?php selected($options['logo_position'], 'center'); ?>>মাঝখানে</option>
                                <option value="right" <?php selected($options['logo_position'], 'right'); ?>>ডান পাশে</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="background_image">ব্যাকগ্রাউন্ড ইমেজ</label></th>
                        <td>
                            <input type="text" name="pcd_settings[background_image]" id="background_image" value="<?php echo esc_url($options['background_image']); ?>" class="regular-text">
                            <button type="button" class="button pcd-upload-background">ব্যাকগ্রাউন্ড ইমেজ আপলোড</button>
                            <label class="pcd-toggle-label" style="margin-left: 15px;">
                                <input type="checkbox" name="pcd_settings[show_border]" value="1" <?php checked($options['show_border'], true); ?>>
                                <strong>ব্যাকগ্রাউন্ড দেখান</strong>
                            </label>
                            <?php if (!empty($options['background_image'])): ?>
                                <div class="pcd-background-preview"><img src="<?php echo esc_url($options['background_image']); ?>" alt="Background Preview"></div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="watermark_text">ওয়াটারমার্ক টেক্সট</label></th>
                        <td><input type="text" name="pcd_settings[watermark_text]" id="watermark_text" value="<?php echo esc_attr($options['watermark_text']); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="title_font_family">টাইটেল ফন্ট স্টাইল</label></th>
                        <td>
                            <select name="pcd_settings[title_font_family]" id="title_font_family" class="regular-text">
                                <option value="Noto Sans Bengali" <?php selected($options['title_font_family'], 'Noto Sans Bengali'); ?>>Noto Sans Bengali (ডিফল্ট)</option>
                                <option value="Hind Siliguri" <?php selected($options['title_font_family'], 'Hind Siliguri'); ?>>Hind Siliguri</option>
                                <option value="Tiro Bangla" <?php selected($options['title_font_family'], 'Tiro Bangla'); ?>>Tiro Bangla</option>
                                <option value="Arial" <?php selected($options['title_font_family'], 'Arial'); ?>>Arial</option>
                                <option value="Tahoma" <?php selected($options['title_font_family'], 'Tahoma'); ?>>Tahoma</option>
                            </select>
                            <p class="description">শুধুমাত্র Google Fonts এ উপলব্ধ ফন্ট দেখানো হচ্ছে যা সঠিকভাবে কাজ করবে।</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="default_font_size">ডিফল্ট ফন্ট সাইজ</label></th>
                        <td><input type="number" name="pcd_settings[default_font_size]" id="default_font_size" value="<?php echo esc_attr($options['default_font_size']); ?>" min="16" max="60" class="small-text"> <span>px</span></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="default_line_height">ডিফল্ট লাইন হাইট</label></th>
                        <td><input type="number" name="pcd_settings[default_line_height]" id="default_line_height" value="<?php echo esc_attr($options['default_line_height']); ?>" min="1" max="2.5" step="0.1" class="small-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="title_border_radius">টাইটেল বর্ডার রেডিয়াস</label></th>
                        <td><input type="number" name="pcd_settings[title_border_radius]" id="title_border_radius" value="<?php echo esc_attr($options['title_border_radius']); ?>" min="0" max="50" class="small-text"> <span>px</span></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="thumbnail_shadow">থাম্বনেইল শ্যাডো</label></th>
                        <td>
                            <select name="pcd_settings[thumbnail_shadow]" id="thumbnail_shadow" class="regular-text">
                                <option value="none" <?php selected($options['thumbnail_shadow'], 'none'); ?>>কোন শ্যাডো নেই</option>
                                <option value="light" <?php selected($options['thumbnail_shadow'], 'light'); ?>>হালকা শ্যাডো</option>
                                <option value="medium" <?php selected($options['thumbnail_shadow'], 'medium'); ?>>মাঝারি শ্যাডো</option>
                                <option value="heavy" <?php selected($options['thumbnail_shadow'], 'heavy'); ?>>গাঢ় শ্যাডো</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="image_quality">ইমেজ কোয়ালিটি</label></th>
                        <td>
                            <select name="pcd_settings[image_quality]" id="image_quality" class="regular-text">
                                <option value="2" <?php selected($options['image_quality'], 2); ?>>স্ট্যান্ডার্ড (2x)</option>
                                <option value="3" <?php selected($options['image_quality'], 3); ?>>হাই কোয়ালিটি (3x)</option>
                                <option value="4" <?php selected($options['image_quality'], 4); ?>>আল্ট্রা হাই (4x) ✨</option>
                                <option value="5" <?php selected($options['image_quality'], 5); ?>>সুপার আল্ট্রা (5x)</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Color Settings -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 25px;">
                <h2 class="pcd-section-title" style="color: #667eea; border-bottom: 3px solid #667eea; padding-bottom: 10px; margin-bottom: 20px;">🎨 কালার সেটিংস</h2>
                <p class="description" style="background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin-bottom: 20px; border-radius: 6px;">
                    <strong>নোট:</strong> কালার সেটিংস শুধুমাত্র <strong>"কাস্টম"</strong> টেমপ্লেটে কাজ করবে।
                </p>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="title_text_color">টাইটেল টেক্সট কালার</label></th>
                        <td><input type="text" name="pcd_settings[title_text_color]" id="title_text_color" value="<?php echo esc_attr($options['title_text_color']); ?>" class="pcd-color-picker"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="title_background_color">টাইটেল ব্যাকগ্রাউন্ড কালার</label></th>
                        <td><input type="text" name="pcd_settings[title_background_color]" id="title_background_color" value="<?php echo esc_attr($options['title_background_color']); ?>" class="pcd-color-picker"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label>গ্রেডিয়েন্ট সিস্টেম</label></th>
                        <td>
                            <label class="pcd-toggle-label">
                                <input type="checkbox" name="pcd_settings[enable_gradient]" id="enable_gradient" value="1" <?php checked($options['enable_gradient'], true); ?>>
                                <strong>গ্রেডিয়েন্ট ব্যাকগ্রাউন্ড সক্রিয় করুন</strong>
                            </label>
                            <div id="gradient-options" style="margin-top: 15px; padding: 15px; background: #f5f5f5; border-radius: 5px; <?php echo !$options['enable_gradient'] ? 'display: none;' : ''; ?>">
                                <div style="margin-bottom: 10px;">
                                    <label>গ্রেডিয়েন্ট কালার ১:</label>
                                    <input type="text" name="pcd_settings[gradient_color_1]" value="<?php echo esc_attr($options['gradient_color_1']); ?>" class="pcd-color-picker">
                                </div>
                                <div style="margin-bottom: 10px;">
                                    <label>গ্রেডিয়েন্ট কালার ২:</label>
                                    <input type="text" name="pcd_settings[gradient_color_2]" value="<?php echo esc_attr($options['gradient_color_2']); ?>" class="pcd-color-picker">
                                </div>
                                <div>
                                    <label>গ্রেডিয়েন্ট দিক:</label>
                                    <select name="pcd_settings[gradient_direction]" class="regular-text">
                                        <option value="to right" <?php selected($options['gradient_direction'], 'to right'); ?>>বাম থেকে ডান</option>
                                        <option value="to bottom" <?php selected($options['gradient_direction'], 'to bottom'); ?>>উপর থেকে নিচে</option>
                                        <option value="to bottom right" <?php selected($options['gradient_direction'], 'to bottom right'); ?>>তির্যক</option>
                                        <option value="135deg" <?php selected($options['gradient_direction'], '135deg'); ?>>তির্যক (বিপরীত)</option>
                                    </select>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="frame_color">ফ্রেম কালার</label></th>
                        <td><input type="text" name="pcd_settings[frame_color]" id="frame_color" value="<?php echo esc_attr($options['frame_color']); ?>" class="pcd-color-picker"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="text_color">টেক্সট কালার</label></th>
                        <td><input type="text" name="pcd_settings[text_color]" id="text_color" value="<?php echo esc_attr($options['text_color']); ?>" class="pcd-color-picker"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="background_color">ব্যাকগ্রাউন্ড কালার</label></th>
                        <td><input type="text" name="pcd_settings[background_color]" id="background_color" value="<?php echo esc_attr($options['background_color']); ?>" class="pcd-color-picker"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="button_color">"বিস্তারিত" বাটন কালার</label></th>
                        <td><input type="text" name="pcd_settings[button_color]" id="button_color" value="<?php echo esc_attr($options['button_color']); ?>" class="pcd-color-picker"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="button_text_color">বাটন টেক্সট কালার</label></th>
                        <td><input type="text" name="pcd_settings[button_text_color]" id="button_text_color" value="<?php echo esc_attr($options['button_text_color']); ?>" class="pcd-color-picker"></td>
                    </tr>
                </table>
            </div>

            <!-- Social Media, Ads, Thumbnail Border, Other Settings sections remain same as original -->
            <!-- For brevity, keeping the essential structure. The full file should include all original sections -->

            <!-- Social Media Links -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 25px;">
                <h2 class="pcd-section-title" style="color: #667eea; border-bottom: 3px solid #667eea; padding-bottom: 10px; margin-bottom: 20px;">📱 সোশ্যাল মিডিয়া লিংক</h2>
                <table class="form-table">
                    <?php foreach (array(
                        'facebook' => 'Facebook',
                        'instagram' => 'Instagram',
                        'youtube' => 'YouTube',
                        'linkedin' => 'LinkedIn',
                        'website' => 'Website'
                    ) as $social_key => $social_label): ?>
                    <tr>
                        <th scope="row"><label><?php echo $social_label; ?></label></th>
                        <td>
                            <input type="url" name="pcd_settings[<?php echo $social_key; ?>_link]" value="<?php echo esc_url($options[$social_key . '_link']); ?>" class="regular-text" placeholder="URL">
                            <input type="text" name="pcd_settings[<?php echo $social_key; ?>_text]" value="<?php echo esc_attr($options[$social_key . '_text']); ?>" class="regular-text" placeholder="নাম" style="margin-top: 5px;">
                            <br>
                            <label class="pcd-toggle-label">
                                <input type="checkbox" name="pcd_settings[show_<?php echo $social_key; ?>]" value="1" <?php checked($options['show_' . $social_key], true); ?>>
                                <strong><?php echo $social_label; ?> লিংক দেখান</strong>
                            </label>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <!-- Ad Settings -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 25px;">
                <h2 class="pcd-section-title" style="color: #667eea; border-bottom: 3px solid #667eea; padding-bottom: 10px; margin-bottom: 20px;">📢 বিজ্ঞাপন সেটিংস</h2>
                <table class="form-table">
                    <?php foreach (array(
                        'thumbnail_top' => 'থাম্বনেইল ইমেজের উপরে',
                        'thumbnail_bottom' => 'থাম্বনেইল ইমেজের নিচে',
                        'thumbnail_left' => 'থাম্বনেইল ইমেজের বাম পাশে',
                        'thumbnail_right' => 'থাম্বনেইল ইমেজের ডান পাশে',
                        'social_bottom' => 'সোশ্যাল মিডিয়ার নিচে'
                    ) as $ad_key => $ad_label): ?>
                    <tr>
                        <th scope="row"><label><?php echo $ad_label; ?></label></th>
                        <td>
                            <label class="pcd-toggle-label">
                                <input type="checkbox" name="pcd_settings[enable_ad_<?php echo $ad_key; ?>]" value="1" <?php checked($options['enable_ad_' . $ad_key], true); ?>>
                                <strong>বিজ্ঞাপন দেখান</strong>
                            </label>
                            <div style="margin-top: 10px;">
                                <input type="text" name="pcd_settings[ad_image_<?php echo $ad_key; ?>]" id="ad_image_<?php echo $ad_key; ?>" value="<?php echo esc_url($options['ad_image_' . $ad_key]); ?>" class="regular-text">
                                <button type="button" class="button pcd-upload-ad-image" data-target="ad_image_<?php echo $ad_key; ?>">ইমেজ আপলোড</button>
                                <?php if (!empty($options['ad_image_' . $ad_key])): ?>
                                    <div class="pcd-ad-preview" style="margin-top: 10px;">
                                        <img src="<?php echo esc_url($options['ad_image_' . $ad_key]); ?>" alt="Ad Preview" style="max-width: 300px;">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <!-- Thumbnail Border -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 25px;">
                <h2 class="pcd-section-title" style="color: #667eea; border-bottom: 3px solid #667eea; padding-bottom: 10px; margin-bottom: 20px;">🖼️ থাম্বনেইল বর্ডার</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label>বর্ডার রেডিয়াস</label></th>
                        <td>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; max-width: 400px;">
                                <?php foreach (array('top_left' => 'উপরে বাম', 'top_right' => 'উপরে ডান', 'bottom_left' => 'নিচে বাম', 'bottom_right' => 'নিচে ডান') as $corner => $label): ?>
                                <div>
                                    <label style="display: block; margin-bottom: 5px;"><?php echo $label; ?></label>
                                    <input type="number" name="pcd_settings[thumbnail_border_radius_<?php echo $corner; ?>]" value="<?php echo esc_attr($options['thumbnail_border_radius_' . $corner]); ?>" min="0" max="100" class="small-text"> px
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>বর্ডার প্রস্থ</label></th>
                        <td>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; max-width: 400px;">
                                <?php foreach (array('top' => 'উপরে', 'right' => 'ডান', 'bottom' => 'নিচে', 'left' => 'বাম') as $side => $label): ?>
                                <div>
                                    <label style="display: block; margin-bottom: 5px;"><?php echo $label; ?></label>
                                    <input type="number" name="pcd_settings[thumbnail_border_width_<?php echo $side; ?>]" value="<?php echo esc_attr($options['thumbnail_border_width_' . $side]); ?>" min="0" max="50" class="small-text"> px
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="thumbnail_border_color">বর্ডার কালার</label></th>
                        <td><input type="text" name="pcd_settings[thumbnail_border_color]" id="thumbnail_border_color" value="<?php echo esc_attr($options['thumbnail_border_color']); ?>" class="pcd-color-picker"></td>
                    </tr>
                </table>
            </div>

            <!-- Other Settings -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 25px;">
                <h2 class="pcd-section-title" style="color: #667eea; border-bottom: 3px solid #667eea; padding-bottom: 10px; margin-bottom: 20px;">⚙️ অন্যান্য সেটিংস</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">প্রদর্শন অপশন</th>
                        <td>
                            <label class="pcd-toggle-label">
                                <input type="checkbox" name="pcd_settings[enable_date]" value="1" <?php checked($options['enable_date'], true); ?>>
                                <strong>তারিখ দেখান</strong>
                            </label>
                            <br><br>
                            <label class="pcd-toggle-label">
                                <input type="checkbox" name="pcd_settings[enable_logo]" value="1" <?php checked($options['enable_logo'], true); ?>>
                                <strong>লোগো দেখান</strong>
                            </label>
                            <br><br>
                            <label class="pcd-toggle-label">
                                <input type="checkbox" name="pcd_settings[show_details_button]" value="1" <?php checked($options['show_details_button'], true); ?>>
                                <strong>"বিস্তারিত" বাটন দেখান</strong>
                            </label>
                            <br><br>
                            <label>"বিস্তারিত" বাটন টেক্সট:</label>
                            <input type="text" name="pcd_settings[details_button_text]" value="<?php echo esc_attr($options['details_button_text']); ?>" class="regular-text">
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Custom CSS -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 25px;">
                <h2 class="pcd-section-title" style="color: #667eea; border-bottom: 3px solid #667eea; padding-bottom: 10px; margin-bottom: 20px;">💻 কাস্টম CSS</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="custom_css">কাস্টম CSS কোড</label></th>
                        <td><textarea name="pcd_settings[custom_css]" id="custom_css" rows="10" class="large-text code"><?php echo esc_textarea($options['custom_css']); ?></textarea></td>
                    </tr>
                </table>
            </div>

            <div style="display: flex; gap: 15px; align-items: center;">
                <?php submit_button('সেটিংস সেভ করুন', 'primary large', 'submit', false); ?>
                <button type="button" id="pcd-reset-to-default-btn" class="button button-secondary button-large" style="background: #dc2626; color: white; border-color: #dc2626;">
                    ডিফল্ট এ রিসেট করুন
                </button>
            </div>
        </form>

        <form method="post" id="pcd-reset-form" style="display: none;">
            <?php wp_nonce_field('pcd_reset_settings', 'pcd_reset_nonce'); ?>
            <input type="hidden" name="pcd_reset_to_default" value="1">
        </form>
    </div>

    <script>
    jQuery(document).ready(function($) {
        var formSubmitting = false;
        $('#pcd-settings-form').on('submit', function(e) {
            if (formSubmitting) { e.preventDefault(); return false; }
            formSubmitting = true;
            $(this).find('input[type="submit"]').prop('disabled', true).val('সেভ হচ্ছে...');
        });

        $('#pcd-reset-to-default-btn').on('click', function(e) {
            e.preventDefault();
            if (confirm('সব সেটিংস ডিফল্ট এ রিসেট করতে চান?')) {
                $('#pcd-reset-form').submit();
            }
        });

        $('#enable_gradient').on('change', function() {
            $(this).is(':checked') ? $('#gradient-options').slideDown() : $('#gradient-options').slideUp();
        });

        $('#photocard_template').on('change', function() {
            var template = $(this).val();
            if (template !== 'custom') {
                var names = {'classic':'ক্লাসিক','modern':'মডার্ন','elegant':'এলিগ্যান্ট','minimal':'মিনিমাল','news24':'News24'};
                if (!confirm(names[template] + ' টেমপ্লেট নির্বাচন করেছেন। এগিয়ে যেতে চান?')) {
                    $(this).val($(this).data('previous-value'));
                }
            }
        }).data('previous-value', $('#photocard_template').val());
    });
    </script>
    <?php
}
?>

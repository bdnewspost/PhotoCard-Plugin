<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', 'pcd_add_admin_menu');

function pcd_get_settings_capability() {
    $options = get_option('pcd_settings');
    $role = isset($options['settings_access_role']) ? $options['settings_access_role'] : 'admin';

    switch ($role) {
        case 'author':
            return 'publish_posts';
        case 'editor':
            return 'edit_others_posts';
        case 'admin':
        default:
            return 'manage_options';
    }
}

function pcd_add_admin_menu() {
    $capability = pcd_get_settings_capability();
    add_options_page(
        'Photocard Generator Settings',
        'Photocard Generator',
        $capability,
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

    // Basic
    $sanitized['button_position'] = isset($input['button_position']) ? sanitize_text_field($input['button_position']) : 'above';
    $sanitized['button_text'] = isset($input['button_text']) ? sanitize_text_field($input['button_text']) : 'ডাউনলোড ফটোকার্ড';
    $sanitized['download_permission'] = isset($input['download_permission']) ? sanitize_text_field($input['download_permission']) : 'everyone';
    $sanitized['settings_access_role'] = isset($input['settings_access_role']) ? sanitize_text_field($input['settings_access_role']) : 'admin';
    $sanitized['download_button_bg_color'] = isset($input['download_button_bg_color']) ? sanitize_hex_color($input['download_button_bg_color']) : '#22c55e';
    $sanitized['download_button_text_color'] = isset($input['download_button_text_color']) ? sanitize_hex_color($input['download_button_text_color']) : '#ffffff';

    // Template
    $sanitized['photocard_template'] = isset($input['photocard_template']) ? sanitize_text_field($input['photocard_template']) : 'news24';
    $sanitized['photocard_language'] = isset($input['photocard_language']) ? sanitize_text_field($input['photocard_language']) : 'bengali';

    // Logo
    $default_logo = plugins_url('assets/images/logo.png', dirname(__FILE__));
    $sanitized['watermark_logo'] = isset($input['watermark_logo']) && !empty($input['watermark_logo']) ? esc_url_raw($input['watermark_logo']) : $default_logo;
    $sanitized['logo_position'] = isset($input['logo_position']) ? sanitize_text_field($input['logo_position']) : 'left';

    // Date
    $sanitized['enable_date'] = !empty($input['enable_date']) ? true : false;
    $sanitized['date_position'] = isset($input['date_position']) ? sanitize_text_field($input['date_position']) : 'right';

    // Display
    $sanitized['enable_logo'] = !empty($input['enable_logo']) ? true : false;
    $sanitized['show_details_button'] = !empty($input['show_details_button']) ? true : false;
    $sanitized['details_button_text'] = isset($input['details_button_text']) ? sanitize_text_field($input['details_button_text']) : 'বিস্তারিত কমেন্টে';
    $sanitized['title_top_offset'] = isset($input['title_top_offset']) ? intval($input['title_top_offset']) : 0;
    $sanitized['details_bottom_offset'] = isset($input['details_bottom_offset']) ? intval($input['details_bottom_offset']) : 0;

    // Font
    $sanitized['title_font_family'] = isset($input['title_font_family']) ? sanitize_text_field($input['title_font_family']) : 'Noto Sans Bengali';
    $sanitized['default_font_size'] = isset($input['default_font_size']) ? absint($input['default_font_size']) : 48;
    $sanitized['default_line_height'] = isset($input['default_line_height']) ? floatval($input['default_line_height']) : 1.3;
    $sanitized['image_quality'] = isset($input['image_quality']) ? absint($input['image_quality']) : 4;

    // Social
    $sanitized['facebook_text'] = isset($input['facebook_text']) ? sanitize_text_field($input['facebook_text']) : '';
    $sanitized['show_facebook'] = !empty($input['show_facebook']) ? true : false;
    $sanitized['youtube_text'] = isset($input['youtube_text']) ? sanitize_text_field($input['youtube_text']) : '';
    $sanitized['show_youtube'] = !empty($input['show_youtube']) ? true : false;
    $sanitized['website_text'] = isset($input['website_text']) ? sanitize_text_field($input['website_text']) : '';
    $sanitized['show_website'] = !empty($input['show_website']) ? true : false;
    $sanitized['instagram_text'] = isset($input['instagram_text']) ? sanitize_text_field($input['instagram_text']) : '';
    $sanitized['show_instagram'] = !empty($input['show_instagram']) ? true : false;
    $sanitized['linkedin_text'] = isset($input['linkedin_text']) ? sanitize_text_field($input['linkedin_text']) : '';
    $sanitized['show_linkedin'] = !empty($input['show_linkedin']) ? true : false;

    // Custom background image
    $sanitized['custom_bg_image'] = isset($input['custom_bg_image']) && !empty($input['custom_bg_image']) ? esc_url_raw($input['custom_bg_image']) : '';

    // Domain text for templates
    $sanitized['domain_text'] = isset($input['domain_text']) ? sanitize_text_field($input['domain_text']) : '';
    $sanitized['show_domain'] = !empty($input['show_domain']) ? true : false;

    // Kalbela
    $sanitized['kalbela_bg_color'] = isset($input['kalbela_bg_color']) ? sanitize_hex_color($input['kalbela_bg_color']) : '#cc0000';

    // News24
    $sanitized['news24_bg_color'] = isset($input['news24_bg_color']) ? sanitize_hex_color($input['news24_bg_color']) : '#FFD700';
    $sanitized['news24_text_color'] = isset($input['news24_text_color']) ? sanitize_hex_color($input['news24_text_color']) : '#000000';
    $sanitized['news24_date_bg'] = isset($input['news24_date_bg']) ? sanitize_hex_color($input['news24_date_bg']) : '#1a5fb4';

    return $sanitized;
}

function pcd_settings_page() {
    $capability = pcd_get_settings_capability();
    if (!current_user_can($capability)) {
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
        'download_permission' => 'everyone',
        'settings_access_role' => 'admin',
        'download_button_bg_color' => '#22c55e',
        'download_button_text_color' => '#ffffff',
        'photocard_template' => 'news24',
        'photocard_language' => 'bengali',
        'watermark_logo' => '',
        'logo_position' => 'left',
        'enable_date' => true,
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
        'custom_bg_image' => '',
        'domain_text' => '',
        'show_domain' => true,
        'kalbela_bg_color' => '#cc0000',
        'news24_bg_color' => '#FFD700',
        'news24_text_color' => '#000000',
        'news24_date_bg' => '#1a5fb4',
    );

    $options = wp_parse_args($options, $defaults);

    // Get available templates
    $template_dir = plugin_dir_path(dirname(__FILE__)) . 'includes/templates/';
    $available_templates = array();
    if (is_dir($template_dir)) {
        foreach (glob($template_dir . '*.php') as $file) {
            $name = basename($file, '.php');
            $available_templates[$name] = $name;
        }
    }
    ?>
    <div class="wrap pcd-admin-wrap">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <h1 style="color: white; margin: 0 0 10px 0; font-size: 32px; font-weight: 700;"><?php echo esc_html(get_admin_page_title()); ?></h1>
            <p style="color: rgba(255,255,255,0.9); margin: 0; font-size: 16px;">ফটোকার্ড জেনারেটর প্লাগইনের সেটিংস।</p>
        </div>

        <form method="post" action="options.php" id="pcd-settings-form">
            <?php settings_fields('pcd_settings_group'); ?>

            <!-- Template Selection -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 25px;">
                <h2 class="pcd-section-title" style="color: #667eea; border-bottom: 3px solid #667eea; padding-bottom: 10px; margin-bottom: 20px;">📍 টেমপ্লেট সিলেক্ট করুন</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="photocard_template">ফটোকার্ড টেমপ্লেট</label></th>
                        <td>
                            <select name="pcd_settings[photocard_template]" id="photocard_template" class="regular-text">
                                <option value="news24" <?php selected($options['photocard_template'], 'news24'); ?>>News24 - হলুদ বার স্টাইল</option>
                                <option value="kalbela" <?php selected($options['photocard_template'], 'kalbela'); ?>>কালবেলা - রেড হেডার/ফুটার স্টাইল</option>
                                <option value="prothomalo" <?php selected($options['photocard_template'], 'prothomalo'); ?>>প্রথম আলো - ব্লু অ্যাকসেন্ট ক্লিন স্টাইল</option>
                                <option value="dailystar" <?php selected($options['photocard_template'], 'dailystar'); ?>>Daily Star - ডার্ক নেভি প্রফেশনাল স্টাইল</option>
                                <option value="jugantor" <?php selected($options['photocard_template'], 'jugantor'); ?>>যুগান্তর - গ্রিন অ্যাকসেন্ট স্টাইল</option>
                                <option value="samakal" <?php selected($options['photocard_template'], 'samakal'); ?>>সমকাল - মেরুন এলিগ্যান্ট স্টাইল</option>
                                <option value="dailyshadhin" <?php selected($options['photocard_template'], 'dailyshadhin'); ?>>Daily Shadhin - মডার্ন বটম বার স্টাইল</option>
                                <?php
                                // Auto-detect any additional templates
                                $known = array('news24', 'kalbela', 'prothomalo', 'dailystar', 'jugantor', 'samakal', 'dailyshadhin');
                                foreach ($available_templates as $tpl_key => $tpl_name) {
                                    if (!in_array($tpl_key, $known)) {
                                        echo '<option value="' . esc_attr($tpl_key) . '" ' . selected($options['photocard_template'], $tpl_key, false) . '>' . esc_html(ucfirst($tpl_name)) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <p class="description">includes/templates/ ফোল্ডারে নতুন .php ফাইল রাখলে অটো দেখাবে।</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="photocard_language">ভাষা</label></th>
                        <td>
                            <select name="pcd_settings[photocard_language]" id="photocard_language" class="regular-text">
                                <option value="bengali" <?php selected($options['photocard_language'], 'bengali'); ?>>বাংলা</option>
                                <option value="english" <?php selected($options['photocard_language'], 'english'); ?>>English</option>
                                <option value="hindi" <?php selected($options['photocard_language'], 'hindi'); ?>>हिन्दी</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Background Image Upload -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 25px;">
                <h2 class="pcd-section-title" style="color: #667eea; border-bottom: 3px solid #667eea; padding-bottom: 10px; margin-bottom: 20px;">🖼️ ব্যাকগ্রাউন্ড ইমেজ</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="custom_bg_image">কাস্টম ব্যাকগ্রাউন্ড ইমেজ</label></th>
                        <td>
                            <input type="text" name="pcd_settings[custom_bg_image]" id="custom_bg_image" value="<?php echo esc_url($options['custom_bg_image']); ?>" class="regular-text">
                            <button type="button" class="button pcd-upload-background">ইমেজ আপলোড করুন</button>
                            <p class="description">একটি কাস্টম ব্যাকগ্রাউন্ড/বর্ডার ইমেজ আপলোড করুন। এটি সিলেক্ট করা টেমপ্লেটের ডিফল্ট ব্যাকগ্রাউন্ড ইমেজ রিপ্লেস করবে। (1080x1080 PNG রেকমেন্ডেড)</p>
                            <?php if (!empty($options['custom_bg_image'])): ?>
                                <div class="pcd-background-preview"><img src="<?php echo esc_url($options['custom_bg_image']); ?>" alt="Background Preview"></div>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Domain Name (Daily Shadhin etc.) -->
            <div id="pcd-domain-section" style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 25px; display: none;">
                <h2 class="pcd-section-title" style="color: #667eea; border-bottom: 3px solid #667eea; padding-bottom: 10px; margin-bottom: 20px;">🌐 ডোমেইন/ব্র্যান্ড নাম</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="domain_text">ডোমেইন/ওয়েবসাইট নাম</label></th>
                        <td>
                            <input type="text" name="pcd_settings[domain_text]" id="domain_text" value="<?php echo esc_attr($options['domain_text']); ?>" class="regular-text" placeholder="example.com">
                            <p class="description">Daily Shadhin টেমপ্লেটের বটম বারের মাঝখানে এই নাম দেখাবে।</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">ডোমেইন দেখান</th>
                        <td>
                            <label class="pcd-toggle-label">
                                <input type="checkbox" name="pcd_settings[show_domain]" value="1" <?php checked(isset($options['show_domain']) ? $options['show_domain'] : true, true); ?>>
                                <strong>ডোমেইন নাম দেখান</strong>
                            </label>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Logo & Date -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 25px;">
                <h2 class="pcd-section-title" style="color: #667eea; border-bottom: 3px solid #667eea; padding-bottom: 10px; margin-bottom: 20px;">🏷️ লোগো ও তারিখ</h2>
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
                        <th scope="row">লোগো দেখান</th>
                        <td>
                            <label class="pcd-toggle-label">
                                <input type="checkbox" name="pcd_settings[enable_logo]" value="1" <?php checked($options['enable_logo'], true); ?>>
                                <strong>লোগো দেখান</strong>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">তারিখ দেখান</th>
                        <td>
                            <label class="pcd-toggle-label">
                                <input type="checkbox" name="pcd_settings[enable_date]" value="1" <?php checked($options['enable_date'], true); ?>>
                                <strong>তারিখ দেখান</strong>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="date_position">তারিখ পজিশন</label></th>
                        <td>
                            <select name="pcd_settings[date_position]" id="date_position" class="regular-text">
                                <option value="left" <?php selected($options['date_position'], 'left'); ?>>বাম পাশে</option>
                                <option value="right" <?php selected($options['date_position'], 'right'); ?>>ডান পাশে</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Font & Display -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 25px;">
                <h2 class="pcd-section-title" style="color: #667eea; border-bottom: 3px solid #667eea; padding-bottom: 10px; margin-bottom: 20px;">🎨 ফন্ট ও ডিসপ্লে</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="title_font_family">টাইটেল ফন্ট</label></th>
                        <td>
                            <select name="pcd_settings[title_font_family]" id="title_font_family" class="regular-text" style="font-size: 15px;">
                                <optgroup label="🔤 Google Fonts — বাংলা">
                                    <option value="Noto Sans Bengali" <?php selected($options['title_font_family'], 'Noto Sans Bengali'); ?>>Noto Sans Bengali (ডিফল্ট)</option>
                                    <option value="Hind Siliguri" <?php selected($options['title_font_family'], 'Hind Siliguri'); ?>>Hind Siliguri</option>
                                    <option value="Tiro Bangla" <?php selected($options['title_font_family'], 'Tiro Bangla'); ?>>Tiro Bangla</option>
                                    <option value="Baloo Da 2" <?php selected($options['title_font_family'], 'Baloo Da 2'); ?>>Baloo Da 2 (রাউন্ড ও ফ্রেন্ডলি)</option>
                                    <option value="Galada" <?php selected($options['title_font_family'], 'Galada'); ?>>Galada (ক্যালিগ্রাফি স্টাইল)</option>
                                    <option value="Mina" <?php selected($options['title_font_family'], 'Mina'); ?>>Mina (ক্লিন ও মডার্ন)</option>
                                    <option value="Atma" <?php selected($options['title_font_family'], 'Atma'); ?>>Atma (হ্যান্ডরিটেন স্টাইল)</option>
                                    <option value="Anek Bangla" <?php selected($options['title_font_family'], 'Anek Bangla'); ?>>Anek Bangla (প্রফেশনাল)</option>
                                    <option value="Noto Serif Bengali" <?php selected($options['title_font_family'], 'Noto Serif Bengali'); ?>>Noto Serif Bengali (সেরিফ/ক্লাসিক)</option>
                                    <option value="Mukta" <?php selected($options['title_font_family'], 'Mukta'); ?>>Mukta (লাইটওয়েট)</option>
                                    <option value="Charukola Ultra Light" <?php selected($options['title_font_family'], 'Charukola Ultra Light'); ?>>Charukola Ultra Light (ডেকোরেটিভ)</option>
                                </optgroup>
                                <optgroup label="🖥️ সিস্টেম ফন্ট — বাংলা (সার্ভারে ইনস্টল থাকলে কাজ করবে)">
                                    <option value="SolaimanLipi" <?php selected($options['title_font_family'], 'SolaimanLipi'); ?>>SolaimanLipi (জনপ্রিয় বাংলা)</option>
                                    <option value="Kalpurush" <?php selected($options['title_font_family'], 'Kalpurush'); ?>>Kalpurush (নিউজ স্টাইল)</option>
                                    <option value="Vrinda" <?php selected($options['title_font_family'], 'Vrinda'); ?>>Vrinda (উইন্ডোজ ডিফল্ট বাংলা)</option>
                                    <option value="Nikosh" <?php selected($options['title_font_family'], 'Nikosh'); ?>>Nikosh (সরকারি বাংলা ফন্ট)</option>
                                    <option value="Siyam Rupali" <?php selected($options['title_font_family'], 'Siyam Rupali'); ?>>Siyam Rupali</option>
                                    <option value="Shonar Bangla" <?php selected($options['title_font_family'], 'Shonar Bangla'); ?>>Shonar Bangla</option>
                                    <option value="Bangla" <?php selected($options['title_font_family'], 'Bangla'); ?>>Bangla (macOS ডিফল্ট)</option>
                                    <option value="Li Ador Noirrit" <?php selected($options['title_font_family'], 'Li Ador Noirrit'); ?>>Li Ador Noirrit</option>
                                    <option value="Mukti" <?php selected($options['title_font_family'], 'Mukti'); ?>>Mukti (ওপেন সোর্স বাংলা)</option>
                                    <option value="Ekushey Lohit" <?php selected($options['title_font_family'], 'Ekushey Lohit'); ?>>Ekushey Lohit</option>
                                    <option value="Apona Lohit" <?php selected($options['title_font_family'], 'Apona Lohit'); ?>>Apona Lohit</option>
                                    <option value="Akaash" <?php selected($options['title_font_family'], 'Akaash'); ?>>Akaash</option>
                                    <option value="Lohit Bengali" <?php selected($options['title_font_family'], 'Lohit Bengali'); ?>>Lohit Bengali (Linux ডিফল্ট)</option>
                                </optgroup>
                                <optgroup label="🌐 English ফন্ট">
                                    <option value="Arial" <?php selected($options['title_font_family'], 'Arial'); ?>>Arial</option>
                                    <option value="Georgia" <?php selected($options['title_font_family'], 'Georgia'); ?>>Georgia (Serif)</option>
                                    <option value="Poetsen One" <?php selected($options['title_font_family'], 'Poetsen One'); ?>>Poetsen One (Bold Display)</option>
                                    <option value="Josefin Sans" <?php selected($options['title_font_family'], 'Josefin Sans'); ?>>Josefin Sans (Elegant)</option>
                                    <option value="Blinker" <?php selected($options['title_font_family'], 'Blinker'); ?>>Blinker (Modern)</option>
                                    <option value="Times New Roman" <?php selected($options['title_font_family'], 'Times New Roman'); ?>>Times New Roman (Classic)</option>
                                </optgroup>
                            </select>
                            <p class="description">বাংলা টাইটেলের জন্য সুন্দর ফন্ট সিলেক্ট করুন। সিস্টেম ফন্ট ব্যবহার করতে হলে সার্ভারে বা ইউজারের ডিভাইসে ফন্টটি ইনস্টল থাকতে হবে।</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="default_font_size">ডিফল্ট ফন্ট সাইজ</label></th>
                        <td><input type="number" name="pcd_settings[default_font_size]" id="default_font_size" value="<?php echo esc_attr($options['default_font_size']); ?>" min="20" max="80" class="small-text"> px</td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="default_line_height">লাইন হাইট</label></th>
                        <td><input type="number" name="pcd_settings[default_line_height]" id="default_line_height" value="<?php echo esc_attr($options['default_line_height']); ?>" min="0.8" max="2.5" step="0.1" class="small-text"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="image_quality">ইমেজ কোয়ালিটি</label></th>
                        <td>
                            <select name="pcd_settings[image_quality]" id="image_quality" class="regular-text">
                                <option value="2" <?php selected($options['image_quality'], 2); ?>>Standard (2x)</option>
                                <option value="3" <?php selected($options['image_quality'], 3); ?>>High (3x)</option>
                                <option value="4" <?php selected($options['image_quality'], 4); ?>>Ultra (4x) ✨</option>
                                <option value="5" <?php selected($options['image_quality'], 5); ?>>Super Ultra (5x)</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">বিস্তারিত বাটন</th>
                        <td>
                            <label class="pcd-toggle-label">
                                <input type="checkbox" name="pcd_settings[show_details_button]" value="1" <?php checked($options['show_details_button'], true); ?>>
                                <strong>বাটন দেখান</strong>
                            </label>
                            <br><br>
                            <input type="text" name="pcd_settings[details_button_text]" value="<?php echo esc_attr($options['details_button_text']); ?>" class="regular-text" placeholder="বাটন টেক্সট">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="title_top_offset">টাইটেল পজিশন অফসেট (px)</label></th>
                        <td>
                            <input type="number" name="pcd_settings[title_top_offset]" id="title_top_offset" value="<?php echo esc_attr($options['title_top_offset']); ?>" min="-200" max="200" class="small-text"> px
                            <p class="description">ধনাত্মক মান = নিচে, ঋণাত্মক মান = উপরে। ডিফল্ট: 0</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="details_bottom_offset">বিস্তারিত পজিশন অফসেট (px)</label></th>
                        <td>
                            <input type="number" name="pcd_settings[details_bottom_offset]" id="details_bottom_offset" value="<?php echo esc_attr($options['details_bottom_offset']); ?>" min="-200" max="200" class="small-text"> px
                            <p class="description">ধনাত্মক মান = নিচে, ঋণাত্মক মান = উপরে। ডিফল্ট: 0</p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Download Button -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 25px;">
                <h2 class="pcd-section-title" style="color: #667eea; border-bottom: 3px solid #667eea; padding-bottom: 10px; margin-bottom: 20px;">⬇️ ডাউনলোড বাটন</h2>
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
                        <th scope="row"><label for="download_button_bg_color">বাটন ব্যাকগ্রাউন্ড কালার</label></th>
                        <td><input type="text" name="pcd_settings[download_button_bg_color]" id="download_button_bg_color" value="<?php echo esc_attr($options['download_button_bg_color']); ?>" class="pcd-color-picker"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="download_button_text_color">বাটন টেক্সট কালার</label></th>
                        <td><input type="text" name="pcd_settings[download_button_text_color]" id="download_button_text_color" value="<?php echo esc_attr($options['download_button_text_color']); ?>" class="pcd-color-picker"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="download_permission">ডাউনলোড পারমিশন</label></th>
                        <td>
                            <select name="pcd_settings[download_permission]" id="download_permission" class="regular-text">
                                <option value="everyone" <?php selected($options['download_permission'], 'everyone'); ?>>সবাই</option>
                                <option value="logged_in" <?php selected($options['download_permission'], 'logged_in'); ?>>লগইন ইউজার</option>
                                <option value="author" <?php selected($options['download_permission'], 'author'); ?>>Author+</option>
                                <option value="editor" <?php selected($options['download_permission'], 'editor'); ?>>Editor+</option>
                                <option value="admin" <?php selected($options['download_permission'], 'admin'); ?>>Admin</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="settings_access_role">সেটিংস অ্যাক্সেস রোল</label></th>
                        <td>
                            <select name="pcd_settings[settings_access_role]" id="settings_access_role" class="regular-text" <?php echo !current_user_can('manage_options') ? 'disabled' : ''; ?>>
                                <option value="admin" <?php selected($options['settings_access_role'], 'admin'); ?>>Admin — শুধু অ্যাডমিন</option>
                                <option value="editor" <?php selected($options['settings_access_role'], 'editor'); ?>>Editor+ — এডিটর ও তার উপরে</option>
                                <option value="author" <?php selected($options['settings_access_role'], 'author'); ?>>Author+ — অথর ও তার উপরে</option>
                            </select>
                            <p class="description">কোন রোলের ইউজাররা ফটোকার্ড সেটিংস পেজ অ্যাক্সেস করতে পারবে। শুধু Admin এই সেটিং পরিবর্তন করতে পারবে।</p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Template Colors -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 25px;">
                <h2 class="pcd-section-title" style="color: #667eea; border-bottom: 3px solid #667eea; padding-bottom: 10px; margin-bottom: 20px;">🎨 টেমপ্লেট কালার কাস্টমাইজ</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row" colspan="2"><strong>— কালবেলা টেমপ্লেট —</strong></th>
                    </tr>
                    <tr>
                        <th scope="row"><label for="kalbela_bg_color">হেডার/ফুটার কালার</label></th>
                        <td><input type="text" name="pcd_settings[kalbela_bg_color]" id="kalbela_bg_color" value="<?php echo esc_attr($options['kalbela_bg_color']); ?>" class="pcd-color-picker"></td>
                    </tr>
                    <tr>
                        <th scope="row" colspan="2"><strong>— News24 টেমপ্লেট —</strong></th>
                    </tr>
                    <tr>
                        <th scope="row"><label for="news24_bg_color">টাইটেল বার কালার</label></th>
                        <td><input type="text" name="pcd_settings[news24_bg_color]" id="news24_bg_color" value="<?php echo esc_attr($options['news24_bg_color']); ?>" class="pcd-color-picker"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="news24_text_color">টাইটেল টেক্সট কালার</label></th>
                        <td><input type="text" name="pcd_settings[news24_text_color]" id="news24_text_color" value="<?php echo esc_attr($options['news24_text_color']); ?>" class="pcd-color-picker"></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="news24_date_bg">তারিখ ব্যাজ কালার</label></th>
                        <td><input type="text" name="pcd_settings[news24_date_bg]" id="news24_date_bg" value="<?php echo esc_attr($options['news24_date_bg']); ?>" class="pcd-color-picker"></td>
                    </tr>
                </table>
            </div>

            <!-- Social Media -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 25px;">
                <h2 class="pcd-section-title" style="color: #667eea; border-bottom: 3px solid #667eea; padding-bottom: 10px; margin-bottom: 20px;">📱 সোশ্যাল মিডিয়া</h2>
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
                            <input type="text" name="pcd_settings[<?php echo $social_key; ?>_text]" value="<?php echo esc_attr($options[$social_key . '_text']); ?>" class="regular-text" placeholder="প্রদর্শন নাম">
                            <br>
                            <label class="pcd-toggle-label" style="margin-top: 5px; display: inline-block;">
                                <input type="checkbox" name="pcd_settings[show_<?php echo $social_key; ?>]" value="1" <?php checked($options['show_' . $social_key], true); ?>>
                                <strong>দেখান</strong>
                            </label>
                        </td>
                    </tr>
                    <?php endforeach; ?>
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

        // Template-specific sections visibility
        function updateTemplateSections() {
            var tpl = $('#photocard_template').val();
            // Show domain section only for dailyshadhin
            if (tpl === 'dailyshadhin') {
                $('#pcd-domain-section').show();
            } else {
                $('#pcd-domain-section').hide();
            }
        }
        updateTemplateSections();
        $('#photocard_template').on('change', updateTemplateSections);
    });
    </script>
    <?php
}
?>

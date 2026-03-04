<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('template_redirect', 'pcd_handle_editor_page');

function pcd_handle_editor_page() {
    if (isset($_GET['pcd_editor']) && $_GET['pcd_editor'] == '1' && isset($_GET['post_id'])) {
        // FIX: Check permission before loading editor page
        if (!pcd_can_user_download()) {
            wp_die('আপনার এই ফটোকার্ড দেখার অনুমতি নেই। প্রয়োজনীয় অনুমতি পেতে অ্যাডমিনের সাথে যোগাযোগ করুন।', 'অ্যাক্সেস নিষেধ', array('response' => 403));
        }
        pcd_load_editor_template();
        exit;
    }
}

function pcd_load_editor_template() {
    $post_id = intval($_GET['post_id']);
    $post = get_post($post_id);

    if (!$post || !has_post_thumbnail($post_id)) {
        wp_die('Invalid post or no thumbnail found.');
    }

    $options = get_option('pcd_settings');
    $post_title = get_the_title($post_id);
    $post_date = get_the_date('', $post_id);
    $current_date_obj = get_the_date('Y-m-d', $post_id);
    $day_of_week = date_i18n('l', strtotime($current_date_obj));
    $current_date = date_i18n('d F Y', strtotime($current_date_obj));
    $thumbnail_url = get_the_post_thumbnail_url($post_id, 'large');
    $post_permalink = get_permalink($post_id);

    $template = isset($options['photocard_template']) ? $options['photocard_template'] : 'custom';
    $language = isset($options['photocard_language']) ? $options['photocard_language'] : 'bengali';

    $thumbnail_border_radius_top_left = isset($options['thumbnail_border_radius_top_left']) ? $options['thumbnail_border_radius_top_left'] : 5;
    $thumbnail_border_radius_top_right = isset($options['thumbnail_border_radius_top_right']) ? $options['thumbnail_border_radius_top_right'] : 5;
    $thumbnail_border_radius_bottom_left = isset($options['thumbnail_border_radius_bottom_left']) ? $options['thumbnail_border_radius_bottom_left'] : 5;
    $thumbnail_border_radius_bottom_right = isset($options['thumbnail_border_radius_bottom_right']) ? $options['thumbnail_border_radius_bottom_right'] : 5;

    $thumbnail_border_width_top = isset($options['thumbnail_border_width_top']) ? $options['thumbnail_border_width_top'] : 3;
    $thumbnail_border_width_right = isset($options['thumbnail_border_width_right']) ? $options['thumbnail_border_width_right'] : 3;
    $thumbnail_border_width_bottom = isset($options['thumbnail_border_width_bottom']) ? $options['thumbnail_border_width_bottom'] : 3;
    $thumbnail_border_width_left = isset($options['thumbnail_border_width_left']) ? $options['thumbnail_border_width_left'] : 3;
    $thumbnail_border_color = isset($options['thumbnail_border_color']) ? $options['thumbnail_border_color'] : '#ffffff';

    $thumbnail_shadow = isset($options['thumbnail_shadow']) ? $options['thumbnail_shadow'] : 'medium';
    $card_padding = isset($options['card_padding']) ? $options['card_padding'] : 0;
    $image_quality = isset($options['image_quality']) ? $options['image_quality'] : 4;

    $thumbnail_padding = 30;

    $title_text_color = isset($options['title_text_color']) ? $options['title_text_color'] : '#000000';
    $title_background_color = isset($options['title_background_color']) ? $options['title_background_color'] : 'transparent';

    $title_font_family = isset($options['title_font_family']) && !empty($options['title_font_family']) ? $options['title_font_family'] : 'Noto Sans Bengali';

    $title_border_radius = isset($options['title_border_radius']) ? $options['title_border_radius'] : 6;

    $shadow_styles = array(
        'none' => 'none',
        'light' => '0 2px 8px rgba(0,0,0,0.1)',
        'medium' => '0 6px 15px rgba(0,0,0,0.15)',
        'heavy' => '0 10px 25px rgba(0,0,0,0.25)'
    );
    $thumbnail_shadow_style = isset($shadow_styles[$thumbnail_shadow]) ? $shadow_styles[$thumbnail_shadow] : $shadow_styles['medium'];

    $template_defaults = pcd_get_template_defaults($template);

    if ($template !== 'custom') {
        $frame_color = $template_defaults['frame_color'];
        $text_color = $template_defaults['text_color'];
        $bg_color = $template_defaults['background_color'];
        $button_color = $template_defaults['button_color'];
        $button_text_color = $template_defaults['button_text_color'];
        $gradient_color_1 = $template_defaults['gradient_color_1'];
        $gradient_color_2 = $template_defaults['gradient_color_2'];
        $enable_gradient = isset($template_defaults['enable_gradient']) ? $template_defaults['enable_gradient'] : false;
    } else {
        $frame_color = isset($options['frame_color']) ? $options['frame_color'] : $template_defaults['frame_color'];
        $text_color = isset($options['text_color']) ? $options['text_color'] : $template_defaults['text_color'];
        $bg_color = isset($options['background_color']) ? $options['background_color'] : $template_defaults['background_color'];
        $button_color = isset($options['button_color']) ? $options['button_color'] : $template_defaults['button_color'];
        $button_text_color = isset($options['button_text_color']) ? $options['button_text_color'] : $template_defaults['button_text_color'];
        $gradient_color_1 = isset($options['gradient_color_1']) ? $options['gradient_color_1'] : $template_defaults['gradient_color_1'];
        $gradient_color_2 = isset($options['gradient_color_2']) ? $options['gradient_color_2'] : $template_defaults['gradient_color_2'];
        $enable_gradient = isset($options['enable_gradient']) ? $options['enable_gradient'] : false;
    }

    $watermark_text = isset($options['watermark_text']) ? $options['watermark_text'] : '';
    $watermark_logo = isset($options['watermark_logo']) ? $options['watermark_logo'] : '';
    $background_image = isset($options['background_image']) ? $options['background_image'] : '';
    $enable_date = isset($options['enable_date']) ? $options['enable_date'] : true;
    $enable_logo = isset($options['enable_logo']) ? $options['enable_logo'] : true;
    $show_details_button = isset($options['show_details_button']) ? $options['show_details_button'] : true;
    $details_button_text = isset($options['details_button_text']) ? $options['details_button_text'] : 'বিস্তারিত কমেন্ট';
    $default_font_size = isset($options['default_font_size']) ? $options['default_font_size'] : 42;
    $default_line_height = isset($options['default_line_height']) ? $options['default_line_height'] : 1.3;

    $show_border = isset($options['show_border']) ? $options['show_border'] : true;
    $gradient_direction = isset($options['gradient_direction']) ? $options['gradient_direction'] : 'to right';
    $logo_position = isset($options['logo_position']) ? $options['logo_position'] : 'left';
    $custom_css = isset($options['custom_css']) ? $options['custom_css'] : '';

    $facebook_link = isset($options['facebook_link']) ? $options['facebook_link'] : 'https://facebook.com/hostercube';
    $facebook_text = isset($options['facebook_text']) ? $options['facebook_text'] : '/hostercube';
    $show_facebook = isset($options['show_facebook']) ? $options['show_facebook'] : false;

    $instagram_link = isset($options['instagram_link']) ? $options['instagram_link'] : '';
    $instagram_text = isset($options['instagram_text']) ? $options['instagram_text'] : '';
    $show_instagram = isset($options['show_instagram']) ? $options['show_instagram'] : false;

    $youtube_link = isset($options['youtube_link']) ? $options['youtube_link'] : '';
    $youtube_text = isset($options['youtube_text']) ? $options['youtube_text'] : '';
    $show_youtube = isset($options['show_youtube']) ? $options['show_youtube'] : false;

    $linkedin_link = isset($options['linkedin_link']) ? $options['linkedin_link'] : '';
    $linkedin_text = isset($options['linkedin_text']) ? $options['linkedin_text'] : '';
    $show_linkedin = isset($options['show_linkedin']) ? $options['show_linkedin'] : false;

    $website_link = isset($options['website_link']) ? $options['website_link'] : 'https://hostercube.com';
    $website_text = isset($options['website_text']) ? $options['website_text'] : 'hostercube.com';
    $show_website = isset($options['show_website']) ? $options['show_website'] : false;

    $enable_ad_thumbnail_top = isset($options['enable_ad_thumbnail_top']) ? $options['enable_ad_thumbnail_top'] : false;
    $ad_image_thumbnail_top = isset($options['ad_image_thumbnail_top']) ? $options['ad_image_thumbnail_top'] : '';

    $enable_ad_thumbnail_bottom = isset($options['enable_ad_thumbnail_bottom']) ? $options['enable_ad_thumbnail_bottom'] : false;
    $ad_image_thumbnail_bottom = isset($options['ad_image_thumbnail_bottom']) ? $options['ad_image_thumbnail_bottom'] : '';

    $enable_ad_thumbnail_left = isset($options['enable_ad_thumbnail_left']) ? $options['enable_ad_thumbnail_left'] : false;
    $ad_image_thumbnail_left = isset($options['ad_image_thumbnail_left']) ? $options['ad_image_thumbnail_left'] : '';

    $enable_ad_thumbnail_right = isset($options['enable_ad_thumbnail_right']) ? $options['enable_ad_thumbnail_right'] : false;
    $ad_image_thumbnail_right = isset($options['ad_image_thumbnail_right']) ? $options['ad_image_thumbnail_right'] : '';

    $enable_ad_social_bottom = isset($options['enable_ad_social_bottom']) ? $options['enable_ad_social_bottom'] : false;
    $ad_image_social_bottom = isset($options['ad_image_social_bottom']) ? $options['ad_image_social_bottom'] : '';

    $enable_ad_top = isset($options['enable_ad_top']) ? $options['enable_ad_top'] : false;
    $ad_code_top = isset($options['ad_code_top']) ? $options['ad_code_top'] : '';

    $enable_ad_above_image = isset($options['enable_ad_above_image']) ? $options['enable_ad_above_image'] : false;
    $ad_code_above_image = isset($options['ad_code_above_image']) ? $options['ad_code_above_image'] : '';

    $enable_ad_below_image = isset($options['enable_ad_below_image']) ? $options['enable_ad_below_image'] : false;
    $ad_code_below_image = isset($options['ad_code_below_image']) ? $options['ad_code_below_image'] : '';

    $enable_ad_above_social = isset($options['enable_ad_above_social']) ? $options['enable_ad_above_social'] : false;
    $ad_code_above_social = isset($options['ad_code_above_social']) ? $options['ad_code_above_social'] : '';

    $enable_ad_below_social = isset($options['enable_ad_below_social']) ? $options['enable_ad_below_social'] : false;
    $ad_code_below_social = isset($options['ad_code_below_social']) ? $options['ad_code_below_social'] : '';

    $enable_ad_bottom = isset($options['enable_ad_bottom']) ? $options['enable_ad_bottom'] : false;
    $ad_code_bottom = isset($options['ad_code_bottom']) ? $options['ad_code_bottom'] : '';

    $formatted_date = pcd_format_date_by_language($current_date, $day_of_week, $language);

    // FIX: News24 template uses gradient by default
    if ($template === 'news24') {
        $background_style = "background: linear-gradient(to bottom, {$gradient_color_1}, {$gradient_color_2});";
    } elseif ($template === 'custom' && $enable_gradient) {
        $background_style = "background: linear-gradient({$gradient_direction}, {$gradient_color_1}, {$gradient_color_2});";
    } else {
        $background_style = "background: {$bg_color};";
    }

    $template_class = 'pcd-template-' . $template;

    $image_container_style = "flex: 1; display: flex; align-items: center; justify-content: center; overflow: hidden;";
    $image_container_style .= " box-shadow: {$thumbnail_shadow_style};";
    $image_container_style .= " border-radius: {$thumbnail_border_radius_top_left}px {$thumbnail_border_radius_top_right}px {$thumbnail_border_radius_bottom_right}px {$thumbnail_border_radius_bottom_left}px;";
    $image_container_style .= " background: white;";
    $image_container_style .= " padding: {$thumbnail_padding}px;";
    $image_container_style .= " min-height: 0;";

    if ($thumbnail_border_width_top > 0 || $thumbnail_border_width_right > 0 || $thumbnail_border_width_bottom > 0 || $thumbnail_border_width_left > 0) {
        $image_container_style .= " border-style: solid;";
        $image_container_style .= " border-color: {$thumbnail_border_color};";
        $image_container_style .= " border-top-width: {$thumbnail_border_width_top}px;";
        $image_container_style .= " border-right-width: {$thumbnail_border_width_right}px;";
        $image_container_style .= " border-bottom-width: {$thumbnail_border_width_bottom}px;";
        $image_container_style .= " border-left-width: {$thumbnail_border_width_left}px;";
    }
    $image_container_style .= " box-sizing: border-box;";

    $main_image_style = "max-width: 100%; max-height: 100%; width: 100%; height: 100%; display: block; object-fit: cover;";
    $main_image_style .= " border-radius: " . max(0, $thumbnail_border_radius_top_left - $thumbnail_padding) . "px ";
    $main_image_style .= max(0, $thumbnail_border_radius_top_right - $thumbnail_padding) . "px ";
    $main_image_style .= max(0, $thumbnail_border_radius_bottom_right - $thumbnail_padding) . "px ";
    $main_image_style .= max(0, $thumbnail_border_radius_bottom_left - $thumbnail_padding) . "px;";

    // News24 template overrides
    $news24_title_style = '';
    if ($template === 'news24') {
        // News24 specific: large bold yellow title on dark gradient
        $title_text_color = '#FFD700';
        $title_background_color = 'rgba(0,0,0,0.7)';
        $frame_color = '#c41e3a';
        $text_color = '#ffffff';
        $title_border_radius = 0;
        $news24_title_style = 'text-shadow: 2px 2px 4px rgba(0,0,0,0.8);';
    }

    ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Automatic Photocard - <?php echo esc_html($post_title); ?></title>
        <!-- FIX: Load ALL Google Fonts properly for editor page -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700;800;900&family=Hind+Siliguri:wght@400;500;600;700&family=Tiro+Bangla:wght@400;700&display=swap" rel="stylesheet">
        <?php if (!empty($custom_css)): ?>
        <style>
            /* FIX: Sanitize custom CSS output */
            <?php echo wp_strip_all_tags($custom_css); ?>
        </style>
        <?php endif; ?>
        <?php wp_head(); ?>
    </head>
    <body class="pcd-editor-page">
        <div class="pcd-editor-container">
            <h1 class="pcd-editor-title">Automatic Photocard</h1>

            <div class="pcd-editor-content">
                <div id="pcd-photocard-preview" class="pcd-photocard-wrapper">
                    <div class="pcd-photocard-with-border <?php echo esc_attr($template_class); ?>" style="position: relative; width: 1080px; height: 1080px;">
                        <?php if ($show_border && !empty($background_image)): ?>
                            <img src="<?php echo esc_url($background_image); ?>" alt="Background" class="pcd-border-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: -1;" crossorigin="anonymous">
                        <?php endif; ?>

                        <div class="pcd-photocard" data-language="<?php echo esc_attr($language); ?>" data-quality="<?php echo esc_attr($image_quality); ?>" style="width: 1080px; height: 1080px; <?php echo $background_style; ?> padding: <?php echo esc_attr($card_padding); ?>px; border-radius: 0; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.2); display: flex; flex-direction: column; box-sizing: border-box;">

                            <!-- Ad position: Top -->
                            <?php if ($enable_ad_top && !empty($ad_code_top)): ?>
                                <div class="pcd-ad-section pcd-ad-top" style="margin-bottom: 10px; padding: 8px; background: rgba(255,255,255,0.03); border-radius: 6px; text-align: center; flex-shrink: 0;">
                                    <?php echo wp_kses_post($ad_code_top); ?>
                                </div>
                            <?php endif; ?>

                            <!-- Header section -->
                            <?php if ($logo_position === 'center'): ?>
                                <div class="pcd-header" style="display: flex; flex-direction: column; align-items: center; gap: 8px; margin-bottom: 0px; padding: 0px 20px 0 20px; flex-shrink: 0; position: relative; z-index: 2;">
                                    <?php if ($enable_logo && !empty($watermark_logo)): ?>
                                        <div class="pcd-logo" style="flex-shrink: 0;">
                                            <img src="<?php echo esc_url($watermark_logo); ?>" alt="Logo" style="height: 75px; width: auto; display: block;" crossorigin="anonymous">
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($enable_date): ?>
                                        <div class="pcd-date" style="color: <?php echo esc_attr($text_color); ?>; font-size: 22px; font-weight: 600; text-align: center;">
                                            <?php echo esc_html($formatted_date); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="pcd-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0px; padding: 0px 20px 0 20px; gap: 12px; flex-shrink: 0; position: relative; z-index: 2;">
                                    <?php if ($logo_position === 'left'): ?>
                                        <?php if ($enable_logo && !empty($watermark_logo)): ?>
                                            <div class="pcd-logo" style="flex-shrink: 0;">
                                                <img src="<?php echo esc_url($watermark_logo); ?>" alt="Logo" style="height: 75px; width: auto; display: block;" crossorigin="anonymous">
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($enable_date): ?>
                                            <div class="pcd-date" style="color: <?php echo esc_attr($text_color); ?>; font-size: 22px; font-weight: 600; text-align: right; flex-shrink: 0;">
                                                <?php echo esc_html($formatted_date); ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if ($enable_date): ?>
                                            <div class="pcd-date" style="color: <?php echo esc_attr($text_color); ?>; font-size: 22px; font-weight: 600; text-align: left; flex-shrink: 0;">
                                                <?php echo esc_html($formatted_date); ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($enable_logo && !empty($watermark_logo)): ?>
                                            <div class="pcd-logo" style="flex-shrink: 0;">
                                                <img src="<?php echo esc_url($watermark_logo); ?>" alt="Logo" style="height: 75px; width: auto; display: block;" crossorigin="anonymous">
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Ad position: Above Image -->
                            <?php if ($enable_ad_above_image && !empty($ad_code_above_image)): ?>
                                <div class="pcd-ad-section pcd-ad-above-image" style="margin-bottom: 10px; padding: 8px; background: rgba(255,255,255,0.03); border-radius: 6px; text-align: center; flex-shrink: 0;">
                                    <?php echo wp_kses_post($ad_code_above_image); ?>
                                </div>
                            <?php endif; ?>

                            <!-- Ad position: Thumbnail Top -->
                            <?php if ($enable_ad_thumbnail_top && !empty($ad_image_thumbnail_top)): ?>
                                <div class="pcd-ad-section pcd-ad-thumbnail-top" style="margin-bottom: 8px; text-align: center; flex-shrink: 0;">
                                    <img src="<?php echo esc_url($ad_image_thumbnail_top); ?>" alt="Advertisement" style="max-width: 100%; height: auto; border-radius: 8px;" crossorigin="anonymous">
                                </div>
                            <?php endif; ?>

                            <!-- Image container -->
                            <div style="flex: 1; display: flex; align-items: stretch; justify-content: center; margin: 0; gap: 8px; min-height: 0; max-height: 650px; padding: 0; position: relative; z-index: 1;">
                                <?php if ($enable_ad_thumbnail_left && !empty($ad_image_thumbnail_left)): ?>
                                    <div class="pcd-ad-section pcd-ad-thumbnail-left" style="flex-shrink: 0; display: flex; align-items: center;">
                                        <img src="<?php echo esc_url($ad_image_thumbnail_left); ?>" alt="Advertisement" style="max-height: 100%; width: auto; max-width: 90px; border-radius: 8px; object-fit: contain;" crossorigin="anonymous">
                                    </div>
                                <?php endif; ?>

                                <div class="pcd-image" style="<?php echo esc_attr($image_container_style); ?> z-index: 1;">
                                    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="<?php echo esc_attr($main_image_style); ?>" crossorigin="anonymous">
                                </div>

                                <?php if ($enable_ad_thumbnail_right && !empty($ad_image_thumbnail_right)): ?>
                                    <div class="pcd-ad-section pcd-ad-thumbnail-right" style="flex-shrink: 0; display: flex; align-items: center;">
                                        <img src="<?php echo esc_url($ad_image_thumbnail_right); ?>" alt="Advertisement" style="max-height: 100%; width: auto; max-width: 90px; border-radius: 8px; object-fit: contain;" crossorigin="anonymous">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Ad position: Thumbnail Bottom -->
                            <?php if ($enable_ad_thumbnail_bottom && !empty($ad_image_thumbnail_bottom)): ?>
                                <div class="pcd-ad-section pcd-ad-thumbnail-bottom" style="margin-top: 8px; text-align: center; flex-shrink: 0;">
                                    <img src="<?php echo esc_url($ad_image_thumbnail_bottom); ?>" alt="Advertisement" style="max-width: 100%; height: auto; border-radius: 8px;" crossorigin="anonymous">
                                </div>
                            <?php endif; ?>

                            <!-- Ad position: Below Image -->
                            <?php if ($enable_ad_below_image && !empty($ad_code_below_image)): ?>
                                <div class="pcd-ad-section pcd-ad-below-image" style="margin-top: 10px; padding: 8px; background: rgba(255,255,255,0.03); border-radius: 6px; text-align: center; flex-shrink: 0;">
                                    <?php echo wp_kses_post($ad_code_below_image); ?>
                                </div>
                            <?php endif; ?>

                            <!-- Title section -->
                            <div style="flex-shrink: 0; display: flex; flex-direction: column; gap: 8px; margin-top: 8px; position: relative; z-index: 2;">
                                <div id="pcd-adjustable-title" class="pcd-title" style="color: <?php echo esc_attr($title_text_color); ?>; background: <?php echo esc_attr($title_background_color); ?>; font-size: <?php echo esc_attr($default_font_size); ?>px; line-height: <?php echo esc_attr($default_line_height); ?>; font-weight: bold; text-align: center; padding: 7px 8px; border-radius: <?php echo esc_attr($title_border_radius); ?>px; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', 'Hind Siliguri', Arial, sans-serif; word-wrap: break-word; overflow-wrap: break-word; <?php echo $news24_title_style; ?>">
                                    <?php echo esc_html($post_title); ?>
                                </div>

                                <?php if ($show_details_button): ?>
                                <div class="pcd-footer-button" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                    <div style="flex: 1; height: 2px; background: <?php echo esc_attr($button_color); ?>; border-radius: 1px;"></div>
                                    <div style="background: <?php echo esc_attr($button_color); ?>; color: <?php echo esc_attr($button_text_color); ?>; padding: 7px 22px; border-radius: 25px; font-weight: bold; font-size: 20px; white-space: nowrap; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                                        <?php echo esc_html($details_button_text); ?>
                                    </div>
                                    <div style="flex: 1; height: 2px; background: <?php echo esc_attr($button_color); ?>; border-radius: 1px;"></div>
                                </div>
                                <?php endif; ?>

                                <div class="pcd-bottom-dots" style="display: flex; justify-content: center; gap: 4px;">
                                    <?php for ($i = 0; $i < 7; $i++): ?>
                                        <div style="width: 5px; height: 5px; background: <?php echo esc_attr($frame_color); ?>; border-radius: 50%;"></div>
                                    <?php endfor; ?>
                                </div>

                                <!-- Ad position: Above Social -->
                                <?php if ($enable_ad_above_social && !empty($ad_code_above_social)): ?>
                                    <div class="pcd-ad-section pcd-ad-above-social" style="margin-top: 8px; padding: 8px; background: rgba(255,255,255,0.03); border-radius: 6px; text-align: center;">
                                        <?php echo wp_kses_post($ad_code_above_social); ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Social media section -->
                                <?php if ($show_facebook || $show_instagram || $show_youtube || $show_linkedin || $show_website): ?>
                                    <div class="pcd-social-links" style="background: <?php echo esc_attr($frame_color); ?>; padding: 8px 10px; border-radius: 8px; display: flex; justify-content: center; align-items: center; gap: 10px; flex-wrap: wrap;">
                                        <?php if ($show_facebook && !empty($facebook_link)): ?>
                                            <div class="pcd-social-item" style="display: flex; align-items: center; gap: 3px; color: white; font-size: 16px; font-weight: 500;">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="white" style="flex-shrink: 0;">
                                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                </svg>
                                                <span><?php echo esc_html($facebook_text); ?></span>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($show_instagram && !empty($instagram_link)): ?>
                                            <div class="pcd-social-item" style="display: flex; align-items: center; gap: 3px; color: white; font-size: 16px; font-weight: 500;">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="white" style="flex-shrink: 0;">
                                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.849-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                                </svg>
                                                <span><?php echo esc_html($instagram_text); ?></span>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($show_youtube && !empty($youtube_link)): ?>
                                            <div class="pcd-social-item" style="display: flex; align-items: center; gap: 3px; color: white; font-size: 16px; font-weight: 500;">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="white" style="flex-shrink: 0;">
                                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                                </svg>
                                                <span><?php echo esc_html($youtube_text); ?></span>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($show_linkedin && !empty($linkedin_link)): ?>
                                            <div class="pcd-social-item" style="display: flex; align-items: center; gap: 3px; color: white; font-size: 16px; font-weight: 500;">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="white" style="flex-shrink: 0;">
                                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                                </svg>
                                                <span><?php echo esc_html($linkedin_text); ?></span>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($show_website && !empty($website_link)): ?>
                                            <div class="pcd-social-item" style="display: flex; align-items: center; gap: 3px; color: white; font-size: 16px; font-weight: 500;">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="white" style="flex-shrink: 0;">
                                                    <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zm6.93 6h-2.95c-.32-1.25-.78-2.45-1.38-3.56 1.84.63 3.37 1.91 4.33 3.56zM12 4.04c.83 1.2 1.48 2.53 1.91 3.96h-3.82c.43-1.43 1.08-2.76 1.91-3.96zM4.26 14C4.1 13.36 4 12.69 4 12s.1-1.36.26-2h3.38c-.08.66-.14 1.32-.14 2 0 .68.06 1.34.14 2H4.26zm.82 2h2.95c.32 1.25.78 2.45 1.38 3.56-1.84-.63-3.37-1.9-4.33-3.56zm2.95-8H5.08c.96-1.66 2.49-2.93 4.33-3.56C8.81 5.55 8.35 6.75 8.03 8zM12 19.96c-.83-1.2-1.48-2.53-1.91-3.96h3.82c-.43 1.43-1.08 2.76-1.91 3.96zM14.34 14H9.66c-.09-.66-.16-1.32-.16-2 0-.68.07-1.35.16-2h4.68c.09.65.16 1.32.16 2 0 .68-.07 1.34-.16 2zm.25 5.56c.6-1.11 1.06-2.31 1.38-3.56h2.95c-.96 1.65-2.49 2.93-4.33 3.56zM16.36 14c.08-.66.14-1.32.14-2 0-.68-.06-1.34-.14-2h3.38c.16.64.26 1.31.26 2s-.1 1.36-.26 2h-3.38z"/>
                                                </svg>
                                                <span><?php echo esc_html($website_text); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Ad position: Social Media Bottom -->
                                <?php if ($enable_ad_social_bottom && !empty($ad_image_social_bottom)): ?>
                                    <div class="pcd-ad-section pcd-ad-social-bottom" style="margin-top: 8px; text-align: center;">
                                        <img src="<?php echo esc_url($ad_image_social_bottom); ?>" alt="Advertisement" style="max-width: 100%; height: auto; border-radius: 8px;" crossorigin="anonymous">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pcd-editor-controls">
                    <div class="pcd-control-group pcd-share-section">
                        <label>শেয়ার এবং কপি করুন</label>
                        <div class="pcd-share-buttons">
                            <button id="pcd-copy-link-button" class="pcd-btn pcd-btn-icon pcd-btn-sm" title="লিংক কপি করুন">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
                                </svg>
                            </button>

                            <button id="pcd-share-facebook" class="pcd-btn pcd-btn-icon pcd-btn-sm pcd-btn-facebook" title="Facebook এ শেয়ার করুন">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </button>

                            <button id="pcd-share-instagram" class="pcd-btn pcd-btn-icon pcd-btn-sm pcd-btn-instagram" title="Instagram এ শেয়ার করুন">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.849-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </button>

                            <button id="pcd-share-twitter" class="pcd-btn pcd-btn-icon pcd-btn-sm pcd-btn-twitter" title="Twitter এ শেয়ার করুন">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </button>

                            <button id="pcd-share-linkedin" class="pcd-btn pcd-btn-icon pcd-btn-sm pcd-btn-linkedin" title="LinkedIn এ শেয়ার করুন">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </button>

                            <button id="pcd-share-whatsapp" class="pcd-btn pcd-btn-icon pcd-btn-sm pcd-btn-whatsapp" title="WhatsApp এ শেয়ার করুন">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- FIX: NEW Title Editor Section -->
                    <div class="pcd-control-group">
                        <label for="pcd-title-editor">টাইটেল এডিট করুন</label>
                        <textarea id="pcd-title-editor" rows="3" style="width: 100%; font-size: 14px; padding: 8px; border: 1px solid #ddd; border-radius: 6px; resize: vertical;"><?php echo esc_textarea($post_title); ?></textarea>
                    </div>

                    <!-- FIX: NEW Title Color per line -->
                    <div class="pcd-control-group">
                        <label>লাইন অনুযায়ী টাইটেল কালার</label>
                        <div id="pcd-line-colors-container" style="display: flex; flex-direction: column; gap: 6px;">
                            <!-- Line color inputs will be generated by JS -->
                        </div>
                        <button type="button" id="pcd-apply-line-colors" class="pcd-btn pcd-btn-secondary" style="margin-top: 8px; font-size: 12px;">কালার প্রয়োগ করুন</button>
                    </div>

                    <div class="pcd-control-group">
                        <label for="pcd-font-size-slider">ফন্ট সাইজ এডজাস্ট করুন</label>
                        <input type="range" id="pcd-font-size-slider" min="16" max="60" value="<?php echo esc_attr($default_font_size); ?>" step="1">
                        <span id="pcd-font-size-value"><?php echo esc_html($default_font_size); ?>px</span>
                    </div>

                    <div class="pcd-control-group">
                        <label for="pcd-line-height-slider">লাইন হাইট এডজাস্ট করুন</label>
                        <input type="range" id="pcd-line-height-slider" min="1" max="2.5" value="<?php echo esc_attr($default_line_height); ?>" step="0.1">
                        <span id="pcd-line-height-value"><?php echo esc_html($default_line_height); ?></span>
                    </div>

                    <div class="pcd-editor-actions">
                        <button id="pcd-back-button" class="pcd-btn pcd-btn-secondary" onclick="window.history.back()">Back</button>
                        <button id="pcd-download-button" class="pcd-btn pcd-btn-primary">Download</button>
                    </div>

                    <div class="pcd-footer-credit">
                        <a href="https://hostercube.com" target="_blank" title="+8801744977947" style="color: #666; text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='#667eea'" onmouseout="this.style.color='#666'">
                            Design & Development By HosterCube Ltd.
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            window.pcdPostPermalink = <?php echo json_encode($post_permalink); ?>;
            window.pcdPostTitle = <?php echo json_encode($post_title); ?>;
        </script>

        <?php wp_footer(); ?>
    </body>
    </html>
    <?php
}

function pcd_get_template_defaults($template) {
    $defaults = array(
        'classic' => array(
            'frame_color' => '#c41e3a',
            'text_color' => '#000000',
            'background_color' => '#ffffff',
            'button_color' => '#c41e3a',
            'button_text_color' => '#ffffff',
            'gradient_color_1' => '#ff6b6b',
            'gradient_color_2' => '#c41e3a',
            'title_font_family' => 'Noto Sans Bengali',
            'title_border_radius' => 6,
        ),
        'modern' => array(
            'frame_color' => '#3b82f6',
            'text_color' => '#1e293b',
            'background_color' => '#f8fafc',
            'button_color' => '#3b82f6',
            'button_text_color' => '#ffffff',
            'gradient_color_1' => '#60a5fa',
            'gradient_color_2' => '#3b82f6',
            'title_font_family' => 'Noto Sans Bengali',
            'title_border_radius' => 6,
        ),
        'elegant' => array(
            'frame_color' => '#d4af37',
            'text_color' => '#1a1a1a',
            'background_color' => '#fefefe',
            'button_color' => '#d4af37',
            'button_text_color' => '#1a1a1a',
            'gradient_color_1' => '#f4e4c1',
            'gradient_color_2' => '#d4af37',
            'title_font_family' => 'Noto Sans Bengali',
            'title_border_radius' => 6,
        ),
        'minimal' => array(
            'frame_color' => '#000000',
            'text_color' => '#000000',
            'background_color' => '#ffffff',
            'button_color' => '#000000',
            'button_text_color' => '#ffffff',
            'gradient_color_1' => '#f5f5f5',
            'gradient_color_2' => '#e5e5e5',
            'title_font_family' => 'Noto Sans Bengali',
            'title_border_radius' => 6,
        ),
        // FIX: NEW News24 template
        'news24' => array(
            'frame_color' => '#c41e3a',
            'text_color' => '#ffffff',
            'background_color' => '#1a1a2e',
            'button_color' => '#c41e3a',
            'button_text_color' => '#ffffff',
            'gradient_color_1' => '#1a1a2e',
            'gradient_color_2' => '#16213e',
            'title_font_family' => 'Noto Sans Bengali',
            'title_border_radius' => 0,
            'enable_gradient' => true,
        ),
        'custom' => array(
            'frame_color' => '#c41e3a',
            'text_color' => '#000000',
            'background_color' => '#ffffff',
            'button_color' => '#22c55e',
            'button_text_color' => '#ffffff',
            'gradient_color_1' => '#ff6b6b',
            'gradient_color_2' => '#4ecdc4',
            'title_font_family' => 'Noto Sans Bengali',
            'title_border_radius' => 6,
        ),
    );

    return isset($defaults[$template]) ? $defaults[$template] : $defaults['classic'];
}

function pcd_format_date_by_language($date_string, $day_of_week, $language) {
    $date_parts = explode(' ', $date_string);

    if (count($date_parts) < 3) {
        return $date_string;
    }

    $day = $date_parts[0];
    $month = $date_parts[1];
    $year = $date_parts[2];

    switch ($language) {
        case 'bengali':
            $bengali_days = array(
                'Sunday' => 'রবিবার',
                'Monday' => 'সোমবার',
                'Tuesday' => 'মঙ্গলবার',
                'Wednesday' => 'বুধবার',
                'Thursday' => 'বৃহস্পতিবার',
                'Friday' => 'শুক্রবার',
                'Saturday' => 'শনিবার'
            );

            $bengali_months = array(
                'January' => 'জানুয়ারি', 'February' => 'ফেব্রুয়ারি', 'March' => 'মার্চ',
                'April' => 'এপ্রিল', 'May' => 'মে', 'June' => 'জুন',
                'July' => 'জুলাই', 'August' => 'আগস্ট', 'September' => 'সেপ্টেম্বর',
                'October' => 'অক্টোবর', 'November' => 'নভেম্বর', 'December' => 'ডিসেম্বর'
            );
            // FIX: Corrected Bengali numbers (was using Hindi for 2-6)
            $bengali_numbers = array('0' => '০', '1' => '১', '2' => '২', '3' => '৩', '4' => '৪', '5' => '৫', '6' => '৬', '7' => '৭', '8' => '৮', '9' => '৯');

            $day_name = isset($bengali_days[$day_of_week]) ? $bengali_days[$day_of_week] : '';
            $day = strtr($day, $bengali_numbers);
            $month = isset($bengali_months[$month]) ? $bengali_months[$month] : $month;
            $year = strtr($year, $bengali_numbers);

            return (!empty($day_name) ? $day_name . ', ' : '') . $day . ' ' . $month . ' ' . $year;

        case 'hindi':
            $hindi_days = array(
                'Sunday' => 'रविवार',
                'Monday' => 'सोमवार',
                'Tuesday' => 'मंगलवार',
                'Wednesday' => 'बुधवार',
                'Thursday' => 'गुरुवार',
                'Friday' => 'शुक्रवार',
                'Saturday' => 'शनिवार'
            );

            $hindi_months = array(
                'January' => 'जनवरी', 'February' => 'फ़रवरी', 'March' => 'मार्च',
                'April' => 'अप्रैल', 'May' => 'मई', 'June' => 'जून',
                'July' => 'जुलाई', 'August' => 'अगस्त', 'September' => 'सितंबर',
                'October' => 'अक्टूबर', 'November' => 'नवंबर', 'December' => 'दिसंबर'
            );
            $hindi_numbers = array('0' => '०', '1' => '१', '2' => '२', '3' => '३', '4' => '४', '5' => '५', '6' => '६', '7' => '७', '8' => '८', '9' => '९');

            $day_name = isset($hindi_days[$day_of_week]) ? $hindi_days[$day_of_week] : '';
            $day = strtr($day, $hindi_numbers);
            $month = isset($hindi_months[$month]) ? $hindi_months[$month] : $month;
            $year = strtr($year, $hindi_numbers);

            return (!empty($day_name) ? $day_name . ', ' : '') . $day . ' ' . $month . ' ' . $year;

        case 'english':
        default:
            return $day_of_week . ', ' . $day . ' ' . $month . ' ' . $year;
    }
}

?>

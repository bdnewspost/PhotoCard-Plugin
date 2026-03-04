<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('template_redirect', 'pcd_handle_editor_page');

function pcd_handle_editor_page() {
    if (isset($_GET['pcd_editor']) && $_GET['pcd_editor'] == '1' && isset($_GET['post_id'])) {
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
    $current_date_obj = get_the_date('Y-m-d', $post_id);
    $day_of_week = date_i18n('l', strtotime($current_date_obj));
    $current_date = date_i18n('d F Y', strtotime($current_date_obj));
    $thumbnail_url = get_the_post_thumbnail_url($post_id, 'large');
    $post_permalink = get_permalink($post_id);

    $template = isset($options['photocard_template']) ? $options['photocard_template'] : 'news24';
    $language = isset($options['photocard_language']) ? $options['photocard_language'] : 'bengali';

    $watermark_logo = isset($options['watermark_logo']) ? $options['watermark_logo'] : '';
    $enable_date = isset($options['enable_date']) ? $options['enable_date'] : true;
    $enable_logo = isset($options['enable_logo']) ? $options['enable_logo'] : true;
    $show_details_button = isset($options['show_details_button']) ? $options['show_details_button'] : true;
    $details_button_text = isset($options['details_button_text']) ? $options['details_button_text'] : 'বিস্তারিত কমেন্টে';
    $default_font_size = isset($options['default_font_size']) ? $options['default_font_size'] : 48;
    $default_line_height = isset($options['default_line_height']) ? $options['default_line_height'] : 1.3;
    $logo_position = isset($options['logo_position']) ? $options['logo_position'] : 'left';
    $date_position = isset($options['date_position']) ? $options['date_position'] : 'right';
    $title_font_family = isset($options['title_font_family']) && !empty($options['title_font_family']) ? $options['title_font_family'] : 'Noto Sans Bengali';
    $image_quality = isset($options['image_quality']) ? $options['image_quality'] : 4;
    $title_alignment = 'center';

    // Social
    $show_facebook = isset($options['show_facebook']) ? $options['show_facebook'] : false;
    $facebook_text = isset($options['facebook_text']) ? $options['facebook_text'] : '';
    $show_youtube = isset($options['show_youtube']) ? $options['show_youtube'] : false;
    $youtube_text = isset($options['youtube_text']) ? $options['youtube_text'] : '';
    $show_website = isset($options['show_website']) ? $options['show_website'] : false;
    $website_text = isset($options['website_text']) ? $options['website_text'] : '';
    $show_instagram = isset($options['show_instagram']) ? $options['show_instagram'] : false;
    $instagram_text = isset($options['instagram_text']) ? $options['instagram_text'] : '';
    $show_linkedin = isset($options['show_linkedin']) ? $options['show_linkedin'] : false;
    $linkedin_text = isset($options['linkedin_text']) ? $options['linkedin_text'] : '';

    // Template colors
    $kalbela_bg_color = isset($options['kalbela_bg_color']) ? $options['kalbela_bg_color'] : '#cc0000';
    $news24_title_color = isset($options['news24_title_color']) ? $options['news24_title_color'] : '#FFD700';

    $formatted_date = pcd_format_date_by_language($current_date, $day_of_week, $language);

    $is_kalbela = ($template === 'kalbela');
    $is_news24 = ($template === 'news24');

    ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Photocard - <?php echo esc_html($post_title); ?></title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700;800;900&family=Hind+Siliguri:wght@400;600;700&family=Tiro+Bangla&display=swap" rel="stylesheet">
        <?php wp_head(); ?>
    </head>
    <body class="pcd-editor-page">
        <div class="pcd-editor-container">
            <h1 class="pcd-editor-title">Automatic Photocard</h1>

            <div class="pcd-editor-content">
                <div id="pcd-photocard-preview" class="pcd-photocard-wrapper">
                    <div class="pcd-photocard-with-border pcd-template-<?php echo esc_attr($template); ?>" style="position: relative; width: 1080px; height: 1080px;">

                        <?php if ($is_news24): ?>
                            <!-- ========== NEWS24 TEMPLATE ========== -->
                            <!-- Matches reference: full-bleed image, logo top-right, gradient overlay bottom, gold title, social bar -->
                            <div class="pcd-photocard" data-language="<?php echo esc_attr($language); ?>" data-quality="<?php echo esc_attr($image_quality); ?>" style="width: 1080px; height: 1080px; background: #000; padding: 0; position: relative; overflow: hidden; box-sizing: border-box;">
                                
                                <!-- Full Bleed Background Image -->
                                <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;" crossorigin="anonymous">

                                <!-- Dark Gradient Overlay (bottom 60%) -->
                                <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 65%; background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.7) 50%, transparent 100%); z-index: 2;"></div>

                                <!-- Logo (top-right by default) -->
                                <?php if ($enable_logo && !empty($watermark_logo)): ?>
                                <div style="position: absolute; top: 20px; <?php echo ($logo_position === 'left') ? 'left: 20px;' : (($logo_position === 'center') ? 'left: 50%; transform: translateX(-50%);' : 'right: 20px;'); ?> z-index: 10;">
                                    <img src="<?php echo esc_url($watermark_logo); ?>" alt="Logo" style="height: 80px; width: auto; display: block;" crossorigin="anonymous">
                                </div>
                                <?php endif; ?>

                                <!-- Bottom Content Area -->
                                <div style="position: absolute; bottom: 0; left: 0; right: 0; z-index: 5; display: flex; flex-direction: column;">
                                    
                                    <!-- Date Badge -->
                                    <?php if ($enable_date): ?>
                                    <div style="text-align: <?php echo esc_attr($date_position); ?>; padding: 0 30px; margin-bottom: 12px;">
                                        <span style="display: inline-block; color: #ffffff; font-size: 24px; font-weight: 600; background: rgba(0,0,0,0.5); padding: 6px 18px; border-radius: 4px; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif;">
                                            <?php echo esc_html($formatted_date); ?>
                                        </span>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Title -->
                                    <div id="pcd-adjustable-title" class="pcd-title" style="color: <?php echo esc_attr($news24_title_color); ?>; font-size: <?php echo esc_attr($default_font_size); ?>px; line-height: <?php echo esc_attr($default_line_height); ?>; font-weight: 900; text-align: <?php echo esc_attr($title_alignment); ?>; padding: 0 30px; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; word-wrap: break-word; overflow-wrap: break-word; text-shadow: 3px 3px 8px rgba(0,0,0,0.9), -1px -1px 4px rgba(0,0,0,0.5);">
                                        <?php echo esc_html($post_title); ?>
                                    </div>

                                    <!-- Details Button -->
                                    <?php if ($show_details_button): ?>
                                    <div style="text-align: center; padding: 12px 30px 0;">
                                        <span style="color: #ffffff; font-size: 22px; font-weight: 600; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif;">
                                            ❯❯ <?php echo esc_html($details_button_text); ?> ❮❮
                                        </span>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Social Links Bar -->
                                    <?php if ($show_facebook || $show_youtube || $show_website || $show_instagram || $show_linkedin): ?>
                                    <div style="background: rgba(0,0,0,0.75); padding: 12px 20px; margin-top: 12px; display: flex; justify-content: center; align-items: center; gap: 25px; flex-wrap: wrap;">
                                        <?php if ($show_facebook && !empty($facebook_text)): ?>
                                        <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: 16px; font-weight: 500;">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#1877f2"><circle cx="12" cy="12" r="12" fill="#1877f2"/><path d="M16.5 12.5h-2.5v7h-3v-7h-2v-2.5h2v-1.5c0-2.2 1-3.5 3.5-3.5h2v2.5h-1.5c-.8 0-1 .3-1 1v1.5h2.5l-.5 2.5z" fill="white"/></svg>
                                            <span><?php echo esc_html($facebook_text); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($show_youtube && !empty($youtube_text)): ?>
                                        <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: 16px; font-weight: 500;">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="red"><rect width="24" height="24" rx="4" fill="red"/><path d="M10 8.5v7l5.5-3.5L10 8.5z" fill="white"/></svg>
                                            <span><?php echo esc_html($youtube_text); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($show_website && !empty($website_text)): ?>
                                        <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: 16px; font-weight: 500;">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#4CAF50"><circle cx="12" cy="12" r="10" fill="#4CAF50"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" fill="white"/></svg>
                                            <span><?php echo esc_html($website_text); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($show_instagram && !empty($instagram_text)): ?>
                                        <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: 16px; font-weight: 500;">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                            <span><?php echo esc_html($instagram_text); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($show_linkedin && !empty($linkedin_text)): ?>
                                        <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: 16px; font-weight: 500;">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#0077b5"><rect width="24" height="24" rx="4" fill="#0077b5"/><path d="M8 19H5V9h3v10zm-1.5-11.3c-1 0-1.5-.7-1.5-1.5s.6-1.5 1.5-1.5 1.5.7 1.5 1.5-.5 1.5-1.5 1.5zM20 19h-3v-5.5c0-1.4-.5-2.3-1.7-2.3-.9 0-1.5.6-1.7 1.2-.1.2-.1.5-.1.8V19h-3V9h3v1.4c.4-.6 1.1-1.5 2.7-1.5 2 0 3.5 1.3 3.5 4.1V19z" fill="white"/></svg>
                                            <span><?php echo esc_html($linkedin_text); ?></span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                        <?php elseif ($is_kalbela): ?>
                            <!-- ========== KALBELA TEMPLATE ========== -->
                            <!-- Matches reference: red header with logo+date, white image area, red footer with title -->
                            <div class="pcd-photocard" data-language="<?php echo esc_attr($language); ?>" data-quality="<?php echo esc_attr($image_quality); ?>" style="width: 1080px; height: 1080px; background: #ffffff; padding: 0; position: relative; display: flex; flex-direction: column; box-sizing: border-box; overflow: hidden;">
                                
                                <!-- Red Header Bar -->
                                <div style="background: <?php echo esc_attr($kalbela_bg_color); ?>; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                                    <?php if ($logo_position === 'right'): ?>
                                        <?php if ($enable_date): ?>
                                        <div style="color: #ffffff; font-size: 28px; font-weight: 700; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif;">
                                            <?php echo esc_html($formatted_date); ?>
                                        </div>
                                        <?php endif; ?>
                                        <?php if ($enable_logo && !empty($watermark_logo)): ?>
                                        <img src="<?php echo esc_url($watermark_logo); ?>" alt="Logo" style="height: 85px; width: auto; display: block;" crossorigin="anonymous">
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if ($enable_logo && !empty($watermark_logo)): ?>
                                        <img src="<?php echo esc_url($watermark_logo); ?>" alt="Logo" style="height: 85px; width: auto; display: block;" crossorigin="anonymous">
                                        <?php endif; ?>
                                        <?php if ($enable_date): ?>
                                        <div style="color: #ffffff; font-size: 28px; font-weight: 700; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif;">
                                            <?php echo esc_html($formatted_date); ?>
                                        </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>

                                <!-- Featured Image Area (white background, centered) -->
                                <div style="flex: 1; background: #ffffff; display: flex; align-items: center; justify-content: center; padding: 20px 30px; min-height: 0; overflow: hidden;">
                                    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="max-width: 100%; max-height: 100%; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; box-shadow: 0 8px 30px rgba(0,0,0,0.15);" crossorigin="anonymous">
                                </div>

                                <!-- Red Title Footer Section -->
                                <div style="background: <?php echo esc_attr($kalbela_bg_color); ?>; padding: 25px 35px 15px; flex-shrink: 0;">
                                    <div id="pcd-adjustable-title" class="pcd-title" style="color: #ffffff; font-size: <?php echo esc_attr($default_font_size); ?>px; line-height: <?php echo esc_attr($default_line_height); ?>; font-weight: 900; text-align: <?php echo esc_attr($title_alignment); ?>; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; word-wrap: break-word; overflow-wrap: break-word; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                                        <?php echo esc_html($post_title); ?>
                                    </div>

                                    <?php if ($show_details_button): ?>
                                    <div style="text-align: center; margin-top: 12px;">
                                        <span style="color: rgba(255,255,255,0.9); font-size: 22px; font-weight: 600; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif;">
                                            ❮❮ <?php echo esc_html($details_button_text); ?> ❯❯
                                        </span>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Social Links -->
                                <?php if ($show_facebook || $show_youtube || $show_website || $show_instagram || $show_linkedin): ?>
                                <div style="background: <?php echo esc_attr($kalbela_bg_color); ?>; border-top: 1px solid rgba(255,255,255,0.2); padding: 10px 20px; display: flex; justify-content: center; align-items: center; gap: 20px; flex-wrap: wrap; flex-shrink: 0;">
                                    <?php if ($show_facebook && !empty($facebook_text)): ?>
                                    <div style="display: flex; align-items: center; gap: 5px; color: white; font-size: 15px; font-weight: 500;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                        <span><?php echo esc_html($facebook_text); ?></span>
                                    </div>
                                    <?php endif; ?>
                                    <?php if ($show_youtube && !empty($youtube_text)): ?>
                                    <div style="display: flex; align-items: center; gap: 5px; color: white; font-size: 15px; font-weight: 500;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                        <span><?php echo esc_html($youtube_text); ?></span>
                                    </div>
                                    <?php endif; ?>
                                    <?php if ($show_website && !empty($website_text)): ?>
                                    <div style="display: flex; align-items: center; gap: 5px; color: white; font-size: 15px; font-weight: 500;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zm6.93 6h-2.95c-.32-1.25-.78-2.45-1.38-3.56 1.84.63 3.37 1.91 4.33 3.56zM12 4.04c.83 1.2 1.48 2.53 1.91 3.96h-3.82c.43-1.43 1.08-2.76 1.91-3.96zM4.26 14C4.1 13.36 4 12.69 4 12s.1-1.36.26-2h3.38c-.08.66-.14 1.32-.14 2 0 .68.06 1.34.14 2H4.26zm.82 2h2.95c.32 1.25.78 2.45 1.38 3.56-1.84-.63-3.37-1.9-4.33-3.56zm2.95-8H5.08c.96-1.66 2.49-2.93 4.33-3.56C8.81 5.55 8.35 6.75 8.03 8zM12 19.96c-.83-1.2-1.48-2.53-1.91-3.96h3.82c-.43 1.43-1.08 2.76-1.91 3.96zM14.34 14H9.66c-.09-.66-.16-1.32-.16-2 0-.68.07-1.35.16-2h4.68c.09.65.16 1.32.16 2 0 .68-.07 1.34-.16 2zm.25 5.56c.6-1.11 1.06-2.31 1.38-3.56h2.95c-.96 1.65-2.49 2.93-4.33 3.56zM16.36 14c.08-.66.14-1.32.14-2 0-.68-.06-1.34-.14-2h3.38c.16.64.26 1.31.26 2s-.1 1.36-.26 2h-3.38z"/></svg>
                                        <span><?php echo esc_html($website_text); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>

                        <?php endif; ?>

                    </div>
                </div>

                <!-- Editor Controls -->
                <div class="pcd-editor-controls">
                    <div class="pcd-control-group pcd-share-section">
                        <label>শেয়ার এবং কপি করুন</label>
                        <div class="pcd-share-buttons">
                            <button id="pcd-copy-link-button" class="pcd-btn pcd-btn-icon pcd-btn-sm" title="লিংক কপি">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg>
                            </button>
                            <button id="pcd-share-facebook" class="pcd-btn pcd-btn-icon pcd-btn-sm pcd-btn-facebook" title="Facebook"><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></button>
                            <button id="pcd-share-instagram" class="pcd-btn pcd-btn-icon pcd-btn-sm pcd-btn-instagram" title="Instagram"><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></button>
                            <button id="pcd-share-whatsapp" class="pcd-btn pcd-btn-icon pcd-btn-sm pcd-btn-whatsapp" title="WhatsApp"><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg></button>
                            <button id="pcd-share-linkedin" class="pcd-btn pcd-btn-icon pcd-btn-sm pcd-btn-linkedin" title="LinkedIn"><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg></button>
                        </div>
                    </div>

                    <!-- Title Editor -->
                    <div class="pcd-control-group">
                        <label>টাইটেল এডিটর</label>
                        <textarea id="pcd-title-editor" class="pcd-title-editor-textarea" rows="3"><?php echo esc_textarea($post_title); ?></textarea>
                    </div>

                    <!-- Title Alignment -->
                    <div class="pcd-control-group">
                        <label>টাইটেল এলাইনমেন্ট</label>
                        <div class="pcd-align-buttons">
                            <button type="button" class="pcd-align-btn" data-align="left" title="বাম">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h18v2H3V3zm0 4h12v2H3V7zm0 4h18v2H3v-2zm0 4h12v2H3v-2zm0 4h18v2H3v-2z"/></svg>
                            </button>
                            <button type="button" class="pcd-align-btn active" data-align="center" title="মাঝখানে">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h18v2H3V3zm3 4h12v2H6V7zm-3 4h18v2H3v-2zm3 4h12v2H6v-2zm-3 4h18v2H3v-2z"/></svg>
                            </button>
                            <button type="button" class="pcd-align-btn" data-align="right" title="ডান">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h18v2H3V3zm6 4h12v2H9V7zm-6 4h18v2H3v-2zm6 4h12v2H9v-2zm-6 4h18v2H3v-2z"/></svg>
                            </button>
                            <button type="button" class="pcd-align-btn" data-align="justify" title="জাস্টিফাই">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h18v2H3V3zm0 4h18v2H3V7zm0 4h18v2H3v-2zm0 4h18v2H3v-2zm0 4h18v2H3v-2z"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Line Colors -->
                    <div class="pcd-control-group">
                        <label>লাইন কালার</label>
                        <div id="pcd-line-colors-container"></div>
                        <button id="pcd-apply-line-colors" type="button">কালার প্রয়োগ করুন</button>
                    </div>

                    <!-- Font Size -->
                    <div class="pcd-control-group">
                        <label>ফন্ট সাইজ <span id="pcd-font-size-value" class="pcd-slider-value"><?php echo esc_html($default_font_size); ?>px</span></label>
                        <input type="range" id="pcd-font-size-slider" min="20" max="70" value="<?php echo esc_attr($default_font_size); ?>">
                    </div>

                    <!-- Line Height -->
                    <div class="pcd-control-group">
                        <label>লাইন হাইট <span id="pcd-line-height-value" class="pcd-slider-value"><?php echo esc_html($default_line_height); ?></span></label>
                        <input type="range" id="pcd-line-height-slider" min="0.8" max="2.5" step="0.05" value="<?php echo esc_attr($default_line_height); ?>">
                    </div>

                    <!-- Actions -->
                    <div class="pcd-editor-actions">
                        <button id="pcd-back-button" class="pcd-btn pcd-btn-secondary" onclick="window.history.back()">Back</button>
                        <button id="pcd-download-button" class="pcd-btn pcd-btn-primary">Download</button>
                    </div>

                    <div class="pcd-footer-credit">
                        <a href="https://hostercube.com" target="_blank" style="color: #666; text-decoration: none;">
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

// Social links helper (kept for backwards compat)
function pcd_render_social_links($frame_color, $show_facebook, $facebook_text, $show_instagram, $instagram_text, $show_youtube, $youtube_text, $show_linkedin, $linkedin_text, $show_website, $website_text) {
    ?>
    <div class="pcd-social-links" style="background: <?php echo esc_attr($frame_color); ?>; padding: 8px 10px; border-radius: 8px; display: flex; justify-content: center; align-items: center; gap: 10px; flex-wrap: wrap;">
        <?php if ($show_facebook && !empty($facebook_text)): ?>
            <div style="display: flex; align-items: center; gap: 3px; color: white; font-size: 16px; font-weight: 500;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                <span><?php echo esc_html($facebook_text); ?></span>
            </div>
        <?php endif; ?>
        <?php if ($show_youtube && !empty($youtube_text)): ?>
            <div style="display: flex; align-items: center; gap: 3px; color: white; font-size: 16px; font-weight: 500;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                <span><?php echo esc_html($youtube_text); ?></span>
            </div>
        <?php endif; ?>
        <?php if ($show_website && !empty($website_text)): ?>
            <div style="display: flex; align-items: center; gap: 3px; color: white; font-size: 16px; font-weight: 500;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2z"/></svg>
                <span><?php echo esc_html($website_text); ?></span>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

function pcd_get_template_defaults($template) {
    $defaults = array(
        'kalbela' => array(
            'frame_color' => '#cc0000',
            'text_color' => '#ffffff',
            'background_color' => '#ffffff',
            'button_color' => '#cc0000',
            'button_text_color' => '#ffffff',
            'gradient_color_1' => '#cc0000',
            'gradient_color_2' => '#990000',
            'title_font_family' => 'Noto Sans Bengali',
            'title_border_radius' => 0,
        ),
        'news24' => array(
            'frame_color' => '#c41e3a',
            'text_color' => '#ffffff',
            'background_color' => '#000000',
            'button_color' => '#c41e3a',
            'button_text_color' => '#ffffff',
            'gradient_color_1' => '#000000',
            'gradient_color_2' => '#1a1a2e',
            'title_font_family' => 'Noto Sans Bengali',
            'title_border_radius' => 0,
        ),
    );

    return isset($defaults[$template]) ? $defaults[$template] : $defaults['news24'];
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
            $bengali_months = array(
                'January' => 'জানুয়ারি', 'February' => 'ফেব্রুয়ারি', 'March' => 'মার্চ',
                'April' => 'এপ্রিল', 'May' => 'মে', 'June' => 'জুন',
                'July' => 'জুলাই', 'August' => 'আগস্ট', 'September' => 'সেপ্টেম্বর',
                'October' => 'অক্টোবর', 'November' => 'নভেম্বর', 'December' => 'ডিসেম্বর'
            );
            $bengali_numbers = array('0' => '০', '1' => '১', '2' => '২', '3' => '৩', '4' => '৪', '5' => '৫', '6' => '৬', '7' => '৭', '8' => '৮', '9' => '৯');

            $bn_day = strtr($day, $bengali_numbers);
            $bn_month = isset($bengali_months[$month]) ? $bengali_months[$month] : $month;
            $bn_year = strtr($year, $bengali_numbers);

            return $bn_day . ' ' . $bn_month . ', ' . $bn_year;

        case 'hindi':
            $hindi_months = array(
                'January' => 'जनवरी', 'February' => 'फ़रवरी', 'March' => 'मार्च',
                'April' => 'अप्रैल', 'May' => 'मई', 'June' => 'जून',
                'July' => 'जुलाई', 'August' => 'अगस्त', 'September' => 'सितम्बर',
                'October' => 'अक्टूबर', 'November' => 'नवम्बर', 'December' => 'दिसम्बर'
            );
            $hindi_numbers = array('0' => '०', '1' => '१', '2' => '२', '3' => '३', '4' => '४', '5' => '५', '6' => '६', '7' => '७', '8' => '८', '9' => '९');

            $hi_day = strtr($day, $hindi_numbers);
            $hi_month = isset($hindi_months[$month]) ? $hindi_months[$month] : $month;
            $hi_year = strtr($year, $hindi_numbers);

            return $hi_day . ' ' . $hi_month . ' ' . $hi_year;

        default:
            return $date_string;
    }
}
?>

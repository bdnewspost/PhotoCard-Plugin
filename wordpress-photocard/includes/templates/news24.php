<?php
/**
 * News24 Template
 * Full-bleed image with yellow title bar at bottom
 * Matches reference: logo top-left, yellow bar bottom, dark text, date badge
 */
if (!defined('ABSPATH')) exit;

// Variables available from photocard-editor.php:
// $post_title, $thumbnail_url, $formatted_date, $watermark_logo, $enable_logo, $enable_date
// $logo_position, $date_position, $title_font_family, $default_font_size, $default_line_height
// $title_alignment, $image_quality, $language, $show_details_button, $details_button_text
// $news24_title_color, $show_facebook, $facebook_text, etc.

$news24_bg_color = isset($options['news24_bg_color']) ? $options['news24_bg_color'] : '#FFD700';
$news24_text_color = isset($options['news24_text_color']) ? $options['news24_text_color'] : '#000000';
$news24_date_bg = isset($options['news24_date_bg']) ? $options['news24_date_bg'] : '#1a5fb4';
?>
<div class="pcd-photocard" data-language="<?php echo esc_attr($language); ?>" data-quality="<?php echo esc_attr($image_quality); ?>" style="width: 1080px; height: 1080px; background: #000; padding: 0; position: relative; overflow: hidden; box-sizing: border-box;">
    
    <!-- Full Bleed Background Image -->
    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;" crossorigin="anonymous">

    <!-- Logo (top-left by default, like BREAKING NEWS badge) -->
    <?php if ($enable_logo && !empty($watermark_logo)): ?>
    <div style="position: absolute; top: 25px; <?php echo ($logo_position === 'right') ? 'right: 25px;' : (($logo_position === 'center') ? 'left: 50%; transform: translateX(-50%);' : 'left: 25px;'); ?> z-index: 10;">
        <img src="<?php echo esc_url($watermark_logo); ?>" alt="Logo" style="height: 80px; width: auto; display: block;" crossorigin="anonymous">
    </div>
    <?php endif; ?>

    <!-- Date Badge (positioned above yellow bar) -->
    <?php if ($enable_date): ?>
    <div style="position: absolute; bottom: 310px; <?php echo ($date_position === 'left') ? 'left: 30px;' : 'right: 30px;'; ?> z-index: 10;">
        <span style="display: inline-block; color: #ffffff; font-size: 26px; font-weight: 700; background: <?php echo esc_attr($news24_date_bg); ?>; padding: 8px 22px; border-radius: 4px; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif;">
            <?php echo esc_html($formatted_date); ?>
        </span>
    </div>
    <?php endif; ?>

    <!-- Yellow Title Bar at Bottom -->
    <div style="position: absolute; bottom: 0; left: 0; right: 0; z-index: 5; background: <?php echo esc_attr($news24_bg_color); ?>; padding: 30px 35px 20px;">
        
        <!-- Title -->
        <div id="pcd-adjustable-title" class="pcd-title" style="color: <?php echo esc_attr($news24_text_color); ?>; font-size: <?php echo esc_attr($default_font_size); ?>px; line-height: <?php echo esc_attr($default_line_height); ?>; font-weight: 900; text-align: <?php echo esc_attr($title_alignment); ?>; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; word-wrap: break-word; overflow-wrap: break-word;">
            <?php echo esc_html($post_title); ?>
        </div>

        <!-- Details Button -->
        <?php if ($show_details_button): ?>
        <div style="text-align: <?php echo esc_attr($title_alignment); ?>; margin-top: 10px;">
            <span style="color: <?php echo esc_attr($news24_text_color); ?>; font-size: 20px; font-weight: 600; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; opacity: 0.8;">
                ❯❯ <?php echo esc_html($details_button_text); ?> ❮❮
            </span>
        </div>
        <?php endif; ?>

        <!-- Website text at bottom-right -->
        <?php if ($show_website && !empty($website_text)): ?>
        <div style="text-align: right; margin-top: 8px;">
            <span style="color: <?php echo esc_attr($news24_text_color); ?>; font-size: 16px; font-weight: 500; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; opacity: 0.7;">
                <?php echo esc_html($website_text); ?>
            </span>
        </div>
        <?php endif; ?>
    </div>

    <!-- Social Links Bar (above yellow bar) -->
    <?php if ($show_facebook || $show_youtube || $show_instagram || $show_linkedin): ?>
    <div style="position: absolute; bottom: 0; left: 0; right: 0; z-index: 6; background: rgba(0,0,0,0.8); padding: 10px 20px; display: flex; justify-content: center; align-items: center; gap: 25px; flex-wrap: wrap;">
        <?php if ($show_facebook && !empty($facebook_text)): ?>
        <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: 15px; font-weight: 500;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            <span><?php echo esc_html($facebook_text); ?></span>
        </div>
        <?php endif; ?>
        <?php if ($show_youtube && !empty($youtube_text)): ?>
        <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: 15px; font-weight: 500;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
            <span><?php echo esc_html($youtube_text); ?></span>
        </div>
        <?php endif; ?>
        <?php if ($show_instagram && !empty($instagram_text)): ?>
        <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: 15px; font-weight: 500;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
            <span><?php echo esc_html($instagram_text); ?></span>
        </div>
        <?php endif; ?>
        <?php if ($show_linkedin && !empty($linkedin_text)): ?>
        <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: 15px; font-weight: 500;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
            <span><?php echo esc_html($linkedin_text); ?></span>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

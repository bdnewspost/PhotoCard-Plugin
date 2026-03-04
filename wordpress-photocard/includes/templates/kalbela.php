<?php
/**
 * Kalbela Template
 * Newspaper style: Red header with logo+date, white image area, red footer with title
 */
if (!defined('ABSPATH')) exit;

// Variables available from photocard-editor.php
$kalbela_bg = isset($options['kalbela_bg_color']) ? $options['kalbela_bg_color'] : '#cc0000';
?>
<div class="pcd-photocard" data-language="<?php echo esc_attr($language); ?>" data-quality="<?php echo esc_attr($image_quality); ?>" style="width: 1080px; height: 1080px; background: #ffffff; padding: 0; position: relative; display: flex; flex-direction: column; box-sizing: border-box; overflow: hidden;">
    
    <!-- Red Header Bar -->
    <div style="background: <?php echo esc_attr($kalbela_bg); ?>; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
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

    <!-- Featured Image Area -->
    <div style="flex: 1; background: #ffffff; display: flex; align-items: center; justify-content: center; padding: 20px 30px; min-height: 0; overflow: hidden;">
        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="max-width: 100%; max-height: 100%; width: 100%; height: 100%; object-fit: cover; border-radius: 8px; box-shadow: 0 8px 30px rgba(0,0,0,0.15);" crossorigin="anonymous">
    </div>

    <!-- Red Title Footer -->
    <div style="background: <?php echo esc_attr($kalbela_bg); ?>; padding: 25px 35px 15px; flex-shrink: 0;">
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
    <div style="background: <?php echo esc_attr($kalbela_bg); ?>; border-top: 1px solid rgba(255,255,255,0.2); padding: 10px 20px; display: flex; justify-content: center; align-items: center; gap: 20px; flex-wrap: wrap; flex-shrink: 0;">
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
            <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zm6.93 6h-2.95c-.32-1.25-.78-2.45-1.38-3.56 1.84.63 3.37 1.91 4.33 3.56zM12 4.04c.83 1.2 1.48 2.53 1.91 3.96h-3.82c.43-1.43 1.08-2.76 1.91-3.96z"/></svg>
            <span><?php echo esc_html($website_text); ?></span>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

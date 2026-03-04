<?php
/**
 * Daily Star Template
 * Dark/Navy professional style with gradient overlay
 * Style: Dark navy header, full image, gradient bottom with white title, red accent
 */
if (!defined('ABSPATH')) exit;

$ds_accent = isset($options['dailystar_accent_color']) ? $options['dailystar_accent_color'] : '#e63946';
$ds_bg = isset($options['dailystar_bg_color']) ? $options['dailystar_bg_color'] : '#1d2d44';
$ds_title_color = isset($options['dailystar_title_color']) ? $options['dailystar_title_color'] : '#ffffff';
?>
<div class="pcd-photocard" data-language="<?php echo esc_attr($language); ?>" data-quality="<?php echo esc_attr($image_quality); ?>" style="width: 1080px; height: 1080px; background: <?php echo esc_attr($ds_bg); ?>; padding: 0; position: relative; display: flex; flex-direction: column; box-sizing: border-box; overflow: hidden;">
    
    <!-- Top Header Bar -->
    <div style="background: <?php echo esc_attr($ds_bg); ?>; padding: 18px 35px; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
        <?php if ($logo_position === 'right'): ?>
            <?php if ($enable_date): ?>
            <div style="color: rgba(255,255,255,0.8); font-size: 22px; font-weight: 500; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif;">
                <?php echo esc_html($formatted_date); ?>
            </div>
            <?php endif; ?>
            <?php if ($enable_logo && !empty($watermark_logo)): ?>
            <img src="<?php echo esc_url($watermark_logo); ?>" alt="Logo" style="height: 70px; width: auto; display: block;" crossorigin="anonymous">
            <?php endif; ?>
        <?php else: ?>
            <?php if ($enable_logo && !empty($watermark_logo)): ?>
            <img src="<?php echo esc_url($watermark_logo); ?>" alt="Logo" style="height: 70px; width: auto; display: block;" crossorigin="anonymous">
            <?php endif; ?>
            <?php if ($enable_date): ?>
            <div style="color: rgba(255,255,255,0.8); font-size: 22px; font-weight: 500; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif;">
                <?php echo esc_html($formatted_date); ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Red Accent Line -->
    <div style="height: 5px; background: <?php echo esc_attr($ds_accent); ?>; flex-shrink: 0;"></div>

    <!-- Featured Image -->
    <div style="flex: 1; min-height: 0; overflow: hidden; position: relative;">
        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="width: 100%; height: 100%; object-fit: cover;" crossorigin="anonymous">
        <!-- Gradient overlay on image bottom -->
        <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 40%; background: linear-gradient(to top, <?php echo esc_attr($ds_bg); ?> 0%, transparent 100%);"></div>
    </div>

    <!-- Title Section -->
    <div style="background: <?php echo esc_attr($ds_bg); ?>; padding: 25px 35px 12px; flex-shrink: 0; position: relative;">
        <!-- Red left border accent -->
        <div style="position: absolute; left: 20px; top: 20px; bottom: 12px; width: 5px; background: <?php echo esc_attr($ds_accent); ?>; border-radius: 3px;"></div>
        
        <div id="pcd-adjustable-title" class="pcd-title" style="color: <?php echo esc_attr($ds_title_color); ?>; font-size: <?php echo esc_attr($default_font_size); ?>px; line-height: <?php echo esc_attr($default_line_height); ?>; font-weight: 800; text-align: <?php echo esc_attr($title_alignment); ?>; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; word-wrap: break-word; padding-left: 15px;">
            <?php echo esc_html($post_title); ?>
        </div>
    </div>

    <!-- Details -->
    <?php if ($show_details_button): ?>
    <div style="background: <?php echo esc_attr($ds_bg); ?>; padding: 0 35px 10px; flex-shrink: 0;">
        <span style="color: <?php echo esc_attr($ds_accent); ?>; font-size: 20px; font-weight: 600; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif;">
            ❯❯ <?php echo esc_html($details_button_text); ?> ❮❮
        </span>
    </div>
    <?php endif; ?>

    <!-- Bottom Social Bar -->
    <?php if ($show_facebook || $show_youtube || $show_website || $show_instagram || $show_linkedin): ?>
    <div style="background: <?php echo esc_attr($ds_accent); ?>; padding: 12px 25px; flex-shrink: 0; display: flex; justify-content: center; align-items: center; gap: 25px;">
        <?php if ($show_facebook && !empty($facebook_text)): ?>
        <div style="display: flex; align-items: center; gap: 5px; color: white; font-size: 15px; font-weight: 600;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            <span><?php echo esc_html($facebook_text); ?></span>
        </div>
        <?php endif; ?>
        <?php if ($show_youtube && !empty($youtube_text)): ?>
        <div style="display: flex; align-items: center; gap: 5px; color: white; font-size: 15px; font-weight: 600;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
            <span><?php echo esc_html($youtube_text); ?></span>
        </div>
        <?php endif; ?>
        <?php if ($show_website && !empty($website_text)): ?>
        <div style="display: flex; align-items: center; gap: 5px; color: white; font-size: 15px; font-weight: 600;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><circle cx="12" cy="12" r="10"/></svg>
            <span><?php echo esc_html($website_text); ?></span>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

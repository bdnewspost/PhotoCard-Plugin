<?php
/**
 * Daily Star Template - Based on real Daily Star branding
 * 
 * Brand: Navy blue (#003366) + White, professional English daily
 * Layout: Navy header bar with logo, full image, navy bottom bar with white title
 * Red accent line separator
 * Clean, authoritative, professional
 */
if (!defined('ABSPATH')) exit;

$ds_navy = '#003366';
$ds_red = '#cc0000';
?>
<div class="pcd-photocard" data-language="<?php echo esc_attr($language); ?>" data-quality="<?php echo esc_attr($image_quality); ?>" style="width: 1080px; height: 1080px; background: <?php echo esc_attr($ds_navy); ?>; padding: 0; position: relative; display: flex; flex-direction: column; box-sizing: border-box; overflow: hidden;">
    
    <!-- Navy Header -->
    <div style="background: <?php echo esc_attr($ds_navy); ?>; padding: 18px 30px; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
        <?php if ($logo_position === 'right'): ?>
            <?php if ($enable_date): ?>
            <div style="color: rgba(255,255,255,0.85); font-size: 22px; font-weight: 500; font-family: '<?php echo esc_attr($title_font_family); ?>', sans-serif;">
                <?php echo esc_html($formatted_date); ?>
            </div>
            <?php endif; ?>
            <?php if ($enable_logo && !empty($watermark_logo)): ?>
            <img src="<?php echo esc_url($watermark_logo); ?>" alt="Logo" style="height: 65px; width: auto;" crossorigin="anonymous">
            <?php endif; ?>
        <?php else: ?>
            <?php if ($enable_logo && !empty($watermark_logo)): ?>
            <img src="<?php echo esc_url($watermark_logo); ?>" alt="Logo" style="height: 65px; width: auto;" crossorigin="anonymous">
            <?php endif; ?>
            <?php if ($enable_date): ?>
            <div style="color: rgba(255,255,255,0.85); font-size: 22px; font-weight: 500; font-family: '<?php echo esc_attr($title_font_family); ?>', sans-serif;">
                <?php echo esc_html($formatted_date); ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Red accent line -->
    <div style="height: 5px; background: <?php echo esc_attr($ds_red); ?>; flex-shrink: 0;"></div>

    <!-- Featured Image - full width -->
    <div style="flex: 1; min-height: 0; overflow: hidden;">
        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="width: 100%; height: 100%; object-fit: cover; display: block;" crossorigin="anonymous">
    </div>

    <!-- Red accent line -->
    <div style="height: 5px; background: <?php echo esc_attr($ds_red); ?>; flex-shrink: 0;"></div>

    <!-- Navy Title Footer -->
    <div style="background: <?php echo esc_attr($ds_navy); ?>; padding: 22px 35px 10px; flex-shrink: 0;">
        <div id="pcd-adjustable-title" class="pcd-title" style="color: #ffffff; font-size: <?php echo esc_attr($default_font_size); ?>px; line-height: <?php echo esc_attr($default_line_height); ?>; font-weight: 800; text-align: <?php echo esc_attr($title_alignment); ?>; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; word-wrap: break-word;">
            <?php echo esc_html($post_title); ?>
        </div>

        <?php if ($show_details_button): ?>
        <div style="text-align: center; margin-top: 10px;">
            <span style="color: rgba(255,255,255,0.8); font-size: 20px; font-weight: 600; font-family: '<?php echo esc_attr($title_font_family); ?>', sans-serif;">
                ❯❯ <?php echo esc_html($details_button_text); ?> ❮❮
            </span>
        </div>
        <?php endif; ?>
    </div>

    <!-- Social bar -->
    <?php if ($show_facebook || $show_youtube || $show_website): ?>
    <div style="background: <?php echo esc_attr($ds_red); ?>; padding: 10px 25px; flex-shrink: 0; display: flex; justify-content: center; align-items: center; gap: 25px;">
        <?php if ($show_facebook && !empty($facebook_text)): ?>
        <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: 15px; font-weight: 600;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            <span><?php echo esc_html($facebook_text); ?></span>
        </div>
        <?php endif; ?>
        <?php if ($show_youtube && !empty($youtube_text)): ?>
        <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: 15px; font-weight: 600;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
            <span><?php echo esc_html($youtube_text); ?></span>
        </div>
        <?php endif; ?>
        <?php if ($show_website && !empty($website_text)): ?>
        <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: 15px; font-weight: 600;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="white"><circle cx="12" cy="12" r="10"/></svg>
            <span><?php echo esc_html($website_text); ?></span>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

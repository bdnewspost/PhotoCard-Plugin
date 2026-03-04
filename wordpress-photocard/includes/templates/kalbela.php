<?php
/**
 * Kalbela Template - EXACT match to reference
 * 
 * Reference analysis:
 * - FULL RED background (entire 1080x1080 is red)
 * - Logo at top-left corner on red bg
 * - Date at top-right on red bg (white text)
 * - Featured image centered with decorative angular red shapes on LEFT and RIGHT sides
 * - Title: Large WHITE bold text on red background at bottom
 * - "❮❮ নিউজ লিংক কমেন্টে ❯❯" in golden/yellow at bottom
 */
if (!defined('ABSPATH')) exit;

$kalbela_bg = isset($options['kalbela_bg_color']) ? $options['kalbela_bg_color'] : '#cc0000';
?>
<div class="pcd-photocard" data-language="<?php echo esc_attr($language); ?>" data-quality="<?php echo esc_attr($image_quality); ?>" style="width: 1080px; height: 1080px; background: <?php echo esc_attr($kalbela_bg); ?>; padding: 0; position: relative; display: flex; flex-direction: column; box-sizing: border-box; overflow: hidden;">
    
    <!-- Red Header Bar with Logo + Date -->
    <div style="padding: 18px 30px; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0; min-height: 100px;">
        <?php if ($logo_position === 'right'): ?>
            <?php if ($enable_date): ?>
            <div style="color: #ffffff; font-size: 32px; font-weight: 700; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">
                <?php echo esc_html($formatted_date); ?>
            </div>
            <?php endif; ?>
            <?php if ($enable_logo && !empty($watermark_logo)): ?>
            <img src="<?php echo esc_url($watermark_logo); ?>" alt="Logo" style="height: 90px; width: auto; display: block; filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.3));" crossorigin="anonymous">
            <?php endif; ?>
        <?php else: ?>
            <?php if ($enable_logo && !empty($watermark_logo)): ?>
            <img src="<?php echo esc_url($watermark_logo); ?>" alt="Logo" style="height: 90px; width: auto; display: block; filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.3));" crossorigin="anonymous">
            <?php endif; ?>
            <?php if ($enable_date): ?>
            <div style="color: #ffffff; font-size: 32px; font-weight: 700; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">
                <?php echo esc_html($formatted_date); ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Featured Image Area with Decorative Side Shapes -->
    <div style="flex: 1; padding: 0 0; min-height: 0; overflow: hidden; position: relative;">
        
        <!-- Left Decorative Angular Shape (red triangle pointing right) -->
        <div style="position: absolute; left: 0; top: 0; bottom: 0; width: 45px; z-index: 3; overflow: hidden;">
            <svg width="45" height="100%" viewBox="0 0 45 600" preserveAspectRatio="none" style="display: block; height: 100%; width: 100%;">
                <polygon points="0,0 45,30 45,570 0,600" fill="<?php echo esc_attr($kalbela_bg); ?>"/>
            </svg>
        </div>
        
        <!-- Right Decorative Angular Shape (red triangle pointing left) -->
        <div style="position: absolute; right: 0; top: 0; bottom: 0; width: 45px; z-index: 3; overflow: hidden;">
            <svg width="45" height="100%" viewBox="0 0 45 600" preserveAspectRatio="none" style="display: block; height: 100%; width: 100%;">
                <polygon points="0,30 45,0 45,600 0,570" fill="<?php echo esc_attr($kalbela_bg); ?>"/>
            </svg>
        </div>

        <!-- Top Decorative Angle -->
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 30px; z-index: 3; overflow: hidden;">
            <svg width="100%" height="30" viewBox="0 0 1080 30" preserveAspectRatio="none" style="display: block;">
                <polygon points="0,0 45,30 1035,30 1080,0" fill="<?php echo esc_attr($kalbela_bg); ?>"/>
            </svg>
        </div>

        <!-- Bottom Decorative Angle -->
        <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 30px; z-index: 3; overflow: hidden;">
            <svg width="100%" height="30" viewBox="0 0 1080 30" preserveAspectRatio="none" style="display: block;">
                <polygon points="45,0 0,30 1080,30 1035,0" fill="<?php echo esc_attr($kalbela_bg); ?>"/>
            </svg>
        </div>

        <!-- The Image -->
        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="width: 100%; height: 100%; object-fit: cover; display: block;" crossorigin="anonymous">
    </div>

    <!-- Title Section on Red Background -->
    <div style="padding: 20px 40px 8px; flex-shrink: 0;">
        <div id="pcd-adjustable-title" class="pcd-title" style="color: #ffffff; font-size: <?php echo esc_attr($default_font_size); ?>px; line-height: <?php echo esc_attr($default_line_height); ?>; font-weight: 900; text-align: <?php echo esc_attr($title_alignment); ?>; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; word-wrap: break-word; overflow-wrap: break-word; text-shadow: 2px 2px 6px rgba(0,0,0,0.4);">
            <?php echo esc_html($post_title); ?>
        </div>
    </div>

    <!-- Details Button with decorative chevrons - golden/yellow style -->
    <?php if ($show_details_button): ?>
    <div style="text-align: center; padding: 10px 35px 20px; flex-shrink: 0;">
        <span style="color: #FFD700; font-size: 28px; font-weight: 700; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; letter-spacing: 2px; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
            ❮❮ <?php echo esc_html($details_button_text); ?> ❯❯
        </span>
    </div>
    <?php endif; ?>

    <!-- Social Links on slightly darker red -->
    <?php if ($show_facebook || $show_youtube || $show_website || $show_instagram || $show_linkedin): ?>
    <div style="background: rgba(0,0,0,0.2); padding: 12px 25px; display: flex; justify-content: center; align-items: center; gap: 25px; flex-wrap: wrap; flex-shrink: 0;">
        <?php if ($show_facebook && !empty($facebook_text)): ?>
        <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: 16px; font-weight: 500;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            <span><?php echo esc_html($facebook_text); ?></span>
        </div>
        <?php endif; ?>
        <?php if ($show_youtube && !empty($youtube_text)): ?>
        <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: 16px; font-weight: 500;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="white"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
            <span><?php echo esc_html($youtube_text); ?></span>
        </div>
        <?php endif; ?>
        <?php if ($show_website && !empty($website_text)): ?>
        <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: 16px; font-weight: 500;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="white"><circle cx="12" cy="12" r="10"/></svg>
            <span><?php echo esc_html($website_text); ?></span>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<?php
/**
 * Kalbela Template - EXACT match to reference
 * 
 * Reference analysis:
 * - FULL RED background (entire 1080x1080)
 * - Logo at top-left, Date at top-right (white text on red)
 * - Featured image centered with PROMINENT angular red triangular cuts at all 4 corners
 * - The red shapes create a trapezoid/octagonal frame effect
 * - Title: Large WHITE bold text on red background below image
 * - "❮❮ নিউজ লিংক কমেন্টে ❯❯" in golden/yellow at bottom
 */
if (!defined('ABSPATH')) exit;

$kalbela_bg = isset($options['kalbela_bg_color']) ? $options['kalbela_bg_color'] : '#cc0000';
?>
<div class="pcd-photocard" data-language="<?php echo esc_attr($language); ?>" data-quality="<?php echo esc_attr($image_quality); ?>" style="width: 1080px; height: 1080px; background: <?php echo esc_attr($kalbela_bg); ?>; padding: 0; position: relative; display: flex; flex-direction: column; box-sizing: border-box; overflow: hidden;">
    
    <!-- Red Header Bar with Logo + Date -->
    <div style="padding: 20px 30px 10px; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0; min-height: 90px;">
        <?php if ($logo_position === 'right'): ?>
            <?php if ($enable_date): ?>
            <div style="color: #ffffff; font-size: 34px; font-weight: 700; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">
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
            <div style="color: #ffffff; font-size: 34px; font-weight: 700; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">
                <?php echo esc_html($formatted_date); ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Featured Image Area with Decorative Angular Frame -->
    <div style="flex: 1; position: relative; margin: 0 30px; min-height: 0; overflow: hidden;">
        
        <!-- The Image (fills the container) -->
        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="width: 100%; height: 100%; object-fit: cover; display: block;" crossorigin="anonymous">
        
        <!-- Top-Left Corner Triangle -->
        <div style="position: absolute; top: 0; left: 0; z-index: 3;">
            <svg width="80" height="80" viewBox="0 0 80 80" style="display: block;">
                <polygon points="0,0 80,0 0,80" fill="<?php echo esc_attr($kalbela_bg); ?>"/>
            </svg>
        </div>
        
        <!-- Top-Right Corner Triangle -->
        <div style="position: absolute; top: 0; right: 0; z-index: 3;">
            <svg width="80" height="80" viewBox="0 0 80 80" style="display: block;">
                <polygon points="0,0 80,0 80,80" fill="<?php echo esc_attr($kalbela_bg); ?>"/>
            </svg>
        </div>
        
        <!-- Bottom-Left Corner Triangle -->
        <div style="position: absolute; bottom: 0; left: 0; z-index: 3;">
            <svg width="80" height="80" viewBox="0 0 80 80" style="display: block;">
                <polygon points="0,0 0,80 80,80" fill="<?php echo esc_attr($kalbela_bg); ?>"/>
            </svg>
        </div>
        
        <!-- Bottom-Right Corner Triangle -->
        <div style="position: absolute; bottom: 0; right: 0; z-index: 3;">
            <svg width="80" height="80" viewBox="0 0 80 80" style="display: block;">
                <polygon points="80,0 0,80 80,80" fill="<?php echo esc_attr($kalbela_bg); ?>"/>
            </svg>
        </div>

        <!-- Left Side Decorative Stripe -->
        <div style="position: absolute; left: 0; top: 80px; bottom: 80px; width: 8px; z-index: 3; background: <?php echo esc_attr($kalbela_bg); ?>;"></div>
        
        <!-- Right Side Decorative Stripe -->
        <div style="position: absolute; right: 0; top: 80px; bottom: 80px; width: 8px; z-index: 3; background: <?php echo esc_attr($kalbela_bg); ?>;"></div>
        
        <!-- Top Edge Stripe -->
        <div style="position: absolute; top: 0; left: 80px; right: 80px; height: 8px; z-index: 3; background: <?php echo esc_attr($kalbela_bg); ?>;"></div>
        
        <!-- Bottom Edge Stripe -->
        <div style="position: absolute; bottom: 0; left: 80px; right: 80px; height: 8px; z-index: 3; background: <?php echo esc_attr($kalbela_bg); ?>;"></div>
    </div>

    <!-- Title Section on Red Background -->
    <div style="padding: 22px 40px 8px; flex-shrink: 0;">
        <div id="pcd-adjustable-title" class="pcd-title" style="color: #ffffff; font-size: <?php echo esc_attr($default_font_size); ?>px; line-height: <?php echo esc_attr($default_line_height); ?>; font-weight: 900; text-align: <?php echo esc_attr($title_alignment); ?>; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; word-wrap: break-word; overflow-wrap: break-word; text-shadow: 2px 2px 6px rgba(0,0,0,0.4);">
            <?php echo esc_html($post_title); ?>
        </div>
    </div>

    <!-- Details Button with decorative chevrons - golden/yellow style -->
    <?php if ($show_details_button): ?>
    <div style="text-align: center; padding: 8px 35px 22px; flex-shrink: 0;">
        <span style="color: #FFD700; font-size: 30px; font-weight: 700; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; letter-spacing: 2px; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
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

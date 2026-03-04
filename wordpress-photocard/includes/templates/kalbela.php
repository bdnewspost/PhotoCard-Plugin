<?php
/**
 * Kalbela Template - Uses actual background image
 * 
 * Layout:
 * - Background image (kalbela-bg.png) with red angular shapes and world map pattern
 * - Post image fills the white/center area
 * - Logo at top corner on the red angular area
 * - Date on opposite side
 * - Title: Large WHITE bold text on the red/map area below image
 * - "❮❮ নিউজ লিংক কমেন্টে ❯❯" golden text
 * - Social links at bottom
 */
if (!defined('ABSPATH')) exit;

$kalbela_bg = isset($options['kalbela_bg_color']) ? $options['kalbela_bg_color'] : '#cc0000';
$plugin_url = plugin_dir_url(dirname(dirname(__FILE__)));
$bg_image_url = $plugin_url . 'assets/images/kalbela-bg.png';
?>
<div class="pcd-photocard" data-language="<?php echo esc_attr($language); ?>" data-quality="<?php echo esc_attr($image_quality); ?>" style="width: 1080px; height: 1080px; padding: 0; position: relative; overflow: hidden; box-sizing: border-box; background: <?php echo esc_attr($kalbela_bg); ?>;">
    
    <!-- Background Template Image (angular shapes + world map) -->
    <img src="<?php echo esc_url($bg_image_url); ?>" alt="" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;" crossorigin="anonymous">

    <!-- Post Featured Image - positioned in the white/center area -->
    <!-- Based on the bg: white area is roughly top:5% to 55%, left:5% to 92% (accounting for angular cuts) -->
    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 58%; object-fit: cover; z-index: 2;" crossorigin="anonymous">

    <!-- The angular red shapes from the background image will naturally overlay because the bg image is on top layer for edges -->
    <!-- We need to re-create the angular mask effect so the bg angular shapes show over the post image -->
    
    <!-- Top-left angular red shape overlay -->
    <div style="position: absolute; top: 0; left: 0; z-index: 3; pointer-events: none;">
        <svg width="380" height="350" viewBox="0 0 380 350" style="display: block;">
            <polygon points="0,0 0,350 320,0" fill="<?php echo esc_attr($kalbela_bg); ?>"/>
        </svg>
    </div>

    <!-- Top-right angular red shape overlay -->
    <div style="position: absolute; top: 0; right: 0; z-index: 3; pointer-events: none;">
        <svg width="200" height="180" viewBox="0 0 200 180" style="display: block;">
            <polygon points="40,0 200,0 200,180" fill="<?php echo esc_attr($kalbela_bg); ?>"/>
        </svg>
    </div>

    <!-- Bottom-left angular red shape (transition to map area) -->
    <div style="position: absolute; bottom: 420; left: 0; z-index: 3; pointer-events: none;">
        <svg width="120" height="100" viewBox="0 0 120 100" style="display: block;">
            <polygon points="0,0 0,100 120,100" fill="<?php echo esc_attr($kalbela_bg); ?>"/>
        </svg>
    </div>

    <!-- Bottom-right angular red shape (transition to map area) -->
    <div style="position: absolute; right: 0; z-index: 3; pointer-events: none; top: 520px;">
        <svg width="120" height="100" viewBox="0 0 120 100" style="display: block;">
            <polygon points="120,0 0,100 120,100" fill="<?php echo esc_attr($kalbela_bg); ?>"/>
        </svg>
    </div>

    <!-- Logo positioned over the red angular area -->
    <?php if ($logo_position === 'right'): ?>
        <?php if ($enable_date): ?>
        <div style="position: absolute; top: 25px; left: 30px; z-index: 10; color: #ffffff; font-size: 34px; font-weight: 700; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">
            <?php echo esc_html($formatted_date); ?>
        </div>
        <?php endif; ?>
        <?php if ($enable_logo && !empty($watermark_logo)): ?>
        <div style="position: absolute; top: 20px; right: 25px; z-index: 10;">
            <img src="<?php echo esc_url($watermark_logo); ?>" alt="Logo" style="height: 90px; width: auto; display: block; filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.3));" crossorigin="anonymous">
        </div>
        <?php endif; ?>
    <?php else: ?>
        <?php if ($enable_logo && !empty($watermark_logo)): ?>
        <div style="position: absolute; top: 20px; left: 25px; z-index: 10;">
            <img src="<?php echo esc_url($watermark_logo); ?>" alt="Logo" style="height: 90px; width: auto; display: block; filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.3));" crossorigin="anonymous">
        </div>
        <?php endif; ?>
        <?php if ($enable_date): ?>
        <div style="position: absolute; top: 25px; right: 30px; z-index: 10; color: #ffffff; font-size: 34px; font-weight: 700; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">
            <?php echo esc_html($formatted_date); ?>
        </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Bottom Content: Title + Details + Social (over the world map area) -->
    <div style="position: absolute; bottom: 0; left: 0; right: 0; z-index: 5; display: flex; flex-direction: column;">

        <!-- Title -->
        <div style="padding: 22px 40px 8px;">
            <div id="pcd-adjustable-title" class="pcd-title" style="color: #ffffff; font-size: <?php echo esc_attr($default_font_size); ?>px; line-height: <?php echo esc_attr($default_line_height); ?>; font-weight: 900; text-align: <?php echo esc_attr($title_alignment); ?>; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; word-wrap: break-word; overflow-wrap: break-word; text-shadow: 2px 2px 6px rgba(0,0,0,0.4);">
                <?php echo esc_html($post_title); ?>
            </div>
        </div>

        <!-- Details Button -->
        <?php if ($show_details_button): ?>
        <div style="text-align: center; padding: 8px 35px 22px;">
            <span style="color: #FFD700; font-size: 30px; font-weight: 700; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; letter-spacing: 2px; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
                ❮❮ <?php echo esc_html($details_button_text); ?> ❯❯
            </span>
        </div>
        <?php endif; ?>

        <!-- Social Links -->
        <?php if ($show_facebook || $show_youtube || $show_website || $show_instagram || $show_linkedin): ?>
        <div style="background: rgba(0,0,0,0.3); padding: 12px 25px; display: flex; justify-content: center; align-items: center; gap: 25px; flex-wrap: wrap;">
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
</div>
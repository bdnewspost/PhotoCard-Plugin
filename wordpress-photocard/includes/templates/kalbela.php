<?php
/**
 * Kalbela Template - Background image handles all decorative elements
 * Just position: image, logo, date, title, details, social links
 */
if (!defined('ABSPATH')) exit;

$kalbela_bg = isset($options['kalbela_bg_color']) ? $options['kalbela_bg_color'] : '#cc0000';
$plugin_url = plugin_dir_url(dirname(dirname(__FILE__)));
$bg_image_url = $plugin_url . 'assets/images/kalbela-bg.png';
?>
<div class="pcd-photocard" data-language="<?php echo esc_attr($language); ?>" data-quality="<?php echo esc_attr($image_quality); ?>" style="width: 1080px; height: 1080px; padding: 0; position: relative; overflow: hidden; box-sizing: border-box; background: <?php echo esc_attr($kalbela_bg); ?>;">
    
    <!-- Post Featured Image - behind the background frame -->
    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 62%; object-fit: cover; z-index: 1;" crossorigin="anonymous">

    <!-- Background Template Image (angular frame + world map) - on top to frame the image -->
    <img src="<?php echo esc_url($bg_image_url); ?>" alt="" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 2;" crossorigin="anonymous">

    <!-- Date - Top Left -->
    <?php if ($enable_date): ?>
    <div style="position: absolute; top: 25px; left: 30px; z-index: 10; color: #ffffff; font-size: 34px; font-weight: 700; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">
        <?php echo esc_html($formatted_date); ?>
    </div>
    <?php endif; ?>

    <!-- Logo - Top Right -->
    <?php if ($enable_logo && !empty($watermark_logo)): ?>
    <div style="position: absolute; top: 20px; <?php echo ($logo_position === 'left') ? 'left: 25px;' : (($logo_position === 'center') ? 'left: 50%; transform: translateX(-50%);' : 'right: 25px;'); ?> z-index: 10;">
        <img src="<?php echo esc_url($watermark_logo); ?>" alt="Logo" style="height: 90px; width: auto; display: block; filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.3));" crossorigin="anonymous">
    </div>
    <?php endif; ?>

    <!-- Bottom Content Area -->
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

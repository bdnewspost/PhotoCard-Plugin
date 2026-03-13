<?php
/**
 * Daily Shadhin Template
 * Layout: Full image top, bottom bar with date (left), domain (center), details (right)
 */
if (!defined('ABSPATH')) exit;

$plugin_url = plugin_dir_url(dirname(dirname(__FILE__)));
$bg_image_url = !empty($custom_bg_image) ? $custom_bg_image : '';
$_title_offset = isset($title_top_offset) ? intval($title_top_offset) : 0;
$_details_offset = isset($details_bottom_offset) ? intval($details_bottom_offset) : 0;
$_domain_text = isset($domain_text) ? $domain_text : '';
$_show_domain = isset($show_domain) ? $show_domain : true;
?>
<div class="pcd-photocard" data-language="<?php echo esc_attr($language); ?>" data-quality="<?php echo esc_attr($image_quality); ?>" style="width: 1080px; height: 1080px; padding: 0; position: relative; overflow: hidden; box-sizing: border-box; background: #1a1a2e;">
    
    <!-- Post Featured Image -->
    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: <?php echo !empty($bg_image_url) ? '70' : '65'; ?>%; object-fit: cover; object-position: center center; z-index: 1;" crossorigin="anonymous">

    <?php if (!empty($bg_image_url)): ?>
    <!-- Custom Background Template Image -->
    <img src="<?php echo esc_url($bg_image_url); ?>" alt="" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 2;" crossorigin="anonymous">
    <?php else: ?>
    <!-- Default gradient overlay -->
    <div style="position: absolute; top: 55%; left: 0; right: 0; bottom: 0; background: linear-gradient(to bottom, transparent 0%, rgba(26,26,46,0.8) 15%, #1a1a2e 35%); z-index: 2;"></div>
    <?php endif; ?>

    <!-- Logo -->
    <?php if ($enable_logo && !empty($watermark_logo)): ?>
    <div style="position: absolute; top: 20px; <?php echo ($logo_position === 'left') ? 'left: 25px;' : (($logo_position === 'center') ? 'left: 50%; transform: translateX(-50%);' : 'right: 25px;'); ?> z-index: 10;">
        <img src="<?php echo esc_url($watermark_logo); ?>" alt="Logo" style="height: 80px; width: auto; display: block; filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.3));" crossorigin="anonymous">
    </div>
    <?php endif; ?>

    <!-- Bottom Content Area -->
    <div style="position: absolute; bottom: 0; left: 0; right: 0; z-index: 5; display: flex; flex-direction: column; justify-content: flex-end;">

        <!-- Title -->
        <div style="padding: 0 45px; margin-bottom: <?php echo (15 - $_title_offset); ?>px;">
            <div id="pcd-adjustable-title" class="pcd-title" style="color: #ffffff; font-size: <?php echo esc_attr($default_font_size); ?>px; line-height: <?php echo esc_attr($default_line_height); ?>; font-weight: 900; text-align: <?php echo esc_attr($title_alignment); ?>; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; word-wrap: break-word; overflow-wrap: break-word; text-shadow: 2px 2px 6px rgba(0,0,0,0.5);">
                <?php echo esc_html($post_title); ?>
            </div>
        </div>

        <!-- Bottom Bar: Date (left) | Domain (center) | Details (right) -->
        <div style="display: flex; justify-content: space-between; align-items: center; padding: <?php echo (18 + $_details_offset); ?>px 40px; background: rgba(0,0,0,0.6); border-top: 2px solid rgba(255,255,255,0.15);">
            
            <!-- Date - Left -->
            <?php if ($enable_date): ?>
            <div style="color: #ffffff; font-size: 24px; font-weight: 700; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">
                <?php echo esc_html($formatted_date); ?>
            </div>
            <?php else: ?>
            <div></div>
            <?php endif; ?>

            <!-- Domain - Center -->
            <?php if ($_show_domain && !empty($_domain_text)): ?>
            <div style="color: #FFD700; font-size: 26px; font-weight: 800; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; letter-spacing: 1px; text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">
                <?php echo esc_html($_domain_text); ?>
            </div>
            <?php else: ?>
            <div></div>
            <?php endif; ?>

            <!-- Details - Right -->
            <?php if ($show_details_button): ?>
            <div style="color: #FFD700; font-size: 24px; font-weight: 700; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">
                &raquo; <?php echo esc_html($details_button_text); ?> &laquo;
            </div>
            <?php else: ?>
            <div></div>
            <?php endif; ?>
        </div>

        <!-- Social Links -->
        <?php if ($show_facebook || $show_youtube || $show_website || $show_instagram || $show_linkedin): ?>
        <div style="padding: 12px 25px 16px; display: flex; justify-content: center; align-items: center; gap: 25px; flex-wrap: wrap; background: rgba(0,0,0,0.4);">
            <?php if ($show_facebook && !empty($facebook_text)): ?>
            <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: 16px; font-weight: 500;">
                <svg width="18" height="18" viewBox="0 0 24 24"><circle cx="12" cy="12" r="12" fill="#1877f2"/><path d="M16.5 12.5h-2.5v7h-3v-7h-2v-2.5h2v-1.5c0-2.2 1-3.5 3.5-3.5h2v2.5h-1.5c-.8 0-1 .3-1 1v1.5h2.5l-.5 2.5z" fill="white"/></svg>
                <span><?php echo esc_html($facebook_text); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($show_instagram && !empty($instagram_text)): ?>
            <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: 16px; font-weight: 500;">
                <svg width="18" height="18" viewBox="0 0 24 24"><rect width="24" height="24" rx="6" fill="url(#ig-grad-ds)"/><circle cx="12" cy="12" r="5" stroke="white" stroke-width="2" fill="none"/><circle cx="17.5" cy="6.5" r="1.5" fill="white"/><defs><linearGradient id="ig-grad-ds" x1="0" y1="24" x2="24" y2="0"><stop offset="0%" stop-color="#feda75"/><stop offset="25%" stop-color="#fa7e1e"/><stop offset="50%" stop-color="#d62976"/><stop offset="75%" stop-color="#962fbf"/><stop offset="100%" stop-color="#4f5bd5"/></linearGradient></defs></svg>
                <span><?php echo esc_html($instagram_text); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($show_youtube && !empty($youtube_text)): ?>
            <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: 16px; font-weight: 500;">
                <svg width="18" height="18" viewBox="0 0 24 24"><rect width="24" height="24" rx="6" fill="#FF0000"/><path d="M10 8.5v7l5.5-3.5L10 8.5z" fill="white"/></svg>
                <span><?php echo esc_html($youtube_text); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($show_linkedin && !empty($linkedin_text)): ?>
            <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: 16px; font-weight: 500;">
                <svg width="18" height="18" viewBox="0 0 24 24"><rect width="24" height="24" rx="4" fill="#0077B5"/><path d="M8 10v7H5.5v-7H8zm-1.25-1.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM19 17h-2.5v-3.5c0-1-.4-1.7-1.3-1.7-.7 0-1.1.5-1.3 1-.1.1-.1.3-.1.5V17H11.5s0-6.5 0-7h2.3l.2 1c.5-.7 1.2-1.2 2.3-1.2 1.7 0 2.7 1.1 2.7 3.5V17z" fill="white"/></svg>
                <span><?php echo esc_html($linkedin_text); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($show_website && !empty($website_text)): ?>
            <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: 16px; font-weight: 500;">
                <svg width="18" height="18" viewBox="0 0 24 24"><circle cx="12" cy="12" r="11" fill="#4CAF50"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" fill="white"/></svg>
                <span><?php echo esc_html($website_text); ?></span>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

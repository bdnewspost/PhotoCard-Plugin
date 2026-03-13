<?php
/**
 * News24 Template - Background image handles all decorative elements
 * Matches reference: logo top-right, date right-bottom of image area,
 * title starts higher in overlay, social bar at bottom
 */
if (!defined('ABSPATH')) exit;

$news24_title_color = isset($options['news24_text_color']) ? $options['news24_text_color'] : '#FFD700';
$news24_date_bg = isset($options['news24_date_bg']) ? $options['news24_date_bg'] : '#1a3a5c';
$plugin_url = plugin_dir_url(dirname(dirname(__FILE__)));
$bg_image_url = !empty($custom_bg_image) ? $custom_bg_image : $plugin_url . 'assets/images/news24-bg.png';
$_title_offset = isset($title_top_offset) ? intval($title_top_offset) : 0;
$_details_offset = isset($details_bottom_offset) ? intval($details_bottom_offset) : 0;
$_content_top = 540 + $_title_offset;
?>
<div class="pcd-photocard" data-language="<?php echo esc_attr($language); ?>" data-quality="<?php echo esc_attr($image_quality); ?>" style="width: 1080px; height: 1080px; padding: 0; position: relative; overflow: hidden; box-sizing: border-box; background: #1a0505;">
    
    <!-- Post Featured Image -->
    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 68%; object-fit: cover; object-position: center center; z-index: 1;" crossorigin="anonymous">

    <!-- Background Template Image -->
    <img src="<?php echo esc_url($bg_image_url); ?>" alt="" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 2;" crossorigin="anonymous">

    <!-- Logo -->
    <?php if ($enable_logo && !empty($watermark_logo)): ?>
    <div style="position: absolute; top: 20px; <?php echo ($logo_position === 'left') ? 'left: 25px;' : (($logo_position === 'center') ? 'left: 50%; transform: translateX(-50%);' : 'right: 25px;'); ?> z-index: 10;">
        <img src="<?php echo esc_url($watermark_logo); ?>" alt="Logo" style="height: 80px; width: auto; display: block;" crossorigin="anonymous">
    </div>
    <?php endif; ?>

    <!-- Date Badge - right side, at bottom edge of image area -->
    <?php if ($enable_date): ?>
<!--     <div style="position: absolute; right: 0px; bottom: 350px; z-index: 10;">
        <span style="display: inline-block; color: #ffffff; font-size: 28px; font-weight: 700; background: <?php echo esc_attr($news24_date_bg); ?>; padding: 10px 24px; border-radius: 5px; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; letter-spacing: 1px;">
            <?php echo esc_html($formatted_date); ?>
        </span>
    </div> -->
	
	
	<div style="position: absolute; right: 0px; bottom: 350px; z-index: 10;">
    <span style="
        display: inline-block;
        color: #ffffff;
        font-size: 28px;
        font-weight: 700;
        background: <?php echo esc_attr($news24_date_bg); ?>;
        padding: 14px 50px;
        font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif;
        letter-spacing: 1px;
        clip-path: polygon(60px 0%, 100% 0%, 100% 100%, 0% 100%, 0% 60px);
    ">
        <?php echo esc_html($formatted_date); ?>
    </span>
</div>
    <?php endif; ?>

    <!-- Bottom Content Area -->
    <div style="position: absolute; top: <?php echo $_content_top; ?>px; bottom: 0; left: 0; right: 0; z-index: 5; display: flex; flex-direction: column; justify-content: flex-start; padding-top: 30px;">

        <!-- Title -->
        <div id="pcd-adjustable-title" class="pcd-title" style="color: <?php echo esc_attr($news24_title_color); ?>; font-size: <?php echo esc_attr($default_font_size); ?>px; line-height: <?php echo esc_attr($default_line_height); ?>; font-weight: 900; text-align: <?php echo esc_attr($title_alignment); ?>; padding: 0 50px; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; word-wrap: break-word; overflow-wrap: break-word; text-shadow: 2px 2px 6px rgba(0,0,0,0.5);">
            <?php echo esc_html($post_title); ?>
        </div>

        <!-- Spacer -->
        <div style="flex: 1;"></div>

        <!-- Details Button -->
        <?php if ($show_details_button): ?>
        <div style="text-align: center; padding: 0 35px <?php echo (12 + $_details_offset); ?>px;">
            <span style="color: #FFD700; font-size: 28px; font-weight: 600; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; letter-spacing: 2px;">
                &raquo;&raquo; <?php echo esc_html($details_button_text); ?> &laquo;&laquo;
            </span>
        </div>
        <?php endif; ?>

        <!-- Social Links Bar -->
        <?php if ($show_facebook || $show_youtube || $show_website || $show_instagram || $show_linkedin): ?>
        <div style="padding: 10px 30px 14px; display: flex; justify-content: center; align-items: center; gap: 35px; flex-wrap: wrap;">
            <?php if ($show_facebook && !empty($facebook_text)): ?>
            <div style="display: flex; align-items: center; gap: 8px; color: white; font-size: 17px; font-weight: 500;">
                <svg width="20" height="20" viewBox="0 0 24 24"><circle cx="12" cy="12" r="12" fill="#1877f2"/><path d="M16.5 12.5h-2.5v7h-3v-7h-2v-2.5h2v-1.5c0-2.2 1-3.5 3.5-3.5h2v2.5h-1.5c-.8 0-1 .3-1 1v1.5h2.5l-.5 2.5z" fill="white"/></svg>
                <span><?php echo esc_html($facebook_text); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($show_instagram && !empty($instagram_text)): ?>
            <div style="display: flex; align-items: center; gap: 8px; color: white; font-size: 17px; font-weight: 500;">
                <svg width="20" height="20" viewBox="0 0 24 24"><rect width="24" height="24" rx="6" fill="url(#ig-grad-n24)"/><circle cx="12" cy="12" r="5" stroke="white" stroke-width="2" fill="none"/><circle cx="17.5" cy="6.5" r="1.5" fill="white"/><defs><linearGradient id="ig-grad-n24" x1="0" y1="24" x2="24" y2="0"><stop offset="0%" stop-color="#feda75"/><stop offset="25%" stop-color="#fa7e1e"/><stop offset="50%" stop-color="#d62976"/><stop offset="75%" stop-color="#962fbf"/><stop offset="100%" stop-color="#4f5bd5"/></linearGradient></defs></svg>
                <span><?php echo esc_html($instagram_text); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($show_youtube && !empty($youtube_text)): ?>
            <div style="display: flex; align-items: center; gap: 8px; color: white; font-size: 17px; font-weight: 500;">
                <svg width="20" height="20" viewBox="0 0 24 24"><rect width="24" height="24" rx="6" fill="#FF0000"/><path d="M10 8.5v7l5.5-3.5L10 8.5z" fill="white"/></svg>
                <span><?php echo esc_html($youtube_text); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($show_linkedin && !empty($linkedin_text)): ?>
            <div style="display: flex; align-items: center; gap: 8px; color: white; font-size: 17px; font-weight: 500;">
                <svg width="20" height="20" viewBox="0 0 24 24"><rect width="24" height="24" rx="4" fill="#0077B5"/><path d="M8 10v7H5.5v-7H8zm-1.25-1.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM19 17h-2.5v-3.5c0-1-.4-1.7-1.3-1.7-.7 0-1.1.5-1.3 1-.1.1-.1.3-.1.5V17H11.5s0-6.5 0-7h2.3l.2 1c.5-.7 1.2-1.2 2.3-1.2 1.7 0 2.7 1.1 2.7 3.5V17z" fill="white"/></svg>
                <span><?php echo esc_html($linkedin_text); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($show_website && !empty($website_text)): ?>
            <div style="display: flex; align-items: center; gap: 8px; color: white; font-size: 17px; font-weight: 500;">
                <svg width="20" height="20" viewBox="0 0 24 24"><circle cx="12" cy="12" r="11" fill="#4CAF50"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" fill="white"/></svg>
                <span><?php echo esc_html($website_text); ?></span>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
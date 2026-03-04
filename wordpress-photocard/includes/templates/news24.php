<?php
/**
 * News24 Template - EXACT match to reference
 * 
 * Reference analysis:
 * - Full-bleed background image (1080x1080)
 * - Logo at TOP-RIGHT corner
 * - Heavy dark gradient overlay from bottom (~65%)
 * - Date badge: blue/dark background, white text, positioned bottom-right ABOVE title
 * - Title: Large GOLDEN/YELLOW bold text with heavy text-shadow, ON the gradient
 * - ">> বিস্তারিত কমেন্টে <<" below title in white
 * - Social links bar at very bottom with dark semi-transparent bg
 * - NO yellow solid bar - title sits directly on gradient
 */
if (!defined('ABSPATH')) exit;

$news24_title_color = isset($options['news24_title_color']) ? $options['news24_title_color'] : '#FFD700';
$news24_date_bg = isset($options['news24_date_bg']) ? $options['news24_date_bg'] : '#1a5fb4';
?>
<div class="pcd-photocard" data-language="<?php echo esc_attr($language); ?>" data-quality="<?php echo esc_attr($image_quality); ?>" style="width: 1080px; height: 1080px; background: #000; padding: 0; position: relative; overflow: hidden; box-sizing: border-box;">
    
    <!-- Full Bleed Background Image -->
    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;" crossorigin="anonymous">

    <!-- Dark Gradient Overlay - heavy from bottom -->
    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 70%; background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.85) 30%, rgba(0,0,0,0.5) 60%, transparent 100%); z-index: 2;"></div>

    <!-- Logo (top-right matching reference) -->
    <?php if ($enable_logo && !empty($watermark_logo)): ?>
    <div style="position: absolute; top: 20px; <?php echo ($logo_position === 'left') ? 'left: 25px;' : (($logo_position === 'center') ? 'left: 50%; transform: translateX(-50%);' : 'right: 25px;'); ?> z-index: 10;">
        <img src="<?php echo esc_url($watermark_logo); ?>" alt="Logo" style="height: 80px; width: auto; display: block;" crossorigin="anonymous">
    </div>
    <?php endif; ?>

    <!-- Bottom Content Area - all on gradient -->
    <div style="position: absolute; bottom: 0; left: 0; right: 0; z-index: 5; display: flex; flex-direction: column;">
        
        <!-- Date Badge (above title, right-aligned) -->
        <?php if ($enable_date): ?>
        <div style="text-align: <?php echo esc_attr($date_position); ?>; padding: 0 35px; margin-bottom: 15px;">
            <span style="display: inline-block; color: #ffffff; font-size: 26px; font-weight: 700; background: <?php echo esc_attr($news24_date_bg); ?>; padding: 8px 22px; border-radius: 4px; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; letter-spacing: 1px;">
                <?php echo esc_html($formatted_date); ?>
            </span>
        </div>
        <?php endif; ?>

        <!-- Title - Golden bold on gradient -->
        <div id="pcd-adjustable-title" class="pcd-title" style="color: <?php echo esc_attr($news24_title_color); ?>; font-size: <?php echo esc_attr($default_font_size); ?>px; line-height: <?php echo esc_attr($default_line_height); ?>; font-weight: 900; text-align: <?php echo esc_attr($title_alignment); ?>; padding: 0 35px; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; word-wrap: break-word; overflow-wrap: break-word; text-shadow: 3px 3px 8px rgba(0,0,0,0.9), 0 0 20px rgba(0,0,0,0.5);">
            <?php echo esc_html($post_title); ?>
        </div>

        <!-- Details Button -->
        <?php if ($show_details_button): ?>
        <div style="text-align: center; padding: 15px 35px 5px;">
            <span style="color: #ffffff; font-size: 24px; font-weight: 600; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; letter-spacing: 1px;">
                ❯❯ <?php echo esc_html($details_button_text); ?> ❮❮
            </span>
        </div>
        <?php endif; ?>

        <!-- Social Links Bar - dark bg at very bottom -->
        <?php if ($show_facebook || $show_youtube || $show_website || $show_instagram || $show_linkedin): ?>
        <div style="background: rgba(0,0,0,0.8); padding: 14px 25px; margin-top: 12px; display: flex; justify-content: center; align-items: center; gap: 30px; flex-wrap: wrap;">
            <?php if ($show_facebook && !empty($facebook_text)): ?>
            <div style="display: flex; align-items: center; gap: 8px; color: white; font-size: 17px; font-weight: 500;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="#1877f2"><circle cx="12" cy="12" r="12" fill="#1877f2"/><path d="M16.5 12.5h-2.5v7h-3v-7h-2v-2.5h2v-1.5c0-2.2 1-3.5 3.5-3.5h2v2.5h-1.5c-.8 0-1 .3-1 1v1.5h2.5l-.5 2.5z" fill="white"/></svg>
                <span><?php echo esc_html($facebook_text); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($show_youtube && !empty($youtube_text)): ?>
            <div style="display: flex; align-items: center; gap: 8px; color: white; font-size: 17px; font-weight: 500;">
                <svg width="20" height="20" viewBox="0 0 24 24"><rect width="24" height="24" rx="4" fill="red"/><path d="M10 8.5v7l5.5-3.5L10 8.5z" fill="white"/></svg>
                <span><?php echo esc_html($youtube_text); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($show_website && !empty($website_text)): ?>
            <div style="display: flex; align-items: center; gap: 8px; color: white; font-size: 17px; font-weight: 500;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="#4CAF50"><circle cx="12" cy="12" r="10" fill="#4CAF50"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" fill="white"/></svg>
                <span><?php echo esc_html($website_text); ?></span>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

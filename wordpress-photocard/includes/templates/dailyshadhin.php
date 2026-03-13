<?php
/**
 * Daily Shadhin Template
 * Layout: Full image top with background overlay template,
 * title over gradient, bottom bar blended with background
 */
if (!defined('ABSPATH')) exit;

$plugin_url = plugin_dir_url(dirname(dirname(__FILE__)));
$bg_image_url = !empty($custom_bg_image) ? $custom_bg_image : $plugin_url . 'assets/images/dailyshadhin-bg.png';
$_title_offset = isset($title_top_offset) ? intval($title_top_offset) : 0;
$_details_offset = isset($details_bottom_offset) ? intval($details_bottom_offset) : 0;
$_domain_text = isset($domain_text) ? $domain_text : '';
$_show_domain = isset($show_domain) ? $show_domain : true;
$_content_top = 560 + $_title_offset;
?>
<div class="pcd-photocard" data-language="<?php echo esc_attr($language); ?>" data-quality="<?php echo esc_attr($image_quality); ?>" style="width: 1080px; height: 1080px; padding: 0; position: relative; overflow: hidden; box-sizing: border-box; background: #1a0a0a;">
    
    <!-- Post Featured Image -->
    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; object-position: center top; z-index: 1;" crossorigin="anonymous">

    <!-- Background Template Image (full overlay) -->
    <img src="<?php echo esc_url($bg_image_url); ?>" alt="" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 2;" crossorigin="anonymous">

    <!-- Logo -->
    <?php if ($enable_logo && !empty($watermark_logo)): ?>
    <div style="position: absolute; top: 20px; <?php echo ($logo_position === 'left') ? 'left: 25px;' : (($logo_position === 'center') ? 'left: 50%; transform: translateX(-50%);' : 'right: 25px;'); ?> z-index: 10;">
        <img src="<?php echo esc_url($watermark_logo); ?>" alt="Logo" style="height: 90px; width: auto; display: block; filter: drop-shadow(2px 2px 6px rgba(0,0,0,0.5));" crossorigin="anonymous">
    </div>
    <?php endif; ?>

    <!-- Bottom Content Area -->
    <div style="position: absolute; top: <?php echo $_content_top; ?>px; bottom: 0; left: 0; right: 0; z-index: 5; display: flex; flex-direction: column; justify-content: flex-start;">

        <!-- Title -->
        <div style="padding: 0 50px; flex-shrink: 0;">
            <div id="pcd-adjustable-title" class="pcd-title" style="color: #ffffff; font-size: <?php echo esc_attr($default_font_size); ?>px; line-height: <?php echo esc_attr($default_line_height); ?>; font-weight: 900; text-align: <?php echo esc_attr($title_alignment); ?>; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; word-wrap: break-word; overflow-wrap: break-word; text-shadow: 3px 3px 8px rgba(0,0,0,0.7);">
                <?php echo esc_html($post_title); ?>
            </div>
        </div>

        <!-- Spacer -->
        <div style="flex: 1;"></div>

        <!-- Bottom Bar: Date (left) | Domain (center) | Details (right) - transparent/blended -->
        <div style="display: flex; justify-content: space-between; align-items: center; padding: <?php echo (18 + $_details_offset); ?>px 45px;">
            
            <!-- Date - Left -->
            <?php if ($enable_date): ?>
            <div style="color: rgba(255,255,255,0.95); font-size: 24px; font-weight: 700; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; text-shadow: 2px 2px 6px rgba(0,0,0,0.8);">
                <?php echo esc_html($formatted_date); ?>
            </div>
            <?php else: ?>
            <div></div>
            <?php endif; ?>

            <!-- Domain - Center -->
            <?php if ($_show_domain && !empty($_domain_text)): ?>
            <div style="color: #ffffff; font-size: 24px; font-weight: 800; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; letter-spacing: 0.5px; text-shadow: 2px 2px 6px rgba(0,0,0,0.8);">
                <?php echo esc_html($_domain_text); ?>
            </div>
            <?php else: ?>
            <div></div>
            <?php endif; ?>

            <!-- Details - Right -->
            <?php if ($show_details_button): ?>
            <div style="color: rgba(255,255,255,0.95); font-size: 24px; font-weight: 700; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; text-shadow: 2px 2px 6px rgba(0,0,0,0.8);">
                <?php echo esc_html($details_button_text); ?>
            </div>
            <?php else: ?>
            <div></div>
            <?php endif; ?>
        </div>
    </div>
</div>

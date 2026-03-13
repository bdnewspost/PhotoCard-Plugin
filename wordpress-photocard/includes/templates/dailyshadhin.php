<?php
/**
 * Daily Shadhin Template
 * Layout: Full image with background overlay template
 */
if (!defined('ABSPATH')) exit;

$plugin_url = plugin_dir_url(dirname(dirname(__FILE__)));
$bg_image_url = !empty($custom_bg_image) ? $custom_bg_image : $plugin_url . 'assets/images/dailyshadhin-bg.png';
$_title_offset = isset($title_top_offset) ? intval($title_top_offset) : 0;
$_details_offset = isset($details_bottom_offset) ? intval($details_bottom_offset) : 0;
$_content_top = 560 + $_title_offset;

// Background color
$_ds_bg = isset($options['dailyshadhin_bg_color']) ? $options['dailyshadhin_bg_color'] : '#1a0a0a';
if (!empty($card_bg_color)) $_ds_bg = $card_bg_color;
$_bg_style = 'background: ' . $_ds_bg . ';';
if (!empty($card_bg_gradient_enable) && !empty($card_bg_gradient_color1) && !empty($card_bg_gradient_color2)) {
    $_bg_style = 'background: linear-gradient(' . esc_attr($card_bg_gradient_direction) . ', ' . esc_attr($card_bg_gradient_color1) . ', ' . esc_attr($card_bg_gradient_color2) . ');';
}

// Featured image settings
$_fi_object_fit = isset($fi_object_fit) ? $fi_object_fit : 'cover';
$_fi_object_position = isset($fi_object_position) ? $fi_object_position : 'center center';
$_fi_zoom = isset($fi_zoom) ? intval($fi_zoom) : 100;
$_fi_zoom_style = ($_fi_zoom != 100) ? 'transform: scale(' . ($_fi_zoom / 100) . ');' : '';

// Featured image padding/margin
$_fi_padding_top = isset($fi_padding_top) ? intval($fi_padding_top) : 0;
$_fi_padding_right = isset($fi_padding_right) ? intval($fi_padding_right) : 0;
$_fi_padding_bottom = isset($fi_padding_bottom) ? intval($fi_padding_bottom) : 0;
$_fi_padding_left = isset($fi_padding_left) ? intval($fi_padding_left) : 0;

// Featured image border per side
$_fi_border_top = isset($fi_border_top) ? intval($fi_border_top) : 0;
$_fi_border_right = isset($fi_border_right) ? intval($fi_border_right) : 0;
$_fi_border_bottom = isset($fi_border_bottom) ? intval($fi_border_bottom) : 0;
$_fi_border_left = isset($fi_border_left) ? intval($fi_border_left) : 0;
$_fi_border_color = isset($fi_border_color) ? $fi_border_color : '#ffffff';

// Featured image border radius per corner
$_fi_radius_tl = isset($fi_radius_tl) ? intval($fi_radius_tl) : 0;
$_fi_radius_tr = isset($fi_radius_tr) ? intval($fi_radius_tr) : 0;
$_fi_radius_bl = isset($fi_radius_bl) ? intval($fi_radius_bl) : 0;
$_fi_radius_br = isset($fi_radius_br) ? intval($fi_radius_br) : 0;

// Build featured image extra styles
$_fi_border_style = '';
if ($_fi_border_top > 0 || $_fi_border_right > 0 || $_fi_border_bottom > 0 || $_fi_border_left > 0) {
    $_fi_border_style = 'border-width: ' . $_fi_border_top . 'px ' . $_fi_border_right . 'px ' . $_fi_border_bottom . 'px ' . $_fi_border_left . 'px; border-style: solid; border-color: ' . $_fi_border_color . ';';
}
if ($_fi_radius_tl > 0 || $_fi_radius_tr > 0 || $_fi_radius_bl > 0 || $_fi_radius_br > 0) {
    $_fi_border_style .= 'border-radius: ' . $_fi_radius_tl . 'px ' . $_fi_radius_tr . 'px ' . $_fi_radius_br . 'px ' . $_fi_radius_bl . 'px;';
}

$_fi_has_spacing = ($_fi_padding_top > 0 || $_fi_padding_right > 0 || $_fi_padding_bottom > 0 || $_fi_padding_left > 0 || $_fi_border_top > 0 || $_fi_border_right > 0 || $_fi_border_bottom > 0 || $_fi_border_left > 0 || $_fi_radius_tl > 0 || $_fi_radius_tr > 0 || $_fi_radius_bl > 0 || $_fi_radius_br > 0);

// Card border settings
$_border_width = isset($card_border_width) ? intval($card_border_width) : 0;
$_border_color = isset($card_border_color) ? $card_border_color : '#ffffff';
$_border_radius = isset($card_border_radius) ? intval($card_border_radius) : 0;
$_border_style = '';
if ($_border_width > 0) {
    $_border_style = 'border: ' . $_border_width . 'px solid ' . $_border_color . ';';
}
if ($_border_radius > 0) {
    $_border_style .= 'border-radius: ' . $_border_radius . 'px;';
}

// Title color
$_title_color = isset($title_text_color) ? $title_text_color : '#ffffff';

// Social icon font size
$_social_font_size = isset($social_icon_font_size) ? intval($social_icon_font_size) : 14;
$_social_icon_size = max(12, $_social_font_size + 2);
?>
<div class="pcd-photocard" data-language="<?php echo esc_attr($language); ?>" data-quality="<?php echo esc_attr($image_quality); ?>" style="width: 1080px; height: 1080px; padding: 0; position: relative; overflow: hidden; box-sizing: border-box; background: #1a0a0a; <?php echo $_border_style; ?>">
    
    <!-- Post Featured Image -->
    <?php if ($_fi_has_spacing): ?>
    <div style="position: absolute; top: <?php echo $_fi_padding_top; ?>px; left: <?php echo $_fi_padding_left; ?>px; right: <?php echo $_fi_padding_right; ?>px; bottom: <?php echo $_fi_padding_bottom; ?>px; z-index: 1; overflow: hidden; <?php echo $_fi_border_style; ?>">
        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="width: 100%; height: 100%; object-fit: <?php echo esc_attr($_fi_object_fit); ?>; object-position: <?php echo esc_attr($_fi_object_position); ?>; display: block; <?php echo $_fi_zoom_style; ?>" crossorigin="anonymous">
    </div>
    <?php else: ?>
    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: <?php echo esc_attr($_fi_object_fit); ?>; object-position: <?php echo esc_attr($_fi_object_position); ?>; z-index: 1; <?php echo $_fi_zoom_style; ?>" crossorigin="anonymous">
    <?php endif; ?>

    <!-- Background Template Image (full overlay) -->
    <img src="<?php echo esc_url($bg_image_url); ?>" alt="" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 2; pointer-events: none;" crossorigin="anonymous">

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
            <div id="pcd-adjustable-title" class="pcd-title" style="color: <?php echo esc_attr($_title_color); ?>; font-size: <?php echo esc_attr($default_font_size); ?>px; line-height: <?php echo esc_attr($default_line_height); ?>; font-weight: 900; text-align: <?php echo esc_attr($title_alignment); ?>; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; word-wrap: break-word; overflow-wrap: break-word; text-shadow: 3px 3px 8px rgba(0,0,0,0.7);">
                <?php echo esc_html($post_title); ?>
            </div>
        </div>

        <!-- Spacer -->
        <div style="flex: 1;"></div>

        <!-- Bottom Bar -->
        <div style="display: flex; justify-content: space-between; align-items: center; padding: <?php echo (18 + $_details_offset); ?>px 45px;">
            
            <!-- Date - Left -->
            <?php if ($enable_date): ?>
            <div style="color: rgba(255,255,255,0.95); font-size: 24px; font-weight: 700; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; text-shadow: 2px 2px 6px rgba(0,0,0,0.8);">
                <?php echo esc_html($formatted_date); ?>
            </div>
            <?php else: ?>
            <div></div>
            <?php endif; ?>

            <!-- Center: Social Icons -->
            <?php if ($show_facebook || $show_youtube || $show_website || $show_instagram || $show_linkedin): ?>
            <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap; justify-content: center;">
                <?php if ($show_facebook && !empty($facebook_text)): ?>
                <div style="display: flex; align-items: center; gap: 5px; color: white; font-size: <?php echo $_social_font_size; ?>px; font-weight: 500;">
                    <svg width="<?php echo $_social_icon_size; ?>" height="<?php echo $_social_icon_size; ?>" viewBox="0 0 24 24"><circle cx="12" cy="12" r="12" fill="#1877f2"/><path d="M16.5 12.5h-2.5v7h-3v-7h-2v-2.5h2v-1.5c0-2.2 1-3.5 3.5-3.5h2v2.5h-1.5c-.8 0-1 .3-1 1v1.5h2.5l-.5 2.5z" fill="white"/></svg>
                    <span><?php echo esc_html($facebook_text); ?></span>
                </div>
                <?php endif; ?>
                <?php if ($show_instagram && !empty($instagram_text)): ?>
                <div style="display: flex; align-items: center; gap: 5px; color: white; font-size: <?php echo $_social_font_size; ?>px; font-weight: 500;">
                    <svg width="<?php echo $_social_icon_size; ?>" height="<?php echo $_social_icon_size; ?>" viewBox="0 0 24 24"><rect width="24" height="24" rx="6" fill="url(#ig-grad-ds2)"/><circle cx="12" cy="12" r="5" stroke="white" stroke-width="2" fill="none"/><circle cx="17.5" cy="6.5" r="1.5" fill="white"/><defs><linearGradient id="ig-grad-ds2" x1="0" y1="24" x2="24" y2="0"><stop offset="0%" stop-color="#feda75"/><stop offset="25%" stop-color="#fa7e1e"/><stop offset="50%" stop-color="#d62976"/><stop offset="75%" stop-color="#962fbf"/><stop offset="100%" stop-color="#4f5bd5"/></linearGradient></defs></svg>
                    <span><?php echo esc_html($instagram_text); ?></span>
                </div>
                <?php endif; ?>
                <?php if ($show_youtube && !empty($youtube_text)): ?>
                <div style="display: flex; align-items: center; gap: 5px; color: white; font-size: <?php echo $_social_font_size; ?>px; font-weight: 500;">
                    <svg width="<?php echo $_social_icon_size; ?>" height="<?php echo $_social_icon_size; ?>" viewBox="0 0 24 24"><rect width="24" height="24" rx="6" fill="#FF0000"/><path d="M10 8.5v7l5.5-3.5L10 8.5z" fill="white"/></svg>
                    <span><?php echo esc_html($youtube_text); ?></span>
                </div>
                <?php endif; ?>
                <?php if ($show_linkedin && !empty($linkedin_text)): ?>
                <div style="display: flex; align-items: center; gap: 5px; color: white; font-size: <?php echo $_social_font_size; ?>px; font-weight: 500;">
                    <svg width="<?php echo $_social_icon_size; ?>" height="<?php echo $_social_icon_size; ?>" viewBox="0 0 24 24"><rect width="24" height="24" rx="4" fill="#0077B5"/><path d="M8 10v7H5.5v-7H8zm-1.25-1.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM19 17h-2.5v-3.5c0-1-.4-1.7-1.3-1.7-.7 0-1.1.5-1.3 1-.1.1-.1.3-.1.5V17H11.5s0-6.5 0-7h2.3l.2 1c.5-.7 1.2-1.2 2.3-1.2 1.7 0 2.7 1.1 2.7 3.5V17z" fill="white"/></svg>
                    <span><?php echo esc_html($linkedin_text); ?></span>
                </div>
                <?php endif; ?>
                <?php if ($show_website && !empty($website_text)): ?>
                <div style="display: flex; align-items: center; gap: 5px; color: white; font-size: <?php echo $_social_font_size; ?>px; font-weight: 500;">
                    <svg width="<?php echo $_social_icon_size; ?>" height="<?php echo $_social_icon_size; ?>" viewBox="0 0 24 24"><circle cx="12" cy="12" r="11" fill="#4CAF50"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" fill="white"/></svg>
                    <span><?php echo esc_html($website_text); ?></span>
                </div>
                <?php endif; ?>
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

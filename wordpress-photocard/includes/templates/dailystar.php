<?php
/**
 * Daily Star Template - Navy blue + accent, professional English daily
 * Now with full featured image padding/border/radius support
 */
if (!defined('ABSPATH')) exit;

$ds_navy = isset($options['dailystar_navy_color']) ? $options['dailystar_navy_color'] : '#003366';
$ds_red = isset($options['dailystar_accent_color']) ? $options['dailystar_accent_color'] : '#cc0000';
$_title_offset = isset($title_top_offset) ? intval($title_top_offset) : 0;
$_details_offset = isset($details_bottom_offset) ? intval($details_bottom_offset) : 0;

// Featured image settings
$_fi_object_fit = isset($fi_object_fit) ? $fi_object_fit : 'cover';
$_fi_object_position = isset($fi_object_position) ? $fi_object_position : 'center center';
$_fi_zoom = isset($fi_zoom) ? intval($fi_zoom) : 100;
$_fi_zoom_style = ($_fi_zoom != 100) ? 'transform: scale(' . ($_fi_zoom / 100) . ');' : '';

// Featured image padding
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

// Background color
$_card_bg = $ds_navy;
if (!empty($card_bg_color)) $_card_bg = $card_bg_color;
$_bg_style = 'background: ' . $_card_bg . ';';
if (!empty($card_bg_gradient_enable) && !empty($card_bg_gradient_color1) && !empty($card_bg_gradient_color2)) {
    $_bg_style = 'background: linear-gradient(' . esc_attr($card_bg_gradient_direction) . ', ' . esc_attr($card_bg_gradient_color1) . ', ' . esc_attr($card_bg_gradient_color2) . ');';
}

// Social icon font size
$_social_font_size = isset($social_icon_font_size) ? intval($social_icon_font_size) : 15;
$_social_icon_size = max(12, $_social_font_size + 1);
?>
<div class="pcd-photocard" data-language="<?php echo esc_attr($language); ?>" data-quality="<?php echo esc_attr($image_quality); ?>" style="width: 1080px; height: 1080px; <?php echo $_bg_style; ?> padding: 0; position: relative; display: flex; flex-direction: column; box-sizing: border-box; overflow: hidden; <?php echo $_border_style; ?>">
    
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

    <!-- Accent line -->
    <div style="height: 5px; background: <?php echo esc_attr($ds_red); ?>; flex-shrink: 0;"></div>

    <!-- Featured Image -->
    <?php if ($_fi_has_spacing): ?>
    <div style="flex: 1; min-height: 0; overflow: hidden; padding: <?php echo $_fi_padding_top; ?>px <?php echo $_fi_padding_right; ?>px <?php echo $_fi_padding_bottom; ?>px <?php echo $_fi_padding_left; ?>px;">
        <div style="width: 100%; height: 100%; overflow: hidden; <?php echo $_fi_border_style; ?>">
            <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="width: 100%; height: 100%; object-fit: <?php echo esc_attr($_fi_object_fit); ?>; object-position: <?php echo esc_attr($_fi_object_position); ?>; display: block; <?php echo $_fi_zoom_style; ?>" crossorigin="anonymous">
        </div>
    </div>
    <?php else: ?>
    <div style="flex: 1; min-height: 0; overflow: hidden;">
        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="width: 100%; height: 100%; object-fit: <?php echo esc_attr($_fi_object_fit); ?>; object-position: <?php echo esc_attr($_fi_object_position); ?>; display: block; <?php echo $_fi_zoom_style; ?>" crossorigin="anonymous">
    </div>
    <?php endif; ?>

    <!-- Accent line -->
    <div style="height: 5px; background: <?php echo esc_attr($ds_red); ?>; flex-shrink: 0;"></div>

    <!-- Navy Title Footer -->
    <div style="background: <?php echo esc_attr($ds_navy); ?>; padding: <?php echo (22 - $_title_offset); ?>px 35px <?php echo (10 + $_details_offset); ?>px; flex-shrink: 0;">
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
    <?php if ($show_facebook || $show_youtube || $show_website || $show_instagram || $show_linkedin): ?>
    <div style="background: <?php echo esc_attr($ds_red); ?>; padding: 10px 25px; flex-shrink: 0; display: flex; justify-content: center; align-items: center; gap: 25px; flex-wrap: wrap;">
        <?php if ($show_facebook && !empty($facebook_text)): ?>
        <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: <?php echo $_social_font_size; ?>px; font-weight: 600;">
            <svg width="<?php echo $_social_icon_size; ?>" height="<?php echo $_social_icon_size; ?>" viewBox="0 0 24 24" fill="white"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            <span><?php echo esc_html($facebook_text); ?></span>
        </div>
        <?php endif; ?>
        <?php if ($show_instagram && !empty($instagram_text)): ?>
        <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: <?php echo $_social_font_size; ?>px; font-weight: 600;">
            <svg width="<?php echo $_social_icon_size; ?>" height="<?php echo $_social_icon_size; ?>" viewBox="0 0 24 24"><rect width="24" height="24" rx="6" fill="url(#ig-grad-ds)"/><circle cx="12" cy="12" r="5" stroke="white" stroke-width="2" fill="none"/><circle cx="17.5" cy="6.5" r="1.5" fill="white"/><defs><linearGradient id="ig-grad-ds" x1="0" y1="24" x2="24" y2="0"><stop offset="0%" stop-color="#feda75"/><stop offset="25%" stop-color="#fa7e1e"/><stop offset="50%" stop-color="#d62976"/><stop offset="75%" stop-color="#962fbf"/><stop offset="100%" stop-color="#4f5bd5"/></linearGradient></defs></svg>
            <span><?php echo esc_html($instagram_text); ?></span>
        </div>
        <?php endif; ?>
        <?php if ($show_youtube && !empty($youtube_text)): ?>
        <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: <?php echo $_social_font_size; ?>px; font-weight: 600;">
            <svg width="<?php echo $_social_icon_size; ?>" height="<?php echo $_social_icon_size; ?>" viewBox="0 0 24 24" fill="white"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
            <span><?php echo esc_html($youtube_text); ?></span>
        </div>
        <?php endif; ?>
        <?php if ($show_linkedin && !empty($linkedin_text)): ?>
        <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: <?php echo $_social_font_size; ?>px; font-weight: 600;">
            <svg width="<?php echo $_social_icon_size; ?>" height="<?php echo $_social_icon_size; ?>" viewBox="0 0 24 24"><rect width="24" height="24" rx="4" fill="#0077B5"/><path d="M8 10v7H5.5v-7H8zm-1.25-1.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM19 17h-2.5v-3.5c0-1-.4-1.7-1.3-1.7-.7 0-1.1.5-1.3 1-.1.1-.1.3-.1.5V17H11.5s0-6.5 0-7h2.3l.2 1c.5-.7 1.2-1.2 2.3-1.2 1.7 0 2.7 1.1 2.7 3.5V17z" fill="white"/></svg>
            <span><?php echo esc_html($linkedin_text); ?></span>
        </div>
        <?php endif; ?>
        <?php if ($show_website && !empty($website_text)): ?>
        <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: <?php echo $_social_font_size; ?>px; font-weight: 600;">
            <svg width="<?php echo $_social_icon_size; ?>" height="<?php echo $_social_icon_size; ?>" viewBox="0 0 24 24" fill="white"><circle cx="12" cy="12" r="10"/></svg>
            <span><?php echo esc_html($website_text); ?></span>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

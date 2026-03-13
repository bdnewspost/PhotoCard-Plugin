<?php
/**
 * Prothom Alo Template - Red gradient overlay, full-bleed image
 * Now with full featured image padding/border/radius + bg color support
 */
if (!defined('ABSPATH')) exit;

$pa_red = isset($options['prothomalo_primary_color']) ? $options['prothomalo_primary_color'] : '#e42313';
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
$_card_bg = '#000';
if (!empty($card_bg_color)) $_card_bg = $card_bg_color;
$_bg_style = 'background: ' . $_card_bg . ';';
if (!empty($card_bg_gradient_enable) && !empty($card_bg_gradient_color1) && !empty($card_bg_gradient_color2)) {
    $_bg_style = 'background: linear-gradient(' . esc_attr($card_bg_gradient_direction) . ', ' . esc_attr($card_bg_gradient_color1) . ', ' . esc_attr($card_bg_gradient_color2) . ');';
}

// Social icon font size
$_social_font_size = isset($social_icon_font_size) ? intval($social_icon_font_size) : 16;
$_social_icon_size = max(14, $_social_font_size + 2);

// Title color
$_title_color = isset($title_text_color) ? $title_text_color : '#ffffff';

// Date position
$_date_position = isset($date_position) ? $date_position : 'right';
?>
<div class="pcd-photocard" data-language="<?php echo esc_attr($language); ?>" data-quality="<?php echo esc_attr($image_quality); ?>" style="width: 1080px; height: 1080px; <?php echo $_bg_style; ?> padding: 0; position: relative; overflow: hidden; box-sizing: border-box; <?php echo $_border_style; ?>">
    
    <!-- Full Bleed Background Image -->
    <?php if ($_fi_has_spacing): ?>
    <div style="position: absolute; top: <?php echo $_fi_padding_top; ?>px; left: <?php echo $_fi_padding_left; ?>px; right: <?php echo $_fi_padding_right; ?>px; bottom: <?php echo $_fi_padding_bottom; ?>px; z-index: 1; overflow: hidden; <?php echo $_fi_border_style; ?>">
        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="width: 100%; height: 100%; object-fit: <?php echo esc_attr($_fi_object_fit); ?>; object-position: <?php echo esc_attr($_fi_object_position); ?>; display: block; <?php echo $_fi_zoom_style; ?>" crossorigin="anonymous">
    </div>
    <?php else: ?>
    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: <?php echo esc_attr($_fi_object_fit); ?>; object-position: <?php echo esc_attr($_fi_object_position); ?>; z-index: 1; <?php echo $_fi_zoom_style; ?>" crossorigin="anonymous">
    <?php endif; ?>

    <!-- Top dark gradient -->
    <div style="position: absolute; top: 0; left: 0; right: 0; z-index: 10; background: linear-gradient(to bottom, rgba(0,0,0,0.7) 0%, transparent 100%); padding: 20px 30px 50px; display: flex; justify-content: space-between; align-items: flex-start;">
        <?php if ($logo_position === 'right'): ?>
            <?php if ($enable_date): ?>
            <div style="color: #ffffff; font-size: 22px; font-weight: 600; font-family: '<?php echo esc_attr($title_font_family); ?>', sans-serif; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
                <?php echo esc_html($formatted_date); ?>
            </div>
            <?php endif; ?>
            <?php if ($enable_logo && !empty($watermark_logo)): ?>
            <img src="<?php echo esc_url($watermark_logo); ?>" alt="Logo" style="height: 70px; width: auto;" crossorigin="anonymous">
            <?php endif; ?>
        <?php else: ?>
            <?php if ($enable_logo && !empty($watermark_logo)): ?>
            <img src="<?php echo esc_url($watermark_logo); ?>" alt="Logo" style="height: 70px; width: auto;" crossorigin="anonymous">
            <?php endif; ?>
            <?php if ($enable_date): ?>
            <div style="color: #ffffff; font-size: 22px; font-weight: 600; font-family: '<?php echo esc_attr($title_font_family); ?>', sans-serif; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
                <?php echo esc_html($formatted_date); ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Red gradient overlay at bottom -->
    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 55%; background: linear-gradient(to top, <?php echo esc_attr($pa_red); ?> 0%, <?php echo esc_attr($pa_red); ?>ee 25%, <?php echo esc_attr($pa_red); ?>88 50%, transparent 100%); z-index: 2;"></div>

    <!-- Bottom content -->
    <div style="position: absolute; bottom: 0; left: 0; right: 0; z-index: 5; display: flex; flex-direction: column; padding-bottom: <?php echo ($show_facebook || $show_youtube || $show_website || $show_instagram || $show_linkedin) ? '0' : (20 + $_details_offset) . 'px'; ?>;">
        
        <!-- Title -->
        <div id="pcd-adjustable-title" class="pcd-title" style="color: #ffffff; font-size: <?php echo esc_attr($default_font_size); ?>px; line-height: <?php echo esc_attr($default_line_height); ?>; font-weight: 900; text-align: <?php echo esc_attr($title_alignment); ?>; padding: 0 35px; margin-top: <?php echo -$_title_offset; ?>px; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; word-wrap: break-word; text-shadow: 2px 2px 6px rgba(0,0,0,0.5);">
            <?php echo esc_html($post_title); ?>
        </div>

        <?php if ($show_details_button): ?>
        <div style="text-align: center; padding: 10px 35px <?php echo (5 + $_details_offset); ?>px;">
            <span style="color: rgba(255,255,255,0.9); font-size: 22px; font-weight: 600; font-family: '<?php echo esc_attr($title_font_family); ?>', sans-serif;">
                ❯❯ <?php echo esc_html($details_button_text); ?> ❮❮
            </span>
        </div>
        <?php endif; ?>

        <!-- Social bar -->
        <?php if ($show_facebook || $show_youtube || $show_website || $show_instagram || $show_linkedin): ?>
        <div style="background: rgba(0,0,0,0.4); padding: 12px 25px; margin-top: 8px; display: flex; justify-content: center; align-items: center; gap: 25px; flex-wrap: wrap;">
            <?php if ($show_facebook && !empty($facebook_text)): ?>
            <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: <?php echo $_social_font_size; ?>px; font-weight: 500;">
                <svg width="<?php echo $_social_icon_size; ?>" height="<?php echo $_social_icon_size; ?>" viewBox="0 0 24 24" fill="white"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                <span><?php echo esc_html($facebook_text); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($show_instagram && !empty($instagram_text)): ?>
            <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: <?php echo $_social_font_size; ?>px; font-weight: 500;">
                <svg width="<?php echo $_social_icon_size; ?>" height="<?php echo $_social_icon_size; ?>" viewBox="0 0 24 24"><rect width="24" height="24" rx="6" fill="url(#ig-grad-pa)"/><circle cx="12" cy="12" r="5" stroke="white" stroke-width="2" fill="none"/><circle cx="17.5" cy="6.5" r="1.5" fill="white"/><defs><linearGradient id="ig-grad-pa" x1="0" y1="24" x2="24" y2="0"><stop offset="0%" stop-color="#feda75"/><stop offset="25%" stop-color="#fa7e1e"/><stop offset="50%" stop-color="#d62976"/><stop offset="75%" stop-color="#962fbf"/><stop offset="100%" stop-color="#4f5bd5"/></linearGradient></defs></svg>
                <span><?php echo esc_html($instagram_text); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($show_youtube && !empty($youtube_text)): ?>
            <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: <?php echo $_social_font_size; ?>px; font-weight: 500;">
                <svg width="<?php echo $_social_icon_size; ?>" height="<?php echo $_social_icon_size; ?>" viewBox="0 0 24 24"><rect width="24" height="24" rx="4" fill="red"/><path d="M10 8.5v7l5.5-3.5L10 8.5z" fill="white"/></svg>
                <span><?php echo esc_html($youtube_text); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($show_linkedin && !empty($linkedin_text)): ?>
            <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: <?php echo $_social_font_size; ?>px; font-weight: 500;">
                <svg width="<?php echo $_social_icon_size; ?>" height="<?php echo $_social_icon_size; ?>" viewBox="0 0 24 24"><rect width="24" height="24" rx="4" fill="#0077B5"/><path d="M8 10v7H5.5v-7H8zm-1.25-1.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM19 17h-2.5v-3.5c0-1-.4-1.7-1.3-1.7-.7 0-1.1.5-1.3 1-.1.1-.1.3-.1.5V17H11.5s0-6.5 0-7h2.3l.2 1c.5-.7 1.2-1.2 2.3-1.2 1.7 0 2.7 1.1 2.7 3.5V17z" fill="white"/></svg>
                <span><?php echo esc_html($linkedin_text); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($show_website && !empty($website_text)): ?>
            <div style="display: flex; align-items: center; gap: 6px; color: white; font-size: <?php echo $_social_font_size; ?>px; font-weight: 500;">
                <svg width="<?php echo $_social_icon_size; ?>" height="<?php echo $_social_icon_size; ?>" viewBox="0 0 24 24" fill="white"><circle cx="12" cy="12" r="10"/></svg>
                <span><?php echo esc_html($website_text); ?></span>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

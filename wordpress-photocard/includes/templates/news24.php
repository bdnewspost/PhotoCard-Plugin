<?php
/**
 * News24 Template - EXACT match to reference
 * 
 * Reference analysis:
 * - Full-bleed background image (1080x1080)
 * - Logo at TOP-RIGHT corner
 * - VERY heavy dark gradient overlay from bottom (~65%) - nearly black at bottom
 * - Subtle world map/globe SVG pattern visible in dark gradient area
 * - Date badge: dark blue bg (#1a3a5c), RIGHT-aligned, positioned just above title
 * - Title: Very large GOLDEN/YELLOW (#FFD700) bold text with heavy shadows
 * - ">> বিস্তারিত কমেন্টে <<" in white below title
 * - Social links bar at very bottom: dark bg with colored brand icons
 */
if (!defined('ABSPATH')) exit;

$news24_title_color = isset($options['news24_title_color']) ? $options['news24_title_color'] : '#FFD700';
$news24_date_bg = isset($options['news24_date_bg']) ? $options['news24_date_bg'] : '#1a3a5c';
?>
<div class="pcd-photocard" data-language="<?php echo esc_attr($language); ?>" data-quality="<?php echo esc_attr($image_quality); ?>" style="width: 1080px; height: 1080px; background: #000; padding: 0; position: relative; overflow: hidden; box-sizing: border-box;">
    
    <!-- Full Bleed Background Image -->
    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;" crossorigin="anonymous">

    <!-- Dark Gradient Overlay - VERY heavy from bottom, nearly black -->
    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 100%; background: linear-gradient(to top, rgba(0,0,0,0.98) 0%, rgba(0,0,0,0.95) 20%, rgba(0,0,0,0.85) 35%, rgba(0,0,0,0.6) 50%, rgba(0,0,0,0.2) 65%, transparent 80%); z-index: 2;"></div>

    <!-- World Map / Globe Pattern Overlay (subtle texture in dark area) -->
    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 60%; z-index: 3; opacity: 0.12; overflow: hidden; pointer-events: none;">
        <svg viewBox="0 0 1080 650" width="1080" height="650" style="display: block;">
            <!-- Simplified world map continents -->
            <!-- North America -->
            <path d="M150,100 Q180,50 240,65 Q290,40 330,60 Q370,50 400,80 Q420,100 410,140 Q395,180 370,210 Q330,240 290,225 Q250,215 225,190 Q195,175 180,150 Z" fill="white"/>
            <!-- South America -->
            <path d="M270,260 Q300,245 315,275 Q330,310 325,360 Q318,410 295,450 Q270,465 255,440 Q248,400 255,350 Q262,300 270,260 Z" fill="white"/>
            <!-- Europe -->
            <path d="M490,65 Q520,40 555,55 Q580,48 595,70 Q610,85 595,110 Q570,125 545,118 Q515,110 500,95 Z" fill="white"/>
            <!-- Africa -->
            <path d="M510,140 Q550,125 575,150 Q600,190 608,240 Q605,300 585,345 Q565,380 540,395 Q515,380 508,345 Q500,290 505,240 Q508,185 510,140 Z" fill="white"/>
            <!-- Asia -->
            <path d="M600,35 Q660,20 720,28 Q785,35 840,60 Q885,85 910,125 Q920,160 895,185 Q860,200 815,192 Q765,180 715,160 Q665,138 630,115 Q605,90 600,35 Z" fill="white"/>
            <!-- Australia -->
            <path d="M830,300 Q875,285 910,300 Q935,318 928,350 Q910,375 875,378 Q840,372 825,348 Q818,325 830,300 Z" fill="white"/>
            <!-- Grid lines -->
            <line x1="0" y1="130" x2="1080" y2="130" stroke="white" stroke-width="0.8" opacity="0.4"/>
            <line x1="0" y1="260" x2="1080" y2="260" stroke="white" stroke-width="0.8" opacity="0.4"/>
            <line x1="0" y1="390" x2="1080" y2="390" stroke="white" stroke-width="0.8" opacity="0.4"/>
            <line x1="0" y1="520" x2="1080" y2="520" stroke="white" stroke-width="0.8" opacity="0.4"/>
            <line x1="216" y1="0" x2="216" y2="650" stroke="white" stroke-width="0.8" opacity="0.4"/>
            <line x1="432" y1="0" x2="432" y2="650" stroke="white" stroke-width="0.8" opacity="0.4"/>
            <line x1="648" y1="0" x2="648" y2="650" stroke="white" stroke-width="0.8" opacity="0.4"/>
            <line x1="864" y1="0" x2="864" y2="650" stroke="white" stroke-width="0.8" opacity="0.4"/>
            <!-- Curved latitude lines -->
            <ellipse cx="540" cy="325" rx="520" ry="220" fill="none" stroke="white" stroke-width="0.6" opacity="0.3"/>
            <ellipse cx="540" cy="325" rx="520" ry="120" fill="none" stroke="white" stroke-width="0.6" opacity="0.3"/>
            <ellipse cx="540" cy="325" rx="520" ry="50" fill="none" stroke="white" stroke-width="0.6" opacity="0.2"/>
        </svg>
    </div>

    <!-- Logo (top-right matching reference) -->
    <?php if ($enable_logo && !empty($watermark_logo)): ?>
    <div style="position: absolute; top: 20px; <?php echo ($logo_position === 'left') ? 'left: 25px;' : (($logo_position === 'center') ? 'left: 50%; transform: translateX(-50%);' : 'right: 25px;'); ?> z-index: 10;">
        <img src="<?php echo esc_url($watermark_logo); ?>" alt="Logo" style="height: 80px; width: auto; display: block;" crossorigin="anonymous">
    </div>
    <?php endif; ?>

    <!-- Bottom Content Area - all on gradient -->
    <div style="position: absolute; bottom: 0; left: 0; right: 0; z-index: 5; display: flex; flex-direction: column;">
        
        <!-- Date Badge (right-aligned, dark blue bg, just above title) -->
        <?php if ($enable_date): ?>
        <div style="text-align: <?php echo esc_attr($date_position); ?>; padding: 0 40px; margin-bottom: 15px;">
            <span style="display: inline-block; color: #ffffff; font-size: 28px; font-weight: 700; background: <?php echo esc_attr($news24_date_bg); ?>; padding: 10px 28px; border-radius: 4px; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; letter-spacing: 1px; box-shadow: 0 3px 10px rgba(0,0,0,0.5);">
                <?php echo esc_html($formatted_date); ?>
            </span>
        </div>
        <?php endif; ?>

        <!-- Title - Golden bold on heavy dark gradient -->
        <div id="pcd-adjustable-title" class="pcd-title" style="color: <?php echo esc_attr($news24_title_color); ?>; font-size: <?php echo esc_attr($default_font_size); ?>px; line-height: <?php echo esc_attr($default_line_height); ?>; font-weight: 900; text-align: <?php echo esc_attr($title_alignment); ?>; padding: 0 40px; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; word-wrap: break-word; overflow-wrap: break-word; text-shadow: 3px 3px 10px rgba(0,0,0,0.95), 0 0 30px rgba(0,0,0,0.7);">
            <?php echo esc_html($post_title); ?>
        </div>

        <!-- Details Button -->
        <?php if ($show_details_button): ?>
        <div style="text-align: center; padding: 18px 35px 8px;">
            <span style="color: #ffffff; font-size: 28px; font-weight: 600; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; letter-spacing: 2px;">
                ❯❯ <?php echo esc_html($details_button_text); ?> ❮❮
            </span>
        </div>
        <?php endif; ?>

        <!-- Social Links Bar - dark bg at very bottom with colored brand icons -->
        <?php if ($show_facebook || $show_youtube || $show_website || $show_instagram || $show_linkedin): ?>
        <div style="background: rgba(0,0,0,0.88); padding: 18px 30px; margin-top: 10px; display: flex; justify-content: center; align-items: center; gap: 40px; flex-wrap: wrap;">
            <?php if ($show_facebook && !empty($facebook_text)): ?>
            <div style="display: flex; align-items: center; gap: 10px; color: white; font-size: 18px; font-weight: 500;">
                <svg width="24" height="24" viewBox="0 0 24 24"><circle cx="12" cy="12" r="12" fill="#1877f2"/><path d="M16.5 12.5h-2.5v7h-3v-7h-2v-2.5h2v-1.5c0-2.2 1-3.5 3.5-3.5h2v2.5h-1.5c-.8 0-1 .3-1 1v1.5h2.5l-.5 2.5z" fill="white"/></svg>
                <span><?php echo esc_html($facebook_text); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($show_youtube && !empty($youtube_text)): ?>
            <div style="display: flex; align-items: center; gap: 10px; color: white; font-size: 18px; font-weight: 500;">
                <svg width="24" height="24" viewBox="0 0 24 24"><rect width="24" height="24" rx="6" fill="#FF0000"/><path d="M10 8.5v7l5.5-3.5L10 8.5z" fill="white"/></svg>
                <span><?php echo esc_html($youtube_text); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($show_website && !empty($website_text)): ?>
            <div style="display: flex; align-items: center; gap: 10px; color: white; font-size: 18px; font-weight: 500;">
                <svg width="24" height="24" viewBox="0 0 24 24"><circle cx="12" cy="12" r="11" fill="#4CAF50"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" fill="white"/></svg>
                <span><?php echo esc_html($website_text); ?></span>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

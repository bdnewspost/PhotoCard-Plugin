<?php
/**
 * News24 Template - EXACT match to reference
 * 
 * Reference analysis:
 * - Full-bleed background image (1080x1080)
 * - Logo at TOP-RIGHT corner
 * - Heavy dark gradient overlay from bottom (~65%) with subtle world map pattern
 * - Date badge: dark semi-transparent bg, white text, positioned right ABOVE title
 * - Title: Large GOLDEN/YELLOW bold text with heavy text-shadow
 * - ">> বিস্তারিত কমেন্টে <<" below title in white
 * - Social links bar at very bottom with dark bg + colored icons
 */
if (!defined('ABSPATH')) exit;

$news24_title_color = isset($options['news24_title_color']) ? $options['news24_title_color'] : '#FFD700';
$news24_date_bg = isset($options['news24_date_bg']) ? $options['news24_date_bg'] : '#1a3a5c';
?>
<div class="pcd-photocard" data-language="<?php echo esc_attr($language); ?>" data-quality="<?php echo esc_attr($image_quality); ?>" style="width: 1080px; height: 1080px; background: #000; padding: 0; position: relative; overflow: hidden; box-sizing: border-box;">
    
    <!-- Full Bleed Background Image -->
    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;" crossorigin="anonymous">

    <!-- Dark Gradient Overlay - heavy from bottom -->
    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 75%; background: linear-gradient(to top, rgba(0,0,0,0.97) 0%, rgba(0,0,0,0.92) 25%, rgba(0,0,0,0.7) 50%, rgba(0,0,0,0.3) 70%, transparent 100%); z-index: 2;"></div>

    <!-- World Map / Globe Pattern Overlay (subtle, always visible in dark area) -->
    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 55%; z-index: 3; opacity: 0.08; overflow: hidden; pointer-events: none;">
        <svg viewBox="0 0 1080 600" width="1080" height="600" style="display: block;">
            <!-- Simplified world map continents -->
            <!-- North America -->
            <path d="M180,120 Q200,80 250,90 Q280,70 310,85 Q340,75 360,95 Q380,110 370,140 Q360,170 340,190 Q310,210 280,200 Q250,190 230,170 Q200,160 190,140 Z" fill="white"/>
            <!-- South America -->
            <path d="M280,240 Q300,230 310,250 Q320,280 315,320 Q310,360 290,390 Q270,400 260,380 Q255,350 260,310 Q265,280 270,260 Z" fill="white"/>
            <!-- Europe -->
            <path d="M480,90 Q500,70 530,80 Q550,75 560,90 Q570,100 560,120 Q540,130 520,125 Q500,120 490,110 Z" fill="white"/>
            <!-- Africa -->
            <path d="M500,150 Q530,140 550,160 Q570,190 575,230 Q570,280 555,310 Q540,340 520,350 Q500,340 495,310 Q490,270 495,230 Q498,190 500,150 Z" fill="white"/>
            <!-- Asia -->
            <path d="M580,60 Q630,50 680,55 Q730,60 780,80 Q820,100 840,130 Q850,160 830,180 Q800,190 760,185 Q720,175 680,160 Q640,140 610,120 Q585,100 580,60 Z" fill="white"/>
            <!-- Australia -->
            <path d="M790,280 Q830,270 860,280 Q880,295 875,320 Q860,340 830,340 Q800,335 790,315 Q785,300 790,280 Z" fill="white"/>
            <!-- Grid lines -->
            <line x1="0" y1="150" x2="1080" y2="150" stroke="white" stroke-width="0.5" opacity="0.5"/>
            <line x1="0" y1="300" x2="1080" y2="300" stroke="white" stroke-width="0.5" opacity="0.5"/>
            <line x1="0" y1="450" x2="1080" y2="450" stroke="white" stroke-width="0.5" opacity="0.5"/>
            <line x1="270" y1="0" x2="270" y2="600" stroke="white" stroke-width="0.5" opacity="0.5"/>
            <line x1="540" y1="0" x2="540" y2="600" stroke="white" stroke-width="0.5" opacity="0.5"/>
            <line x1="810" y1="0" x2="810" y2="600" stroke="white" stroke-width="0.5" opacity="0.5"/>
            <!-- Curved latitude lines -->
            <ellipse cx="540" cy="300" rx="500" ry="200" fill="none" stroke="white" stroke-width="0.5" opacity="0.3"/>
            <ellipse cx="540" cy="300" rx="500" ry="100" fill="none" stroke="white" stroke-width="0.5" opacity="0.3"/>
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
        
        <!-- Date Badge (above title, right-aligned with dark bg) -->
        <?php if ($enable_date): ?>
        <div style="text-align: <?php echo esc_attr($date_position); ?>; padding: 0 35px; margin-bottom: 12px;">
            <span style="display: inline-block; color: #ffffff; font-size: 28px; font-weight: 700; background: <?php echo esc_attr($news24_date_bg); ?>; padding: 10px 25px; border-radius: 5px; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; letter-spacing: 1px; box-shadow: 0 2px 8px rgba(0,0,0,0.4);">
                <?php echo esc_html($formatted_date); ?>
            </span>
        </div>
        <?php endif; ?>

        <!-- Title - Golden bold on gradient -->
        <div id="pcd-adjustable-title" class="pcd-title" style="color: <?php echo esc_attr($news24_title_color); ?>; font-size: <?php echo esc_attr($default_font_size); ?>px; line-height: <?php echo esc_attr($default_line_height); ?>; font-weight: 900; text-align: <?php echo esc_attr($title_alignment); ?>; padding: 0 40px; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; word-wrap: break-word; overflow-wrap: break-word; text-shadow: 3px 3px 8px rgba(0,0,0,0.9), 0 0 25px rgba(0,0,0,0.6);">
            <?php echo esc_html($post_title); ?>
        </div>

        <!-- Details Button -->
        <?php if ($show_details_button): ?>
        <div style="text-align: center; padding: 18px 35px 8px;">
            <span style="color: #ffffff; font-size: 26px; font-weight: 600; font-family: '<?php echo esc_attr($title_font_family); ?>', 'Noto Sans Bengali', sans-serif; letter-spacing: 2px;">
                ❯❯ <?php echo esc_html($details_button_text); ?> ❮❮
            </span>
        </div>
        <?php endif; ?>

        <!-- Social Links Bar - dark bg at very bottom with colored icons -->
        <?php if ($show_facebook || $show_youtube || $show_website || $show_instagram || $show_linkedin): ?>
        <div style="background: rgba(0,0,0,0.85); padding: 16px 30px; margin-top: 10px; display: flex; justify-content: center; align-items: center; gap: 35px; flex-wrap: wrap;">
            <?php if ($show_facebook && !empty($facebook_text)): ?>
            <div style="display: flex; align-items: center; gap: 10px; color: white; font-size: 18px; font-weight: 500;">
                <svg width="22" height="22" viewBox="0 0 24 24"><circle cx="12" cy="12" r="12" fill="#1877f2"/><path d="M16.5 12.5h-2.5v7h-3v-7h-2v-2.5h2v-1.5c0-2.2 1-3.5 3.5-3.5h2v2.5h-1.5c-.8 0-1 .3-1 1v1.5h2.5l-.5 2.5z" fill="white"/></svg>
                <span><?php echo esc_html($facebook_text); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($show_youtube && !empty($youtube_text)): ?>
            <div style="display: flex; align-items: center; gap: 10px; color: white; font-size: 18px; font-weight: 500;">
                <svg width="22" height="22" viewBox="0 0 24 24"><rect width="24" height="24" rx="6" fill="#FF0000"/><path d="M10 8.5v7l5.5-3.5L10 8.5z" fill="white"/></svg>
                <span><?php echo esc_html($youtube_text); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($show_website && !empty($website_text)): ?>
            <div style="display: flex; align-items: center; gap: 10px; color: white; font-size: 18px; font-weight: 500;">
                <svg width="22" height="22" viewBox="0 0 24 24"><circle cx="12" cy="12" r="11" fill="#4CAF50"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" fill="white"/></svg>
                <span><?php echo esc_html($website_text); ?></span>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

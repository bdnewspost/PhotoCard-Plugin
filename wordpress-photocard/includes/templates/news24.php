<?php
/**
 * News24 Template - EXACT match to reference
 * 
 * Reference analysis:
 * - Full-bleed background image (1080x1080)
 * - Logo at TOP-RIGHT corner
 * - VERY heavy dark gradient overlay from bottom (~70%) - nearly black at bottom
 * - Subtle world map/globe SVG pattern visible in dark gradient area (more detailed)
 * - Date badge: dark blue bg (#1a3a5c), RIGHT-aligned, positioned just above title
 * - Title: Very large GOLDEN/YELLOW (#FFD700) bold text with heavy shadows
 * - ">> বিস্তারিত কমেন্টে <<" in white below title
 * - Social links bar at very bottom: dark bg with colored brand icons
 */
if (!defined('ABSPATH')) exit;

$news24_title_color = isset($options['news24_title_color']) ? $options['news24_title_color'] : '#FFD700';
$news24_date_bg = isset($options['news24_date_bg']) ? $options['news24_date_bg'] : '#1a3a5c';
?>
<div class="pcd-photocard" data-language="<?php echo esc_attr($language); ?>" data-quality="<?php echo esc_attr($image_quality); ?>" style="width: 1080px; height: 1080px; background: #0a0a14; padding: 0; position: relative; overflow: hidden; box-sizing: border-box;">
    
    <!-- Full Bleed Background Image -->
    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post_title); ?>" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;" crossorigin="anonymous">

    <!-- Dark Gradient Overlay - VERY heavy from bottom, nearly black -->
    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 100%; background: linear-gradient(to top, rgba(5,5,20,0.99) 0%, rgba(5,5,20,0.97) 15%, rgba(5,5,20,0.93) 25%, rgba(5,5,20,0.85) 35%, rgba(0,0,15,0.65) 48%, rgba(0,0,10,0.3) 62%, rgba(0,0,0,0.08) 75%, transparent 85%); z-index: 2;"></div>

    <!-- World Map / Globe Pattern Overlay (detailed, visible in dark area) -->
    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 65%; z-index: 3; opacity: 0.08; overflow: hidden; pointer-events: none;">
        <svg viewBox="0 0 1080 700" width="1080" height="700" style="display: block;">
            <!-- Detailed World Map Continents -->
            
            <!-- North America -->
            <path d="M120,80 Q130,55 155,45 Q180,35 210,40 Q240,30 270,38 Q300,28 330,45 Q355,35 375,55 Q395,65 405,85 Q415,110 408,140 Q400,165 385,190 Q370,215 345,235 Q320,248 295,242 Q270,235 255,225 Q235,218 220,205 Q200,195 185,175 Q170,155 158,135 Q145,115 130,100 Z" fill="white" opacity="0.7"/>
            <!-- Central America -->
            <path d="M255,245 Q270,240 280,255 Q285,268 278,280 Q270,290 258,288 Q248,280 250,265 Z" fill="white" opacity="0.6"/>
            
            <!-- South America -->
            <path d="M275,300 Q295,285 315,295 Q330,310 338,335 Q342,365 335,400 Q328,430 315,460 Q300,485 280,498 Q265,495 255,475 Q248,445 252,410 Q258,370 265,340 Z" fill="white" opacity="0.7"/>
            
            <!-- Europe -->
            <path d="M490,50 Q510,35 535,42 Q555,35 575,48 Q595,42 608,58 Q618,75 610,95 Q600,112 582,118 Q560,122 540,115 Q520,108 505,95 Q495,78 490,50 Z" fill="white" opacity="0.7"/>
            <!-- UK/Ireland -->
            <path d="M465,55 Q475,45 485,52 Q488,65 478,72 Q468,68 465,55 Z" fill="white" opacity="0.5"/>
            <!-- Scandinavia -->
            <path d="M535,18 Q548,10 558,22 Q565,35 555,48 Q545,42 535,35 Z" fill="white" opacity="0.5"/>
            
            <!-- Africa -->
            <path d="M510,130 Q535,118 558,128 Q580,142 598,168 Q612,200 618,240 Q620,280 615,320 Q608,360 592,395 Q575,425 555,445 Q535,455 518,445 Q505,425 498,395 Q492,355 495,315 Q498,270 502,235 Q506,195 510,160 Z" fill="white" opacity="0.7"/>
            <!-- Madagascar -->
            <path d="M625,370 Q632,360 638,370 Q640,390 635,405 Q628,400 625,385 Z" fill="white" opacity="0.4"/>
            
            <!-- Asia -->
            <path d="M610,30 Q645,18 685,22 Q725,18 765,28 Q800,35 835,52 Q865,68 885,92 Q900,115 905,142 Q902,168 885,185 Q860,198 830,195 Q798,188 765,175 Q732,162 700,148 Q668,135 642,115 Q622,95 615,68 Z" fill="white" opacity="0.7"/>
            <!-- India -->
            <path d="M720,165 Q740,155 752,172 Q758,195 752,220 Q742,240 728,248 Q715,240 712,218 Q710,195 715,175 Z" fill="white" opacity="0.6"/>
            <!-- Southeast Asia -->
            <path d="M790,195 Q810,185 825,198 Q835,215 828,235 Q815,245 800,238 Q788,225 790,208 Z" fill="white" opacity="0.5"/>
            
            <!-- Japan -->
            <path d="M895,78 Q905,68 912,80 Q915,95 908,108 Q898,105 895,92 Z" fill="white" opacity="0.5"/>
            
            <!-- Australia -->
            <path d="M830,320 Q860,305 895,310 Q925,320 942,342 Q948,368 935,390 Q915,408 888,412 Q858,408 838,392 Q822,372 825,348 Z" fill="white" opacity="0.7"/>
            <!-- New Zealand -->
            <path d="M960,410 Q968,402 975,412 Q978,428 970,438 Q962,432 960,420 Z" fill="white" opacity="0.4"/>
            
            <!-- Greenland -->
            <path d="M330,10 Q355,5 375,15 Q388,28 382,45 Q370,52 352,48 Q335,40 330,25 Z" fill="white" opacity="0.5"/>

            <!-- Grid lines - longitude -->
            <line x1="108" y1="0" x2="108" y2="700" stroke="white" stroke-width="0.5" opacity="0.25"/>
            <line x1="216" y1="0" x2="216" y2="700" stroke="white" stroke-width="0.5" opacity="0.25"/>
            <line x1="324" y1="0" x2="324" y2="700" stroke="white" stroke-width="0.5" opacity="0.25"/>
            <line x1="432" y1="0" x2="432" y2="700" stroke="white" stroke-width="0.5" opacity="0.25"/>
            <line x1="540" y1="0" x2="540" y2="700" stroke="white" stroke-width="0.5" opacity="0.25"/>
            <line x1="648" y1="0" x2="648" y2="700" stroke="white" stroke-width="0.5" opacity="0.25"/>
            <line x1="756" y1="0" x2="756" y2="700" stroke="white" stroke-width="0.5" opacity="0.25"/>
            <line x1="864" y1="0" x2="864" y2="700" stroke="white" stroke-width="0.5" opacity="0.25"/>
            <line x1="972" y1="0" x2="972" y2="700" stroke="white" stroke-width="0.5" opacity="0.25"/>
            
            <!-- Grid lines - latitude -->
            <line x1="0" y1="87" x2="1080" y2="87" stroke="white" stroke-width="0.5" opacity="0.25"/>
            <line x1="0" y1="175" x2="1080" y2="175" stroke="white" stroke-width="0.5" opacity="0.25"/>
            <line x1="0" y1="262" x2="1080" y2="262" stroke="white" stroke-width="0.5" opacity="0.25"/>
            <line x1="0" y1="350" x2="1080" y2="350" stroke="white" stroke-width="0.5" opacity="0.25"/>
            <line x1="0" y1="437" x2="1080" y2="437" stroke="white" stroke-width="0.5" opacity="0.25"/>
            <line x1="0" y1="525" x2="1080" y2="525" stroke="white" stroke-width="0.5" opacity="0.25"/>
            <line x1="0" y1="612" x2="1080" y2="612" stroke="white" stroke-width="0.5" opacity="0.25"/>
            
            <!-- Curved latitude lines (globe effect) -->
            <ellipse cx="540" cy="350" rx="530" ry="300" fill="none" stroke="white" stroke-width="0.4" opacity="0.2"/>
            <ellipse cx="540" cy="350" rx="530" ry="200" fill="none" stroke="white" stroke-width="0.4" opacity="0.2"/>
            <ellipse cx="540" cy="350" rx="530" ry="100" fill="none" stroke="white" stroke-width="0.4" opacity="0.15"/>
            <ellipse cx="540" cy="350" rx="530" ry="40" fill="none" stroke="white" stroke-width="0.4" opacity="0.1"/>
            
            <!-- Curved longitude lines (globe effect) -->
            <ellipse cx="540" cy="350" rx="50" ry="340" fill="none" stroke="white" stroke-width="0.4" opacity="0.15"/>
            <ellipse cx="540" cy="350" rx="150" ry="340" fill="none" stroke="white" stroke-width="0.4" opacity="0.15"/>
            <ellipse cx="540" cy="350" rx="280" ry="340" fill="none" stroke="white" stroke-width="0.4" opacity="0.15"/>
            <ellipse cx="540" cy="350" rx="420" ry="340" fill="none" stroke="white" stroke-width="0.4" opacity="0.15"/>
        </svg>
    </div>

    <!-- Subtle blue/dark navy tint in the dark area -->
    <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 55%; background: linear-gradient(to top, rgba(10,15,40,0.4) 0%, rgba(10,15,40,0.2) 50%, transparent 100%); z-index: 4; pointer-events: none;"></div>

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

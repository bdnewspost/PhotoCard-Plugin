=== Photocard Generator (Fixed) ===
Version: 1.1.0

All fixes applied:
1. Bengali numbers corrected (was using Hindi for 2-6)
2. Google Fonts loading fixed for all fonts
3. Permission system fixed - editor page now checks permissions
4. Added "author" permission level
5. News24 template added
6. Title editor added (editable text before download)
7. Line-wise color system added
8. XSS security fix - ad code sanitized with wp_kses_post
9. title_background_color sanitization fixed (allows 'transparent')
10. Consistent checkbox handling
11. Consistent default_line_height (1.3 everywhere)
12. Removed non-existent fonts (SolaimanLipi, Kalpurush, Mukti) from options

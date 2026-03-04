<?php
/**
 * License Manager for Photocard Generator Plugin
 * Handles license activation, validation, and management
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class PCD_License_Manager {

    private static $instance = null;
    private $license_key_option = 'pcd_license_key';
    private $license_status_option = 'pcd_license_status';
    private $license_expiry_option = 'pcd_license_expiry';
    private $api_url = 'https://hostercube.com/wp-json/lmfwc/v2/licenses/';

    /**
     * Get singleton instance
     */
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        add_action('admin_menu', array($this, 'add_license_menu'));
        add_action('admin_init', array($this, 'handle_license_actions'));
    }

    /**
     * Add license menu page
     */
    public function add_license_menu() {
        add_menu_page(
            'Photocard License',
            'Photocard License',
            'manage_options',
            'photocard-license',
            array($this, 'render_license_page'),
            'dashicons-admin-network',
            81
        );
    }

    /**
     * Handle license activation/deactivation
     */
    public function handle_license_actions() {
        // Activate license
        if (isset($_POST['pcd_activate_license']) && check_admin_referer('pcd_license_action', 'pcd_license_nonce')) {
            $license_key = isset($_POST['pcd_license_key']) ? sanitize_text_field(trim($_POST['pcd_license_key'])) : '';

            if (empty($license_key)) {
                add_settings_error('pcd_license', 'empty_key', 'লাইসেন্স কী দিন।', 'error');
                return;
            }

            // Validate license via API
            $result = $this->validate_license_remote($license_key);

            if ($result === true) {
                update_option($this->license_key_option, $license_key);
                update_option($this->license_status_option, 'active');
                update_option($this->license_expiry_option, date('Y-m-d', strtotime('+1 year')));
                add_settings_error('pcd_license', 'activated', 'লাইসেন্স সফলভাবে সক্রিয় হয়েছে! 🎉', 'success');
            } else {
                // For now, accept any non-empty key (offline mode)
                update_option($this->license_key_option, $license_key);
                update_option($this->license_status_option, 'active');
                update_option($this->license_expiry_option, date('Y-m-d', strtotime('+1 year')));
                add_settings_error('pcd_license', 'activated', 'লাইসেন্স সক্রিয় হয়েছে! 🎉', 'success');
            }
        }

        // Deactivate license
        if (isset($_POST['pcd_deactivate_license']) && check_admin_referer('pcd_license_action', 'pcd_license_nonce')) {
            delete_option($this->license_key_option);
            update_option($this->license_status_option, 'inactive');
            delete_option($this->license_expiry_option);
            add_settings_error('pcd_license', 'deactivated', 'লাইসেন্স নিষ্ক্রিয় করা হয়েছে।', 'updated');
        }
    }

    /**
     * Validate license via remote API
     */
    private function validate_license_remote($license_key) {
        $response = wp_remote_get(
            $this->api_url . 'validate/' . urlencode($license_key),
            array(
                'timeout' => 15,
                'headers' => array(
                    'Content-Type' => 'application/json',
                ),
            )
        );

        if (is_wp_error($response)) {
            return 'offline'; // Can't reach server, allow offline activation
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (isset($data['success']) && $data['success'] === true) {
            return true;
        }

        return false;
    }

    /**
     * Check if license is valid
     */
    public function is_license_valid() {
        $status = get_option($this->license_status_option, 'inactive');
        $license_key = get_option($this->license_key_option, '');

        if ($status !== 'active' || empty($license_key)) {
            return false;
        }

        // Check expiry
        $expiry = get_option($this->license_expiry_option, '');
        if (!empty($expiry) && strtotime($expiry) < time()) {
            update_option($this->license_status_option, 'expired');
            return false;
        }

        return true;
    }

    /**
     * Get current license key (masked)
     */
    public function get_masked_license_key() {
        $key = get_option($this->license_key_option, '');
        if (empty($key)) {
            return '';
        }
        if (strlen($key) <= 8) {
            return str_repeat('*', strlen($key));
        }
        return substr($key, 0, 4) . str_repeat('*', strlen($key) - 8) . substr($key, -4);
    }

    /**
     * Get license status
     */
    public function get_license_status() {
        return get_option($this->license_status_option, 'inactive');
    }

    /**
     * Render license page
     */
    public function render_license_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        settings_errors('pcd_license');

        $is_valid = $this->is_license_valid();
        $status = $this->get_license_status();
        $masked_key = $this->get_masked_license_key();
        $expiry = get_option($this->license_expiry_option, '');
        ?>
        <div class="wrap" style="max-width: 700px; margin: 30px auto;">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 16px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); text-align: center;">
                <h1 style="color: white; margin: 0 0 10px 0; font-size: 28px; font-weight: 700;">🎴 Photocard Generator</h1>
                <p style="color: rgba(255,255,255,0.9); margin: 0; font-size: 15px;">লাইসেন্স অ্যাক্টিভেশন ম্যানেজমেন্ট</p>
            </div>

            <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 15px rgba(0,0,0,0.08);">
                <?php if ($is_valid): ?>
                    <!-- Active License -->
                    <div style="text-align: center; padding: 20px 0;">
                        <div style="width: 80px; height: 80px; background: #22c55e; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="white"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                        </div>
                        <h2 style="color: #22c55e; margin: 0 0 10px 0;">✅ লাইসেন্স সক্রিয় আছে</h2>
                        <p style="color: #666; font-size: 14px;">লাইসেন্স কী: <code style="background: #f1f5f9; padding: 4px 10px; border-radius: 4px;"><?php echo esc_html($masked_key); ?></code></p>
                        <?php if (!empty($expiry)): ?>
                            <p style="color: #666; font-size: 13px;">মেয়াদ: <?php echo esc_html(date_i18n('d F Y', strtotime($expiry))); ?></p>
                        <?php endif; ?>
                    </div>

                    <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">

                    <form method="post" style="text-align: center;">
                        <?php wp_nonce_field('pcd_license_action', 'pcd_license_nonce'); ?>
                        <p style="color: #999; font-size: 13px; margin-bottom: 15px;">লাইসেন্স নিষ্ক্রিয় করলে প্লাগইনের সকল ফিচার বন্ধ হয়ে যাবে।</p>
                        <button type="submit" name="pcd_deactivate_license" class="button button-secondary" style="background: #ef4444; color: white; border-color: #ef4444; padding: 8px 25px;">
                            লাইসেন্স নিষ্ক্রিয় করুন
                        </button>
                    </form>

                <?php else: ?>
                    <!-- Inactive License -->
                    <div style="text-align: center; padding: 10px 0 20px 0;">
                        <div style="width: 80px; height: 80px; background: #f59e0b; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="white"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
                        </div>
                        <h2 style="color: #f59e0b; margin: 0 0 5px 0;">🔒 লাইসেন্স সক্রিয় করুন</h2>
                        <p style="color: #666; font-size: 14px;">প্লাগইন ব্যবহার করতে আপনার লাইসেন্স কী প্রবেশ করুন।</p>
                    </div>

                    <form method="post">
                        <?php wp_nonce_field('pcd_license_action', 'pcd_license_nonce'); ?>
                        <div style="margin-bottom: 20px;">
                            <label for="pcd_license_key" style="display: block; font-weight: 600; margin-bottom: 8px; color: #374151;">লাইসেন্স কী</label>
                            <input type="text" name="pcd_license_key" id="pcd_license_key" placeholder="XXXX-XXXX-XXXX-XXXX" style="width: 100%; padding: 12px 15px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 16px; letter-spacing: 1px; text-align: center; transition: border-color 0.3s;" onfocus="this.style.borderColor='#667eea'" onblur="this.style.borderColor='#e5e7eb'">
                        </div>
                        <button type="submit" name="pcd_activate_license" class="button button-primary" style="width: 100%; padding: 12px; font-size: 16px; font-weight: 600; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 8px; cursor: pointer; box-shadow: 0 4px 12px rgba(102,126,234,0.3);">
                            🔑 লাইসেন্স সক্রিয় করুন
                        </button>
                    </form>

                    <div style="margin-top: 20px; padding: 15px; background: #f0f9ff; border-radius: 8px; border-left: 4px solid #3b82f6;">
                        <p style="margin: 0; font-size: 13px; color: #1e40af;">
                            <strong>লাইসেন্স কী নেই?</strong><br>
                            <a href="https://hostercube.com" target="_blank" style="color: #3b82f6;">hostercube.com</a> থেকে লাইসেন্স কিনুন অথবা যোগাযোগ করুন।
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <p style="text-align: center; color: #999; font-size: 12px; margin-top: 20px;">
                Photocard Generator v<?php echo PCD_VERSION; ?> | By <a href="https://hostercube.com" target="_blank" style="color: #667eea;">HosterCube Ltd.</a>
            </p>
        </div>
        <?php
    }
}

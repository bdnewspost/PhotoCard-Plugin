<?php
/**
 * License Manager Class
 * Handles license key validation and activation for CUSTOMERS
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class PCD_License_Manager {

    private $option_name = 'pcd_license_data';
    private $license_server_url = null;
    private static $instance = null;

    public function __construct() {
        add_action('admin_menu', array($this, 'add_license_menu'), 5);
        add_action('admin_init', array($this, 'handle_license_actions'));
    }

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function get_license_server_url() {
        if ($this->license_server_url === null) {
            // Obfuscated URL using ASCII character codes
            $encoded = [104,116,116,112,115,58,47,47,108,105,99,101,110,115,101,46,117,112,100,117,109,46,99,111,109,47,119,112,45,106,115,111,110,47,112,99,100,45,108,105,99,101,110,115,101,47,118,49,47];
            $this->license_server_url = implode('', array_map('chr', $encoded));
        }
        return $this->license_server_url;
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
            array($this, 'license_page'),
            'dashicons-lock',
            2
        );
    }

    /**
     * Check if license is valid
     */
    public function is_license_valid() {
        $license_data = get_option($this->option_name);

        if (!$license_data || !isset($license_data['license_key'])) {
            return false;
        }

        if (!isset($license_data['status']) || $license_data['status'] !== 'active') {
            return false;
        }

        $current_domain = $this->get_current_domain();
        if (!isset($license_data['domain']) || $license_data['domain'] !== $current_domain) {
            return false;
        }

        // Check expiration date if exists
        if (isset($license_data['expires']) && $license_data['expires'] !== 'lifetime') {
            $expiry_date = strtotime($license_data['expires']);
            if ($expiry_date < time()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get current domain
     */
    private function get_current_domain() {
        $domain = $_SERVER['HTTP_HOST'];
        // Remove www. prefix
        $domain = preg_replace('/^www\./', '', $domain);
        return $domain;
    }

    /**
     * Validate license key with server
     */
    private function validate_license_key($license_key) {
        $current_domain = $this->get_current_domain();

        $response = wp_remote_post($this->get_license_server_url() . 'validate', array(
            'body' => array(
                'license_key' => $license_key,
                'domain' => $current_domain,
                'product' => 'photocard-downloader'
            ),
            'timeout' => 45,
            'sslverify' => false,
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded'
            )
        ));

        if (is_wp_error($response)) {
            return array(
                'success' => false,
                'message' => 'লাইসেন্স সার্ভারের সাথে সংযোগ করতে ব্যর্থ: ' . $response->get_error_message()
            );
        }

        $response_code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);

        if (empty($body)) {
            return array(
                'success' => false,
                'message' => 'লাইসেন্স সার্ভার থেকে কোন প্রতিক্রিয়া পাওয়া যায়নি। Response Code: ' . $response_code
            );
        }

        $data = json_decode($body, true);

        if (!$data) {
            return array(
                'success' => false,
                'message' => 'লাইসেন্স সার্ভার থেকে অবৈধ প্রতিক্রিয়া। Response: ' . substr($body, 0, 100)
            );
        }

        return $data;
    }

    /**
     * Activate license
     */
    public function activate_license($license_key) {
        $license_key = sanitize_text_field($license_key);

        if (empty($license_key)) {
            return array(
                'success' => false,
                'message' => 'লাইসেন্স কী খালি থাকতে পারবে না'
            );
        }

        // Validate with server
        $validation = $this->validate_license_key($license_key);

        if (!$validation['success']) {
            return $validation;
        }

        // Save license data
        $license_data = array(
            'license_key' => $license_key,
            'status' => 'active',
            'domain' => $this->get_current_domain(),
            'activated_at' => current_time('mysql'),
            'expires' => isset($validation['expires']) ? $validation['expires'] : 'lifetime',
            'customer_name' => isset($validation['customer_name']) ? $validation['customer_name'] : '',
            'customer_email' => isset($validation['customer_email']) ? $validation['customer_email'] : ''
        );

        update_option($this->option_name, $license_data);

        return array(
            'success' => true,
            'message' => 'লাইসেন্স সফলভাবে সক্রিয় করা হয়েছে!'
        );
    }

    /**
     * Deactivate license
     */
    public function deactivate_license() {
        $license_data = get_option($this->option_name);

        if (!$license_data || !isset($license_data['license_key'])) {
            return array(
                'success' => false,
                'message' => 'কোন সক্রিয় লাইসেন্স পাওয়া যায়নি'
            );
        }

        // Notify server about deactivation
        wp_remote_post($this->get_license_server_url() . 'deactivate', array(
            'body' => array(
                'license_key' => $license_data['license_key'],
                'domain' => $this->get_current_domain()
            ),
            'timeout' => 15,
            'sslverify' => false
        ));

        // Delete license data locally regardless of server response
        delete_option($this->option_name);

        return array(
            'success' => true,
            'message' => 'লাইসেন্স নিষ্ক্রিয় করা হয়েছে'
        );
    }

    /**
     * Handle license actions
     */
    public function handle_license_actions() {
        try {
            if (!isset($_POST['pcd_license_action']) || !isset($_POST['pcd_license_nonce'])) {
                return;
            }

            if (!wp_verify_nonce($_POST['pcd_license_nonce'], 'pcd_license_action')) {
                return;
            }

            if (!current_user_can('manage_options')) {
                return;
            }

            $action = $_POST['pcd_license_action'];

            if ($action === 'activate') {
                $license_key = isset($_POST['license_key']) ? $_POST['license_key'] : '';
                $result = $this->activate_license($license_key);

                if ($result['success']) {
                    add_settings_error('pcd_license', 'license_activated', $result['message'], 'success');
                } else {
                    add_settings_error('pcd_license', 'license_error', $result['message'], 'error');
                }
            } elseif ($action === 'deactivate') {
                $result = $this->deactivate_license();

                if ($result['success']) {
                    add_settings_error('pcd_license', 'license_deactivated', $result['message'], 'success');
                } else {
                    add_settings_error('pcd_license', 'license_error', $result['message'], 'error');
                }
            }

            set_transient('pcd_license_messages', get_settings_errors('pcd_license'), 30);

            wp_redirect(admin_url('admin.php?page=photocard-license'));
            exit;
        } catch (Exception $e) {
            add_settings_error('pcd_license', 'license_exception', 'একটি ত্রুটি ঘটেছে: ' . $e->getMessage(), 'error');
            set_transient('pcd_license_messages', get_settings_errors('pcd_license'), 30);
            wp_redirect(admin_url('admin.php?page=photocard-license'));
            exit;
        }
    }

    /**
     * License page HTML - CUSTOMER VERSION (NO GENERATOR)
     */
    public function license_page() {
        $license_data = get_option($this->option_name);
        $is_active = $this->is_license_valid();

        // Show messages
        $messages = get_transient('pcd_license_messages');
        if ($messages) {
            delete_transient('pcd_license_messages');
            foreach ($messages as $message) {
                ?>
                <div class="notice notice-<?php echo esc_attr($message['type']); ?> is-dismissible">
                    <p><?php echo esc_html($message['message']); ?></p>
                </div>
                <?php
            }
        }
        ?>
        <div class="wrap pcd-license-wrap">
            <h1>🔐 Photocard Downloader License</h1>

            <div class="pcd-license-container" style="max-width: 800px; margin-top: 30px;">

                <?php if ($is_active): ?>
                    <div class="pcd-license-active" style="background: #d4edda; border: 2px solid #28a745; border-radius: 8px; padding: 30px; margin-bottom: 30px;">
                        <div style="display: flex; align-items: center; margin-bottom: 20px;">
                            <span class="dashicons dashicons-yes-alt" style="font-size: 48px; color: #28a745; margin-right: 15px;"></span>
                            <div>
                                <h2 style="margin: 0; color: #155724;">লাইসেন্স সক্রিয় আছে</h2>
                                <p style="margin: 5px 0 0 0; color: #155724;">আপনার প্লাগিন সম্পূর্ণভাবে কার্যকর</p>
                            </div>
                        </div>

                        <div class="pcd-license-details" style="background: white; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
                            <table class="widefat" style="border: none;">
                                <tr>
                                    <td style="width: 200px; font-weight: bold;">লাইসেন্স কী:</td>
                                    <td>
                                        <code style="background: #f5f5f5; padding: 5px 10px; border-radius: 3px;"><?php echo esc_html($license_data['license_key']); ?></code>
                                        <button type="button" class="button button-small pcd-copy-license" data-license="<?php echo esc_attr($license_data['license_key']); ?>" style="margin-left: 10px;">কপি করুন</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold;">ডোমেইন:</td>
                                    <td><?php echo esc_html($license_data['domain']); ?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold;">সক্রিয় করা হয়েছে:</td>
                                    <td><?php echo esc_html(date('d F Y, h:i A', strtotime($license_data['activated_at']))); ?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold;">মেয়াদ শেষ:</td>
                                    <td>
                                        <?php
                                        if ($license_data['expires'] === 'lifetime') {
                                            echo '<strong style="color: #28a745;">আজীবন</strong>';
                                        } else {
                                            echo esc_html(date('d F Y', strtotime($license_data['expires'])));
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php if (!empty($license_data['customer_name'])): ?>
                                <tr>
                                    <td style="font-weight: bold;">গ্রাহকের নাম:</td>
                                    <td><?php echo esc_html($license_data['customer_name']); ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>

                        <form method="post" action="" id="pcd-deactivate-form">
                            <?php wp_nonce_field('pcd_license_action', 'pcd_license_nonce'); ?>
                            <input type="hidden" name="pcd_license_action" value="deactivate">
                            <button type="submit" class="button button-secondary" onclick="return confirm('আপনি কি নিশ্চিত যে আপনি লাইসেন্স নিষ্ক্রিয় করতে চান?');">
                                লাইসেন্স নিষ্ক্রিয় করুন
                            </button>
                            <a href="<?php echo admin_url('options-general.php?page=photocard-downloader'); ?>" class="button button-primary" style="margin-left: 10px;">
                                <span class="dashicons dashicons-admin-settings" style="margin-top: 3px;"></span>
                                প্লাগইন সেটিংস
                            </a>
                        </form>
                    </div>

                <?php else: ?>
                    <div class="pcd-license-inactive" style="background: #f8d7da; border: 2px solid #dc3545; border-radius: 8px; padding: 30px; margin-bottom: 30px;">
                        <div style="display: flex; align-items: center; margin-bottom: 20px;">
                            <span class="dashicons dashicons-lock" style="font-size: 48px; color: #dc3545; margin-right: 15px;"></span>
                            <div>
                                <h2 style="margin: 0; color: #721c24;">লাইসেন্স সক্রিয় নেই</h2>
                                <p style="margin: 5px 0 0 0; color: #721c24;">প্লাগিন ব্যবহার করতে লাইসেন্স কী প্রবেশ করান</p>
                            </div>
                        </div>

                        <form method="post" action="" id="pcd-activate-form" style="background: white; padding: 20px; border-radius: 5px;">
                            <?php wp_nonce_field('pcd_license_action', 'pcd_license_nonce'); ?>
                            <input type="hidden" name="pcd_license_action" value="activate">

                            <table class="form-table">
                                <tr>
                                    <th scope="row">
                                        <label for="license_key">লাইসেন্স কী</label>
                                    </th>
                                    <td>
                                        <input type="text" name="license_key" id="license_key" class="regular-text" placeholder="XXXX-XXXX-XXXX-XXXX-XXXX" required>
                                        <p class="description">আপনার ক্রয়কৃত লাইসেন্স কী প্রবেশ করান</p>
                                    </td>
                                </tr>
                            </table>

                            <p class="submit">
                                <button type="submit" class="button button-primary button-large" id="pcd-activate-btn">
                                    লাইসেন্স সক্রিয় করুন
                                </button>
                            </p>
                        </form>
                    </div>
                <?php endif; ?>

                <div class="pcd-license-info" style="background: #e7f3ff; border: 1px solid #b3d9ff; border-radius: 8px; padding: 20px;">
                    <h3 style="margin-top: 0;">ℹ️ লাইসেন্স সম্পর্কে তথ্য</h3>
                    <ul style="line-height: 1.8;">
                        <li>প্রতিটি লাইসেন্স কী শুধুমাত্র একটি ডোমেইনের জন্য বৈধ</li>
                        <li>লাইসেন্স সক্রিয় না থাকলে প্লাগিন কাজ করবে না</li>
                        <li>ডোমেইন পরিবর্তন করলে নতুন লাইসেন্স কী প্রয়োজন হবে</li>
                        <li>লাইসেন্স সমস্যার জন্য সাপোর্ট টিমের সাথে যোগাযোগ করুন</li>
                    </ul>

                    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #b3d9ff;">
                        <strong>সাপোর্ট:</strong> <a href="https://wa.me/send?phone=8801744977947&text=Hello%20PhotoCard%20Support%20Team%2C%20Need%20Help!" target="_blank">(+88) 01744977947 (WhatsAPP)</a><br>
                        <strong>ইমেইল:</strong> <a href="mailto:support@hostercube.com">support@hostercube.com</a>
                    </div>
                </div>
            </div>
        </div>

        <style>
        .pcd-license-wrap {
            font-family: 'Noto Sans Bengali', sans-serif;
        }
        .pcd-license-wrap h1 {
            font-size: 28px;
            font-weight: 600;
        }
        .pcd-license-wrap table td {
            padding: 12px 10px;
        }
        .pcd-license-wrap .button-large {
            height: 40px;
            line-height: 38px;
            font-size: 16px;
            padding: 0 30px;
        }
        </style>

        <script>
        jQuery(document).ready(function($) {
            var formSubmitting = false;

            $('#pcd-activate-form, #pcd-deactivate-form').on('submit', function(e) {
                if (formSubmitting) {
                    e.preventDefault();
                    return false;
                }

                formSubmitting = true;
                var $btn = $(this).find('button[type="submit"]');
                $btn.prop('disabled', true).text('অপেক্ষা করুন...');

                // Re-enable after 5 seconds as fallback
                setTimeout(function() {
                    formSubmitting = false;
                    $btn.prop('disabled', false);
                }, 5000);
            });

            $('.pcd-copy-license').on('click', function() {
                var license = $(this).data('license');
                var $temp = $('<input>');
                $('body').append($temp);
                $temp.val(license).select();
                document.execCommand('copy');
                $temp.remove();
                $(this).text('কপি হয়েছে!');
                setTimeout(() => {
                    $(this).text('কপি করুন');
                }, 2000);
            });
        });
        </script>
        <?php
    }
}

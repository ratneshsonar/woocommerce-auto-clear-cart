<?php
/**
 * Plugin Name: WooCommerce Auto Clear Cart
 * Description: Automatically clears WooCommerce cart and sessions based on selected interval.
 * Version: 1.2
 * Author: Ratnesh Sonar
 * Author URI: https://ratnesh.dev
 * Plugin URI: https://github.com/ratneshsonar/woocommerce-auto-clear-cart
 */

if (!defined('ABSPATH')) exit;
// Deactivate this plugin if WooCommerce is not active
add_action('admin_init', function() {
    if (!is_plugin_active('woocommerce/woocommerce.php')) {
        deactivate_plugins(plugin_basename(__FILE__));
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p><strong>WooCommerce Auto Clear Cart</strong> requires WooCommerce to be installed and active. The plugin has been deactivated.</p></div>';
        });
    }
});


define('WACC_OPTION_ENABLED', 'wacc_enable_clear_cart');
define('WACC_OPTION_INTERVAL', 'wacc_clear_cart_interval');
define('WACC_OPTION_LOGGING', 'wacc_enable_logging');

register_activation_hook(__FILE__, 'wacc_schedule_event');
register_deactivation_hook(__FILE__, 'wacc_clear_scheduled_event');

add_action('admin_menu', 'wacc_add_settings_page');
add_action('admin_init', 'wacc_register_settings');
add_filter('cron_schedules', 'wacc_custom_cron_intervals');
add_action('wacc_clear_cart_event', 'wacc_clear_cart_sessions');

function wacc_custom_cron_intervals($schedules) {
    $schedules['every_15_minutes'] = array('interval' => 900, 'display' => __('Every 15 Minutes'));
    $schedules['every_30_minutes'] = array('interval' => 1800, 'display' => __('Every 30 Minutes'));
    $schedules['every_60_minutes'] = array('interval' => 3600, 'display' => __('Every 60 Minutes'));
    return $schedules;
}

function wacc_schedule_event() {
    $enabled = get_option(WACC_OPTION_ENABLED, 'yes');
    $interval = get_option(WACC_OPTION_INTERVAL, 'every_15_minutes');
    if ($enabled === 'yes' && !wp_next_scheduled('wacc_clear_cart_event')) {
        wp_schedule_event(time(), $interval, 'wacc_clear_cart_event');
    }
}

function wacc_clear_scheduled_event() {
    wp_clear_scheduled_hook('wacc_clear_cart_event');
}

function wacc_clear_cart_sessions() {
    $enabled = get_option(WACC_OPTION_ENABLED, 'yes');
    if ($enabled !== 'yes') return;

    global $wpdb;
    $table = $wpdb->prefix . 'woocommerce_sessions';
    $wpdb->query("TRUNCATE TABLE $table");

    if (get_option(WACC_OPTION_LOGGING, 'no') === 'yes') {
        $log_dir = wp_upload_dir()['basedir'];
        $log_file = $log_dir . '/auto-clear-cart-log.txt';
        $log_entry = "[WooCommerce Auto Clear Cart] Sessions cleared at " . current_time('mysql') . "\n";
        if (is_writable(dirname($log_file))) { file_put_contents($log_file, $log_entry, FILE_APPEND); }
    }
}

function wacc_add_settings_page() {
    add_submenu_page(
        'woocommerce',
        'Auto Clear Cart',
        'Auto Clear Cart',
        'manage_options',
        'wacc-settings',
        'wacc_settings_page_html'
    );
}

function wacc_register_settings() {
    register_setting('wacc_settings_group', WACC_OPTION_ENABLED, ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('wacc_settings_group', WACC_OPTION_INTERVAL, ['sanitize_callback' => 'sanitize_text_field']);
    register_setting('wacc_settings_group', WACC_OPTION_LOGGING, ['sanitize_callback' => 'sanitize_text_field']);
}

function wacc_settings_page_html() {
    ?>
    <div class="wrap">
        <h1>WooCommerce Auto Clear Cart Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('wacc_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Enable Auto Clear Cart</th>
                    <td>
                        <label><input type="radio" name="<?php echo esc_attr(WACC_OPTION_ENABLED); ?>" value="yes" <?php checked('yes', get_option(WACC_OPTION_ENABLED, 'yes')); ?>> Yes</label><br>
                        <label><input type="radio" name="<?php echo esc_attr(WACC_OPTION_ENABLED); ?>" value="no" <?php checked('no', get_option(WACC_OPTION_ENABLED)); ?>> No</label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Clear Interval</th>
                    <td>
                        <select name="<?php echo esc_attr(WACC_OPTION_INTERVAL); ?>">
                            <option value="every_15_minutes" <?php selected(get_option(WACC_OPTION_INTERVAL), 'every_15_minutes'); ?>>Every 15 Minutes</option>
                            <option value="every_30_minutes" <?php selected(get_option(WACC_OPTION_INTERVAL), 'every_30_minutes'); ?>>Every 30 Minutes</option>
                            <option value="every_60_minutes" <?php selected(get_option(WACC_OPTION_INTERVAL), 'every_60_minutes'); ?>>Every 60 Minutes</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Enable Logging</th>
                    <td>
                        <label><input type="radio" name="<?php echo esc_attr(WACC_OPTION_LOGGING); ?>" value="yes" <?php checked('yes', get_option(WACC_OPTION_LOGGING, 'no')); ?>> Yes</label><br>
                        <label><input type="radio" name="<?php echo esc_attr(WACC_OPTION_LOGGING); ?>" value="no" <?php checked('no', get_option(WACC_OPTION_LOGGING, 'no')); ?>> No</label>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

add_action('update_option_' . WACC_OPTION_INTERVAL, 'wacc_reset_schedule_on_change', 10, 2);
function wacc_reset_schedule_on_change($old_value, $value) {
    wacc_clear_scheduled_event();
    if (get_option(WACC_OPTION_ENABLED, 'yes') === 'yes') {
        wp_schedule_event(time(), $value, 'wacc_clear_cart_event');
    }
}

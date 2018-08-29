<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Plugin_Name
 *
 * @wordpress-plugin
 * Plugin Name:       Elementor Test
 * Plugin URI:
 * Description:       Extra Elementor Additions
 * Version:           0.1
 * Author:            Me
 * Author URI:
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plugin-name
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
    die;
}

final class WP_Elementor_Extra_Additions
{
    // const VERSION;
    // const MINIMUM_ELEMENTOR_VERSION;
    // const MINIMUM_PHP_VERSION;

    private static $_instance = null;
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }return self::$_instance;
    }

    public function __construct()
    {
        // add_action('admin_menu', array($this, 'wp_add_menu'));
        register_activation_hook(__FILE__, array($this, 'wpa_install'));
        register_deactivation_hook(__FILE__, array($this, 'wpa_uninstall'));
        add_action('init', [$this, 'i18n']);
        add_action('plugins_loaded', [$this, 'init']);
    }
    public function i18n()
    {
        load_plugin_textdomain('elementor-extra-additions');
    }
    public function init()
    {
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return;
        }

        add_action('elementor/widgets/widgets_registered', [$this, 'init_widgets']);
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'widget_styles']);
        // add_action('elementor/controls/controls_registered', [$this, 'init_controls']);
    }
    public function widget_styles()
    {
        wp_register_style('wp_elementor_ea-split-header', plugins_url('public/css/wp_elementor_ea-split-header.css', __FILE__));
        wp_enqueue_style('wp_elementor_ea-split-header');
    }
    public function init_widgets()
    {
        require_once __DIR__ . '/widgets/widget--split-header.php';
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor_EA_Split_Header_Widget());
    }
    public function init_controls()
    {

        // Include Control files
        // require_once __DIR__ . '/controls/test-control.php';

        // Register control
        // \Elementor\Plugin::$instance->controls_manager->register_control('control-type-', new \Test_Control());

    }
    public function admin_notice_missing_main_plugin()
    {

        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'elementor-test-extension'),
            '<strong>' . esc_html__('Elementor Test Extension', 'elementor-test-extension') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'elementor-test-extension') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);

    }

    public function includes()
    {}

    public function wp_add_menu()
    {
        add_menu_page('Elementor Extra Additions', 'Elementor Extra Additions', 'manage_options', 'elementor-ea-options', array(__CLASS__, 'wpa_page_file_path'));
    }

}

WP_Elementor_Extra_Additions::instance();

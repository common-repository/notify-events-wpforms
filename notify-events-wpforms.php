<?php
/*
Plugin Name: Notify.Events - WPForms
Plugin URI: https://notify.events/en/source/79
Description: Fast and simplest way to integrate WPForms plugin with more then 30 messengers and platforms including SMS, Voicecall, Facebook messenger, VK, Telegram, Viber, Slack and etc.
Author: Notify.Events
Author URI: https://notify.events/
Version: 1.0.0
License: GPL-2.0
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: notify-events-wpforms
Domain Path: /languages/
*/

require_once ABSPATH . 'wp-admin/includes/plugin.php';

use notify_events\modules\wpforms\models\WPForms;

const WPNE_WPF = 'notify-events-wpforms';

spl_autoload_register(function($class) {
    if (stripos($class, 'notify_events\\modules\\wpforms\\') !== 0) {
        return;
    }

    $class_file = __DIR__ . '/' . str_replace(['notify_events\\', '\\'], ['', '/'], $class . '.php');

    if (!file_exists($class_file)) {
        return;
    }

    require_once $class_file;
});

register_activation_hook(__FILE__, function() {
    if (!is_plugin_active('notify-events/notify-events.php') and current_user_can('activate_plugins')) {
        wp_die(__('Sorry, but this plugin requires the <a href="https://ru.wordpress.org/plugins/notify-events/" target="_blank">Notify.Events</a> plugin to be installed and active.<br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>', WPNE_WPF), __('Plugin required', WPNE_WPF));
    }

    if (!is_plugin_active('wpforms-lite/wpforms.php') and !is_plugin_active('wpforms/wpforms.php') and current_user_can('activate_plugins')) {
        wp_die(__('Sorry, but this plugin requires the <a href="https://ru.wordpress.org/plugins/wpforms-lite/" target="_blank">WPForms</a> plugin to be installed and active.<br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>', WPNE_WPF), __('Plugin required', WPNE_WPF));
    }
});

add_action('plugins_loaded', function() {
    if (!is_plugin_active('notify-events/notify-events.php')) {
        deactivate_plugins('notify-events-wpforms/notify-events-wpforms.php');
        return;
    }

    if (!is_plugin_active('wpforms-lite/wpforms.php') && !is_plugin_active('wpforms/wpforms.php')) {
        deactivate_plugins('notify-events-wpforms/notify-events-wpforms.php');
        return;
    }
});

add_action('wpne_module_init', function() {
    WPForms::register();
});

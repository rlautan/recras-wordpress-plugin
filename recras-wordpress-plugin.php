<?php
/*
Plugin Name: Recras WordPress Plugin
Plugin URI: https://www.recras.nl/
Description: Easily integrate your Recras data into your own site
Author: Recras
Text Domain: recras-wp
Domain Path: /lang
Version: 1.8.1.1

Author URI: https://www.recras.nl/
*/

// Debugging
if (WP_DEBUG) {
    error_reporting(-1);
    ini_set('display_errors', 'On');
}

try {
    require_once('vendor/autoload.php');
    $recras = new \Recras\Plugin;
} catch (Exception $e) {
    if (version_compare(phpversion(), '5.4', '<')) {
        function phpVersionTooOld() {
            echo '<div class="error"><p>';
            echo 'The Recras WordPress plugin requires PHP 5.4 or higher and will not be activated. Your server is running PHP version ' . phpversion();
            echo 'Any PHP version less than 5.6.0 has reached "End of Life" and no longer receives bugfixes or security updates.';
            echo 'The official information on how to update and why can be found on <a href="https://secure.php.net/supported-versions.php" target="_blank"><strong>the PHP website</strong></a>';
            echo '</p></div>';
        }
        add_action('admin_notices', 'phpVersionTooOld');
        return;
    }
}

<?php
/*
Plugin Name: Recras WordPress Plugin
Plugin URI: http://www.recras.nl/
Description: Easily integrate your Recras data into your own site
Author: Recras
Version: 1.7.1

Author URI: http://www.recras.nl/
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
            echo 'The official information on how to update and why at <a href="http://php.net/eol.php" target="_blank"><strong>php.net/eol.php</strong></a>';
            echo '</p></div>';
        }
        add_action('admin_notices', 'phpVersionTooOld');
        return;
    }
}

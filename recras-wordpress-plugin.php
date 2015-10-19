<?php

/**
 * @package Recras WordPress Plugin
 * @version 0.13.2
 */
/*
Plugin Name: Recras WordPress Plugin
Plugin URI: http://www.recras.nl/
Description: Easily integrate your Recras data into your own site
Author: Recras
Version: 0.13.2

Author URI: http://www.recras.nl/
*/

// Debugging
if (WP_DEBUG) {
    error_reporting(-1);
    ini_set('display_errors', 'On');
}

require_once('vendor/autoload.php');
$recras = new \Recras\Plugin;

<?php

/*
Plugin name: Greg-OR Exchange
Plugin URI: http://greg-or.pl/wordpress-plugins/greg-or-exchange
Description: Currency exchange plugin. Application work for <a href="https://welearning.pl/" target="_blank">WE Learning</a>.
Version: 1.0.0
Author: Greg Kepa
Author URI: http://greg-or.pl
License: GPLv2 or later
Txt domain: greg-or-exchange
*/

if (!\function_exists('add_action')) {
    die('Hi there!  I\'m just a plugin, not much I can do when called directly.');
}
$dirname = \dirname(__FILE__);
if (true === file_exists($dirname . '/vendor/autoload.php')) {
    require_once($dirname . '/vendor/autoload.php');
}

if (\class_exists('GregOrPlugin\\GregOrExchange\\Init')) {
    GregOrPlugin\GregOrExchange\Init::registerServices();
}

<?php

namespace GregOrPlugin;

if (!defined('WP_UNINSTALL_PLUGIN')) {
    \die('Plugin is not uninstalled in correct context.');
}

$exchangeRates = \get_posts(['post_type' => 'exchange', 'numberposts' => -1]);

foreach ($exchangeRates as $exchangeRate) {
    \wp_delete_post($exchangeRate->ID, true);
}

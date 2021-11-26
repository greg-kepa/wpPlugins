<?php

namespace GregOrPlugin\GregOrExchange\Pages;

use \GregOrPlugin\GregOrExchange\BaseController;

class Admin extends BaseController
{
    public function register(): void
    {
        \add_action('admin_menu', [$this, 'addAdminPages']);
    }

    public function addAdminPages(): void
    {
        add_menu_page(
            __('Exchange rates Plugin'),
            __('Exchange rates'),
            'manage_options',
            'greg_or_exchange_plugin',
            [$this, 'adminIndex'],
            'dashicons-money-alt',
            1
        );
    }

    public function adminIndex(): void
    {
        require_once $this->getPluginPath() . 'templates/admin.php';
    }
}

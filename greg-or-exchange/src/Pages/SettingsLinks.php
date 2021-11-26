<?php

namespace GregOrPlugin\GregOrExchange\Pages;

use \GregOrPlugin\GregOrExchange\BaseController;

class SettingsLinks extends BaseController
{
    public function register(): void
    {
        \add_filter(sprintf('plugin_action_links_%s', $this->getPluginBaseName()), [$this, 'settingsLink']);
    }

    public function settingsLink(array $links): array
    {
        $settingsLink = sprintf(
            '<a href="options-general.php?page=greg_or_exchange_plugin">%s</a>',
            \__('Exchange Settings')
        );
        \array_push($links, $settingsLink);
        return $links;
    }
}

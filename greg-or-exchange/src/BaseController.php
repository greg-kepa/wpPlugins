<?php

namespace GregOrPlugin\GregOrExchange;

class BaseController
{
    private string $pluginPath;
    private string $pluginBaseName;
    private string $pluginUrl;

    public function __construct()
    {
        $this->pluginPath = \plugin_dir_path(\dirname(__FILE__, 1));
        $this->pluginUrl = \plugin_dir_url(\dirname(__FILE__, 1));

        $this->pluginBaseName = \plugin_basename(\dirname(__FILE__, 2) . '/greg-or-exchange.php');
    }

    public function getPluginPath()
    {
        return $this->pluginPath;
    }

    public function getPluginUrl()
    {
        return $this->pluginUrl;
    }

    public function getPluginBaseName()
    {
        return $this->pluginBaseName;
    }
}
